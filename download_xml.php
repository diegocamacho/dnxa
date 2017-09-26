<?
include("includes/session_ui.php");
include("includes/db.php");
include("includes/funciones.php");

error_reporting(0);
extract($_GET);
$id_factura = limpiaStr($id_factura);

$sql = "SELECT uuid FROM facturas WHERE id_factura=$id_factura";
$q = mysql_query($sql);
$ft = mysql_fetch_assoc($q);

$uuid = $ft['uuid'];
$xml = 'http://facturacion.dentisxa.mx/facturacion/cfdi/'.$uuid.'.xml';
	
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"$xml\"\n");

$fp=fopen($ruta_xml, "r");
fpassthru($fp);