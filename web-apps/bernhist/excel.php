<?php
/********************************************
PHP - Excel Extraction Tutorial Code
Page: excel.php
Developer: Jeffrey M. Johns
Support: binary.star@verizon.net
Created: 10/01/2003
Modified: N/A
*********************************************
Notes/Comments: This code is a very basic/replica of
the code that is in the tutorial. To make it work you must
define your connection variables below. Make sure you
replace the following all CAPS text with your proper values.
*********************************************
YOUR DATABASE HOST = (ex. localhost)
USERNAME = username used to connect to host
PASSWORD = password used to connect to host
DB_NAME = your database name
TABLE_NAME = table in the database used for extraction
*********************************************
This code will extract the data from your table and format
it for an excel spreadsheet download. It is very quick,
simple, and to the point. If you only want to extract 
certain fields and not the whole table, simply replace
the * in the $select variable with the fields you want
to extract.
*********************************************
Disclaimer: Upon using this code, it is your responsibilty
and I, Jeffrey M. Johns, can not be held accountable for
any misuse or anything that may go wrong.
*********************************************
Other: Support will not be provided if the code is
enhanced or changed. I do not have the time for 
figuring out your changes and modifications. I will only
offer simple support for the code listed below.
/********************************************/
$themavar = $_REQUEST['themavar'];
$themaarray = explode('|', $themavar);
$themakey = $themaarray[0];
$themaname = $themaarray[1];
$thematype = $themaarray[2];

$ortvar = $_REQUEST['ortvar'];
$ortarray = explode('|', $ortvar);
$ortkey = $ortarray[0];
$ortname = $ortarray[1];
$orttype = $ortarray[2];

$startjahr = $_REQUEST['startjahr'];
$endjahr = $_REQUEST['endjahr'];

require_once('./conn.php');
//define(db_host, "localhost");
//define(db_user, "semeyer");
//define(db_pass, "sun%ray");
//define(db_link, mysql_connect(db_host,db_user,db_pass));
$db_link = $dbcnx;
define('db_name', 'bernhist');
/********************************************
Write the query, call it, and find the number of fields
/********************************************/
$select = 'SELECT START,END,ort1.NAME,OBS_VALUE,thema.STD_TERM_NAME FROM obs_base,ort1,quellenthema,thema WHERE obs_base.src_term_key=quellenthema.src_term_key '.
	"AND quellenthema.std_term_key=thema.std_term_key AND obs_base.STD_SYS_KEY=$ortkey AND thema.std_term_key=$themakey AND START>=$startjahr AND END<=$endjahr ".
	"AND obs_base.STD_SYS_KEY=ort1.key ORDER BY START LIMIT 0,1000";		
$export = mysql_query($select);
$count = mysql_num_fields($export);
/********************************************
Extract field names and write them to the $header
variable
/********************************************/
$header = array('Startjahr', 'Endjahr', 'Ort', 'Wert', 'Thema');
$quelle = 'QUELLE: Datenbank Bernhist http://www.bernhist.ch';
//for ($i = 0; $i < $count; $i++) {
//	$header .= mysql_field_name($export, $i)."\t";
//}
/********************************************
Extract all data, format it, and assign to the $data
variable
/********************************************/
$data = '';

while($row = mysql_fetch_row($export)) {
	$line = '';
	foreach($row as $value) {											
		if ((!isset($value)) OR ($value == "")) {
			$value = "\t";
		} else {
			$value = str_replace('"', '""', $value);
			$value = '"' . $value . '"' . "\t";
		}
		$line .= $value;
	}
	$data .= trim($line)."\n";
}

$data = utf8_decode(str_replace("\r", '', $data));
/********************************************
Set the default message for zero records
/********************************************/
if ($data == '') {
	$data = "\n(0) Records Found!\n";						
}
/********************************************
Set the automatic downloadn section
/********************************************/
header('Content-type: application/octet-stream');
header('Content-Disposition: attachment; filename=Bernhist.xls');
header('Pragma: no-cache');
header('Expires: 0');
//print "\n$data";
print "$header[0]\t$header[1]\t$header[2]\t$header[3]\t$header[4]\n\n$data\n$quelle";
?>