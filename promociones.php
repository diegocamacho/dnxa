<?
$sql="SELECT * FROM promociones ORDER BY promocion ASC";
$q=mysql_query($sql);
$promociones = array();
while($datos=mysql_fetch_object($q)):
	$promociones[] = $datos;
endwhile;
$val=count($promociones);

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
				  		<p>La promoción se ha agregado</p>
				  	</div>
			  <? }if($_GET['msg']==2){ ?>
			  		<br>
			  		<div class="alert alert-dismissable alert-info">
				  		<button type="button" class="close" data-dismiss="alert">×</button>
				  		<p>La promoción se ha editado</p>
				  	</div>
			  <? } ?>
			  <!-- Contenido -->
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet light  portlet-fit">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-present font-dark"></i>
						<span class="caption-subject font-dark bold uppercase">Promociones</span>
					</div>
					<div class="actions btn-set">
						<a href="javascript:;" class="btn btn-sm blue " data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#NuevaPromocion"><i class="fa fa-plus"></i> Agregar promoción </a>
					</div>
				</div>
				<div class="portlet-body">
					<? if($val>0): ?>
					<table class="table table-striped table-bordered table-hover">
						<thead>
					        <tr>
								<th>Promoción</th>
								<th>Descripción</th>
								<th width="150"></th>
					        </tr>
					    </thead>
					    <tbody>
					    <? foreach($promociones as $promocion): ?>
					        <tr>
								<td><?=$promocion->promocion?></td>
								<td><?=$promocion->descripcion?></td>
								<td align="right">
									<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load_<?=$promocion->id_promocion?>" width="19" class="oculto" />
										<? if($promocion->activo==1): ?>
											<a role="button" class="btn green btn-xs btn_<?=$promocion->id_promocion?>" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#EditaPromocion" data-id="<?=$promocion->id_promocion?>">Editar</a>
											<a role="button" class="btn red btn-xs btn_<?=$promocion->id_promocion?>" onclick="javascript:Desactiva(<?=$promocion->id_promocion?>)">Desactivar</a>
										<? else: ?>
											<a role="button" class="btn btn-warning btn-xs btn_<?=$promocion->id_promocion?>" onclick="javascript:Activa(<?=$promocion->id_promocion?>)">Activar</a>
										<? endif; ?>
								</td>
					        </tr>
					    <? endforeach; ?>
					    </tbody>
					</table>
					<? else: ?>
					<div class="alert alert-dismissable alert-warning">
						<button type="button" class="close" data-dismiss="alert">×</button>
						<p>Aún no se han creado promociones</p>
					</div>
					<? endif; ?>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
	</div>
</div>













<!-- Modal -->
<div class="modal fade" id="NuevaPromocion">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Nueva Promoción</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="msg_error"></div>
<!--Formulario -->
		<form id="frm_guarda" class="form-horizontal">
			
			<div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Promoción</label>
				<div class="col-md-9">
					<input type="text" maxlength="64" class="form-control dat" name="nombre" id="nuevo_nombre" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Descripción</label>
				<div class="col-md-9">
					<textarea class="form-control" autocomplete="off" name="descripcion" rows="3"></textarea>
				</div>
			</div>

		</form>
		      
      </div>
      <div class="modal-footer">
      	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load" width="25" class="oculto" />
        <button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn_ac" onclick="NuevaPromocion()">Guardar Promoción</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->




<!-- Modal -->
<div class="modal fade" id="EditaPromocion">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Edita Promoción</h4>
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
				<label for="nombre" class="col-md-3 control-label">Descripción</label>
				<div class="col-md-9">
					<textarea class="form-control" autocomplete="off" name="descripcion" id="descripcion" rows="3"></textarea>
				</div>
			</div>
			
			<input type="hidden" name="id_promocion" id="id_promocion" />
		</form>
		      
      </div>
      <div class="modal-footer">      	
      	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load2" width="25" class="oculto" />
        <button type="button" class="btn btn-default btn_ac btn-modal" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn_ac btn-modal" onclick="EditaPromocion()">Actualizar Promoción</button>
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
	   	url: "data/promociones.php",
	   	data: 'id_promocion='+data_id,
	   	success: function(data){
	   		var datos = data.split('|');
	   		$('#nombre').val(datos[0]);
	   		$('#descripcion').val(datos[1]);
	   		$('#id_promocion').val(data_id);
	   		
	   		
	   		$('#load_big').hide();
	   		$('#frm_edita').show();
	   		$('.btn-modal').show();
	  	
	   	},
	   	cache: false
	   });
	});
	
	$('#NuevaPromocion').on('shown.bs.modal',function(e){
		$('#nuevo_nombre').focus();
	});
	
	$('#NuevaPromocion').on('hidden.bs.modal',function(e){
		$('#id_tipo_usuario').val("0");
		$('.dat').val("");
		$('#msg_error2').hide();
		$('#msg_error').hide();
		$('#ver_permisos').hide();
	});
	
	$('#EditaPromocion').on('hidden.bs.modal',function(e){
		$('.edit').val("");
		$('#msg_error2').hide();
		$('#msg_error').hide();
	});
	
	$('form').submit(function(e){
		e.preventDefault();	
	});
});

function EditaPromocion(){
	$('#msg_error2').hide('Fast');
	$('.btn_ac').hide();
	$('#load2').show();
	var datos=$('#frm_edita').serialize();
	$.post('ac/edita_promocion.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Promociones&msg=2", "_self");
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
	$.post('ac/activa_desactiva_promocion.php', { tipo: "0", id_promocion: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Promociones", "_self");
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
	$.post('ac/activa_desactiva_promocion.php', { tipo: "1", id_promocion: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Promociones", "_self");
		}else{
			$("#load_"+id+"").hide();
			$(".btn_"+id+"").show();
			alert(data);
		}
	});
}
function NuevaPromocion(){
	$('#msg_error').hide('Fast');
	$('.btn_ac').hide();
	$('#load').show();
	var datos=$('#frm_guarda').serialize();
	$.post('ac/nueva_promocion.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Promociones&msg=1", "_self");
	    }else{
	    	$('#load').hide();
			$('.btn').show();
			$('#msg_error').html(data);
			$('#msg_error').show('Fast');
	    }
	});
}
</script>