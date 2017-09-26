<?php
if(is_numeric($_GET['id_clinica'])):
	$id_clinica_event = "?id_clinica=".$_GET['id_clinica'];
	$nombreClinica = @dameClinica($_GET['id_clinica']);
	$id_alter = $_GET['id_clinica'];
	if(!$nombreClinica):
		$nombreClinica = 'Todos';
		$id_clinica_event = '';
		$id_alter = '';
	endif;
else:
	if($s_tipo==3):
		$id_clinica_event = "?id_clinica=".$s_id_clinica;
		$nombreClinica = @dameClinica($s_id_clinica);
		$id_alter = $s_id_clinica;
		if(!$nombreClinica):
			$nombreClinica = 'Todos';
			$id_clinica_event = '';
			$id_alter = '';
		endif;
	else:
		$nombreClinica = 'Todos';
	endif;
endif;
//Pacientes
$q=mysql_query("SELECT id_paciente,nombre FROM pacientes WHERE tipo=1 AND activo=1");
$pacientes = array();
while($datos=mysql_fetch_object($q)):
	$pacientes[] = $datos;
endwhile;

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

//Especialistas
$sql="SELECT id_especialista_lab,nombre FROM especialistas_lab WHERE activo=1 AND tipo=1";
$q=mysql_query($sql);
$especialistas=array();
while($datos=mysql_fetch_object($q)):
	$especialistas[] = $datos;
endwhile;

?>

<input type="hidden" id="contador" value=""/>
<div class="page-content-inner">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light portlet-fit  calendar">
                <div class="portlet-title">
                    <div class="caption">
                        <i class=" icon-layers font-dark"></i>
                        <span class="caption-subject font-dark sbold uppercase">Agenda de <?=$nombreClinica?></span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div id="calendar" class="has-toolbar"> </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
/*
setTimeout(function() {
	var event={"id":"21111","title":"EVENTO 14:00 DENTIS+A CHETUMAL","start":"2017-03-05T14:00:00","end":"2017-03-03T15:00:00","allDay":false,"backgroundColor":"#2F353B"};
	$('#calendar').fullCalendar( 'renderEvent', event, true);
}, 2000);
*/

$(function() {
	
	setTimeout(function() {
		var numero_actual = 0;
		$.getJSON('eventos2.php<?=$id_clinica_event?>',function(data) {
			numero_actual = Object.keys(data).length;
			console.log('SET '+numero_actual);
			$('#contador').val(numero_actual);
		});
		
	}, 1000);


	setInterval(function() {
		listener();
	}, 180000);

	$(document).on('click', '[data-id]', function () {
		$('#tiene_blanqueamientos_reagenda').prop('checked',false);
		$('.edit').val("");
		$('.btn-modal').hide();
		$('#frm_cambia_cita').hide();
		$('#load_big').show();
	    var data_id = $(this).attr('data-id');
	    $.ajax({
	   	url: "data/cita.php",
	   	data: 'id_cita='+data_id,
	   	success: function(data){
		   	console.log("Datos Cita:"+data);
		   	var datos = data.split('|');
		   	var id_clinica=datos[1];
		   	var id_usuario=datos[9];
	   		$('#nombre_cita_reagenda').html(datos[0]);
	   		$('#id_clinica_reagenda').val(id_clinica);
	   		$('#id_tratamiento_reagenda').val(datos[2]);
	   		$('#id_especialista_reagenda').val(datos[12]);
	   		$('#id_promocion_reagenda').val(datos[3]);
	   		$('#fecha_inicia').val(datos[4]);
	   		$('#fecha_hora').val(datos[5]);
	   		$('#fecha_final').val(datos[6]);
	   		$('#fecha_hora_final').val(datos[7]);
	   		$('#color').val(datos[8]);
	   		$('#comentarios_reagenda').val(datos[10]);
	   		$.get('data/select_doctores.php', {id_clinica:id_clinica,id_usuario:id_usuario},function(data){
		   		$('#doctor').html(data);
	   		});
	   		$('#id_cita').val(data_id);	   		
	   		$('#load_big').hide();
	   		$('#frm_cambia_cita').show();
	   		$('.btn-modal').show();
	   		
	   		if(datos[11]==1){
		   		$('#tiene_blanqueamientos').prop('checked',true);
	   		}
	   		
	   	},
	   	cache: false
	   });
	});

    
});


