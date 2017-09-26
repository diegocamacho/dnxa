<?
$sql="SELECT * FROM tratamientos ORDER BY tratamiento ASC";
$q=mysql_query($sql);
$tratamientos = array();
while($datos=mysql_fetch_object($q)):
	$tratamientos[] = $datos;
endwhile;
$val=count($tratamientos);

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
				  		<p>El tratamiento se ha agregado</p>
				  	</div>
			  <? }if($_GET['msg']==2){ ?>
			  		<br>
			  		<div class="alert alert-dismissable alert-info">
				  		<button type="button" class="close" data-dismiss="alert">×</button>
				  		<p>El tratamiento se ha editado</p>
				  	</div>
			  <? } ?>
			  <!-- Contenido -->
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet light  portlet-fit">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-star font-dark"></i>
						<span class="caption-subject font-dark bold uppercase">Tratamientos</span>
					</div>
					<div class="actions btn-set">
						<a href="javascript:;" class="btn btn-sm blue " data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#NuevoTratamiento"><i class="fa fa-plus"></i> Agregar tratamiento </a>
					</div>
				</div>
				<div class="portlet-body">
					<? if($val>0): ?>
					<table class="table table-striped table-bordered table-hover">
						<thead>
					        <tr>
					          <th>Tratamiento</th>
					          <th width="100" style="text-align: right;">Costo</th>
					          <th width="280"></th>
					        </tr>
					      </thead>
					      <tbody>
					      <? foreach($tratamientos as $tratamiento): ?>
					        <tr>
					          <td><?=$tratamiento->tratamiento?></td>
					          <td align="right"><?=number_format($tratamiento->costo,2)?></td>
					          <td align="right">
					          		<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load_<?=$tratamiento->id_tratamiento?>" width="19" class="oculto" />
					          	<? if($tratamiento->activo==1): ?>
					          		<a role="button" class="btn blue btn-xs green btn_<?=$tratamiento->id_tratamiento?>" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#EditaTratamiento" data-id="<?=$tratamiento->id_tratamiento?>">Editar</a>
					          		<a role="button" class="btn blue btn-xs red btn_<?=$tratamiento->id_tratamiento?>" onclick="javascript:Desactiva(<?=$tratamiento->id_tratamiento?>)">Desactivar</a>
					          	<? else: ?>
					          		<a role="button" class="btn btn-xs btn-warning btn_<?=$tratamiento->id_tratamiento?>" onclick="javascript:Activa(<?=$tratamiento->id_tratamiento?>)">Activar</a>
					          	<? endif; ?>
					          	<? if($tratamiento->web==1): ?>
					          		<a role="button" class="btn btn-xs btn-default btn_<?=$tratamiento->id_tratamiento?>" onclick="javascript:DesactivaW(<?=$tratamiento->id_tratamiento?>)">Desactivar Web</a>
					          	<? else: ?>
					          		<a role="button" class="btn btn-xs blue btn_<?=$tratamiento->id_tratamiento?>" onclick="javascript:ActivaW(<?=$tratamiento->id_tratamiento?>)">Activar Web</a>
					          	<? endif; ?>
					          </td>
					        </tr>
					      <? endforeach; ?>
					      </tbody>
					</table>
					<? else: ?>
					<div class="alert alert-dismissable alert-warning">
				  		<button type="button" class="close" data-dismiss="alert">×</button>
				  		<p>Aún no se han cargado tratamientos</p>
				  	</div>
					<? endif; ?>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
	</div>
</div>













<!-- Modal -->
<div class="modal fade" id="NuevoTratamiento">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Nuevo Tratamiento</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="msg_error"></div>
<!--Formulario -->
		<form id="frm_guarda" class="form-horizontal">
			
			<div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Tratamiento</label>
				<div class="col-md-9">
					<input type="text" maxlength="128" class="form-control dat" name="nombre" id="nuevo_nombre" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="telefono" class="col-md-3 control-label">Costo</label>
				<div class="col-md-3">
					<input type="text" maxlength="20" class="form-control dat" name="costo" autocomplete="off">
				</div>
			</div>

		</form>
		      
      </div>
      <div class="modal-footer">
      	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load" width="25" class="oculto" />
        <button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn_ac" onclick="NuevoTratamiento()">Guardar Tratamiento</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->




