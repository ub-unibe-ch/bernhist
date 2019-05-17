<?php

$themavar = $_REQUEST['themavar'];
$ortvar = $_REQUEST['ortvar'];
 
$themaarray = explode('|', $themavar);
$themakey = $themaarray[0];
$themaname = $themaarray[1];
$thematype = $themaarray[2];

$ortarray = explode('|', $ortvar);
$ortkey = $ortarray[0];
$ortname = $ortarray[1];
$orttype = $ortarray[2];

?>
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
		<table width="700" border="0" style="text-align:left">
			<tr>
				<th width="350px">Folgender Ort ist ausgewählt:</th>
				<td><?php echo "<strong>$ortname</strong> <em>($orttype)</em>";?></td>
			</tr>
			<tr>
				<th>Folgendes Thema ist ausgewählt:</th>
				<td><?php echo "<strong>$themaname</strong> <em>($thematype)</em>";?></td>
			</tr>
		</table>
		<table style="padding:3em 0;">
			<tr>
				<td><img src="/content/images/1leftarrow.png" width="16" height="16" /></td>
				<td width="150px"><a href="javascript:history.back()">zurück</a></td>
				<td><img src="/content/images/1rightarrow.gif" width="16" height="16" /></td>
				<td><a href="abfrage.php?themavar=<?php echo urlencode($themavar);?>&amp;ortvar=<?php echo urlencode($ortvar);?>">weiter zur Zeitauswahl</a></td>
			</tr>
		</table>
		<hr />
		<a href="/d/index.html"><img src="/content/images/home.png" /> zur Startseite</a>
	</body>
</html>