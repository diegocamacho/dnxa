<?php
	

function return_linea($linea){
	if(strlen($linea)>0):
		return 'esc_pos_line($printer, "'.$linea.'");';
	endif;
}


function imprimirConsulta($id_consulta,$metodo, $codigo, $factura = 1){
	
	global $conexion;
	
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

