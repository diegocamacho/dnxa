<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if(!$id_cliente) exit("No llego el identificador del cliente.");
if(!$rfc) exit("Debe escribir un RFC.");
if(!$razon_social) exit("Debe escribir la razón social.");
if(!$telefono) exit("Debe escribir un número de teléfono.");
if(!$calle) exit("Debe escribir la calle.");
if(!$n_exterior) exit("Debe escribir el número exterior.");
if(!$colonia) exit("Debe escribir la colonia.");
if(!$cp) exit("Debe escribir el código postal.");
if(!$estado) exit("Debe seleccionar el estado.");
if(!$municipio) exit("Debe escribir un municipio.");
if(!$ciudad) exit("Debe escribir una ciudad.");
//if(!$email) exit("Debe escribir una direcci&oacute;n de Email.");
//if(!$referencia) exit("Debe escribir alguna referencia.");

//Formateamos y validamos los valores
$rfc=limpiaStr($rfc,1,1);
$razon_social=limpiaStr($razon_social,1,1);
$calle=limpiaStr($calle,1,1);
$colonia=limpiaStr($colonia,1,1);
$municipio=limpiaStr($municipio,1,1);
$ciudad=limpiaStr($ciudad,1,1);
/*$referencia=limpiaStr($referencia,1,1);*/

$q=mysql_query("SELECT * FROM clientes WHERE rfc='$rfc' ");
$valida=mysql_num_rows($q);
if($valida>0){
	//Insertamos datos
	$sql = "UPDATE clientes SET razon_social='$razon_social', representante='$representante', email='$email', telefono='$telefono', celular='$celular', calle='$calle', n_exterior='$n_exterior', n_interior='$n_interior', colonia='$colonia', cp='$cp', estado_pais_cadena='$estado', municipio='$municipio', ciudad='$ciudad' WHERE id_cliente=$id_cliente";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		exit("Ocurrió un error, intente nuevamente.");
	}
}else{
	//Insertamos datos
	$sql = "UPDATE clientes SET rfc='$rfc', razon_social='$razon_social', representante='$representante', email='$email', telefono='$telefono', celular='$celular', calle='$calle', n_exterior='$n_exterior', n_interior='$n_interior', colonia='$colonia', cp='$cp', estado_pais_cadena='$estado', municipio='$municipio', ciudad='$ciudad' WHERE id_cliente=$id_cliente";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		exit("Ocurrió un error, intente nuevamente.");
	}
	
}
?>