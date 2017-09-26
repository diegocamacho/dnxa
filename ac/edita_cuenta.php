<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if(!$id_cuenta) exit("No llegó el identificador de la cuenta.");
if(!$id_empresa) exit("Debe escribir un nombre para la cuenta.");
if(!$nombre) exit("Debe escribir un nombre para la cuenta.");
if(!$tipo_cuenta) exit("Debe escribir un nombre para la cuenta.");

//if(!$password) exit("Debe escribir una contraseña.");


//Formateamos y validamos los valores
$nombre=limpiaStr($nombre,1,1);

	//Insertamos datos
	$sql="UPDATE books_cuentas SET id_empresa='$id_empresa', alias='$nombre', tipo_cuenta='$tipo_cuenta' WHERE id_cuenta=$id_cuenta AND eliminable=1";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}
?>