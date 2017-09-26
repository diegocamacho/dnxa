<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if(!$nombre) exit("Debe escribir un nombre para el proveedor.");
//if(!$telefono) exit("Debe escribir un número de teléfono para el proveedor.");
//if(!$email) exit("Debe escribir una cuenta de email para el proveedor.");

$nombre=limpiaStr($nombre,1,1);



	//Insertamos datos
	$sql="INSERT INTO books_proveedores (id_clinica,fecha_alta,proveedor,telefono,email) VALUES ('$id_clinica','$fecha_actual','$nombre','$telefono','$email')";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}
