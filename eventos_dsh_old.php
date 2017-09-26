<?
if($s_tipo==3):
	$consulta=" AND citas.id_clinica=$s_id_clinica";
	$consulta2=" AND id_clinica=$s_id_clinica";
endif;

$tipo=" citas.activo=1 AND citas.tipo=2";
$limite="LIMIT 500";

if($_GET['fecha1']){
	$fecha1=fechaBase2($_GET['fecha1']);
	$fecha2=fechaBase2($_GET['fecha2']);
	$rangos=" AND fecha_hora BETWEEN '$fecha1 00:00:00' AND '$fecha2 23:59:59' ";
}else{
	$fecha1 = date("Y-m-d");
	$rangos = "AND DATE(fecha_hora) >= '$fecha1'";
}

$sql="SELECT usuarios.nombre AS doctor, clinicas.clinica, citas.* FROM citas 
JOIN clinicas ON clinicas.id_clinica=citas.id_clinica
JOIN usuarios ON usuarios.id_usuario=citas.id_usuario
WHERE $tipo $consulta $rangos ORDER BY citas.fecha_hora ASC $limite";
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


/* PARA LA CITA */
//Clinicas
$sql="SELECT id_clinica,clinica FROM clinicas WHERE activo=1 AND tipo=1 $consulta2";
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
						<span class="caption-subject font-dark bold uppercase">Eventos</span>
					</div>
					<div class="actions btn-set">
						<a href="javascript:;" class="btn btn-sm green" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#nuevoEvento"> Nuevo Evento </a>&nbsp;&nbsp;
						<a href="javascript:;" class="btn btn-sm green-jungle" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#nuevoEventoRecurrente"> Nuevo Evento Recurrente</a>
					</div>
				</div>
				<div class="portlet-body">
					<? if($val>0):?>
					<table class="table table-striped table-bordered table-hover" id="tabla_citas">
						<thead>
					        <tr>
								<th>Usuario</th>
								<th>Evento</th>
								<th>Clínica</th>
								<th>Agendo</th>
								<th width="70"></th>
					        </tr>
					    </thead>
					    <tbody>
					      <? foreach($citas as $cita): ?>
					        <tr class="tr_<?=$cita->id_cita?>">
								<td><span class="badge" style="background-color: <?=$cita->color?>">&nbsp;&nbsp;</span>&nbsp;&nbsp; <?=$cita->doctor?></td>
								<td><?=devuelveFechaHora($cita->fecha_hora)?><br><?=devuelveFechaHora($cita->fecha_hora_final)?></td>
								<td><?=$cita->clinica;?></td>
								<td><? if($cita->id_usuario_agendo): echo dameUsuario($cita->id_usuario_agendo); else: echo "N/A"; endif; ?>
								<td>

									<div class="btn-group" style="margin-top: 1px">
                                	    <a class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Opciones
                                	        <i class="fa fa-angle-down"></i>
                                	    </a>
                                	    <ul class="dropdown-menu">
	                                	    <li>
                                	            <a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#editaEvento" data-id="<?=$cita->id_cita?>">Editar Evento</a>
                                	        </li>
                                	        <li>
                                	            <a href="javascript:;" onclick="javascript:cancelaEvento(<?=$cita->id_cita?>)">Cancelar Evento</a>
                                	        </li>
                                	        <? if($cita->id_evento): ?>
                                	        <li class="divider"> </li>
                                	        <li>
                                	            <a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#editaEventoRecurrente" data-id-r="<?=$cita->id_cita?>">Editar Recurrente</a>
                                	        </li>
                                	        <li>
                                	            <a href="javascript:;" onclick="javascript:cancelaRecurrente(<?=$cita->id_evento?>)">Cancelar Recurrente</a>
                                	        </li>
                                	        <? endif; ?>
                                	    </ul>
                                	</div>
                                	
								</td>
					        </tr>
					      <? endforeach; ?>
					    </tbody>
					</table>
					<? else: ?>
					<div class="alert alert-dismissable alert-warning">
				  		<button type="button" class="close" data-dismiss="alert">×</button>
				  		<p>Aún no se han creado eventos</p>
				  	</div>
					<? endif; ?>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
	</div>
