<?php
require_once("../core/class.mysql.php");
require_once("../core/class.timetable.php");
	$horas=0;
	if(isset($_GET['horas'])) {$horas=$_GET['horas'];}
	$tt = new TimeTable($horas);
	$db = new MySQL();
	$need_hour=false;
require_once("../core/function.tools.php");
require_once("../core/function.statistics.php");
noCache();
$memo_ini=memory_get_peak_usage();
set_time_limit(10);

//Facultad y Universidad
$f_sel=f_sel($_GET['f_sel']);
$u_sel=u_sel($_GET['u_sel']);

//Cursos Elegidos
$course=$_GET['c_sel'];
$course=explode("-",$course);
for($i=0; $i<count($course); $i++) {
	if($course[$i]!="") {
		$courses[$i]=$course[$i];		
	}
}
$courses=getID($courses);
if(!checkCourses($courses, $f_sel)) {echo '<span class="fail">Los cursos no corresponden con la facultad seleccionada.</span>'; exit;}
//Profes Elegidos
$profe=$_GET['p_sel'];
$profe=explode("-",$profe);
for($i=0; $i<count($courses); $i++) {	
	$profes[$courses[$i]]=$profe[$i];	
}
//Secciones Elegidas
$seccis=$_GET['s_sel'];
$seccis=explode("-",$seccis);
if(!isset($_COOKIE['seccis'])) {
	setcookie("seccis",json_encode($seccis),time()+7*24*60*60,"/");
} else {
	if(json_encode(recuperarCookie("seccis"))!=json_encode($seccis)) {
		setcookie("seccis",json_encode($seccis),time()+7*24*60*60,"/");
	}
}

$ck_courses=recuperarCookie("courses");
$ck_profes=recuperarCookie("profes");

//Estadisticas Profesor y Universidad
if(json_encode($profes)!=json_encode($ck_profes)) {
	PlusProfessor($profes);
}
if(json_encode($courses)!=json_encode($ck_courses)) {
	PlusCourse($courses,$f_sel);
}

//Manejar Cookies
if(!$ck_courses or !$ck_profes) {		
	setcookie("courses",json_encode($courses),time()+7*24*60*60,"/");
	setcookie("profes",json_encode($profes),time()+7*24*60*60,"/");
	$secciones=getSeccion($courses, $profes);
	//$room_data=$secciones[4];
	$prof_name=$secciones[4];
	$prof_data=$secciones[3];
	$sec_data=$secciones[2];
	$hid_data=$secciones[1];
	$secciones=$secciones[0];
	setcookie("secciones",json_encode($secciones),time()+7*24*60*60,"/");
	setcookie("hid_data",json_encode($hid_data),time()+7*24*60*60,"/");
	setcookie("sec_data",json_encode($sec_data),time()+7*24*60*60,"/");
	setcookie("prof_data",json_encode($prof_data),time()+7*24*60*60,"/");
	setcookie("prof_name",json_encode($prof_name),time()+7*24*60*60,"/");
	//setcookie("room_data",json_encode($room_data),time()+7*24*60*60,"/");
} else {
	if(json_encode($courses)!=json_encode($ck_courses) or json_encode($profes)!=json_encode($ck_profes)) {
		setcookie("courses",json_encode($courses),time()+7*24*60*60,"/");
		setcookie("profes",json_encode($profes),time()+7*24*60*60,"/");
		$secciones=getSeccion($courses, $profes);
		//$room_data=$secciones[4];
		$prof_name=$secciones[4];
		$prof_data=$secciones[3];
		$sec_data=$secciones[2];
		$hid_data=$secciones[1];
		$secciones=$secciones[0];
		setcookie("secciones",json_encode($secciones),time()+7*24*60*60,"/");
		setcookie("hid_data",json_encode($hid_data),time()+7*24*60*60,"/");
		setcookie("sec_data",json_encode($sec_data),time()+7*24*60*60,"/");
		setcookie("prof_data",json_encode($prof_data),time()+7*24*60*60,"/");
		setcookie("prof_name",json_encode($prof_name),time()+7*24*60*60,"/");
		//setcookie("room_data",json_encode($room_data),time()+7*24*60*60,"/");
	} else {
		$courses=$ck_courses;
		$profes=$ck_profes;
		$secciones=recuperarCookie("secciones");
		$hid_data=recuperarCookie("hid_data");
		$sec_data=recuperarCookie("sec_data");
		$prof_data=recuperarCookie("prof_data");
		$prof_name=recuperarCookie("prof_name");
		//$room_data=recuperarCookie("room_data");
	}
}
for($i=0; $i<count($courses); $i++) {	
	if($seccis[$i]!="") {
		$secciones[$i]=$seccis[$i];
	}
}
$sec_sel=setTT($secciones, $courses);
$horario=$tt->getTT();
$inform=$tt->getInform();
echo '<table width="100%" id="tt_done">
<thead><tr>
<th class="tithora"> </th><th>Lunes</th><th>Martes</th><th>Miercoles</th><th>Jueves</th><th>Viernes</th><th>Sabado</th>
</tr></thead><tbody>';
for($i=0; $i<14; $i++) {
	$ts=$i+8; $tn=$ts+1;
	echo '<tr><td class="hora">'.$ts.' - '.$tn.'</td>';
	for($j=0; $j<6; $j++) {
		if($horario[$i][$j]!=0) {
			//$var='<strong>'.$cid_show.'</strong>';
			$var=$hid_data[$horario[$i][$j]].'-'.$sec_sel[$sec_data[$horario[$i][$j]]];
			$solocur='curso="'.$hid_data[$horario[$i][$j]].'"';
			$solosec='seccion="'.$sec_sel[$sec_data[$horario[$i][$j]]].'"';
			$vartit='title="'."<strong>".$hid_data[$horario[$i][$j]]."</strong> en la secci&oacute;n <strong>".$sec_sel[$sec_data[$horario[$i][$j]]]."</strong>";// en el <strong>".$room_data[$horario[$i][$j]]."</strong>
			$vartit.="<br />Dicta Ing. <strong>".$prof_name[$prof_data[$horario[$i][$j]]]."</strong>";
			if($inform[$i][$j]!=0) {
				$class='class="fail"';
				$vartit.="<br />"."<span class='fail'>".'Cruce con '."<strong>".$hid_data[$inform[$i][$j]].'-'.$sec_sel[$sec_data[$inform[$i][$j]]]."</strong></span>";
			} else {$class='';}
			$vartit.='"';
		} else { $var=""; $vartit=""; $solocur="";}
		echo '<td '.$vartit.' '.$class.' '.$solocur.' '.$solosec.'>'.$var.'</td>';
	}
	echo '</tr>';
}
echo '</tbody></table>';
//echo '<span "dbtotal"></span><br />';
if($tt->getCruzadas()>0) {	
	echo '<span class="fail">Hay en total '.$tt->getCruzadas().' horas de cruce (rojo).</span>';
	PlusHCruce($tt->getCruzadas(),$f_sel);
}

//Esportar a Excel
echo '<form action="steps/expcel.php" method="post" target="_blank" id="exp_excel">
	<p title="Guarda tu Horario en un Archivo Excel!">Exportar a Excel  <img src="images/export_excel.png" style="margin-bottom: -4px;" /></p>
	<input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
</form>';
$memo_fin=memory_get_peak_usage();
echo '<font style="font-size: 9px; font-color: #D8D8D8;">Se realizar&oacute;n '.$db->getTotal().' consultas a la DB &#166; Se ha usado un m&aacute;ximo de '.round(($memo_fin - $memo_ini)/(1024),2).' Kb</font>';

$db->close();
?>