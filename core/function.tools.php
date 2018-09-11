<?php
/*
HorariosTools 3.0 by RubensaiD
Herramientas para la generacion del Horario
Code 09 FIM
http://www.code09fim.com
*/

/*Variable Globales*/
function f_sel($val="") {
	if(!isset($_COOKIE['facultad'])) {
		setcookie("facultad",1,time()+7*24*60*60,"/");
	} else {
		if($_COOKIE['facultad']!=$val && !empty($val)) {
			setcookie("facultad",$val,time()+7*24*60*60,"/");
			return $val;
		} else {
			return $_COOKIE['facultad'];
		}
	}
}

function u_sel($val="") {
	if(!isset($_COOKIE['universidad'])) {
		setcookie("universidad",1,time()+7*24*60*60,"/");
	} else {
		if($_COOKIE['universidad']!=$val && !empty($val)) {
			setcookie("universidad",$val,time()+7*24*60*60,"/");
			return $val;
		} else {
			return $_COOKIE['universidad'];
		}
	}
}

/*
Obtener ID de curso
Se debe enviar los parametros
	$db		Objeto MySQL			GLOBAL
	$courses	Array - Codigos de Curso
Devuelve un vector con los ID de los cursos
*/
function getID($courses) {
	global $db;		
	for($i=0; $i<count($courses); $i++) {		
		$consul=$db->query("SELECT cid FROM cursos WHERE code LIKE '%".$courses[$i]."%'");
		$row=$db->fetch_array($consul);
		$cid[]=$row['cid'];		
	}
	return $cid;
}

/*
Obtener Codigo de curso
Se debe enviar los parametros
	$db		Objeto MySQL			GLOBAL
	$courses	Array - ID de Curso
Devuelve un vector con los Codigos de los cursos
*/
function getCode($courses) {
	global $db;		
	for($i=0; $i<count($courses); $i++) {		
		$consul=$db->query("SELECT code FROM cursos WHERE cid='".$courses[$i]."'");
		$row=$db->fetch_array($consul);
		$cid[]=$row['code'];		
	}
	return $cid;
}

/*
Obtener Nombre del Profesor
Se debe enviar los parametros
	$db		Objeto MySQL			GLOBAL
	$profes	Array - ID de Curso
Devuelve un vector con los Nombres de los Profesores
*/
function getName($profes, $cid, $single=false) {
	global $db;		
	if(!$single) {
		for($i=0; $i<count($profes); $i++) {		
			if(!empty($profes[$cid[$i]])) {
				$consul=$db->query("SELECT pname FROM profesor WHERE pid='".$profes[$cid[$i]]."'");
				$row=$db->fetch_array($consul);
				$pn[]=Array($row['pname'],$profes[$cid[$i]]);
			} else {$pn[]=Array("","");}
		}			
	} else {
		$consul=$db->query("SELECT pname FROM profesor WHERE pid='".$profes."'");
		$row=$db->fetch_array($consul);
		$pn=$row['pname'];
	}
	return $pn;
}

/*
Verificar Cursos
Se debe enviar los parametros
	$db		Objeto MySQL			GLOBAL
	$courses	Array - ID de Curso
	$f_sel	ID de la facultad seleccionada
Averigua si los cursos todos pertenecen a la facultad seleccionada, si no es asi devuelve false
*/
function checkCourses($courses, $f_sel) {
	global $db;			
	for($i=0; $i<count($courses); $i++) {		
		$consul=$db->query("SELECT fid FROM cursos WHERE cid='".$courses[$i]."'");
		$row=$db->fetch_array($consul);
		if($row['fid']!=$f_sel) return false;
	}
	return true;
}

