<?php
global $db, $extrablock;

$extrablock.="<div><h3>Ranking Profesores</h3><ol>";
$query="SELECT pname FROM profesor WHERE hits<>0 and fid='".f_sel()."' ORDER BY hits DESC LIMIT 5";
$consulta=$db->query($query);
while($row=$db->fetch_array($consulta)) {
	$extrablock.="<li>".$row['pname']."</li>";
}
$extrablock.="</ol><a href='#' title='Ranking de los Profesores mas pedidos - Estadisticas'>+ mas...</a></div>";
?>