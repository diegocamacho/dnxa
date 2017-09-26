<?

include("../includes/db.php");
include("../includes/funciones.php");

if(!$_GET['id']){ exit("Error de ID");}

$id=escapar($_GET['id'],1);

$sql="SELECT empresa FROM books_empresas WHERE id_empresa=$id";
$query=mysql_query($sql);
$ft=mysql_fetch_assoc($query);
if($query){
	echo $ft['empresa'];
}else{
	echo "error";
}
?>