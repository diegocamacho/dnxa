<?
$sql="SELECT canales.canal, id_paciente,nombre,email,telefono,fecha_registro, pacientes.activo FROM pacientes 
LEFT JOIN canales ON canales.id_canal=pacientes.id_canal
WHERE tipo=1 AND pacientes.activo=1 ORDER BY fecha_registro DESC";
$q=mysql_query($sql);
$pacientes = array();
while($datos=mysql_fetch_object($q)):
	$pacientes[] = $datos;
endwhile;
$val=count($pacientes);

/* PARA LA CITA */

//Clinicas
$sql="SELECT id_clinica,clinica FROM clinicas WHERE activo=1 AND tipo=1 $consulta";
$q=mysql_query($sql);
$clinicas=array();
while($datos=mysql_fetch_object($q)):
	$clinicas[] = $datos;
endwhile;
$valida_clinicas=count($clinicas);

//Tratamientos
$sql="SELECT id_tratamiento,tratamiento FROM tratamientos WHERE activo=1";
$q=mysql_query($sql);
$tratamientos=array();
while($datos=mysql_fetch_object($q)):
	$tratamientos[] = $datos;
endwhile;
$valida_tratamientos=count($tratamientos);

//Promociones
$sql="SELECT id_promocion,promocion FROM promociones WHERE activo=1";
$q=mysql_query($sql);
$promociones=array();
while($datos=mysql_fetch_object($q)):
	$promociones[] = $datos;
endwhile;
$valida_promociones=count($promociones);

//Canales
$sql="SELECT * FROM canales WHERE activo=1 ORDER BY canal ASC";
$q=mysql_query($sql);
$canales = array();
while($datos=mysql_fetch_object($q)):
	$canales[] = $datos;
endwhile;

//Especialistas
$sql="SELECT id_especialista,nombre FROM especialistas WHERE activo=1";
$q=mysql_query($sql);
$especialistas=array();
while($datos=mysql_fetch_object($q)):
	$especialistas[] = $datos;
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
<script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<div class="page-content-inner">
	<div class="row">
		<div class="col-md-12">
			<!-- Confirmación -->
			  <? if($_GET['msg']==1){ ?>
			  		<br>
			  		<div class="alert alert-dismissable alert-success">
				  		<button type="button" class="close" data-dismiss="alert">×</button>
				  		<p>El paciente se ha agregado</p>
				  	</div>
			  <? }if($_GET['msg']==2){ ?>
			  		<br>
			  		<div class="alert alert-dismissable alert-info">
				  		<button type="button" class="close" data-dismiss="alert">×</button>
				  		<p>El paciente se ha editado</p>
				  	</div>
			  <? } ?>
			  <!-- Contenido -->
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet light  portlet-fit">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-users font-dark"></i>
						<span class="caption-subject font-dark bold uppercase">Pacientes</span>
					</div>
					<div class="actions btn-set">
						<a href="javascript:;" class="btn btn-sm blue " data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#nuevoPaciente"><i class="fa fa-plus"></i> Agregar paciente </a>
						
					</div>
				</div>
				<div class="portlet-body">
					<? if($val>0):?>
					<table class="table table-striped table-bordered table-hover" id="tabla_pacientes">
						<thead>
					        <tr>
								<th>Paciente</th>
								<th>Email</th>
								<th>Teléfono</th>
								<th>Canal</th>
								<th>Registro</th>
								<th>Última Cita</th>
								<th width="60"></th>
					        </tr>
					    </thead>
					    <tbody>
					      <? foreach($pacientes as $paciente): ?>
					        <tr class="tr_<?=$paciente->id_paciente?>">
								<td><?=$paciente->nombre?></td>
								<td><?=$paciente->email?></td>
								<td><?=$paciente->telefono?></td>
								<td><? if($paciente->id_canal): echo $paciente->canal; else: echo "DIRECTO"; endif;?></td>
								<td><?=fechaLetraDos($paciente->fecha_registro)?></td>
								<td><?=ultimaCita($paciente->id_paciente)?></td>
								<td>
									<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load_<?=$paciente->id_paciente?>" width="19" class="oculto" />
									
									<? if($paciente->activo==1): ?>
									<div class="btn-group btn_<?=$paciente->id_paciente?>">
                                        <a class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Opciones
                                            <i class="fa fa-angle-down"></i>
                                        </a>
                                        <ul class="dropdown-menu">
	                                        <li>
                                                <a href="?Modulo=Perfil&id=<?=$paciente->id_paciente?>">Historial de citas</a>
                                            </li>
                                            <li>
                                                <a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#editaPaciente" data-id="<?=$paciente->id_paciente?>">Editar datos</a>
                                            </li>
                                            <li>
                                                <a href="javascript:;" onclick="javascript:Desactiva(<?=$paciente->id_paciente?>)">Eliminar paciente</a>
                                            </li>
                                            <li class="divider"> </li>
                                            <li>
                                                <a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#nuevaCita" data-id-agenda="<?=$paciente->id_paciente?>">Agendar cita</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <? else: ?>
										<a role="button" class="btn btn-xs btn-warning btn_<?=$paciente->id_paciente?>" onclick="javascript:Activa(<?=$paciente->id_paciente?>)">Activar</a>
									<? endif; ?>
								</td>
					        </tr>
					      <? endforeach; ?>
					    </tbody>
					</table>
					<? else: ?>
					<div class="alert alert-dismissable alert-warning">
				  		<button type="button" class="close" data-dismiss="alert">×</button>
				  		<p>Aún no se han creado prospectos</p>
				  	</div>
					<? endif; ?>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
	</div>
