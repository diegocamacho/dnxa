<?
//Clínicas
$sql="SELECT * FROM clinicas WHERE activo=1 AND tipo=1 ORDER BY clinica ASC";
$q=mysql_query($sql);
$clinicas = array();
while($datos=mysql_fetch_object($q)):
	$clinicas[] = $datos;
endwhile;
$valida_clinicas=count($clinicas);

$sql="SELECT clinicas.clinica, tipo_usuario.*, usuarios.* FROM usuarios 
JOIN tipo_usuario ON tipo_usuario.id_tipo_usuario=usuarios.id_tipo_usuario
LEFT JOIN clinicas ON clinicas.id_clinica=usuarios.id_clinica
ORDER BY usuarios.nombre ASC";
$q=mysql_query($sql);


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
				  		<p>El usuario se ha agregado</p>
				  	</div>
			  <? }if($_GET['msg']==2){ ?>
			  		<br>
			  		<div class="alert alert-dismissable alert-info">
				  		<button type="button" class="close" data-dismiss="alert">×</button>
				  		<p>El usuario se ha editado</p>
				  	</div>
			  <? } ?>
			  <!-- Contenido -->
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet light  portlet-fit">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-users font-dark"></i>
						<span class="caption-subject font-dark bold uppercase">Usuarios</span>
					</div>
					<div class="actions btn-set">
						<a href="javascript:;" class="btn btn-sm blue " data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#NuevoUsuario"><i class="fa fa-plus"></i> Agregar usuario </a>
					</div>
				</div>
				<div class="portlet-body">
					<table class="table table-striped table-bordered table-hover">
						<thead>
					        <tr>
								<th>Nombre</th>
								<th>Tipo</th>
								<th>Clínica</th>
								<th>Celular</th>
								<th>Email</th>
								<th>Último Acceso</th>
								<th width="150"></th>
					        </tr>
					    </thead>
					    <tbody>
					    <? while($ft=mysql_fetch_assoc($q)){ ?>
					        <tr>
								<td><?=$ft['nombre']?></td>
								<td><?=$ft['tipo_usuario']?></td>
								<td><? if($ft['id_tipo_usuario']>1){ echo $ft['clinica']; }else{ echo "N/A"; }?></td>
								<td><?=$ft['celular']?></td>
								<td><?=$ft['email']?></td>
								<td><? if($ft['ultimo_acceso']){ echo devuelveFechaHora($ft['ultimo_acceso']); }else{ echo "NUNCA"; }?></td>
								<td align="right">
									<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load_<?=$ft['id_usuario']?>" width="19" class="oculto" />
									<? if($ft['activo']==1){ ?>
									<a role="button" class="btn blue btn-xs green btn_<?=$ft['id_usuario']?>" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#EditaUsuario" data-id="<?=$ft['id_usuario']?>">Editar</a>
									<a role="button" class="btn blue btn-xs red btn_<?=$ft['id_usuario']?>" onclick="javascript:Desactiva(<?=$ft['id_usuario']?>)">Desactivar</a>
									<? }else{ ?>
									<a role="button" class="btn btn-xs btn-warning btn_<?=$ft['id_usuario']?>" onclick="javascript:Activa(<?=$ft['id_usuario']?>)">Activar</a>
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
        <h4 class="modal-title">Nuevo Usuario</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="msg_error"></div>
<!--Formulario -->
		<form id="frm_guarda" class="form-horizontal">
			
			<div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Primer nombre</label>
				<div class="col-md-9">
					<input type="text" maxlength="64" class="form-control dat" name="nombre" id="nuevo_nombre" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Primer apellido</label>
				<div class="col-md-9">
					<input type="text" maxlength="64" class="form-control dat" name="apellido" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="email" class="col-md-3 control-label">Email</label>
				<div class="col-md-9">
					<input type="text" maxlength="64" class="form-control dat" name="email" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="celular" class="col-md-3 control-label">Celular</label>
				<div class="col-md-9">
					<input type="text" maxlength="10" class="form-control dat" name="celular" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="password" class="col-md-3 control-label">Contraseña</label>
				<div class="col-md-9">
					<input type="text" maxlength="16" class="form-control dat" name="password" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Tipo de Usuario</label>
				<div class="col-md-9">
					<select class="form-control" name="id_tipo_usuario" id="val_id_tipo_usuario">
                    	<option value="0">Seleccione uno</option>
                    	<?
	                    $sq="SELECT * FROM tipo_usuario";	
	                    $q=mysql_query($sq);
	                    while($ft=mysql_fetch_assoc($q)){
						?>
						<option value="<?=$ft['id_tipo_usuario']?>"><?=$ft['tipo_usuario']?></option>
						<? } ?>
					</select>
				</div>
			</div>
			
			<div class="form-group muestra_clinica oculto">
				<label for="direccion" class="col-md-3 control-label">Clínica</label>
				<div class="col-md-9">
					<select class="form-control" name="id_clinica" >
						<option value="0">Seleccione una</option>
						<? foreach($clinicas as $clinica): ?>
						<option value="<?=$clinica->id_clinica?>"><?=$clinica->clinica?></option>
						<? endforeach; ?>
					</select>
				</div>
			</div>
			<hr>
			<div class="form-group">
				<label for="precio_venta" class="col-md-3 control-label">Foto de perfil</label>
				<div class="col-md-9" id="fotos">
					<div id="upload_dropzone" class="dropzone" style="border:none; background-color:white"></div>
				</div>
			</div>
			
			

		</form>
		      
      </div>
      <div class="modal-footer">
      	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load" width="25" class="oculto" />
        <button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn_ac" onclick="NuevoUsuario()">Guardar Usuario</button>
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
				<label for="nombre" class="col-md-3 control-label">Primer nombre</label>
				<div class="col-md-9">
					<input type="text" maxlength="64" class="form-control edit" id="nombre" name="nombre" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Primer apellido</label>
				<div class="col-md-9">
					<input type="text" maxlength="64" class="form-control edit" id="apellido" name="apellido" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="email" class="col-md-3 control-label">Email</label>
				<div class="col-md-9">
					<input type="text" maxlength="64" class="form-control edit" id="email" name="email" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="celular" class="col-md-3 control-label">Celular</label>
				<div class="col-md-9">
					<input type="text" maxlength="24" class="form-control edit" id="celular" name="celular" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="password" class="col-md-3 control-label">Contraseña</label>
				<div class="col-md-9">
					<input type="text" maxlength="16" class="form-control edit" name="password" placeholder="Cambiar la contraseña" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Tipo de Usuario</label>
				<div class="col-md-9">
					<select class="form-control" name="id_tipo_usuario" id="id_tipo_usuario">
                    	<?
	                    $sq="SELECT * FROM tipo_usuario";	
	                    $q=mysql_query($sq);
	                    while($ft=mysql_fetch_assoc($q)){
						?>
						<option value="<?=$ft['id_tipo_usuario']?>"><?=$ft['tipo_usuario']?></option>
						<? } ?>
					</select>
				</div>
			</div>
			
			<div class="form-group muestra_clinica oculto">
				<label for="direccion" class="col-md-3 control-label">Clínica</label>
				<div class="col-md-9">
					<select class="form-control" name="id_clinica" id="id_clinica" >
						<option value="0">Seleccione una</option>
						<? foreach($clinicas as $clinica): ?>
						<option value="<?=$clinica->id_clinica?>"><?=$clinica->clinica?></option>
						<? endforeach; ?>
					</select>
				</div>
			</div>
			<hr>
			<div class="form-group">
				<label for="fotos2" class="col-md-3 control-label">Foto</label>
				<div class="col-md-9" id="fotos2">
					<center><img class="media-object" style="height: 64px;" src="" id="foto_edita"></center>
					<div id="upload_dropzone_2" class="dropzone" style="border:none; background-color:white"></div>
					<input type="hidden" name="foto_final" id="foto-usuario-edita">
				</div>
			</div>
			
			<input type="hidden" name="id_usuario" id="id_usuario" />
		</form>
		      
      </div>
      <div class="modal-footer">      	
      	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load2" width="25" class="oculto" />
        <button type="button" class="btn btn-default btn_ac btn-modal" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn_ac btn-modal" onclick="EditaUsuario()">Actualizar Usuario</button>
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
	   	url: "data/usuarios.php",
	   	data: 'id_usuario='+data_id,
	   	success: function(data){
	   		var datos = data.split('|');
	   		var id_tipo_usuario=datos[0];
	   		var nombre=datos[1];
	   		var apellido=datos[2];
	   		var email=datos[3];
	   		var celular=datos[4];
	   		var id_clinica=datos[6];
	   		if(id_tipo_usuario>1){
		   		$('.muestra_clinica').show();
		   		$('#id_clinica').val(id_clinica);
	   		}
	   		$('#foto_edita').attr('src','files/'+datos[5]);
	   		$('#foto-usuario-edita').val(datos[5]);
	   		
	   		$('#id_tipo_usuario').val(id_tipo_usuario);
	   		$('#nombre').val(nombre);
	   		$('#apellido').val(apellido);
	   		$('#email').val(email);
	   		$('#celular').val(celular);
	   		$('#id_usuario').val(data_id);
	   		
	   		
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
		$('#id_tipo_usuario').val("0");
		$('.dat').val("");
		$('#msg_error2').hide();
		$('#msg_error').hide();
		$('.muestra_clinica').hide();
	});
	
	$('#EditaUsuario').on('hidden.bs.modal',function(e){
		$('.edit').val("");
		$('#msg_error2').hide();
		$('#msg_error').hide();
		$('.muestra_clinica').hide();
	});
	
	//DROPZONE
    $("#upload_dropzone").dropzone({ 
        url: "upload_foto.php",
        paramName: "archivo",
        maxFiles: 1,
        maxFilesize: 10,
        acceptedFiles: "image/jpg,image/jpeg,image/png",
        dictDefaultMessage: "<a class='btn btn-circle btn-info btn-sm'>Arrastre o de Clic para cambiar la foto</a><br><p><i>Solo imagenes en formato jpg o png</i></p>",
        dictInvalidFileType: "No puede subir este tipo de archivo, seleccione uno válido",
        dictFileTooBig: "El tamaño del archivo excede el permitido (10 Mb)",
        dictMaxFilesExceeded:"Solo puede subir una imagen a la vez",
        addRemoveLinks: true,
        dictRemoveFile: "Eliminar Archivo",
        dictCancelUpload: "Cancela",
        success: function (file, response) {
            var imgName = response;
            //$('.fotos').hide();
            $('#foto-usuario').remove();
            //$('#foto-usuario').attr('src',imgName);
            setTimeout(function(){ $('#fotos').append('<input type="hidden" value="'+imgName+'" name="foto_final" id="foto-usuario">'); }, 1000);         
        }});

	//DROPZONE
    $("#upload_dropzone_2").dropzone({ 
        url: "upload_foto.php",
        paramName: "archivo",
        maxFiles: 1,
        maxFilesize: 10,
        acceptedFiles: "image/jpg,image/jpeg,image/png",
        dictDefaultMessage: "<a class='btn btn-circle btn-info btn-sm'>Arrastre o de Clic para cambiar la foto</a><br><p><i>Solo imagenes en formato jpg o png</i></p>",
        dictInvalidFileType: "No puede subir este tipo de archivo, seleccione uno válido",
        dictFileTooBig: "El tamaño del archivo excede el permitido (10 Mb)",
        dictMaxFilesExceeded:"Solo puede subir una imagen a la vez",
        addRemoveLinks: true,
        dictRemoveFile: "Eliminar Archivo",
        dictCancelUpload: "Cancela",
        success: function (file, response) {
            var imgName = response;
            $('#foto_edita').remove();
            $('#foto-usuario-edita').remove();
            //$('#foto-usuario').attr('src',imgName);
            setTimeout(function(){ $('#fotos2').append('<input type="hidden" value="'+imgName+'" name="foto_final" id="foto-usuario-edita">'); }, 1000);         
        }});
        
    $('#val_id_tipo_usuario').change(function(){
		var tipo = $('#val_id_tipo_usuario').val();
		if(tipo>1){
			$('.muestra_clinica').show('fast');
		}else{
			$('.muestra_clinica').hide('fast');
		}
    });
    
    $('#id_clinica').change(function(){
		var tipo = $('#id_clinica').val();
		if(tipo>1){
			$('.muestra_clinica').show('fast');
		}else{
			$('.muestra_clinica').hide('fast');
		}
    });

});

function EditaUsuario(){
	$('#msg_error2').hide('Fast');
	$('.btn_ac').hide();
	$('#load2').show();
	var datos=$('#frm_edita').serialize();
	$.post('ac/edita_usuario.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Usuarios&msg=2", "_self");
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
	$.post('ac/activa_desactiva_usuario.php', { tipo: "0", id_usuario: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Usuarios", "_self");
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
	$.post('ac/activa_desactiva_usuario.php', { tipo: "1", id_usuario: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Usuarios", "_self");
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
	$.post('ac/nuevo_usuario.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Usuarios&msg=1", "_self");
	    }else{
	    	$('#load').hide();
			$('.btn').show();
			$('#msg_error').html(data);
			$('#msg_error').show('Fast');
	    }
	});
}
</script>