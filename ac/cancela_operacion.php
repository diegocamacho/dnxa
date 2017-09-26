<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");
//exit("error");
extract($_POST);
//print_r($_POST);
//Validamos datos completos
if(!$tipo) exit("No llego el identificador del tipo de operación");
if(!$id) exit("No llego el identificador de la operación");


mysql_query('BEGIN');

if($tipo==1): 	//Ingreso

	$sql="SELECT id_transferencia FROM books_ingresos WHERE id_ingreso=$id";
	$q=mysql_query($sql);
	$ft=mysql_fetch_assoc($q);
	
	if($ft['id_transferencia']):
	
		$id_transferencia=$ft['id_transferencia'];
		$sq=@mysql_query("UPDATE books_ingresos SET activo=0 WHERE id_transferencia=$id_transferencia");
		$error = (!$sq) ? true : false;
		$sq=@mysql_query("UPDATE books_gastos SET activo=0 WHERE id_transferencia=$id_transferencia"); 
		$error = (!$sq) ? true : false;

	else:
		$sq=@mysql_query("UPDATE books_ingresos SET activo=0 WHERE id_ingreso=$id");
		$error = (!$sq) ? true : false;
		
		if($id_consulta):
			$id_consulta_ok = $id_consulta;
			$sql=mysql_query("SELECT id_cita FROM consultas WHERE id_consulta=$id_consulta");
			$ft=mysql_fetch_assoc($sql);
			$id_cita=$ft['id_cita'];
			
			$sq=@mysql_query("UPDATE consultas SET activo=0 WHERE id_consulta=$id_consulta");
			$error = (!$sq) ? true : false;
			
			$sq=@mysql_query("UPDATE citas SET atendida=0 WHERE id_cita=$id_cita");
			$error = (!$sq) ? true : false;
			
			$sq=@mysql_query("DELETE FROM pre_facturas WHERE id_consulta=$id_consulta");
			$error = (!$sq) ? true : false;
			
			
			#$sq=@NONO_mysql_query("DELETE FROM consultas_tratamientos WHERE id_consulta=$id_consulta");
			#if(!$sq) $error = true;
		endif;
		
	endif;
	
	
elseif($tipo==2): //Egreso
	
	$sql="SELECT id_transferencia FROM books_gastos WHERE id_gasto=$id";
	$q=mysql_query($sql);
	$ft=mysql_fetch_assoc($q);
	
	if($ft['id_transferencia']):
	
		$id_transferencia=$ft['id_transferencia'];
		$sq=@mysql_query("UPDATE books_ingresos SET activo=0 WHERE id_transferencia=$id_transferencia");
		$error = (!$sq) ? true : false;
		$sq=@mysql_query("UPDATE books_gastos SET activo=0 WHERE id_transferencia=$id_transferencia");
		$error = (!$sq) ? true : false;
	
	else:		
	
		$sq=@mysql_query("UPDATE books_gastos SET activo=0 WHERE id_gasto=$id");
		$error = (!$sq) ? true : false;
	
	endif;
	
else:
	exit("Ocurrió un error, intenta nuevamente.");
endif;

if($error==true):
    mysql_query('ROLLBACK');
    echo "Ocurrió un error, intente más tarde.";
else:
    mysql_query('COMMIT');
	$imprimir = base64_encode(imprimirCancelacionIngreso($id_consulta_ok));	
    echo "1|$imprimir";
    
    
endif;

function return_linea($linea){
	if(strlen($linea)>0):
		return 'esc_pos_line($printer, "'.$linea.'");';
	endif;
}


function imprimirCancelacionIngreso($id_consulta){
	
	if(!$id_consulta):
		return false;
	endif;
	
	
	global $conexion;
	
	$metodo_sql = "SELECT metodo_pago FROM books_ingresos
				JOIN books_metodo_pago ON books_metodo_pago.id_metodo_pago = books_ingresos.id_metodo_pago
				WHERE books_ingresos.id_consulta = $id_consulta";
				
	$metodo = @mysql_result(@mysql_query($metodo_sql), 0);
	
	
	$sql = "
	SELECT consultas.fecha_hora,clinicas.clinica, usuarios.nombre, pacientes.nombre AS paciente
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
	
	
	$sql = "SELECT*FROM ticket";	
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


    $var.='
	  $printer = esc_pos_open("DENTISXA", "ch-latin-2", false, true); 
	  esc_pos_drawer($printer);
	  esc_pos_align($printer, "center");
      esc_pos_font($printer, "A");
      esc_pos_char_width($printer, "");
      esc_pos_line($printer, "***********************************************");
      esc_pos_line($printer, "TICKET CANCELADO");
      esc_pos_line($printer, "***********************************************");
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
      esc_pos_line($printer, "FOLIO CANCELADO: '.$id_consulta.'");
      esc_pos_line($printer, "DR: '.$aux.'");
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
      esc_pos_line($printer, "***********************************************");
      esc_pos_line($printer, "TICKET CANCELADO");
      esc_pos_line($printer, "***********************************************");
      esc_pos_align($printer, "center");
     esc_pos_line($printer, "");';
	  
	  $var.=$footer_1;
	  $var.=$footer_2;
	  $var.=$footer_3;
	  $var.=$footer_4;
	  $var.=$footer_5;
	  
      $var.='
      esc_pos_line($printer, "");
	  esc_pos_cut($printer);    
      esc_pos_close($printer);
      ';

	  return $var;
	
}