function listener(){
	var numero_actual = 0;
	$.getJSON('eventos2.php<?=$id_clinica_event?>',function(data) {
		
		numero_actual 	= Number(Object.keys(data).length);
		var saved		= Number($('#contador').val());
		
		console.log('REFRESH '+numero_actual+' - '+saved);

		if(saved>0){
			if(saved!=numero_actual){
				console.log('BYE');
				window.location.reload();
			}
		}	
	});
}



var AppCalendar = function() {

    return {
        //main function to initiate the module
        init: function() {
            this.initCalendar();
        },

        initCalendar: function() {

            if (!jQuery().fullCalendar) {
                return;
            }

            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();
            var h = {};

            if ($('#calendar').parents(".portlet").width() <= 720) {
                $('#calendar').addClass("mobile");
                h = {
                    left: 'title, prev, next',
                    center: '',
                    right: 'month'
                };
            } else {
                $('#calendar').removeClass("mobile");
                h = {
                    left: 'title',
                    center: '',
                    right: 'prev,next,agendaDay,today,agendaWeek,month'
                };
            }

			$('#VerDetalle').on('hidden.bs.modal', function () {
			  	$('.inp').html('Cargando...');
			});

            $('#calendar').fullCalendar('destroy');
            $('#calendar').fullCalendar({
	            lang: 'es',
                header: h,
                defaultView: 'month',
                slotMinutes: 15,
                editable: false,
                droppable: false,
                displayEventTime: false,
                events: 'eventos2.php<?=$id_clinica_event?>',
				
				
		        eventClick: function (calEvent, jsEvent, view) {
					var fecha_click_mames = calEvent.start.format();
					
					$.getJSON('data/agenda.php', {id_cita:calEvent.id,fecha_click:fecha_click_mames} ,function(data) {
						console.log(data);
						var tipo = data.tipo;
						if(data.atendida==1){
							var confirmada = '<u>Atendida</u>';
						}else{
							var confirmada = (data.confirmada==1) ? '<u>Confirmada</u>' : 'Por Confirmar';
						}
						if(tipo==1){
							$('#evento').hide();
							$('#cita').show();
							$('#modal-title').html("Detalle de la Cita");
							$('#estado').html(confirmada);
							$('#paciente').html(data.nombre);
							$('#telefono').html(data.telefono);
							$('#fecha').html(data.fecha_hora);
							$('#clinica').html(data.clinica);
							$('#tratamiento').html(data.tratamiento);
							if(data.promocion){
								$('#promocion').html(data.promocion);
							}else{
								$('#promocion').html("N/A");
							}
							
							if(data.especialista){
								$('#especialista').html(data.especialista);
							}else{
								$('#especialista').html("N/A");
							}
							$('#comentarios').html(data.comentario);
							$('#md-ft').append('<a href="javascript:;" onclick="javascript:cancelaCita('+data.id_cita+')"  class="btn btn-danger btn-cancelar">Cancelar Cita</a>');
							$('#md-ft').append('<a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#cambiaCita" data-id="'+data.id_cita+'"  class="btn btn-warning btn-cancelar">Reagendar</a>');
							if(data.confirmada!=1){
								$('#md-ft').append('<a href="javascript:;" onclick="javascript:confirmaCita('+data.id_cita+')"  class="btn btn-info btn-cancelar">Confirma Cita</a>');
							}
							if(data.atendida==0){
							$('#md-ft').append('<a href="?Modulo=Consulta&id='+data.id_cita+'" class="btn btn-success btn-atender">Atender</a>');
							}
							if(data.agendado==0){
								$('#agendado').hide();
							}else{
								$('#agendado').show();
								$('#agendo').html(data.agendado);
							}
						}else{
							
							$('#cita').hide();
							$('#evento').show();
							$('.modal-title').html("Detalle del Evento");
							$('#e_usuario').html(data.nombre_dr);
							$('#e_fecha').html(data.fecha_hora);
							$('#e_fecha2').html(data.fecha_hora_final);
							$('#e_clinica').html(data.clinica);
							$('#e_comentarios').html(data.comentario);
							$('#md-ft').append('<a href="?Modulo=NExcepcion&id='+data.id_cita+'&fecha='+calEvent.start.format()+'" class="btn btn-success btn-excepcion">Agregar Excepción</a>');
						}
						
					});
					
					$('#VerDetalle').modal('show');

		        },
		        
		        dayClick: function(date, jsEvent, view) {
			        
			        	var fecha_completa = date.format();
			        	fecha_completa = fecha_completa.split('T');
			        	var fecha_bien = fecha_completa[0];
			        	var hora = fecha_completa[1];
			        	var hora2;
			        	if(hora){ hora2 = hora.split(':'); hora2 = Number(hora2[0])+1; hora2 = hora2+":00";}
		        
		                console.log('Clicked on: ' + date.format());
		        
		                console.log(fecha_bien);
		        
		                console.log(hora);
		                
		                $('#fecha1_agenda').val(fecha_bien);
		                $('#fecha2_agenda').val(fecha_bien);
		                $('#hora1').val(hora);
		                $('#hora2').val(hora2);
						$('#nuevaCita').modal('toggle');
		                // change the day's background color just for fun
		                //$(this).css('background-color', 'red');
		        
		            },
		        
		        viewRender: function(view, element) {
			
			        console.log(view.intervalStart.format());
			
			    }
		        
/***/
            });
			
			
			
        }

    };

}();

