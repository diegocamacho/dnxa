<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if(!$rfc) exit("Debe escribir un RFC.");
if(!$razon_social) exit("Debe escribir la razón social.");
/*if(!$telefono) exit("Debe escribir un número de teléfono.");
if(!$calle) exit("Debe escribir la calle.");
if(!$n_exterior) exit("Debe escribir el número exterior.");
if(!$colonia) exit("Debe escribir la colonia.");
if(!$cp) exit("Debe escribir el código postal.");
if(!$estado) exit("Debe seleccionar el estado.");
if(!$municipio) exit("Debe escribir un municipio.");
if(!$ciudad) exit("Debe escribir una ciudad.");*/
//if(!$email) exit("Debe escribir una direcci&oacute;n de Email.");
//if(!$referencia) exit("Debe escribir alguna referencia.");

//Formateamos y validamos los valores
$rfc=limpiaStr($rfc,1,1);
$razon_social=limpiaStr($razon_social,1,1);
if($calle){$calle=limpiaStr($calle,1,1);}
if($colonia){$colonia=limpiaStr($colonia,1,1);}
if($municipio){$municipio=limpiaStr($municipio,1,1);}
if($ciudad){$ciudad=limpiaStr($ciudad,1,1);}
/*$referencia=limpiaStr($referencia,1,1);*/

$q=mysql_query("SELECT * FROM clientes WHERE rfc='$rfc' ");
$valida=mysql_num_rows($q);
if($valida>0){
	exit("El RFC ya fué utilizado.");
}else{
	//Insertamos datos
	$sql = "INSERT INTO clientes (rfc,razon_social,representante,email,telefono,celular,calle,n_exterior,n_interior,colonia,cp,estado_pais_cadena,municipio,ciudad,activo)VALUES
	('$rfc','$razon_social','$representante','$email','$telefono','$celular','$calle','$n_exterior','$n_interior','$colonia','$cp','$estado','$municipio','$ciudad',1)";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		exit("Ocurrió un error, intente nuevamente.");
	}
	
}
?>