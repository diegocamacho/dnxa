<?

include("../includes/db.php");
include("../includes/funciones.php");

if(!$_GET['id_doctor']){ exit("Error de ID");}

$id_doctor=escapar($_GET['id_doctor'],1);

$sql="SELECT * FROM doctores WHERE id_doctor = '$id_doctor'";
$query=mysql_query($sql);
$ft=mysql_fetch_assoc($query);
if($query){
	echo $ft['nombre']."|".$ft['id_clinica'];
}else{
	echo "error";
}
?>