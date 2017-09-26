<?

include("../includes/db.php");
include("../includes/funciones.php");

$id_tratamiento = $_GET['id'];

$sql = "SELECT costo FROM tratamientos WHERE id_tratamiento = $id_tratamiento";
$q = mysql_query($sql);
$datos = mysql_fetch_object($q);
echo json_encode($datos);