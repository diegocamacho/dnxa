<?
$sql="
SELECT pagos_especialistas_lab.*,especialistas_lab.nombre,especialistas_lab.telefono,especialistas_lab.email, especialistas_lab.tipo, consultas.fecha_hora as fecha_hora_consulta
FROM pagos_especialistas_lab 
JOIN especialistas_lab ON especialistas_lab.id_especialista_lab = pagos_especialistas_lab.id_especialista_lab
JOIN consultas ON consultas.id_consulta = pagos_especialistas_lab.id_consulta
WHERE liquidado = 1 ORDER BY id_pago_especialistas_lab DESC";
$q=mysql_query($sql);
$cuantos = mysql_num_rows($q);
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
				  		<p>Agregado con éxito.</p>
				  	</div>
			  <? }if($_GET['msg']==2){ ?>
			  		<br>
			  		<div class="alert alert-dismissable alert-info">
				  		<button type="button" class="close" data-dismiss="alert">×</button>
				  		<p>Actualizado con éxito.</p>
				  	</div>
			  <? } ?>
			  <!-- Contenido -->
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet light  portlet-fit">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-user-md font-dark"></i>
						<span class="caption-subject font-dark bold uppercase">Consultas de Especialistas & Laboratorios (Pagadas)</span>
					</div>
					<div class="actions btn-set">

						<a href="?Modulo=OperacionesEspecialistas" class="btn btn-sm red" ><i class="fa fa-money"></i> Operaciones Pendientes de Pago </a>

					</div>
				</div>
				<div class="portlet-body">
					<?
					if($cuantos):	
					?>
					<table class="table table-striped table-bordered table-hover">
						<thead>
					        <tr>
								<th>ID</th>
								<th>Fecha</th>
								<th>Nombre</th>
								<th>Tipo</th>
								<th>Teléfono</th>
								<th>Consulta</th>
								<th>Total Pagado</th>
								<th>Última Fecha de Pago</th>
								<th></th>
					        </tr>
					    </thead>
					    <tbody>
					    <? while($ft=mysql_fetch_assoc($q)){ 
						    $id_consulta = $ft['id_consulta'];
						    $sql ="SELECT tratamientos.tratamiento, (consultas_tratamientos.cantidad*consultas_tratamientos.precio) as costo
								   FROM consultas_tratamientos
								   JOIN tratamientos ON tratamientos.id_tratamiento = consultas_tratamientos.id_tratamiento
								   WHERE consultas_tratamientos.id_consulta = $id_consulta";
								   $qx = mysql_query($sql);
								   unset($consulta_datos);
								   while($consulta_w = mysql_fetch_assoc($qx)):
									   	$consulta_datos.=$consulta_w['tratamiento'].' - $'.$consulta_w['costo'].'<br>';						   
								   endwhile;
								   
							$sql_pagos = "SELECT SUM(monto) FROM books_gastos WHERE id_pago_especialistas_lab = ".$ft['id_pago_especialistas_lab'];
							$monto_pagado = @mysql_result(mysql_query($sql_pagos), 0);
							
							$sql = "SELECT fecha_gasto FROM books_gastos WHERE id_pago_especialistas_lab =".$ft['id_pago_especialistas_lab']." ORDER BY id_gasto DESC LIMIT 1";
							$q_fechagasto = mysql_result(mysql_query($sql), 0);
					    ?>
					        <tr>
								<td><?=$ft['id_pago_especialistas_lab']?></td>
								<td><?=devuelveFechaHora($ft['fecha_hora_consulta'])?></td>
								<td><?=$ft['nombre']?></td>
								<td><?=($ft['tipo'] == 1 ? 'Especialista':'Laboratorio')?></td>
								<td><?=$ft['telefono']?></td>							
								<td><?=$consulta_datos?></td>
								<td>$<?=number_format($monto_pagado,2)?></td>
								<td><?=fechaLetra($q_fechagasto)?></td>
								<td>
									<div class="btn-group">
									<button class="btn btn-xs btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
										<i class="fa fa-angle-down"></i>
									</button>
									<ul class="dropdown-menu pull-right" role="menu">
									    <li><a href="javascript:;" onclick="marcaPendiente(<?=$ft['id_pago_especialistas_lab']?>);">Marcar como Pendiente de Pago </a>
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
				  		<p>No existen pagos registrados.</p>
				  	</div>

					<?	endif; ?>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
	</div>
</div>


<!-- Modal Crear Espe/Lab-->
<div class="modal fade" id="NuevoModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Nuevo Especialista / Lab</h4>
      </div>
<!--Formulario -->
		<form id="frm_guarda" class="form-horizontal">
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="msg_error"></div>
			
			<div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Nombre</label>
				<div class="col-md-9">
					<input type="text" maxlength="96" class="form-control dat" name="nuevo_nombre" id="nuevo_nombre" autocomplete="off" required>
				</div>
			</div>

			<div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Teléfono (opcional)</label>
				<div class="col-md-9">
					<input type="text" maxlength="10" class="form-control dat" name="nuevo_telefono" id="nuevo_telefono" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Email (opcional)</label>
				<div class="col-md-9">
					<input type="text" maxlength="96" class="form-control dat" name="nuevo_email" id="nuevo_email" autocomplete="off">
				</div>
			</div>
            <div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Tipo</label>
				<div class="col-md-9">
					<select class="form-control" name="nuevo_Tipo" id="nuevo_Tipo" required>
                        <option selected disabled>Seleccionar Tipo </option>
                        <option value="1">Especialista</option>
                        <option value="2">Laboratorio</option>
                  </select>
				</div>
			</div>
					
		
		      
      </div>
      <div class="modal-footer">
      	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load" width="25" class="oculto" />
        <button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success btn_ac" >Guardar</button>
      </div>
    </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->




<!-- Modal Editar Espe/Lab-->
<div class="modal fade" id="EditaModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Edita Especialista / Lab</h4>
      </div>
<!--Formulario -->
      <form id="frm_edita" class="form-horizontal">
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="msg_error2"></div>
<!-- Loader -->
		<div class="row oculto" id="load_big">
			<div class="col-md-12 text-center" >
				<img src="assets/global/img/ajax-loading.gif" border="0"  />
			</div>
		</div>
			<div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Nombre</label>
				<div class="col-md-9">
					<input type="text" maxlength="96" class="form-control dat" name="edita_nombre" id="edita_nombre" autocomplete="off" required>
				</div>
			</div>

			<div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Teléfono (opcional)</label>
				<div class="col-md-9">
					<input type="text" maxlength="10" class="form-control dat" name="edita_telefono" id="edita_telefono" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Email (opcional)</label>
				<div class="col-md-9">
					<input type="text" maxlength="96" class="form-control dat" name="edita_email" id="edita_email" autocomplete="off">
				</div>
			</div>
			<div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Tipo</label>
				<div class="col-md-9">
					<select class="form-control" name="edita_Tipo" id="edita_Tipo" required>
                        <option value="1">Especialista</option>
                        <option value="2">Laboratorio</option>
                  </select>
				</div>
			</div>
			
			<input type="hidden" name="editar_id" id="editar_id"/>
		      
      </div>
      <div class="modal-footer">      	
      	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load2" width="25" class="oculto" />
        <button type="button" class="btn btn-default btn_ac btn-modal" data-dismiss="modal">Cancelar</button>
        <button type="sumbit" class="btn btn-success btn_ac btn-modal">Actualizar</button>
      </div>
     </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
		    
<!--- Js -->
<script>

function marcaPendiente(id_pago_especialistas_lab){
	
	$.post('ac/marcar_pendiente_pago.php','id='+id_pago_especialistas_lab,function(data) {
		if(data==1){
			window.open("?Modulo=OperacionesEspecialistas", "_self");
		}else{
			alert('Error: '+data);
		}
	});
	
}



</script>