<?
$id_cuenta_get=$_GET['id'];

$sql="SELECT * FROM books_cuentas WHERE id_empresa=$s_id_clinica AND id_cuenta=$id_cuenta_get AND activo=1";
$q=mysql_query($sql);
$valida_acceso=mysql_num_rows($q);
if(!$valida_acceso):
	exit('<div class="alert alert-dismissable alert-danger"><p>Estas intentando entrar a una cuenta que no tienes acceso, se dará aviso al administrador</p></div>');
endif;

if($_GET['tipo']):
	$tipo_web=$_GET['tipo'];
endif;

//Fechas
if($_GET['fecha1']):
	$fecha1=$_GET['fecha1'];
	$fecha2=$_GET['fecha2'];
	$consulta1="AND DATE(fecha_gasto) BETWEEN '$fecha1' AND '$fecha2'";
	$consulta2="AND DATE(fecha_ingreso) BETWEEN '$fecha1' AND '$fecha2'";
	$subtitulo="Consultando del ".fechaLetra($fecha1)." al ".fechaLetra($fecha2);
	$msg_val="No se han creado movimientos en este período de fechas (".fechaLetraDos($fecha1)." - ".fechaLetraDos($fecha2).")";
else:
	//$fecha1=date("Y-m")."-01";
	//$fecha2=date("Y-m")."-".ultimoDia(date("Y"),date("m"));
	$consulta1="ORDER BY fecha_gasto DESC LIMIT 30 ";
	$consulta2="ORDER BY fecha_ingreso DESC LIMIT 30 ";
	$subtitulo="Consultando los últimos 30 movimientos";
	$msg_val="Aún no se han creado movimientos para esta cuenta";
endif;

if(!$tipo_web):
	//Gastos
	$sql="SELECT id_gasto, proveedor, metodo_pago,cuenta_gasto, fecha_gasto, monto, referencia,id_cuenta_receptora,books_gastos.activo  FROM books_gastos
	LEFT JOIN books_proveedores ON books_proveedores.id_proveedor=books_gastos.id_proveedor
	JOIN books_cuentas ON books_cuentas.id_cuenta=books_gastos.id_cuenta
	LEFT JOIN books_metodo_pago ON books_metodo_pago.id_metodo_pago=books_gastos.id_metodo_pago
	JOIN books_tipos_gasto ON books_tipos_gasto.id_tipo_gasto=books_gastos.id_tipo_gasto
	WHERE books_gastos.id_cuenta=$id_cuenta_get $consulta1";
	$q=mysql_query($sql);
	//$operaciones = array();
	$operaciones = array();
	while($datos=mysql_fetch_assoc($q)):
	
		$id = $datos['id_gasto'];
		$proveedor = $datos['proveedor'];
		$metodo_pago = $datos['metodo_pago'];
		$cuenta = $datos['cuenta_gasto'];
		$fecha = $datos['fecha_gasto'];
		$egreso = $datos['monto'];
		$referencia = $datos['referencia'];
		$tipo = "2";
		$id_cuenta = $datos['id_cuenta_receptora'];
		$id_consulta = "0";
		$activo = $datos['activo'];
		
		$operaciones[] = array($id,$proveedor,$metodo_pago,$cuenta,$fecha,$ingreso,$egreso,$referencia,$cliente,$tipo,$id_cuenta,$id_consulta,$activo);
	endwhile;
	
	
	//Ingresos
	$sql="SELECT books_ingresos.*, books_clientes.cliente,books_metodo_pago.metodo_pago,books_tipos_ingreso.cuenta_ingreso,id_cuenta_emisora,id_consulta, books_ingresos.activo FROM books_ingresos 
	LEFT JOIN books_clientes ON books_clientes.id_cliente=books_ingresos.id_cliente
	LEFT JOIN books_metodo_pago ON books_metodo_pago.id_metodo_pago=books_ingresos.id_metodo_pago
	JOIN books_tipos_ingreso ON books_tipos_ingreso.id_tipo_ingreso=books_ingresos.id_tipo_ingreso
	WHERE id_cuenta=$id_cuenta_get $consulta2";
	$q=mysql_query($sql);
	
	while($datos=mysql_fetch_assoc($q)):
		
		$id = $datos['id_ingreso'];
		$cliente = $datos['cliente'];
		$metodo_pago = $datos['metodo_pago'];
		$cuenta = $datos['cuenta_ingreso'];
		$fecha = $datos['fecha_ingreso'];
		$ingreso = $datos['monto'];
		$egreso = "0";
		$referencia = $datos['referencia'];
		$tipo = "1";
		$id_cuenta = $datos['id_cuenta_emisora'];
		$id_consulta = $datos['id_consulta'];
		$activo = $datos['activo'];
		
		$operaciones[] = array($id,$proveedor,$metodo_pago,$cuenta,$fecha,$ingreso,$egreso,$referencia,$cliente,$tipo,$id_cuenta,$id_consulta,$activo);
	endwhile;
	
