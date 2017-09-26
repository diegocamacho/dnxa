<?
	include('../includes/db.php');
	
	$eventos_viejos = mysql_query("SELECT * FROM citas WHERE tipo=2");
	
	/*while($evento = mysql_fetch_assoc($eventos_viejos)){
		$dia1 = substr($evento['fecha_hora'],0,10);
		$hora1 = substr($evento['fecha_hora'],11);
		$dia2 = substr($evento['fecha_hora_final'],0,10);
		$hora2 =substr($evento['fecha_hora_final'],11);
		$descripcion = $evento['comentario'];
		$color = $evento['color'];
		$id_clinica = $evento['id_clinica'];
		$id_doctor =  $evento['id_usuario'];
		
		//INSERTAMOS
		$q = mysql_query("INSERT INTO eventos (id_clinica,id_doctor,descripcion,lunes,martes,miercoles,jueves,viernes,sabado,domingo,fecha1,fecha2,hora1,hora2,id_usuario,color) VALUES ('$id_clinica','$id_doctor','$descripcion','on','on','on','on','on','on','on','$dia1','$dia2','$hora1','$hora2','$id_doctor','$color')");
		if($q){
			echo "EVENTO INSERTADO <br>";
		}else{
			echo "ERROR EN CITA ".$evento['id_cita']."<br>";
		}
	}*/
?>