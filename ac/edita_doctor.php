<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if(!$id_doctor) exit("No llegó el identificador del doctor.");
if(!$nombre) exit("Debe escribir un nombre para el doctor.");
if(!$id_clinica) exit("Debe seleccionar una clínica para dicho médico.");

//if(!$password) exit("Debe escribir una contraseña.");


//Formateamos y validamos los valores
$nombre=limpiaStr($nombre,1,1);

	//Insertamos datos
	$sql="UPDATE doctores SET nombre='$nombre', id_clinica = '$id_clinica' WHERE id_doctor=$id_doctor";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}
?>