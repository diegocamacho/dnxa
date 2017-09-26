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


/***********/

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

/*
include('../includes/db.php');
include('../includes/funciones.php');

echo imprimirCancelacionIngreso(11,'EFECTIVO');
*/

function imprimirCancelacionIngreso($id_consulta,$metodo){
	
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



function imprimirTransferencia($id_transferencia){
	
	global $conexion;
	
	$sql = "
	
SELECT books_transferencias.id_transferencia, usuarios.nombre, books_gastos.fecha_gasto as fecha_transferencia, books_gastos.monto as monto_transferido, books_gastos.referencia as descripcion, bc.alias as cuenta_emisora, br.alias as cuenta_receptora, clinica_emisora.clinica as clinica_emisora, clinica_receptora.clinica as clinica_receptora
FROM books_transferencias
JOIN books_gastos ON books_gastos.id_transferencia = books_transferencias.id_transferencia
JOIN usuarios ON usuarios.id_usuario = books_gastos.id_usuario
JOIN books_cuentas as bc ON bc.id_cuenta = books_transferencias.id_cuenta_emisora
JOIN books_cuentas as br ON br.id_cuenta = books_transferencias.id_cuenta_receptora
JOIN clinicas as clinica_emisora ON clinica_emisora.id_clinica = bc.id_empresa
JOIN clinicas as clinica_receptora ON clinica_receptora.id_clinica = br.id_empresa
WHERE books_transferencias.id_transferencia = $id_transferencia";

	$q = mysql_query($sql);
	$datos = @mysql_fetch_assoc($q);
	
	$fecha_hoy = devuelveFechaHora(date('Y-m-d H:i:s'));
	$id_transferencia = $id_transferencia;
	$usuario = mb_strtoupper($datos['nombre'],'UTF-8');
	$fecha_transferencia = fechaLetraDos($datos['fecha_transferencia']);
	$monto = number_format($datos['monto_transferido'],2);
	$empresa_emisora = mb_strtoupper($datos['clinica_emisora'],'UTF-8');
	$cuenta_emisora = mb_strtoupper($datos['cuenta_emisora'],'UTF-8');
	$empresa_receptora = mb_strtoupper($datos['clinica_receptora'],'UTF-8');
	$cuenta_receptora = mb_strtoupper($datos['cuenta_receptora'],'UTF-8');
	$descripcion = mb_strtoupper($datos['descripcion'],'UTF-8');
	
	
	
    $var.='
	  $printer = esc_pos_open("DENTISXA", "ch-latin-2", false, true); 
	  esc_pos_drawer($printer);
	  esc_pos_align($printer, "center");
      esc_pos_font($printer, "A");
      esc_pos_char_width($printer, "");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, "DENTISXA");
      esc_pos_line($printer, "TRANSFERENCIA ENTRE CUENTAS");
      esc_pos_line($printer, "'.$fecha_hoy.'");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, " ");
	  esc_pos_align($printer, "left");	
      esc_pos_line($printer, "ID: '.$id_transferencia.'");
      esc_pos_line($printer, "USUARIO: '.$usuario.'");
      esc_pos_line($printer, "FECHA: '.$fecha_transferencia.'");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, "MONTO: '.$monto.'");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, "EMPRESA EMISORA:");
      esc_pos_line($printer, "'.$empresa_emisora.'");
      esc_pos_line($printer, "CUENTA EMISORA:");
      esc_pos_line($printer, "'.$cuenta_emisora.'");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, "EMPRESA RECEPTORA:");
      esc_pos_line($printer, "'.$empresa_receptora.'");
      esc_pos_line($printer, "CUENTA RECEPTORA:");
      esc_pos_line($printer, "'.$cuenta_receptora.'");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, "DESCRIPCION:");
      esc_pos_line($printer, "'.$descripcion.'");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, " ");
	  esc_pos_align($printer, "center");
      esc_pos_line($printer, "_________________________________");
      esc_pos_line($printer, "RESPONSABLE");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, " ");
	  esc_pos_cut($printer);    
      esc_pos_close($printer);
      ';
	  
	  
	  return $var;

}


