<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if(!$nombre) exit("Debe escribir el tratamiento.");
#if(!$costo) exit("Debe escribir un costo para el tratamiento.");

$nombre=limpiaStr($nombre,1,1);



	//Insertamos datos
	$sql="INSERT INTO tratamientos (tratamiento,costo) VALUES ('$nombre','$costo')";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}