</div>



















<!-- Nuevo Evento -->
<div class="modal fade" id="nuevoEvento">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
				<h4 class="modal-title">Nuevo Evento</h4>
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
				<form id="frm_evento" class="form-horizontal">
					
					<div class="form-group">
						<label for="direccion" class="col-md-3 control-label">Clínica</label>
						<div class="col-md-9">
							<select class="form-control" name="id_clinica" id="id_clinica" >
								<option value="0">Seleccione una clínica</option>
								<? foreach($clinicas as $clinica): ?>
								<option value="<?=$clinica->id_clinica?>"><?=$clinica->clinica?></option>
								<? endforeach; ?>
							</select>
						</div>
					</div>
					
					<div class="form-group" id="doctor" style="display: none;">
						
					</div>
					
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
				<button type="button" class="btn btn-success btn_ac" onclick="nuevoEvento()">Agregar Evento</button>
			</div>
		</div>
	</div>
</div>

<!-- Nuevo Evento Recurrente -->
<div class="modal fade" id="nuevoEventoRecurrente">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
				<h4 class="modal-title">Nuevo Evento Recurrente</h4>
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
						<label for="direccion" class="col-md-3 control-label">Clínica</label>
						<div class="col-md-9">
							<select class="form-control" name="id_clinica" id="id_clinica_recurrente" >
								<option value="0">Seleccione una clínica</option>
								<? foreach($clinicas as $clinica): ?>
								<option value="<?=$clinica->id_clinica?>"><?=$clinica->clinica?></option>
								<? endforeach; ?>
							</select>
						</div>
					</div>
					
					<div class="form-group" id="doctor_recurrente" style="display: none;">
						
					</div>
					
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
                        <label class="control-label col-md-3">Hora de inicio</label>
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
                        <label class="control-label col-md-3">Hora de final</label>
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
                    <!--
                    <div class="form-group">
                        <label class="col-md-3 control-label">Aplicar a días</label>
                        <div class="col-md-9">
                            <div class="mt-checkbox-list">
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox"> Lunes
                                    <span></span>
                                </label>
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox"> Martes
                                    <span></span>
                                </label>
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox"> Miércoles
                                    <span></span>
                                </label>
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox"> Jueves
                                    <span></span>
                                </label>
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox"> Viernes
                                    <span></span>
                                </label>
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox"> Sábado
                                    <span></span>
                                </label>
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox"> Domingo
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    -->
                    <div class="form-group">
                        <label class="col-md-3 control-label">Aplicar a días</label>
                        <div class="col-md-9">
                            <div class="mt-checkbox-inline">
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox" name="lunes"> Lun
                                    <span></span>
                                </label>
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox" name="martes"> Mar
                                    <span></span>
                                </label>
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox"  name="miercoles"> Mié
                                    <span></span>
                                </label>
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox" name="jueves"> Jue
                                    <span></span>
                                </label>
                                <label class="mt-checkbox mt-checkbox-outline">
                                    <input type="checkbox" name="viernes"> Vie
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


					<div class="form-group">
						<label for="descripcion" class="col-md-3 control-label">Comentarios</label>
						<div class="col-md-9">
							<textarea class="form-control dat" autocomplete="off" name="comentarios" rows="3"></textarea>
						</div>
					</div>
					
			
				</form>
			</div>
			
			<div class="modal-footer">
				<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load4" width="25" class="oculto" />
				<button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-success btn_ac" onclick="nuevoEventoRecurrente()">Agregar Evento</button>
			</div>
		</div>
	</div>
</div>





<!-- Ediciones --->

