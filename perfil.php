<?
$id_paciente=$_GET['id'];
$sql="SELECT * FROM pacientes 
JOIN canales ON canales.id_canal=pacientes.id_canal
WHERE id_paciente=$id_paciente";
$q=mysql_query($sql);
$ft=mysql_fetch_assoc($q);
$nombre=$ft['nombre'];
$telefono=$ft['telefono'];
$email=$ft['email'];
$id_canal=$ft['id_canal'];
$canal=$ft['canal'];

if(!$canal){
	$canal = 'DIRECTO';
}

$sql="SELECT * FROM canales WHERE activo=1 ORDER BY canal ASC";
$q=mysql_query($sql);
$canales = array();
while($datos=mysql_fetch_object($q)):
	$canales[] = $datos;
endwhile;


$sql="SELECT usuarios.nombre,consultas.*,clinicas.clinica, citas.*, citas.fecha_hora AS fecha_cita, consultas.fecha_hora AS fecha_consulta, consultas.observaciones AS mensaje, consultas.id_doctor AS doctor FROM citas 
JOIN clinicas ON clinicas.id_clinica=citas.id_clinica
LEFT JOIN consultas ON consultas.id_cita=citas.id_cita
LEFT JOIN usuarios ON usuarios.id_usuario=consultas.id_usuario
WHERE citas.id_paciente=$id_paciente AND consultas.activo=1 ORDER BY citas.fecha_hora DESC";
$q=mysql_query($sql);
$citas = array();
while($datos=mysql_fetch_object($q)):
	$citas[] = $datos;
endwhile;
$val=count($citas);

?>
<style>
.foto{
	height: 150px;
	max-width: 240px;
}	
.titulo_producto{
	margin-top: 5px;
	display: block;
}

.color {
background:#ffffda;
-webkit-transition:background 1s;
-moz-transition:background 1s;
-o-transition:background 1s;
transition:background 1s
}

.color2 {
background:white;
-webkit-transition:background 2s;
-moz-transition:background 2s;
-o-transition:background 2s;
transition:background 2s
}
.ocultar{
	display: none;
}
</style>
<div class="page-content-inner">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light portlet-fit">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-user font-dark"></i>
                        <span class="caption-subject font-dark sbold uppercase">Historial de <?=$nombre?> - <?=$canal?></span>
                    </div>
                    
                    <div class="actions">
						<a href="?Modulo=Pacientes" class="btn btn-circle red-thunderbird"> Regresar </a>
                    </div>
                </div>
                <div class="portlet-body">
	                
	                
	                
                    <div class="row">
<!-- Datos del paciente -->	                    
						<div class="col-md-12 hide">
								<div class="portlet box green">
                            	    <div class="portlet-title">
                            	        <div class="caption">Información de paciente </div>
                            	    </div>
                            	    <div class="portlet-body">
	                        	        
										<div class="form-body" style="margin-top: 20px;">
								
                            			    <form id="frm_datos" class="form-horizontal" role="form" onsubmit="return false">
			
												<div class="form-group">
													<label for="nombre" class="col-md-1 control-label" style="text-align: left;">Nombre</label>
													<div class="col-md-6">
														<input type="text" maxlength="128" class="form-control dat" name="nombre" id="nuevo_nombre" value="<?=$nombre?>" autocomplete="off">
													</div>
												</div>
			
												<div class="form-group">
													<label for="telefono" class="col-md-1 control-label" style="text-align: left;">Teléfono</label>
													<div class="col-md-6">
														<input type="text" maxlength="10" class="form-control dat" name="telefono" value="<?=$telefono?>" autocomplete="off">
													</div>
												</div>
												
												<div class="form-group">
													<label for="direccion" class="col-md-1 control-label" style="text-align: left;">Email</label>
													<div class="col-md-6">
														<input type="text" maxlength="92" class="form-control dat" name="email" value="<?=$email?>" autocomplete="off">
													</div>
												</div>
												
												<div class="form-group">
													<label for="direccion" class="col-md-1 control-label" style="text-align: left;">Canal</label>
													<div class="col-md-6">
														<select class="form-control" name="id_canal">
                    										<option value="0">Seleccione uno</option>
                    										<? foreach($canales as $canal): ?>
															<option <? if($id_canal==$canal->id_canal):?>selected="1"<?endif;?> value="<?=$canal->id_canal?>"><?=$canal->canal?></option>
															<? endforeach; ?>
														</select>
													</div>
												</div>
												
												<input type="hidden" name="id_cita" value="<?=$id_cita?>" />
												<input type="hidden" name="id_paciente" value="<?=$id_paciente?>" />
											</form>
											
										</div>

                            	    </div>
                            	</div>
                            	
						</div>
