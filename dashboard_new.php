<?
if($_GET['Clinica']):
	$cli=$_GET['Clinica'];
	$consulta_grafica="AND id_clinica=$cli";
	$consulta_grafica2="books_cuentas.id_empresa=$cli AND";
endif;
$sql="SELECT id_clinica AS id_empresa, clinica AS empresa FROM clinicas WHERE activo=1 $consulta_grafica";
$q=mysql_query($sql);
$empresas = array();
while($datos=mysql_fetch_object($q)):
	$empresas[] = $datos;
endwhile;
$sql="SELECT id_clinica AS id_empresa, clinica AS empresa FROM clinicas WHERE todos = 0 AND activo=1 $consulta_grafica";
$q=mysql_query($sql);
$empresas2 = array();
while($datos=mysql_fetch_object($q)):
	$empresas2[] = $datos;
endwhile;
$valida_empresas=count($empresas);
$cuenta=1;

//Gastos
$mes = "1";
$ano = date("Y");


for ($i = $mes; $i <= 12; $i++):
	$fecha1=$ano."-".$i."-"."01";
	$fecha2=$ano."-".$i."-".ultimoDia($ano,$mes);
	$sql="SELECT SUM(monto) AS total FROM books_gastos 
	JOIN books_cuentas ON books_cuentas.id_cuenta=books_gastos.id_cuenta
	WHERE books_gastos.activo=1 AND books_gastos.id_tipo_gasto !=1 AND $consulta_grafica2 DATE(fecha_gasto) BETWEEN '$fecha1' AND '$fecha2'";
	$q=mysql_query($sql);
	$ft=mysql_fetch_assoc($q);
	
	if($ft['total']):
		$total=$ft['total'];
	else:
		$total=0;
	endif;
	
	if($i==1):
		$c_gastos=$total;
	else:
		$c_gastos.=",".$total;	
	endif;
	
endfor;

//Ingresos
for ($i = $mes; $i <= 12; $i++):
	$fecha1=$ano."-".$i."-"."01";
	$fecha2=$ano."-".$i."-".ultimoDia($ano,$mes);
	$sql="SELECT SUM(monto) AS total FROM books_ingresos 
	JOIN books_cuentas ON books_cuentas.id_cuenta=books_ingresos.id_cuenta
	WHERE books_ingresos.activo=1 AND books_ingresos.id_tipo_ingreso !=1 AND $consulta_grafica2 DATE(fecha_ingreso) BETWEEN '$fecha1' AND '$fecha2'";
	$q=mysql_query($sql);
	$ft=mysql_fetch_assoc($q);
	
	if($ft['total']):
		$total=$ft['total'];
	else:
		$total=0;
	endif;
	
	if($i==1):
		$c_ingresos=$total;
	else:
		$c_ingresos.=",".$total;	
	endif;
	