jQuery(document).ready(function() {    
   AppCalendar.init();
   var moment = $('#calendar').fullCalendar('getDate');
   console.log(moment.month()); 
   var view = $('#calendar').fullCalendar('getView');
   //alert("The view's title is " + view.start.format());
});
$(function(){
	$('#VerDetalle').on('hidden.bs.modal',function(e){
		$('.btn-atender').hide();
		$('.btn-excepcion').hide();
		$('.btn-cancelar').hide();
	});
	
	$('#nuevaCita').on('hidden.bs.modal',function(e){
		$('.edit').val("");
		$('#msg_error4').hide();
	});
	
	$('#cambiaCita').on('shown.bs.modal',function(e){
		$('#VerDetalle').modal('toggle');
	});
	
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
});


function agendaCita(){
	$('#msg_error4').hide('Fast');
	$('.btn_ac').hide();
	$('#load4').show();
	var datos=$('#frm_agenda').serialize();
	$.post('ac/nueva_cita_agenda.php',datos,function(data){
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

function cancelaCita(id){
	$('#VerDetalle').modal('toggle');
	swal({
		title: "Cancelar Cita",
		text: "¿Estás seguro que quieres cancelar la cita?",
		type: "warning",
		confirmButtonText: "Si, cancelar",
		cancelButtonText: "No",
		showCancelButton: true,
		closeOnConfirm: false,
		showLoaderOnConfirm: true
	}, function () {
		$.post('ac/activa_desactiva_cita.php', { tipo: "0", id_cita: id },function(data){
			if(data==1){
				swal({
				title: "Cita Cancelada",
				type: "success",
				confirmButtonText: "Ok",
				}, function () {
					window.open("?Modulo=Agenda", "_self");
				});
			}else{
				swal("Error", data, "success");
			}
		});
	});
}

function cambiaCita(){
	$('#msg_error').hide('Fast');
	$('.btn_ac').hide();
	$('#load').show();
	var datos=$('#frm_cambia_cita').serialize();
	$.post('ac/cambia_cita.php',datos,function(data){
	    if(data==1){
		    <? if($_GET['id_clinica']): ?>
			window.open("?Modulo=Agenda&id_clinica=<?=$_GET['id_clinica']?>", "_self");
			<? else: ?>
			window.open("?Modulo=Agenda", "_self");
			<? endif; ?>
	    }else{
	    	$('#load').hide();
			$('.btn').show();
			$('#msg_error').html(data);
			$('#msg_error').show('Fast');
	    }
	});
}

function confirmaCita(id){
	$('#VerDetalle').modal('toggle');
	swal({
		title: "Confirmar cita",
		text: "¿Estás seguro que quieres confirmar la cita?",
		type: "info",
		confirmButtonText: "Confirmar Cita",
		cancelButtonText: "No",
		showCancelButton: true,
		closeOnConfirm: false,
		showLoaderOnConfirm: true
	},function(){
		$.post('ac/confirma_cita.php', { id_cita: id },function(data){
			if(data==1){
				swal({
				title: "Cita Confirmada",
				type: "success",
				confirmButtonText: "Ok",
				}, function () {
					//window.open("?Modulo=Citas", "_self");
					location.reload();
				});
			}else{
				swal("Error", data, "success");
			}
		});
	});	
}
</script>

<!--- Pra la cita -->
<!-- Modal -->
<div class="modal fade" id="VerDetalle">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
				<h4 class="modal-title" id="modal-title"></h4>
			</div>
			
			<div class="modal-body">
				<!--Formulario -->
				<form id="" class="form-horizontal">
					
					<div id="cita">
						<div class="form-group">
							<label for="descripcion" class="col-md-3 control-label bold">Estado:</label>
							<div class="col-md-9 control-label" style="text-align: left"><span id="estado" class="inp">Cargando...</span></div>
						</div>
						<div class="form-group">
							<label for="descripcion" class="col-md-3 control-label bold">Paciente:</label>
							<div class="col-md-9 control-label" style="text-align: left"><span id="paciente" class="inp">Cargando...</span></div>
						</div>
						<div class="form-group">
							<label for="descripcion" class="col-md-3 control-label bold">Teléfono:</label>
							<div class="col-md-9 control-label" style="text-align: left"><span id="telefono" class="inp">Cargando...</span></div>
						</div>
						<div class="form-group">
							<label for="descripcion" class="col-md-3 control-label bold">Fecha Inicio:</label>
							<div class="col-md-9 control-label" style="text-align: left"><span id="fecha" class="inp">Cargando...</span></div>
						</div>
						<div class="form-group">
							<label for="descripcion" class="col-md-3 control-label bold">Clínica:</label>
							<div class="col-md-9 control-label" style="text-align: left"><span id="clinica" class="inp">Cargando...</span></div>
						</div>
						<div class="form-group">
							<label for="descripcion" class="col-md-3 control-label bold">Especialista:</label>
							<div class="col-md-9 control-label" style="text-align: left"><span id="especialista" class="inp">Cargando...</span></div>
						</div>
						<div class="form-group" id="agendado">
							<label for="descripcion" class="col-md-3 control-label bold">Agendó:</label>
							<div class="col-md-9 control-label" style="text-align: left"><span id="agendo" class="inp">Cargando...</span></div>
						</div>
						<div class="form-group">
							<label for="descripcion" class="col-md-3 control-label bold">Tratamiento:</label>
							<div class="col-md-9 control-label" style="text-align: left"><span id="tratamiento" class="inp">Cargando...</span></div>
						</div>
						<div class="form-group">
							<label for="descripcion" class="col-md-3 control-label bold">Promoción:</label>
							<div class="col-md-9 control-label" style="text-align: left"><span id="promocion" class="inp">Cargando...</span></div>
						</div>
						<div class="form-group">
							<label for="descripcion" class="col-md-3 control-label bold">Comentarios:</label>
							<div class="col-md-9 control-label" style="text-align: left"><span id="comentarios" class="inp">Cargando...</span></div>
						</div>
					</div>
					
					<div id="evento">
						<div class="form-group">
							<label for="descripcion" class="col-md-3 control-label bold">Usuario:</label>
							<div class="col-md-9 control-label" style="text-align: left"><span id="e_usuario" class="inp">Cargando...</span></div>
						</div>
						<div class="form-group">
							<label for="descripcion" class="col-md-3 control-label bold">Fecha Inicio:</label>
							<div class="col-md-9 control-label" style="text-align: left"><span id="e_fecha" class="inp">Cargando...</span></div>
						</div>
						<div class="form-group">
							<label for="descripcion" class="col-md-3 control-label bold">Fecha Final:</label>
							<div class="col-md-9 control-label" style="text-align: left"><span id="e_fecha2" class="inp">Cargando...</span></div>
						</div>
						<div class="form-group">
							<label for="descripcion" class="col-md-3 control-label bold">Clínica:</label>
							<div class="col-md-9 control-label" style="text-align: left"><span id="e_clinica" class="inp">Cargando...</span></div>
						</div>
						<div class="form-group">
							<label for="descripcion" class="col-md-3 control-label bold">Comentarios:</label>
							<div class="col-md-9 control-label" style="text-align: left"><span id="e_comentarios" class="inp">Cargando...</span></div>
						</div>
						
					</div>

				</form>
			</div>
			
			<div class="modal-footer" id="md-ft">
				<button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cerrar</button>
<!--				<button type="button" class="btn btn-info btn_ac" onclick="">Cambiar Cita</button>
				<button type="button" class="btn btn-danger btn_ac" onclick="">Cancelar Cita</button>
				<button type="button" class="btn btn-success btn_ac" onclick="">Confirmar Cita</button>-->
			</div>
		</div>
	</div>
</div>


<!-- Citas -->
<div class="modal fade" id="nuevaCita">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
				<h4 class="modal-title">Nueva cita</h4>
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
						<div class="col-md-12">
							<select class="form-control select2" name="id_paciente" id="id_paciente" >
								<option value="0" >Buscar paciente</option>
								<? foreach($pacientes as $paciente): ?>
								<option value="<?=$paciente->id_paciente?>"><?=$paciente->nombre?></option>
								<? endforeach; ?>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label for="direccion" class="col-md-3 control-label">Clínica</label>
						<div class="col-md-9">
							<select class="form-control edit" name="id_clinica" id="id_clinica" >
								<option value="0">Seleccione una clínica</option>
								<? foreach($clinicas as $clinica): ?>
								<option value="<?=$clinica->id_clinica?>"><?=$clinica->clinica?></option>
								<? endforeach; ?>
								<? if($s_tipo==3): ?>
									<? /*foreach($clinicas2 as $clinica2): ?>
									<option value="<?=$clinica2->id_clinica?>"><?=$clinica2->clinica?></option>
									<? endforeach;*/ ?>
								<? endif; ?>
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
							<select class="form-control select2" name="id_tratamiento">
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
							<select class="form-control select2" name="id_promocion">
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
							<div class="col-md-4">
								<div class="input-group date-picker input-daterange" data-date="" data-date-format="yyyy-mm-dd">
									<input type="text" class="form-control edit" name="fecha1" id="fecha1_agenda">
								</div>
							</div>
							<div class="col-md-4">
							    <div class="input-group">
							        <input type="text" class="form-control timepicker timepicker-24 edit" name="hora1" id="hora1">
							        <span class="input-group-btn">
							            <button class="btn default" type="button">
							                <i class="fa fa-clock-o"></i>
							            </button>
							        </span>
							    </div>
							 </div>
						</div>
					</div>
					
					<div class="form-group">
						<label for="nombre" class="col-md-3 control-label">Termina</label>
						<div class="col-md-9">
							<div class="col-md-4">
								<div class="input-group date-picker input-daterange" data-date="" data-date-format="yyyy-mm-dd">
									<input type="text" class="form-control edit" name="fecha2" id="fecha2_agenda">
								</div>
							</div>
							<div class="col-md-4">
							    <div class="input-group">
							        <input type="text" class="form-control timepicker timepicker-24 edit" name="hora2" id="hora2">
							        <span class="input-group-btn">
							            <button class="btn default" type="button">
							                <i class="fa fa-clock-o"></i>
							            </button>
							        </span>
							    </div>
							 </div>
						</div>
					</div>
					
					<div class="form-group">
						<label for="nombre" class="col-md-3 control-label">Color</label>
						<div class="col-md-9">
							<select class="bs-select form-control edit" data-show-subtext="false" name="color">
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
							<textarea class="form-control edit" autocomplete="off" name="comentarios" rows="3"></textarea>
						</div>
					</div>
					
			
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

<!--- Pra la cita -->
<!-- Modal -->
<div class="modal fade" id="cambiaCita">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
				<h4 class="modal-title">Cambiar cita para <label id="nombre_cita_reagenda"></label></h4>
			</div>
			
			<div class="modal-body">
				<div class="alert alert-danger oculto" role="alert" id="msg_error"></div>
				<!-- Loader -->
				<div class="row oculto" id="load_big">
					<div class="col-md-12 text-center" >
						<img src="assets/global/img/ajax-loading.gif" border="0"  />
					</div>
				</div>
				<!--Formulario -->
				<form id="frm_cambia_cita" class="form-horizontal">
					<div class="form-group">
						<label for="direccion" class="col-md-3 control-label">Clínica</label>
						<div class="col-md-9">
							<select class="form-control" name="id_clinica" id="id_clinica_reagenda" >
								<option value="0">Seleccione una clínica</option>
								<? foreach($clinicas as $clinica): ?>
								<option value="<?=$clinica->id_clinica?>" <?if($clinica->id_clinica==$s_id_clinica):?>selected="1"<?endif;?>><?=$clinica->clinica?></option>
								<? endforeach; ?>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label for="direccion" class="col-md-3 control-label">Especialista</label>
						<div class="col-md-9">
							<select class="form-control" name="id_especialista" id="id_especialista_reagenda" >
								<option value="0">Seleccione un especialista</option>
								<? foreach($especialistas as $especialista): ?>
								<option value="<?=$especialista->id_especialista_lab?>"><?=$especialista->nombre?></option>
								<? endforeach; ?>
							</select>
						</div>
					</div>
					
					<div class="form-group">
                        <label class="col-md-3 control-label">Agregar a</label>
                        <div class="col-md-9">
                            <div class="mt-checkbox-inline">
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox" name="blanqueamientos" value="1" id="tiene_blanqueamientos_reagenda"> Blanqueamientos
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
					
					<div class="form-group">
						<label for="direccion" class="col-md-3 control-label">Tratamiento</label>
						<div class="col-md-9">
							<select class="form-control select2" name="id_tratamiento" id="id_tratamiento_reagenda">
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
							<select class="form-control select2" name="id_promocion" id="id_promocion_reagenda">
								<option value="0">Seleccione una</option>
								<? foreach($promociones as $promocion): ?>
								<option value="<?=$promocion->id_promocion?>"><?=$promocion->promocion?></option>
								<? endforeach; ?>
							</select>
						</div>
					</div>
					<? endif; ?>
					
					<div class="form-group">
						<label for="nombre" class="col-md-3 control-label">Inica</label>
						<div class="col-md-9">
							<div class="input-group date form_meridian_datetime">
                                <input type="text" size="16" class="form-control" id="fecha_inicia" style="width: 220px;">
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
                                <input type="text" size="16" class="form-control" id="fecha_final" style="width: 220px;">
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
							<select class="form-control" data-show-subtext="false" name="color" id="color">
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
					

					<div class="form-group" id="doctor">
						
					</div>
					
					<div class="form-group">
						<label for="descripcion" class="col-md-3 control-label">Comentarios</label>
						<div class="col-md-9">
							<textarea class="form-control dat" autocomplete="off" name="comentarios" id="comentarios_reagenda" rows="3"></textarea>
						</div>
					</div>
					
					<input type="hidden" name="id_cita" id="id_cita" />
					<input type="hidden" name="fecha_hora" id="fecha_hora" />
					<input type="hidden" name="fecha_hora_final" id="fecha_hora_final" />
			
				</form>
			</div>
			
			<div class="modal-footer">
				<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load" width="25" class="oculto" />
				<button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-success btn_ac" onclick="cambiaCita()">Reagendar Cita</button>
			</div>
		</div>
	</div>
</div>
