<?
include("../includes/session.php");
include("../includes/db.php");

extract($_POST);
//print_r($_POST);
//Validamos datos completos
//if(!$tipo) exit("No llego el identificador de la operación");
if(!$id_canal) exit("No llego el identificador del canal");

//Updateamos el estado
$sql="UPDATE canales SET activo='$tipo' WHERE id_canal=$id_canal";
$q=mysql_query($sql);
if($q){
	echo "1";
}else{
	echo "Ocurrió un error al actualizar el usuario";
}
?>