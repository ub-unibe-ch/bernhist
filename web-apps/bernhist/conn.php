<?php

$host = 'ub-mysql01.unibe.ch';
$usr = 'usr_bernhist';
$pwd = 'udQsU!oxVqP94DYI$VL9nACCuG@quK(CdgLM';
$dbcnx = mysql_connect($host, $usr, $pwd);
mysql_set_charset('utf8');
mysql_select_db('bernhist');

?>
