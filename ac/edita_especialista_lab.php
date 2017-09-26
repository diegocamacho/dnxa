<?php

include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

if(!$edita_nombre) exit("Debe especificar el nombre del nuevo especialista/laboratorio.");
if(!$edita_Tipo) exit("Debe especificar la fecha y hora en que va a terminar la cita.");

filter_var($edita_nombre, FILTER_SANITIZE_STRING);
filter_var($edita_email, FILTER_SANITIZE_STRING);
filter_var($edita_telefono, FILTER_SANITIZE_STRING);

//Insertamos datos
$sql="UPDATE especialistas_lab SET nombre='$edita_nombre', telefono = '".$edita_telefono."', email = '".$edita_email."', tipo = ".$edita_Tipo." WHERE id_especialista_lab=$editar_id";

//echo $sql
$q=mysql_query($sql);

if($q){
	echo "1";
}else{
    echo $sql;
	echo "Ocurrió un error, intente más tarde.";
}

?>