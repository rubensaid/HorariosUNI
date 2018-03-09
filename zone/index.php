<?php
global $db;

$courses=recuperarCookie("courses");
$profes=recuperarCookie("profes");
$prof_name=recuperarCookie("prof_name");
$seccis=recuperarCookie("seccis");
//print_r($seccis);
$lis="";
if($courses) {	
	$coursesc=getCode($courses);	
	for($i=0; $i<count($courses); $i++) {
		if(!empty($profes[$courses[$i]])) {
			$lis.=theme(Array("[CODE]", "[PROFE]"), Array(str_replace("\n","",$coursesc[$i]), "<p id='".$profes[$courses[$i]]."' esto='profe'>".str_replace("\n","",$prof_name[$profes[$courses[$i]]])."</p>"), "li")."\n";
		} elseif(!empty($seccis[$i])) {
			$lis.=theme(Array("[CODE]", "[PROFE]"), Array(str_replace("\n","",$coursesc[$i]), "<p id='".$seccis[$i]."' esto='sec'>".str_replace("\n","","Secci&oacute;n ".$seccis[$i])."</p>"), "li2")."\n";
		} else {
			$lis.=theme(Array("[CODE]", "[PROFE]"), Array(str_replace("\n","",$coursesc[$i]), ""), "li")."\n";
		}
	}
}

//Java Script
$js='<script type="text/javascript">
		var is_done=false;
		var u_sel='.f_sel().';
		var f_sel='.u_sel().';
		var p_sel=new Array();
		var s_sel=new Array();
		var c_sel=new Array();
	</script>
	<script type="text/javascript" src="js/bsn.AutoSuggest_2.1.3.js"></script>
	<script type="text/javascript">
		function autosuggest(){
			var options = {
				script: "steps/curso.php?fid="+f_sel+"&",
				varname: "code",
				json:false,
				maxentries:5,
				minchars:2,
				timeout:9999,
				noresults:"No existe el Curso" 
			};
			var as = new bsn.AutoSuggest("code_input",  options);
		}
		
	</script>
	<script type="text/javascript" src="js/fx.js?ver=4.9"></script>	
	<script type="text/javascript" src="js/prettyForms.js"></script>
	<script type="text/javascript" src="js/tooltip.js"></script>
	<script type="text/javascript" src="js/tooltip.slide.js"></script>
	<script type="text/javascript" src="js/tooltip.dynamic.js"></script>
	<script type="text/javascript" src="js/toolbox.js?ver=4.9"></script>';
	
//CSS
$css='<link rel="stylesheet" type="text/css" href="css/prettyForms.css"/>	
	<link rel="stylesheet" type="text/css" href="css/autosuggest_inquisitor.css"/>
	<link rel="stylesheet" type="text/css" href="css/toolbox.css"/>';
	
//Body Effect
$bodyfx='onload="autosuggest();"';

//Content<div id="msgimportant">DB <strong>FIM UNI 2011-2</strong> actualizada. Correciones al 25/08/2011 6:00pm.</div>
$content='
		
		<div id="msgimportanta">Actualizando DB para PA 2012-1 en progreso...</div>		
		<div class="uni">Universidad Nacional de Ingenier&iacute;a <span>cambiar</span></div>
			<div class="facu">Facultad de Ingenier&iacute;a Mec&aacute;nica <span>cambiar</span></div>
			<div class="course">
				<ul>[LISTA]</ul>
				<input type="text" id="code_input" name="code_input" autocomplete="off" value="C&oacute;digo del Curso"></input> <span>+ agregar curso</span>
				<div class="horas_cruce" title="Permita el cruce de horas de clases. No distingue entre teoria y practica.">
					<input type="text" id="horas_input" name="horas_input" value="" size="1"></input>
					Habilitar Cruces? <input type="checkbox" id="horas_cruce" name="horas_cruce" />
				</div>
			</div>			
			<div class="TT">
				<button type="button" title="Para Generar o Ver Nuevos posibles presiona este boton!" name="makeTT" id="makeTT">Generar Horario<br />Ya!</button><br />
				<a id="cleanlist" class="underbottom">Limpiar Lista</a> <a id="tthelp" class="underbottom help">Ayuda</a>
			</div>
			<div id="TimeTable">
			</div>
			<div style="text-align: center;">
			<script type="text/javascript"><!--
				google_ad_client = "ca-pub-6285500735898731";
				/* Enlaces Tira */
				google_ad_slot = "4377643819";
				google_ad_width = 468;
				google_ad_height = 15;
				//-->
			</script>
			<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
			</script>
			</div>';
			
//Estadisticas
include("blocks/statistics.profe.php");
include("blocks/statistics.curso.php");
?>