<!-- Evnto -->
<div class="modal fade" id="editaEvento">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
				<h4 class="modal-title">Edita Evento</h4>
			</div>
			
			<div class="modal-body">
				<div class="alert alert-danger oculto" role="alert" id="edita_msg_error"></div>
				<!-- Loader -->
				<div class="row oculto" id="edita_load1">
					<div class="col-md-12 text-center" >
						<img src="assets/global/img/ajax-loading.gif" border="0"  />
					</div>
				</div>
				<!--Formulario -->
				<form id="frm_evento_edita1" class="form-horizontal">
					
					<div class="form-group">
						<label for="direccion" class="col-md-3 control-label">Clínica</label>
						<div class="col-md-9">
							<select class="form-control" name="id_clinica" id="id_clinica_edita1" >
								<option value="0">Seleccione una clínica</option>
								<? foreach($clinicas as $clinica): ?>
								<option value="<?=$clinica->id_clinica?>"><?=$clinica->clinica?></option>
								<? endforeach; ?>
							</select>
						</div>
					</div>
					
					<div class="form-group" id="doctor_edita" style="display: none;">
						
					</div>
					
					<div class="form-group">
						<label for="nombre" class="col-md-3 control-label">Inicia</label>
						<div class="col-md-9">
							<div class="input-group date form_meridian_datetime_4" >
                                <input type="text" size="16" class="form-control" id="edita_fecha_hora_v" style="width: 220px;">
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
							<div class="input-group date form_meridian_datetime_3">
                                <input type="text" size="16" class="form-control" id="edita_fecha_hora_final_v" style="width: 220px;">
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
							<select class=" form-control" data-show-subtext="false" name="color" id="edita_color">
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


					<div class="form-group">
						<label for="descripcion" class="col-md-3 control-label">Comentarios</label>
						<div class="col-md-9">
							<textarea class="form-control dat" autocomplete="off" name="comentarios" id="edita_comentarios" rows="3"></textarea>
						</div>
					</div>
					
					<input type="hidden" name="id_evento" id="edita_id_evento" />
					<input type="hidden" name="fecha_hora" id="edita_fecha_hora" />
					<input type="hidden" name="fecha_hora_final" id="edita_fecha_hora_final" />
			
				</form>
			</div>
			
			<div class="modal-footer">
				<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load10" width="25" class="oculto" />
				<button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-success btn_ac" onclick="editaEvento()">Editar Evento</button>
			</div>
		</div>
	</div>
</div>


