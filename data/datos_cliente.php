<?

include("../includes/db.php");
include("../includes/funciones.php");

extract($_GET);
$rfc = $_GET['rfc'];
if(!$rfc) exit("Ingrese su RFC.");

$rfc=limpiaStr($rfc,1,1);
	
	$sq="SELECT * FROM clientes WHERE rfc='$rfc'";
	$q=mysql_query($sq);
	$valida=mysql_num_rows($q);
	
	if($valida){
		$dat=mysql_fetch_assoc($q);
		
		$id_cliente=$dat['id_cliente'];
		$razon_social=$dat['razon_social'];
		$email=$dat['email'];
		$telefono=$dat['telefono'];
		$celular=$dat['celular'];
		$direccion=$dat['direccion1'];
		$calle=$dat['calle'];
		$n_exterior=$dat['n_exterior'];
		$n_interior=$dat['n_interior'];
		$colonia=$dat['colonia'];
		$cp=$dat['cp'];
		$municipio=$dat['municipio'];
		$ciudad=$dat['ciudad'];
		$estado=$dat['estado_pais_cadena'];
		
		echo "1|$razon_social|$email|$telefono|$celular|$calle|$n_exterior|$n_interior|$colonia|$cp|$estado|$municipio|$ciudad|$id_cliente";
	}else{
		exit("2");
	}
?>