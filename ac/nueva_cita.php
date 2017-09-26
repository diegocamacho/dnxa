<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);
//print_r($_POST);
//exit();
//Validamos datos completos
if(!$id_paciente_agenda) exit("Seleccione un paciente.");
if(!$id_clinica) exit("Seleccione una clínica.");
if(!$id_tratamiento) exit("Seleccione un tratamiento.");
//if(!$id_promocion) exit("Seleccione un tratamiento.");
if(!$fecha_hora) exit("Debe especificar la fecha y hora de la cita.");
if(!$fecha_hora_final) exit("Debe especificar la fecha y hora en que va a terminar la cita.");
if(!$color) exit("Seleccione un color.");
if($comentarios) $comentarios=limpiaStr($comentarios,1,1);
//Validamos que seleccione o un doctor o un especialista
if(!$id_doctor):
    if($id_especialista==0) exit("Debe seleccionar un especialista o un doctor para la cita.");
endif;
//blanqueamientos
//Agregar sql begin
if($blanqueamientos):
	$blanqueamientos=1;
endif;

mysql_query('BEGIN');

$sq=@mysql_query("INSERT INTO citas (id_paciente,id_clinica,id_tratamiento,id_promocion,id_usuario,fecha_hora,fecha_hora_final,fecha_hora_creacion,comentario,color,id_usuario_agendo,tiene_blanqueamiento,id_especialista_lab,id_doctor) VALUES ('$id_paciente_agenda','$id_clinica','$id_tratamiento','$id_promocion','0','$fecha_hora','$fecha_hora_final','$fechahora','$comentarios','$color','$s_id_usuario','$blanqueamientos','$id_especialista','$id_doctor')");
if(!$sq) $error = true;
$id_cita_nuevo=mysql_insert_id();


$sq=@mysql_query("UPDATE pacientes SET tipo='1' WHERE id_paciente=$id_paciente_agenda");
if(!$sq) $error = true;

//Duplicamos la cita
if($blanqueamientos==1):
	$sq=@mysql_query("INSERT INTO citas (id_paciente,id_clinica,id_tratamiento,id_promocion,id_usuario,fecha_hora,fecha_hora_final,comentario,color,id_usuario_agendo,id_blanqueamiento,id_doctor) VALUES ('$id_paciente_agenda',11,'$id_tratamiento','$id_promocion','0','$fecha_hora','$fecha_hora_final','$comentarios','$color','$s_id_usuario','$id_cita_nuevo','$id_doctor')");
	if(!$sq) $error = true;
endif;
	
	
if($error):
    mysql_query('ROLLBACK');
    echo "Ocurrió un error, intente más tarde.";
else:
    mysql_query('COMMIT');
    echo "1";
endif;