elseif($tipo_web==1):

	//Gastos
	$sql="SELECT id_gasto, proveedor, metodo_pago,cuenta_gasto, fecha_gasto, monto, referencia,id_cuenta_receptora,books_gastos.activo  FROM books_gastos
	LEFT JOIN books_proveedores ON books_proveedores.id_proveedor=books_gastos.id_proveedor
	JOIN books_cuentas ON books_cuentas.id_cuenta=books_gastos.id_cuenta
	LEFT JOIN books_metodo_pago ON books_metodo_pago.id_metodo_pago=books_gastos.id_metodo_pago
	JOIN books_tipos_gasto ON books_tipos_gasto.id_tipo_gasto=books_gastos.id_tipo_gasto
	WHERE books_gastos.id_cuenta=$id_cuenta_get $consulta1";
	$q=mysql_query($sql);
	//$operaciones = array();
	$operaciones = array();
	while($datos=mysql_fetch_assoc($q)):
	
		$id = $datos['id_gasto'];
		$proveedor = $datos['proveedor'];
		$metodo_pago = $datos['metodo_pago'];
		$cuenta = $datos['cuenta_gasto'];
		$fecha = $datos['fecha_gasto'];
		$egreso = $datos['monto'];
		$referencia = $datos['referencia'];
		$tipo = "2";
		$id_cuenta = $datos['id_cuenta_receptora'];
		$id_consulta = "0";
		$activo = $datos['activo'];
		
		$operaciones[] = array($id,$proveedor,$metodo_pago,$cuenta,$fecha,$ingreso,$egreso,$referencia,$cliente,$tipo,$id_cuenta,$id_consulta,$activo);
	endwhile;

elseif($tipo_web==2):

	//Ingresos
	$sql="SELECT books_ingresos.*, books_clientes.cliente,books_metodo_pago.metodo_pago,books_tipos_ingreso.cuenta_ingreso,id_cuenta_emisora,id_consulta,books_ingresos.activo FROM books_ingresos 
	LEFT JOIN books_clientes ON books_clientes.id_cliente=books_ingresos.id_cliente
	LEFT JOIN books_metodo_pago ON books_metodo_pago.id_metodo_pago=books_ingresos.id_metodo_pago
	JOIN books_tipos_ingreso ON books_tipos_ingreso.id_tipo_ingreso=books_ingresos.id_tipo_ingreso
	WHERE id_cuenta=$id_cuenta_get $consulta2";
	$q=mysql_query($sql);
	
	while($datos=mysql_fetch_assoc($q)):
		
		$id = $datos['id_ingreso'];
		$cliente = $datos['cliente'];
		$metodo_pago = $datos['metodo_pago'];
		$cuenta = $datos['cuenta_ingreso'];
		$fecha = $datos['fecha_ingreso'];
		$ingreso = $datos['monto'];
		$egreso = "0";
		$referencia = $datos['referencia'];
		$tipo = "1";
		$id_cuenta = $datos['id_cuenta_emisora'];
		$id_consulta = $datos['id_consulta'];
		$activo = $datos['activo'];
		
		$operaciones[] = array($id,$proveedor,$metodo_pago,$cuenta,$fecha,$ingreso,$egreso,$referencia,$cliente,$tipo,$id_cuenta,$id_consulta,$activo);
	endwhile;
	
endif;

//print_r(json_encode($operaciones));
//$operaciones=json_encode($operaciones);
$cuenta=1;

$sql="SELECT  books_cuentas.*, clinicas.clinica AS empresa FROM books_cuentas
JOIN clinicas ON clinicas.id_clinica=books_cuentas.id_empresa
WHERE id_cuenta=$id_cuenta_get";
$q=mysql_query($sql);
$ft=mysql_fetch_assoc($q);

$valida=count($operaciones);

$ingresos=dameIngresos($id_cuenta_get);
$egresos=dameEgresoso($id_cuenta_get);
			                        
$saldo=$ingresos-$egresos;
?>

