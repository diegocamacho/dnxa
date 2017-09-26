<?

include("../includes/db.php");
include("../includes/funciones.php");

if(!$_GET['id_paciente']){ exit("Error de ID");}

$id_paciente=escapar($_GET['id_paciente'],1);

$sql="SELECT * FROM pacientes WHERE id_paciente=$id_paciente";
$query=mysql_query($sql);
$ft=mysql_fetch_assoc($query);
if($query){
	echo $ft['id_canal']."|".$ft['nombre']."|".$ft['telefono']."|".$ft['email']."|".$ft['id_cliente'];
}else{
	echo "error";
}
?>