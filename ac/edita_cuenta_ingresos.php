<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if(!$id_tipo_ingreso) exit("No llegó el identificador de la cuenta.");
if(!$nombre) exit("Debe escribir un nombre para la cuenta.");

//if(!$password) exit("Debe escribir una contraseña.");


//Formateamos y validamos los valores
$nombre=limpiaStr($nombre,1,1);

	//Insertamos datos
	$sql="UPDATE books_tipos_ingreso SET cuenta_ingreso='$nombre' WHERE id_tipo_ingreso=$id_tipo_ingreso AND eliminable=1";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}
?>