endfor;
?>
<style>
.oculto{
	display: none;
}
.link{
	cursor: pointer;
}
</style>
<!--<h3>Dentista Books</h3>-->
<div class="page-content-inner">
	<? if($valida_empresas): ?>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-tasks" style="margin-right:8px;"></i><span class="caption-subject  bold">Ingresos Clínicas del <?=fechaLetra($fecha_actual)?></span></div>
				</div>
				<div class="portlet-body">
					<div class="row">
						<?foreach($empresas2 as $empresa){
							//POR CADA UNA DE LAS EMPRESAS HAY QUE SACAR SUS CUENTAS
							$dinero_dia = 0;
							$id_clinica = $empresa->id_empresa;
							$q_cuentas = mysql_query("SELECT id_cuenta FROM books_cuentas WHERE id_empresa = '$id_clinica'");
							while($ft = mysql_fetch_assoc($q_cuentas)){
								$id_cuenta = $ft['id_cuenta'];
							//SACAMOS LA LANA DEL DIA DE CADA UNA DE LAS EMPRESAS
								$dinero_dia += mysql_result(mysql_query("SELECT SUM(monto) FROM books_ingresos WHERE fecha_ingreso = '$fecha_actual' AND id_cuenta = '$id_cuenta' AND activo = 1"), 0);
							}
							$dinero_total += $dinero_dia;
						?>
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 margin-bottom-10">
							<div class="dashboard-stat green-meadow">
								<div class="visual">
									<i class="icon-plus"></i>
								</div>
								<div class="details">
									<div class="number">
										$<?=number_format($dinero_dia,2,'.',',')?>
									</div>
									<div class="desc">
										<small><?=$empresa->empresa?></small>
									</div>
								</div>
							</div>
						</div>
						<?}?>
						
					</div>
					<br>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="dashboard-stat green-meadow">
								<div class="visual">
									<i class="icon-plus"></i>
								</div>
								<div class="details">
									<div class="number">
										$<?=number_format($dinero_total,2,'.',',')?>
									</div>
									<div class="desc">
										TOTAL INGRESO
									</div>
								</div>
								<!--<a class="more" href="?Modulo=Operaciones">
								Operaciones <i class="m-icon-swapright m-icon-white"></i>
								</a>-->
							</div>
						</div>
					</div>
					<br>
				</div>
			</div>
			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-tasks" style="margin-right:8px;"></i><span class="caption-subject  bold">Venta Acumulada de <?=soloMes(date('n'))?></span></div>
				</div>
				<div class="portlet-body">
					<div class="row">
						<?	$dinero_total = 0;
							foreach($empresas2 as $empresa){
							//POR CADA UNA DE LAS EMPRESAS HAY QUE SACAR SUS CUENTAS
							$inicial = date('Y-m-01');; 
							$final = date('Y-m-t');
							$dinero_dia = 0;
							$id_clinica = $empresa->id_empresa;
							$q_cuentas = mysql_query("SELECT id_cuenta FROM books_cuentas WHERE id_empresa = '$id_clinica'");
							while($ft = mysql_fetch_assoc($q_cuentas)){
								$id_cuenta = $ft['id_cuenta'];
							//SACAMOS LA LANA DEL DIA DE CADA UNA DE LAS EMPRESAS
								$dinero_dia += mysql_result(mysql_query("SELECT SUM(monto) FROM books_ingresos WHERE fecha_ingreso >= '$inicial' AND fecha_ingreso <= '$final' AND id_cuenta = '$id_cuenta' AND activo = 1"), 0);
								
							}
							$dinero_total += $dinero_dia;
						?>
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-10 margin-bottom-10">
							<div class="dashboard-stat yellow-gold">
								<div class="visual">
									<i class="icon-plus"></i>
								</div>
								<div class="details">
									<div class="number">
										$<?=number_format($dinero_dia,2,'.',',')?>
									</div>
									<div class="desc">
										<small><?=$empresa->empresa?></small>
									</div>
								</div>
							</div>
						</div>
						<?}?>
					</div>
					<br>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="dashboard-stat yellow-gold">
								<div class="visual">
									<i class="icon-plus"></i>
								</div>
								<div class="details">
									<div class="number">
										$<?=number_format($dinero_total,2,'.',',')?>
									</div>
									<div class="desc">
										TOTAL VENTA ACUMULADA
									</div>
								</div>
								<!--<a class="more" href="?Modulo=Operaciones">
								Operaciones <i class="m-icon-swapright m-icon-white"></i>
								</a>-->
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-tasks" style="margin-right:8px;"></i><span class="caption-subject  bold">Pacientes Atendidos / Nuevos del <?=fechaLetra($fecha_actual)?></span></div>
				</div>
				<div class="portlet-body" id="pacientes_diario">
					<center><img src="loader.gif" style="display: none;" id="loader2"></center>
				</div>
			</div>
			
			<div class="portlet light">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-tasks" style="margin-right:8px;"></i><span class="caption-subject  bold">Pacientes Atendidos / Nuevos de <?=soloMes(date('n'))?></span></div>
				</div>
				<div class="portlet-body" id="pacientes_acumulado">
					<center><img src="loader.gif" style="display: none;" id="loader"></center>
				</div>
			</div>

	        <!-- BEGIN BORDERED TABLE PORTLET-->
	        <div class="portlet light portlet-fit ">
	            <div class="portlet-title">
	                <div class="caption">
	                    <i class="icon-book-open font-dark"></i>
	                    <span class="caption-subject font-dark sbold uppercase">Dentista Books Resumen</span>
	                </div>
	                <div class="actions">
		                
	                    <div class="btn-group">
	                        <a class="btn blue-chambray dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Filtro
	                            <i class="fa fa-angle-down"></i>
	                        </a>
	                        <ul class="dropdown-menu">
		                        <? foreach($clinicas as $clinica): ?>
                                	<li>
	                                	<a href="?Clinica=<?=$clinica->id_clinica?>"> <?=$clinica->clinica?> </a>
									</li>
                                <? endforeach; ?>
	                        </ul>
	                    </div>
	                    
	                </div>
	            </div>
	            <div class="portlet-body">
	                <div class="table-scrollable table-scrollable-borderless">
	                    <table class="table table-hover table-light">
	                        <thead>
	                            <tr class="uppercase">
	                                <th width="40"> # </th>
	                                <th> Empresas </th>
	                                <th width="120" style="text-align: right"> Efectivo </th>
	                                <th width="120" style="text-align: right"> Banco </th>
	                                <th width="150"> </th>
	                            </tr>
	                        </thead>
	                        <tbody>
		                        <? foreach($empresas as $empresa): 
			                        
			                        //Sacamos saldos
			                        unset($total_ingresos);
			                        unset($total_egresos);
			                        //unset($saldo);
			                        $id_empresa=$empresa->id_empresa;

			                        $sql="SELECT id_cuenta FROM books_cuentas WHERE id_empresa=$id_empresa AND activo=1 AND tipo_cuenta=2";
			                        $q=mysql_query($sql);
			                        while($ft=mysql_fetch_assoc($q)):
			                        	$id_cuenta=$ft['id_cuenta'];
			                        	
			                        	//movimientos
			                        	$ingresos=dameIngresos($id_cuenta);
										$egresos=dameEgresoso($id_cuenta);
			                        	
			                        	$total_ingresos+=$ingresos;
			                        	$total_egresos+=$egresos;
			                        endwhile;
			                        $saldo_efectivo=$total_ingresos-$total_egresos;
			                        
			                        unset($total_ingresos);
			                        unset($total_egresos);
			                        $sql="SELECT id_cuenta FROM books_cuentas WHERE id_empresa=$id_empresa AND activo=1 AND tipo_cuenta=3";
			                        $q=mysql_query($sql);
			                        while($ft=mysql_fetch_assoc($q)):
			                        	$id_cuenta=$ft['id_cuenta'];
			                        	
			                        	//movimientos
			                        	$ingresos=dameIngresos($id_cuenta);
										$egresos=dameEgresoso($id_cuenta);
			                        	
			                        	$total_ingresos+=$ingresos;
			                        	$total_egresos+=$egresos;
			                        endwhile;
			                        $saldo_banco=$total_ingresos-$total_egresos;

		                        ?>
	                            <tr>
	                                <td> <?=$cuenta?> </td>
	                                <td> <?=$empresa->empresa?> </td>
	                                <td align="right" class="font-dark"> <?=number_format($saldo_efectivo,2)?> </td>
	                                <td align="right" class="font-dark"> <?=number_format($saldo_banco,2)?> </td>

	                                <td align="right">
	                                    <a href="?Modulo=Operaciones&id=<?=$empresa->id_empresa?>" role="button" class="btn  blue-chambray btn-xs ">Cuentas</a>
	                                </td>
	                            </tr>
	                            <? 
		                            
		                            $cuenta++;
		                            endforeach; ?>
	                            
	                        </tbody>
	                    </table>
	                </div>
	            </div>
	        </div>
	        <!-- END BORDERED TABLE PORTLET-->
	    </div>
	</div>
