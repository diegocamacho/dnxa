<?php

include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

if(!$nuevo_nombre) exit("Debe especificar el nombre del nuevo especialista/laboratorio.");
if(!$nuevo_Tipo) exit("Debe especificar la fecha y hora en que va a terminar la cita.");
$activo = 1;

//Insertamos datos
$sql="INSERT INTO especialistas_lab (id_especialista_lab, nombre, telefono, email, tipo, activo) VALUES (null,'".$nuevo_nombre."','".$nuevo_telefono."','".$nuevo_email."',".$nuevo_Tipo.",1)";
$q=mysql_query($sql);
if($q){
	echo "1";
}else{
	echo "Ocurrió un error, intente más tarde.";
}

?>