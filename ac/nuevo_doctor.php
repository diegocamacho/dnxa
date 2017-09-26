<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if(!$nombre) exit("Debe escribir el nombre del médico.");
if(!$id_clinica) exit("Debe seleccionar una clínica para dicho médico.");

$nombre=limpiaStr($nombre,1,1);



	//Insertamos datos
	$sql="INSERT INTO doctores (nombre,id_clinica) VALUES ('$nombre','$id_clinica')";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}
