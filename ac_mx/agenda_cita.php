<?
include("../includes/db.php");
include("../includes/funciones.php");

extract($_GET);

if(!$telefono):
	$data->estado = "2";
	$data->msg = "Ingrese su número de teléfono.";
	echo json_encode($data);
	exit();
endif;

if(!$nombre):
	$data->estado = "2";
	$data->msg = "Ingrese su nombre. 2";
	echo json_encode($data);
	exit();
endif;

if($id_clinica==0):
	$data->estado = "2";
	$data->msg = "Seleccione una clínica para su consulta.";
	echo json_encode($data);
	exit();
endif;

if(!$fecha):
	$data->estado = "2";
	$data->msg = "Seleccione una fecha para su consulta.";
	echo json_encode($data);
	exit();
endif;

if($id_clinica==0):
	$data->estado = "2";
	$data->msg = "Seleccione una hora para su consulta.";
	echo json_encode($data);
	exit();
endif;

if($tratamiento==0):
	$data->estado = "2";
	$data->msg = "Seleccione un tratamiento para su consulta.";
	echo json_encode($data);
	exit();
endif;

$telefono=limpiaStr($telefono,1,1);
$nombre=limpiaStr($nombre,1,1);
$fecha_hora_usuario=fechaBase2($fecha)." ".$hora;
if($tratamiento == 1){
	$hora2 = date('H:i',strtotime($hora.'+ 30 minutes'));
}else{
	$hora2 = date('H:i',strtotime($hora.'+ 1 hour'));
}
$fecha_hora_usuario_final=fechaBase2($fecha)." ".$hora2;

//$id_tratamiento=1;
$id_promocion=0;
$id_doctor=0;
$fecha_hora=$fecha_hora_usuario;
$fecha_hora_final=$fecha_hora_usuario_final;
$comentarios="Consulta agendada desde la página Web";
$color="#FF6600";
$s_id_usuario=2;

$comentarios2="Paciente agregado desde la página Web";

//Sacamos cuantas citas soporta la Clínica
$sq="SELECT capacidad_citas, clinica, direccion FROM clinicas WHERE id_clinica=$id_clinica";
$q=mysql_query($sq);
$ft=mysql_fetch_assoc($q);
$capacidad=$ft['capacidad_citas'];
$clinica=$ft['clinica'];
$direccion=$ft['direccion'];

//Sacamos cuantas citas agendadad tiene la Clínica
$sq="SELECT id_cita FROM citas WHERE id_clinica=$id_clinica AND activo=1 AND tipo=1 AND fecha_hora='$fecha_hora_usuario'";
$q=mysql_query($sq);
$citas_agendadas=mysql_num_rows($q);

//CHECKUP DE LOS EVENTOS ANTES
$fecha = fechaBase2($fecha);
$dia_semana = DiaSemana(date('N',strtotime($fecha)));
$sql = "SELECT id_evento FROM eventos WHERE id_clinica='$id_clinica' AND $dia_semana = 'on' AND '$fecha' BETWEEN fecha1 AND fecha2 AND ('$hora' BETWEEN hora1 AND hora2 OR hora1='$hora')";
$eventos = mysql_num_rows(mysql_query($sql));
if($eventos > 0){
	$data->estado = "2";
	$data->msg = $nombre." lamentablemente no tenemos citas disponibles para la hora que solicitaste ($hora), Podrías intentar otra hora.";
	echo json_encode($data);
}

//Verificamos la disponibilidad de la Clínica
if($citas_agendadas>=$capacidad):
	$data->estado = "2";
	$data->msg = $nombre." lamentablemente ya no tenemos citas disponibles para la hora que solicitaste ($hora), Podrías intentar otra hora.";
	echo json_encode($data);
else:
	$sql="SELECT id_paciente,nombre FROM pacientes WHERE telefono='$telefono' LIMIT 1";
	$q=mysql_query($sql);
	$valida=mysql_num_rows($q);
	if($valida==1):
		//Sacamos los datos del paciente
		$dat=mysql_fetch_assoc($q);
		$id_paciente=$dat['id_paciente'];
		$nombre=$dat['nombre'];	
	else:
		//insertamos el paciente
		$sql="INSERT INTO pacientes (id_canal,fecha_registro,nombre,telefono,email,ultima_com,prox_com,comentarios,tipo) VALUES ('6','$fecha_actual','$nombre','$telefono','$email','$fecha_actual','$fecha_actual','$comentarios2','1')";
		$q=mysql_query($sql);
		$id_paciente=mysql_insert_id();
		
	endif;
	
	$sq="INSERT INTO citas (id_paciente,id_clinica,id_tratamiento,id_promocion,id_usuario,fecha_hora,fecha_hora_final,fecha_hora_creacion,comentario,color,id_usuario_agendo,tiene_blanqueamiento) VALUES ('$id_paciente','$id_clinica','$tratamiento','$id_promocion','$id_doctor','$fecha_hora','$fecha_hora_final','$fechahora','$comentarios','$color','$s_id_usuario','$blanqueamientos')";
	$q=mysql_query($sq);
	if($q):
		$data->estado = "1";
		$data->msg = "Felicidades ".$nombre." tu cita se ha agendado, te esperamos este ".fechaHoraMeridian($fecha_hora)." en ".$clinica." ubicada en ".$direccion.", te recomendamos llegar 15 minutos antes a tu cita";
		echo json_encode($data);
	else:
		$data->estado = "2";
		$data->msg = "Ocurrió un error, intente más tarde.";
		echo json_encode($data);
	endif;
	
endif;