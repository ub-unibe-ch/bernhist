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
 

$startjahr = $_SESSION['startjahr'];
$endjahr = $_SESSION['endjahr'];

require_once('./conn.php');

$result = @mysql_query('SELECT START,END,ort1.NAME,OBS_VALUE,thema.STD_TERM_NAME FROM obs_base,ort1,quellenthema,thema WHERE obs_base.src_term_key=quellenthema.src_term_key '.
	"AND quellenthema.std_term_key=thema.std_term_key AND obs_base.STD_SYS_KEY=$ortkey AND thema.std_term_key=$themakey AND START>=$startjahr AND END<=$endjahr ".
	"AND obs_base.STD_SYS_KEY=ort1.key ORDER BY START LIMIT 0,1000");

$example_data = array();
$i = 0;

while($row = mysql_fetch_array($result)) {
	$start = $row['START'];
	$end = $row['END'];
	$ort= $row['NAME'];
	$value = $row['OBS_VALUE'];

	$example_data[$i] = array('',$start,$value);
	$i = $i + 1;

	$maxvalue[] = $start;  //liest alle jahrzahlen in einen array
	//$yscale = array($value);    //liest alle werte in einen array 
	$yscale[] = $value; 
}

$zahl = max($yscale);
$result = $zahl/10;

if ($result <= 3) {
	$result = 1;
} elseif ($result > 3 && $result <= 14) {
	$result = 5;	 
} elseif ($result > 15 && $result <= 24) {
	$result = 10;		 
} elseif ($result > 25 && $result <= 74) {
	$result = 50;	 
} elseif ($result > 75 && $result <= 249) {
	$result = 100;
} elseif ($result > 250 && $result <= 749) {
	$result = 500;
} elseif ($result > 750 && $result <= 2499) {
	$result = 1000;
} elseif ($result > 2500 && $result <= 7499) {
	$result = 5000;
} elseif ($result > 7500 && $result <= 24999) {
	$result = 10000;
} elseif ($result > 25000 && $result <= 74999) {
	$result = 50000;
}elseif ($result > 75000) {
	$result = 100000;
}	 

//min and max values of y 
$miny = min($yscale);
$maxy = max($yscale) + $result;

//first year
$firstx = min($maxvalue);

//Include the code
require_once('phplot.php');

//Define the object
$graph = new PHPlot(600, 400);

$graph->SetDataType('linear-linear');

$graph->SetFileFormat('png'); 
//$graph->SetImageArea(600,400);
$graph->SetPlotAreaWorld($firstx, 0, 2000, $maxy);
$graph->SetTitle(utf8_decode("$ortname ($orttype) \n $themaname ($thematype)"));
$graph->SetTitleFontSize(3);
$graph->SetTickLength(5); 
$graph->SetDrawYGrid(1);

//Set X
$graph->SetXDataLabelAngle(0);
$graph->SetHorizTickIncrement(25);
$graph->SetXGridLabelType('custom');
$graph->SetXLabel('Jahr'); 

if ($zahl < 99000) {   //sonst überschneidet das label mit den jahrzahlen

$graph->SetYLabel(utf8_decode("$themaname ($thematype)")); 
}
//Set Y
$graph->SetVertTickIncrement($result);
$graph->SetYGridLabelType('data');
$graph->SetPrecisionY(0);
$graph->SetPlotType('lines');
$graph->SetBackgroundColor('white');

/*
//Other settings
$graph->SetPlotBgColor(array(222,222,222));
$graph->SetBackgroundColor(white);//(array(200,222,222)); //can use rgb values or "name" values
$graph->SetTextColor("black");
$graph->SetGridColor("black");
$graph->SetLightGridColor(array(175,175,175));
$graph->SetTickColor("black");
$graph->SetTitleColor(array(0,0,0)); // Can be array or name
$graph->SetImageBorderType("black");
*/

//Set some data
$graph->SetDataValues($example_data);

//Draw it
$graph->DrawGraph();
/*
unset($ortvar);
unset($themavar);
unset($startjahr);
unset($endjahr);
session_destroy();*/

?>