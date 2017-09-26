<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if(!$id_empresa_1) exit("Seleccione la empresa de salida.");
if(!$id_cuenta_1) exit("Seleccione la cuenta de salida.");
if(!$id_empresa_2) exit("Seleccione la empresa de entrada.");
if(!$id_cuenta_2) exit("Seleccione la cuenta de entrada.");
//if(!$descripcion) exit("Escriba una descripción para la operación.");
if(!$monto) exit("Escriba el monto de la operación.");

//Validamos saldos
$ingresos=dameIngresos($id_cuenta_1);
$egresos=dameEgresoso($id_cuenta_1);
$saldo=$ingresos-$egresos;

if($saldo<1) exit("La cuenta de salida no tiene saldo para hacer una operación");
if($saldo<$monto) exit("La cuenta de salida no tiene suficiente saldo para hacer la operación");

//Limpiamos campos
if($descripcion):
	$descripcion=limpiaStr($descripcion,1,1);
endif;

//Insertamos datos
mysql_query('BEGIN');

$sq=@mysql_query("INSERT INTO books_transferencias (id_cuenta_emisora,id_cuenta_receptora)VALUES('$id_cuenta_1','$id_cuenta_2')");
if(!$sq) $error = true;
$id_transferencia=mysql_insert_id();

$sq=@mysql_query("INSERT INTO books_ingresos (id_cuenta,id_cuenta_emisora,id_usuario,id_tipo_ingreso,id_transferencia,fecha_hora_captura,fecha_ingreso,monto,referencia)VALUES('$id_cuenta_2','$id_cuenta_1','$s_id_usuario','1','$id_transferencia','$fechahora','$fechahora','$monto','$descripcion')");
if(!$sq) $error = true;
	
$sq=@mysql_query("INSERT INTO books_gastos (id_cuenta,id_cuenta_receptora,id_usuario,id_tipo_gasto,id_transferencia,fecha_hora_captura,fecha_gasto,monto,referencia) VALUES ('$id_cuenta_1','$id_cuenta_2','$s_id_usuario','1','$id_transferencia','$fechahora','$fechahora','$monto','$descripcion')");
if(!$sq) $error = true;

if($error):
    mysql_query('ROLLBACK');
    echo "Ocurrió un error, intente más tarde.";
else:
    mysql_query('COMMIT');
	$imprimir = base64_encode(imprimirTransferencia($id_transferencia));
    echo "1|$imprimir";
endif;


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
