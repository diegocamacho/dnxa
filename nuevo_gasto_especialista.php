<?
$sql="SELECT * FROM clinicas WHERE activo=1";
$ql=mysql_query($sql);
$clinicas = array();
while($datos=mysql_fetch_object($ql)):
	$clinicas[] = $datos;
endwhile;

//Tipo de gasto
$sql="SELECT * FROM books_tipos_gasto WHERE activo=1 AND eliminable=1 ORDER BY cuenta_gasto  ASC";
$ql=mysql_query($sql);
$tipo_gatos = array();
while($datos=mysql_fetch_object($ql)):
	$tipo_gatos[] = $datos;
endwhile;

//Metódo de pago
$sql="SELECT * FROM books_metodo_pago WHERE activo=1 ORDER BY metodo_pago  ASC";
$ql=mysql_query($sql);
$metodo_pago = array();
while($datos=mysql_fetch_object($ql)):
	$metodo_pago[] = $datos;
endwhile;
?>



<!-- Modal -->
<div class="modal fade" id="pagaEspecialista">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
				<h4 class="modal-title">Pago Especialista/Laboratorio</h4>
			</div>
			
			<div class="modal-body">
				<div class="alert alert-danger oculto" role="alert" id="msg_error"></div>
				<form id="frm_guarda" class="form-horizontal">
			
				<div class="form-group">
					<label for="direccion" class="col-md-3 control-label">Tipo de gasto</label>
					<div class="col-md-9">
						<select class="form-control" name="id_tipo_gasto" >
							<option value="0">Seleccione una</option>
							<? foreach($tipo_gatos as $tipo_gato): ?>
							<option <? if($tipo_gato->id_tipo_gasto==2){?>selected="1"<?}?> value="<?=$tipo_gato->id_tipo_gasto?>"><?=$tipo_gato->cuenta_gasto?></option>
							<? endforeach; ?>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<label for="direccion" class="col-md-3 control-label">Empresa</label>
					<div class="col-md-9">
						<select class="form-control" name="id_clinica" id="id_clinica" >
							<option value="0">Seleccione una</option>
							<? foreach($clinicas as $clinica): ?>
							<option value="<?=$clinica->id_clinica?>"><?=$clinica->clinica?></option>
							<? endforeach; ?>
						</select>
					</div>
				</div>
				
				
				<div id="show_form">
							
				</div>
				
				
				
				
				
				<hr>
				
				<div class="form-group">
					<label for="nombre" class="col-md-3 control-label">Descripción</label>
					<div class="col-md-9">
						<input type="text" maxlength="128" class="form-control dat" name="descripcion" id="descripcion" autocomplete="off">
					</div>
				</div>
				
				<div class="form-group">
					<label for="telefono" class="col-md-3 control-label">Monto</label>
					<div class="col-md-4">
						<input type="text" maxlength="16" class="form-control dat" name="monto" id="monto" autocomplete="off" value="0">
					</div>
					<div class="col-md-3">
						<div class="mt-checkbox-inline">
                            <label class="mt-checkbox mt-checkbox-outline" style="margin-bottom:0px;"> Líquidado
                                <input type="checkbox" value="1" name="liquidado" id="liquidado">
                                <span></span>
                            </label>
                        </div>
					</div>
				</div>
				
				<div class="form-group">
					<label for="telefono" class="col-md-3 control-label">Fecha</label>
					<div class="col-md-4">
						<input type="text" maxlength="128" class="form-control dat date-picker" name="fecha" autocomplete="off">
					</div>
				</div>
				
				<div class="form-group">
					<label for="direccion" class="col-md-3 control-label">Metódo de pago</label>
					<div class="col-md-9">
						<select class="form-control" name="id_metodo_pago" id="id_metodo_pago" >
							<option value="0">Seleccione uno</option>
							<? foreach($metodo_pago as $metodo): ?>
							<option value="<?=$metodo->id_metodo_pago?>"><?=$metodo->metodo_pago?></option>
							<? endforeach; ?>
						</select>
					</div>
				</div>
				
				<input type="hidden" name="monto_real" id="monto_real" />
				<input type="hidden" name="id_pago_especialista" id="id_pago_especialista" />
			
			</form>
		      
		</div>
			
			<div class="modal-footer">
				<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load" width="25" class="oculto" />
				<button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn blue-chambray btn_ac" onclick="guardaPago()">Guardar Pago</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->





<script>
$(function(){
	//Para gastos
	$('#id_clinica').change(function(){
		var id_clinica = $('#id_clinica').val();
		$.ajax({
			url: "data/books_select_nuevo_gasto.php",
	   		data: 'id_clinica='+id_clinica,
	   		success: function(data){
		   		console.log(data);
		   		$('#show_form').html(data);
		   		$('#show_form').show();
	   	},
	   	cache: false
	   	});
		
	});
	
	$(document).on('click', '[data-monto]', function () {
	    var monto = $(this).attr('data-monto');
	    var id_pago_especialista = $(this).attr('data-id-pago-especialista');
	    var descripcion = $(this).attr('data-desc');
		$('#id_pago_especialista').val(id_pago_especialista);
		$('#descripcion').val(descripcion);
	    $('#monto').val(monto);
	    $('#monto_real').val(monto);
		//alert(monto);
	});
	
});	
	

function guardaPago(){
	$('#msg_error').hide('Fast');
	$('.btn_ac').hide();
	$('#load').show();
	var monto_real = $('#monto_real').val();
	var monto = $('#monto').val();
	/*
	if(monto>monto_real){
		$('#load').hide();
		$('.btn_ac').show();
		$('#msg_error').html("El monto a pagar no puede ser mayor al monto adeudado.");
		$('#msg_error').show('Fast');
		return false;
	}
	*/
	var datos=$('#frm_guarda').serialize();
	$.post('ac/nueva_compra.php',datos,function(data){		
		console.log(data);
		var datos = data.split('|');
		
	    if(datos[0]==1){
		    
			if(datos[1]){
				$.post('http://localhost/imprimir_remoto.php','imprimir='+datos[1]);
				setTimeout(function() {
				
			 window.open("?Modulo=Operaciones&msg=1", "_self");
				
				}, 1000);
			}
			
	    }else{
	    	$('#load').hide();
			$('.btn_ac').show();
			$('#msg_error').html(data);
			$('#msg_error').show('Fast');
	    }
	});
}

</script>