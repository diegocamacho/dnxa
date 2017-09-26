<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if(!$nombre) exit("Debe escribir un nombre para el proveedor.");
//if(!$telefono) exit("Debe escribir un número de teléfono para el proveedor.");
//if(!$email) exit("Debe escribir una cuenta de email para el proveedor.");
if($id_plan):
	if(!$fecha1) exit("Sí la empresa tiene un plan corporativo es necesario porner una fecha de incio del mismo.");
	if(!$fecha2) exit("Sí la empresa tiene un plan corporativo es necesario porner una fecha de termino del mismo.");
	$consulta=", id_plan='$id_plan', fecha_inicio_plan='$fecha1', fecha_final_plan='$fecha2'";
endif;
$nombre=limpiaStr($nombre,1,1);

	//Insertamos datos
	$sql="UPDATE books_clientes SET id_empresa='$id_clinica', cliente='$nombre', telefono='$telefono', email='$email' $consulta WHERE id_cliente=$id_cliente";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}
?>