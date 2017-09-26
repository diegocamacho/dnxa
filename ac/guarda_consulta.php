<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);
	
if(!$nombre) exit("Debe escribir el nombre del paciente.");
//if(!$telefono) exit("Debe escribir el teléfono del paciente.");
//if(!$email) exit("Debe escribir el email del paciente.");
if(!$cantidad) exit("Seleccione al menos un tratamiento para guardar la consulta.");
if(!$id_metodo_pago) exit("Seleccione el método de pago.");
if(!$id_clinica) exit("No se identifico la clínica, contacte a soporte.");


$nombre=limpiaStr($nombre,1,1);
$comentarios=limpiaStr($comentarios,1,1);


if($id_metodo_pago==1):
	$tipo_cuenta=2;
else:
	$tipo_cuenta=3;
endif;

//Sacamos la cuenta donde vamos a guardar la operación
$sql="SELECT id_cuenta FROM books_cuentas WHERE eliminable=0 AND id_empresa=$id_clinica AND tipo_cuenta=$tipo_cuenta";
$q=mysql_query($sql);
$dat=mysql_fetch_assoc($q);
$id_cuenta=$dat['id_cuenta'];

if(!$id_cuenta) exit("La clínica no tiene cuentas para guardar el ingreso, contacte a soporte.");

mysql_query('BEGIN');

if(!$id_paciente):

	$sq=@mysql_query("INSERT INTO pacientes (fecha_registro,nombre,telefono,email,tipo) VALUES ('$fecha_actual','$nombre','$telefono','$email','1')");
	if(!$sq) $error = true;
	$id_paciente=mysql_insert_id();

endif;


$sq=@mysql_query("INSERT INTO consultas (id_cita,id_paciente,id_clinica,id_usuario,id_doctor,fecha_hora,observaciones)VALUES('$id_cita','$id_paciente','$id_clinica','$s_id_usuario','$id_doctor','$fechahora','$observaciones')");
if(!$sq) $error = true;
$id_consulta=mysql_insert_id();
	
foreach($cantidad as $id => $val):

	$precio_total = $precio[$id];
	$sq=@mysql_query("INSERT INTO consultas_tratamientos (id_consulta,id_tratamiento,cantidad,precio)VALUES('$id_consulta','$id','$val','$precio_total')");
	if(!$sq) $error = true;
	
	$cobro_total=$val*$precio_total;
	
	$total+=$cobro_total;
	
endforeach;
if($id_cita):
	$sq=@mysql_query("UPDATE citas SET atendida=1 WHERE id_cita=$id_cita");
	if(!$sq) $error = true;
	
	$sq=@mysql_query("UPDATE pacientes SET tipo=1 WHERE id_paciente=$id_paciente");
	if(!$sq) $error = true;
endif;

	/*insertamos el especialista */
	if($id_especialista_lab>0){
		$sq=@mysql_query("INSERT INTO pagos_especialistas_lab (id_especialista_lab,id_consulta)VALUES('$id_especialista_lab','$id_consulta')");
		if(!$sq) $error = true;
	}
	
	if($id_laboratorio>0){
		$sq=@mysql_query("INSERT INTO pagos_especialistas_lab (id_especialista_lab,id_consulta)VALUES('$id_laboratorio','$id_consulta')");
		if(!$sq) $error = true;
	}
	
	/*insertamos el ingreso en la cuenta */
	$referencia="Consulta del paciente: ".$nombre." <br>atendido por ".$s_nombre;
	$referencia=limpiaStr($referencia,1,1);
	$sq=@mysql_query("INSERT INTO books_ingresos (id_cuenta,id_usuario,id_metodo_pago,id_tipo_ingreso,id_consulta,fecha_hora_captura,fecha_ingreso,monto,referencia)VALUES('$id_cuenta','$s_id_usuario','$id_metodo_pago','2','$id_consulta','$fechahora','$fechahora','$total','$referencia')");
	if(!$sq) $error = true;
	
	//Insertamos la pre factura
	$metodo_pago=metodoPago($id_metodo_pago);
	$clave=clavemetodoPago($id_metodo_pago);
	$codigo=mt_rand(111111,999999);
	$sq=@mysql_query("INSERT INTO pre_facturas (id_consulta,codigo,monto,metodo_pago,clave,num_cuenta,fecha_hora)VALUES('$id_consulta','$codigo','$total','$metodo_pago','$clave','$num_cuenta','$fechahora')");
	if(!$sq) $error = true;
	
	
if($error):
    mysql_query('ROLLBACK');
    echo "Ocurrió un error, intente más tarde.";
else:
    mysql_query('COMMIT');
	$imprimir = base64_encode(imprimirConsulta($id_consulta,$metodo_pago, $codigo, 1));

    echo "1|$imprimir";