</div>










<!-- Nuevo Paciente -->
<div class="modal fade" id="nuevoPaciente">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Nuevo Paciente</h4>
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
					<input type="text" maxlength="92" class="form-control dat" name="email" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="direccion" class="col-md-3 control-label">Canal</label>
				<div class="col-md-9">
					<select class="form-control" name="id_canal">
                    	<option value="0">Seleccione uno</option>
                    	<? foreach($canales as $canal): ?>
						<option value="<?=$canal->id_canal?>"><?=$canal->canal?></option>
						<? endforeach; ?>
					</select>
				</div>
			</div>

		</form>
		      
      </div>
      <div class="modal-footer">
      	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load" width="25" class="oculto" />
        <button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn_ac" onclick="nuevoPaciente()">Guardar Prospecto</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->




<!-- Edita Paciente -->
<div class="modal fade" id="editaPaciente">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Datos del Paciente</h4>
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
				<label for="direccion" class="col-md-3 control-label">Canal</label>
				<div class="col-md-9">
					<select class="form-control" name="id_canal" id="id_canal">
                    	<option value="0">Seleccione uno</option>
                    	<? foreach($canales as $canal): ?>
						<option value="<?=$canal->id_canal?>"><?=$canal->canal?></option>
						<? endforeach; ?>
					</select>
				</div>
			</div>
			
			<input type="hidden" name="id_paciente" id="id_paciente" />
		</form>
		      
      </div>
      <div class="modal-footer">      	
      	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load2" width="25" class="oculto" />
        <button type="button" class="btn btn-default btn_ac btn-modal" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn_ac btn-modal" onclick="editaPaciente()">Actualizar Paciente</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->









