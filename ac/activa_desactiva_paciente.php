<?
include("../includes/session.php");
include("../includes/db.php");

extract($_POST);
//print_r($_POST);
//Validamos datos completos
//if(!$tipo) exit("No llego el identificador de la operación");
if(!$id_paciente) exit("No llegó el identificador");

//Updateamos el estado
$sql="UPDATE pacientes SET activo='$tipo' WHERE id_paciente=$id_paciente";
$q=mysql_query($sql);
if($q){
	echo "1";
}else{
	echo "Ocurrió un error al actualizar el usuario";
}
?>