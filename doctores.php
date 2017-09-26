<?
//Clínicas
$sql="SELECT * FROM doctores ORDER BY nombre ASC";
$q=mysql_query($sql);
$doctores = array();
while($datos=mysql_fetch_object($q)):
	$doctores[] = $datos;
endwhile;
$valida_clinicas=count($doctores);


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
				  		<p>El doctor se ha agregado</p>
				  	</div>
			  <? }if($_GET['msg']==2){ ?>
			  		<br>
			  		<div class="alert alert-dismissable alert-info">
				  		<button type="button" class="close" data-dismiss="alert">×</button>
				  		<p>El doctor se ha editado</p>
				  	</div>
			  <? } ?>
			  <!-- Contenido -->
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet light  portlet-fit">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-user-md font-dark"></i>
						<span class="caption-subject font-dark bold uppercase">Doctores</span>
					</div>
					<div class="actions btn-set">
						<a href="javascript:;" class="btn btn-sm blue " data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#NuevoUsuario"><i class="fa fa-plus"></i> Agregar Doctor </a>
					</div>
				</div>
				<div class="portlet-body">
					<table class="table table-striped table-bordered table-hover">
						<thead>
					        <tr>
								<th>Nombre</th>
								<th>Clínica</th>
								<th width="150"></th>
					        </tr>
					    </thead>
					    <tbody>
					    <? while($ft=mysql_fetch_assoc($q)){ ?>
					        <tr>
								<td><?=$ft['nombre']?></td>
								<td><?=@dameClinica($ft['id_clinica'])?></td>
								<td align="right">
									<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load_<?=$ft['id_usuario']?>" width="19" class="oculto" />
									<? if($ft['activo']==1){ ?>
									<a role="button" class="btn blue btn-xs green btn_<?=$ft['id_doctor']?>" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#EditaUsuario" data-id="<?=$ft['id_doctor']?>">Editar</a>
									<a role="button" class="btn blue btn-xs red btn_<?=$ft['id_doctor']?>" onclick="javascript:Desactiva(<?=$ft['id_doctor']?>)">Desactivar</a>
									<? }else{ ?>
									<a role="button" class="btn btn-xs btn-warning btn_<?=$ft['id_doctor']?>" onclick="javascript:Activa(<?=$ft['id_doctor']?>)">Activar</a>
									<? } ?>
								</td>
							</tr>
						<? } ?>
					    </tbody>
					</table>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="NuevoUsuario">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Nuevo Doctor</h4>
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
			
			<div class="form-group">
				<label for="direccion" class="col-md-3 control-label">Clínica</label>
					<div class="col-md-9">
						<select class="form-control" name="id_clinica" >
							<option value="0">Seleccione una clínica</option>
						<? foreach($clinicas as $clinica): ?>
							<option value="<?=$clinica->id_clinica?>" <?if($clinica->id_clinica==$s_id_clinica):?>selected="1"<?endif;?>><?=$clinica->clinica?></option>
						<? endforeach; ?>
					</select>
				</div>
			</div>			
		</form>
		      
      </div>
      <div class="modal-footer">
      	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load" width="25" class="oculto" />
        <button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn_ac" onclick="NuevoUsuario()">Guardar Doctor</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->




<!-- Modal -->
<div class="modal fade" id="EditaUsuario">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Edita Usuario</h4>
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
				<label for="direccion" class="col-md-3 control-label">Clínica</label>
					<div class="col-md-9">
						<select class="form-control" name="id_clinica" id="id_clinica" >
							<option value="0">Seleccione una clínica</option>
						<? foreach($clinicas as $clinica): ?>
							<option value="<?=$clinica->id_clinica?>" <?if($clinica->id_clinica==$s_id_clinica):?>selected="1"<?endif;?>><?=$clinica->clinica?></option>
						<? endforeach; ?>
					</select>
				</div>
			</div>	
			
			<input type="hidden" name="id_doctor" id="id_doctor" />
		</form>
		      
      </div>
      <div class="modal-footer">      	
      	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load2" width="25" class="oculto" />
        <button type="button" class="btn btn-default btn_ac btn-modal" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn_ac btn-modal" onclick="EditaUsuario()">Actualizar Doctor</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script src="js/dropzone.js"></script>		    
		    
<!--- Js -->
<script>
$(function(){
	$('form').submit(function(e){
		e.preventDefault();	
	});
	
	$(document).on('click', '[data-id]', function () {
		$('.edit').val("");
		$('.btn-modal').hide();
		$('#frm_edita').hide();
		$('#load_big').show();
	    var data_id = $(this).attr('data-id');
	    $.ajax({
	   	url: "data/doctores.php",
	   	data: 'id_doctor='+data_id,
	   	success: function(data){
	   		var datos = data.split('|');
	   		var nombre=datos[0];
	   		var clinica = datos[1];
	   		$('#nombre').val(nombre);
	   		$('#id_clinica').val(clinica);
	   		$('#id_doctor').val(data_id);
	   		
	   		
	   		$('#load_big').hide();
	   		$('#frm_edita').show();
	   		$('.btn-modal').show();
	  	
	   	},
	   	cache: false
	   });
	});
	
	
	$('#NuevoUsuario').on('shown.bs.modal',function(e){
		$('#nuevo_nombre').focus();
	});
	
	$('#NuevoUsuario').on('hidden.bs.modal',function(e){
		$('.dat').val("");
		$('#msg_error2').hide();
		$('#msg_error').hide();
	});
	
	$('#EditaUsuario').on('hidden.bs.modal',function(e){
		$('.edit').val("");
		$('#msg_error2').hide();
		$('#msg_error').hide();;
	});
	
	        
});

function EditaUsuario(){
	$('#msg_error2').hide('Fast');
	$('.btn_ac').hide();
	$('#load2').show();
	var datos=$('#frm_edita').serialize();
	$.post('ac/edita_doctor.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Doctores&msg=2", "_self");
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
	$.post('ac/activa_desactiva_doctor.php', { tipo: "0", id_doctor: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Doctores", "_self");
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
	$.post('ac/activa_desactiva_doctor.php', { tipo: "1", id_doctor: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Doctores", "_self");
		}else{
			$("#load_"+id+"").hide();
			$(".btn_"+id+"").show();
			alert(data);
		}
	});
}
function NuevoUsuario(){
	$('#msg_error').hide('Fast');
	$('.btn_ac').hide();
	$('#load').show();
	var datos=$('#frm_guarda').serialize();
	$.post('ac/nuevo_doctor.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Doctores&msg=1", "_self");
	    }else{
	    	$('#load').hide();
			$('.btn').show();
			$('#msg_error').html(data);
			$('#msg_error').show('Fast');
	    }
	});
}
</script>