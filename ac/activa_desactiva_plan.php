<?
include("../includes/session.php");
include("../includes/db.php");

extract($_POST);
//print_r($_POST);
//Validamos datos completos
//if(!$tipo) exit("No llego el identificador de la operación");
if(!$id_plan) exit("No llego el identificador del plan");

//Updateamos el estado
$sql="UPDATE planes SET activo='$tipo' WHERE id_plan=$id_plan";
$q=mysql_query($sql);
if($q){
	echo "1";
}else{
	echo "Ocurrió un error al actualizar el usuario";
}
?>