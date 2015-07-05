<?php
/**
 * Created by PhpStorm.
 * User: mem1b
 * Date: 15.06.2015
 * Time: 20:16
 */

require('./lib/common.lib.php');
require('./lib/meeting.lib.php');

if (AA_connectToDB() == FALSE) {        // invalid DB connection
    return;
}


$infos = $_SESSION['meeting_infos'];
$meetingId = $infos['xMeeting'];

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<meta charset=\"UTF-8\">";
echo "</head>";
echo "<body>";

echo "<div id=\"meetingid\">";
echo "<p>Meeting: <b>" . $infos["Name"] . "</b> id(" . $infos["xMeeting"] . ")</p>";
echo "</div>";

echo "<div id=\"anmeldungen\">";
echo "<h1>Statistiken:</h1>";
echo "<table>";
echo "<tr>";
$result = mysql_query("SELECT COUNT(DISTINCT(xAthlet))
FROM anmeldung as an
LEFT JOIN athlet AS at USING(xAthlet)
WHERE an.xMeeting = " . $meetingId);
echo "<td>Total Anmeldungen:</td><td>" . mysql_result($result, 0) . "</td>";
echo "</tr>";

echo "<tr>";
$result = mysql_query("SELECT COUNT(DISTINCT(xAthlet))
FROM resultat as r
LEFT JOIN serienstart AS ss USING(xSerienstart)
LEFT JOIN serie AS s USING(xSerie)
LEFT JOIN runde AS ru USING(xRunde)
LEFT JOIN wettkampf AS w USING(xWettkampf)
LEFT JOIN start AS st ON(ss.xStart = st.xStart)
LEFT JOIN anmeldung AS an USING(xAnmeldung)
LEFT JOIN athlet AS at USING(xAthlet)
WHERE r.Leistung <= 0
 AND w.xMeeting = " . $meetingId);
echo "<td>Davon nicht angetreten:</td><td>" . mysql_result($result, 0) . "</td>";
echo "</tr>";

echo "<tr>";
$result = mysql_query("SELECT COUNT(DISTINCT(xAthlet))
FROM resultat as r
LEFT JOIN serienstart AS ss USING(xSerienstart)
LEFT JOIN serie AS s USING(xSerie)
LEFT JOIN runde AS ru USING(xRunde)
LEFT JOIN wettkampf AS w USING(xWettkampf)
LEFT JOIN start AS st ON(ss.xStart = st.xStart)
LEFT JOIN anmeldung AS an USING(xAnmeldung)
LEFT JOIN athlet AS at USING(xAthlet)
WHERE r.Leistung > 0
 AND w.xMeeting = " . $meetingId);
echo "<td>Davon Angetreten: </td><td>" . mysql_result($result, 0) . "</td>";
echo "</tr>";

echo "</table>";

echo "<br />";

echo "Es kann sein, dass \"Total Anmeldungen\" mehr hat als die Summe von \"angetretenen\" und \"nicht angetretenen\". Weil vielleicht noch vor dem Wettkampf Athleten entfernt wurden.";
echo "</div>";


echo "<h1>Vollständigkeit:</h1>";
echo "<div id=\"differenz\">";
$result_diff = mysql_query("SELECT COUNT(*) - (SELECT COUNT(*)
FROM runde as r
INNER JOIN wettkampf as w USING(xWettkampf)
WHERE w.xMeeting = " . $meetingId . ")
FROM wettkampf
WHERE xMeeting = " . $meetingId . "
");
echo "<p>WTK Definitionen, wie viele Zeiten fehlen noch: " . mysql_result($result_diff, 0) . " (<=0 ist ok)</p>";

echo "</div>";

echo "</body>";
echo "</html>";
?>