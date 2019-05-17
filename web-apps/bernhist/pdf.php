<?php

/*
* This php-file writes the content of a databasequery in a PDF-file.
*/

// Getting theme - variable
$themavar = $_REQUEST['themavar'];
// Splits the string
$themaarray = explode('|', $themavar);
$themakey = $themaarray[0];
$themaname = $themaarray[1];
$thematype = $themaarray[2];

// Getting place - variable
$ortvar = $_REQUEST['ortvar'];
// Splits the string
$ortarray = explode('|', $ortvar);
$ortkey = $ortarray[0];
$ortname = $ortarray[1];
$orttype = $ortarray[2];

// Getting time - variables
$startjahr = $_REQUEST['startjahr'];
$endjahr = $_REQUEST['endjahr'];

// Connecting to the DB - server
require_once('./conn.php');

// SQL - statement
$result = @mysql_query('SELECT START,END,ort1.NAME,OBS_VALUE,thema.STD_TERM_NAME FROM obs_base,ort1,quellenthema,thema WHERE obs_base.src_term_key=quellenthema.src_term_key '.
	"AND quellenthema.std_term_key=thema.std_term_key AND obs_base.STD_SYS_KEY=$ortkey AND thema.std_term_key=$themakey AND START>=$startjahr AND END<=$endjahr ".
	'AND obs_base.STD_SYS_KEY=ort1.key ORDER BY START LIMIT 0,1000');

define('FPDF_FONTPATH','/usr/share/php/fpdf/font');
require_once('fpdf.php');

class PDF extends FPDF {
	var $B;
	var $I;
	var $U;
	var $HREF;

	function PDF ($orientation='P', $unit='mm', $format='A4') {
		//Call parent constructor
		$this->FPDF($orientation,$unit,$format);
		//Initialization
		$this->B = 0;
		$this->I = 0;
		$this->U = 0;
		$this->HREF = '';
	}

	function WriteHTML ($html) {
		//HTML parser
		$html = str_replace("\n", ' ', $html);
		$a = preg_split('/<(.*)>/U',$html, -1, PREG_SPLIT_DELIM_CAPTURE);

		foreach($a as $i=>$e) {
			if ($i%2 == 0) {
				//Text
				if ($this->HREF) $this->PutLink($this->HREF, $e);
				else $this->Write(5, $e);
			} else {
				//Tag
				if ($e{0} == '/') {
					$this->CloseTag(strtoupper(substr($e, 1)));
				} else {
					//Extract attributes
					$a2 = explode(' ', $e);
					$tag = strtoupper(array_shift($a2));
					$attr = array();
					foreach($a2 as $v) if(ereg('^([^=]*)=["\']?([^"\']*)["\']?$', $v, $a3)) $attr[strtoupper($a3[1])] = $a3[2];
					$this->OpenTag($tag, $attr);
				}
			}
		}
	}

	function OpenTag ($tag, $attr) {
		//Opening tag
		if ($tag == 'B' or $tag == 'I' or $tag == 'U') $this->SetStyle($tag, true);
		if ($tag == 'A') $this->HREF = $attr['HREF'];
		if ($tag == 'BR') $this->Ln(5);
	}

	function Footer () {
		//Go to 1.5 cm from bottom
		$this->SetY(-15);
		//Select Arial italic 8
		$this->SetFont('Arial', 'I', 10);
		//Print centered page number
		$this->Write(5, 'QUELLE: Datenbank Bernhist http://www.bernhist.ch');
	}

	function CloseTag ($tag) {
		//Closing tag
		if ($tag == 'B' or $tag == 'I' or $tag == 'U') $this->SetStyle($tag, false);
		if ($tag == 'A') $this->HREF = '';
	}

	function SetStyle ($tag, $enable) {
		//Modify style and select corresponding font
		$this->$tag += ($enable ? 1 : -1);
		$style = '';

		foreach(array('B','I','U') as $s) if($this->$s > 0) $style .= $s;
		$this->SetFont('', $style);
	}

	function PutLink($URL, $txt)
		{
		//Put a hyperlink
		$this->SetTextColor(0, 0, 255);
		$this->SetStyle('U', true);
		$this->Write(5, $txt, $URL);
		$this->SetStyle('U', false);
		$this->SetTextColor(0);
	}
}

$pdf = new PDF();
$pdf->Open();
$pdf->AddPage();
//Draws the line
$pdf->Line(10, 35, 200, 35);
//Set font
$pdf->SetFont('Arial', 'B', 16);
//Centered text in a framed 20*10 mm cell and line break
$pdf->Cell(0, 10, 'Bernhist', 1, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$coltitle = "<br><br>$ortname - $themaname <i>($thematype)</i><br><br>";
$pdf->WriteHTML(utf8_decode($coltitle));
$pdf->SetFont('', '');
$pdf->SetLeftMargin(20);
$pdf->SetFontSize(10);

//Fills the pdf-file with the dynamic content
while($row = mysql_fetch_array($result)) {
	$start = $row['START'];
	$end = $row['END'];
	$ort= $row['NAME'];
	$value = $row['OBS_VALUE'];

	$html = "$start         $end          $ort  ($orttype)                $value<br>";
	$pdf->WriteHTML(utf8_decode($html));
}

$pdf->Output();
?>
