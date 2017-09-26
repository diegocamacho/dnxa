<?
$sql="SELECT books_proveedores.*, clinicas.clinica FROM books_proveedores 
LEFT JOIN clinicas ON clinicas.id_clinica=books_proveedores.id_clinica
ORDER BY clinica ASC";
$q=mysql_query($sql);

$proveedores = array();

while($datos=mysql_fetch_object($q)):
	$proveedores[] = $datos;
endwhile;
$val=count($proveedores);


$sql="SELECT * FROM clinicas WHERE activo=1";
$q=mysql_query($sql);

$clinicas = array();

while($datos=mysql_fetch_object($q)):
	$clinicas[] = $datos;
endwhile;

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
				  		<p>El proveedor se ha agregado</p>
				  	</div>
			  <? }if($_GET['msg']==2){ ?>
			  		<br>
			  		<div class="alert alert-dismissable alert-info">
				  		<button type="button" class="close" data-dismiss="alert">×</button>
				  		<p>El proveedor se ha editado</p>
				  	</div>
			  <? } ?>
			  <!-- Contenido -->
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet light  portlet-fit">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-briefcase font-dark"></i>
						<span class="caption-subject font-dark bold uppercase">Proveedores</span>
					</div>
					<div class="actions btn-set">
						<a href="javascript:;" class="btn btn-sm blue-chambray " data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#nuevoProveedor"><i class="fa fa-plus"></i> Agregar proveedor </a>
					</div>
				</div>
				<div class="portlet-body">
					<? if($val>0): ?>
					<table class="table table-striped table-bordered table-hover">
						<thead>
					        <tr>
					          <th>Proveedor</th>
					          <th>Empresa</th>
					          <th>Teléfono</th>
					          <th>Email</th>
					          <th width="220"></th>
					        </tr>
					      </thead>
					      <tbody>
						    <? foreach($proveedores as $proveedor): ?>  
					        <tr>
								<td><?=$proveedor->proveedor?></td>
								<td><? if($proveedor->id_clinica==0): echo "TODAS"; else: echo $proveedor->clinica; endif;?></td>
								<td><?=$proveedor->telefono?></td>
								<td><?=$proveedor->email?></td>
								<td align="right">
									<a role="button" class="btn blue btn-xs btn_<?=$proveedor->id_proveedor?>" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#editaProveedor" data-id="<?=$proveedor->id_proveedor?>">Editar</a>
									<a role="button" class="btn red btn-xs btn_<?=$proveedor->id_proveedor?>" onclick="javascript:Desactiva(<?=$proveedor->id_proveedor?>)">Desactivar</a>
									<!--<a role="button" class="btn  default btn-xs btn_<?=$proveedor->id_proveedor?>" onclick="javascript:s(<?=$proveedor->id_proveedor?>)">Historial</a>-->
								</td>
					        </tr>
					        <? endforeach; ?>
					        
					      </tbody>
					</table>
					<? else: ?>
					<div class="alert alert-dismissable alert-warning">
				  		<button type="button" class="close" data-dismiss="alert">×</button>
				  		<p>Aún no se han creado proveedores</p>
				  	</div>
					<? endif; ?>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
	</div>
</div>













<!-- Modal -->
<div class="modal fade" id="nuevoProveedor">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Nuevo proveedor</h4>
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

		</form>
		      
      </div>
      <div class="modal-footer">
      	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load" width="25" class="oculto" />
        <button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn_ac" onclick="nuevoProveedor()">Guardar Proveedor</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->




<!-- Modal -->
<div class="modal fade" id="editaProveedor">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Edita Proveedor</h4>
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
			
			<input type="hidden" name="id_proveedor" id="id_proveedor" />
		</form>
		      
      </div>
      <div class="modal-footer">      	
      	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load2" width="25" class="oculto" />
        <button type="button" class="btn btn-default btn_ac btn-modal" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn_ac btn-modal" onclick="editaProveedor()">Actualizar Proveedor</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



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
	   	url: "data/proveedor.php",
	   	data: 'id='+data_id,
	   	success: function(data){
		   	
	   		var datos = data.split('|');
	   		$('#nombre').val(datos[0]);
	   		$('#telefono').val(datos[1]);
	   		$('#email').val(datos[2]);
	   		$('#id_clinica').val(datos[3]);
	   		$('#id_proveedor').val(data_id);
	   		
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
	
	$('form').submit(function(e){
		e.preventDefault();	
	});
});

function editaProveedor(){
	$('#msg_error2').hide('Fast');
	$('.btn_ac').hide();
	$('#load2').show();
	var datos=$('#frm_edita').serialize();
	$.post('ac/edita_proveedor.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Proveedores&msg=2", "_self");
	    }else{
	    	$('#load2').hide();
			$('.btn').show();
			$('#msg_error2').html(data);
			$('#msg_error2').show('Fast');
	    }
	});
}
function Desactiva(id){
	$(".btn_"+id+"").hide();
	$("#load_"+id+"").show();
	$.post('ac/activa_desactiva_clinica.php', { tipo: "0", id_proveedor: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Clinicas", "_self");
		}else{
			$("#load_"+id+"").hide();
			$(".btn_"+id+"").show();
			alert(data);
		}
	});
}
function Activa(id){
	$(".btn_"+id+"").hide();
	$("#load_"+id+"").show();
	$.post('ac/activa_desactiva_clinica.php', { tipo: "1", id_proveedor: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Clinicas", "_self");
		}else{
			$("#load_"+id+"").hide();
			$(".btn_"+id+"").show();
			alert(data);
		}
	});
}
function nuevoProveedor(){
	$('#msg_error').hide('Fast');
	$('.btn_ac').hide();
	$('#load').show();
	var datos=$('#frm_guarda').serialize();
	$.post('ac/nuevo_proveedor.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Proveedores&msg=1", "_self");
	    }else{
	    	$('#load').hide();
			$('.btn').show();
			$('#msg_error').html(data);
			$('#msg_error').show('Fast');
	    }
	});
}
</script>