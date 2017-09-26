<?php
include("../includes/db.php");
include("../includes/funciones.php");

if(!$_GET['id_cita']) exit("Error de ID");

//CHECAMOS SI ES UN EVENTO
$id_cita = $_GET['id_cita'];
if($id_cita[0] == 'E'){
	//AQUI VA TODO EL DESMADRE DE CREAR EL DATA DEL EVENTO
	$id = explode("|",$id_cita);
	$id = $id[1];

	$fecha_click = explode("T",$_GET['fecha_click']);
	$fecha_click = $fecha_click[0];

	
	$sql = "SELECT eventos.*,clinicas.clinica,usuarios.nombre as nombre_dr FROM eventos 
	LEFT JOIN clinicas ON clinicas.id_clinica = eventos.id_clinica
	LEFT JOIN usuarios ON usuarios.id_usuario = eventos.id_usuario 
	WHERE id_evento = $id";
	$q = mysql_query($sql);
	$datos = mysql_fetch_assoc($q);
	
	//file_put_contents("xd.txt","SELECT * FROM eventos_excepciones WHERE id_evento=$id AND '$fecha_click' BETWEEN DATE(fecha_hora) AND DATE(fecha_hora_final)");
	$data = array("id_cita" => $id_cita, "fecha_hora" => $datos['fecha1']." ".$datos['hora1'], "tipo" => 2, "fecha_hora_final" => $datos['fecha2']." ".$datos['hora2'], "clinica" => $datos['clinica'], "nombre_dr" => $datos['nombre_dr'], "comentario" => $datos['descripcion']);
	
	$sqN="SELECT * FROM eventos_excepciones WHERE id_evento=$id AND '$fecha_click' BETWEEN DATE(fecha_hora) AND DATE(fecha_hora_final) ORDER BY id_excepcion DESC LIMIT 1";
	$qN=mysql_query($sqN);
	$val=mysql_num_rows($qN);
	if($val):
		$dt=mysql_fetch_assoc($qN);
		
		$f1=$dt['fecha_hora'];
		$f2=$dt['fecha_hora_final'];
		
		$data['fecha_hora'] 		= fechaHoraMeridian($f1);
		$data['fecha_hora_final'] 	= fechaHoraMeridian($f2);
	else:
		$data['fecha_hora'] 		= fechaHoraMeridian($data['fecha_hora']);
		$data['fecha_hora_final'] 	= fechaHoraMeridian($data['fecha_hora_final']);
	endif;
	
	
}else{
	$id_cita = escapar($id_cita,1);
	
	$sql = "
	SELECT citas.id_cita,
	pacientes.nombre,
	pacientes.telefono,
	citas.fecha_hora,
	citas.comentario,
	citas.confirmada,
	citas.atendida,
	citas.tipo,
	citas.fecha_hora_final,
	promociones.promocion,
	clinicas.clinica,
	tratamientos.tratamiento,
	usuarios.nombre as nombre_dr,
	especialistas_lab.nombre AS especialista
	FROM citas
	LEFT JOIN pacientes ON pacientes.id_paciente = citas.id_paciente
	LEFT JOIN clinicas ON clinicas.id_clinica = citas.id_clinica
	LEFT JOIN tratamientos ON tratamientos.id_tratamiento  = citas.id_tratamiento
	LEFT JOIN usuarios ON usuarios.id_usuario  = citas.id_usuario 
	LEFT JOIN promociones ON promociones.id_promocion  = citas.id_promocion 
	LEFT JOIN especialistas_lab ON especialistas_lab.id_especialista_lab  = citas.id_especialista_lab 
	WHERE citas.activo = 1 AND citas.id_cita = '$id_cita'";
	$q = mysql_query($sql);
	#$data = mysql_fetch_array($q);
	$data = mysql_fetch_assoc($q);
	#$data = array_map('utf8_decode', $data);
	$data['fecha_hora'] 		= fechaHoraMeridian($data['fecha_hora']);
	$data['fecha_hora_final'] 	= fechaHoraMeridian($data['fecha_hora_final']);
	
	//AQUI VAMOS A AGREGAR EL AGENDADO
	$check_blanquemiento = mysql_fetch_assoc(mysql_query("SELECT id_blanqueamiento,tiene_blanqueamiento FROM citas WHERE id_cita = '$id_cita'"));
	if($check_blanquemiento['id_blanqueamiento']>0){
		$n_id = $check_blanquemiento['id_blanqueamiento'];
		$clinica = mysql_fetch_assoc(mysql_query("SELECT id_clinica FROM citas WHERE id_cita='$n_id'"));
		$clinica = dameClinica($clinica['id_clinica']);
		$data['agendado'] = $clinica;
	}else{
		$data['agendado'] = 0;
	}
	
}


echo json_encode($data);