function imprimirGasto($id_gasto){
	
	global $conexion;
	
	$sql = "
SELECT id_gasto, proveedor, metodo_pago,cuenta_gasto, fecha_gasto, monto, referencia,books_gastos.activo, alias, clinica, usuarios.nombre as doctor
FROM books_gastos 
LEFT JOIN books_proveedores ON books_proveedores.id_proveedor=books_gastos.id_proveedor 
JOIN books_cuentas ON books_cuentas.id_cuenta=books_gastos.id_cuenta 
JOIN clinicas ON clinicas.id_clinica =  books_gastos.id_cuenta 
JOIN usuarios ON usuarios.id_usuario = books_gastos.id_usuario
LEFT JOIN books_metodo_pago ON books_metodo_pago.id_metodo_pago=books_gastos.id_metodo_pago 
JOIN books_tipos_gasto ON books_tipos_gasto.id_tipo_gasto=books_gastos.id_tipo_gasto 
WHERE books_gastos.id_cuenta=1 AND books_gastos.id_metodo_pago=1 AND books_gastos.id_gasto = $id_gasto";

	$q = mysql_query($sql);
	$datos = @mysql_fetch_assoc($q);
	
	
	$id_gasto = $id_gasto;
	$usuario = mb_strtoupper($datos['doctor'],'UTF-8');
	$monto_gasto = number_format($datos['monto'],2);
	$metodo_pago = mb_strtoupper($datos['metodo_pago'],'UTF-8');
	$cuenta_pago = mb_strtoupper($datos['alias'],'UTF-8');
	$fecha_gasto = fechaLetraDos($datos['fecha_gasto']);
	$fecha_hoy = devuelveFechaHora(date('Y-m-d H:i:s'));
	$tipo = mb_strtoupper($datos['cuenta_gasto'],'UTF-8');
	$empresa = mb_strtoupper($datos['clinica'],'UTF-8');
	$proveedor = mb_strtoupper($datos['proveedor'],'UTF-8');
	$descripcion = mb_strtoupper($datos['referencia'],'UTF-8');
	
    $var.='
	  $printer = esc_pos_open("DENTISXA", "ch-latin-2", false, true); 
	  esc_pos_drawer($printer);
	  esc_pos_align($printer, "center");
      esc_pos_font($printer, "A");
      esc_pos_char_width($printer, "");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, "DENTISXA");
      esc_pos_line($printer, "COMPROBANTE DE GASTO");
      esc_pos_line($printer, "'.$fecha_hoy.'");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, " ");
	  esc_pos_align($printer, "left");	
      esc_pos_line($printer, "ID: '.$id_gasto.'");
      esc_pos_line($printer, "USUARIO: '.$usuario.'");
      esc_pos_line($printer, "MONTO: '.$monto_gasto.'");
      esc_pos_line($printer, "FECHA DE GASTO: '.$fecha_gasto.'");
      esc_pos_line($printer, "METODO DE PAGO: '.$metodo_pago.'");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, "EMPRESA:");
      esc_pos_line($printer, "'.$empresa.'");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, "TIPO DE GASTO:");
      esc_pos_line($printer, "'.$tipo.'");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, "PROVEEDOR:");
      esc_pos_line($printer, "'.$proveedor.'");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, "DESCRIPCION:");
      esc_pos_line($printer, "'.$descripcion.'");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, " ");
	  esc_pos_align($printer, "center");
      esc_pos_line($printer, "_________________________________");
      esc_pos_line($printer, "RESPONSABLE");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, " ");
      esc_pos_line($printer, " ");
	  esc_pos_cut($printer);    
      esc_pos_close($printer);
      ';
	  
	  
	  return $var;

}