<div class="page-content-inner">
	<div class="row">
		<div class="col-md-12">
	        <!-- BEGIN BORDERED TABLE PORTLET-->
	        <div class="portlet light portlet-fit ">
	            <div class="portlet-title">
	                <div class="caption">
	                    <i class="icon-book-open font-dark"></i>
	                    <span class="caption-subject font-dark sbold uppercase">Transacciones de <?=$ft['empresa'] ?> (<?=$ft['alias'] ?>/<?=dameTipo($ft['tipo_cuenta']) ?>) </span>
	                    <div class="caption-desc font-grey-cascade"> <?=$subtitulo?> - Saldo al día: <?=number_format($saldo,2)?> </div>
	                </div>
	                <div class="actions">
		                <a class="btn red-thunderbird hidden-print print-btn" onclick="javascript:window.print();">Imprimir</a>&nbsp;&nbsp;
		                <? if($fecha1): ?>
		                <a href="?Modulo=Transacciones&id=<?=$id_cuenta_get?>" class="btn btn-sm red-thunderbird hidden-print"> Últimos 30 movimientos </a>&nbsp;&nbsp;
		                <? endif; ?>
	                    <a href="javascript:;" class="btn btn-sm blue-chambray hidden-print" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#filtro_fechas"> Filtro por fechas </a>&nbsp;&nbsp;
	                    <div class="btn-group ">
	                        <a class="btn blue-chambray dropdown-toggle hidden-print" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Tipo
	                            <i class="fa fa-angle-down"></i>
	                        </a>
	                        <ul class="dropdown-menu">
	                            <li>
	                                <a href="?Modulo=Transacciones&id=<?=$id_cuenta_get?>&tipo=2&fecha1=<?=$fecha1?>&fecha2=<?=$fecha2?>"> Ingresos </a>
	                            </li>
	                            <li>
	                                <a href="?Modulo=Transacciones&id=<?=$id_cuenta_get?>&tipo=1&fecha1=<?=$fecha1?>&fecha2=<?=$fecha2?>"> Gastos </a>
	                            </li>
	                            <li>
	                                <a href="?Modulo=Transacciones&id=<?=$id_cuenta_get?>&fecha1=<?=$fecha1?>&fecha2=<?=$fecha2?>"> Todos </a>
	                            </li>
	                            
	                        </ul>
	                    </div>
	                    
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
	                                <th> Concepto </th>
	                                <th width="150" style="text-align: right"> Ingresos </th>
	                                <th width="150" style="text-align: right"> Gastos </th>
	                                <th width="100" class="hidden-print">  </th>
	                            </tr>
	                        </thead>
	                        <tbody>
		                        <? 
			                        foreach ($operaciones as $key => $value) {
										$fechas[$key]=$operaciones[$key][4];
									}
									array_multisort($fechas, SORT_DESC, $operaciones);
									
			                        foreach ($operaciones as $val => $value):
			                        	$activo=$operaciones[$val][12];
			                        	
			                        	if($activo==1):
											$total_ingresos+=$operaciones[$val][5];
											$total_egresos+=$operaciones[$val][6];
											
											if($operaciones[$val][9]==2){
												//Salida
												$fecha="<i class='fa fa-arrow-left'></i>";
												$saldo-=$operaciones[$val][6];
											}else{
												//Entrada
												$fecha="<i class='fa fa-arrow-right'></i>";
												$saldo+=$operaciones[$val][5];
											}
										endif;
										
										$total_cuenta=$total_ingresos-$total_egresos;
								?>
	                            <tr <? if($activo==0): echo "style='text-decoration:line-through;' class='danger' "; endif; ?>>
	                                <td> <?=$cuenta?> </td>
	                                <td class="font-dark"> <?=fechaLetra(fechaSinHora($operaciones[$val][4]))?> </td>
	                                <td class="font-dark"> <?=$operaciones[$val][3]?> <? if($operaciones[$val][9]==2){ echo "<br><small><em>PROVEEDOR: ".$operaciones[$val][1]."</em></small>"; }?> <? if($operaciones[$val][10]): echo "<br><small><em>".$fecha." ".datosCuenta($operaciones[$val][10])."</em></small>"; endif;?></td>
	                                <td align="right" class="font-dark"> <? if($operaciones[$val][5]): echo number_format($operaciones[$val][5],2); endif;?> </td>
	                                <td align="right" class="font-dark"> <? if($operaciones[$val][6]): echo number_format($operaciones[$val][6],2); endif;?> </td>
	                                <td align="right" class="font-dark hidden-print">
		                                <? if($activo==1): ?>
		                                	<a href="javascript:;" class="btn red btn-outline btn-xs hidden-print" role="button" onclick="javascript:eliminaOperacion(<?=$operaciones[$val][0]?>,<?=$operaciones[$val][9]?>,<?=$operaciones[$val][11]?>)">Cancelar</a> 
		                                <? else: ?>
		                                
		                                <? endif; ?>
		                            </td>
	                            </tr>
	                            <? 
		                            $cuenta++;
		                            endforeach; ?>
	                            <!--
	                            <tr>
	                                <td> &nbsp;  </td>
	                                <td class="font-dark"> &nbsp; </td>
	                                <td class="font-dark"> &nbsp; </td>
	                                <td align="right" class="font-dark"> &nbsp; </td>
	                                <td align="right" class="font-dark"> &nbsp; </td>
	                                <td align="right" class="font-dark"> &nbsp; </td>
	                            </tr>-->
	                            <? if(!$tipo_web): ?>
	                            <tr>
	                                <td>  </td>
	                                <td class="font-dark">  </td>
	                                <td class="font-dark">  </td>
	                                <td align="right" class="font-dark"> <?=number_format($total_ingresos,2)?> </td>
	                                <td align="right" class="font-dark"> <?=number_format($total_egresos,2)?> </td>
	                                <td align="right" class="font-dark hidden-print">  </td>
	                            </tr>
	                            <? endif; ?>
	                        </tbody>
	                    </table>
	                </div>
	                <? else: ?>
	                <div class="alert alert-dismissable alert-success">
						<p><?=$msg_val?></p>
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