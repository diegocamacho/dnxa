<?
include("../includes/session.php");
include("../includes/db.php");

extract($_POST);
//print_r($_POST);
//Validamos datos completos
//if(!$tipo) exit("No llego el identificador de la operación");
if(!$id_cliente) exit("No llego el identificador del cliente");

//Updateamos el estado
$sql="UPDATE books_clientes SET activo='$tipo' WHERE id_cliente=$id_cliente";
$q=mysql_query($sql);
if($q){
	echo "1";
}else{
	echo "Ocurrió un error al actualizar el usuario";
}
?>