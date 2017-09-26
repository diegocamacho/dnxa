<?

if($s_tipo==3):
	$consulta=" AND citas.id_clinica=$s_id_clinica";
	$consulta2=" AND id_clinica=$s_id_clinica";
endif;

if($_GET['tipo']==2):
	$tipo=" citas.activo=0 AND citas.atendida=0";
	//$limite="LIMIT 100";
elseif($_GET['tipo']==3):
	$tipo=" citas.activo=1 AND citas.atendida=1";
	//$limite="LIMIT 100";
else:
	$tipo=" citas.activo=1 AND citas.atendida=0";
endif;

if($_GET['fecha1']):
	$fecha1=fechaBase2($_GET['fecha1']);
	$fecha2=fechaBase2($_GET['fecha2']);
	$rangos=" AND fecha_hora BETWEEN '$fecha1 00:00:00' AND '$fecha2 23:59:59' ";
endif;
//$limite="LIMIT 100";
$sql="SELECT usuarios.nombre AS doctor, pacientes.nombre, pacientes.email, pacientes.telefono,pacientes.encuestado, clinicas.clinica, canal, citas.* FROM citas 
JOIN pacientes ON pacientes.id_paciente=citas.id_paciente
JOIN clinicas ON clinicas.id_clinica=citas.id_clinica
LEFT JOIN canales ON canales.id_canal=pacientes.id_canal
LEFT JOIN usuarios ON usuarios.id_usuario=citas.id_usuario
WHERE citas.tipo=1 AND pacientes.activo=1 AND $tipo $consulta $rangos ORDER BY citas.fecha_hora ASC $limite";
/*$sql="SELECT pacientes.nombre, pacientes.telefono, pacientes.encuestado, clinicas.clinica, canal, fecha_hora_creacion, fecha_hora, fecha_hora_final, id_usuario_agendo, id_usuario_reagendo, confirmada, citas.activo, id_cita FROM citas 
JOIN pacientes ON pacientes.id_paciente=citas.id_paciente
JOIN clinicas ON clinicas.id_clinica=citas.id_clinica
LEFT JOIN canales ON canales.id_canal=pacientes.id_canal
WHERE citas.tipo=1 AND pacientes.activo=1 AND $tipo $consulta $rangos ORDER BY citas.fecha_hora ASC $limite";*/
$q=mysql_query($sql);
$citas = array();
while($datos=mysql_fetch_object($q)):
	$citas[] = $datos;
endwhile;
$val=count($citas);

$sql="SELECT * FROM canales WHERE activo=1 ORDER BY canal ASC";
$q=mysql_query($sql);
$canales = array();
while($datos=mysql_fetch_object($q)):
	$canales[] = $datos;
endwhile;


/* PARA LA CITA 
//Clinicas
$sql="SELECT id_clinica,clinica FROM clinicas WHERE activo=1 AND tipo=1 $consulta2";
$q=mysql_query($sql);
$clinicas=array();
while($datos=mysql_fetch_object($q)):
	$clinicas[] = $datos;
endwhile;*/
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