/*
Obtener Secciones
Se debe enviar los parametros
	$db		Objeto MySQL			GLOBAL
	$cid	Array - Id del Curso
Devuelve una matriz con las secciones posibles de cada curso
*/
function getSeccion($cid, $pid) {
	global $db;
	for($i=0; $i<count($cid); $i++) {
		$aux="";
		$sec_pid=false;
		if(!empty($pid[$cid[$i]])) {			
			$consul = $db->query("SELECT sec FROM horarios WHERE pid='".$pid[$cid[$i]]."' and cid='".$cid[$i]."'");
			while($row=$db->fetch_array($consul)) {
				if($aux != $row['sec']) {
					$output[$i][]=$row['sec'];
					$aux=$row['sec'];
				}				
			}
			$sec_pid=true;
		}	
		$consul = $db->query("SELECT hid, cid, sec, pid FROM horarios WHERE cid='".$cid[$i]."'"); //, room
		while($row=$db->fetch_array($consul)) {
			if(!$sec_pid) {
				if($aux != $row['sec']) {
					$output[$i][]=$row['sec'];
					$aux=$row['sec'];
				}
			}
			$consulint=$db->query("SELECT code FROM cursos WHERE cid='".$cid[$i]."'");
			$rowint=$db->fetch_array($consulint);
			$hid_data[$row['hid']]=$rowint['code'];
			$sec_data[$row['hid']]=$cid[$i];
			$prof_data[$row['hid']]=$row['pid'];
			if(!isset($prof_name[$row['pid']])) {
				$prof_name[$row['pid']]=getName($row['pid'], null, true);
			}
			//$room_data[$row['hid']]=$row['room'];
		}
	}
	return Array($output,$hid_data,$sec_data,$prof_data,$prof_name); //,$room_data
}

/*
Armar el Horario
Se debe enviar los parametros
	$db		Objeto MySQL			GLOBAL
	$tt		Objeto TimeTable		GLOBAL
	$cid	Array - Id del Curso
	$secs	Matriz - Id y Secciones del Curso
Manda a la funcion setTTCuourse la consulta para sacar los horarios de una seccion elegida al azar de un curso
*/
function setTT($secs, $cid) {
	global $db, $tt;		
	for($i=0; $i<count($cid); $i++) {
		/*$bool=true;
		insertar:*/
		$log=Array();
		$try=0;
		$bool=false;
		while(!$bool) {
			$ran=rand(0,count($secs[$i])-1);
			if(!in_array($ran, $log)) {
				$query="SELECT * FROM horarios WHERE cid='".$cid[$i]."' and sec='".$secs[$i][$ran]."'";
				$consul=$db->query($query);			
				$bool=setTTCourse($consul);
				$out[$cid[$i]]=$secs[$i][$ran];
				$log[]=$secs[$i][$ran];
			} else {				
				$try++;
				if($try>count($secs[$i])) {
					if(!$need_hour) {
						echo "<span class='fail'>Algunos cursos no han podido ser ubicados. Intente aumentar las horas de cruce.</span>";
						$need_hour=true;
					}
					break;
				}
			}
			//if(!$bool) goto insertar;
		}
	}
	return $out;
}

/*
Pone los cursos en el TimeTable
Se debe enviar los parametros
	$db		Objeto MySQL			GLOBAL
	$tt		Objeto TimeTable		GLOBAL
	$conculta	Consulta a la base de datos para obtener horarios e Id de clases
Agrega todas las clases al TimeTable. Si logra hacerlo devuelve true si no, borra todo lo que habia agregado y devuelve false.
*/
function setTTCourse($consulta) {
	global $db, $tt;
	$hid_log=null;
	while($clases=$db->fetch_array($consulta)) {
		$insert=$tt->AddCourse($clases['day'], $clases['start'], $clases['end'], $clases['hid']);
		$hid_log[]=$clases['hid'];
		if(!$insert) {
			clearTTCourse($hid_log);
			return false;
			break;
		}
	}
	return true;
}

/*
Borra los cursos del TimeTable
Se debe enviar los parametros
	$tt		Objeto TimeTable		GLOBAL
	$clases	Array - Id de las clases
Quita todas las clases del TimeTable cuyo Id esta en $clases
*/
function clearTTCourse($clases) {
	global $tt;
	for($i=0; $i<count($clases); $i++) {
		$remove=$tt->RemoveCourse($clases[$i]);
		if(!$remove) {
			clearTTCourse($clases);			
			break;
		}		
	}
}

