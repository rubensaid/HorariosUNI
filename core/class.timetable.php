<?php
/*
TimeTable 1.0 by RubensaiD
Clase  para el Horario de Clases
Code 09 FIM
http://www.code09fim.com
*/
class TimeTable {
	private $tt;
	private $days;
	private $hrcruce;
	private $hrcruced;
	private $inform;
	
	public function TimeTable($horas=0) {
		if(!isset($this->tt)) {
			for($i=0; $i<14; $i++) {
				for($j=0; $j<6; $j++) {
					$this->tt[$i][$j]=0;
				}
			}
			$this->days=Array('Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'); //Days 0:Lunes and so on...
			$this->hrcruce=$horas;
			$this->hrcruced=0;
			$this->inform=$this->tt;
		}
	}
	
	public function AddCourse($day, $start, $end, $hid) {
		if($this->IsAvailable($day, $start, $end)) {
			for($i=$start-8; $i<=$end-9; $i++) {
				if($this->tt[$i][$day]!=0) {
					$this->hrcruce--;
					$this->inform[$i][$day]=$this->tt[$i][$day];
					$this->hrcruced++;
				}
				$this->tt[$i][$day]=$hid;
			}				
			return true;
		} else {
			return false;
		}
	}
	
	public function RemoveCourse($hid) {
		for($i=0; $i<14; $i++) {
			for($j=0; $j<6; $j++) {
				if($this->tt[$i][$j]==$hid) {
					$this->tt[$i][$j]=$this->inform[$i][$j];
					if($this->inform[$i][$j]!=0) {
						$this->inform[$i][$j]=0;
						$this->hrcruce++;
						$this->hrcruced--;
					}
				}
			}
		}		
		return true;
	}
	
	public function IsAvailable($day, $start, $end) {
		if($this->hrcruce==0) {$horas=0;} else {$horas=$this->hrcruce+2;}
		for($i=$start-8; $i<=$end-9; $i++) {
			if($this->tt[$i][$day]!=0 and $horas<=0) {
				return false;
				break;
			} else {
				$horas--; //echo "Dia:".$day.".".$start.".".$end." resta a ".$horas."<br />";
			}
		}
		return true;
	}
	
	public function getTT() {		
		return $this->tt;		
	}
	
	public function getInform() {		
		return $this->inform;		
	}
	
	public function getCruzadas() {		
		return $this->hrcruced;
	}
}
?>