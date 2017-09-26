<?php
date_default_timezone_set('America/Mexico_City');
include('includes/db.php');
$id_clinica = $_GET['id_clinica'];
$sc = ($id_clinica) ? "= $id_clinica" : ">0";

//Para agilizarlo
if(isset($_GET['start'])){
	$first = $_GET['start'];
	$last = $_GET['end'];
}else{
	$month = date('m');
	$first = date("Y-".$month."-d", strtotime("first day of this month"));
	$last= date(date('t', strtotime($first)) .'-' . $month . '-Y');
	$last = date("Y-m-d",strtotime($last));
}
	

//
$sql="SELECT citas.id_cita,citas.id_usuario_agendo, pacientes.activo, pacientes.nombre,citas.color,citas.fecha_hora,citas.fecha_hora_final,citas.confirmada,citas.tipo,atendida,usuarios.nombre AS doctor FROM citas 
LEFT JOIN pacientes ON pacientes.id_paciente=citas.id_paciente
LEFT JOIN clinicas ON clinicas.id_clinica=citas.id_clinica
LEFT JOIN usuarios ON usuarios.id_usuario=citas.id_usuario
WHERE citas.activo = 1 AND citas.atendida = 0 AND citas.tipo = 1 AND DATE(fecha_hora) BETWEEN '$first' AND '$last' AND citas.id_clinica $sc ORDER BY id_evento ASC LIMIT 70000";

$q=mysql_query($sql);

$citas		= array();
$e			= array();
$eventos	= array();

while($datos=mysql_fetch_object($q)):
	$citas[] = $datos;
endwhile;

foreach($citas as $cita):
	
	if($cita->tipo==1):
		if($cita->activo==0): continue; endif;
	endif;
	
	#if($cita->atendida==1) continue;
	
	$hora = explode(' ', $cita->fecha_hora);
	$fecha = $hora[0];	
	$hora = explode(':', $hora[1]);
	$hora = $hora[0].':'.$hora[1];
	
#	$conf = ($cita->confirmada) ? '✓' : '';
#	$conf = ($cita->atendida) ? '✓✓' : '';
	
	if($cita->atendida):
		$conf = '✓✓';
	else:
		if($cita->confirmada):
			$conf = '✓';
		else:
			$conf = '';
		endif;
	endif;
	
	if($cita->id_usuario_agendo == 17){
		$conf = '@ ';
	}

	$tipo = $cita->tipo;
	$e['id'] = $cita->id_cita;
	if($tipo==1):
		$e['title'] = $conf.$hora.' '.$cita->nombre;
	else:
		$e['title'] = 'EVENTO '.$hora.' '.$cita->doctor;
	endif;
	$e['start'] = str_replace(' ', 'T', $cita->fecha_hora);
	$e['end'] = str_replace(' ', 'T', $cita->fecha_hora_final);
	$e['allDay'] = false;
	$e['backgroundColor'] = $cita->color;
	array_push($eventos, $e);


endforeach;

//AQUI SE GENERARAN LOS EVENTOS
function DiaSemana($dia) {
	switch($dia){
		case 1:
			$dia_semana = "lunes";
		break;
		case 2:
			$dia_semana = "martes";
		break;
		case 3:
			$dia_semana = "miercoles";
		break;
		case 4:
			$dia_semana = "jueves";
		break;
		case 5:
			$dia_semana = "viernes";
		break;
		case 6:
			$dia_semana = "sabado";
		break;
		case 7:
			$dia_semana = "domingo";
		break;
	}
	return $dia_semana;
}

//ESTE CICLO VA A IR AGREGANDO FECHA POR FECHA HASTA QUE NOS ACABEMOS LOS DIAS 
    $dias = array();
	$begin = new DateTime($first);
	$end = new DateTime($last);
	$interval = new DateInterval('P1D');
	$period = new DatePeriod($begin, $interval, $end);

	foreach ( $period as $dt ) {
		array_push($dias, $dt->format('Y-m-d'));
	}
	array_push($dias, $end->format('Y-m-d')); //HAY QUE AGREGAR EL ULTIMO DIA
	
//COLOR 1 : #F7CA18
//COLOR 2 : #ACB5C3
//POR CADA UNO DE LOS DIAS HAY QUE CHECAR PRIMERO QUE DIA ES EL DIA A CONSULTAR PARA PODER HACER LA CONSULTA MÁS RAPIDO
for($x = 0 ; $x < count($dias) ; $x++){
	$dia_actual = $dias[$x];
	$dia_semana = date("N",strtotime($dia_actual));
	$dia_semana = DiaSemana($dia_semana);
	$q_dia = $dia_semana." = 'on'";
	
	//AHORA HACEMOS LA CONSULTA Y AGREGAMOS AL JSON SEGUN CORRESPONDA
	$q_eventos = mysql_query("SELECT eventos.*, usuarios.nombre AS doctor FROM eventos 
LEFT JOIN clinicas ON clinicas.id_clinica=eventos.id_clinica
LEFT JOIN usuarios ON usuarios.id_usuario=eventos.id_usuario
WHERE '$dia_actual' BETWEEN fecha1 AND fecha2 AND $q_dia AND eventos.id_clinica $sc");
	$check = mysql_num_rows($q_eventos);
	if($check){
		while($n_evento = mysql_fetch_assoc($q_eventos)){
			//AHORA HAY QUE CHECAR LAS EXCEPCIONES, SI EXISTE SE CONSTRUYE EL JSON CON ESA EXCEPCION Y NO CON EL EVENTO QUE CORRESPONDE
			//file_put_contents($dia_actual.".txt", "SELECT * FROM eventos_excepciones WHERE id_evento = $id_evento AND '$dia_actual' BETWEEN DATE(fecha_hora) AND DATE(fecha_hora_final)");
			$id_evento = $n_evento['id_evento'];
			$excepciones = mysql_query("SELECT * FROM eventos_excepciones WHERE id_evento = $id_evento AND '$dia_actual' BETWEEN DATE(fecha_hora) AND DATE(fecha_hora_final) ORDER BY id_excepcion DESC LIMIT 1");
			$hay_excep = mysql_num_rows($excepciones);
			if($hay_excep){
				$excepcion = mysql_fetch_assoc($excepciones);
				$hora1 = substr($excepcion['fecha_hora'],11);
				$hora2 = substr($excepcion['fecha_hora_final'],11);
				$e['id'] = "E|".$n_evento['id_evento'];
				$e['title'] = 'EVENTO '.$hora1.' '.$n_evento['doctor'];	
				$e['start'] = $dia_actual."T".$hora1;
				$e['end'] = $dia_actual."T".$hora2;
				$e['allDay'] = false;
				$e['backgroundColor'] = $n_evento['color'];
			}else{
				$e['id'] = "E|".$n_evento['id_evento'];
				$e['title'] = 'EVENTO '.$n_evento['hora1'].' '.$n_evento['doctor'];	
				$e['start'] = $dia_actual."T".$n_evento['hora1'];
				$e['end'] = $dia_actual."T".$n_evento['hora2'];
				$e['allDay'] = false;
				$e['backgroundColor'] = $n_evento['color'];
			}
			
			array_push($eventos, $e);	
		}
	}
}


echo json_encode($eventos);