<!-- Evento Recurrente -->
<div class="modal fade" id="editaEventoRecurrente">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
				<h4 class="modal-title">Edita Evento Recurrente</h4>
			</div>
			
			<div class="modal-body">
				<div class="alert alert-danger oculto" role="alert" id="edita_msg_error_recurrente"></div>
				<!-- Loader -->
				<div class="row oculto" id="recurrente_load">
					<div class="col-md-12 text-center" >
						<img src="assets/global/img/ajax-loading.gif" border="0"  />
					</div>
				</div>
				<!--Formulario -->
				<form id="frm_evento_recurrente_edita" class="form-horizontal">
					
					<div class="form-group">
						<label for="direccion" class="col-md-3 control-label">Clínica</label>
						<div class="col-md-9">
							<select class="form-control" name="id_clinica" id="id_clinica_recurrente_edita" >
								<option value="0">Seleccione una clínica</option>
								<? foreach($clinicas as $clinica): ?>
								<option value="<?=$clinica->id_clinica?>"><?=$clinica->clinica?></option>
								<? endforeach; ?>
							</select>
						</div>
					</div>
					
					<div class="form-group" id="doctor_recurrente_edita" style="display: none;">
						
					</div>
					
					<div class="form-group">
					    <label class="control-label col-md-3">Duración</label>
					    <div class="col-md-9">
					        <div class="input-group input-large date-picker input-daterange" data-date="" data-date-format="yyyy-mm-dd">
								<input type="text" class="form-control r_limpia" name="fecha1" id="fecha1">
								<span class="input-group-addon"> a </span>
								<input type="text" class="form-control r_limpia" name="fecha2" id="fecha2"> 
							</div>
					    </div>
					</div>
					
					<div class="form-group">
                        <label class="control-label col-md-3">Hora de inicio</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" class="form-control timepicker timepicker-24" name="hora1" id="hora1">
                                <span class="input-group-btn">
                                    <button class="btn default" type="button">
                                        <i class="fa fa-clock-o"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-md-3">Hora de final</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" class="form-control timepicker timepicker-24" name="hora2" id="hora2">
                                <span class="input-group-btn">
                                    <button class="btn default" type="button">
                                        <i class="fa fa-clock-o"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-3 control-label">Aplicar a días</label>
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
					
					<div class="form-group">
						<label for="nombre" class="col-md-3 control-label">Color</label>
						<div class="col-md-9">
							<select class=" form-control" data-show-subtext="false" name="color" id="color_recurrente">
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


					<div class="form-group">
						<label for="descripcion" class="col-md-3 control-label">Comentarios</label>
						<div class="col-md-9">
							<textarea class="form-control dat" autocomplete="off" name="comentarios" id="recurrente_comentarios" rows="3"></textarea>
						</div>
					</div>
					
					<input type="hidden" name="id_evento" id="id_evento" />
				</form>
			</div>
			
			<div class="modal-footer">
				<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load14" width="25" class="oculto" />
				<button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-success btn_ac" onclick="editaEventoRecurrente()">Editar Evento</button>
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
	
	$('#id_clinica_edita1').change(function(){
		var id_clinica = $('#id_clinica_edita1').val();
		$.ajax({
			url: "data/select_doctores.php",
	   		data: 'id_clinica='+id_clinica,
	   		success: function(data){
		   		$('#doctor_edita').html(data);
		   		$('#doctor_edita').show();
	   	},
	   	cache: false
	   	});
		
	});
	
	$('#id_clinica_recurrente').change(function(){
		var id_clinica = $('#id_clinica_recurrente').val();
		$.ajax({
			url: "data/select_doctores.php",
	   		data: 'id_clinica='+id_clinica,
	   		success: function(data){
		   		$('#doctor_recurrente').html(data);
		   		$('#doctor_recurrente').show();
	   	},
	   	cache: false
	   	});
		
	});
	
	$('#id_clinica_recurrente_edita').change(function(){
		var id_clinica = $('#id_clinica_recurrente_edita').val();
		$.ajax({
			url: "data/select_doctores.php",
	   		data: 'id_clinica='+id_clinica,
	   		success: function(data){
		   		$('#doctor_recurrente_edita').html(data);
		   		$('#doctor_recurrente_edita').show();
	   	},
	   	cache: false
	   	});
		
	});
	
	$(document).on('click', '[data-id]', function () {
		$('.edit').val("");
		$('.btn_ac').hide();
		$('#frm_evento_edita1').hide();
		$('#edita_load1').show();
	    var id = $(this).attr('data-id');
	    $.getJSON('data/evento.php', {id:id} ,function(data) {
			console.log(data);
			
			$('#id_clinica_edita1').val(data.id_clinica).trigger("change");
			$('#resset_id_usuario').val(data.id_usuario);
			$('#edita_fecha_hora_v').val(data.fecha_hora2);
			$('#edita_fecha_hora_final_v').val(data.fecha_hora_final2);
			$('.fecha_hora').val(data.fecha_hora);
			$('.fecha_hora_final').val(data.fecha_hora_final);
			$('#edita_color').val(data.color);
			$('#edita_comentarios').html(data.comentario);
			$('#edita_id_evento').val(data.id_cita);
			
			//Cambiamos el usuario
			var id_usuario=$('#resset_id_usuario').val();
			$('#select_doctor_edita').val(id_usuario);
				
			$('.btn_ac').show();
			$('#frm_evento_edita1').show();
			$('#edita_load1').hide();
			
		});
	});
	
	$(document).on('click', '[data-id-r]', function () {
		$('.edit').val("");
		$('.btn_ac').hide();
		$('#frm_evento_recurrente_edita').hide();
		$('#recurrente_load').show();
	    var id = $(this).attr('data-id-r');
	    $.getJSON('data/evento_recurrente.php', {id:id} ,function(data) {
			console.log(data);
			$('#id_clinica_recurrente_edita').val(data.id_clinica).trigger("change");
			$('#fecha1').val(data.fecha1);
			$('#fecha2').val(data.fecha2);
			$('#hora1').val(data.hora1);
			$('#hora2').val(data.hora2);
			if(data.lunes=="on"){
				$('#lunes').prop("checked", true);
			}else{
				$('#lunes').prop("checked", false);
			}
			
			if(data.martes=="on"){
				$('#martes').prop("checked", true);
			}else{
				$('#martes').prop("checked", false);
			}
			
			if(data.miercoles=="on"){
				$('#miercoles').prop("checked", true);
			}else{
				$('#miercoles').prop("checked", false);
			}
			
			if(data.jueves=="on"){
				$('#jueves').prop("checked", true);
			}else{
				$('#jueves').prop("checked", false);
			}
			
			if(data.viernes=="on"){
				$('#viernes').prop("checked", true);
			}else{
				$('#viernes').prop("checked", false);
			}
			
			if(data.sabado=="on"){
				$('#sabado').prop("checked", true);
			}else{
				$('#sabado').prop("checked", false);
			}
			
			if(data.domingo=="on"){
				$('#domingo').prop("checked", true);
			}else{
				$('#domingo').prop("checked", false);
			}
			
			$('#color_recurrente').val(data.color).trigger("change");
			$('#recurrente_comentarios').val(data.comentario);
			$('#id_evento').val(data.id_evento);
			
			
				
			$('.btn_ac').show();
			$('#frm_evento_recurrente_edita').show();
			$('#recurrente_load').hide();
			
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
function cancelaEvento(id){
	swal({
		title: "Cancelar Evento",
		text: "¿Estás seguro que quieres cancelar el evento?",
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
				title: "Evento Cancelado",
				type: "success",
				confirmButtonText: "Ok",
				}, function () {
					window.open("?Modulo=Eventos", "_self");
				});
			}else{
				swal("Error", data, "success");
			}
		});
	});
}
function cancelaRecurrente(id){
	swal({
		title: "Cancelar Evento Recurrente",
		text: "¿Estás seguro que quieres cancelar el evento recurrente?",
		type: "warning",
		confirmButtonText: "Si, cancelar",
		cancelButtonText: "No",
		showCancelButton: true,
		closeOnConfirm: false,
		showLoaderOnConfirm: true
	}, function () {
		$.post('ac/cancela_recurrente.php', { id: id },function(data){
			if(data==1){
				swal({
				title: "Evento Recurrente Cancelado",
				type: "success",
				confirmButtonText: "Ok",
				}, function () {
					window.open("?Modulo=Eventos", "_self");
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
function editaEvento(){
	$('#edita_msg_error').hide('Fast');
	$('.btn_ac').hide();
	$('#load10').show();
	var datos=$('#frm_evento_edita1').serialize();
	$.post('ac/edita_evento.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Eventos", "_self");
	    }else{
	    	$('#load10').hide();
			$('.btn_ac').show();
			$('#edita_msg_error').html(data);
			$('#edita_msg_error').show('Fast');
	    }
	});
}

function nuevoEvento(){
	$('#msg_error4').hide('Fast');
	$('.btn_ac').hide();
	$('#load4').show();
	var datos=$('#frm_evento').serialize();
	$.post('ac/nuevo_evento.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Eventos", "_self");
	    }else{
	    	$('#load4').hide();
			$('.btn').show();
			$('#msg_error4').html(data);
			$('#msg_error4').show('Fast');
	    }
	});
}

function nuevoEventoRecurrente(){
	$('#msg_error_recurrente').hide('Fast');
	$('.btn_ac').hide();
	$('#load4').show();
	var datos=$('#frm_evento_recurrente').serialize();
	$.post('ac/nuevo_evento_recurrente.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Eventos", "_self");
	    }else{
		    console.log(data);
	    	$('#load4').hide();
			$('.btn').show();
			$('#msg_error_recurrente').html(data);
			$('#msg_error_recurrente').show('Fast');
	    }
	});
}

function editaEventoRecurrente(){
	$('#edita_msg_error_recurrente').hide('Fast');
	$('.btn_ac').hide();
	$('#load14').show();
	var datos=$('#frm_evento_recurrente_edita').serialize();
	$.post('ac/edita_evento_recurrente.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Eventos", "_self");
	    }else{
		    console.log(data);
	    	$('#load14').hide();
			$('.btn').show();
			$('#edita_msg_error_recurrente').html(data);
			$('#edita_msg_error_recurrente').show('Fast');
	    }
	});
}
</script>