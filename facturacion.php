<?
if($_GET['Tipo']):
	$tipo=$_GET['Tipo'];
endif;

if($_GET['Empresa']):
	$empresa=$_GET['Empresa'];
endif;

if($_GET['Comprob']):
	$comprob=$_GET['Comprob'];
endif;

if($tipo==2):
	$consulta="estado=2 OR estado=3";
	$titulo="Facturas Canceladas";
else:
	$consulta="estado=1";
	$titulo="Facturas Emitidas";
endif;

if($empresa):
	$consulta="id_empresa = '$empresa' AND estado=1";
	$emp = mysql_fetch_array(mysql_query("SELECT razon_social FROM config_facturacion WHERE id_empresa = '$empresa'"));
	$titulo="Facturas de ".$emp[0];
endif;

if($comprob=='ingreso'):
	$consulta="tipo_comprobante = '$comprob' AND estado=1";
	$titulo="Ingresos ";
elseif($comprob=='egreso'): 
	$consulta="tipo_comprobante = '$comprob' AND estado=1";
	$titulo="Egresos";
endif;
	
$sql="SELECT fecha_hora, serie, folio, receptor_rfc, receptor_rs, metodo_pago, total, estado, id_factura, uuid, fecha_hora_cancelacion FROM facturas
WHERE $consulta ORDER BY fecha_hora_cfdi DESC";
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
						<span class="caption-subject font-dark bold uppercase"><?=$titulo?></span>
					</div>
					<div class="actions btn-set">
						<!--<a href="javascript:;" class="btn btn-sm blue " data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#nuevoPaciente"><i class="fa fa-plus"></i> Agregar paciente </a>-->      
	                    <div class="btn-group">
	                        <a class="btn blue-chambray dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Filtro
	                            <i class="fa fa-angle-down"></i>
	                        </a>
	                        <ul class="dropdown-menu">
                                	<li>
	                                	<a href="?Modulo=Facturacion&Empresa=1"> DOCBOC S.C. </a>
									</li>
									<li>
	                                	<a href="?Modulo=Facturacion&Empresa=2"> CLINICAS DENTALES POPULARES </a>
									</li>
									<li class="divider"></li>
									<li>
	                                	<a href="?Modulo=Facturacion&Comprob=ingreso"> Ingresos </a>
									</li>
									<li>
	                                	<a href="?Modulo=Facturacion&Comprob=egreso"> Egresos </a>
									</li>
									<?if($empresa || $comprob){?>
									<li class="divider"></li>
									<li>
	                                	<a href="?Modulo=Facturacion"> TODAS </a>
									</li>
									<?}?>
	                        </ul>
	                    </div>
					</div>
				</div>
				<div class="portlet-body">
					<? if($val>0):?>
					<table class="table table-striped table-bordered table-hover" id="tabla_facturas">
						<thead>
					        <tr>
								<th>Fecha</th>
								<th>Folio</th>
								<th>Serie</th>
								<th>RFC</th>
								<th>Razón Social</th>
								<th>Método</th>
								<th>Total</th>
								<? if($factura->estado==2): ?>
								<th width="140">Fecha cancelación</th>
								<? endif; ?>
								<th width="60"></th>
					        </tr>
					    </thead>
					    <tbody>
					      <? foreach($facturas as $factura): ?>
					        <tr class="tr_<?=$factura->id_factura?>">
								<td><?=devuelveFechaHora($factura->fecha_hora)?></td>
								<td><?=$factura->folio?></td>
								<td><?=$factura->serie?></td>
								<td><?=$factura->receptor_rfc?></td>
								<td><?=$factura->receptor_rs?></td>
								<td><?=$factura->metodo_pago?></td>
								<td align="right"><?=$factura->total?></td>
								<? if($factura->estado==2): ?>
								<td><?=fechaLetra(fechaSinHora($factura->fecha_hora_cancelacion)); ?></td>
								<? endif; ?>
								<td <? if($factura->estado==3): ?>colspan="2"<?endif;?>>
									<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load_<?=$factura->id_factura?>" width="19" class="oculto" />
									<!--<a role="button" class="btn btn-xs btn-default" href="reportes/factura_html.php?id=<?=$factura->id_factura?>" data-target="#verFactura" data-toggle="modal">Ver</a>-->
									
									<? if($factura->estado==1): ?>
									<div class="btn-group btn_<?=$factura->id_factura?>">
                                        <a class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Opciones
                                            <i class="fa fa-angle-down"></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="reportes/factura_html.php?id=<?=$factura->id_factura?>" data-target="#verFactura" data-toggle="modal">Ver Factura</a>
                                            </li>
                                            <li>
                                                <a href="javascript:;" onclick="javascript:Cancela(<?=$factura->id_factura?>)">Cancelar</a>
                                            </li>
                                        </ul>
                                    </div>
									<? else: ?>
									Cancelada
                                    <? endif; ?>
                                    
								</td>
					        </tr>
					      <? endforeach; ?>
					    </tbody>
					</table>
					<? else: ?>
					<div class="alert alert-dismissable alert-warning">
				  		<button type="button" class="close" data-dismiss="alert">×</button>
				  		<p>No hay facturas que mostrar.</p>
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
				'targets': [0,4,5,6]
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
			[1, "asc"]
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
		$.post('ac/activa_desactiva_paciente.php', { tipo: "0", id_factura: id },function(data){
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
	$.post('ac/activa_desactiva_paciente.php', { tipo: "1", id_factura: ""+id+"" },function(data){
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

function Cancela(id){
	swal({
		title: "Cancelar Factura",
		text: "¿Estás seguro que quieres cancelar la factura seleccionada?",
		type: "warning",
		confirmButtonText: "Si, cancelar",
		cancelButtonText: "Cancelar",
		showCancelButton: true,
		closeOnConfirm: false,
		showLoaderOnConfirm: true
	}, function () {
		$.post('ac/precancela_fact.php', { tipo: "3", id_factura: id },function(data){
			if(data==1){
				//$(".tr_"+id+"").hide();
				swal("Factura preparada para cancelar", "", "success");
			}else{
				swal("Error", data, "success");
			}
		});
	});
	
	
}
</script>