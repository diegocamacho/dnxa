<?
$sql="SELECT books_clientes.*, clinicas.clinica, planes.plan FROM books_clientes 
LEFT JOIN clinicas ON clinicas.id_clinica=books_clientes.id_empresa
LEFT JOIN planes ON planes.id_plan=books_clientes.id_plan
WHERE books_clientes.activo = 1
ORDER BY clinica ASC";
$q=mysql_query($sql);
$clientes = array();
while($datos=mysql_fetch_object($q)):
	$clientes[] = $datos;
endwhile;
$val=count($clientes);


if(!file_exists('uploader_masivo/excel/json.txt')):
		exit('Error, archivo con datos faltante.');
endif;

$datos = file_get_contents('uploader_masivo/excel/json.txt');
$json = json_decode($datos,true);


function dameEmpresa($id_cliente){

	global $conexion;
	if(!$id_cliente){
		return false;
	}
	
	$sql = "SELECT cliente,fecha_final_plan, plan FROM books_clientes JOIN planes ON planes.id_plan = books_clientes.id_plan 
	WHERE id_cliente = $id_cliente";	
	$q = mysql_query($sql);
	return @mysql_fetch_assoc($q);
	

}
?>
<style>
.oculto{
	display: none;
}
.link{
	cursor: pointer;
}
</style>

<div class="page-content-inner">
	<div class="row">
		<div class="col-md-12">
			<!-- Confirmación -->			  <!-- Contenido -->
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet light  portlet-fit">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-user font-dark"></i>
						<span class="caption-subject font-dark bold uppercase">Importación de pacientes</span>
					</div>
					<div class="actions btn-set">
						
						<select class="form-control" id="id_canal" name="id_canal">
							<option value="0" disabled selected >Seleccione Canal</option>
<?
	$sql = "SELECT id_canal, canal FROM canales WHERE activo = 1";
	$q = mysql_query($sql);
	while($ft = mysql_fetch_assoc($q)):
?>
							<option value="<?=$ft['id_canal']?>"><?=$ft['canal']?></option>
<?
	endwhile;	
?>
						</select>
					</div>
				</div>
				<div class="portlet-body">
					<form id="form">
						<table class="table table-striped table-bordered table-hover">
							<thead>
						        <tr>
						          <th>Nombre</th>
						          <th>Teléfono</th>
						          <th>Email</th>
						          <th>Empresa</th>
						          <th>Plan</th>
						          <th>Expiración</th>
						        </tr>
						      </thead>
						      <tbody>
						        <?
							    $cuantos_real = 0;
							    $cuantos = count($json['nombre']);
							    for($x=1;$x<=$cuantos;$x++):
							    if(strlen($json['nombre'][$x])>0):
							    	$cuantos_real++;
							    	$datos_empresa = dameEmpresa($json['id_cliente'][$x]);
							    ?>
						        <tr>
									<td><?=$json['nombre'][$x]?><input type="hidden" name="nombre[<?=$x?>]" value="<?=$json['nombre'][$x]?>"/></td>
									<td><?=$json['tel'][$x]?><input type="hidden" name="tel[<?=$x?>]" value="<?=$json['tel'][$x]?>"/></td>
									<td><?=$json['email'][$x]?><input type="hidden" name="email[<?=$x?>]" value="<?=$json['email'][$x]?>"/></td>
									<td><?=$datos_empresa['cliente']?><input type="hidden" name="id_cliente[<?=$x?>]" value="<?=$json['id_cliente'][$x]?>"/></td>
									<td><?=$datos_empresa['plan']?></td>
									<td><?=fechaLetra($datos_empresa['fecha_final_plan'])?></td>
						        </tr>
							    <?
								 endif;
								endfor;
								?>
						      </tbody>
						</table>
					</form>
					<div class="row">
						<div class="col-md-12 text-right">
							<a role="button" class="btn green-jungle" id="guardar_nuevos">Guardar Pacientes Nuevos</a>
						</div>
					</div>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
	</div>
</div>
<script>
$(function() {

	$('#guardar_nuevos').click(function() {
	
		var canal = $('#id_canal').val();
		var canal_txt = $('#id_canal option:selected').text();

		if(!canal){
			swal("Seleccione un canal antes de continuar.");
			return false;
		}
		
		swal({
		  title: "¿Seguro?",
		  text: "A continuación se crearán <?=$cuantos_real?> pacientes nuevos en el canal "+canal_txt+".",
		  type: "warning",
		  showCancelButton: true,
		  confirmButtonColor: "#DD6B55",
		  confirmButtonText: "Si, señor",
		  closeOnConfirm: true
		},
		function(){
			$('#guardar_nuevos').hide();
			var datos = $('#form').serialize()+'&id_canal='+$('#id_canal').val();
			$.post('ac/pacientes_masivo.php',datos,function(data) {
					console.log(data);
					if(data==1){
						swal({
							title: "¡Datos Importados!",
							text: "Todos los pacientes han sido creados con éxito.",
							type: "success",
							confirmButtonText: "¡OK!",
							showCancelButton: false,
							closeOnConfirm: false,
							showLoaderOnConfirm: true
						},function(){
								window.location = "index.php?Modulo=Pacientes";
						});	
					}else{			
						alert(data);
						$('#guardar_nuevos').show();
					}
			});
			
		});
		
	
	});

});
</script>

