<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
//if(!$id_paciente_agenda) exit("Seleccione un paciente.");
if(!$id_clinica) exit("Seleccione una clínica.");
if(!$id_doctor) exit("Seleccione el usuario/doctor.");
//if(!$id_tratamiento) exit("Seleccione un tratamiento.");
//if(!$id_promocion) exit("Seleccione un tratamiento.");
if(!$fecha1) exit("Debe especificar la fecha y hora de la cita.");
if(!$fecha2) exit("Debe especificar la fecha y hora de la cita.");
//if(!$fecha_hora_final) exit("Debe especificar la fecha y hora en que va a terminar la cita.");
//if(!$comentarios) exit("Debe especificar la fecha y hora de la cita.");
if($comentarios) $comentarios=limpiaStr($comentarios,1,1);


//print_r($_POST);
$start    = new DateTime($fecha1);
//$start->modify('first day of this month');

//$end      = new DateTime(date('Y-m-d'));
$end      = new DateTime($fecha2);
$end->modify('+ 1 day');
$interval = DateInterval::createFromDateString('1 day');
$period   = new DatePeriod($start, $interval, $end);

mysql_query('BEGIN');

$sq=@mysql_query("INSERT INTO eventos (id_clinica,id_doctor,descripcion,lunes,martes,miercoles,jueves,viernes,sabado,domingo,fecha1,fecha2,hora1,hora2,id_usuario,color,fecha_hora) VALUES ('$id_clinica','$id_doctor','$comentarios','$lunes','$martes','$miercoles','$jueves','$viernes','$sabado','$domingo','$fecha1','$fecha2','$hora1','$hora2','$s_id_usuario','$color','$fechahora')");
if(!$sq) $error = true;
$id_evento=mysql_insert_id();

/*foreach ($period as $dt):
		$date=$dt->format("Y-m-d");
		$dia=date('l', strtotime($date));
		
		$fecha_hora=$date." ".$hora1;
		$fecha_hora_final=$date." ".$hora2;

	    if($lunes):
	    	if($dia=="Monday"):
	    		$sq=@mysql_query("INSERT INTO citas (id_paciente,id_clinica,id_usuario,fecha_hora,fecha_hora_final,comentario,color,id_usuario_agendo,tipo,id_evento) VALUES ('0','$id_clinica','$id_doctor','$fecha_hora','$fecha_hora_final','$comentarios','$color','$s_id_usuario','2','$id_evento')");
				if(!$sq) $error = true;
	    	endif;
	    endif;
	    
	    if($martes):
	    	if($dia=="Tuesday"):
	    		$sq=@mysql_query("INSERT INTO citas (id_paciente,id_clinica,id_usuario,fecha_hora,fecha_hora_final,comentario,color,id_usuario_agendo,tipo,id_evento) VALUES ('0','$id_clinica','$id_doctor','$fecha_hora','$fecha_hora_final','$comentarios','$color','$s_id_usuario','2','$id_evento')");
				if(!$sq) $error = true;
	    	endif;
	    endif;
	    
	    if($miercoles):
	    	if($dia=="Wednesday"):
	    		$sq=@mysql_query("INSERT INTO citas (id_paciente,id_clinica,id_usuario,fecha_hora,fecha_hora_final,comentario,color,id_usuario_agendo,tipo,id_evento) VALUES ('0','$id_clinica','$id_doctor','$fecha_hora','$fecha_hora_final','$comentarios','$color','$s_id_usuario','2','$id_evento')");
				if(!$sq) $error = true;
	    	endif;
	    endif;
	    
	    if($jueves):
	    	if($dia=="Thursday"):
	    		$sq=@mysql_query("INSERT INTO citas (id_paciente,id_clinica,id_usuario,fecha_hora,fecha_hora_final,comentario,color,id_usuario_agendo,tipo,id_evento) VALUES ('0','$id_clinica','$id_doctor','$fecha_hora','$fecha_hora_final','$comentarios','$color','$s_id_usuario','2','$id_evento')");
				if(!$sq) $error = true;
	    	endif;
	    endif;
	    
	    if($viernes):
	    	if($dia=="Friday"):
	    		$sq=@mysql_query("INSERT INTO citas (id_paciente,id_clinica,id_usuario,fecha_hora,fecha_hora_final,comentario,color,id_usuario_agendo,tipo,id_evento) VALUES ('0','$id_clinica','$id_doctor','$fecha_hora','$fecha_hora_final','$comentarios','$color','$s_id_usuario','2','$id_evento')");
				if(!$sq) $error = true;
	    	endif;
	    endif;
	    
	    if($sabado):
	    	if($dia=="Saturday"):
	    		$sq=@mysql_query("INSERT INTO citas (id_paciente,id_clinica,id_usuario,fecha_hora,fecha_hora_final,comentario,color,id_usuario_agendo,tipo,id_evento) VALUES ('0','$id_clinica','$id_doctor','$fecha_hora','$fecha_hora_final','$comentarios','$color','$s_id_usuario','2','$id_evento')");
				if(!$sq) $error = true;
	    	endif;
	    endif;
	    
	    if($domingo):
	    	if($dia=="Sunday"):
	    		$sq=@mysql_query("INSERT INTO citas (id_paciente,id_clinica,id_usuario,fecha_hora,fecha_hora_final,comentario,color,id_usuario_agendo,tipo,id_evento) VALUES ('0','$id_clinica','$id_doctor','$fecha_hora','$fecha_hora_final','$comentarios','$color','$s_id_usuario','2','$id_evento')");
				if(!$sq) $error = true;
	    	endif;
	    endif;
	    
	    
	    //$annio = $dt->format("Y");
	    //$mes = (int)$dt->format("m");		 

endforeach;*/
	
if($error):
    mysql_query('ROLLBACK');
    echo "Ocurrió un error, intente más tarde.";
else:
    mysql_query('COMMIT');
    echo "1";
endif;