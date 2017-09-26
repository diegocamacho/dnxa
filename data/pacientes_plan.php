<?php
include("../includes/db.php");
include("../includes/funciones.php");

if(!$_GET['id_paciente']) exit("Error de ID");

$id = escapar($_GET['id_paciente'],1);

$sql = "
SELECT 	
pacientes.nombre,
books_clientes.cliente,
planes.plan,
planes.id_plan,
books_clientes.fecha_inicio_plan,
books_clientes.fecha_final_plan,
planes.observacion
FROM pacientes
JOIN books_clientes ON books_clientes.id_cliente = pacientes.id_cliente
JOIN planes ON books_clientes.id_plan = planes.id_plan
WHERE id_paciente = $id";

$q = mysql_query($sql);
$data = mysql_fetch_object($q);

$data->observacion = str_replace("\n", "</br>", $data->observacion);

$data->vigencia = fechaLetra($data->fecha_inicio_plan).' - '.fechaLetra($data->fecha_final_plan);


/*****/


$id_plan = $data->id_plan;
$id_paciente = $id;
$plan_inicia = $data->fecha_inicio_plan;
$plan_expira = $data->fecha_final_plan;

$sql = "SELECT id_tratamiento, cantidad FROM planes_tratamientos WHERE id_plan = $id_plan";
$q = mysql_query($sql);

while($consumido = mysql_fetch_assoc($q)):
		$tratamientos_incluidos[$consumido['id_tratamiento']] = $consumido['cantidad'];
	$tratamientos_dentro.= getNT($consumido['id_tratamiento']).': <b>'.$consumido['cantidad'].'</b></br>';
endwhile;


$sql = "
SELECT 	consultas_tratamientos.id_tratamiento,
		consultas_tratamientos.cantidad,
		consultas_tratamientos.precio,
		consultas.fecha_hora
		FROM consultas_tratamientos
		JOIN consultas ON consultas.id_consulta = consultas_tratamientos.id_consulta
		WHERE consultas.id_paciente = $id_paciente
		AND DATE(consultas.fecha_hora) BETWEEN '$plan_inicia' AND '$plan_expira'";

$q = mysql_query($sql);

while($consumido = mysql_fetch_assoc($q)):

	if(isset($tratamientos_incluidos[$consumido['id_tratamiento']])):

		if($consumido['precio']==0):
			$tratamientos_incluidos[$consumido['id_tratamiento']]=$tratamientos_incluidos[$consumido['id_tratamiento']]-$consumido['cantidad'];
		endif;

	endif;
endwhile;

foreach($tratamientos_incluidos as $id => $cantidad):

	unset($style);
	if($cantidad==0):
		$style = "color:red";
	endif;

$tratamientos_restantes.= getNT($id).': <b style="'.$style.'">'.$cantidad.'</b></br>';

endforeach;

/*
$tratamientos_restantes.= getNT($consumido['id_tratamiento']).': '.$tratamientos_incluidos[$consumido['id_tratamiento']]-$consumido['cantidad'].'</br>';
*/

if(!$tratamientos_restantes){
	$tratamientos_restantes = $tratamientos_dentro;
}

$data->tratamientos_incluidos = $tratamientos_dentro;
$data->tratamientos_restantes = $tratamientos_restantes;


/******/

echo json_encode($data);


/*
data_plan_paciente
data_plan_empresa
data_plan_plan
data_plan_vigencia
data_plan_tratamientos
data_plan_observaciones

*/

function getNT($id_tratamiento){
	global $conexion;
	$sql = "SELECT tratamiento FROM tratamientos WHERE id_tratamiento = $id_tratamiento";
	return @mysql_result(mysql_query($sql), 0);
	
}