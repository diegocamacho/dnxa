<?

include("../includes/db.php");
include("../includes/funciones.php");

if(!$_GET['id']){ exit("Error de ID");}

$id=escapar($_GET['id'],1);

$sql="SELECT * FROM books_clientes WHERE id_cliente=$id";
$query=mysql_query($sql);
$ft=mysql_fetch_assoc($query);
if($query){
	echo $ft['cliente']."|".$ft['telefono']."|".$ft['email']."|".$ft['id_empresa']."|".$ft['id_plan']."|".$ft['fecha_inicio_plan']."|".$ft['fecha_final_plan'];
}else{
	echo "error";
}
?>