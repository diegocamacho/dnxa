<?

include("../includes/db.php");
include("../includes/funciones.php");
include("../includes/session.php");

if($_GET['Clinica']):
	$cli=$_GET['Clinica'];
	$consulta_grafica="AND id_clinica=$cli";
	$consulta_grafica2="books_cuentas.id_empresa=$cli AND";
endif;
$sql="SELECT id_clinica AS id_empresa, clinica AS empresa FROM clinicas WHERE activo=1 AND todos=0 $consulta_grafica";
$q=mysql_query($sql);
$empresas = array();
while($datos=mysql_fetch_object($q)):
	$empresas[] = $datos;
endwhile;
?>
<div class="row">
						<?foreach($empresas as $empresa){
							//POR CADA UNA DE LAS EMPRESAS HAY QUE SACAR LOS PACIENTES
							$inicial = date('Y-m-01');; 
							$final = date('Y-m-t');
							$pacientes_atendidos = 0;
							$pacientes_nuevos = 0;
							$id_clinica = $empresa->id_empresa;
							//SACAMOS LOS PACIENTES QUE FUERON ATENDIDOS EN LA CLINICA ESE DIA 
							$pacientes_clinica = mysql_query("SELECT DISTINCT(id_cita),id_paciente FROM citas 
															WHERE id_clinica = '$id_clinica' AND DATE(fecha_hora) = '$fecha_actual' AND citas.tipo=1 AND citas.activo=1");
							
							while($paciente = mysql_fetch_assoc($pacientes_clinica)){
								$id_cita = $paciente['id_cita'];
								$id_paciente = $paciente['id_paciente'];
								$check = mysql_num_rows(mysql_query("SELECT id_consulta FROM consultas WHERE id_cita = '$id_cita'"));
									if($check == 1){
										$pacientes_atendidos++;
										$check2 = mysql_num_rows(mysql_query("SELECT id_consulta FROM consultas WHERE id_paciente = '$id_paciente'"));
										if($check2 == 1){
											$pacientes_nuevos++;
										}
									}
							}
							$nuevos_total += $pacientes_nuevos;
							$atendidos_total += $pacientes_atendidos;
						?>
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-10 margin-bottom-10">
							<div class="dashboard-stat grey-cascade">
								<div class="visual">
									<i class="icon-user"></i>
								</div>
								<div class="details">
									<div class="number">
										<?=$pacientes_atendidos?> &nbsp; / &nbsp; <?=$pacientes_nuevos?>
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
							<div class="dashboard-stat grey-cascade">
								<div class="visual">
									<i class="icon-user"></i>
								</div>
								<div class="details">
									<div class="number">
										<?=$atendidos_total?> / <?=$nuevos_total?>
									</div>
									<div class="desc">
										TOTAL DIARIO ATENDIDOS / NUEVOS
									</div>
								</div>
								<!--<a class="more" href="?Modulo=Citas">
								Citas <i class="m-icon-swapright m-icon-white"></i>
								</a>-->
							</div>
						</div>
					</div>
<?

?>