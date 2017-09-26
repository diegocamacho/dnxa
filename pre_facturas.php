<?
$sql="SELECT consultas.fecha_hora, clinicas.clinica, pacientes.nombre, pre_facturas.codigo,pre_facturas.fecha_hora, pre_facturas.monto, pre_facturas.metodo_pago, pre_facturas.id_pre_factura FROM pre_facturas
JOIN consultas ON consultas.id_consulta=pre_facturas.id_consulta
JOIN clinicas ON clinicas.id_clinica=consultas.id_clinica
JOIN pacientes ON pacientes.id_paciente=consultas.id_paciente";
$q=mysql_query($sql);
$facturas = array();
while($datos=mysql_fetch_object($q)):
	$facturas[] = $datos;
endwhile;
$val=count($facturas);


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
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet light  portlet-fit">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-layers font-dark"></i>
						<span class="caption-subject font-dark bold uppercase">Pre Facturas</span>
					</div>
					<div class="actions btn-set">
						<!--<a href="javascript:;" class="btn btn-sm blue " data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#nuevoPaciente"><i class="fa fa-plus"></i> Agregar paciente </a>-->
					</div>
				</div>
				<div class="portlet-body">
					<? if($val>0):?>
					<table class="table table-striped table-bordered table-hover" id="tabla_facturas">
						<thead>
					        <tr>
								<th>Fecha</th>
								<th>Clínica</th>
								<th>Paciente</th>
								<th>Código</th>
								<th>Método</th>
								<th>Total</th>
								<th width="60"></th>
					        </tr>
					    </thead>
					    <tbody>
					      <? foreach($facturas as $factura): ?>
					        <tr class="tr_<?=$factura->id_pre_factura?>">
								<td><?=devuelveFechaHora($factura->fecha_hora)?></td>
								<td><?=$factura->clinica?></td>
								<td><?=$factura->nombre?></td>
								<td><?=$factura->codigo?></td>
								<td><?=$factura->metodo_pago?></td>
								<td align="right"><?=$factura->monto?></td>
								<td>
									<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load_<?=$factura->id_pre_factura?>" width="19" class="oculto" />
									
									
									<div class="btn-group btn_<?=$factura->id_pre_factura?>">
                                        <a class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Opciones
                                            <i class="fa fa-angle-down"></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="javascript:;" onclick="javascript:Cancela(<?=$factura->id_pre_factura?>)">Cancelar</a>
                                            </li>
                                            <!--
                                            <li>
                                                <a href="javascript:;" onclick="javascript:Descarga(<?=$factura->id_pre_factura?>)">Descargar</a>
                                            </li>-->
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
				  		<p>Aún no se han generado pre facturas</p>
				  	</div>
					<? endif; ?>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
	</div>
</div>













<!--- Js -->
<script>
$(function(){
	
	$('#tabla_facturas').dataTable({
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
		$.post('ac/activa_desactiva_paciente.php', { tipo: "0", id_pre_factura: id },function(data){
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
	$.post('ac/activa_desactiva_paciente.php', { tipo: "1", id_pre_factura: ""+id+"" },function(data){
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