<?php
global $db, $extrablock;

$extrablock.="<div><h3>Ranking Cursos</h3><ol>";
$query="SELECT code, cname FROM cursos WHERE hits<>0 and fid='".f_sel()."' ORDER BY hits DESC LIMIT 5";
$consulta=$db->query($query);
while($row=$db->fetch_array($consulta)) {
	$var="";
	if($row['cname']!="") {
		$var=$row['cname'];
	}
	$extrablock.="<li>".$row['code'].' '.$var."</li>";
}
$extrablock.="</ol><a href='#' title='Ranking de los Cursos mas pedidos - Estadisticas'>+ mas...</a></div>";
?>