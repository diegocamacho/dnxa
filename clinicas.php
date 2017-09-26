<?
$sql="SELECT * FROM clinicas ORDER BY clinica ASC";
$q=mysql_query($sql);

$clinicas = array();

while($datos=mysql_fetch_object($q)):
	$clinicas[] = $datos;
endwhile;
$val=count($clinicas);

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
				  		<p>La clínica se ha agregado</p>
				  	</div>
			  <? }if($_GET['msg']==2){ ?>
			  		<br>
			  		<div class="alert alert-dismissable alert-info">
				  		<button type="button" class="close" data-dismiss="alert">×</button>
				  		<p>La clínica se ha editado</p>
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
						<a href="javascript:;" class="btn btn-sm blue " data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#NuevaClinica"><i class="fa fa-plus"></i> Agregar empresa </a>
					</div>
				</div>
				<div class="portlet-body">
					<? if($val>0): ?>
					<table class="table table-striped table-bordered table-hover">
						<thead>
					        <tr>
								<th>Empresa</th>
								<th>Tipo</th>
								<th>Teléfono</th>
								<th>Dirección</th>
								<th width="250"></th>
					        </tr>
						</thead>
						<tbody>
					      <? foreach($clinicas as $clinica): ?>
					        <tr>
								<td><span class="badge hide" style="background-color: <?=$clinica->color?>">&nbsp;&nbsp;</span> <?=$clinica->clinica?></td>
								<td><?=dameTipoEmpresa($clinica->tipo)?></td>
								<td><?=$clinica->telefono?></td>
								<td><?=$clinica->direccion?></td>
								<td align="right">
									<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load_<?=$clinica->id_clinica?>" width="19" class="oculto" />
									<? if($clinica->activo==1): ?>
										<a role="button" class="btn blue btn-xs btn_<?=$clinica->id_clinica?>" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#EditaClinica" data-id="<?=$clinica->id_clinica?>">Editar</a>
										<a role="button" class="btn btn-info btn-xs btn_<?=$clinica->id_clinica?>" href="?Modulo=HorariosClinica&id=<?=$clinica->id_clinica?>">Horarios</a>
										<a role="button" class="btn red btn-xs btn_<?=$clinica->id_clinica?>" onclick="javascript:Desactiva(<?=$clinica->id_clinica?>)">Desactivar</a>
										
									<? else: ?>
										<a role="button" class="btn btn-warning btn-xs btn_<?=$clinica->id_clinica?>" onclick="javascript:Activa(<?=$clinica->id_clinica?>)">Activar</a>
									<? endif; ?>
								</td>
					        </tr>
					      <? endforeach; ?>
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
<div class="modal fade" id="NuevaClinica">
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
			
			<div class="form-group">
				<label for="telefono" class="col-md-3 control-label">Teléfono</label>
				<div class="col-md-9">
					<input type="text" maxlength="10" class="form-control dat" name="telefono" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="direccion" class="col-md-3 control-label">Dirección</label>
				<div class="col-md-9">
					<input type="text" class="form-control dat" name="direccion" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group hide">
				<label for="direccion" class="col-md-3 control-label">Color</label>
				<div class="col-md-9">
					<input type="text" id="hue-demo" class="form-control demo" data-control="hue" value="#12AF9B" name="color">
				</div>
			</div>
			
			<div class="form-group">
				<label for="direccion" class="col-md-3 control-label">Tipo</label>
				<div class="col-md-9">
					<select class="form-control" name="tipo" >
						<option value="0">Seleccione una</option>
						<option value="1">Clínica</option>
						<option value="2">Corporativo</option>
					</select>
				</div>
			</div>
			<hr>
			<h4>Citas Web</h4>
			<br>
			<div class="form-group">
				<label for="direccion" class="col-md-3 control-label">Capacidad de Citas x Hora</label>
				<div class="col-md-4">
					<input type="text" class="form-control dat" name="capacidad" autocomplete="off">
				</div>
			</div>
			
                    <div class="form-group">
                        <label class="col-md-3 control-label">Días Disponibles</label>
                        <div class="col-md-9">
                            <div class="mt-checkbox-inline">
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox" name="lunes" checked> Lun
                                    <span></span>
                                </label>
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox" name="martes" checked> Mar
                                    <span></span>
                                </label>
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox"  name="miercoles" checked> Mié
                                    <span></span>
                                </label>
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox" name="jueves" checked> Jue
                                    <span></span>
                                </label>
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox" name="viernes" checked> Vie
                                    <span></span>
                                </label>
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox" name="sabado"> Sáb
                                    <span></span>
                                </label>
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox" name="domingo"> Dom
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
			

		</form>
		      
      </div>
      <div class="modal-footer">
      	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load" width="25" class="oculto" />
        <button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn_ac" onclick="NuevaClinica()">Guardar Empresa</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->




<!-- Modal -->
<div class="modal fade" id="EditaClinica">
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
			
			<div class="form-group">
				<label for="telefono" class="col-md-3 control-label">Teléfono</label>
				<div class="col-md-9">
					<input type="text" maxlength="10" class="form-control dat" name="telefono" id="telefono" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="direccion" class="col-md-3 control-label">Dirección</label>
				<div class="col-md-9">
					<input type="text" class="form-control dat" name="direccion" id="direccion" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group hide">
				<label for="direccion" class="col-md-3 control-label">Color</label>
				<div class="col-md-9">
					<input type="text" id="hue-demo" class="form-control demo color" data-control="hue" name="color">
				</div>
			</div>
			
			<div class="form-group">
				<label for="direccion" class="col-md-3 control-label">Tipo de empresa</label>
				<div class="col-md-9">
					<select class="form-control" name="tipo" id="tipo" >
						<option value="0">Seleccione una</option>
						<option value="1">Clínica</option>
						<option value="2">Empresa</option>
					</select>
				</div>
			</div>
			<hr>
			<h4>Citas Web</h4>
			<br>
			<div class="form-group">
				<label for="direccion" class="col-md-3 control-label">Capacidad de Citas x Hora</label>
				<div class="col-md-4">
					<input type="text" class="form-control dat" id="capacidad" name="capacidad" autocomplete="off">
				</div>
			</div>
			                    
                    <div class="form-group">
                        <label class="col-md-3 control-label">Días Disponibles</label>
                        <div class="col-md-9">
                            <div class="mt-checkbox-inline">
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox" name="lunes" id="lunes"> Lun
                                    <span></span>
                                </label>
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox" name="martes" id="martes"> Mar
                                    <span></span>
                                </label>
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox"  name="miercoles" id="miercoles"> Mié
                                    <span></span>
                                </label>
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox" name="jueves" id="jueves"> Jue
                                    <span></span>
                                </label>
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox" name="viernes" id="viernes"> Vie
                                    <span></span>
                                </label>
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox" name="sabado" id="sabado"> Sáb
                                    <span></span>
                                </label>
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox" name="domingo" id="domingo"> Dom
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>

			
			<input type="hidden" name="id_clinica" id="id_clinica" />
		</form>
		      
      </div>
      <div class="modal-footer">      	
      	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load2" width="25" class="oculto" />
        <button type="button" class="btn btn-default btn_ac btn-modal" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn_ac btn-modal" onclick="EditaClinica()">Actualizar Clínica</button>
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
	   	url: "data/clinicas.php",
	   	dataType: "json",
	   	data: 'id_clinica='+data_id,
	   	success: function(data){
		   	
	   		$('#id_clinica').val(data_id);
	   		console.log(data);
	   		$('#nombre').val(data.clinica);
	   		$('#telefono').val(data.telefono);
	   		$('#direccion').val(data.direccion);
	   		$('#tipo').val(data.tipo);
	   		$('#capacidad').val(data.capacidad_citas);
			if(data.lun=="on"){
				$('#lunes').prop("checked", true);
			}else{
				$('#lunes').prop("checked", false);
			}
			
			if(data.mar=="on"){
				$('#martes').prop("checked", true);
			}else{
				$('#martes').prop("checked", false);
			}
			
			if(data.mie=="on"){
				$('#miercoles').prop("checked", true);
			}else{
				$('#miercoles').prop("checked", false);
			}
			
			if(data.jue=="on"){
				$('#jueves').prop("checked", true);
			}else{
				$('#jueves').prop("checked", false);
			}
			
			if(data.vie=="on"){
				$('#viernes').prop("checked", true);
			}else{
				$('#viernes').prop("checked", false);
			}
			
			if(data.sab=="on"){
				$('#sabado').prop("checked", true);
			}else{
				$('#sabado').prop("checked", false);
			}
			
			if(data.dom=="on"){
				$('#domingo').prop("checked", true);
			}else{
				$('#domingo').prop("checked", false);
			}

	   		
	   		$('#load_big').hide();
	   		$('#frm_edita').show();
	   		$('.btn-modal').show();
	  	
	   	},
	   	cache: false
	   });
	});
	
	$('#NuevaClinica').on('shown.bs.modal',function(e){
		$('#nuevo_nombre').focus();
	});
	
	$('#NuevaClinica').on('hidden.bs.modal',function(e){
		$('.dat').val("");
		$('#msg_error2').hide();
		$('#msg_error').hide();
	});
	
	$('#EditaClinica').on('hidden.bs.modal',function(e){
		$('.edit').val("");
		$('#msg_error2').hide();
		$('#msg_error').hide();
	});
	
	$('form').submit(function(e){
		e.preventDefault();	
	});
});

function EditaClinica(){
	$('#msg_error2').hide('Fast');
	$('.btn_ac').hide();
	$('#load2').show();
	var datos=$('#frm_edita').serialize();
	$.post('ac/edita_clinica.php',datos,function(data){
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
	$.post('ac/activa_desactiva_clinica.php', { tipo: "0", id_clinica: ""+id+"" },function(data){
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
	$.post('ac/activa_desactiva_clinica.php', { tipo: "1", id_clinica: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Empresas", "_self");
		}else{
			$("#load_"+id+"").hide();
			$(".btn_"+id+"").show();
			alert(data);
		}
	});
}
function NuevaClinica(){
	$('#msg_error').hide('Fast');
	$('.btn_ac').hide();
	$('#load').show();
	var datos=$('#frm_guarda').serialize();
	$.post('ac/nueva_clinica.php',datos,function(data){
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