<!-- Historial de consultas -->
						
                </div>
                
                <? if($val>0):?>
					<table class="table table-striped table-bordered ">
						<thead>
					        <tr>
						        <th>Tipo</th>
								<th>Fecha Cita</th>
								<th>Fecha Consulta</th>
								<th>Clínica</th>
								<th>Atendió / Doctor</th>
								<th>Servicios</th>
								<th style="text-align: right">Monto Pagado</th>
								<th>Observaciones</th>
					        </tr>
					    </thead>
					    <tbody>
					      <? foreach($citas as $cita): ?>
					        <tr class="tr_<?=$cita->id_cita?>">
						        <? if($cita->atendida==1):
								        $tex="Atendida";
								        //$color="bg-green-jungle bg-font-green-jungle";
								    elseif(($cita->atendida==0)&&($cita->confirmada==1)):
								        $tex="Agendada y Confirmada";
								        //$color="bg-blue-chambray bg-font-blue-chambray";
								    elseif(($cita->atendida==0)&&($cita->confirmada==0)&&($cita->activo==1)):
								        $tex="Pendiente por Confirmar";
								        //$color="bg-yellow-saffron bg-font-yellow-saffron";
								    elseif($cita->activo==0):
										$tex="Cita Cancelada";
										//$color="bg-red-thunderbird bg-font-red-thunderbird";
								endif;
							    ?>
						        <td class="<?=$color?>" style="background-color: <?=$cita->color?>; color: white; font:bold;"><?=$tex?></td>
								<td><?=devuelveFechaHora($cita->fecha_cita)?><br><?=devuelveFechaHora($cita->fecha_hora_final)?></td>
								<td><?=devuelveFechaHora($cita->fecha_consulta)?></td>
								<td><?=$cita->clinica;?></td>
								<td><?=$cita->nombre;?> / <?=dameDoctor($cita->doctor)?></td>
								<td><?
									$id_consulta=$cita->id_consulta;
									$sql="SELECT * FROM consultas_tratamientos
									JOIN tratamientos ON tratamientos.id_tratamiento=consultas_tratamientos.id_tratamiento
									WHERE id_consulta=$id_consulta";
									$q=mysql_query($sql);
									while($datos=mysql_fetch_assoc($q)){
										echo $datos['cantidad']." ".$datos['tratamiento']."<br>";
									}
									?>
								</td>
								<td style="text-align: right"><?
									//$sql = "SELECT SUM(precio) FROM consultas_tratamientos WHERE id_consulta = $id_consulta";
									$sql = "SELECT SUM(monto) FROM books_ingresos WHERE id_consulta = $id_consulta";
									$q_precio = mysql_query($sql);
									echo "$".number_format(@mysql_result($q_precio, 0),2);
									?></td>
								<td>
									<? if($cita->atendida==1):
										echo $cita->mensaje;
									else:
										echo $cita->comentario;
									endif; ?>
								</td>
					        </tr>
					      <? endforeach; ?>
					    </tbody>
					</table>
					<? else: ?>
					<div class="alert alert-dismissable alert-warning">
				  		<button type="button" class="close" data-dismiss="alert">×</button>
				  		<p>No hay citas en este historial</p>
				  	</div>
					<? endif; ?>
                
            </div>
        </div>
    </div>
</div>

<script>

function guardaSolicitud(){
	App.blockUI(
		{
            boxed: true,
            message: 'Guardando Consulta.'
        }
	);
	var datos	=	$('#frm_productos,#frm_datos').serialize();
	$.post('ac/guarda_consulta.php',datos,function(data){
		console.log(data);
	    if(data==1){
			window.open("?Modulo=Citas&msg=3", "_self");
	    }else{
	    	App.unblockUI();
			$('#msg_error').html(data);
			$('#msg_error').show('Fast');
	    }
	});
}
</script>
<script src="assets/pages/scripts/ui-sweetalert.min.js" type="text/javascript"></script>