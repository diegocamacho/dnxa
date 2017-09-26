<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if(!$id_cuenta) exit("Seleccione la cuenta de salida.");
if(!$id_proveedor) exit("Seleccione el proveedor.");
if(!$id_metodo_pago) exit("Seleccione el metodo de pago.");
if(!$id_tipo_gasto) exit("Seleccione el tipo de gasto.");
if(!$fecha) exit("Seleccione la fecha de la operación.");
if(!$monto) exit("Escriba el monto de la operación.");
//if(!$descripcion) exit("Escriba una descripción para la operación.");

$descripcion=limpiaStr($descripcion,1,1);
$fecha=fechaBase2($fecha);


	//Insertamos datos
	$sql="INSERT INTO books_gastos (id_cuenta,id_proveedor,id_usuario,id_metodo_pago,id_tipo_gasto,fecha_hora_captura,fecha_gasto,monto,referencia) VALUES ('$id_cuenta','$id_proveedor','$s_id_usuario','$id_metodo_pago','$id_tipo_gasto','$fechahora','$fecha','$monto','$descripcion')";
	$q=mysql_query($sql);
	$id_gasto = mysql_insert_id();
	$imprimir = base64_encode(imprimirGasto($id_gasto));

	if($q){
		echo "1|$imprimir";
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}


function imprimirGasto($id_gasto){
	
	global $conexion;
	
	$sql = "
SELECT id_gasto, proveedor, metodo_pago,cuenta_gasto, fecha_gasto, monto, referencia,books_gastos.activo, alias, clinica, usuarios.nombre as doctor
FROM books_gastos 
LEFT JOIN books_proveedores ON books_proveedores.id_proveedor=books_gastos.id_proveedor 
JOIN books_cuentas ON books_cuentas.id_cuenta=books_gastos.id_cuenta 
JOIN clinicas ON clinicas.id_clinica =  books_cuentas.id_empresa
JOIN usuarios ON usuarios.id_usuario = books_gastos.id_usuario
LEFT JOIN books_metodo_pago ON books_metodo_pago.id_metodo_pago=books_gastos.id_metodo_pago 
JOIN books_tipos_gasto ON books_tipos_gasto.id_tipo_gasto=books_gastos.id_tipo_gasto 
WHERE books_gastos.id_gasto = $id_gasto";

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