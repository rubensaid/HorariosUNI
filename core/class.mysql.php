<?php
/*
Clase para conectar PHP MySQL
http://www.webtutoriales.com/tutoriales/programacion/php/clase-conectar-php-mysql.20.html
*/
class MySQL {
	private $conexion;
	private $total_consultas;
 
	public function MySQL() {
		if(!isset($this->conexion)) {
			$this->conexion = (mysql_connect("localhost","rubensai_horari","ght=45#")) or die(mysql_error());
			mysql_select_db("rubensai_code09fi_horarios",$this->conexion) or die(mysql_error());
		}
	}
	
	public function query($consulta){
		$this->total_consultas++;
		$resultado = mysql_query($consulta,$this->conexion);
		if(!$resultado){
			echo 'MySQL Error: '.mysql_error();
			exit;
		}
		return $resultado; 
	}
	
	public function fetch_array($consulta){ 
		return mysql_fetch_array($consulta);
	}
	
	public function num_rows($consulta){ 
		return mysql_num_rows($consulta);
	}
	
	public function close() {
		if ($this->conexion) {
			return mysql_close($this->conexion);
		}
	}
	
	public function getTotal() {
		return $this->total_consultas;
	}
}
?>