<!--	
	<center>
		<input type="button" value="Imprimir" id="imprimir" />
	</center>	
	<br/>
-->

	<div class="row">
	    <div class="col-md-12">
	        <div class="portlet light portlet-fit ">
	            <div class="portlet-title">
	                <div class="caption">
	                    <i class=" icon-layers font-dark"></i>
	                    <span class="caption-subject font-dark bold uppercase">Estadisticas</span>
	                </div>
	                <div class="actions">
	                    
	                </div>
	            </div>
	            <div class="portlet-body">
	                <div id="echarts_bar" style="height:500px;"></div>
	            </div>
	        </div>
	    </div>
	</div>
	<? else: ?>
	
	
	
	<? endif; ?>
</div>





<script>
jQuery(document).ready(function() {
	setTimeout(function() {
	
		datosDiarios();
		
	
	}, 1000);
	
	setTimeout(function() {
	
		datosAcumulados();
		
	
	}, 1500);
	
	$('#imprimir').click(function() {
		alert('click');
		$.get('http://epicmedia.pro/dentista/print.php',function(data) {
			alert('data');
			$.post('http://localhost/imprimir_remoto.php','imprimir='+data,function() {			
				alert('post');
			});

			
		
		})
			
	
	});
	
	
    // ECHARTS
    require.config({
        paths: {
            echarts: 'assets/global/plugins/echarts/'
        }
    });

    // DEMOS
    require(
        [
            'echarts',
            'echarts/chart/bar',
            'echarts/chart/chord',
            'echarts/chart/eventRiver',
            'echarts/chart/force',
            'echarts/chart/funnel',
            'echarts/chart/gauge',
            'echarts/chart/heatmap',
            'echarts/chart/k',
            'echarts/chart/line',
            'echarts/chart/map',
            'echarts/chart/pie',
            'echarts/chart/radar',
            'echarts/chart/scatter',
            'echarts/chart/tree',
            'echarts/chart/treemap',
            'echarts/chart/venn',
            'echarts/chart/wordCloud'
        ],
        function(ec) {
            //--- BAR ---
            var myChart = ec.init(document.getElementById('echarts_bar'));
            myChart.setOption({
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: ['Egresos', 'Ingresos']
                },
                toolbox: {
                    show: false,
                    feature: {
                        mark: {
                            show: true
                        },
                        dataView: {
                            show: true,
                            readOnly: false
                        },
                        magicType: {
                            show: true,
                            type: ['line', 'bar']
                        },
                        restore: {
                            show: true
                        },
                        saveAsImage: {
                            show: true
                        }
                    }
                },
                calculable: true,
                xAxis: [{
                    type: 'category',
                    data: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']
                }],
                yAxis: [{
                    type: 'value',
                    splitArea: {
                        show: true
                    }
                }],
                series: [{
                    name: 'Egresos',
                    type: 'bar',
                    data: [<?=$c_gastos?>]
                }, {
                    name: 'Ingresos',
                    type: 'bar',
                    data: [<?=$c_ingresos?>]
                }]
            });

        }
    );
});	

function datosAcumulados(){
	$('#loader').show();
	
	$.get('data/dashboard_acumulado.php',function(data) {
	
		$('#pacientes_acumulado').html(data);
		$('#loder').hide();
		
	});
	
}

function datosDiarios(){
	$('#loader2').show();
	
	$.get('data/dashboard_diario.php',function(data) {
	
		$('#pacientes_diario').html(data);
		$('#loder2').hide();
		
	});
	
}
</script>