<!-- Citas -->
<div class="modal fade" id="nuevaCita">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
				<h4 class="modal-title">Nueva cita para <label id="nombre_cita"></label></h4>
			</div>
			
			<div class="modal-body">
				<div class="alert alert-danger oculto" role="alert" id="msg_error4"></div>
				<!-- Loader -->
				<div class="row oculto" id="load_big3">
					<div class="col-md-12 text-center" >
						<img src="assets/global/img/ajax-loading.gif" border="0"  />
					</div>
				</div>
				<!--Formulario -->
				<form id="frm_agenda" class="form-horizontal">
					
					<div class="form-group">
						<label for="direccion" class="col-md-3 control-label">Clínica</label>
						<div class="col-md-9">
							<select class="form-control" name="id_clinica" id="id_clinica" >
								<option value="0">Seleccione una clínica</option>
								<? foreach($clinicas as $clinica): ?>
								<option value="<?=$clinica->id_clinica?>"><?=$clinica->clinica?></option>
								<? endforeach; ?>
								<? if($s_tipo==3): ?>
									<? foreach($clinicas2 as $clinica2): ?>
									<option value="<?=$clinica2->id_clinica?>"><?=$clinica2->clinica?></option>
									<? endforeach; ?>
								<? endif; ?>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label for="direccion" class="col-md-3 control-label">Especialista</label>
						<div class="col-md-9">
							<select class="form-control" name="id_especialista" id="id_especialista" >
								<option value="0">Seleccione un especialista</option>
								<? foreach($especialistas as $especialista): ?>
								<option value="<?=$especialista->id_especialista?>"><?=$especialista->nombre?></option>
								<? endforeach; ?>
							</select>
						</div>
					</div>
					
					<div class="form-group">
                        <label class="col-md-3 control-label">Agregar a</label>
                        <div class="col-md-9">
                            <div class="mt-checkbox-inline">
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox" name="blanqueamientos"> Blanqueamientos
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
					
					<div class="form-group">
						<label for="direccion" class="col-md-3 control-label">Tratamiento</label>
						<div class="col-md-9">
							<select class="form-control" name="id_tratamiento">
								<option value="0">Seleccione un tratamiento</option>
								<? foreach($tratamientos as $tratamiento): ?>
								<option value="<?=$tratamiento->id_tratamiento?>"><?=$tratamiento->tratamiento?></option>
								<? endforeach; ?>
							</select>
						</div>
					</div>
					
					<? if($valida_promociones): ?>
					<div class="form-group">
						<label for="direccion" class="col-md-3 control-label">Promoción</label>
						<div class="col-md-9">
							<select class="form-control" name="id_promocion">
								<option value="0">Seleccione una</option>
								<? foreach($promociones as $promocion): ?>
								<option value="<?=$promocion->id_promocion?>"><?=$promocion->promocion?></option>
								<? endforeach; ?>
							</select>
						</div>
					</div>
					<? endif; ?>
					
					<div class="form-group">
						<label for="nombre" class="col-md-3 control-label">Inicia</label>
						<div class="col-md-9">
							<div class="input-group date form_meridian_datetime" >
                                <input type="text" size="16" class="form-control" style="width: 220px;">
                                <span class="input-group-btn">
                                    <button class="btn default date-reset oculto" type="button">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set oculto" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
						</div>
					</div>
					
					<div class="form-group">
						<label for="nombre" class="col-md-3 control-label">Termina</label>
						<div class="col-md-9">
							<div class="input-group date form_meridian_datetime_2">
                                <input type="text" size="16" class="form-control" style="width: 220px;">
                                <span class="input-group-btn">
                                    <button class="btn default date-reset oculto" type="button">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set oculto" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
						</div>
					</div>
					
					<div class="form-group">
						<label for="nombre" class="col-md-3 control-label">Color</label>
						<div class="col-md-9">
							<select class="bs-select form-control" data-show-subtext="false" name="color">
								<option>Seleccione un color</option>
								<option data-content="<span class='label label-xs' style='background-color:#4B77BE;'>&nbsp;&nbsp;&nbsp;&nbsp;</span> Azul" value="#4B77BE">Azul</option>
								<option data-content="<span class='label label-xs' style='background-color:#26C281;'>&nbsp;&nbsp;&nbsp;&nbsp;</span> Verde" value="#26C281">Verde</option>
								<option data-content="<span class='label label-xs' style='background-color:#2F353B;'>&nbsp;&nbsp;&nbsp;&nbsp;</span> Negro" value="#2F353B">Negro</option>
								<option data-content="<span class='label label-xs' style='background-color:#D91E18;'>&nbsp;&nbsp;&nbsp;&nbsp;</span> Rojo" value="#D91E18">Rojo</option>
								<option data-content="<span class='label label-xs' style='background-color:#E87E04;'>&nbsp;&nbsp;&nbsp;&nbsp;</span> Naranja" value="#E87E04">Naranja</option>
								<option data-content="<span class='label label-xs' style='background-color:#8E44AD;'>&nbsp;&nbsp;&nbsp;&nbsp;</span> Morado" value="#8E44AD">Morado</option>
								<option data-content="<span class='label label-xs' style='background-color:#F7CA18;'>&nbsp;&nbsp;&nbsp;&nbsp;</span> Amarillo" value="#F7CA18">Amarillo</option>
								<option data-content="<span class='label label-xs' style='background-color:#ACB5C3;'>&nbsp;&nbsp;&nbsp;&nbsp;</span> Gris" value="#ACB5C3">Gris</option>
								<option data-content="<span class='label label-xs' style='background-color:#32C5D2;'>&nbsp;&nbsp;&nbsp;&nbsp;</span> Turquesa" value="#32C5D2">Turquesa</option>
								<option data-content="<span class='label label-xs' style='background-color:#555555;'>&nbsp;&nbsp;&nbsp;&nbsp;</span> Café" value="#555555">Café</option>
							</select>
						</div>
					</div>
					

					<div class="form-group" id="doctor" style="display: none;">
						
					</div>

					<div class="form-group">
						<label for="descripcion" class="col-md-3 control-label">Comentarios</label>
						<div class="col-md-9">
							<textarea class="form-control dat" autocomplete="off" name="comentarios" rows="3"></textarea>
						</div>
					</div>
					
					<input type="hidden" name="id_paciente_agenda" id="id_paciente_agenda" />
					<input type="hidden" name="fecha_hora" id="fecha_hora" />
					<input type="hidden" name="fecha_hora_final" id="fecha_hora_final" />
			
				</form>
			</div>
			
			<div class="modal-footer">
				<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load4" width="25" class="oculto" />
				<button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-success btn_ac" onclick="agendaCita()">Agendar Cita</button>
			</div>
		</div>
	</div>
