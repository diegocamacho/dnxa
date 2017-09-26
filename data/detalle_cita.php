<?php
include("../includes/db.php");
include("../includes/funciones.php");

if(!$_GET['id_cita']) exit("Error de ID");

$id_cita = escapar($_GET['id_cita'],1);

$sql = "
SELECT usuarios.nombre AS doctor,consultas.fecha_hora,consultas.observaciones, pacientes.nombre AS paciente, clinicas.clinica  FROM citas 
JOIN clinicas ON clinicas.id_clinica=citas.id_clinica
JOIN pacientes ON pacientes.id_paciente=citas.id_paciente
LEFT JOIN consultas ON consultas.id_cita=citas.id_cita
LEFT JOIN usuarios ON usuarios.id_usuario=consultas.id_usuario
WHERE citas.id_cita = $id_cita";

$q = mysql_query($sql);
$data = mysql_fetch_object($q);
$data->fecha_hora = fechaHoraMeridian($data->fecha_hora);
echo json_encode($data);

//paciente
//doctor
//fecha_hora
//observaciones
//tratamiento