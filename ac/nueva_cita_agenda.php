<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);
//print_r($_POST);
//exit();
//Validamos datos completos
if(!$id_paciente) exit("Seleccione un paciente.");
if(!$id_clinica) exit("Seleccione una clínica.");
if(!$id_tratamiento) exit("Seleccione un tratamiento.");
//if(!$id_promocion) exit("Seleccione un tratamiento.");
if(!$fecha1) exit("Seleccione la fecha de la cita");
if(!$hora1) exit("Seleccione la hora inicial de la cita");
if(!$fecha2) exit("Seleccione la fecha de la cita");
if(!$hora2) exit("Seleccione la hora final de la cita");
//if(!$fecha_hora) exit("Debe especificar la fecha y hora de la cita.");
//if(!$fecha_hora_final) exit("Debe especificar la fecha y hora en que va a terminar la cita.");
if(!$color) exit("Seleccione un color.");
if($comentarios) $comentarios=limpiaStr($comentarios,1,1);
if(strtotime($hora1) > strtotime($hora2) || strtotime($hora2) < strtotime($hora1) || strtotime($hora1) == strtotime($hora2)) exit("¡Debe poner horarios válidos! Intente nuevamente por favor.");
if($fecha1 < $fecha_actual || $fecha2 < $fecha_actual) exit("No es posible agendar una cita en un día anterior al día de hoy");
if($fecha1 > $fecha2) exit('Corrobore por favor las fechas de la consulta.');
$fecha_hora = $fecha1." ".$hora1;
$fecha_hora_final = $fecha2." ".$hora2;
//blanqueamientos
//Agregar sql begin
if($blanqueamientos):
	$blanqueamientos=1;
endif;

mysql_query('BEGIN');

$sq=@mysql_query("INSERT INTO citas (id_paciente,id_clinica,id_tratamiento,id_promocion,id_usuario,fecha_hora,fecha_hora_final,fecha_hora_creacion,comentario,color,id_usuario_agendo,tiene_blanqueamiento) VALUES ('$id_paciente','$id_clinica','$id_tratamiento','$id_promocion','$id_doctor','$fecha_hora','$fecha_hora_final','$fechahora','$comentarios','$color','$s_id_usuario','$blanqueamientos')");
if(!$sq) $error = true;
$id_cita_nuevo=mysql_insert_id();


$sq=@mysql_query("UPDATE pacientes SET tipo='1' WHERE id_paciente=$id_paciente");
if(!$sq) $error = true;

//Duplicamos la cita
if($blanqueamientos==1):
	$sq=@mysql_query("INSERT INTO citas (id_paciente,id_clinica,id_tratamiento,id_promocion,id_usuario,fecha_hora,fecha_hora_final,comentario,color,id_usuario_agendo,id_blanqueamiento) VALUES ('$id_paciente',11,'$id_tratamiento','$id_promocion','$id_doctor','$fecha_hora','$fecha_hora_final','$comentarios','$color','$s_id_usuario','$id_cita_nuevo')");
	if(!$sq) $error = true;
endif;
	
	
if($error):
    mysql_query('ROLLBACK');
    echo "Ocurrió un error, intente más tarde.";
else:
    mysql_query('COMMIT');
    echo "1";
endif;
