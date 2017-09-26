<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if(!$nombre) exit("Debe escribir un nombre para la empresa.");

$nombre=limpiaStr($nombre,1,1);



	//Insertamos datos
	$sql="INSERT INTO books_empresas (empresa,fecha_creacion) VALUES ('$nombre','$fecha_actual')";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}
