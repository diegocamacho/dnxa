<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if(!$id_tipo_ingreso) exit("Seleccione el tipo de ingreso.");
if(!$id_cuenta) exit("Seleccione la cuenta de entrada.");
if(!$id_cliente) exit("Seleccione el cliente.");
if(!$id_metodo_pago) exit("Seleccione el metodo de pago.");

if(!$fecha) exit("Seleccione la fecha de la operación.");
if(!$monto) exit("Escriba el monto de la operación.");
if(!$descripcion) exit("Escriba una descripción para la operación.");

$descripcion=limpiaStr($descripcion,1,1);
$fecha=fechaBase2($fecha);


	//Insertamos datos
	$sql="INSERT INTO books_ingresos (id_cuenta,id_cliente,id_usuario,id_metodo_pago,id_tipo_ingreso,fecha_hora_captura,fecha_ingreso,monto,referencia) VALUES ('$id_cuenta','$id_cliente','$s_id_usuario','$id_metodo_pago','$id_tipo_ingreso','$fechahora','$fecha','$monto','$descripcion')";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}