//Especialistas
$sql="SELECT id_especialista_lab,nombre FROM especialistas_lab WHERE activo=1 AND tipo=1";
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
				  		<p>La cita se ha cambiado</p>
				  	</div>
			  <? }elseif($_GET['msg']==3){ ?>
			  		<br>
			  		<div class="alert alert-dismissable alert-success">
				  		<button type="button" class="close" data-dismiss="alert">×</button>
				  		<p>La consulta se ha concluido</p>
				  	</div>
			  <? } ?>
			  <!-- Contenido -->
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet light  portlet-fit">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-book-open font-dark"></i>
						<? if($_GET['tipo']==2): ?>
						<span class="caption-subject font-dark bold uppercase">Citas Canceladas</span>
						<? elseif($_GET['tipo']==3): ?>
						<span class="caption-subject font-dark bold uppercase">Citas Atendidas</span>
						<? else: ?>
						<span class="caption-subject font-dark bold uppercase">Citas</span>
						<? endif; ?>
					</div>
					<div class="actions btn-set">
						<? if($_GET['tipo']): ?>
						<a href="?Modulo=Citas" class="btn btn-sm btn-default"> Regresar </a>&nbsp;&nbsp;&nbsp;
						<a href="javascript:;" class="btn btn-sm blue" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#CambiaFecha"> Filtrar </a>
						<? else: ?>
						<a href="?Modulo=Citas&tipo=3" class="btn btn-sm green"> Citas Atendidas </a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="?Modulo=Citas&tipo=2" class="btn btn-sm red-thunderbird"> Citas Canceladas </a>
						<? endif; ?>
					</div>
				</div>
				<div class="portlet-body">
					<? if($val>0):?>
					<table class="table table-striped table-bordered table-hover" id="tabla_citas">
						<thead>
					        <tr>
								<th>Paciente</th>
								<th>Teléfono</th>
								<th>Canal</th>
								<th>Agendada</th>
								<th>Cita</th>
								<th>Clínica</th>
								<th>Agdo. / Rgdo.</th>
								<th>Enc.</th>
								<? if($_GET['tipo']!=3): ?>
									<th width="100">Estado</th>
									<? if($s_tipo==3):?>
									<th width="120"></th>
									<? else: ?>
									<th width="60"></th>
									<? endif; ?>
								<? endif; ?>
								<? if($_GET['tipo']==3): ?>
								<th width="100"></th>
								<? endif; ?>
					        </tr>
					    </thead>
					    <tbody>
					      <? foreach($citas as $cita): ?>
					        <tr class="tr_<?=$cita->id_cita?>">
								<td><span class="badge" style="background-color: <?=$cita->color?>">&nbsp;&nbsp;</span>&nbsp;&nbsp; <?=$cita->nombre?></td>
								<td><?=$cita->telefono?></td>
								<td><?=$cita->canal?></td>
								<td><?if($cita->fecha_hora_creacion): echo devuelveFechaHora($cita->fecha_hora_creacion); else: echo "N/A"; endif; ?></td>
								<td><?=devuelveFechaHora($cita->fecha_hora)?><br><?=devuelveFechaHora($cita->fecha_hora_final)?></td>
								<td><?=$cita->clinica;?> <?if($cita->doctor):?> <br>(DR. <?=$cita->doctor?>) <?endif;?></td>
								<td><? if($cita->id_usuario_agendo): echo dameUsuario($cita->id_usuario_agendo); else: echo "N/A"; endif; ?> / <? if($cita->id_usuario_reagendo != '-'): echo dameUsuario($cita->id_usuario_reagendo); else: echo "-"; endif; ?></td>
								<td align="center"><?if($cita->encuestado){?><i class="fa fa-check-circle" aria-hidden="true" style="color:#00aeff"></i><?}else{ echo "-"; }?></td>
								<? if($_GET['tipo']!=3): ?>
								
									<td>
										<? 	
												if($cita->confirmada==1): echo "<label class='font-green-jungle'>Confirmada</label>"; 
												else: 
													echo "<label class='font-red-thunderbird'>Pendiente por confirmar</label>"; 
												endif;
										?>
									</td>
									<td>
										<? if($cita->activo==1): ?>
											<? if($s_tipo==3): ?>
												<a role="button" href="?Modulo=Consulta&id=<?=$cita->id_cita?>" class="btn btn-xs green btn_<?=$ft['id_usuario']?>">Atender</a>&nbsp;
											<? endif; ?>
										
										
											<?
	                                	    if(!$cita->id_blanqueamiento):
	                                	    ?>
										<div class="btn-group btn_<?=$cita->id_cita?>" style="margin-top: 1px">     
                                	        <a class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Opciones
                                	            <i class="fa fa-angle-down"></i>
                                	        </a>
                                	        <ul class="dropdown-menu">
                                	            <li>
                                	                <a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#cambiaCita" data-id="<?=$cita->id_cita?>">Cambiar Cita</a>
                                	            </li>
                                	            <li>
                                	                <a href="javascript:;" onclick="javascript:cancelaCita(<?=$cita->id_cita?>)">Cancelar Cita</a>
                                	            </li>
                                	            <? if($cita->confirmada==0): ?>
                                	            <li class="divider"> </li>
                                	            <li>
                                	                <a href="javascript:;" onclick="javascript:confirmaCita(<?=$cita->id_cita?>)">Confirmar Cita</a>
                                	            </li>
                                	            <? endif; ?>
                                	        </ul>
                                	       <?
	                                	    endif;
	                                	   ?>
                                	    </div>
                                	    
                                	    
                                	    <? else: 
			                                	if(!$cita->id_blanqueamiento):
	                                	?>
												<a role="button" class="btn btn-xs btn-warning btn_<?=$ft['id_usuario']?>" onclick="javascript:activar(<?=$cita->id_cita?>)">Restaurar</a>
										<?
												endif;
										 endif; 
										?>
									</td>
								<? endif;?>
								<? if($_GET['tipo']==3): ?>
								<td >
									<a role="button" class="btn btn-xs blue" onclick="javascript:muestraCita(<?=$cita->id_cita?>)">Detalle</a>
									
								</td>
								<? endif; ?>
					        </tr>
					      <? endforeach; ?>
					    </tbody>
					</table>
					<? else: ?>
					<div class="alert alert-dismissable alert-warning">
				  		<button type="button" class="close" data-dismiss="alert">×</button>
				  		<p>Aún no se han creado citas</p>
				  	</div>
					<? endif; ?>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
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
				<h4 class="modal-title">Cambiar cita para <label id="nombre_cita"></label></h4>
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
							<select class="form-control" name="id_clinica" id="id_clinica" >
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
                                    <input type="checkbox" name="blanqueamientos" value="1" id="tiene_blanqueamientos"> Blanqueamientos
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
					
					<div class="form-group">
						<label for="direccion" class="col-md-3 control-label">Tratamiento</label>
						<div class="col-md-9">
							<select class="form-control select2" name="id_tratamiento" id="id_tratamiento">
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
							<select class="form-control select2" name="id_promocion" id="id_promocion">
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
                                <input type="text" size="16" class="form-control" id="fecha" style="width: 220px;">
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
							<textarea class="form-control dat" autocomplete="off" name="comentarios" id="comentarios" rows="3"></textarea>
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
				<button type="button" class="btn btn-success btn_ac" onclick="cambiaCita()">Agendar Cita</button>
			</div>
		</div>
	</div>
