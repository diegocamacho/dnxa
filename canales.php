<?
$sql="SELECT * FROM canales ORDER BY canal ASC";
$q=mysql_query($sql);

$canales = array();

while($datos=mysql_fetch_object($q)):
	$canales[] = $datos;
endwhile;
$val=count($canales);

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
				  		<p>El canal se ha agregado</p>
				  	</div>
			  <? }if($_GET['msg']==2){ ?>
			  		<br>
			  		<div class="alert alert-dismissable alert-info">
				  		<button type="button" class="close" data-dismiss="alert">×</button>
				  		<p>El canal se ha editado</p>
				  	</div>
			  <? } ?>
			  <!-- Contenido -->
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet light  portlet-fit">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-map font-dark"></i>
						<span class="caption-subject font-dark bold uppercase">Canales</span>
					</div>
					<div class="actions btn-set">
						<a href="javascript:;" class="btn btn-sm blue " data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#NuevoCanal"><i class="fa fa-plus"></i> Agregar canal </a>
					</div>
				</div>
				<div class="portlet-body">
					<? if($val>0): ?>
					<table class="table table-striped table-bordered table-hover">
						<thead>
					        <tr>
					          <th>Canal</th>
					          <th width="150"></th>
					        </tr>
					      </thead>
					      <tbody>
					      <? foreach($canales as $canal): ?>
					        <tr>
					          <td><?=$canal->canal?></td>
					          <td align="right">
					          		<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load_<?=$canal->id_canal?>" width="19" class="oculto" />
					          	<? if($canal->activo==1): ?>
					          		<a role="button" class="btn green btn-xs btn_<?=$canal->id_canal?>" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#EditaCanal" data-id="<?=$canal->id_canal?>">Editar</a>
					          		<a role="button" class="btn red btn-xs btn_<?=$canal->id_canal?>" onclick="javascript:Desactiva(<?=$canal->id_canal?>)">Desactivar</a>
					          	<? else: ?>
					          		<a role="button" class="btn btn-warning btn-xs btn_<?=$canal->id_canal?>" onclick="javascript:Activa(<?=$canal->id_canal?>)">Activar</a>
					          	<? endif; ?>
					          </td>
					        </tr>
					      <? endforeach; ?>
					      </tbody>
					</table>
					<? else: ?>
					<div class="alert alert-dismissable alert-warning">
				  		<button type="button" class="close" data-dismiss="alert">×</button>
				  		<p>Aún no se han creado canales</p>
				  	</div>
					<? endif; ?>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
	</div>
</div>













<!-- Modal -->
<div class="modal fade" id="NuevoCanal">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Nuevo Canal</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="msg_error"></div>
<!--Formulario -->
		<form id="frm_guarda" class="form-horizontal">
			
			<div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Nombre</label>
				<div class="col-md-9">
					<input type="text" maxlength="64" class="form-control dat" name="nombre" id="nuevo_nombre" autocomplete="off">
				</div>
			</div>

		</form>
		      
      </div>
      <div class="modal-footer">
      	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load" width="25" class="oculto" />
        <button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn_ac" onclick="NuevoCanal()">Guardar Canal</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->




<!-- Modal -->
<div class="modal fade" id="EditaCanal">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Edita Canal</h4>
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
			
			<input type="hidden" name="id_canal" id="id_canal" />
		</form>
		      
      </div>
      <div class="modal-footer">      	
      	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load2" width="25" class="oculto" />
        <button type="button" class="btn btn-default btn_ac btn-modal" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn_ac btn-modal" onclick="EditaCanal()">Actualizar Canal</button>
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
	   	url: "data/canales.php",
	   	data: 'id_canal='+data_id,
	   	success: function(data){

	   		$('#nombre').val(data);
	   		$('#id_canal').val(data_id);
	   		
	   		
	   		$('#load_big').hide();
	   		$('#frm_edita').show();
	   		$('.btn-modal').show();
	  	
	   	},
	   	cache: false
	   });
	});
	
	$('#NuevoCanal').on('shown.bs.modal',function(e){
		$('#nuevo_nombre').focus();
	});
	
	$('#NuevoCanal').on('hidden.bs.modal',function(e){
		$('#id_tipo_usuario').val("0");
		$('.dat').val("");
		$('#msg_error2').hide();
		$('#msg_error').hide();
		$('#ver_permisos').hide();
	});
	
	$('#EditaCanal').on('hidden.bs.modal',function(e){
		$('.edit').val("");
		$('#msg_error2').hide();
		$('#msg_error').hide();
	});
	
	$('form').submit(function(e){
		e.preventDefault();	
	});
});

function EditaCanal(){
	$('#msg_error2').hide('Fast');
	$('.btn_ac').hide();
	$('#load2').show();
	var datos=$('#frm_edita').serialize();
	$.post('ac/edita_canal.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Canales&msg=2", "_self");
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
	$.post('ac/activa_desactiva_canal.php', { tipo: "0", id_canal: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Canales", "_self");
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
	$.post('ac/activa_desactiva_canal.php', { tipo: "1", id_canal: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Canales", "_self");
		}else{
			$("#load_"+id+"").hide();
			$(".btn_"+id+"").show();
			alert(data);
		}
	});
}
function NuevoCanal(){
	$('#msg_error').hide('Fast');
	$('.btn_ac').hide();
	$('#load').show();
	var datos=$('#frm_guarda').serialize();
	$.post('ac/nuevo_canal.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Canales&msg=1", "_self");
	    }else{
	    	$('#load').hide();
			$('.btn').show();
			$('#msg_error').html(data);
			$('#msg_error').show('Fast');
	    }
	});
}
</script>