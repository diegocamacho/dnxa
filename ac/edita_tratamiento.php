<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if(!$id_tratamiento) exit("No llegó el identificador del tratamiento.");
if(!$nombre) exit("Debe escribir un nombre para el tratamiento.");
#if(!$costo) exit("Debe escribir un costo para el tratamiento.");

//if(!$password) exit("Debe escribir una contraseña.");


//Formateamos y validamos los valores
$nombre=limpiaStr($nombre,1,1);

	//Insertamos datos
	$sql="UPDATE tratamientos SET tratamiento='$nombre', costo='$costo' WHERE id_tratamiento=$id_tratamiento";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}
?>