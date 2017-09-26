<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
//if(!$id_paciente_agenda) exit("Seleccione un paciente.");
if(!$id_clinica) exit("Seleccione una clínica.");
if(!$id_doctor) exit("Seleccione el usuario/doctor.");
//if(!$id_tratamiento) exit("Seleccione un tratamiento.");
//if(!$id_promocion) exit("Seleccione un tratamiento.");
if(!$fecha_hora) exit("Debe especificar la fecha y hora de la cita.");
if(!$fecha_hora_final) exit("Debe especificar la fecha y hora en que va a terminar la cita.");
//if(!$comentarios) exit("Debe especificar la fecha y hora de la cita.");
if($comentarios) $comentarios=limpiaStr($comentarios,1,1);



	//Insertamos datos
	$sql="INSERT INTO citas (id_paciente,id_clinica,id_usuario,fecha_hora,fecha_hora_final,comentario,color,id_usuario_agendo,tipo) VALUES ('0','$id_clinica','$id_doctor','$fecha_hora','$fecha_hora_final','$comentarios','$color','$s_id_usuario','2')";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}
