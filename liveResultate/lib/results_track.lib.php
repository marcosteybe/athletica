<?php

/**********
 *
 *	track results
 *	
 */
 
 

if (!defined('AA_RESULTS_TRACK_LIB_INCLUDED'))
{
	define('AA_RESULTS_TRACK_LIB_INCLUDED', 1);

function AA_results_Track($round, $layout, $content_navi, $cat, $disc, $rtyp, $event){   
 
require('./config.inc.php');
require('./config.inc.end.php');   

require('./lib/common.lib.php');
require('./lib/heats.lib.php');  
require('./lib/utils.lib.php');
require_once('./lib/timing.lib.php');
                                     
$p = "./tmp";
$fp = @fopen($p."/live".$round.".html",'w');
if(!$fp){
    AA_printErrorMsg($GLOBALS['strErrFileOpenFailed']);  
    return;
}   

$relay = AA_checkRelay($event);	// check, if this is a relay event

$svm = AA_checkSVM(0, $round); // decide whether to show club or team name  

$teamsm = AA_checkTeamSM($event);  
 
global $content;          

$mergedMain=AA_checkMainRound($round);  
if ($mergedMain > 0) {
    $sqlRounds = AA_getMergedRounds($round);
    $sqlRounds = " IN " . $sqlRounds; 
    if ($mergedMain == 1) {           
        $round = AA_getMainRound($round); 
    }   
}
else {
      $sqlRounds = " = " . $round;
}
	
// get url
   $url = '';
   $result = mysql_query("
            SELECT
                url
            FROM
                athletica_liveResultate.config");
        if(mysql_errno() > 0) {
            AA_printErrorMsg(mysql_errno() . ": " . mysql_error());
            
        }
        else {
            $row = mysql_fetch_row($result);            
            $url = $row[0]; 
        } 
        
// read round data
if($round > 0)
{    
     $content = $cfgHtmlStart1;      
     if  (empty($GLOBALS['cfgDir']) ){
        $content .= "<meta http-equiv='refresh' content='" . $GLOBALS['cfgMonitorReload'] . ";  url=http://" . $url ."/live".$round .".html'>"; 
     }
     else {
          $content .= "<meta http-equiv='refresh' content='" . $GLOBALS['cfgMonitorReload'] . ";  url=http://" . $url . "/" . $GLOBALS['cfgDir'] ."/live".$round .".html'>";      
     }  
     
     $content .= $cfgHtmlStart2;   
     $content .= $cfgHtmlStart3;     
     $content .= $content_navi; 
     $content .= "</div ><div id='content_pc'><div id='content_pda'>";     
     
     $status = '';
     // check if round is final
     $sql_r="SELECT 
                    rt.Typ,
                    r.Status
                FROM
                    athletica.runde as r
                    LEFT JOIN athletica.rundentyp_" . $_COOKIE['language'] . " as rt USING (xRundentyp)
                WHERE
                    r.xRunde=" .$round;
       $res_r = mysql_query($sql_r);
       
       if(mysql_errno() > 0) {        // DB error
            AA_printErrorMsg(mysql_errno() . ": " . mysql_error());
        }
        
       $order="ASC";   
       if (mysql_num_rows($res_r) == 1) {
            $row_r=mysql_fetch_row($res_r);  
            if ($row_r[0]=='F'){
                $order="DESC";  
            }
            $status =  $row_r[1];
        }            
        
		// display all athletes
		if($relay == FALSE) {		// single event
            if ($status == $cfgRoundStatus['open'] || $status == $cfgRoundStatus['enrolement_done'] || $status == $cfgRoundStatus['enrolement_pending'] ) {
                
               $content .= "<h1>$strTNlist " . $_COOKIE['meeting'] ."</h1>";              // title   
                
                if ($teamsm){
                   $query = "SELECT 
                                    r.Bahnen
                                    , rt.Name
                                    , rt.Typ  
                                    , a.Startnummer
                                    , at.Name
                                    , at.Vorname
                                    , at.Jahrgang  
                                    , t.Name
                                    , at.Land     
                                    , at.xAthlet
                                    , r.status                  
                             FROM 
                                    athletica.runde AS r                                    
                                    LEFT JOIN athletica.start AS st ON (st.xWettkampf = r.xWettkampf)
                                    LEFT JOIN athletica.anmeldung AS a ON (a.xAnmeldung = st.xAnmeldung)
                                    LEFT JOIN athletica.athlet AS at ON (at.xAthlet = a.xAthlet)
                                    LEFT JOIN athletica.verein AS v ON (v.xVerein = at.xVerein)
                                    INNER JOIN athletica.teamsmathlet AS tat ON(a.xAnmeldung = tat.xAnmeldung)    
                                    LEFT JOIN athletica.teamsm as t ON (tat.xTeamsm = t.xTeamsm)                      
                                    LEFT JOIN athletica.rundentyp_" . $_COOKIE['language'] . " AS rt ON rt.xRundentyp = r.xRundentyp    
                             WHERE
                                    r.xRunde  " . $sqlRounds ."   
                             ORDER BY at.Name, at.Vorname";      
                }
                else {
                     $query = "SELECT 
                                    r.Bahnen
                                    , rt.Name
                                    , rt.Typ  
                                    , a.Startnummer
                                    , at.Name
                                    , at.Vorname
                                    , at.Jahrgang  
                                    , if('".$svm."', t.Name, IF(a.Vereinsinfo = '', v.Name, a.Vereinsinfo))  
                                    , at.Land     
                                    , at.xAthlet
                                    , r.status                  
                             FROM 
                                    athletica.runde AS r                                    
                                    LEFT JOIN athletica.start AS st ON (st.xWettkampf = r.xWettkampf)
                                    LEFT JOIN athletica.anmeldung AS a ON (a.xAnmeldung = st.xAnmeldung)
                                    LEFT JOIN athletica.athlet AS at ON (at.xAthlet = a.xAthlet)
                                    LEFT JOIN athletica.verein AS v ON (v.xVerein = at.xVerein)
                                    LEFT JOIN athletica.team AS t ON(a.xTeam = t.xTeam)
                                    LEFT JOIN athletica.rundentyp_" . $_COOKIE['language'] . " AS rt ON rt.xRundentyp = r.xRundentyp    
                             WHERE
                                    r.xRunde  " . $sqlRounds ."   
                             ORDER BY at.Name, at.Vorname";      
                }        
              
            }
            else {
                $content .= "<h1>$strStartlist " . $_COOKIE['meeting'] ."</h1>";              // title  
                  
                  if ($teamsm){
                        $query = "SELECT 
                                    r.Bahnen
                                    , rt.Name
                                    , rt.Typ
                                    , s.xSerie
                                    , s.Bezeichnung
                                    , s.Wind
                                    , s.Film
                                    , an.Bezeichnung
                                    , ss.xSerienstart
                                    , ss.Position
                                    , ss.Rang
                                    , ss.Qualifikation
                                    , a.Startnummer
                                    , at.Name
                                    , at.Vorname
                                    , at.Jahrgang  
                                    , t.Name
                                    , LPAD(s.Bezeichnung,5,'0') as heatid
                                    , s.Handgestoppt
                                    , at.Land   
                                    , ss.Bemerkung  
                                    , at.xAthlet
                                    , r.status                   
                             FROM 
                                    athletica.runde AS r
                                    LEFT JOIN athletica.serie AS s ON (s.xRunde = r.xRunde)
                                    LEFT JOIN athletica.serienstart AS ss ON (ss.xSerie = s.xSerie)
                                    LEFT JOIN athletica.start AS st ON (st.xStart = ss.xStart)
                                    LEFT JOIN athletica.anmeldung AS a ON (a.xAnmeldung = st.xAnmeldung)
                                    LEFT JOIN athletica.athlet AS at ON (at.xAthlet = a.xAthlet)
                                    LEFT JOIN athletica.verein AS v ON (v.xVerein = at.xVerein)
                                    INNER JOIN athletica.teamsmathlet AS tat ON(a.xAnmeldung = tat.xAnmeldung)    
                                    LEFT JOIN athletica.teamsm as t ON (tat.xTeamsm = t.xTeamsm)                      
                                    LEFT JOIN athletica.rundentyp_" . $_COOKIE['language'] . " AS rt ON rt.xRundentyp = r.xRundentyp
                                    LEFT JOIN athletica.anlage AS an ON an.xAnlage = s.xAnlage
                             WHERE
                                    r.xRunde  " . $sqlRounds ."   
                             ORDER BY heatid ".$order .", ss.Position";      
                             
                              echo "<br>query=$query";
                  }
                  else {
                      
                 
                $query = "SELECT 
                                    r.Bahnen
                                    , rt.Name
                                    , rt.Typ
                                    , s.xSerie
                                    , s.Bezeichnung
                                    , s.Wind
                                    , s.Film
                                    , an.Bezeichnung
                                    , ss.xSerienstart
                                    , ss.Position
                                    , ss.Rang
                                    , ss.Qualifikation
                                    , a.Startnummer
                                    , at.Name
                                    , at.Vorname
                                    , at.Jahrgang  
                                    , if('".$svm."', t.Name, IF(a.Vereinsinfo = '', v.Name, a.Vereinsinfo))  
                                    , LPAD(s.Bezeichnung,5,'0') as heatid
                                    , s.Handgestoppt
                                    , at.Land   
                                    , ss.Bemerkung  
                                    , at.xAthlet
                                    , r.status                   
                             FROM 
                                    athletica.runde AS r
                                    LEFT JOIN athletica.serie AS s ON (s.xRunde = r.xRunde)
                                    LEFT JOIN athletica.serienstart AS ss ON (ss.xSerie = s.xSerie)
                                    LEFT JOIN athletica.start AS st ON (st.xStart = ss.xStart)
                                    LEFT JOIN athletica.anmeldung AS a ON (a.xAnmeldung = st.xAnmeldung)
                                    LEFT JOIN athletica.athlet AS at ON (at.xAthlet = a.xAthlet)
                                    LEFT JOIN athletica.verein AS v ON (v.xVerein = at.xVerein)
                                    INNER JOIN athletica.team AS t ON(a.xTeam = t.xTeam)
                                    LEFT JOIN athletica.rundentyp_" . $_COOKIE['language'] . " AS rt ON rt.xRundentyp = r.xRundentyp
                                    LEFT JOIN athletica.anlage AS an ON an.xAnlage = s.xAnlage
                             WHERE
                                    r.xRunde  " . $sqlRounds ."   
                             ORDER BY heatid ".$order .", ss.Position";      
                             
                            
                 }
            }
		}
		else {								// relay event
              if ($status == $cfgRoundStatus['open'] || $status == $cfgRoundStatus['enrolement_done'] || $status == $cfgRoundStatus['enrolement_pending'] ) { 
                  
                    $content .= "<h1>$strTNlist " . $_COOKIE['meeting'] ."</h1>";              // title   
                    
                    $query= "SELECT 
                                 sf.Startnummer    
                                , rt.Name
                                , rt.Typ    
                                , sf.Name
                                , if('".$svm."', t.Name, v.Name)
                                , r.xRunde  
                         FROM 
                                athletica.runde AS r   
                                LEFT JOIN athletica.start AS st ON (st.xWettkampf = r.xWettkampf)
                                INNER JOIN athletica.staffel AS sf ON (sf.xStaffel = st.xStaffel)
                                LEFT JOIN athletica.verein AS v ON (v.xVerein = sf.xVerein)                    
                                LEFT JOIN athletica.team AS t ON(sf.xTeam = t.xTeam)
                                LEFT JOIN athletica.rundentyp_" . $_COOKIE['language'] . " AS rt ON rt.xRundentyp = r.xRundentyp
                               
                         WHERE 
                                r.xRunde " . $sqlRounds ."                          
                        ORDER BY sf.Name";               
              }
              else {    
                 $content .= "<h1>$strStartlist " . $_COOKIE['meeting'] ."</h1>";              // title   
                 
			    $query= "SELECT 
                                r.Bahnen
                                , rt.Name
                                , rt.Typ
                                , s.xSerie
                                , s.Bezeichnung
                                , s.Wind
                                , s.Film
                                , an.Bezeichnung
                                , ss.xSerienstart
                                , ss.Position
                                , ss.Rang
                                , ss.Qualifikation
                                , sf.Name
                                , if('".$svm."', t.Name, v.Name)  
                                , LPAD(s.Bezeichnung,5,'0') as heatid
                                , s.Handgestoppt
                                , ss.Bemerkung   
                         FROM 
                                athletica.runde AS r
                                LEFT JOIN athletica.serie AS s ON (s.xRunde = r.xRunde)
                                LEFT JOIN athletica.serienstart AS ss ON (ss.xSerie = s.xSerie)
                                LEFT JOIN athletica.start AS st ON (st.xStart = ss.xStart)
                                LEFT JOIN athletica.staffel AS sf ON (sf.xStaffel = st.xStaffel)
                                LEFT JOIN athletica.verein AS v ON (v.xVerein = sf.xVerein)                    
                                INNER JOIN athletica.team AS t ON(sf.xTeam = t.xTeam)
                                LEFT JOIN athletica.rundentyp_" . $_COOKIE['language'] . " AS rt ON rt.xRundentyp = r.xRundentyp
                                LEFT JOIN athletica.anlage AS an ON an.xAnlage = s.xAnlage
                         WHERE 
                                r.xRunde  " . $sqlRounds ."                          
                        ORDER BY heatid ".$order .", ss.Position";   
             }
		}  
		$result = mysql_query($query);
       
		if(mysql_errno() > 0) {		// DB error             
			AA_printErrorMsg(mysql_errno() . ": " . mysql_error());
		}
		else {
			 
			// initialize variables
			$h = 0;		// heat counter
			$p = 0;		// position counter (to evaluate empty heats
			$i = 0;		// input counter (an individual id is assigned to each
							// input field, focus is then moved to the next input
							// field by calling $i+1)
			$rowclass = 'odd';
			$tracks = 0;    
            
            if (!empty($rtyp)){
                $content .= "<h2>$cat $disc, $rtyp</h2>";    
            } 
            else {
                 $content .= "<h2>$cat $disc</h2>";    
            }  
            $content .= "<table class='dialog'>";   
            
            if ($status == $cfgRoundStatus['open'] || $status == $cfgRoundStatus['enrolement_done'] || $status == $cfgRoundStatus['enrolement_pending'] )  {
                    $content .="</table><table class='dialog'>";
                    $content .="<tr>";           

                   

                    $content .="<tr>";   
                    if($relay == FALSE) {    // athlete display

                        $content .="<tr>";   
                        $content .= "<th class='dialog'>". $strStartnumber ."</th>";
                       $content .= "<th class='dialog' >". $strAthlete ."</th>";   
                       $content .= "<th class='dialog_pc'>". $strYearShort ."</th>";   
                       $content .= "<th class='dialog_pc'>". $strCountry ."</th>";   
                       $content .= "<th class='dialog_pc'>";
                       if($svm){ 
                            $content .= $strTeam; 
                       }else{ 
                            $content .= $strClub;
                       } 
                                
                                
                                
                        }  
                        else {
                             $content .="<tr>";   
                            $content .= "<th class='dialog'>". $strStartnumber."</th>";         
                            $content .= "<th class='dialog'>". $strRelay ."</th>";
                            $content .= "<th class='dialog_pc'>";
                            if($svm){ 
                                $content .= $strTeam; 
                            }else{ 
                                $content .= $strClub;
                            } 
                            $content .= "</th>";  
                        }
                        
                         $content .="<tr>";    
                    
                
                    while($row = mysql_fetch_row($result))
                    { $i++;        // next element        
                     if($i % 2 == 0) {        // even row numer
                        $rowclass='even';
                    }
                    else {                            // odd row number
                        $rowclass='odd';
                    }    
                    if($relay == FALSE) {
   
                            $content .= "<tr class='" . $rowclass ."'>";
                            
                            $content .= "<td class='forms_right'>". $row[3]."</td>";             /* start nbr */
                            $content .= "<td>" . $row[4]. " " . $row[5]."</td>";                  /* name */
                            $content .= "<td class='forms_ctr_pc'>" . AA_formatYearOfBirth($row[6])."</td>";
                            $content .= "<td class='forms_pc'>";
                            if ($row[8]!='' && $row[8]!='-') {
                                $content .= $row[8];
                            } else {
                                $content .= " "; 
                            }
                            $content .= "</td>";
                            $content .= "<td class='forms_pc' nowrap>" . $row[7]."</td>";                                           /* club */    
                    }
                    else {    // relay

                        $content .= "<tr class='". $rowclass ."'>";
                        $content .= "<td class='forms_right'>" . $row[0] ."</td>";                /* position */
                        $content .= "<td>" . $row[3] ."</td>";                                    /* relay name */ 
                        $content .= "<td class='forms_pc' >" . $row[4] ."</td>";                  /* club */       
                    }     
                        
                     
                    }
            }
            else {
            
           
			while($row = mysql_fetch_row($result))
			{   
                
                
                
               
				$p++;			// increment position counter
/*
 *  Heat headerline
 */
				if($h != $row[3])		// new heat
				{
					$tracks = $row[0];	// keep nbr of planned tracks

					// fill previous heat with empty tracks
					if($p > 1) {
						printEmptyTracks($p, $tracks, 5+$c);
					}
	
					$h = $row[3];				// keep heat ID
					$p = 1;						// start with track one

					if(is_null($row[1])) {		// only one round
						$title = "$strFinalround";
					}
					else {		// more than one round
						$title = "$row[1]";
					}

					// increment colspan to include ranking and qualification
					$c = 0;
					if($status == $cfgRoundStatus['results_done']) {
						$c++;
						if($nextRound > 0) {
							$c++;
						}
					}  
                    $content .="</table><table class='dialog'>";
	                $content .="<tr>";
	                $content .= "<th class='dialog' >" .$title ." " .$row[4] ."</th>"; 
                    if ($relay == FALSE) {
                        $content .= "<th class='dialog' colspan='5'>" .$strFilm ." " . $row[6] ."</th>";   
                    } 
                    else {
                        $content .= "<th class='dialog' colspan='3'>" .$strFilm ." " . $row[6] ."</th>";   
                    }     

	                $i++;		// next element        

	                $content .="<tr>";   

/*
 *  Column header
 */
					if($relay == FALSE) {	// athlete display

	                    $content .="<tr>";   
                        if ($row[22] == $cfgRoundStatus['open'] || $row[22] == $cfgRoundStatus['enrolement_done'] || $row[22] == $cfgRoundStatus['enrolement_pending'] ){     
                                 $content .= "<th class='dialog' >". $strAthlete ."</th>";   
                                $content .= "<th class='dialog_pc'>". $strYearShort ."</th>";   
                                $content .= "<th class='dialog_pc'>". $strCountry ."</th>";   
                                $content .= "<th class='dialog_pc'>";
                                if($svm){ 
                                    $content .= $strTeam; 
                                }else{ 
                                    $content .= $strClub;
                                } 
                        }
                        else {  
		                        $content .= "<th class='dialog'>". $strPositionShort ."</th>";   
                                $content .= "<th class='dialog'>". $strStartnumber ."</th>";     
		                        $content .= "<th class='dialog' >". $strAthlete ."</th>";   
		                        $content .= "<th class='dialog_pc'>". $strYearShort ."</th>";   
		                        $content .= "<th class='dialog_pc'>". $strCountry ."</th>";   
		                        $content .= "<th class='dialog_pc'>";
                                if($svm){ 
                                    $content .= $strTeam; 
                                }else{ 
                                    $content .= $strClub;
                                } 
                         }  
                        $content .=  "</th>";      
					}
					else {		// relay display

	                    $content .="<tr>";   
		                $content .= "<th class='dialog'>". $strPositionShort."</th>";         
		                $content .= "<th class='dialog'>". $strRelay ."</th>";
		                $content .= "<th class='dialog_pc'>";
                        if($svm){ 
                            $content .= $strTeam; 
                        }else{ 
                            $content .= $strClub;
                        } 
                        $content .= "</th>";  
					}  

	                $content .="</tr>";   

				}		// ET new heat

/*
 * Empty tracks
 */
				if(($layout == $cfgDisciplineType[$strDiscTypeTrack])
					|| ($layout == $cfgDisciplineType[$strDiscTypeTrackNoWind])
					|| ($layout == $cfgDisciplineType[$strDiscTypeRelay]))
				{
					// current track and athlete's position not identical
					if($p < $row[9]) {
						$p = printEmptyTracks($p, ($row[9]-1), 5+$c);
					}
				}	// ET empty tracks

/*
 * Athlete data lines
 */
				$p = $row[9];			// keep position
				if($p % 2 == 0) {		// even row numer
					$rowclass='even';
				}
				else {							// odd row number
					$rowclass='odd';
				}	

				if($relay == FALSE) {
   
	                $content .= "<tr class='" . $rowclass ."'>";
		            $content .= "<td class='forms_right'>" . $row[9] ."</td>";                          /* position */
		            $content .= "<td class='forms_right'>". $row[12] ."</td>";             /* start nbr */
		            $content .= "<td>" . $row[13] . " " . $row[14] ."</td>";                  /* name */
		            $content .= "<td class='forms_ctr_pc'>" . AA_formatYearOfBirth($row[15]) ."</td>";
		            $content .= "<td class='forms_pc'>";
                    if ($row[19]!='' && $row[19]!='-') {
                        $content .= $row[19];
                    } else {
                        $content .= " "; 
                    }
                    $content .= "</td>";
		            $content .= "<td class='forms_pc' nowrap>" . $row[16] ."</td>";                                           /* club */    
				}
				else {	// relay

	                $content .= "<tr class='". $rowclass ."'>";
		            $content .= "<td class='forms_right'>" . $row[9] ."</td>";                /* position */
		            $content .= "<td>" . $row[12] ."</td>";                                    /* relay name */ 
		            $content .= "<td class='forms_pc'>" . $row[13] ."</td>";                                  /* club */       
				}  
               
			}

			// Fill last heat with empty tracks for disciplines run in
			// individual tracks
			if(($layout == $cfgDisciplineType[$strDiscTypeTrack])
				|| ($layout == $cfgDisciplineType[$strDiscTypeTrackNoWind])
				|| ($layout == $cfgDisciplineType[$strDiscTypeRelay]))
			{
				if($p > 0) {	// heats set up
					$p++;
					printEmptyTracks($p, $tracks, 5+$c);
				}
			}	// ET track disciplines
            
            }  // end while status not open

			mysql_free_result($result);
            
           // $list->endPage();    // end HTML page for printing
           $content .= "</table>"; 
           $content .= $cfgHtmlFooter;  
           $content .= "</div ></div>";   
           $content .= $cfgHtmlEnd;  

           if (!fwrite($fp, $content)) {
                AA_printErrorMsg($GLOBALS['strErrFileWriteFailed']);    
                return;
           }  

           fclose($fp);
            
            
		}		// ET DB error
	
}		// ET round selected

         AA_UpdateStatusChanged($round);
?>
</body>
</html>
<?php   

}   


/**
 * print empty tracks
 * ------------------
 * arg 1 (int): heat position
 * arg 2 (int): up to this position
 * arg 3 (int): column span
 *
 * returns next position
 */
function printEmptyTracks($position, $last, $span)
{
	require('./lib/common.lib.php');
	include('./config.inc.php');
    
    global $content;

	while($position <= $last)
	{
		// switch row class again
		if($position % 2 == 0) {			// even row numer
			$rowclass='even';
		}
		else {						// odd row number
			$rowclass='odd';
		}	

	    $content .= "<tr class='" . $rowclass ."'>";
		$content .= "<td class='forms_right'>". $position ."</td>";
		$content .= "<td colspan='" . $span ."'>" . $strEmpty ."</td>";
	    $content .= "</tr>";

		$position++;
	}

	return $position;
}


}	// AA_RESULTS_TRACK_LIB_INCLUDED
?>
