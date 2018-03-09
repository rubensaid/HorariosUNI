<?php
require_once("../core/class.mysql.php");
require_once("../core/class.timetable.php");
require_once("../core/function.tools.php");
//noCache();

$db = new MySQL();
//Universidad
$query="SELECT * FROM universidad ORDER BY uname DESC";
$consulta = $db->query($query);
$alma="";
while($row=$db->fetch_array($consulta)) {
	if($row['uid']==$_GET['uid']) $state="selected"; else $state="";
	$alma .= theme(Array("[VALUE]", "[TEXT]", "[SELECTED]"), Array($row['uid'], $row['uname'], $state), "options")."\n";
}
echo theme(Array("[ID]", "[OPTIONS]", "[EXTRA]"), Array("uni", $alma, "<span class='sel'>cancelar</span>"), "select")."\n";

$db->close();
?>