<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);
//print_r($_POST);
//Validamos datos completos
//if(!$id_paciente_agenda) exit("Seleccione un paciente.");
if(!$id_evento) exit("No llego el identificador del evento para la excepción");
if(!$fecha1) exit("Debe especificar la fecha inicial de la excepción.");
if(!$fecha2) exit("Debe especificar la fecha final de la excepción.");
if(!$hora1) exit("Debe especificar la hora inicial de la excepción.");
if(!$hora2) exit("Debe especificar la hora final de la excepción.");
if(!$comentarios) exit("Debe especificar comentarios.");
if($comentarios) $comentarios=limpiaStr($comentarios,1,1);

$fecha_hora = $fecha1." ".$hora1;
$fecha_hora_final = $fecha2." ".$hora2;

	//Insertamos datos
	$sql="INSERT INTO eventos_excepciones (id_evento,fecha_hora,fecha_hora_final,comentarios,id_usuario,fecha_creado) VALUES ('$id_evento','$fecha_hora','$fecha_hora_final','$comentarios','$s_id_usuario','$fechahora')";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}
