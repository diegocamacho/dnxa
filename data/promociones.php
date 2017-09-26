<?

include("../includes/db.php");
include("../includes/funciones.php");

if(!$_GET['id_promocion']){ exit("Error de ID");}

$id_promocion=escapar($_GET['id_promocion'],1);

$sql="SELECT * FROM promociones WHERE id_promocion=$id_promocion";
$query=mysql_query($sql);
$ft=mysql_fetch_assoc($query);
if($query){
	echo $ft['promocion']."|".$ft['descripcion'];
}else{
	echo "error";
}
?>