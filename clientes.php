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


$sql="SELECT * FROM clinicas WHERE activo=1";
$q=mysql_query($sql);
$clinicas = array();
while($datos=mysql_fetch_object($q)):
	$clinicas[] = $datos;
endwhile;

$sql="SELECT * FROM planes ORDER BY plan ASC";
$q=mysql_query($sql);
$planes = array();
while($datos=mysql_fetch_object($q)):
	$planes[] = $datos;
endwhile;
$val_planes=count($planes);

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
						<span class="caption-subject font-dark bold uppercase">Clientes</span>
					</div>
					<div class="actions btn-set">
						<a href="Plantilla_Dentisxa_Corp.xlsx" class="btn btn-sm red-thunderbird">
							<i class="fa fa-file-excel-o"></i> Descargar Excel </a>&nbsp;&nbsp;
						<a href="javascript:;" class="btn btn-sm green-jungle " data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#importarExcel"><i class="fa fa-file-excel-o"></i> Importar Excel </a>&nbsp;&nbsp;
						<a href="javascript:;" class="btn btn-sm blue-chambray " data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#nuevoCliente"><i class="fa fa-plus"></i> Agregar cliente </a>
					</div>
				</div>
				<div class="portlet-body">
					<? if($val>0): ?>
					<table class="table table-striped table-bordered table-hover">
						<thead>
					        <tr>
					          <th width="85">ID Cliente</th>
					          <th>Cliente</th>
					          <th>Plan</th>
					          <th>Empresa</th>
					          <th>Teléfono</th>
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
								<td><b><?=$cliente->id_cliente?></b></td>
								<td><?=$cliente->cliente?></td>
								<td><? 
									if($cliente->plan){ 
										unset($span);
										unset($span2);
										if($cliente->fecha_final_plan<date('Y-m-d')):
										 $span = '<span style="color:red">';
										 $span2 = '</span>';
										endif;
										echo $cliente->plan." <br>$span(".fechaLetraDos($cliente->fecha_inicio_plan)." al ".fechaLetraDos($cliente->fecha_final_plan).") $span2<br>Pacientes: ".$pacientes; 
									}else{ 
										echo "N/A"; 
									}
									?></td>
								<td><? if($cliente->id_empresa==0): echo "TODAS"; else: echo $cliente->clinica; endif;?></td>
								<td><?=$cliente->telefono?></td>
								<!--<td><?=$cliente->email?></td>-->
								<td align="right">
									<a role="button" class="btn blue btn-xs btn_<?=$cliente->id_cliente?>" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#editaCliente" data-id="<?=$cliente->id_cliente?>">Editar</a>
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
<div class="modal fade" id="nuevoCliente">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Nuevo cliente</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="msg_error"></div>
<!--Formulario -->
		<form id="frm_guarda" class="form-horizontal">
			
			<div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Nombre</label>
				<div class="col-md-9">
					<input type="text" maxlength="128" class="form-control dat" name="nombre" id="nuevo_nombre" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="telefono" class="col-md-3 control-label">Teléfono</label>
				<div class="col-md-9">
					<input type="text" maxlength="10" class="form-control dat" name="telefono" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="direccion" class="col-md-3 control-label">Email</label>
				<div class="col-md-9">
					<input type="text" class="form-control dat" name="email" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="direccion" class="col-md-3 control-label">Empresa</label>
				<div class="col-md-9">
					<select class="form-control" name="id_clinica" >
						<option value="0">Seleccione una</option>
						<? foreach($clinicas as $clinica): ?>
						<option value="<?=$clinica->id_clinica?>"><?=$clinica->clinica?></option>
						<? endforeach; ?>
					</select>
					<p class="help-block">*Si se deja en blanco este proveedor podrá tener operaciones con todas las empresas.</p>
				</div>
			</div>
			
			<? if($val_planes): ?>
			<hr>
			<h4>Planes Corporativos</h4>
			<br>
			<div class="form-group">
				<label for="direccion" class="col-md-3 control-label">Plan</label>
				<div class="col-md-9">
					<select class="form-control" name="id_plan" >
						<option value="0">Seleccione una</option>
						<? foreach($planes as $plan): ?>
						<option value="<?=$plan->id_plan?>"><?=$plan->plan?></option>
						<? endforeach; ?>
					</select>
					<p class="help-block">*Sólo en caso de que el cliente tenga un plan activo.</p>
				</div>
			</div>
			
			<div class="form-group">
				<label class="control-label col-md-3">Período de fechas</label>
                <div class="col-md-9">
                	<div class="input-group input-medium date-picker input-daterange" data-date="01/01/2017" data-date-format="yyyy-mm-dd">
                    	<input type="text" class="form-control" name="fecha1">
                        <span class="input-group-addon"> a </span>
                        <input type="text" class="form-control" name="fecha2"> 
					</div>
				</div>
			</div>
			<? endif; ?>

		</form>
		      
      </div>
      <div class="modal-footer">
      	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load" width="25" class="oculto" />
        <button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn_ac" onclick="nuevoCliente()">Guardar Cliente</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->




<!-- Modal -->
<div class="modal fade" id="editaCliente">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Edita Cliente</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="msg_error2"></div>
<!-- Loader -->
		<div class="row oculto" id="load_big">
			<div class="col-md-12 text-center" >
				<img src="assets/global/img/ajax-loading.gif" border="0"  />
			</div>
		</div>
