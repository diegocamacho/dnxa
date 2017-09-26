<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);
//Validamos datos completos
//if(!$id_paciente_agenda) exit("Seleccione un paciente.");
if(!$id_evento) exit("No llegó el identificador del evento.");
if(!$id_clinica) exit("Seleccione una clínica.");
if($id_doctor==0) exit("Seleccione el usuario/doctor.");
//if(!$id_tratamiento) exit("Seleccione un tratamiento.");
//if(!$id_promocion) exit("Seleccione un tratamiento.");
if(!$fecha_hora) exit("Debe especificar la fecha y hora de la cita.");
if(!$fecha_hora_final) exit("Debe especificar la fecha y hora en que va a terminar la cita.");
//if(!$comentarios) exit("Debe especificar la fecha y hora de la cita.");
if($comentarios) $comentarios=limpiaStr($comentarios,1,1);



	//Insertamos datos
	$sql="UPDATE citas SET id_clinica='$id_clinica', id_usuario='$id_doctor', fecha_hora='$fecha_hora', fecha_hora_final='$fecha_hora_final', comentario='$comentarios', color='$color' WHERE id_cita=$id_evento";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}
