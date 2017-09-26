<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if(!$nombre) exit("Debe escribir un nombre para la cuenta.");
if(!$id_empresa) exit("Debe seleccionar una empresa para la cuenta.");
if($tipo_cuenta==0) exit("Debe seleccionar un tipo de cuenta.");

$nombre=limpiaStr($nombre,1,1);



	//Insertamos datos
	$sql="INSERT INTO books_cuentas (id_empresa,alias,tipo_cuenta,fecha_creacion) VALUES ('$id_empresa','$nombre','$tipo_cuenta','$fecha')";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}
