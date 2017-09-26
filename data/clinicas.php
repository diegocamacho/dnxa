<?

include("../includes/db.php");
include("../includes/funciones.php");

if(!$_GET['id_clinica']){ exit("Error de ID");}

$id_clinica=escapar($_GET['id_clinica'],1);

$sql="SELECT * FROM clinicas WHERE id_clinica=$id_clinica";
$query=mysql_query($sql);
if($query){
	$data = mysql_fetch_object($query);
	echo json_encode($data);
}else{
	echo "error";
}
?>