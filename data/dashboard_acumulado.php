<?
error_reporting();
//set_time_limit(3600);
include("../includes/db.php");
include("../includes/funciones.php");
//include("../includes/session.php");

if($_GET['Clinica']):
	$cli=$_GET['Clinica'];
	$consulta_grafica="AND id_clinica=$cli";
	$consulta_grafica2="books_cuentas.id_empresa=$cli AND";
endif;
$sql="SELECT id_clinica AS id_empresa, clinica AS empresa FROM clinicas WHERE activo=1 AND todos = 0 $consulta_grafica";
$q = mysqli_query($conexion, $sql, MYSQLI_USE_RESULT);
$empresas = array();
while($data = mysqli_fetch_object($q)):
	$empresas[] = $data;
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
							$pacientes_clinica = mysqli_query($conexion,"SELECT DISTINCT(id_cita),id_paciente FROM citas WHERE id_clinica = '$id_clinica' AND DATE(fecha_hora) >= '$inicial' AND DATE(fecha_hora) <= '$final'  AND citas.tipo=1 AND citas.activo=1 AND citas.atendida = 1 ");
							//print_r($pacientes_clinica);
							//file_put_contents(date("HH_MM_SS").rand().".txt", 'primer paso');
							
							while($paciente = mysqli_fetch_assoc($pacientes_clinica)){
								$id_cita = $paciente['id_cita'];
								$id_paciente = $paciente['id_paciente'];
								$q1=mysqli_query($conexion,"SELECT id_consulta FROM consultas WHERE id_cita = '$id_cita'");
								$check = mysqli_num_rows($q1);
								if($check == 1){
										$q2=mysqli_query($conexion,"SELECT id_consulta FROM consultas WHERE id_paciente = '$id_paciente'");
										$check2 = mysqli_num_rows($q2);
										if($check2 == 1){
											$pacientes_nuevos++;
										}
									}
								//file_put_contents(date("HH_MM_SS").rand().".txt", 'segundo paso');
							}
							$pacientes_atendidos = mysqli_num_rows($pacientes_clinica);
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
										TOTAL ACUMULADO ATENDIDOS / NUEVOS
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