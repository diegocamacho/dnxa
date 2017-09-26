<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if(!$id_paciente) exit("No llegó el identificador.");
if(!$nombre) exit("Debe escribir el nombre del prospecto.");
//if(!$telefono) exit("Debe escribir un teléfono para el prospecto.");
//if(!$email) exit("Debe escribir la dirección de correo del prospecto.");
if(!$id_canal) exit("Debe seleccionar un canal de contacto por donde llego el prospecto.");

$nombre=limpiaStr($nombre,1,1);

	//Insertamos datos
	$sql="UPDATE pacientes SET id_canal='$id_canal', nombre='$nombre', telefono='$telefono', email='$email' WHERE id_paciente=$id_paciente";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}
?>