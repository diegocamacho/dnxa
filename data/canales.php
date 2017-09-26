<?

include("../includes/db.php");
include("../includes/funciones.php");

if(!$_GET['id_canal']){ exit("Error de ID");}

$id_canal=escapar($_GET['id_canal'],1);

$sql="SELECT * FROM canales WHERE id_canal=$id_canal";
$query=mysql_query($sql);
$ft=mysql_fetch_assoc($query);
if($query){
	echo $ft['canal'];
}else{
	echo "error";
}
?>