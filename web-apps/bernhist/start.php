<?php

require_once('./TreeMenu.php');

$icon = 'folder.gif';
$expandedIcon = 'folder-expanded.gif';
$menu = new HTML_TreeMenu();

$rname = 'Kanton Bern';
$rkey = '16';
$rtype = 'Kanton';
$rvalue = "$rkey|$rname|$rtype";
$node1 = new HTML_TreeNode(array('text' => $rname.' <em>('.$rtype.')</em><br>', 'link' => "ort.php?ortkey=$rvalue"));

require_once('conn.php');

//level 2 abfragen
$level2 = mysql_query('SELECT * FROM ort1 where level2 IS NOT NULL && level2=16');

//level2 ausgeben
while ($row = mysql_fetch_array($level2)) {
	$keyl2 = $row['KEY'];
	$namel2 = $row['NAME'];
	$typel2 = $row['TYPE'];
	$valuel2 = "$keyl2|$namel2|$typel2";

	$node1_1 = &$node1->addItem(new HTML_TreeNode(array('text' => $namel2.' <em>('.$typel2.')</em>', 'link' => "ort.php?ortkey=$valuel2")));
		//, 'icon' => $icon, 'expandedIcon' => $expandedIcon)));

	//level 3 abfragen
	$level3 = @mysql_query("SELECT * FROM ort1 where level3 IS NOT NULL && level3=$keyl2");

	//level3 ausgeben
	while ($row = mysql_fetch_array($level3)) {
		$keyl3 = $row['KEY'];
		$namel3 = $row['NAME'];
		$typel3 = $row['TYPE'];
		$valuel3 = "$keyl3|$namel3|$typel3";

		$node1_1_1 = &$node1_1->addItem(new HTML_TreeNode(array('text' => $namel3.' <em>('.$typel3.')</em>', 'link' => "ort.php?ortkey=$valuel3")));

		//level 4 abfragen
		$level4 = @mysql_query("SELECT * FROM ort1 where level4 IS NOT NULL && level4=$keyl3");

		//level4 ausgeben
		while ($row = mysql_fetch_array($level4)) {
			$keyl4 = $row['KEY'];
			$namel4 = $row['NAME'];
			$typel4 = $row['TYPE'];
			$valuel4 = "$keyl4|$namel4|$typel4";

			$node1_1_1_1 = &$node1_1_1->addItem(new HTML_TreeNode(array('text' => $namel4.' <em>('.$typel4.')</em>', 'link' => "ort.php?ortkey=$valuel4")));

			//level 5 abfragen
			$level5 = @mysql_query("SELECT * FROM ort1 where level5 IS NOT NULL && level5=$keyl4");

			//level5 ausgeben
			while ($row = mysql_fetch_array($level5)) {
				$keyl5 = $row['KEY'];
				$namel5 = $row['NAME'];
				$typel5 = $row['TYPE'];
				$valuel5 = "$keyl5|$namel5|$typel5";

				$node1_1_1_1->addItem(new HTML_TreeNode(array('text' => $namel5." <em>(".$typel5.")</em>", 'link' => "ort.php?ortkey=$valuel5")));
			}
		}
	}
}

$menu->addItem($node1);
	
// Create the presentation class
$treeMenu = new HTML_TreeMenu_DHTML($menu, array('images' => './images', 'defaultClass' => 'treeMenuDefault'));
$listBox  = new HTML_TreeMenu_Listbox($menu, array('linkTarget' => '_self'));

?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<title>BERNHIST - Historisch-Statistische Datenbank des Kantons Bern</title>
		<link href="/content/css/global.css" rel="stylesheet" type="text/css" />
		<script src="/web-apps/bernhist/js/TreeMenu.js" language="JavaScript" type="text/javascript"></script>
	</head>
	<body style="margin:10em 0 0 15px;">
		<div id="header">
			<img src="/content/images/titelbar.gif" />
		</div>
		<script language="JavaScript" type="text/javascript">
			<!--
				a = new Date();
				a = a.getTime();
			//-->
		</script>
		<?php $treeMenu->printMenu(); ?>
		<script language="JavaScript" type="text/javascript">
			<!--
				b = new Date();
				b = b.getTime();
				document.write('<p style="font-size:smaller">Time to render tree: ' + ((b - a) / 1000) + 's</p>');
			//-->
		</script>
		<hr />
		<a href="/d/index.html"><img src="/content/images/home.png"> zur Startseite</a>
	</body>
</html>