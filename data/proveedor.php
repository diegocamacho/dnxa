<?

include("../includes/db.php");
include("../includes/funciones.php");

if(!$_GET['id']){ exit("Error de ID");}

$id=escapar($_GET['id'],1);

$sql="SELECT * FROM books_proveedores WHERE id_proveedor=$id";
$query=mysql_query($sql);
$ft=mysql_fetch_assoc($query);
if($query){
	echo $ft['proveedor']."|".$ft['telefono']."|".$ft['email']."|".$ft['id_clinica'];
}else{
	echo "error";
}
?>