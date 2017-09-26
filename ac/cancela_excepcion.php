<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
//if(!$id_paciente_agenda) exit("Seleccione un paciente.");
if(!$id) exit("No llegó el identificador de la excepción.");


//$fecha = $dias[date('N', strtotime($fecha1))];

	//Insertamos datos
	/*
	$sql="INSERT INTO citas (id_paciente,id_clinica,id_usuario,fecha_hora,fecha_hora_final,comentario,color,id_usuario_agendo,tipo) VALUES ('0','$id_clinica','$id_doctor','$fecha_hora','$fecha_hora_final','$comentarios','$color','$s_id_usuario','2')";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}
	*/

mysql_query('BEGIN');


$sq=@mysql_query("DELETE FROM eventos_excepciones WHERE id_excepcion=$id");
if(!$sq) $error = true;
	
if($error):
    mysql_query('ROLLBACK');
    echo "Ocurrió un error, intente más tarde.";
else:
    mysql_query('COMMIT');
    echo "1";
endif;