</div>




<!-- Modal -->
<div class="modal fade" id="CambiaFecha">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Filtrar por fecha</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="msg_error"></div>
<!--Formulario -->
		<form action="#" class="form-horizontal" id="frm_guarda">
            
            <div class="form-group">
                <label class="control-label col-md-4">Rango de fechas</label>
                <div class="col-md-8">
                    <div class="input-group input-large date-picker input-daterange" data-date="" data-date-format="mm/dd/yyyy">
						<input type="text" class="form-control" name="fecha_hora" id="filtro_fecha_hora">
						<span class="input-group-addon"> a </span>
						<input type="text" class="form-control" name="fecha_hora_final" id="filtro_fecha_hora_final"> 
					</div>
                </div>
            </div>            
            
		</form>
	</div>
		
    <div class="modal-footer">
    	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load2" width="20" class="oculto" />
		<button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
		<button type="button" class="btn btn-success btn_ac" onclick="cambiaFecha()">Aceptar</button>
	</div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->





<!-- Modal -->
<div class="modal fade" id="VerDetalle">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
				<h4 class="modal-title">Detalle de Cita</h4>
			</div>
			
			<div class="modal-body">
				<!--Formulario -->
				<form id="" class="form-horizontal">
					<div class="form-group">
						<label for="descripcion" class="col-md-3 control-label bold">Paciente:</label>
						<div class="col-md-9 control-label" style="text-align: left"><span id="paciente" class="inp">Cargando...</span></div>
					</div>
					<div class="form-group">
						<label for="descripcion" class="col-md-3 control-label bold">Fecha:</label>
						<div class="col-md-9 control-label" style="text-align: left"><span id="xfecha" class="inp">Cargando...</span></div>
					</div>
					<div class="form-group">
						<label for="descripcion" class="col-md-3 control-label bold">Atendió:</label>
						<div class="col-md-9 control-label" style="text-align: left"><span id="atendio" class="inp">Cargando...</span></div>
					</div>
					<div class="form-group">
						<label for="descripcion" class="col-md-3 control-label bold">Observaciones:</label>
						<div class="col-md-9 control-label" style="text-align: left"><span id="observaciones" class="inp">Cargando...</span></div>
					</div>
					<div class="form-group">
						<label for="descripcion" class="col-md-3 control-label bold">Clínica:</label>
						<div class="col-md-9 control-label" style="text-align: left"><span id="clinica" class="inp">Cargando...</span></div>
					</div>
					<div class="form-group hide">
						<label for="descripcion" class="col-md-3 control-label bold">Comentarios:</label>
						<div class="col-md-9 control-label" style="text-align: left"><span id="comentarios" class="inp">Cargando...</span></div>
					</div>

				</form>
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cerrar</button>
<!--				<button type="button" class="btn btn-info btn_ac" onclick="">Cambiar Cita</button>
				<button type="button" class="btn btn-danger btn_ac" onclick="">Cancelar Cita</button>
				<button type="button" class="btn btn-success btn_ac" onclick="">Confirmar Cita</button>-->
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
		$('#tiene_blanqueamientos').prop('checked',false);
		$('.edit').val("");
		$('.btn-modal').hide();
		$('#frm_cambia_cita').hide();
		$('#load_big').show();
	    var data_id = $(this).attr('data-id');
	    $.ajax({
	   	url: "data/cita.php",
	   	data: 'id_cita='+data_id,
	   	success: function(data){
		   	var datos = data.split('|');
		   	var id_clinica=datos[1];
		   	var id_usuario=datos[9];
	   		$('#nombre_cita').html(datos[0]);
	   		$('#id_clinica').val(id_clinica);
	   		$('#id_especialista_reagenda').val(datos[12]);
	   		$('#id_tratamiento').val(datos[2]);
	   		$('#id_promocion').val(datos[3]);
	   		$('#fecha').val(datos[4]);
	   		$('#fecha_hora').val(datos[5]);
	   		$('#fecha_final').val(datos[6]);
	   		$('#fecha_hora_final').val(datos[7]);
	   		$('#color').val(datos[8]);
	   		$('#comentarios').val(datos[10]);
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
	
	$('#NuevoProspecto').on('shown.bs.modal',function(e){
		$('#nuevo_nombre').focus();
	});
	
	$('#Seguimiento').on('shown.bs.modal',function(e){
		$('#comentarios').focus();
	});
	
	$('#NuevoProspecto').on('hidden.bs.modal',function(e){
		$('.dat').val("");
		$('#msg_error2').hide();
		$('#msg_error').hide();
	});
	
	$('#EditaClinica').on('hidden.bs.modal',function(e){
		$('.edit').val("");
		$('#msg_error2').hide();
		$('#msg_error').hide();
	});
	
	$('#Seguimiento').on('hidden.bs.modal',function(e){
		$('.dat').val("");
		$('#msg_error3').hide();
	});
	
	$('form').submit(function(e){
		e.preventDefault();	
	});
	
	
});

function EditaProspecto(){
	$('#msg_error2').hide('Fast');
	$('.btn_ac').hide();
	$('#load2').show();
	var datos=$('#frm_edita').serialize();
	$.post('ac/edita_prospecto.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Prospectos&msg=2", "_self");
	    }else{
	    	$('#load2').hide();
			$('.btn').show();
			$('#msg_error2').html(data);
			$('#msg_error2').show('Fast');
	    }
	});
}
function activar(id){
	swal({
		title: "Restaurar Cita",
		text: "¿Estás seguro que quieres restaurar la cita?",
		type: "info",
		confirmButtonText: "Restaurar cita",
		cancelButtonText: "No",
		showCancelButton: true,
		closeOnConfirm: false,
		showLoaderOnConfirm: true
	},function(){
		$.post('ac/activa_desactiva_cita.php', { tipo: "1", id_cita: id },function(data){
			if(data==1){
				swal({
				title: "Cita Restaurada",
				type: "success",
				confirmButtonText: "Ok",
				}, function () {
					window.open("?Modulo=Citas", "_self");
				});
			}else{
				swal("Error", data, "success");
			}
		});
	});	
}
function confirmaCita(id){
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
					window.open("?Modulo=Citas", "_self");
				});
			}else{
				swal("Error", data, "success");
			}
		});
	});	
}
function cancelaCita(id){
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
					window.open("?Modulo=Citas", "_self");
				});
			}else{
				swal("Error", data, "success");
			}
		});
	});
}
function encuestar(id){
	swal({
		title: "Encuesta de calidad",
		text: "¿Ya se ha encuestado esta consulta?",
		type: "info",
		confirmButtonText: "Si",
		cancelButtonText: "No",
		showCancelButton: true,
		closeOnConfirm: false,
		showLoaderOnConfirm: true
	}, function () {
		$.post('ac/encuestado.php', { id_cita: id },function(data){
			if(data==1){
				swal({
				title: "Encuestado",
				type: "success",
				confirmButtonText: "Ok",
				}, function () {
					window.open("?Modulo=Citas&tipo=3", "_self");
				});
			}else{
				swal("Error", data, "success");
			}
		});
	});
}
function NuevoProspecto(){
	$('#msg_error').hide('Fast');
	$('.btn_ac').hide();
	$('#load').show();
	var datos=$('#frm_guarda').serialize();
	$.post('ac/nuevo_prospecto.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Prospectos&msg=1", "_self");
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

function cambiaCita(){
	$('#msg_error').hide('Fast');
	$('.btn_ac').hide();
	$('#load').show();
	var datos=$('#frm_cambia_cita').serialize();
	$.post('ac/cambia_cita.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Citas&msg=1", "_self");
	    }else{
	    	$('#load').hide();
			$('.btn').show();
			$('#msg_error').html(data);
			$('#msg_error').show('Fast');
	    }
	});
}

function cambiaFecha(){
	var fecha_hora = $('#filtro_fecha_hora').val();
	var fecha_hora_final = $('#filtro_fecha_hora_final').val();
	$('.btn_ac').hide();
	$('#load2').show();
	
	if(fecha){
		window.open("?Modulo=Citas&tipo=<?=$_GET['tipo']?>&fecha1="+fecha_hora+"&fecha2="+fecha_hora_final, "_self");
	}else{
		alert("Seleccione una fecha");
		$('#load2').hide();
		$('.btn_ac').show();
		return false;
	}
}

function muestraCita(id_cita){
	$.getJSON('data/detalle_cita.php', {id_cita:id_cita} ,function(data) {
		
		$('#paciente').html(data.paciente);
		$('#atendio').html(data.doctor);
		$('#xfecha').html(data.fecha_hora);
		$('#observaciones').html(data.observaciones);
		$('#clinica').html(data.clinica);
		//$('#tratamiento').html(data.tratamiento);
		//$('#comentarios').html(data.comentario);
		
	});
	
	$('#VerDetalle').modal('show');
}
</script>