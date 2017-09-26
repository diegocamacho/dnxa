<?php
	include("includes/db.php");
include("includes/funciones.php");

	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	if($action == 'ajax'){
		include 'paginacion.php'; //incluir el archivo de paginación
		//las variables de paginación
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 100; //la cantidad de registros que desea mostrar
		$adjacents  = 4; //brecha entre páginas después de varios adyacentes
		$offset = ($page - 1) * $per_page;
		//Cuenta el número total de filas de la tabla*/
		if($_REQUEST['id_paciente']):
			$id_paciente=$_REQUEST['id_paciente'];
			$busqueda="AND id_paciente=".$id_paciente;
		endif;

		if($_REQUEST['id_cliente']):
			$id_cliente=$_REQUEST['id_cliente'];
			$datos_plan = 1;
			if($id_cliente=='TODOS'):
				$busqueda="AND pacientes.id_cliente>0";
			else:
				$busqueda="AND pacientes.id_cliente=".$id_cliente;
			endif;
		endif;

		#$count_query   = mysql_query("SELECT count(id_paciente) AS numrows FROM pacientes");

		
		
		/*
		$sql="SELECT count(id_paciente) AS numrows FROM pacientes";
		$q=mysql_query($sql);
		$ft=mysql_fetch_assoc($q);
		$numrows=$ft['numrows'];
		$total_pages = ceil($numrows/$per_page);
		$reload = 'index.php';*/
		//consulta principal para recuperar los datos
		$sq="SELECT canales.canal, id_paciente,nombre,pacientes.email,pacientes.telefono,fecha_registro,pacientes.activo,pacientes.encuestado,books_clientes.cliente,books_clientes.fecha_final_plan FROM pacientes 
		LEFT JOIN canales ON canales.id_canal=pacientes.id_canal
		LEFT JOIN books_clientes ON books_clientes.id_cliente=pacientes.id_cliente
		WHERE tipo=1 AND pacientes.activo=1 $busqueda ORDER BY fecha_registro DESC LIMIT $offset,$per_page";
		$query = mysql_query($sq);
		$pacientes = array();
		while($datos=mysql_fetch_object($query)):
			$pacientes[] = $datos;
		endwhile;
		
		$numrows = count($pacientes);
	
		$total_pages = ceil($numrows/$per_page);
		$reload = 'index.php';
		
		if ($numrows>0){
			?>
			<table class="table table-striped table-bordered table-hover">
				<thead>
			        <tr>
				        <th>Empresa</th>
						<th>Paciente</th>
						<!--<th>Email</th>-->
						<th>Teléfono</th>
						<th>Canal</th>
						<th>Registro</th>
						<th>Última Cita</th>
					<? if($datos_plan==1): ?>
						<th>Expiración</th>						
					<? endif;?>
						<th>Aten/Enc</th>
						<th width="60"></th>
			        </tr>
			    </thead>
			    <tbody>
			      <? foreach($pacientes as $paciente): ?>
			        <tr class="tr_<?=$paciente->id_paciente?>">
				        <td><? if($paciente->cliente){ echo $paciente->cliente; }else{ echo "N/A"; }?></td>
						<td><?=$paciente->nombre?></td>
						<!--<td><?=$paciente->email?></td>-->
						<td><?=$paciente->telefono?></td>
						<td><? if($paciente->canal): echo $paciente->canal; else: echo "DIRECTO"; endif;?></td>
						<td><?=fechaLetraDos($paciente->fecha_registro)?></td>
						<td><?=ultimaCita($paciente->id_paciente)?></td>
					<? if($datos_plan==1): ?>
						<td><?=fechaLetra($paciente->fecha_final_plan)?></td>						
					<? endif;?>
						<td align="center">
							<? $atendido = mysql_num_rows(mysql_query("SELECT id_consulta FROM consultas WHERE id_paciente = '$paciente->id_paciente'"));
								if($atendido){ ?>
								<i class="fa fa-check-circle" aria-hidden="true" style="color:#00c901"></i>
							<?}else{ echo "-"; }?> / 
							<?if($paciente->encuestado){?>
								<i class="fa fa-check-circle" aria-hidden="true" style="color:#00aeff"></i>
							<?}else{ echo "-"; }?>
						</td>
						<td>
							<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load_<?=$paciente->id_paciente?>" width="19" class="oculto" />
							
							<? if($paciente->activo==1): ?>
							<div class="btn-group btn_<?=$paciente->id_paciente?>">
                                <a class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Opciones
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu">
	                                <li>
                                        <a href="?Modulo=Perfil&id=<?=$paciente->id_paciente?>">Historial de citas</a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#editaPaciente" data-id="<?=$paciente->id_paciente?>">Editar datos</a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" onclick="javascript:Desactiva(<?=$paciente->id_paciente?>)">Eliminar paciente</a>
                                    </li>
                                    <li class="divider"> </li>
                                    <li>
                                        <a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#nuevaCita" data-id-agenda="<?=$paciente->id_paciente?>">Agendar cita</a>
                                    </li>
									<? if($paciente->cliente): ?>
                                    <li class="divider"> </li>
                                    <li>
                                        <a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#datosPlan" data-id-paciente="<?=$paciente->id_paciente?>">Plan Corporativo</a>
                                    </li>
									<? endif; ?>
                                    <li class="divider"> </li>
                                    <? 	if($paciente->encuestado==0):	?>
										<li><a href="javascript:;" onclick="javascript:encuestar(<?=$paciente->id_paciente?>)">Encuesta</a></li>
									<?	endif; ?>
                                </ul>
                            </div>
                            <? else: ?>
								<a role="button" class="btn btn-xs btn-warning btn_<?=$paciente->id_paciente?>" onclick="javascript:Activa(<?=$paciente->id_paciente?>)">Activar</a>
							<? endif; ?>
						</td>
			        </tr>
			      <? endforeach; ?>
			    </tbody>
			</table>
			<? if((!$_REQUEST['id_paciente'])OR(!$_REQUEST['id_cliente'])): ?>
			<div class="table-pagination pull-right">
				<?php echo paginate($reload, $page, $total_pages, $adjacents);?>
			</div>
			<br><br>
			<? endif; ?>
			<?php
			
		} else {
			?>
			<div class="alert alert-warning alert-dismissable">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h4>Aviso:</h4> No hay datos para mostrar
            </div>
			<?php
		}
	}
?>
