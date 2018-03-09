<?php
require_once("../core/class.mysql.php");
require_once("../core/class.timetable.php");
require_once("../core/function.tools.php");
//noCache();
//echo utf8_decode($_GET['code']);

$db = new MySQL();
//Profesor
$query="SELECT cid FROM cursos WHERE code LIKE '%".utf8_decode($_GET['code'])."%'";
$consulta=$db->query($query);
$row=$db->fetch_array($consulta);
$cid=$row['cid'];
$query="SELECT pid FROM horarios WHERE cid='".$cid."'";
$consulta=$db->query($query);
$alma=null;
while($row=$db->fetch_array($consulta)) {
	if(!in_array($row['pid'], $alma)) {
		$pid[]=$row['pid'];
		$alma[]=$row['pid'];
	}
}
//Prof Sel
$prof_sel="";
$profes=$_GET['p_sel'];
if(preg_replace("/[^0-9]/","", $profes)) $prof_sel=$profes;
////
if($prof_sel=="") $sinp="selected"; else $sinp="";
$alma = theme(Array("[VALUE]", "[TEXT]", "[SELECTED]"), Array(0, "- Cualquier Profesor -", $sinp), "options")."\n";
if($pid[0]!=0) {
for($i=0; $i<count($pid); $i++) {
	$query="SELECT pname FROM profesor WHERE pid='".$pid[$i]."'";
	$consulta=$db->query($query);
	$row=$db->fetch_array($consulta);
	if($pid[$i]==$prof_sel) $state="selected"; else $state="";
	$alma .= theme(Array("[VALUE]", "[TEXT]", "[SELECTED]"), Array($pid[$i], $row['pname'], $state), "options")."\n";
}
}
echo theme(Array("[ID]", "[OPTIONS]", "[EXTRA]"), Array("profe", $alma, "<span class='sel'>cancelar</span>"), "select")."\n";

$db->close();
?>