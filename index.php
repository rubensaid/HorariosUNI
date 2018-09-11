<?php
require_once("core/class.mysql.php");
require_once("core/class.timetable.php");
$db = new MySQL();
require_once("core/function.tools.php");

$js=""; $css=""; $title=""; $content="Proximamente..."; $bodyfx=""; $lis=""; $extrablock="";

$url_bar=explode("?",$_SERVER['REQUEST_URI']);
$url_bar=$url_bar[1];
if($url_bar=="") $url_bar="index";
if($url_bar=="addDB") {	
	$content=makeDBCourse("fimuni2013-1.txt");
} else {	
	if(file_exists("zone/".$url_bar.".php")) {
		include("zone/".$url_bar.".php");
	} else {
		include("zone/index.php");
	}
}

if($title=="") {
	$title="Horarios - Arma tu horario universitario Online!!!";
} else {
	$title="Horarios - ".$title;
}

print theme(Array("[JS]", "[CSS]", "[TITLE]", "[CONTENT]", "[BODYFX]", "[LISTA]","[FEED]","[EXTRA]"), Array($js, $css, $title, $content, $bodyfx, $lis, LeerFeed(), $extrablock), "index");

$db->close();
?>