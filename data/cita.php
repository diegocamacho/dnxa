<?

include("../includes/db.php");
include("../includes/funciones.php");

if(!$_GET['id_cita']){ exit("Error de ID");}

$id_cita=escapar($_GET['id_cita'],1);

$sql="SELECT pacientes.nombre, citas.* FROM citas 
JOIN pacientes ON pacientes.id_paciente=citas.id_paciente
WHERE id_cita=$id_cita";
$query=mysql_query($sql);
$ft=mysql_fetch_assoc($query);
if($query){
	echo $ft['nombre']."|".$ft['id_clinica']."|".$ft['id_tratamiento']."|".$ft['id_promocion']."|".fechaHoraMeridian($ft['fecha_hora'])."|".$ft['fecha_hora']."|".fechaHoraMeridian($ft['fecha_hora_final'])."|".$ft['fecha_hora_final']."|".$ft['color']."|".$ft['id_usuario']."|".$ft['comentario'].'|'.$ft['tiene_blanqueamiento'].'|'.$ft['id_especialista_lab'];;
}else{
	echo "error";
}
?>