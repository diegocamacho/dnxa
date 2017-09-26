<?

include("../includes/db.php");
include("../includes/funciones.php");

if(!$_GET['id_horario']){ exit("Error de ID");}

$id_horario=escapar($_GET['id_horario'],1);

$sql="SELECT * FROM clinicas_horarios WHERE id_horario=$id_horario";
$query=mysql_query($sql);
if($query){
	$data = mysql_fetch_object($query);
	echo json_encode($data);
}else{
	echo "error";
}
?>