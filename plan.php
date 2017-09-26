<?
if($_GET['id']):
	$id_plan=$_GET['id'];
	
	$sql="SELECT plan, observacion FROM planes WHERE id_plan=$id_plan AND activo = 1";
	$q=mysql_query($sql);
	$ft=mysql_fetch_assoc($q);
	
	$plan=$ft['plan'];
	$observacion = $ft['observacion'];
	
	function cantidad($id_plan,$id_tratamiento) {
		$sql="SELECT cantidad FROM planes_tratamientos WHERE id_plan=$id_plan AND id_tratamiento=$id_tratamiento ";
		$q=mysql_query($sql);
		$dt=mysql_fetch_assoc($q);
		return $dt['cantidad'];
	}
endif;
	
	
	
$sql="SELECT * FROM tratamientos WHERE activo = 1 ORDER BY tratamiento ASC";
$q=mysql_query($sql);
$tratamientos = array();
while($datos=mysql_fetch_object($q)):
	$tratamientos[] = $datos;
endwhile;

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
			
			<div class="portlet light  portlet-fit">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-briefcase font-dark"></i>
						<span class="caption-subject font-dark bold uppercase">Planes Corporativos</span>
					</div>
				</div>
				<div class="portlet-body">
					<form id="frm-datos" class="form-horizontal">
						<div class="alert alert-danger oculto" role="alert" id="msg_error"></div>
						<div class="form-body">
							
							<div class="form-group">
								<label for="nombre" class="col-md-2 control-label">Nombre del Plan</label>
								<div class="col-md-6">
									<input type="text" maxlength="128" class="form-control dat" name="nombre" id="nombre" autocomplete="off" value="<?=$plan?>">
								</div>
							</div>
							<div class="form-group">
								<label for="nombre" class="col-md-2 control-label">Observaciones</label>
								<div class="col-md-6">
									<textarea class="form-control dat" rows="5" name="observacion"><?=$observacion?></textarea>
<!--									<input type="text" maxlength="128" class="form-control dat" name="nombre" id="nombre" autocomplete="off" value="<?=$plan?>">-->
								</div>
							</div>
							<br><br>
							<h4>Tratamientos para el Plan (debe tener un costo de $0.00 para ser válido)</h4>
							
							<table class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th width="80">Cantidad</th>
										<th>Tratamiento</th>
										<th>Costo</th>
									</tr>
								</thead>
								<tbody>
									<? foreach($tratamientos as $tratamiento): 
											unset($readon);
											unset($color_in);
											if($tratamiento->costo>0):
												$nombre_trat = '<span style="color:#a90400">'.$tratamiento->costo.'</span>';
												$readon = 'disabled';
											else:
												$nombre_trat = '<span style="color:#129d02"><b>'.$tratamiento->costo.'</b></span>';
												$color_in = "background-color:#fffdc7";
											endif;

											
									?>
									<tr>
										<td><input type="text" maxlength="4" class="form-control numero" style="<?=$color_in?>" <?=$readon?> name="cantidad[<?=$tratamiento->id_tratamiento?>]" autocomplete="off" <? if($id_plan){ echo 'value="'.cantidad($id_plan,$tratamiento->id_tratamiento).'"'; }?>></td>
										<td style="padding-top: 15px;"><?=$tratamiento->tratamiento?></td>
										<td style="padding-top: 15px;"><?=$nombre_trat?></td>

									</tr>
									<? endforeach; ?>
								</tbody>
							</table>
							
	        			
						</div>
						
						<div class="form-actions text-right">
							<? if($id_plan): ?>
							<input type="hidden" name="id_plan" value="<?=$id_plan?>" />
							<? endif; ?>
							<a role="button" class="btn btn-default btn-outline " href="javascript:history.back(1)">Cancelar</a>&nbsp;&nbsp;
							<a role="button" class="btn green-jungle btn-outline " onclick="guardaPlan()">Guardar Plan</a>
						</div>
		        	</form>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
	</div>
</div>















<!--- Js -->
<script>
$(function(){
	
	$('form').submit(function(e){
		e.preventDefault();	
	});
});

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

function guardaPlan(){
	App.blockUI();
	var datos=$('#frm-datos').serialize();
	$.post('ac/plan.php',datos,function(data){
	    console.log(data);
	    if(data==1){
			window.open("?Modulo=Planes&msg=1", "_self");
	    }else{
	    	alert(data);
			App.unblockUI();
	    }
	});
}
</script>