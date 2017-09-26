<?
include("../includes/session.php");
include("../includes/db.php");

extract($_POST);
//print_r($_POST);
//Validamos datos completos
//if(!$tipo) exit("No llego el identificador de la operación");
if(!$id_cuenta) exit("No llego el identificador de la cuenta");

//Updateamos el estado
$sql="UPDATE books_cuentas SET activo='$tipo' WHERE id_cuenta=$id_cuenta AND eliminable=1";
$q=mysql_query($sql);
if($q){
	echo "1";
}else{
	echo "Ocurrió un error al desactivar la cuenta";
}
?>