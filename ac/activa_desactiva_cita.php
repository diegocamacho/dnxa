<?
include("../includes/session.php");
include("../includes/db.php");

extract($_POST);
//print_r($_POST);
//Validamos datos completos
//if(!$tipo) exit("No llego el identificador de la operación");
if(!$id_cita) exit("No llego el identificador de la cita");

//Updateamos el estado
$sql="UPDATE citas SET activo='$tipo', confirmada=0 WHERE id_cita=$id_cita";
$q=mysql_query($sql);
if($q){
	echo "1";
	
	$sql="UPDATE citas SET activo='$tipo', confirmada=0 WHERE id_blanqueamiento=$id_cita";
	$q=@mysql_query($sql);
	
}else{
	echo "Ocurrió un error al actualizar el usuario";
}
?>