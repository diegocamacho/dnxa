<?

include("../includes/db.php");
include("../includes/funciones.php");

if(!$_GET['id']){ exit("Error de ID");}

$id=escapar($_GET['id'],1);

$sql="SELECT cuenta_ingreso FROM books_tipos_ingreso WHERE id_tipo_ingreso=$id";
$query=mysql_query($sql);
$ft=mysql_fetch_assoc($query);
if($query){
	echo $ft['cuenta_ingreso'];
}else{
	echo "error";
}
?>