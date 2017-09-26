<?

include("../includes/db.php");
include("../includes/funciones.php");

if(!$_GET['id_tratamiento']){ exit("Error de ID");}

$id_tratamiento=escapar($_GET['id_tratamiento'],1);

$sql="SELECT * FROM tratamientos WHERE id_tratamiento=$id_tratamiento";
$query=mysql_query($sql);
$ft=mysql_fetch_assoc($query);
if($query){
	echo $ft['tratamiento']."|".$ft['costo'];
}else{
	echo "error";
}
?>