<?
include("../includes/session.php");
include("../includes/db.php");

extract($_POST);
//print_r($_POST);
//Validamos datos completos
//if(!$tipo) exit("No llego el identificador de la operación");
if(!$id_clinica) exit("No llego el identificador la clínica");

//Updateamos el estado
$sql="UPDATE clinicas SET activo='$tipo' WHERE id_clinica=$id_clinica";
$q=mysql_query($sql);
if($q){
	echo "1";
}else{
	echo "Ocurrió un error al actualizar el usuario";
}
?>