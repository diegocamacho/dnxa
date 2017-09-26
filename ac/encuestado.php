<?
include("../includes/session.php");
include("../includes/db.php");

extract($_POST);
//print_r($_POST);
//Validamos datos completos
//if(!$tipo) exit("No llego el identificador de la operación");
if(!$id_cita) exit("No llego el identificador del canal");

//Updateamos el estado
$sql="UPDATE pacientes SET encuestado='1' WHERE id_paciente=$id_cita";
$q=mysql_query($sql);
if($q){
	echo "1";
}else{
	echo "Ocurrió un error al actualizar la cita";
}
?>