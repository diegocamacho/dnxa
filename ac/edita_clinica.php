<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if(!$id_clinica) exit("No llegó el identificador del la clínica.");
if(!$nombre) exit("Debe escribir un nombre para el prototipo.");
if(!$telefono) exit("Debe escribir el número de teléfono de la clínica.");
if(!$direccion) exit("Debe escribir la dirección de la clínica.");
//if(!$color) exit("Debe escribir la dirección de la clínica.");

//if(!$password) exit("Debe escribir una contraseña.");


//Formateamos y validamos los valores
$nombre=limpiaStr($nombre,1,1);
$direccion=limpiaStr($direccion,1,1);

	//Insertamos datos
	$sql="UPDATE clinicas SET clinica='$nombre', telefono='$telefono', direccion='$direccion', color='$color',capacidad_citas='$capacidad',lun='$lunes',mar='$martes',mie='$miercoles',jue='$jueves',vie='$viernes',sab='$sabado',dom='$domingo',hora_ini='$hora1',hora_fin='$hora2' WHERE id_clinica=$id_clinica";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}
?>