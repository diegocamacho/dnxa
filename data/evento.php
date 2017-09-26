<?php
include("../includes/db.php");
include("../includes/funciones.php");

if(!$_GET['id']) exit("Error de ID");

$id = escapar($_GET['id'],1);

$sql = "SELECT * FROM citas WHERE id_cita=$id";

$q = mysql_query($sql);
$data = mysql_fetch_object($q);
$data->fecha_hora2 = fechaHoraMeridian($data->fecha_hora);
$data->fecha_hora_final2 = fechaHoraMeridian($data->fecha_hora_final);
echo json_encode($data);