/*
Agrega los horarios a la Base de Datos
Se debe enviar los parametros
	$db		Objeto MySQL			GLOBAL	
	$filename	Archivo txt con la siguiente estructura
		CODE_COURSE
		SEC DAY START-END ROOM PROFESSOR
		SEC1/SEC2 DAY START-END ROOM PROFESSOR
El codigo del curso marca un nuevo inicio para el bucle que agrega las secciones.
*/
function makeDBCourse($filename) {
	$content="";
	$go=false;
	if(!file_exists("copy-".$filename)) {
		if(copy($filename,"copy-".$filename)) {
			$go=true;
		}
	} else {$go=true;}
	if($go) {
		$log="";
		$archivo=file("copy-".$filename);
		$cursos=0;	
		if(count($archivo)>0) {
			for($i=0; $i<count($archivo); $i++){
				$line=$archivo[$i];
				if($line!="") {
					$line_div=explode(" ",$line);
					if(count($line_div)==1) {
						$cursos++;
						if($cursos>=2) {
							break;
						} else {
							$cid=AddCourse($line, 1);
							$log.=";".$line;
							$content.="Agregado ".$line."<br />";
						}
					} else {
						$line=explode(" ",$line);
						$log.=";".implode(" ",$line);
						if(count($line)>5) {
							$arreglar=null;
							for($k=4; $k<count($line); $k++) {
								$arreglar[]=$line[$k];
							}
							$line[4]=implode("-",$arreglar);
						}
						$auxline=explode("/",$line[0]);
						$auxline2=explode("-",$line[2]);
						for($j=0; $j<count($auxline); $j++) {
							$pid=AddProfessor($line[4], 1);
							$how=AddClass($cid, $auxline[$j], getDayNum($line[1]), $auxline2[0], $auxline2[1], $line[3], $pid);
							$content.=" - Profe ".$line[4]." con ID ".$pid."<br />";
						}			
					}
				}
			}
			$archivo=";".implode(";",$archivo);	
			$archivo=str_replace($log,"",$archivo);	
			$archivo=str_replace(";","",$archivo);
			file_put_contents("copy-".$filename,$archivo); 
		} else {
			$content.="Terminado!";
		}
	}
	return $content;
}
/*
Recibe ID del curso, Seccion, Dia, Hora de Inicio, Hora de Fin, Salon, Id del Profesor
Y lo agrega y devuelve true o false
*/
function AddClass($cid, $sec, $day, $start, $end, $room, $pid) {
	global $db;
	$sec=strtoupper($sec);
	if(!existsClass($cid, $day, $start, $end, $sec)) {
		$est=$db->query("INSERT INTO horarios VALUES ('',".$cid.",'".$sec."',".$day.",".$start.",".$end.",'".$room."',".$pid.",'')");
	}
	return $est;
}

/*
Verifica si existe una clase, si existe devuelve true si no devuelve false
*/
function existsClass($cid, $day, $start, $end, $sec) {
	global $db;
	$consulta=$db->query("SELECT hid FROM horarios WHERE sec='".$sec."' and cid='".$cid."' and day='".$day."' and start='".$start."' and end='".$end."'");
	$num=$db->num_rows($consulta);
	if($num>0) {
		return true;
	} else {	
		return false;
	}	
}

/*
Recibe Codigo del curso, lo agrega y devuelve su Id
*/
function AddCourse($name, $fid) {
	global $db;
	$string=setStd(strtoupper($name));
	if(!existsCourse($string, $fid)) {
		$db->query("INSERT INTO cursos VALUES ('',".$fid.",'".$string."','','0')");
	}
	$consulta=$db->query("SELECT cid FROM cursos WHERE code='".$string."' and fid='".$fid."'");
	$row=$db->fetch_array($consulta);
	return $id=$row['cid'];		
}

/*
Verifica si existe un curso, si existe devuelve true si no devuelve false
*/
function existsCourse($name, $fid) {
	global $db;
	$consulta=$db->query("SELECT cid FROM cursos WHERE code='".$name."' and fid='".$fid."'");
	$num=$db->num_rows($consulta);
	if($num>0) {
		return true;
	} else {	
		return false;
	}	
}


/*
Recibe en form de APPAT-APMAT-NOMBRE-OTRO
Y agrega a la Base de datos
Appat Apmat Nombre
Devuelve el Id del profesor agregado
*/
function AddProfessor($name, $fid) {
	global $db;
	$prof=explode("-",$name);	
	if(count($prof)>=2) {
		$string=$prof[0]." ".$prof[1]." ".$prof[2];
		$string=ucwords(strtolower(setStd(utf8_decode($string))));
		if(!existsProfessor($string, $fid)) {
			$db->query("INSERT INTO profesor VALUES ('',".$fid.",'".$string."','0')");
		}
		$consulta=$db->query("SELECT pid FROM profesor WHERE pname='".$string."' and fid='".$fid."'");
		$row=$db->fetch_array($consulta);
		$id=$row['pid'];
	} else {
		$id=0;
	}
	return $id;
}

