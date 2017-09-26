<?php
	
	
$impresion = base64_encode(imprimir_mesa($id_venta,0,123456,'EFECTIVO'));


echo $impresion;
	
	
function return_linea($linea){
	if(strlen(trim($linea))>0):
		return 'esc_pos_line($printer, "'.$linea.'");';
	endif;
}

function imprimir_mesa($id_venta,$factura=false,$codigo=false,$metodo=false){
	
	include('includes/db.php');
	
	$sql = "SELECT*FROM ticket";	
	
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

	


	$fecha_hora_ticket = date('d-m-Y h:i a');	


    $var.='
	  $printer = esc_pos_open("DENTISXA", "ch-latin-2", false, true); 
	  esc_pos_drawer($printer);
	  esc_pos_align($printer, "center");
      esc_pos_font($printer, "A");
      esc_pos_char_width($printer, "");
      esc_pos_line($printer, "DENTISXA TACUBAYA");';

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
      esc_pos_line($printer, "'.$fecha_hora_ticket.'");
      esc_pos_line($printer, "FOLIO: '._ID_.'");
      esc_pos_line($printer, "AUX: '._QUIEN_IMPRIME_.'");
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


