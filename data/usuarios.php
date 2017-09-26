<?

include("../includes/db.php");
include("../includes/funciones.php");

if(!$_GET['id_usuario']){ exit("Error de ID");}

$id_usuario=escapar($_GET['id_usuario'],1);

$sql="SELECT * FROM usuarios WHERE id_usuario=$id_usuario";
$query=mysql_query($sql);
$ft=mysql_fetch_assoc($query);
if($query){
	$datos=explode(" ", $ft['nombre']);
	$nombre=$datos[0];
	$apellido=$datos[1];
	echo $ft['id_tipo_usuario']."|".$nombre."|".$apellido."|".$ft['email']."|".$ft['celular']."|".$ft['foto']."|".$ft['id_clinica'];
}else{
	echo "error";
}
?>