<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if(!$nombre) exit("Debe escribir un nombre para la promoción.");
//if(!$descripcion) exit("Debe escribir la descripción de la promoción.");

$nombre=limpiaStr($nombre,1,1);
if($descripcion):
	$descripcion=limpiaStr($descripcion,1,1);
endif;


	//Insertamos datos
	$sql="UPDATE promociones SET promocion='$nombre', descripcion='$descripcion' WHERE id_promocion=$id_promocion";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}
?>