<?php
/*
HorariosStatistics 1.0 by RubensaiD
Funciones para manejo de las Estadisticas del Sitio
Code 09 FIM
http://www.code09fim.com
*/

/*
Agregar +1 en el campo hit del profesor
*/
function PlusProfessor($pid) {
	global $db;
	foreach($pid as $curso=>$prof) {
		if($prof!="") {
			$db->query("UPDATE profesor SET hits=hits+1 WHERE pid='".$prof."'");			
		}
	}
}

/*
Agregar +1 en el campo hit del curso
Agrega Cantidad de Cursos para Promedio
*/
function PlusCourse($cid,$fid) {
	global $db;
	foreach($cid as $ind=>$curso) {
		$db->query("UPDATE cursos SET hits=hits+1 WHERE cid='".$curso."'");
	}
	$prim=$db->query("SELECT * FROM stats_cursos WHERE fid='".$fid."' and cursos='".count($cid)."'");
	if($db->num_rows($prim)>0) {
		$db->query("UPDATE stats_cursos SET cant=cant+1 WHERE fid='".$fid."' and cursos='".count($cid)."'");
	} else {
		$db->query("INSERT INTO stats_cursos VALUES ('".$fid."','".count($cid)."','1')");
	}	
}

/*
Agrega Cantidad de Horas de Cruce para Promedio
*/
function PlusHCruce($hc,$fid) {
	global $db;
	$prim=$db->query("SELECT * FROM stats_horas WHERE fid='".$fid."' and horas='".$hc."'");
	if($db->num_rows($prim)>0) {
		$db->query("UPDATE stats_horas SET cant=cant+1 WHERE fid='".$fid."' and horas='".$hc."'");
	} else {
		$db->query("INSERT INTO stats_horas VALUES ('".$fid."','".$hc."','1')");
	}
}

?>