</div>







<!--- Js -->
<script>
$(function(){
	
	//Para la fecha final
	$(".form_meridian_datetime_2").datetimepicker({
		isRTL: App.isRTL(),
		format: "dd MM yyyy - HH:ii P",
		showMeridian: true,
		autoclose: true,
		minuteStep: 30,
		pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left"),
		linkField: "fecha_hora_final",
		linkFormat: "yyyy-mm-dd hh:ii"
    });
	
	//Para traer los doctores
	$('#id_clinica').change(function(){
		var id_clinica = $('#id_clinica').val();
		$.ajax({
			url: "data/select_doctores.php",
	   		data: 'id_clinica='+id_clinica,
	   		success: function(data){
		   		$('#doctor').html(data);
		   		$('#doctor').show();
	   	},
	   	cache: false
	   	});
		
	});

	$(document).on('click', '[data-id]', function () {
		$('.edit').val("");
		$('.btn-modal').hide();
		$('#frm_edita').hide();
		$('#load_big').show();
	    var data_id = $(this).attr('data-id');
	    $.ajax({
		url: "data/pacientes.php",
		data: 'id_paciente='+data_id,
		success: function(data){
			var datos = data.split('|');
			$('#id_canal').val(datos[0]);
			$('#nombre').val(datos[1]);
			$('#telefono').val(datos[2]);
			$('#email').val(datos[3]);
			$('#id_paciente').val(data_id);


	   		$('#load_big').hide();
	   		$('#frm_edita').show();
	   		$('.btn-modal').show();
	  	
	   	},
	   	cache: false
	   });
	});

	$(document).on('click', '[data-id-agenda]', function () {
		$('.edit').val("");
		$('.btn-modal').hide();
		$('#frm_agenda').hide();
		$('#load_big3').show();
	    var data_id = $(this).attr('data-id-agenda');
	    $.ajax({
	   	url: "data/seguimiento_paciente.php",
	   	data: 'id_paciente='+data_id,
	   	success: function(data){
		   	var datos = data.split('|');
	   		$('#nombre_cita').html(datos[0]);
	   		$('#id_paciente_agenda').val(data_id);
	   		
	   		
	   		$('#load_big3').hide();
	   		$('#frm_agenda').show();
	   		$('.btn-modal').show();
	  	
	   	},
	   	cache: false
	   });
	});
	
	$('#NuevoProspecto').on('shown.bs.modal',function(e){
		$('#nuevo_nombre').focus();
	});
	
	
	$('#NuevoProspecto').on('hidden.bs.modal',function(e){
		$('.dat').val("");
		$('#msg_error2').hide();
		$('#msg_error').hide();
	});
	
	$('#editaPaciente').on('hidden.bs.modal',function(e){
		$('.edit').val("");
		$('#id_canal').val("0");
		$('#msg_error2').hide();
		$('#msg_error').hide();
	});
	
	
	$('form').submit(function(e){
		e.preventDefault();	
	});
	
	$('#tabla_pacientes').dataTable({
		language: {
			url: 'assets/global/plugins/datatables/spanish.js'
		},
		"bStateSave": false,
		"lengthMenu": [
			[20, 35, 50, -1],
			[20, 35, 50, "Todos"]
		],
		"pageLength": 20,            
		"pagingType": "bootstrap_full_number",
		"columnDefs": [
			{ 
				'orderable': false,
				'targets': [1,2,3,4,5,6]
			}, 
			{
				"searchable": false,
				"targets": [5,6]
			},
			{
				"className": "dt-right", 
				//"targets": [2]
			}
		],
		"order": [
			[0, "asc"]
		]
	});

});

