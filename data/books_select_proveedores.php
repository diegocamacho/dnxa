<?

include("../includes/db.php");
include("../includes/funciones.php");

if(!$_GET['id_clinica']){ exit("Error de ID");}
$id_clinica=escapar($_GET['id_clinica'],1);
//Doctores
$sql="SELECT * FROM books_proveedores WHERE id_clinica=$id_clinica OR id_clinica=0";
$q=mysql_query($sql);
$proveedores=array();
while($datos=mysql_fetch_object($q)):
	$proveedores[] = $datos;
endwhile;
$valida=count($proveedores);
?>
<label for="" class="col-md-3 control-label">Proveedor</label>
<div class="col-md-9">
	<select class="form-control" data-show-subtext="false" name="id_proveedor" id="id_proveedor">
		<? if($valida): ?>
		<option>Seleccione un doctor</option>
		<? foreach($proveedores AS $proveedor): ?>
			<option value="<?=$proveedor->id_proveedor?>"><?=$proveedor->proveedor?></option>
		<? endforeach; ?>
		<? else: ?>
		<option>No se encontraron proveedores para esta clínica/empresa</option>
		<? endif; ?>
	</select>
</div>