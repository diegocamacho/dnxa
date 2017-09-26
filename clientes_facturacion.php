<?
$sql="SELECT * FROM clientes
WHERE activo = 1
ORDER BY rfc ASC";
$q=mysql_query($sql);
$clientes = array();
while($datos=mysql_fetch_object($q)):
	$clientes[] = $datos;
endwhile;
$val=count($clientes);



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
			<!-- Confirmación -->
			  <? if($_GET['msg']==1){ ?>
			  		<br>
			  		<div class="alert alert-dismissable alert-success">
				  		<button type="button" class="close" data-dismiss="alert">×</button>
				  		<p>El cliente se ha agregado</p>
				  	</div>
			  <? }if($_GET['msg']==2){ ?>
			  		<br>
			  		<div class="alert alert-dismissable alert-info">
				  		<button type="button" class="close" data-dismiss="alert">×</button>
				  		<p>El cliente se ha editado</p>
				  	</div>
			  <? } ?>
			  
			  <? if($_GET['m']){ ?>
			  		<br>
			  		<div class="alert alert-dismissable alert-danger">
				  		<p><?=base64_decode($_GET['m'])?></p>
				  	</div>
			  <? } ?>
			  
			  <!-- Contenido -->
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet light  portlet-fit">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-briefcase font-dark"></i>
						<span class="caption-subject font-dark bold uppercase">Clientes Facturación</span>
					</div>
					<div class="actions btn-set">
						<!--<a href="Plantilla_Dentisxa_Corp.xlsx" class="btn btn-sm red-thunderbird">
							<i class="fa fa-file-excel-o"></i> Descargar Excel </a>&nbsp;&nbsp;
						<a href="javascript:;" class="btn btn-sm green-jungle " data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#importarExcel"><i class="fa fa-file-excel-o"></i> Importar Excel </a>&nbsp;&nbsp;-->
						<a href="?Modulo=NuevoCliente" class="btn btn-sm blue-chambray "><i class="fa fa-plus"></i> Agregar cliente </a>
					</div>
				</div>
				<div class="portlet-body">
					<? if($val>0): ?>
					<table class="table table-striped table-bordered table-hover">
						<thead>
					        <tr>
					          <th width="85">RFC</th>
					          <th>Cliente</th>
					          <th>Representante</th>
					          <th>Teléfono</th>
					          <th>Celular</th>
					          <!--<th>Email</th>-->
					          <th width="240"></th>
					        </tr>
					      </thead>
					      <tbody>
						    <? foreach($clientes as $cliente): 
							    $id_cliente=$cliente->id_cliente;
							    $sql="SELECT id_paciente FROM pacientes WHERE id_cliente=$id_cliente";
							    $q=mysql_query($sql);
							    $pacientes=mysql_num_rows($q);
						    ?>  
					        <tr>
								<td><b><?=$cliente->rfc?></b></td>
								<td><?=$cliente->razon_social?></td>
								<td><?=$cliente->representante?></td>
								<td><?=$cliente->telefono?></td>
								<td><?=$cliente->celular?></td>
								<!--<td><?=$cliente->email?></td>-->
								<td align="right">
									<a role="button" class="btn blue btn-xs btn_<?=$cliente->id_cliente?>" href="?Modulo=EditaCliente&id=<?=$cliente->id_cliente?>">Editar</a>
									<a role="button" class="btn red btn-xs btn_<?=$cliente->id_cliente?>" onclick="javascript:Desactiva(<?=$cliente->id_cliente?>)">Eliminar</a>
									<!--<a role="button" class="btn  default btn-xs btn_<?=$cliente->id_cliente?>" onclick="javascript:s(<?=$cliente->id_cliente?>)">Historial</a>-->
								</td>
					        </tr>
					        <? endforeach; ?>
					        
					      </tbody>
					</table>
					<? else: ?>
					<div class="alert alert-dismissable alert-warning">
				  		<button type="button" class="close" data-dismiss="alert">×</button>
				  		<p>Aún no se han creado clientes</p>
				  	</div>
					<? endif; ?>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
	</div>
</div>




<!-- Modal -->
<div class="modal fade" id="importarExcel">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
				<h4 class="modal-title">Importar Plantilla de Excel</h4>
			</div>
			
			<form id="frm_guarda" class="form-horizontal" method="post" enctype="multipart/form-data"  action="uploader_masivo/excel/uploader.php">
				<div class="modal-body">
					<div class="alert alert-danger oculto" role="alert" id="msg_error"></div>
					
					<div class="form-group" style="margin-top: 20px;">
						<label class="control-label col-md-2">&nbsp;</label>
						<div class="col-md-10">
							<div class="fileinput fileinput-new" data-provides="fileinput">
            	                <div class="input-group input-large">
            	                    <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
            	                        <i class="fa fa-file fileinput-exists"></i>&nbsp;
            	                        <span class="fileinput-filename"> </span>
            	                    </div>
            	                    <span class="input-group-addon btn default btn-file">
            	                        <span class="fileinput-new"> Seleccionar archivo </span>
            	                        <span class="fileinput-exists"> Cambiar </span>
            	                        <input type="file" name="archivo" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"> </span>
            	                    <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Eliminar </a>
            	                </div>
            	            </div>
						</div>
					</div>
					
				</div>
				<div class="modal-footer">
					<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load" width="25" class="oculto" />
					<button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
					<!--<input type="submit" value="ok"/>-->
					<button type="submit" class="btn btn-success btn_ac">Importar</button>
				</div>
			</form>
		</div>
	</div>
</div>





<!--- Js -->
<script>
$(function(){
	
});

function Desactiva(id){
	
	if(confirm('¿Desea eliminar el cliente permanentemente?')){
	
		$(".btn_"+id+"").hide();
		$("#load_"+id+"").show();
		$.post('ac/activa_desactiva_cliente_fact.php', { tipo: "0", id_cliente: id },function(data){
			if(data==1){
				window.open("?Modulo=Clientes", "_self");
			}else{
				$("#load_"+id+"").hide();
				$(".btn_"+id+"").show();
				alert(data);
			}
		});
	
	}
	
}
function Activa(id){
	$(".btn_"+id+"").hide();
	$("#load_"+id+"").show();
	$.post('ac/activa_desactiva_cliente_fact.php', { tipo: "1", id_cliente: id },function(data){
		if(data==1){
			window.open("?Modulo=Clientes", "_self");
		}else{
			$("#load_"+id+"").hide();
			$(".btn_"+id+"").show();
			alert(data);
		}
	});
}
</script>