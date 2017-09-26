<?
	
$id_clinica = $_GET['id'];	
$sql="SELECT * FROM clinicas_horarios WHERE id_clinica = '$id_clinica'";
$q=mysql_query($sql);


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
				  		<p>La clínica se ha agregado</p>
				  	</div>
			  <? }if($_GET['msg']==2){ ?>
			  		<br>
			  		<div class="alert alert-dismissable alert-info">
				  		<button type="button" class="close" data-dismiss="alert">×</button>
				  		<p>El horario de la clínica se ha editado</p>
				  	</div>
			  <? } ?>
			  <!-- Contenido -->
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet light  portlet-fit">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-clock font-dark"></i>
						<span class="caption-subject font-dark bold uppercase">Horarios - <?=dameClinica($id_clinica)?></span>
					</div>
					<div class="actions btn-set">
						<!--<a href="javascript:;" class="btn btn-sm blue " data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#NuevaClinica"><i class="fa fa-plus"></i> Agregar empresa </a>-->
						<a href="?Modulo=Clinicas" class="btn btn-sm purple "><i class="fa fa-chevron-left"></i> Regresar </a>
					</div>
				</div>
				<div class="portlet-body">
					<table class="table table-striped table-bordered table-hover">
						<thead>
					        <tr>
								<th>Dia</th>
								<th>Hora Primera Cita</th>
								<th>Hora Última Cita</th>
								<th width="150"></th>
					        </tr>
						</thead>
						<tbody>
					      <? while($horario = mysql_fetch_assoc($q)){?>
					        <tr>
								<td class="text-capitalize"><?=DiaSemana($horario['dia'])?></td>
								<td><?if($horario['hora_ini']){?><?=horaInput($horario['hora_ini'])?><?}?></td>
								<td><?if($horario['hora_fin']){?><?=horaInput($horario['hora_fin'])?><?}?></td>
								<td align="right">
									<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load_<?=$horario['id_horario']?>" width="19" class="oculto" />
										<a role="button" class="btn blue btn-xs btn_<?=$horario['id_horario']?>" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#EditaClinica" data-id="<?=$horario['id_horario']?>">Editar</a>
								</td>
					        </tr>
					      <? } ?>
					      </tbody>
					</table>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
	</div>
</div>



<!-- Modal -->
<div class="modal fade" id="EditaClinica">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Edita Horario Citas Web</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="msg_error2"></div>
<!-- Loader -->
		<div class="row oculto" id="load_big">
			<div class="col-md-12 text-center" >
				<img src="assets/global/img/ajax-loading.gif" border="0"  />
			</div>
		</div>
<!--Formulario -->
		<form id="frm_edita" class="form-horizontal">
			
					<div class="form-group">
                        <label class="control-label col-md-3">Hora Inicio</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" class="form-control timepicker timepicker-24" name="hora1" id="hora1" data-minute-step="60">
                                <span class="input-group-btn">
                                    <button class="btn default" type="button">
                                        <i class="fa fa-clock-o"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-md-3">Hora Final</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" class="form-control timepicker timepicker-24" name="hora2" id="hora2" data-minute-step="60">
                                <span class="input-group-btn">
                                    <button class="btn default" type="button">
                                        <i class="fa fa-clock-o"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
			
			<input type="hidden" name="id_horario" id="id_horario" />
		</form>
		      
      </div>
      <div class="modal-footer">      	
      	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load2" width="25" class="oculto" />
        <button type="button" class="btn btn-default btn_ac btn-modal" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success btn_ac btn-modal" onclick="EditaClinica()">Actualizar Horario</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<!--- Js -->
<script>
$(function(){
	
	

	$(document).on('click', '[data-id]', function () {
		$('.edit').val("");
		$('.btn-modal').hide();
		$('#frm_edita').hide();
		$('#load_big').show();
	    var data_id = $(this).attr('data-id');
	    $.ajax({
	   	url: "data/horarios.php",
	   	dataType: "json",
	   	data: 'id_horario='+data_id,
	   	success: function(data){
		   	
	   		$('#id_horario').val(data_id);
	   		console.log(data);
			$('#hora1').val(data.hora_ini);
			$('#hora2').val(data.hora_fin);
	   		
	   		$('#load_big').hide();
	   		$('#frm_edita').show();
	   		$('.btn-modal').show();
	  	
	   	},
	   	cache: false
	   });
	});
	
	$('#EditaClinica').on('hidden.bs.modal',function(e){
		$('.edit').val("");
		$('#msg_error2').hide();
		$('#msg_error').hide();
	});
	
	$('form').submit(function(e){
		e.preventDefault();	
	});
	
	
});


function EditaClinica(){
	$('#msg_error2').hide('Fast');
	$('.btn_ac').hide();
	$('#load2').show();
	var datos=$('#frm_edita').serialize();
	$.post('ac/edita_horario.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=HorariosClinica&msg=2&id=<?=$id_clinica?>", "_self");
	    }else{
	    	$('#load2').hide();
			$('.btn').show();
			$('#msg_error2').html(data);
			$('#msg_error2').show('Fast');
	    }
	});
}
</script>