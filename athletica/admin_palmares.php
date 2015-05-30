<?php

/********************
 *
 *	admin_palmares.php
 *	---------
 *	login form for getting the palamres data from the slv web system
 *
 *******************/

$noMeetingCheck = true;
 
require('./lib/cl_gui_menulist.lib.php');
require('./lib/cl_gui_page.lib.php');

require('./lib/common.lib.php');

//require('./lib/cl_xml_data.lib.php');
require('./lib/cl_http_data.lib.php');

require('./lib/cl_xml_simple_data.lib.php');   

if(AA_connectToDB() == FALSE)	{		// invalid DB connection
	return;
}

/*if(AA_checkMeetingID() == FALSE){		// no meeting selected
	return;		// abort
}*/

//
//	Display enrolement list
//

$page = new GUI_Page('admin_palmares');
$page->startPage();
$page->printPageTitle($strPalmaresUpdate);

$menu = new GUI_Menulist();
$menu->addButton($cfgURLDocumentation . 'help/administration/palmares.html', $strHelp, '_blank');
$menu->printMenu();
?>
<p/>

<?php

// handle reset request


	
	
	

$http = new HTTP_data();
$webserverDomain = $cfgSLVhost; // domain of swiss-athletics webserver

// handle arguments
$login = false;
$slvsid = 0;
$basefiles = array();
$filetype = "";
$newglc = "";
// login attempt on the slv server
if($_POST['arg'] == "login"){
	
	// sending login information and global last change date of the palamres_data
	/*$result = mysql_query("SELECT global_last_change FROM base_log where type like 'base_%' ORDER BY id_log DESC LIMIT 1");
	if(mysql_errno > 0){
		AA_printErrorMsg(mysql_errno().": ".mysql_error());
	}else{
		// the following information is used to determinate wich base file we need to download
		if(mysql_num_rows($result) == 0){
			$glc = "";
			$type = "complete"; // for a complete base data download
		}else{
			$row = mysql_fetch_array($result);
			$glc = $row[0];
			$type = "update"; // if there is already a log entry, a complete download was made once
		}
		
		mysql_free_result($result);
		*/
		//$result = $http->send_get('slv.exigo.ch', '/downloads/verbandstagung_201104.ppt' , 'file', 'test.ppt', true);
        $glc = ""; 
		$post = "clubnr=".urlencode($_POST['clubnr'])."&pass=".urlencode($_POST['pass'])
			."&glc=".urlencode($glc)."&type=".urlencode($type);
		
		$result = $http->send_post($webserverDomain, '/meetings/athletica/login.php', $post, 'ini');		
		//TEST mit XML aus Ordner basdata_test
		//$result = $http->send_post($webserverDomain, '/meetings/athletica/login_test.php', $post, 'ini');
		
		if(!$result){
			AA_printErrorMsg($strErrLogin);
		}else{
			switch($result['login']){
				case "error":
				AA_printErrorMsg($result['error']);
				break;
				
				case "ok":
				$login = true;
				echo "<p>$strLoginTrue</p>";
				$slvsid = $result['sid']; // remember session id from slv server
				$basefiles = explode(":",$result['files']); // get files to download
				$filetype = $result['filetype']; // return complete or update, 
								//maybe the glc-date is too old, so the login script returned a complete set
				$newglc = substr($result["newglc"],0,4)."-".substr($result["newglc"],4,2)."-".substr($result["newglc"],6,2);
				break;
				
				case "denied":
				$login = false;
				echo "<p>$strLoginFalse</p>";
				break;
			}
		}
	}


// start download of files
if($login) {// show download process
	// start download
	
		?>

		<p><?php echo $strBaseDownload ?></p>
		

		<?php
		/*
		 * update process ----------------------------------------------------------------------------------
		*/
		set_time_limit(3600); // the script will break if this is not set
		
		$i = 0;
         // Palmares 
   //$xml = new XML_data(); 
   // $xml->load_xml("C:/Programme/Apache Group/Apache2/htdocs/athletica_SVN/athletica/tmp/Palmares/palmares.xml", "palmares");
    
    $xml = new XML_simple_data();                 
   // $arr_noCat = $xml->load_xml_simple($_FILES['xmlfile']['tmp_name'], 'regUKC', '', $ukc_meeting);        
    
    
		  
				//echo "<p>".date("H:i:s")."</p>";
				// start parsing xml file
				echo "<p> $strBaseProcessing ... <b>$strPleaseWait</b> ";
				ob_flush();
				flush();
				
				$arr_noCat = $xml->load_xml_simple('C:/Programme/Apache Group/Apache2/htdocs/athletica_SVN/athletica/tmp/Palmares/palmares.xml' , 'palmares', '', $ukc_meeting);    
    
				echo " OK!</p>\n";
				//echo "<p>".date("H:i:s")."</p>";
			
			
		
		
		//$xml->load_xml("D:/Programme/athletica/www/athletica/tmp/20070219_full.gz", "base");
		
		
		
		?>
<script type="text/javascript">
document.getElementById("progress").width="150";
</script>
		<?php
		
		
	
   
  
	//
	// output form for next step: getting registrations
	//
	?>

<table class='dialog'>
<tr><td>
<?php echo $strPalmaresUpdated ?>


</td></tr>
<?php
if(isset($_SESSION['meeting_infos']) && count($_SESSION['meeting_infos'])>0){
	?>
	<form name="export" action="admin_registration.php" target="_self" method="post">
	<input type="hidden" name="slvsid" value="<?php echo $slvsid; ?>">
	<input type="hidden" name="arg" value="list">
	
	</form>
	<?php
}
?>
</table>

	<?php
	
}else{ // show login form

?>
<form action='admin_palmares.php' name='base' method='post' target='_self'>
 <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tbody><tr>
            <td style="vertical-align: top;" width="260">
                <table class="dialog" width="260">
                    <tbody><tr>

                        <th><?php echo $strConfiguration; ?></th>
                    </tr>
                    <tr>
                        <td>
                          <p><?php echo $strEffortsUpdateInfo4; ?></p>
                          <p>
                            <label>
                              <input type="radio" name="mode" value="overwrite" id="mode_0" checked="checked" />
                              <?php echo $strOverwrite;?></label>
                            <br />
                            <label>
                              <input type="radio" name="mode" value="skip" id="mode_1" />
                              <?php echo$strLeaveBehind ;?></label>
                            <br />
                          </p></td>
                    </tr>
                </tbody>
        </table>
 <br />
<table class='dialog'>
<tr>
	<th><?php echo $strLoginSlv; ?></th>
</tr>
<tr>
	<td>
   
    
    
		<table class='admin'>
		
		<input type="hidden" name="arg" value="login">
        <tr>
            <td>
        <?php echo $strClubNr ?>
            </td>
            <td>
                <input type="text" name="clubnr" value="">
            </td>
        </tr>
        
        
		<tr>
			<td>
				<?php echo $strPassword ?>
			</td>
			<td>
				<input type="password" name="pass" value="">
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="submit" name="submit" value="<?php echo $strLogin ?>">
			</td>
		</tr>
		</form>	
		</table>
	</td>
</tr>
</table>

<?php
} // end if login

$page->endPage();
?>