<!-- Modal -->
<div class="modal fade" id="EditaTratamiento">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Edita Tratamiento</h4>
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
				<label for="telefono" class="col-md-3 control-label">Costo</label>
				<div class="col-md-3">
					<input type="text" maxlength="20" class="form-control dat" name="costo" id="costo" autocomplete="off">
				</div>
			</div>
			
			<input type="hidden" name="id_tratamiento" id="id_tratamiento" />
		</form>
		      
      </div>
      <div class="modal-footer">      	
      	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load2" width="25" class="oculto" />
        <button type="button" class="btn btn-default btn_ac btn-modal" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn_ac btn-modal" onclick="EditaTratamiento()">Actualizar Tratamiento</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<!--- Js -->
<script>
$(function(){

	$(document).on('click', '[data-id]', function () {
		$('.edit').val("");
		$('.btn-modal').hide();
		$('#frm_edita').hide();
		$('#load_big').show();
	    var data_id = $(this).attr('data-id');
	    $.ajax({
	   	url: "data/tratamientos.php",
	   	data: 'id_tratamiento='+data_id,
	   	success: function(data){
	   		var datos = data.split('|');
	   		$('#nombre').val(datos[0]);
	   		$('#costo').val(datos[1]);
	   		$('#id_tratamiento').val(data_id);
	   		
	   		
	   		$('#load_big').hide();
	   		$('#frm_edita').show();
	   		$('.btn-modal').show();
	  	
	   	},
	   	cache: false
	   });
	});
	
	$('#NuevoTratamiento').on('shown.bs.modal',function(e){
		$('#nuevo_nombre').focus();
	});
	
	$('#NuevoTratamiento').on('hidden.bs.modal',function(e){
		$('.dat').val("");
		$('#msg_error2').hide();
		$('#msg_error').hide();
	});
	
	$('#EditaTratamiento').on('hidden.bs.modal',function(e){
		$('.edit').val("");
		$('#msg_error2').hide();
		$('#msg_error').hide();
	});
	
	$('form').submit(function(e){
		e.preventDefault();	
	});
});

function EditaTratamiento(){
	$('#msg_error2').hide('Fast');
	$('.btn_ac').hide();
	$('#load2').show();
	var datos=$('#frm_edita').serialize();
	$.post('ac/edita_tratamiento.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Tratamientos&msg=2", "_self");
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
	$.post('ac/activa_desactiva_tratamiento.php', { tipo: "0", id_tratamiento: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Tratamientos", "_self");
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
	$.post('ac/activa_desactiva_tratamiento.php', { tipo: "1", id_tratamiento: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Tratamientos", "_self");
		}else{
			$("#load_"+id+"").hide();
			$(".btn_"+id+"").show();
			alert(data);
		}
	});
}
function DesactivaW(id){
	$(".btn_"+id+"").hide();
	$("#load_"+id+"").show();
	$.post('ac/activa_desactiva_tratamiento_web.php', { tipo: "0", id_tratamiento: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Tratamientos", "_self");
		}else{
			$("#load_"+id+"").hide();
			$(".btn_"+id+"").show();
			alert(data);
		}
	});
}
function ActivaW(id){
	$(".btn_"+id+"").hide();
	$("#load_"+id+"").show();
	$.post('ac/activa_desactiva_tratamiento_web.php', { tipo: "1", id_tratamiento: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Tratamientos", "_self");
		}else{
			$("#load_"+id+"").hide();
			$(".btn_"+id+"").show();
			alert(data);
		}
	});
}

function NuevoTratamiento(){
	$('#msg_error').hide('Fast');
	$('.btn_ac').hide();
	$('#load').show();
	var datos=$('#frm_guarda').serialize();
	$.post('ac/nuevo_tratamiento.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Tratamientos&msg=1", "_self");
	    }else{
	    	$('#load').hide();
			$('.btn').show();
			$('#msg_error').html(data);
			$('#msg_error').show('Fast');
	    }
	});
}
</script>