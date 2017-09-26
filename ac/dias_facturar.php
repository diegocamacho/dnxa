<?

include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

if($dias <= 0) exit("DEBE DEJAR AL MENOS UN DÍA PARA PODER FACTURAR. REINTENTE");
$sql = "UPDATE config_facturacion SET dias_facturar = '$dias' WHERE RFC = 'DOC160429N55'";
$q = mysql_query($sql);
if($q){
	$resp = 1;
}else{
	$resp = "Hubo un problema con la actualización de los días para facturación. Contacte a Soporte";
}
echo $resp;