function editaPaciente(){
	$('#msg_error2').hide('Fast');
	$('.btn_ac').hide();
	$('#load2').show();
	var datos=$('#frm_edita').serialize();
	$.post('ac/edita_paciente.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Pacientes&msg=2", "_self");
	    }else{
	    	$('#load2').hide();
			$('.btn').show();
			$('#msg_error2').html(data);
			$('#msg_error2').show('Fast');
	    }
	});
}
function Desactiva(id){
	swal({
		title: "Eliminar paciente",
		text: "¿Estás seguro que quieres eliminar al paciente?",
		type: "warning",
		confirmButtonText: "Si, eliminar",
		cancelButtonText: "Cancelar",
		showCancelButton: true,
		closeOnConfirm: false,
		showLoaderOnConfirm: true
	}, function () {
		$.post('ac/activa_desactiva_paciente.php', { tipo: "0", id_paciente: id },function(data){
			if(data==1){
				$(".tr_"+id+"").hide();
				swal("Paciente eliminado", "", "success");
			}else{
				swal("Error", data, "success");
			}
		});
	});
	
	
}
function Activa(id){
	$(".btn_"+id+"").hide();
	$("#load_"+id+"").show();
	$.post('ac/activa_desactiva_paciente.php', { tipo: "1", id_paciente: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=Prospectos", "_self");
		}else{
			$("#load_"+id+"").hide();
			$(".btn_"+id+"").show();
			alert(data);
		}
	});
}
function nuevoPaciente(){
	$('#msg_error').hide('Fast');
	$('.btn_ac').hide();
	$('#load').show();
	var datos=$('#frm_guarda').serialize();
	$.post('ac/nuevo_paciente.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Pacientes&msg=1", "_self");
	    }else{
	    	$('#load').hide();
			$('.btn').show();
			$('#msg_error').html(data);
			$('#msg_error').show('Fast');
	    }
	});
}

function ActualizaSeguimiento(){
	$('#msg_error3').hide('Fast');
	$('.btn_ac').hide();
	$('#load3').show();
	var datos=$('#frm_seguimiento').serialize();
	$.post('ac/actualiza_seguimiento.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Prospectos&msg=3", "_self");
	    }else{
	    	$('#load3').hide();
			$('.btn').show();
			$('#msg_error3').html(data);
			$('#msg_error3').show('Fast');
	    }
	});
}

function agendaCita(){
	$('#msg_error4').hide('Fast');
	$('.btn_ac').hide();
	$('#load4').show();
	var datos=$('#frm_agenda').serialize();
	$.post('ac/nueva_cita.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Agenda", "_self");
	    }else{
	    	$('#load4').hide();
			$('.btn').show();
			$('#msg_error4').html(data);
			$('#msg_error4').show('Fast');
	    }
	});
}
</script>