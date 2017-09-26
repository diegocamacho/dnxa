<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");
extract($_POST);
//Validamos datos completos
if(!$id_cita) exit("No llegó el identificador de la cita.");
if(!$id_clinica) exit("Seleccione una clínica.");
if(!$id_tratamiento) exit("Seleccione un tratamiento.");
//if(!$id_promocion) exit("Seleccione un tratamiento.");
if(!$fecha_hora) exit("Debe especificar la fecha y hora de la cita.");
if(!$fecha_hora_final) exit("Debe especificar la fecha y hora en que va a terminar la cita.");
if(!$color) exit("Seleccione un color para la cita.");
if($comentarios) $comentarios=limpiaStr($comentarios,1,1);


mysql_query('BEGIN');


$sql = "SELECT tiene_blanqueamiento FROM citas WHERE id_cita = $id_cita";
$q = mysql_query($sql);
$tiene_blanq = @mysql_result($q, 0);
if(!$q) $error = true;

//CHECAMOS QUE SEA DE LA CLINICA 11 QUE ES BLANQUEAMIENTOS (ESO QUIERE DECIR QUE TEIENE BLANQUEAMIENTO
/*$sql = "SELECT id_clinica FROM citas WHERE id_cita = $id_cita";
$q = mysql_query($sql);
$clinica = @mysql_result($q, 0);
if($clinica == 11){
	$tiene_blanq = 1;
}
if(!$q) $error = true;*/


if(!$blanqueamientos){
	$blanqueamientos = 0;
}

if($tiene_blanq){		
																	// Si tiene_blanqueamiento:
	if(!$blanqueamientos){ 																	// Y ya no lo tiene.
		$sql = "DELETE FROM citas WHERE id_blanqueamiento = '$id_cita'";					//eliminar hijo				
		$q = mysql_query($sql);
		if(!$q) $error = true;
	}else{																						//Y lo sigue teniendo.
																								//updatear hijo.
		$sql="UPDATE citas 	
			SET id_clinica='$id_clinica', 
			id_tratamiento='$id_tratamiento', 
			id_promocion='$id_promocion', 
			fecha_hora='$fecha_hora', 
			fecha_hora_final='$fecha_hora_final', 
			comentario='$comentarios', 
			tiene_blanqueamiento = '$blanqueamientos', 
			id_usuario = '$id_doctor', 
			id_usuario_reagendo = '$s_id_usuario',
			color='$color' 
			WHERE id_blanqueamiento='$id_cita'";
		$q = mysql_query($sql);
		if(!$q) $error = true;
							
	}
}else{				//Si no tiene blanqueamiento:

	if($blanqueamientos){ 			// Y Ya lo tiene:
									//Insertar hijo

		$sql = "SELECT confirmada,id_paciente FROM citas WHERE id_cita = $id_cita";
		$q = mysql_query($sql);
		if(!$q) $error = true;
		$datx = mysql_fetch_assoc($q);
		
		$id_p = $datx['id_paciente'];
		$confirmada = $datx['confirmada'];
		
		$sql ="INSERT INTO citas 
		(id_paciente,id_clinica, confirmada,
		id_tratamiento,id_promocion,
		id_usuario,fecha_hora,
		fecha_hora_final,comentario,
		color,id_usuario_agendo,
		id_blanqueamiento) VALUES ('$id_p','11', '$confirmada',
		'$id_tratamiento','$id_promocion',
		'$id_doctor','$fecha_hora',
		'$fecha_hora_final','$comentarios',
		'$color','$s_id_usuario',
		'$id_cita')";
		$q = mysql_query($sql);
		if(!$q) $error = true;
	}
}

//Insertamos datos
$sql="UPDATE citas SET id_clinica='$id_clinica', id_tratamiento='$id_tratamiento', id_promocion='$id_promocion', fecha_hora='$fecha_hora', fecha_hora_final='$fecha_hora_final', comentario='$comentarios', tiene_blanqueamiento='$blanqueamientos', id_usuario='$id_doctor',id_usuario_reagendo = '$s_id_usuario', color='$color', id_especialista_lab='$id_especialista' WHERE id_cita='$id_cita'";
$q=mysql_query($sql);
if(!$q) $error = true;


if($error):
    mysql_query('ROLLBACK');
    echo "Ocurrió un error, intente más tarde.";
else:
    mysql_query('COMMIT');
    echo "1";
endif;



