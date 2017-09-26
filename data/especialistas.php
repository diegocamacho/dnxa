<?
include("../includes/db.php");
include("../includes/funciones.php");

if(!$_GET['id']){ exit("Error ID");}

$id=escapar($_GET['id'],1);

$sql="SELECT * FROM especialistas_lab WHERE id_especialista_lab = '$id'";
$query=mysql_query($sql);
$data = mysql_fetch_object($query);

echo json_encode($data);