endif;

//include('imprimir_ticket.php');



function return_linea($linea){
	if(strlen($linea)>0):
		return 'esc_pos_line($printer, "'.$linea.'");';
	endif;
}


function imprimirConsulta($id_consulta,$metodo, $codigo, $factura = 1){
	
	global $conexion;
	
	$sql = "
	SELECT consultas.fecha_hora,clinicas.clinica, usuarios.nombre, pacientes.nombre AS paciente, consultas.id_doctor AS id_doctor
	FROM consultas
	JOIN clinicas ON clinicas.id_clinica = consultas.id_clinica
	JOIN usuarios ON usuarios.id_usuario = consultas.id_usuario
	JOIN pacientes ON pacientes.id_paciente = consultas.id_paciente
	WHERE consultas.id_consulta = $id_consulta";

	$q = mysql_query($sql);
	$datos = @mysql_fetch_assoc($q);
	
	$sucursal = mb_strtoupper($datos['clinica'],'UTF-8');
	$aux = mb_strtoupper($datos['nombre'],'UTF-8');
	$paciente = mb_strtoupper($datos['paciente'],'UTF-8');
	$fecha_hora = devuelveFechaHora($datos['fecha_hora']);
	$id_doctor = $datos['id_doctor'];
	
	$sql = "SELECT * FROM ticket";	
	$q_ticket = mysql_query($sql);
	$data = @mysql_fetch_assoc($q_ticket);
	
	$linea_1  = return_linea($data['linea_1']);
	$linea_2  = return_linea($data['linea_2']);
	$linea_3  = return_linea($data['linea_3']);
	$linea_4  = return_linea($data['linea_4']);
	$linea_5  = return_linea($data['linea_5']);
	$linea_6  = return_linea($data['linea_6']);
	$linea_7  = return_linea($data['linea_7']);
	$linea_8  = return_linea($data['linea_8']);
	$linea_9  = return_linea($data['linea_9']);
	$linea_10 = return_linea($data['linea_10']);
	
	$footer_1  = return_linea($data['footer_1']);
	$footer_2  = return_linea($data['footer_2']);
	$footer_3  = return_linea($data['footer_3']);
	$footer_4  = return_linea($data['footer_4']);
	$footer_5  = return_linea($data['footer_5']);


	$sitio_web_factura = $data['sitio_web'];
	$dias_para_factura = $data['dias_facturar'];

    $var.='
	  $printer = esc_pos_open("DENTISXA", "ch-latin-2", false, true); 
	  esc_pos_drawer($printer);
	  esc_pos_align($printer, "center");
      esc_pos_font($printer, "A");
      esc_pos_char_width($printer, "");
      esc_pos_line($printer, "'.$sucursal.'");';

    $var.=$linea_1;
    $var.=$linea_2;
    $var.=$linea_3;
    $var.=$linea_4;
    $var.=$linea_5;
    $var.=$linea_6;
    $var.=$linea_7;
    $var.=$linea_8;
    $var.=$linea_9;
    $var.=$linea_10;
    

    $var.='
      esc_pos_line($printer, "'.$fecha_hora.'");
      esc_pos_line($printer, "FOLIO: '.$id_consulta.'");
      esc_pos_line($printer, "ASIST: '.$aux.'");
      esc_pos_line($printer, "DR: '.dameDoctor($id_doctor).'");
      esc_pos_line($printer, "PACIENTE: '.$paciente.'");
      esc_pos_line($printer, "------------------------------------------------");
	  esc_pos_align($printer, "left");	
      esc_pos_line($printer, "DESCRIPCION                 CANT   UNIT    SUBT");';




	$sql = "SELECT consultas_tratamientos.cantidad,tratamientos.tratamiento,consultas_tratamientos.precio FROM consultas_tratamientos
	JOIN tratamientos ON tratamientos.id_tratamiento = consultas_tratamientos.id_tratamiento
	WHERE id_consulta = '$id_consulta'";
	$q = mysql_query($sql);
	while($ft=mysql_fetch_assoc($q)){
		
		  $producto = $ft['tratamiento'];  		  
		  $producto = substr($producto,0,20);

	      $c_p = strlen($producto);
		  if($c_p<20){
		  	$to_p = 20-$c_p;
			  switch($to_p){
			      case 1: $space0 = " "; break;
			      case 2: $space0 = "  "; break;
			      case 3: $space0 = "   "; break;
			      case 4: $space0 = "    "; break;
			      case 5: $space0 = "     "; break;
			      case 6: $space0 = "      "; break;
			      case 7: $space0 = "       "; break;
			      case 8: $space0 = "        "; break;
			      case 9: $space0 = "         "; break;
			      case 10: $space0 = "          "; break;
			      case 11: $space0 = "           "; break;
			      case 12: $space0 = "            "; break;
			      case 13: $space0 = "             "; break;
			      case 14: $space0 = "              "; break;
 			      case 15: $space0 = "               "; break;
			      case 16: $space0 = "                "; break;
			      case 17: $space0 = "                 "; break;
			      case 18: $space0 = "                  "; break;
			      case 19: $space0 = "                   "; break;
			      case 20: $space0 = "                    "; break;

			  }
		  }
		  
		  $cantidad = $ft['cantidad'];
		  $precio = $ft['precio'];
		  
		  $total=$ft['cantidad']*$ft['precio']; 
		  $g_total+=$total; 
		  $total= number_format($total,2, '.', '');
		  
			  $prec = strlen($precio);
			  $cant = strlen($cantidad);
			  $tot = strlen($total);

			  switch($cant){
			      case 1: $space1 = "          "; break;
			      case 2: $space1 = "         "; break;
			      case 3: $space1 = "        "; break;
			      case 4: $space1 = "       "; break;
			  }
			  switch($prec){
			      case 4: $space2 = "    "; break;
			      case 5: $space2 = "   "; break;
			      case 6: $space2 = "  "; break;
			      case 7: $space2 = " "; break;
			      case 8: $space2 = ""; break;
			  }
			  switch($tot){
			      case 4: $space3 = "     "; break;
			      case 5: $space3 = "    "; break;
			      case 6: $space3 = "   "; break;
			      case 7: $space3 = "  "; break;
			      case 8: $space3 = " "; break;
			  }
	      
	      $var.= 'esc_pos_line($printer, "'.$producto.$space0.$space1.$cantidad.$space2.$precio.$space3.$total.'");';
	      
	      unset($space0);
	  }
	  
	  $g_total = number_format($g_total,2, '.', '');
	  
	  $t = strlen($g_total);
	  $m = strlen($metodo);

	  switch($t){
	      case 4: $spacet = "           "; break;
	      case 5: $spacet = "          "; break;
	      case 6: $spacet = "         "; break;
	      case 7: $spacet = "        "; break;
	      case 8: $spacet = "       "; break;
	  }
	  
	  switch($m){
	      case 4: $spacem = "           "; break;
	      case 5: $spacem = "          "; break;
	      case 6: $spacem = "         "; break;
	      case 7: $spacem = "        "; break;
	      case 8: $spacem = "       "; break;
	      case 9: $spacem = "      "; break;
	      case 10: $spacem = "    "; break;
	      case 11: $spacem = "   "; break;
	      case 12: $spacem = "  "; break;
	      case 13: $spacem = " "; break;
	  }

		
		if($factura==1){
			$fact = 'esc_pos_line($printer, "GENERE SU FACTURA EN: '.$sitio_web_factura.'");';		
			$fact.= 'esc_pos_line($printer, "CODIGO DE FACTURACION: '.$codigo.'");';		
			$fact.= 'esc_pos_line($printer, "CUENTA CON '.$dias_para_factura.' DIAS A PARTIR DE LA FECHA");';			 	
			$fact.= 'esc_pos_line($printer, "DE ESTE TICKET PARA GENERAR SU FACTURA.");';			 	
		}else{
			$status = 'esc_pos_font($printer, "B");';
			$status.= 'esc_pos_line($printer, "FACTURA NO REQUERIDA POR EL CLIENTE.");';		 	
			$status.= 'esc_pos_line($printer, "ESTA VENTA SE INTEGRARA A LA FACTURA GLOBAL DIARIA.");';		 	
		}
	
	
	$var.='
      esc_pos_line($printer, "------------------------------------------------");
      esc_pos_align($printer, "right");
	  esc_pos_emphasize($printer,true);
		esc_pos_line($printer, "METODO:'.$spacem.$metodo.'");
      esc_pos_line($printer, "TOTAL:'.$spacet.$g_total.'");
	  esc_pos_emphasize($printer,false);
      esc_pos_line($printer, "------------------------------------------------");
      esc_pos_font($printer, "A");
      esc_pos_align($printer, "center");
      '.$fact.'
      esc_pos_align($printer, "center");
     esc_pos_line($printer, "");';
	  
	  $var.=$footer_1;
	  $var.=$footer_2;
	  $var.=$footer_3;
	  $var.=$footer_4;
	  $var.=$footer_5;
	  
      $var.='
      esc_pos_line($printer, "");
	  '.$status.'
	  esc_pos_cut($printer);    
      esc_pos_close($printer);
      ';

	  return $var;
	
}


