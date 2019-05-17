<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<title>BERNHIST - Historisch-Statistische Datenbank des Kantons Bern</title>
		<link href="/content/css/global.css" rel="stylesheet" type="text/css" />
	</head>
	<body style="margin:10em 0 0 15px;">
		<div id="header">
			<img src="/content/images/titelbar.gif" />
		</div>
<?php

$themavar = $_GET['themavar'];
$ortvar = $_GET['ortvar'];
$themaarray = explode('|', $themavar);
$themakey = $themaarray[0];
$themaname = $themaarray[1];
$thematype = $themaarray[2];
// print "<p>Debug: themakey = ".$themakey.", themaname = ".$themaname.", thematype = ".$thematype."<br />";

$ortarray = explode('|', $ortvar);
$ortkey = $ortarray[0];
$ortname = $ortarray[1];
$orttype = $ortarray[2];
// print "Debug: ortkey = ".$ortkey.", ortname = ".$ortname.", orttype = ".$orttype."<br />"; -->

require_once('./conn.php');

$selectstring = 'SELECT distinct START,END,ort1.NAME,OBS_VALUE,thema.STD_TERM_NAME FROM obs_base,ort1,quellenthema,thema WHERE obs_base.src_term_key=quellenthema.src_term_key '.
	"AND quellenthema.std_term_key=thema.std_term_key AND obs_base.STD_SYS_KEY=$ortkey AND thema.std_term_key=$themakey AND obs_base.STD_SYS_KEY=ort1.key ORDER BY START LIMIT 0,1000";
// print "Debug: selectstring = ".$selectstring;
$result = mysql_query($selectstring);

if (!$result) {
	printf ('<br />[error %d : %s]\n', mysql_errno(), mysql_error());
}

while ($row = mysql_fetch_array($result)) {
	$start = $row['START'];
	$end = $row['END'];
	$ort= $row['NAME'];
	$thema = $row['STD_TERM_NAME'];

	$allort[] = $ort;
	// print "Debug: ort = ".$ort."<br />";
	$allthema[] = $thema;
	// print "Debug: thema = ".$thema."<br />";
	$allstart[] = $start;
	// print "Debug: start = ".$start."<br />";
	$allend[]= $end;
	// print "Debug: end = ".$end."<br />";
}

$count = mysql_num_rows($result);

if (!isset($allort, $allthema)) {
	echo "<p class=\"notice\">Es sind keine Daten von $ortname <em>($orttype)</em> zum Thema $themaname vorhanden!</p>";
} else {
	rsort($allend);     //sortiert den Array mit den Endjahren in umgekehrter Reihenfolge
	session_start();
	$_SESSION['ortvar'] = $ortvar;
	$_SESSION['themavar'] = $themavar;

?>
		<form action="result.php" target="_blank" method = "POST">
			<table width="700" border="0" style="text-align:left">
				<tr>
					<th>Folgender Ort ist ausgewählt:</th>
					<td><?php echo "<strong>$ortname</strong> <em>($orttype)</em>";?></td>
				</tr>
				<tr>
					<th>Folgendes Thema ist ausgewählt:</th>
					<td><?php echo "<strong>$themaname</strong> <em>($thematype)</em>";?></td>
				</tr>
				<tr>
					<th>Verfügbar sind Daten aus den Jahren von:</th>
					<td>
						<select name="startjahr">
<?php
	foreach ($allstart as $startID) {
		echo ("\t\t\t\t\t\t\t<option value=\"$startID\">$startID</option>\n");
	}

	echo "\t\t\t\t\t\t</select>\n\t\t\t\t\t\tbis\n\t\t\t\t\t\t<select name=\"endjahr\">\n";

	foreach ($allend as $endID) {
		echo ("\t\t\t\t\t\t\t<option value=\"$endID\">$endID</option>\n");
	}
?>
						</select>
					</td>
				</tr>
				<tr>
					<th>Anzahl gefundener Datensätze:</th>
					<th><?php echo $count; ?></th>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><br /><br /><input type="submit" class="button" name="submit" value="Abfrage starten"></td>
				</tr>
			</table>
		</form>
<?php 

}

?>
		<table width="700px" border="0">
			<tr>
				<td>
					<img src="/content/images/player_rew.png" width="16" height="16" />
					<a href="start.php">zur Ortsauswahl</a>
				</td>
				<td>
					<img src="/content/images/1leftarrow.png" width="16" height="16" />
					<a href="thementree.php?ortvar=<?php echo $ortvar; ?>">zur Themenauswahl</a>
				</td>
<?php

if (isset($allort, $allthema)) {
	$ortvar = urlencode($ortvar);
	$themavar = urlencode($themavar);

?>
				<td>
					<img src="/content/images/redo.png" width="16" height="16" />
					<a href="abfrage.php?ortvar=<?php echo "$ortvar&amp;themavar=$themavar"; ?>">Werte zurücksetzen</a>
				</td>
<?php

}

?>
			</tr>
		</table>
		<hr />
		<a href="/d/index.html"><img src="/content/images/home.png" /> zur Startseite</a>
	</body>
</html>