/*
Verifica si existe un profesor, si existe devuelve true si no devuelve false
*/
function existsProfessor($name, $fid) {
	global $db;
	$consulta=$db->query("SELECT pid FROM profesor WHERE pname LIKE '%".$name."%' and fid='".$fid."'");
	$num=$db->num_rows($consulta);
	if($num>0) {
		return true;
	} else {	
		return false;
	}	
}

/*
Recibe parametros LU MA MI JU VI SA
y devuelve 0 1 2 3 4 5
*/
function getDayNum($day) {
	$one=Array('LU','MA','MI','JU','VI','SA');
	$two=Array(0,1,2,3,4,5);
	return str_replace($one,$two,$day);
}

/*
Quita caracteres extraños de la cadena y la devuelve
*/
function setStd($str) {
	$one=Array("ñ","Ñ","á","Á","é","É","í","Í","ó","Ó","ú","Ú");
	$two=Array("&ntilde;","&Ntilde;","&aacute;","&Aacute;","&eacute;","&Eacute;","&iacute;","&Iacute;","&oacute;","&Oacute;","&uacute;","&Uacute;");
	return str_replace($one,$two,$str);
}

/*
Reemplaza los valores de $bus por $reem en la platilla html dada por el archivo $file
*/
function theme($bus, $reem, $file) {
	if(!file_exists("../themes/".$file.".html")) {
		if(!file_exists("themes/".$file.".html")) {
			die("ERROR: No existe la plantilla");
			exit;
		} else {
			return str_replace($bus, $reem, file_get_contents("themes/".$file.".html"));
		}
	} else {
		return str_replace($bus, $reem, file_get_contents("../themes/".$file.".html"));
	}
}

/*
Evita el cache para paginas php.
*/
function noCache() {
  header("Expires: Tue, 01 Jul 2001 06:00:00 GMT");
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
  header("Cache-Control: no-store, no-cache, must-revalidate");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");
}

/*
http://icebeat.bitacoras.com/post/283/evitar-el-cache-de-los-css-y-js
Devuelve la url recibida mas el simbolo ? seguida de la fecha de modificacion del archivo
input	$url
output	$url?date
*/
function noFileCache($file) {
    return $file.'?'.filemtime($file);
}

/*
Fix al Problema generado en servidor
json_decode devuelve vacio puesto que la cookie esta mal codificada.... posiblemente por problemas del server hosting24
Devuelve matrices esperadas, no es necesario decoficar ni usar objetos
*/
function recuperarCookie($name) {
	if(isset($_COOKIE[$name])) {
		$val=$_COOKIE[$name];
		if($name!="secciones") {
			$val=str_replace(Array('\"',"[","]","{","}","\\","\\n","\\r","rn"), "", $val);
			$val=explode(",",$val);
			for($i=0; $i<count($val); $i++) {
				$veri=explode(":",$val[$i]);
				if(count($veri)>1) {
					$out[$veri[0]]=$veri[1];
				} else {
					$out[]=$val[$i];
				}
			}
		} else {
			$val=str_replace('\"', "", $val);
			$val=explode("],[",$val);
			for($i=0; $i<count($val); $i++) {
				$aux=null;
				$veri=str_replace(Array("[","]"), "", $val[$i]);
				$veri=explode(",",$veri);
				for($j=0; $j<count($veri); $j++) {
					$aux[]=$veri[$j];
				}
				$out[]=$aux;
			}
		}
		return $out;
	} else {
		return false;
	}
}

/*
Leer XML
Utiliza extension de PHP Simple XML
http://www.zarpele.com.ar/2011/04/php-leer-xml-con-simplexml/
*/
function LeerFeed($urlfeed="http://feeds.feedburner.com/Code09FIM?format=xml") {
	$i=0;
	$xml=simplexml_load_file($urlfeed);
	$feed='<ol>';
	foreach ($xml->channel->item as $noticia) {		
		$feed.='<li class="ite'.$i.'"><a href="'.$noticia->link.'">'.$noticia->title.'</a><br />'.substr($noticia->description,5,100).' ...</li>';
		$i++;
		if($i>=3) break;
	}
	$feed.='</ol>';
	return $feed;
}
?>