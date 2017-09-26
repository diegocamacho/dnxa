<?
$id_especialista_lab=$_GET['id'];

$sql="SELECT * FROM especialistas_lab WHERE id_especialista_lab=$id_especialista_lab";
$q=mysql_query($sql);
$ft=mysql_fetch_assoc($q);
$nombre=$ft['nombre'];

//Consultas
$sql="SELECT id_consulta, consultas.id_paciente, consultas.id_clinica, fecha_hora, pacientes.nombre AS paciente, clinica, consultas.activo FROM consultas 
LEFT JOIN pacientes ON pacientes.id_paciente=consultas.id_paciente
LEFT JOIN clinicas ON clinicas.id_clinica=consultas.id_clinica
WHERE id_especialista_lab=$id_especialista_lab";
$q=mysql_query($sql);
while($datos=mysql_fetch_object($q)):
	$consultas[] = $datos;
endwhile;
$valida=count($consultas);

$cuenta=1;
?>

<div class="page-content-inner">
	<div class="row">
		<div class="col-md-12">
	        <!-- BEGIN BORDERED TABLE PORTLET-->
	        <div class="portlet light portlet-fit ">
	            <div class="portlet-title">
	                <div class="caption">
	                    <i class="icon-book-open font-dark"></i>
	                    <span class="caption-subject font-dark sbold uppercase">Transacciones de <?=$nombre?></span>
	                    <div class="caption-desc font-grey-cascade">&nbsp; </div>
	                </div>
	                <div class="actions">
		                <a class="btn red-thunderbird hidden-print print-btn" onclick="javascript:window.print();">Imprimir</a>
	                </div>
	            </div>
	            <div class="portlet-body">
		            
		            <? if($valida): ?>
	                <div class="table-scrollable table-scrollable-borderless">
	                    <table class="table table-hover table-light">
	                        <thead>
	                            <tr class="uppercase">
	                                <th width="40"> # </th>
	                                <th width="110"> Fecha </th>
	                                <th> Paciente </th>
	                                <th> Clínica </th>
	                                <th width="150" style="text-align: right"> Monto </th>
	                                <th width="100" class="hidden-print">  </th>
	                            </tr>
	                        </thead>
	                        <tbody>
		                        <? foreach($consultas as $consulta): ?>
	                            <tr <? if($consulta->activo==0): echo "style='text-decoration:line-through;' class='danger' "; endif; ?>>
	                                <td> <?=$cuenta?> </td>
	                                <td class="font-dark"> <?=fechaLetra(fechaSinHora($consulta->fecha_hora))?> </td>
	                                <td class="font-dark"> <?=$consulta->paciente?></td>
	                                <td class="font-dark"> <?=$consulta->clinica?></td>
	                                <td align="right" class="font-dark"> <?=number_format(dameMontoConsulta($consulta->id_consulta),2)?> </td>
	                                <td align="right" class="font-dark hidden-print">
		                                <? if($consulta->activo==1): ?>
		                                	<a href="javascript:;" class="btn red btn-outline btn-xs hidden-print" role="button" onclick="javascript:eliminaOperacion(<?=$operaciones[$val][0]?>,<?=$operaciones[$val][9]?>,<?=$operaciones[$val][11]?>)">Eliminar</a> 
		                                <? endif; ?>
		                            </td>
	                            </tr>
	                            <? 
		                            $cuenta++;
		                            $total_billete+=dameMontoConsulta($consulta->id_consulta);
		                            endforeach; ?>
	                            
	                            <? if(!$tipo_web): ?>
	                            <tr>
	                                <td>  </td>
	                                <td class="font-dark">  </td>
	                                <td class="font-dark">  </td>
	                                <td align="right" class="font-dark"> </td>
	                                <td align="right" class="font-dark"> <?=number_format($total_billete,2)?> </td>
	                                <td align="right" class="font-dark hidden-print">  </td>
	                            </tr>
	                            <? endif; ?>
	                        </tbody>
	                    </table>
	                </div>
	                <? else: ?>
	                <div class="alert alert-dismissable alert-success">
						<p>Aún no se han creado consultas con este especialista.</p>
					</div>
	                <? endif; ?>
	            </div>
	        </div>
	        <!-- END BORDERED TABLE PORTLET-->
	    </div>
	</div>
</div>









<div id="filtro_fechas" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Filtrado por fechas</h4>
            </div>
            <div class="modal-body">
                <form action="#" class="form-horizontal">
                    <div class="form-group">
                        <label class="control-label col-md-4">Rango de fechas</label>
                        <div class="col-md-8">
                            <div class="input-group input-medium date-picker input-daterange" data-date="01/01/2017" data-date-format="yyyy-mm-dd">
                                <input type="text" class="form-control" name="fecha1" id="fecha1">
                                <span class="input-group-addon"> a </span>
                                <input type="text" class="form-control" name="fecha2" id="fecha2"> </div>
                            <!-- /input-group -->
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
	            <img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load" width="25" style="display: none;" />
                <button class="btn btn-ac dark btn-outline" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                <button class="btn btn-ac green" onclick="cambiaFecha()">Filtrar</button>
            </div>
        </div>
    </div>
</div>
<script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script>
function eliminaOperacion(id,tipo,id_consulta){
	if(id_consulta==0){
		var mensaje = "";
	}else{
		var mensaje = "Al cancelar este movimiento, cancelará la consulta.";
	}
	swal({
		title: "Cancelar operación",
		text: "¿Estás seguro que quieres cancelar la operación? <br><b style='color:#D91E18'>"+mensaje+"</b>",
		type: "warning",
		confirmButtonText: "Si, cancelar",
		cancelButtonText: "No",
		showCancelButton: true,
		closeOnConfirm: false,
		showLoaderOnConfirm: true,
		html: true
	},function(){
		$.post('ac/cancela_operacion.php', { id: id, tipo: tipo, id_consulta: id_consulta},function(data){

		console.log(data);
		var datos = data.split('|');
		
	    if(datos[0]==1){
		    
			if(datos[1]){
				$.post('http://localhost/imprimir_remoto.php','imprimir='+datos[1]);
			}
			
			swal({
			title: "Operación cancelada",
			type: "success",
			confirmButtonText: "Ok",
			}, function () {
				window.open("?Modulo=Transacciones&id=<?=$id_cuenta_get?>&tipo=<?=$tipo_web?>&fecha1=<?=$fecha1?>&fecha2=<?=$fecha2?>", "_self");
			});
			}else{
				swal("Error", data, "error");
			}
		});
	});

}

function cambiaFecha(){
	var fecha1 = $('#fecha1').val();
	var fecha2 = $('#fecha2').val();
	$('.btn-ac').hide();
	$('#load').show();
	if((fecha1)&&(fecha2)){
		window.open("?Modulo=Transacciones&id=<?=$id_cuenta_get?>&fecha1="+fecha1+"&fecha2="+fecha2, "_self");
	}else{
		alert("Seleccione las fecha de inicio y fecha final.");
		$('#load').hide();
		$('.btn-ac').show();
	}
}


</script>