<?php

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
$sql="SELECT citas.id_cita, pacientes.activo, pacientes.nombre,citas.color,citas.fecha_hora,citas.fecha_hora_final,citas.confirmada,citas.tipo,atendida,usuarios.nombre AS doctor FROM citas 
LEFT JOIN pacientes ON pacientes.id_paciente=citas.id_paciente
LEFT JOIN clinicas ON clinicas.id_clinica=citas.id_clinica
LEFT JOIN usuarios ON usuarios.id_usuario=citas.id_usuario
WHERE citas.activo = 1 AND citas.atendida = 0 AND DATE(fecha_hora) BETWEEN '$first' AND '$last' AND citas.id_clinica $sc ORDER BY id_evento ASC LIMIT 70000";

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

echo json_encode($eventos);
