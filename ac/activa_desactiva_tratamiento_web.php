<?
include("../includes/session.php");
include("../includes/db.php");

extract($_POST);
//print_r($_POST);
//Validamos datos completos
//if(!$tipo) exit("No llego el identificador de la operación");
if(!$id_tratamiento) exit("No llego el identificador del tratamiento");

//Updateamos el estado
$sql="UPDATE tratamientos SET web='$tipo' WHERE id_tratamiento=$id_tratamiento";
$q=mysql_query($sql);
if($q){
	echo "1";
}else{
	echo "Ocurrió un error al actualizar el usuario";
}
?>