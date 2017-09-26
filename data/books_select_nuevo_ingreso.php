<?

include("../includes/db.php");
include("../includes/funciones.php");

if(!$_GET['id_clinica']){ exit("Error de ID");}
$id_clinica=escapar($_GET['id_clinica'],1);
//Proveedores
$sql="SELECT * FROM books_clientes WHERE id_empresa=$id_clinica OR id_empresa=0";
$q=mysql_query($sql);
$proveedores=array();
while($datos=mysql_fetch_object($q)):
	$proveedores[] = $datos;
endwhile;
$valida=count($proveedores);

//Cuenta de pago
$sql="SELECT * FROM books_cuentas WHERE id_empresa=$id_clinica AND activo=1";
$q=mysql_query($sql);
$cuentas=array();
while($datos=mysql_fetch_object($q)):
	$cuentas[] = $datos;
endwhile;
$valida_cuentas=count($cuentas);
?>

<div class="form-group">

	<label for="" class="col-md-3 control-label">Cliente</label>
	<div class="col-md-9">
		<? if($valida): ?>
		<select class="form-control" data-show-subtext="false" name="id_cliente">
			<option value="0">Seleccione un cliente</option>
			<? foreach($proveedores AS $proveedor): ?>
				<option value="<?=$proveedor->id_cliente?>"><?=$proveedor->cliente?></option>
			<? endforeach; ?>
		</select>
		<? else: ?>
		<div class="alert alert-danger" role="alert" >No se han cargado clientes para esta empresa</div>
	<? endif; ?>
	</div>
	
</div>


<div class="form-group">
						
	<label for="" class="col-md-3 control-label">Cuenta de pago</label>
	<div class="col-md-9">
		<? if($valida_cuentas): ?>
		<select class="form-control" data-show-subtext="false" name="id_cuenta">
			<option value="0">Seleccione una cuenta</option>
			<? foreach($cuentas AS $cuenta): ?>
				<option value="<?=$cuenta->id_cuenta?>"><?=$cuenta->alias?> (<?=dameTipo($cuenta->tipo_cuenta);?>)</option>
			<? endforeach; ?>	
		</select>
		<? else: ?>
			<div class="alert alert-danger" role="alert" >No se han creado cuentas de pago para esta empresa</div>
		<? endif; ?>
	</div>
	
</div>