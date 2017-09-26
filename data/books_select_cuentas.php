<?

include("../includes/db.php");
include("../includes/funciones.php");

if(!$_GET['id_clinica']){ exit("Error de ID");}
$id_clinica=escapar($_GET['id_clinica'],1);
//Cuenta de pago
$sql="SELECT * FROM books_cuentas WHERE id_empresa=$id_clinica AND activo=1";
$q=mysql_query($sql);
$cuentas=array();
while($datos=mysql_fetch_object($q)):
	$cuentas[] = $datos;
endwhile;
$valida_cuentas=count($cuentas);

if($valida_cuentas):
	foreach($cuentas AS $cuenta): 
		$ingresos=dameIngresos($cuenta->id_cuenta);
		$egresos=dameEgresoso($cuenta->id_cuenta);
		$saldo=$ingresos-$egresos;
	?>
		<option value="<?=$cuenta->id_cuenta?>"><?=strtoupper($cuenta->alias)?> <?if($cuenta->eliminable==1): echo "(".strtoupper(dameTipo($cuenta->tipo_cuenta)).")"; endif;?> SALDO: <?=$saldo?></option>
<? 	endforeach;
else: ?>
	<option value="0" >No se han creado cuentas de pago para esta empresa</option>
<? endif; ?>