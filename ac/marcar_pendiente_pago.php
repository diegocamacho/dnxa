<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);


if(!$id) exit("No llegó el identificador.");

	$sql="UPDATE pagos_especialistas_lab SET liquidado = 0 WHERE id_pago_especialistas_lab = $id";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}
?>