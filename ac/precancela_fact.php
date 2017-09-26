<?
include("../includes/session.php");
include("../includes/db.php");

extract($_POST);
//print_r($_POST);
//Validamos datos completos
//if(!$tipo) exit("No llego el identificador de la operación");
if(!$id_factura) exit("No llego el identificador de la factura");

//Updateamos el estado
$sql="UPDATE facturas SET estado='$tipo' WHERE id_factura=$id_factura";
$q=mysql_query($sql);
if($q){
	echo "1";
}else{
	echo "Ocurrió un error al precancelar la factura";
}
?>