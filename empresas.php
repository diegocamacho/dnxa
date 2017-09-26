<?
$sql="SELECT * FROM books_empresas ORDER BY empresa ASC";
$q=mysql_query($sql);
$empresas = array();
while($datos=mysql_fetch_object($q)):
	$empresas[] = $datos;
endwhile;
$val=count($empresas);

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
				  		<p>La empresa se ha agregado</p>
				  	</div>
			  <? }if($_GET['msg']==2){ ?>
			  		<br>
			  		<div class="alert alert-dismissable alert-info">
				  		<button type="button" class="close" data-dismiss="alert">×</button>
				  		<p>La empresa se ha editado</p>
				  	</div>
			  <? } ?>
			  <!-- Contenido -->
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet light  portlet-fit">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-globe font-dark"></i>
						<span class="caption-subject font-dark bold uppercase">Empresas</span>
					</div>
					<div class="actions btn-set">
						<a href="javascript:;" class="btn btn-sm blue-chambray " data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#nuevaEmpresa"><i class="fa fa-plus"></i> Agregar empresa </a>
					</div>
				</div>
				<div class="portlet-body">
					<? if($val>0): ?>
					<table class="table table-striped table-bordered table-hover">
						<thead>
					        <tr>
					          <th>Empresa</th>
					          <th width="150"></th>
					        </tr>
					    </thead>
					    <tbody>
						    <? foreach($empresas as $empresa): ?>  
					        <tr>
								<td><?=$empresa->empresa?></td>
								<td align="right">

									<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load_<?=$empresa->id_empresa?>" width="19" class="oculto" />
									<? if($empresa->activo==1): ?>
										<a role="button" class="btn green btn-xs btn_<?=$empresa->id_empresa?>" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#editaEmpresa" data-id="<?=$empresa->id_empresa?>">Editar</a>
										<a role="button" class="btn red btn-xs btn_<?=$empresa->id_empresa?>" onclick="javascript:Desactiva(<?=$empresa->id_empresa?>)">Desactivar</a>
									<? else: ?>
										<a role="button" class="btn btn-warning btn-xs btn_<?=$empresa->id_empresa?>" onclick="javascript:Activa(<?=$empresa->id_empresa?>)">Activar</a>
									<? endif; ?>
									
								</td>
					        </tr>
					        <? endforeach ?>
					    </tbody>
					</table>
					<? else: ?>
					<div class="alert alert-dismissable alert-warning">
				  		<button type="button" class="close" data-dismiss="alert">×</button>
				  		<p>Aún no se han creado empresas</p>
				  	</div>
					<? endif; ?>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
	</div>
</div>













<!-- Modal -->
<div class="modal fade" id="nuevaEmpresa">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Nueva Empresa</h4>
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

		</form>
		      
      </div>
      <div class="modal-footer">
      	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load" width="25" class="oculto" />
        <button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn_ac" onclick="nuevaEmpresa()">Guardar Empresa</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->




<!-- Modal -->
<div class="modal fade" id="editaEmpresa">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Edita Empresa</h4>
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
			
			<input type="hidden" name="id_empresa" id="id_empresa" />
		</form>
		      
      </div>
      <div class="modal-footer">      	
      	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load2" width="25" class="oculto" />
        <button type="button" class="btn btn-default btn_ac btn-modal" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn_ac btn-modal" onclick="editaEmpresa()">Actualizar Empresa</button>
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
	   	url: "data/empresas.php",
	   	data: 'id='+data_id,
	   	success: function(data){
		   	
	   		var datos = data.split('|');
	   		$('#nombre').val(datos[0]);
	   		$('#id_empresa').val(data_id);
	   		
	   		$('#load_big').hide();
	   		$('#frm_edita').show();
	   		$('.btn-modal').show();
	  	
	   	},
	   	cache: false
	   });
	});
	
	$('#nuevaEmpresa').on('shown.bs.modal',function(e){
		$('#nuevo_nombre').focus();
	});
	
	$('#nuevaEmpresa').on('hidden.bs.modal',function(e){
		$('.dat').val("");
		$('#msg_error2').hide();
		$('#msg_error').hide();
	});
	
	$('#editaEmpresa').on('hidden.bs.modal',function(e){
		$('.edit').val("");
		$('#msg_error2').hide();
		$('#msg_error').hide();
	});
	
	$('form').submit(function(e){
		e.preventDefault();	
	});
});

function editaEmpresa(){
	$('#msg_error2').hide('Fast');
	$('.btn_ac').hide();
	$('#load2').show();
	var datos=$('#frm_edita').serialize();
	$.post('ac/edita_empresa.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Empresas&msg=2", "_self");
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
	$.post('ac/activa_desactiva_empresa.php', { tipo: "0", id_empresa: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Empresas", "_self");
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
	$.post('ac/activa_desactiva_empresa.php', { tipo: "1", id_empresa: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Empresas", "_self");
		}else{
			$("#load_"+id+"").hide();
			$(".btn_"+id+"").show();
			alert(data);
		}
	});
}
function nuevaEmpresa(){
	$('#msg_error').hide('Fast');
	$('.btn_ac').hide();
	$('#load').show();
	var datos=$('#frm_guarda').serialize();
	$.post('ac/nueva_empresa.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Empresas&msg=1", "_self");
	    }else{
	    	$('#load').hide();
			$('.btn').show();
			$('#msg_error').html(data);
			$('#msg_error').show('Fast');
	    }
	});
}
</script>