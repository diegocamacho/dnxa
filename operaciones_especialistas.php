<?
$sql="
SELECT pagos_especialistas_lab.*,especialistas_lab.nombre,especialistas_lab.telefono,especialistas_lab.email, especialistas_lab.tipo, consultas.fecha_hora as fecha_hora_consulta, pacientes.nombre AS paciente FROM pagos_especialistas_lab 
JOIN especialistas_lab ON especialistas_lab.id_especialista_lab = pagos_especialistas_lab.id_especialista_lab 
JOIN consultas ON consultas.id_consulta = pagos_especialistas_lab.id_consulta 
JOIN pacientes ON pacientes.id_paciente = consultas.id_paciente 
WHERE liquidado = 0 ORDER BY id_pago_especialistas_lab DESC";
$q=mysql_query($sql);
$cuantos = mysql_num_rows($q);
include("nuevo_gasto_especialista.php");

function dameTratamientos($id_consulta){
	$sql="SELECT id_consuta_tratamiento FROM consultas_tratamientos WHERE id_consulta=$id_consulta";
	$q=mysql_query($sql);
	$cuantos=mysql_num_rows($q);

	return $cuantos;
}
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
						<span class="caption-subject font-dark bold uppercase">Consultas de Especialistas & Laboratorios (Pendientes de Pago)</span>
					</div>
					<div class="actions btn-set">

						<a href="?Modulo=OperacionesEspecialistasPagadas" class="btn btn-sm blue" ><i class="fa fa-money"></i> Operaciones Pagadas </a>

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
								<th>Paciente</th>
								<th>Nombre</th>
								<th>Tipo</th>
								<th>Teléfono</th>
								<th>Consulta</th>
								<th>Tratamientos</th>
								<th>Pagado</th>
								<th width="100"></th>
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
								   unset($monto_total);
								   while($consulta_w = mysql_fetch_assoc($qx)):
									   	$consulta_datos.=$consulta_w['tratamiento'].' - $'.$consulta_w['costo'].'<br>';
									   	$monto_total+=$consulta_w['costo']; 						   
								   endwhile;
								   
							$sql_pagos = "SELECT SUM(monto) FROM books_gastos WHERE id_pago_especialistas_lab = ".$ft['id_pago_especialistas_lab'];
							$monto_pagado = @mysql_result(mysql_query($sql_pagos), 0);
					    ?>
					        <tr>
								<td><?=$ft['id_pago_especialistas_lab']?></td>
								<td><?=devuelveFechaHora($ft['fecha_hora_consulta'])?></td>
								<td><?=$ft['paciente']?></td>
								<td><?=$ft['nombre']?></td>
								<td><? 
									if($ft['tipo']==1): $tipo = 'Especialista';
									else: $tipo ="Laboratorio"; endif;?>
									<?=$tipo?></td>
								<td><?=$ft['telefono']?></td>							
								<td><?=$consulta_datos?></td>
								<td><?=dameTratamientos($id_consulta)?></td>
								<td>$<?=number_format($monto_pagado,2)?></td>
								<td align="right">
									<a role="button" href="javascript:;" class="btn btn-xs btn-info" data-desc="Pago a <?=$tipo?> (<?=$ft['nombre']?>)" data-id-pago-especialista="<?=$ft['id_pago_especialistas_lab']?>" data-monto="<?=$monto_total-$monto_pagado?>" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#pagaEspecialista">Crear Pago</a>
								</td>
							</tr>
						<? } ?>
					    </tbody>
					</table>
					<? else: ?>
			  		<div class="alert alert-dismissable alert-warning">
				  		<p>No existen pagos pendientes.</p>
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
$(function(){
    //Guardar Formulario
	$('#frm_guarda').submit(function(e){
		e.preventDefault();
        //alert("Crear Nuevo");
        Nuevo();
	});
    
    //Editar Formulario
    $('#frm_edita').submit(function(e){
		e.preventDefault();
        
        Edita();
        
	});
    
    $('form').submit(function(e){
		e.preventDefault();	
	});
	
	$(document).on('click', '[data-id]', function () {
		$('.edit').val("");
		$('.btn-modal').hide();
		$('#frm_edita').hide();
		$('#load_big').show();
	    var data_id = $(this).attr('data-id');
	    $.getJSON('data/especialistas.php','id='+data_id,function(data) {
	    	
			$('#edita_nombre').val(data.nombre);
            $('#edita_telefono').val(data.telefono);
            $('#edita_email').val(data.email);
            $('#edita_Tipo').val(data.tipo);
	   		$('#id').val(data.id_edita);
	   		$('#load_big').hide();
	   		$('#frm_edita').show();
	   		$('.btn-modal').show();
	    	
	    });
	});	
	
	$('#NuevoModal').on('shown.bs.modal',function(e){
		$('#nuevo_nombre').focus();
	});
	
	$('#NuevoModal').on('hidden.bs.modal',function(e){
		$('.dat').val("");
		$('#msg_error2').hide();
		$('#msg_error').hide();
	});
	
	$('#EditaModal').on('hidden.bs.modal',function(e){
		$('.edit').val("");
		$('#msg_error2').hide();
		$('#msg_error').hide();;
	});
	
	        
});

function setEditarID(id){
    $('#editar_id').val(id);
}

function Edita(){
	$('#msg_error2').hide('Fast');
	$('.btn_ac').hide();
	$('#load2').show();
	var datos=$('#frm_edita').serialize();
	$.post('ac/edita_especialista_lab.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=EspecialistasLabs&msg=1", "_self");
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
	$.post('ac/activa_desactiva_especialista_lab.php', { tipo: "0", id: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=EspecialistasLabs", "_self");
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
	$.post('ac/activa_desactiva_especialista_lab.php', { tipo: "1", id: ""+id+"" },function(data){
		if(data==1){
			window.open("?Modulo=EspecialistasLabs", "_self");
		}else{
			$("#load_"+id+"").hide();
			$(".btn_"+id+"").show();
			alert(data);
		}
	});
}
function Nuevo(){
	$('#msg_error').hide('Fast');
	$('.btn_ac').hide();
	$('#load').show();
	var datos=$('#frm_guarda').serialize();
	$.post('ac/nuevo_especialista_lab.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=EspecialistasLabs&msg=1", "_self");
	    }else{
	    	$('#load').hide();
			$('.btn').show();
			$('#msg_error').html(data);
			$('#msg_error').show('Fast');
	    }
	});
}
</script>