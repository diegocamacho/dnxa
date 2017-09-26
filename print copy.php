<?php
	
	
$impresion = base64_encode(imprimir_mesa($id_venta,0,123456,'EFECTIVO'));


echo $impresion;
	

function imprimir_mesa($id_venta,$factura=false,$codigo=false,$metodo=false){
	

	
	$fecha_hora_ticket = date('d-m-Y h:i a');	

    $var.='
	  $printer = esc_pos_open("DENTISXA", "ch-latin-2", false, true); 
	  esc_pos_drawer($printer);
	  esc_pos_align($printer, "center");
      esc_pos_font($printer, "A");
      esc_pos_char_width($printer, "");
      esc_pos_line($printer, "---- CABECERA ----");
      esc_pos_line($printer, "---- LINEA 1 ----");
      esc_pos_line($printer, "---- LINEA 2 ----");
      esc_pos_line($printer, "---- LINEA 3 ----");
      esc_pos_line($printer, "---- LINEA 4 ----");
      esc_pos_line($printer, "dentisxa.mx");
      esc_pos_line($printer, "5555112134");
      esc_pos_line($printer, "'.$fecha_hora_ticket.'");
      esc_pos_line($printer, "FOLIO: A1234");
      esc_pos_line($printer, "AUX: SAN JOHN DOE");
      esc_pos_line($printer, "------------------------------------------------");
	  esc_pos_align($printer, "left");	
      esc_pos_line($printer, "DESC.                 CANT     UNIT       SUBT");
      esc_pos_line($printer, "PRODUCTO1                1   500.00     500.00");
      esc_pos_line($printer, "PRODUCTO2                1   500.00     500.00");
      esc_pos_line($printer, "PRODUCTO3                1   500.00     500.00");
      ';

		$detalle = '
		esc_pos_line($printer, "METODO: 01 EFECTIVO");
		';
		
			$fact = 'esc_pos_line($printer, "GENERE SU FACTURA EN: www.dentisxa.mx");';		
			$fact.= 'esc_pos_line($printer, "CODIGO DE FACTURACION: 123456");';		
			$fact.= 'esc_pos_line($printer, "CUENTA CON 5 DIAS A PARTIR DE LA FECHA");';			 	
			$fact.= 'esc_pos_line($printer, "DE ESTE TICKET PARA GENERAR SU FACTURA.");';
			
	$var.='
      esc_pos_line($printer, "------------------------------------------------");
      esc_pos_align($printer, "right");
	  esc_pos_emphasize($printer,true);
      esc_pos_line($printer, "TOTAL                                  1500.00");
	  '.$detalle.'
	  esc_pos_emphasize($printer,false);
      esc_pos_line($printer, "------------------------------------------------");
      esc_pos_font($printer, "A");
      esc_pos_align($printer, "center");
      '.$fact.'
      esc_pos_align($printer, "center");
     esc_pos_line($printer, "");';

      $var.='
      esc_pos_line($printer, "");
	  '.$status.'
	  esc_pos_cut($printer);    
      esc_pos_close($printer);
      ';

	  return $var;
	
}


