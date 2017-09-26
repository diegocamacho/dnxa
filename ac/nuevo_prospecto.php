<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if(!$nombre) exit("Debe escribir el nombre del prospecto.");
//if(!$telefono) exit("Debe escribir un teléfono para el prospecto.");
//if(!$email) exit("Debe escribir la dirección de correo del prospecto.");
if(!$id_canal) exit("Debe seleccionar un canal de contacto por donde llego el prospecto.");

$nombre=limpiaStr($nombre,1,1);
if($comentarios) $comentarios=limpiaStr($comentarios,1,1); 
if($proxima_comunicacion): $prox_com=fechaBase2($proxima_comunicacion); endif;
	//Insertamos datos
	$sql="INSERT INTO pacientes (id_canal,fecha_registro,nombre,telefono,email,ultima_com,prox_com,comentarios) VALUES ('$id_canal','$fecha_actual','$nombre','$telefono','$email','$fecha_actual','$prox_com','$comentarios')";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}
