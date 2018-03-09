<?php
require_once("../core/class.mysql.php");
require_once("../core/class.timetable.php");
require_once("../core/function.tools.php");
//noCache();

$db = new MySQL();
//Facultad
$query="SELECT * FROM facultad WHERE uid='".$_GET['uid']."' ORDER BY fname DESC";
$consulta = $db->query($query);
$alma="";
while($row=$db->fetch_array($consulta)) {
	if($row['fid']==$_GET['fid']) $state="selected"; else $state="";
	$alma .= theme(Array("[VALUE]", "[TEXT]", "[SELECTED]"), Array($row['fid'], $row['fname'], $state), "options")."\n";
}
echo theme(Array("[ID]", "[OPTIONS]", "[EXTRA]"), Array("facu", $alma, "<span class='sel'>cancelar</span>"), "select")."\n";

$db->close();
?>