<!--Formulario -->
		<form id="frm_edita" class="form-horizontal">
			
			<div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Nombre</label>
				<div class="col-md-9">
					<input type="text" maxlength="64" class="form-control edit" id="nombre" name="nombre" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="telefono" class="col-md-3 control-label">Teléfono</label>
				<div class="col-md-9">
					<input type="text" maxlength="10" class="form-control dat" name="telefono" id="telefono" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="direccion" class="col-md-3 control-label">Email</label>
				<div class="col-md-9">
					<input type="text" class="form-control dat" name="email" id="email" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="direccion" class="col-md-3 control-label">Empresa</label>
				<div class="col-md-9">
					<select class="form-control" name="id_clinica" id="id_clinica" >
						<option value="0">Todas</option>
						<? foreach($clinicas as $clinica): ?>
						<option value="<?=$clinica->id_clinica?>"><?=$clinica->clinica?></option>
						<? endforeach; ?>
					</select>
					<p class="help-block">*Si se deja en blanco este proveedor podrá tener operaciones con todas las empresas.</p>
				</div>
			</div>
			
			<? if($val_planes): ?>
			<hr>
			<h4>Planes Corporativos</h4>
			<br>
			<div class="form-group">
				<label for="direccion" class="col-md-3 control-label">Plan</label>
				<div class="col-md-9">
					<select class="form-control" name="id_plan" id="id_plan">
						<option value="0">Seleccione una</option>
						<? foreach($planes as $plan): ?>
						<option value="<?=$plan->id_plan?>"><?=$plan->plan?></option>
						<? endforeach; ?>
					</select>
					<p class="help-block">*Sólo en caso de que el cliente tenga un plan activo.</p>
				</div>
			</div>
			
			<div class="form-group">
				<label class="control-label col-md-3">Período de fechas</label>
                <div class="col-md-9">
                	<div class="input-group input-medium date-picker input-daterange" data-date="01/01/2017" data-date-format="yyyy-mm-dd">
                    	<input type="text" class="form-control" name="fecha1" id="fecha1">
                        <span class="input-group-addon"> a </span>
                        <input type="text" class="form-control" name="fecha2" id="fecha2"> 
					</div>
				</div>
			</div>
			<? endif; ?>
			
			<input type="hidden" name="id_cliente" id="id_cliente" />
		</form>
		      
      </div>
      <div class="modal-footer">      	
      	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load2" width="25" class="oculto" />
        <button type="button" class="btn btn-default btn_ac btn-modal" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn_ac btn-modal" onclick="editaCliente()">Actualizar Cliente</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->






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
	
	//$('#color').minicolors();

	$(document).on('click', '[data-id]', function () {
		$('.edit').val("");
		$('.btn-modal').hide();
		$('#frm_edita').hide();
		$('#load_big').show();
	    var data_id = $(this).attr('data-id');
	    $.ajax({
	   	url: "data/cliente.php",
	   	data: 'id='+data_id,
	   	success: function(data){
		   	
	   		var datos = data.split('|');
	   		$('#nombre').val(datos[0]);
	   		$('#telefono').val(datos[1]);
	   		$('#email').val(datos[2]);
	   		$('#id_clinica').val(datos[3]);
	   		$('#id_plan').val(datos[4]);
	   		$('#fecha1').val(datos[5]);
	   		$('#fecha2').val(datos[6]);
	   		$('#id_cliente').val(data_id);
	   		
	   		$('#load_big').hide();
	   		$('#frm_edita').show();
	   		$('.btn-modal').show();
	  	
	   	},
	   	cache: false
	   });
	});
	
	$('#nuevoProveedor').on('shown.bs.modal',function(e){
		$('#nuevo_nombre').focus();
	});
	
	$('#nuevoProveedor').on('hidden.bs.modal',function(e){
		$('.dat').val("");
		$('#msg_error2').hide();
		$('#msg_error').hide();
	});
	
	$('#editaProveedor').on('hidden.bs.modal',function(e){
		$('.edit').val("");
		$('#msg_error2').hide();
		$('#msg_error').hide();
	});
	/*
	$('form').submit(function(e){
		e.preventDefault();	
	});*/
});

function editaCliente(){
	$('#msg_error2').hide('Fast');
	$('.btn_ac').hide();
	$('#load2').show();
	var datos=$('#frm_edita').serialize();
	$.post('ac/edita_cliente.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Clientes&msg=2", "_self");
	    }else{
	    	$('#load2').hide();
			$('.btn').show();
			$('#msg_error2').html(data);
			$('#msg_error2').show('Fast');
	    }
	});
}
function Desactiva(id){
	
	if(confirm('¿Desea eliminar el cliente permanentemente?')){
	
		$(".btn_"+id+"").hide();
		$("#load_"+id+"").show();
		$.post('ac/activa_desactiva_cliente.php', { tipo: "0", id_cliente: id },function(data){
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
	$.post('ac/activa_desactiva_cliente.php', { tipo: "1", id_cliente: id },function(data){
		if(data==1){
			window.open("?Modulo=Clientes", "_self");
		}else{
			$("#load_"+id+"").hide();
			$(".btn_"+id+"").show();
			alert(data);
		}
	});
}
function nuevoCliente(){
	$('#msg_error').hide('Fast');
	$('.btn_ac').hide();
	$('#load').show();
	var datos=$('#frm_guarda').serialize();
	$.post('ac/nuevo_cliente.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Clientes&msg=1", "_self");
	    }else{
	    	$('#load').hide();
			$('.btn').show();
			$('#msg_error').html(data);
			$('#msg_error').show('Fast');
	    }
	});
}
</script>