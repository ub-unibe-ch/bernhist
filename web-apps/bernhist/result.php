<?php

session_start();

$themavar = $_SESSION['themavar'];
$themaarray = explode('|', $themavar);
$themakey = $themaarray[0];
$themaname = $themaarray[1];
$thematype = $themaarray[2];

$ortvar = $_SESSION['ortvar'];
$ortarray = explode('|', $ortvar);
$ortkey = $ortarray[0];
$ortname = $ortarray[1];
$orttype = $ortarray[2];

$startjahr = $_POST['startjahr'];
$endjahr = $_POST['endjahr'];

$_SESSION['startjahr'] = $startjahr;
$_SESSION['endjahr'] = $endjahr;

?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<title><?php echo "$ortname $themaname $startjahr bis $endjahr"; ?></title>
		<link href="/content/css/global.css" rel="stylesheet" type="text/css" />
		<style type="text/css">
			th {text-align:left;width:80px;}
		</style>
		<script language="JavaScript" type="text/JavaScript">
			function Drucken () {
				if (window.print) {
					self.print();
				} else {
					alert("Leider unterstützt Ihr Browser diese Methode nicht! Drücken Sie Strg+P oder wählen Sie via Menu die Funktion Drucken aus.");
				}
			}

			function fenster () {
				var win;
				win=window.open ("grafik.php", "bernhistgrafik","width=600, height=400, resizable=no, dependent=yes, scrollbars=no");
			}
		</script>
	</head>
	<body style="margin:10em 0 0 15px;">
		<div id="header">
			<img src="/content/images/titelbar.gif" />
		</div>
<?php

if ($startjahr > $endjahr) {

?>
		<p class="notice">Das Startjahr muss kleiner als das Endjahr sein!</p>
		<a href= "javascript:window.close()">Fenster schliessen.</a>
	</body>
</html>


<?php

	exit();
}

$ortvar = urlencode($ortvar);
$themavar = urlencode($themavar);

?>
		<table border="0" width="700px">
			<tr>
				<td>
					<img src="/content/images/xls.gif" />
					<a href="excel.php?ortvar=<?php echo "$ortvar&amp;themavar=$themavar&amp;startjahr=$startjahr&amp;endjahr=$endjahr"; ?>">Excel export</a>	
				</td>
				<td>
					<img src="/content/images/pdf.png" />
					<a href="pdf.php?ortvar=<?php echo "$ortvar&amp;themavar=$themavar&amp;startjahr=$startjahr&amp;endjahr=$endjahr"; ?>" target="_blank">PDF export</a>
				</td>
				<td>
					<img src="/content/images/spreadsheet.gif" />
					<a href="javascript:fenster()">Als Grafik anzeigen</a>
				</td>
				<td>
					<img src="/content/images/printer1.gif" />
					<a href="javascript:onclick=Drucken()">Seite drucken</a>
				</td>
				<td>
					<img src="/content/images/fileclose.png" />
					<a href="javascript:window.close()">Fenster schliessen</a>
				</td>
			</tr>
		</table>
<?php

echo "<p><strong>$ortname</strong> <em> ($orttype) </em><strong> - $themaname</strong> <em>($thematype)</em></p>";
require_once('./conn.php');

$result = @mysql_query('SELECT START,END,ort1.NAME,OBS_VALUE,thema.STD_TERM_NAME FROM obs_base,ort1,quellenthema,thema WHERE obs_base.src_term_key=quellenthema.src_term_key '.
	"AND quellenthema.std_term_key=thema.std_term_key AND obs_base.STD_SYS_KEY=$ortkey AND thema.std_term_key=$themakey AND START>=$startjahr ".
	"AND END<=$endjahr AND obs_base.STD_SYS_KEY=ort1.key ORDER BY START LIMIT 0,1000;");
?>
		<table>
			<tr>
				<th>Startjahr</th>
				<th>Endjahr</th>
				<th width="300px" style="width:300px;min-width:300px;">Ort (Typ)</th>
				<td align="right"><b>Wert</b><em> (<?php echo $thematype; ?>)</em></td>
			</tr>
			<tr>
				<td colspan="4" style="line-height:1px;border-top:1px solid black;padding:0;">&nbsp;</td>
			</tr>
<?php

while ($row = mysql_fetch_array($result)) {
	$start = $row['START'];
	$end = $row['END'];
	$ort = $row['NAME'];
	$value = $row['OBS_VALUE'];

	echo "<tr><td>$start</td><td>$end</td><td>$ort ($orttype)</td><td align=\"right\">$value</td></tr>";
}

?>
		</table>
		<p>Quelle: BERNHIST</p>
	</body>
</html>