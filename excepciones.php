<?
$id_evento = $_GET['id'];

$nom = mysql_fetch_array(mysql_query("SELECT eventos.descripcion,clinicas.clinica FROM eventos JOIN clinicas ON clinicas.id_clinica = eventos.id_clinica WHERE id_evento = $id_evento"));

$sql = "SELECT eventos_excepciones.*,eventos.descripcion FROM eventos_excepciones JOIN eventos ON eventos.id_evento = eventos_excepciones.id_evento WHERE eventos_excepciones.id_evento = $id_evento";
$excepciones = mysql_query($sql);

$val = mysql_num_rows($excepciones);
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
				  		<p>Se ha agregado una excepción</p>
				  	</div>
			  <? }elseif($_GET['msg']==2){ ?>
			  		<br>
			  		<div class="alert alert-dismissable alert-success">
				  		<button type="button" class="close" data-dismiss="alert">×</button>
				  		<p>Se ha eliminado su excepción</p>
				  	</div>
			  <? } ?>
			  <!-- Contenido -->
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet light  portlet-fit">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-book-open font-dark"></i>
						<span class="caption-subject font-dark bold uppercase">Excepciones Evento - <?=$nom[0]?>/<?=$nom[1]?></span>
					</div>
					<div class="actions btn-set">
						<a href="javascript:;" class="btn btn-sm green-jungle" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#nuevoEventoRecurrente"> Nueva Excepción</a>
					</div>
				</div>
				<div class="portlet-body">
					<? if($val>0):?>
					<table class="table table-striped table-bordered table-hover" id="tabla_citas">
						<thead>
					        <tr>
								<th>Excepción</th>
								<th>Inicio</th>
								<th>Final</th>
								<th>Creado</th>
								<th width="70"></th>
					        </tr>
					    </thead>
					    <tbody>
					      <? while($e = mysql_fetch_assoc($excepciones)){ ?>
					        <tr class="tr_<?=$e['id_excepcion']?>">
						        <td><?=$e['comentarios']?></td>
								<td><?=devuelveFechaHora($e['fecha_hora'])?></td>
								<td><?=devuelveFechaHora($e['fecha_hora_final'])?></td>
								<td><?=devuelveFechaHora($e['fecha_creado'])?></td>
								<td>

									<div class="btn-group" style="margin-top: 1px">
                                	    <a class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Opciones
                                	        <i class="fa fa-angle-down"></i>
                                	    </a>
                                	    <ul class="dropdown-menu">
                                	        <li>
                                	            <a href="javascript:;" onclick="javascript:cancelaExcepcion(<?=$e['id_excepcion']?>)">Cancelar Excepción</a>
                                	        </li>
                                	        
                                	    </ul>
                                	</div>
                                	
								</td>
					        </tr>
					      <? } ?>
					    </tbody>
					</table>
					<? else: ?>
					<div class="alert alert-dismissable alert-warning">
				  		<button type="button" class="close" data-dismiss="alert">×</button>
				  		<p>Aún no se han creado excepciones</p>
				  	</div>
					<? endif; ?>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
	</div>
</div>






<!-- Nuevo Evento Recurrente -->
<div class="modal fade" id="nuevoEventoRecurrente">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
				<h4 class="modal-title">Nueva Excepción</h4>
			</div>
			
			<div class="modal-body">
				<div class="alert alert-danger oculto" role="alert" id="msg_error_recurrente"></div>
				<!-- Loader -->
				<div class="row oculto" id="load_big3">
					<div class="col-md-12 text-center" >
						<img src="assets/global/img/ajax-loading.gif" border="0"  />
					</div>
				</div>
				<!--Formulario -->
				<form id="frm_evento_recurrente" class="form-horizontal">
					
					
					<div class="form-group">
					    <label class="control-label col-md-3">Duración</label>
					    <div class="col-md-9">
					        <div class="input-group input-large date-picker input-daterange" data-date="" data-date-format="yyyy-mm-dd">
								<input type="text" class="form-control r_limpia" name="fecha1" id="">
								<span class="input-group-addon"> a </span>
								<input type="text" class="form-control r_limpia" name="fecha2" id=""> 
							</div>
					    </div>
					</div>
					
					<div class="form-group">
                        <label class="control-label col-md-3">Hora de Inicio</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" class="form-control timepicker timepicker-24" name="hora1">
                                <span class="input-group-btn">
                                    <button class="btn default" type="button">
                                        <i class="fa fa-clock-o"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-md-3">Hora Final</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" class="form-control timepicker timepicker-24" name="hora2">
                                <span class="input-group-btn">
                                    <button class="btn default" type="button">
                                        <i class="fa fa-clock-o"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    

					<div class="form-group">
						<label for="descripcion" class="col-md-3 control-label">Comentarios</label>
						<div class="col-md-9">
							<textarea class="form-control dat" autocomplete="off" name="comentarios" rows="3"></textarea>
						</div>
					</div>
					
					<input type="hidden" name="id_evento" value="<?=$id_evento?>">
				</form>
			</div>
			
			<div class="modal-footer">
				<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load4" width="25" class="oculto" />
				<button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-success btn_ac" onclick="nuevoEventoRecurrente()">Agregar Excepción</button>
			</div>
		</div>
	</div>
