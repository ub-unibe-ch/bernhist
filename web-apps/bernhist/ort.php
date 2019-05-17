<?php

$ortkey = $_REQUEST['ortkey'];
$array = explode('|', $ortkey);
$ortvar = $array[0];
$name = $array[1];
$type = $array[2];

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
		<b>Folgender Ort ist ausgewählt:</b> <?php echo "<strong>$name</strong> <em>($type)</em>";?>
		<table style="padding:5em 0;">
			<tr>
				<td><img src="/content/images/1leftarrow.png" width="16" height="16" /></td>
				<td width="150px"><a href="javascript:history.back()">zurück</a></td>
				<td><img src="/content/images/1rightarrow.gif" width="16" height="16" /></td>
				<td><a href="thementree.php?ortvar=<?php echo urlencode($ortkey);?>">weiter zur Themenauswahl</a></td>
			</tr>
		</table>
		<hr />
		<a href="/d/index.html"><img src="/content/images/home.png" /> zur Startseite</a>
	</body>
</html>