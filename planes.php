<?
$sql="SELECT * FROM planes ORDER BY plan ASC";
$q=mysql_query($sql);

$planes = array();

while($datos=mysql_fetch_object($q)):
	$planes[] = $datos;
endwhile;
$val=count($planes);

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
				  		<p>El plan se ha agregado/editado</p>
				  	</div>
			  <? }if($_GET['msg']==2){ ?>
			  		<br>
			  		<div class="alert alert-dismissable alert-info">
				  		<button type="button" class="close" data-dismiss="alert">×</button>
				  		<p>El plan se ha editado</p>
				  	</div>
			  <? } ?>
			  <!-- Contenido -->
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet light  portlet-fit">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-briefcase font-dark"></i>
						<span class="caption-subject font-dark bold uppercase">Planes Corporativos</span>
					</div>
					<div class="actions btn-set">
						<a href="?Modulo=Plan" class="btn btn-sm blue "><i class="fa fa-plus"></i> Agregar Plan </a>
					</div>
				</div>
				<div class="portlet-body">
					<? if($val>0): ?>
					<table class="table table-striped table-bordered table-hover">
						<thead>
					        <tr>
								<th width="70">ID Plan</th>
								<th width="250">Plan</th>
								<th>Productos</th>
								<th>Observaciones</th>
								<th width="150"></th>
					        </tr>
					      </thead>
					      <tbody>
					      <? foreach($planes as $plan): 
						      $id_plan=$plan->id_plan;
						      $sq="SELECT tratamiento,cantidad FROM planes_tratamientos 
						      JOIN tratamientos ON tratamientos.id_tratamiento=planes_tratamientos.id_tratamiento WHERE id_plan=$id_plan";
						      $q=mysql_query($sq);
						      while($datos=mysql_fetch_object($q)):
							  	$tratamientos[] = $datos;
							  endwhile;
							  $val2=count($tratamientos);
					      ?>
					        <tr>
								<td><?=$plan->id_plan?></td>
								<td><?=$plan->plan?></td>
								<td>
									<? if($val2): 
										foreach($tratamientos as $tratamiento):
											echo "[".$tratamiento->cantidad."] ".$tratamiento->tratamiento."<br>";
										endforeach;
									else: ?>
										Aún no se han agregado productos
									<? 	endif; ?>
								</td>
								<td><?=str_replace("\n", "<br>",$plan->observacion)?></td>
								<td align="right">
					          		<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load_<?=$id_plan?>" width="19" class="oculto" />
					          	<? if($plan->activo==1): ?>
					          		<a role="button" href="?Modulo=Plan&id=<?=$id_plan?>" class="btn green btn-xs btn_<?=$id_plan?>" >Editar</a>
					          		<a role="button" class="btn red btn-xs btn_<?=$id_plan?>" onclick="javascript:Desactiva(<?=$id_plan?>)">Desactivar</a>
					          	<? else: ?>
					          		<a role="button" class="btn btn-warning btn-xs btn_<?=$id_plan?>" onclick="javascript:Activa(<?=$id_plan?>)">Activar</a>
					          	<? endif; ?>
								</td>
							</tr>
					      <? 	unset($tratamientos);
						      endforeach; ?>
					      </tbody>
					</table>
					<? else: ?>
					<div class="alert alert-dismissable alert-warning">
				  		<button type="button" class="close" data-dismiss="alert">×</button>
				  		<p>Aún no se han creado planes</p>
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

function EditaCanal(){
	$('#msg_error2').hide('Fast');
	$('.btn_ac').hide();
	$('#load2').show();
	var datos=$('#frm_edita').serialize();
	$.post('ac/edita_canal.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Canales&msg=2", "_self");
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
	$.post('ac/activa_desactiva_plan.php', { tipo:"0", id_plan:id },function(data){
		if(data==1){
			window.open("?Modulo=Planes", "_self");
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
	$.post('ac/activa_desactiva_plan.php', { tipo:"1", id_plan:id },function(data){
		if(data==1){
			window.open("?Modulo=Planes", "_self");
		}else{
			$("#load_"+id+"").hide();
			$(".btn_"+id+"").show();
			alert(data);
		}
	});
}
function NuevoCanal(){
	$('#msg_error').hide('Fast');
	$('.btn_ac').hide();
	$('#load').show();
	var datos=$('#frm_guarda').serialize();
	$.post('ac/nuevo_canal.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Canales&msg=1", "_self");
	    }else{
	    	$('#load').hide();
			$('.btn').show();
			$('#msg_error').html(data);
			$('#msg_error').show('Fast');
	    }
	});
}
</script>