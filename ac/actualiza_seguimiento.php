<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if(!$id_paciente_seguimiento) exit("No llegó el identificador.");
//if(!$proxima_comunicacion) exit("Debe escribir la próxima comunicación.");
if(!$comentarios) exit("Debe escribir los comentarios obtenidos de la comunicación.");

$comentarios=limpiaStr($comentarios,1,1);
$prox_com=fechaBase2($proxima_comunicacion);

if($proxima_comunicacion):
	$val_com=",prox_com='$prox_com'";
endif;
	//Insertamos datos
	$sql="UPDATE pacientes SET ultima_com='$fecha_actual', comentarios='$comentarios' $val_com WHERE id_paciente=$id_paciente_seguimiento";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}
