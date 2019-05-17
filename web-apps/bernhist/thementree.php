<?php

$ortvar = $_REQUEST['ortvar'];
$array = explode('|', $ortvar);
$key = $array[0];
$name = $array[1];
$type = $array[2];

require_once('./TreeMenu.php');

$icon         = 'folder.gif';
$expandedIcon = 'folder-expanded.gif';
$menu = new HTML_TreeMenu();
$node1 = new HTML_TreeNode(array('text' => 'Alle Themen<br>'));

require_once('./conn.php');

//level 1 abfragen
$level1 = @mysql_query('SELECT thema.STD_TERM_KEY,thema.STD_TERM_NAME,thema.STD_TERM_TYPE FROM thema,thema_in WHERE
thema_in.STD_TERM_IN=0 AND thema.STD_TERM_KEY=thema_in.STD_TERM_KEY ORDER BY thema.STD_TERM_NAME');

//level1 ausgeben
while ($row = mysql_fetch_array($level1)) {
	$keyl1 = $row['STD_TERM_KEY'];
	$namel1 = $row['STD_TERM_NAME'];
	$typel1 = $row['STD_TERM_TYPE'];
	$valuel1 = "$keyl1|$namel1|$typel1";

	if ($typel1 == 'Kategorie') {
		$node1_1 = &$node1->addItem(new HTML_TreeNode(array('text' => $namel1)));//, 'link' => "thema.php?themavar=$valuel1")));
	} else {
		$node1_1 = &$node1->addItem(new HTML_TreeNode(array('text' => $namel1, 'link' => "thema.php?themavar=$valuel1&ortvar=$ortvar")));
	}

	//level 2 abfragen
	$level2 = @mysql_query('SELECT thema.STD_TERM_KEY,thema.STD_TERM_NAME,thema.STD_TERM_TYPE FROM thema,thema_in WHERE '.
		"thema_in.STD_TERM_IN=$keyl1 AND thema.STD_TERM_KEY=thema_in.STD_TERM_KEY ORDER BY thema.STD_TERM_NAME");

	//level2 ausgeben
	while ($row = mysql_fetch_array($level2)) {
		$keyl2 = $row['STD_TERM_KEY'];
		$namel2 = $row['STD_TERM_NAME'];
		$typel2 = $row['STD_TERM_TYPE'];
		$valuel2 = $keyl2.'|'.$namel2.'|'.$typel2;

		if ($typel2 == 'Kategorie') {
			$node1_1_1 = &$node1_1->addItem(new HTML_TreeNode(array('text' => $namel2)));
		} else {
			$node1_1_1 = &$node1_1->addItem(new HTML_TreeNode(array('text' => $namel2, 'link' => "thema.php?themavar=$valuel2&ortvar=$ortvar")));
		}

		//level 3 abfragen
		$level3 = @mysql_query('SELECT thema.STD_TERM_KEY,thema.STD_TERM_NAME,thema.STD_TERM_TYPE FROM thema,thema_in '.
			"WHERE thema_in.STD_TERM_IN=$keyl2 AND thema.STD_TERM_KEY=thema_in.STD_TERM_KEY ORDER BY thema.STD_TERM_NAME");

		//level3 ausgeben
		while ($row = mysql_fetch_array($level3)) {
			$keyl3 = $row['STD_TERM_KEY'];
			$namel3 = $row['STD_TERM_NAME'];
			$typel3 = $row['STD_TERM_TYPE'];
			$valuel3 = "$keyl3|$namel3|$typel3";
			//$checkl3 = "<input type=\"checkbox\" name=\"thema\" value=\"$valuel3\"> $namel3";
			//$bothl3 = $checkl3. "  "."(".$typel3.")" ;

			if ($typel3 == 'Kategorie') {
				$node1_1_1_1 = &$node1_1_1->addItem(new HTML_TreeNode(array('text' => $namel3)));//, 'link' => "thema.php?themavar=$valuel3")));
			} else {
				$node1_1_1_1 = &$node1_1_1->addItem(new HTML_TreeNode(array('text' => $namel3, 'link' => "thema.php?themavar=$valuel3&ortvar=$ortvar")));
			}

			//level 4 abfragen
			$level4 = @mysql_query('SELECT thema.STD_TERM_KEY,thema.STD_TERM_NAME,thema.STD_TERM_TYPE FROM thema,thema_in '.
				"WHERE thema_in.STD_TERM_IN=$keyl3 AND thema.STD_TERM_KEY=thema_in.STD_TERM_KEY ORDER BY thema.STD_TERM_NAME");

			//level4 ausgeben
			while ($row = mysql_fetch_array($level4)) {
				$keyl4 = $row['STD_TERM_KEY'];
				$namel4 = $row['STD_TERM_NAME'];
				$typel4 = $row['STD_TERM_TYPE'];
				$valuel4 = $keyl4.'|'.$namel4.'|'.$typel4;
				//$checkl4 =  "<input type='checkbox' name='thema'value='$valuel4'> $namel4";
				//$bothl4 = $checkl4. "  "."(".$typel4.")" ;

				if ($typel4 == 'Kategorie') {
					$node1_1_1_1_1 = &$node1_1_1_1->addItem(new HTML_TreeNode(array('text' => $namel4)));//, 'link' => "thema.php?themavar=$valuel3")));
				} else {
					$node1_1_1_1_1 = &$node1_1_1_1->addItem(new HTML_TreeNode(array('text' => $namel4, 'link' => "thema.php?themavar=$valuel4&ortvar=$ortvar")));
				}

				//$node1_1_1_1 = &$node1_1_1->addItem(new HTML_TreeNode(array('text' => $namel3, 'link' => $link)));
				//$node1_1_1_1 = &$node1_1_1->addItem(new HTML_TreeNode(array('text' => $namel3, 'link' => "thema.php?themavar=$valuel3")));
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
		<b>Folgender Ort ist ausgewählt:</b> <?php echo "<strong>$name</strong> <em>($type)</em>";?>
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
		<a href="/d/index.html"><img src="/content/images/home.png" /> zur Startseite</a>
	</body>
</html>