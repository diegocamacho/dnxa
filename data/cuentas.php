<?

include("../includes/db.php");
include("../includes/funciones.php");

if(!$_GET['id']){ exit("Error de ID");}

$id=escapar($_GET['id'],1);

$sql="SELECT * FROM books_cuentas WHERE id_cuenta=$id";
$query=mysql_query($sql);
$ft=mysql_fetch_assoc($query);
if($query){
	echo $ft['alias']."|".$ft['id_empresa']."|".$ft['tipo_cuenta']."|".$ft['activo'];
}else{
	echo "error";
}
?>