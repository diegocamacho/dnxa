<?

include("../includes/db.php");
include("../includes/funciones.php");


$sql = "SELECT dias_facturar FROM config_facturacion WHERE RFC = 'DOC160429N55'";
$q = mysql_query($sql);
$resp = mysql_fetch_array($q);
$resp = $resp[0];
echo $resp;