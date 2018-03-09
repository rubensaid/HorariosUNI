<? 
require_once("../core/class.mysql.php");
require_once("../core/class.timetable.php");
require_once("../core/function.tools.php");
//noCache();

$db = new MySQL();
//Curso
$materiales=$db->query("SELECT cid, code FROM cursos WHERE fid='".$_GET['fid']."' and code LIKE '%".utf8_decode($_GET['code'])."%' LIMIT 5");
if(isset($_REQUEST['json'])) {
	header("Content-Type: application/json" );
	echo "{\"results\": [";
	$arr = array();
	while ($row=$db->fetch_array($materiales)) {
		$arr[] = "{\"id\": \"".$row['cid']."\", \"value\": \"".$row['code']."\", \"info\": \"\"}";
	}
	echo implode(", ", $arr);
	echo "]}";
} else {
	header("Content-Type: text/xml");
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?><results>";
	while ($row=$db->fetch_array($materiales))	{
		echo "<rs id=\"".$row['cid']."\" info=\"\">".$row['code']."</rs>";
	}
	echo "</results>";
}

$db->close();
?>