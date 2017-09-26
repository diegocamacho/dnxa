<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if(!$id_horario) exit("No llegó el identificador del horario.");
if(!$hora1) exit("Debe escribir la hora inicial.");
if(!$hora2) exit("Debe escribir la hora final.");

$check_min = explode(":",$hora1);
$check_min2 = explode(":",$hora2);

if(strtotime($hora1) > strtotime($hora2) || strtotime($hora2) < strtotime($hora1) || strtotime($hora1) == strtotime($hora2)) exit("¡Debe poner horarios válidos! Intente nuevamente por favor.");
if($check_min[1] != "00" || $check_min2[1] != "00") exit("Las horas de inicio y final deben empezar y terminar en horas completas, no se permiten minutos, verifique la información.");
	
	//Insertamos datos
	$sql="UPDATE clinicas_horarios SET hora_ini='$hora1',hora_fin='$hora2' WHERE id_horario=$id_horario";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}
?>