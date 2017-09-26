<?

include("../includes/db.php");
include("../includes/funciones.php");

if(!$_GET['id']){ exit("Error de ID");}

$id=escapar($_GET['id'],1);

$sql="SELECT cuenta_gasto FROM books_tipos_gasto WHERE id_tipo_gasto=$id";
$query=mysql_query($sql);
$ft=mysql_fetch_assoc($query);
if($query){
	echo $ft['cuenta_gasto'];
}else{
	echo "error";
}
?>