</div>




<!--- Js -->
<script>
$(function(){
	
	
	$('#tabla_citas').dataTable({
		language: {
			url: 'assets/global/plugins/datatables/spanish.js'
		},
		"bStateSave": true,
		"lengthMenu": [
			[20, 35, 50, -1],
			[20, 35, 50, "Todos"]
		],
		"pageLength": 20,            
		"pagingType": "bootstrap_full_number",
		"columnDefs": [
			{ 
				'orderable': false,
				'targets': [4]
			}, 
			{
				"searchable": false,
				"targets": [4]
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
	
	//Para la fecha final
	$(".form_meridian_datetime_2").datetimepicker({
		isRTL: App.isRTL(),
		format: "dd MM yyyy - HH:ii P",
		showMeridian: true,
		autoclose: true,
		minuteStep: 30,
		pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left"),
		todayBtn: true,
		linkField: "fecha_hora_final",
		linkFormat: "yyyy-mm-dd hh:ii"
    });
    
    $(".form_meridian_datetime_3").datetimepicker({
		isRTL: App.isRTL(),
		format: "dd MM yyyy - HH:ii P",
		showMeridian: true,
		autoclose: true,
		minuteStep: 30,
		pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left"),
		todayBtn: true,
		linkField: "edita_fecha_hora_final",
		linkFormat: "yyyy-mm-dd hh:ii"
    });
    
    $(".form_meridian_datetime_4").datetimepicker({
		isRTL: App.isRTL(),
		format: "dd MM yyyy - HH:ii P",
		showMeridian: true,
		autoclose: true,
		minuteStep: 30,
		pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left"),
		todayBtn: true,
		linkField: "edita_fecha_hora",
		linkFormat: "yyyy-mm-dd hh:ii"
    });
	
			
	$('form').submit(function(e){
		e.preventDefault();	
	});
	
	
});

function cancelaExcepcion(id){
	swal({
		title: "Cancelar Excepción",
		text: "¿Estás seguro que quieres cancelar la excepción?",
		type: "warning",
		confirmButtonText: "Si, cancelar",
		cancelButtonText: "No",
		showCancelButton: true,
		closeOnConfirm: false,
		showLoaderOnConfirm: true
	}, function () {
		$.post('ac/cancela_excepcion.php', { id: id },function(data){
			if(data==1){
				swal({
				title: "Excepción Cancelada",
				type: "success",
				confirmButtonText: "Ok",
				}, function () {
					window.open("?Modulo=Excepciones&id=<?=$id_evento?>", "_self");
				});
			}else{
				swal("Error", data, "success");
			}
		});
	});
}

function nuevoEventoRecurrente(){
	$('#msg_error_recurrente').hide('Fast');
	$('.btn_ac').hide();
	$('#load4').show();
	var datos=$('#frm_evento_recurrente').serialize();
	$.post('ac/nueva_excepcion.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Excepciones&msg=1&id=<?=$id_evento?>", "_self");
	    }else{
		    console.log(data);
	    	$('#load4').hide();
			$('.btn').show();
			$('#msg_error_recurrente').html(data);
			$('#msg_error_recurrente').show('Fast');
	    }
	});
}

</script>