<?
	$sql_estados_pais="SELECT * FROM estados_pais ORDER BY estado ASC";
	$q_estados_pais=mysql_query($sql_estados_pais);

	extract($_GET);
	// if(!$id) exit("No llego identificador del proyecto.");
	
	if($id){
		$sql="SELECT venta_detalle.*, productos.nombre FROM venta_detalle
		JOIN productos ON productos.id_producto=venta_detalle.id_producto
		WHERE id_venta='$id'";
		$q=mysql_query($sql);
		$valida=mysql_num_rows($q);
		
		//Venta
	
		$sq="SELECT * FROM ventas WHERE id_venta=$id";
		$qu=mysql_query($sq);
		$datos=mysql_fetch_assoc($qu);
		$id_metodo_pago=$datos['id_metodo_pago'];
	}
	$q_factura = mysql_query("SELECT id_empresa,rfc,razon_social FROM config_facturacion");
	$q_vendedor = mysql_query("SELECT id_usuario,nombre FROM usuarios WHERE activo=1");
?>
<div class="row">
	<div class="col-md-12">
	<div class="portlet light  portlet-fit">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-layers font-dark"></i>
						<span class="caption-subject font-dark bold uppercase">Nueva Factura</span>
					</div>
				</div>
				<div class="portlet-body">
					<div class="panel panel-primary">
						<div class="panel-heading">
					    	<h3 class="panel-title">Datos del receptor</h3>
					  	</div>
						<div class="panel-body">
							<form id="form_datos" class="form-horizontal">
								<div class="alert alert-warning oculto" style="font-size: 16px;" role="alert" id="msg_error"></div>
								<div class="alert alert-success oculto" style="font-size: 16px;" role="alert" id="msg_ok"></div>
								
								<div id="step1">
				  					
				  					<div class="form-group">
										<label for="rfc" class="col-sm-2 control-label">RFC:</label>
										<div class="col-sm-9">
											<div class="input-group">
												<!-- <input type="hidden" name="clienteID" id="clienteID" value=""> -->
												<input type="hidden" name="proyectoID" id="proyectoID" value="<?=$id?>">
												<input type="text" class="form-control" id="rfc" name="rfc" autocomplete="off" maxlength="13">
												<span class="input-group-btn">
													<button class="btn btn-primary" type="button" onclick="javascript:consulta();" id="btn-consulta" data-loading-text="Buscando...">Buscar</button>
												</span>
											</div>
				    					</div>
				  					</div>
								</div>
								<div id="step2" class="oculto">	
									<div class="alert alert-warning oculto" style="font-size: 16px;" role="alert" id="msg_error"></div>
									<div class="form-group">
										<label for="razon_social" class="col-sm-2 control-label">Razón Social:</label>
										<div class="col-sm-9">
											<input type="text" class="form-control limpia" id="razon_social" name="razon_social" autocomplete="off">
				    					</div>
				  					</div>
			
				  					<div class="form-group">
										<label for="direccion" class="col-sm-2 control-label">Calle:</label>
										<div class="col-sm-9">
											<input type="text" class="form-control limpia" id="calle" name="calle" autocomplete="off">
				    					</div>
				  					</div>
			
				  					<div class="form-group">
										<label for="n_exterior" class="col-sm-2 control-label">Número Exterior:</label>
										<div class="col-sm-4">
											<input type="text" class="form-control limpia" id="n_exterior" name="n_exterior" autocomplete="off">
				    					</div>
				    					
				    					<label for="n_interior" class="col-sm-2 control-label">Número Interior:</label>
										<div class="col-sm-3">
											<input type="text" class="form-control limpia" id="n_interior" name="n_interior" placeholder="Opcional" autocomplete="off">
				    					</div>
				  					</div>
				  					
				  					<div class="form-group">
										<label for="colonia" class="col-sm-2 control-label">Colonia:</label>
										<div class="col-sm-4">
											<input type="text" class="form-control limpia" id="colonia" name="colonia" autocomplete="off">
				    					</div>
				    					
				    					<label for="cp" class="col-sm-2 control-label">Código Postal:</label>
										<div class="col-sm-3">
											<input type="text" class="form-control limpia" id="cp" name="cp" autocomplete="off" maxlength="6">
				    					</div>
				  					</div>
			
				  					<hr><br>
			
									<div class="form-group">
										<label for="estado" class="col-sm-2 control-label">Estado:</label>
										<div class="col-sm-4">
											<select class="form-control" name="estado" id="estado">
													<option value="">Seleccione Estado</option>
												<? while($ft_estados_pais=mysql_fetch_assoc($q_estados_pais)){ ?>
													<option value="<?=$ft_estados_pais['estado']?>"><?=$ft_estados_pais['estado']?></option>
												<? } ?>
											</select>
				    					</div>
				  					</div>
			
				  					<div class="form-group">
										<label for="municipio" class="col-sm-2 control-label">Mpio o Delegación:</label>
										<div class="col-sm-4">
											<input type="text" class="form-control limpia" id="municipio" name="municipio" autocomplete="off">
				    					</div>
			
										<label for="ciudad" class="col-sm-2 control-label">Ciudad:</label>
										<div class="col-sm-3">
											<input type="text" class="form-control limpia" id="ciudad" name="ciudad" autocomplete="off">
				    					</div>
				  					</div>
			
				  					<hr><br>
			
				  					<!--<div class="form-group">
										<label for="email" class="col-sm-2 control-label">Email:</label>
										<div class="col-sm-4">
											<input type="email" class="form-control limpia" id="email" name="email" autocomplete="off">
				    					</div>
				    				</div>-->
			
				  					<div class="form-group">
										<label for="telefono" class="col-sm-2 control-label">Teléfono:</label>
										<div class="col-sm-4">
											<input type="text" class="form-control limpia" id="telefono" name="telefono" autocomplete="off">
				    					</div>
				    					
				    					<label for="celular" class="col-sm-2 control-label">Celular:</label>
										<div class="col-sm-3">
											<input type="text" class="form-control limpia" id="celular" name="celular" placeholder="" autocomplete="off">
				    					</div>
				  					</div>
				  					<br>
								</div><!-- end step2-->
							</form>
							
						</div>
					</div>
					<div class="row oculto" id="step3">
						<div class="col-md-12">
							<div class="panel panel-primary">
								<div class="panel-heading">
							    	<h3 class="panel-title">Conceptos en Factura <?if ($id){?> | Venta #<?echo str_pad($id,5,"0",STR_PAD_LEFT);}?></h3>
							  	</div>
								<div class="panel-body">
									<form id="form_datos2" class="form-horizontal">
									<div class="alert alert-warning oculto" style="font-size: 16px;" role="alert" id="msg_error2"></div>
									<div id="step3">
										<div id="agrega">
										<!-- aqui el while -->
										<?
										while ($ft=@mysql_fetch_assoc($q)) {
										?>
											<div id="externalRow_<?=$ft['id_detalle']?>" class="cuenta_productos" numero-fila="<?=$ft['id_detalle']?>">
												<div class="row">
													
													<div class="col-xs-1">
														<a href="javascript:void(0);" style="margin-top: 30px;" class="btn btn-sm btn-danger" role="button" id="" onclick="eliminar(<?=$ft['id_detalle']?>)">x</a>
													</div>
													
													<div class="col-xs-2">
														<label>Cantidad</label>
														<input type="text" class="form-control just_numbers" name="cantidad[]" onkeyup="javascript:cambioImportes(<?=$ft['id_detalle']?>)" id="cantidad_<?=$ft['id_detalle']?>" id_fila="<?=$ft['id_detalle']?>" maxlength="6" autocomplete="off" value="<?=$ft['cantidad']?>">
													</div>
					
													<div class="col-xs-2">
														<input type="hidden" name="clave[]" value="NA"/>
														<label>Unidad</label>
														<select name="unidad[]" id="unidad_<?=$ft['id_detalle']?>" class="form-control">
															<option value="PIEZA" selected="selected">Pieza</option>
															<option value="SERVICIO">Servicio</option>
															<option value="METRO">Metro</option>
															<option value="LITRO">Litro</option>
															<option value="GALON">Galon</option>
															<option value="KILO">Kilo</option>
															<option value="CAJA">Caja</option>
															<option value="PIEZA">Pieza</option>
															<option value="PAQUETE">Paquete</option>
															<option value="PAR">PAR</option>
															<option value="CONJUNTO">Conjunto</option>										
															<option value="NA">NA</option>
														</select>
													</div>
					
													<div class="col-xs-3">
														<label>Descripción</label>
														<!-- <input type="text" class="form-control" name="descripcion" id="descripcion"  autocomplete="off" value="<?=$ft['descripcion']?>"> -->
														<textarea class="form-control" rows="4" name="descripcion[]" id="descripcion_<?=$ft['id_detalle']?>" autocomplete="off"><?=$ft['nombre']?></textarea>
													</div>
													
													<div class="col-xs-2">
														<label>P.U.</label>
														<?$precio_sin_iva = round($ft['precio_venta']/1.16,2);///ESTO LE QUITA EL IVA AL PRECIO DE VENTA?>
														<!-- <input type="text" class="form-control suma_cantidad" name="cantidad[]" id="cantidad_<?=$ft['id_detalle']?>" id_fila="<?=$ft['id_detalle']?>" autocomplete="off" value="<?=number_format($ft['cantidad'],0)?>"> -->
														<input type="text" class="form-control just_numbers" name="precio[]" onkeyup="javascript:cambioImportes(<?=$ft['id_detalle']?>)" id="precio_<?=$ft['id_detalle']?>" id_fila="<?=$ft['id_detalle']?>" autocomplete="off" value="<?=$precio_sin_iva?>">
													</div>
					
													<?
													$importe_producto=$ft['cantidad']*$precio_sin_iva;//$ft['precio_venta'];
													$totales+=$importe_producto;
													?>
													<div class="col-xs-2">
														<label>Importe</label>
														<input type="text" class="form-control total2 suma_importe"  name="importe[]" id="importe_<?=$ft['id_detalle']?>" maxlength="10" style="text-align:right;" value="<?=number_format($importe_producto,2,".","")?>">
													</div>
												</div><br><hr>
											</div>
										<?
										}
										?>
										</div>
										<!-- aqui el while -->
										<button type="button" id="agregar" class="btn btn-primary btn-sm">+ Agregar Concepto</button>
										<br><br><br>
										<div class="form-group">
											<label for="observacion" class="col-sm-2 control-label">Facturar Con:</label>
											<div class="col-sm-6">
												<select name="id_empresa" id="id_empresa" class="form-control">
													<?while ($ft = mysql_fetch_assoc($q_factura)) {	
														?>
														<option value="<?=$ft['id_empresa']?>"><?=$ft['razon_social']?></option>
													<? 
													} 
													?>
												</select>
					    					</div> 
					
					    					<label for="iva" class="col-sm-2 control-label text-right">IVA:</label>
											<div class="col-sm-2">
												<?
												$iva_totales=$totales*0.16;
												$iva_totales=number_format($iva_totales,2,".","");
												$totales_con_iva=$totales+$iva_totales;
												$totales_con_iva=number_format($totales_con_iva,2,".","");
												?>
												<input type="text" class="form-control limpia total2" id="iva" name="iva"  style="text-align:right;" maxlength="10" value="<?=$iva_totales?>">
					    					</div> 					    					
					  					</div>
					  					<div class="form-group">
											<label for="tipo_comprobante" class="col-sm-2 control-label">Tipo Comprabante:</label>
											<div class="col-sm-6">
												<select name="tipo_comprobante" id="tipo_comprobante" class="form-control">
													<option value="ingreso">INGRESO</option>
													<option value="egreso">EGRESO</option>
												</select>
					    					</div>
					    					
<!-- ISR % -->
											<label for="iva" class="col-sm-2 control-label text-right">ISR (Retención):</label>
											<div class="col-sm-2">
												<input type="text" class="form-control limpia total2 just_numbers" id="isr_retenidoA" name="isr_retenidoA" onkeyup="calculaISR();"  style="text-align:right;" maxlength="10">
					    					</div>
					  					</div>
										<div class="form-group">
											<label for="metodo_pago" class="col-sm-2 control-label">Método de pago:</label>
											<div class="col-sm-4">
												<!--<input type="text" name="metodo_pago" id="metodo_pago" class="form-control">-->
												<select name="metodo_pago" id="metodo_pago" class="form-control">
													<?
													$sql_metodosPago = "SELECT * FROM metodo_pago";	
													$q_metodosPago = mysql_query($sql_metodosPago);
													while ($ft = mysql_fetch_assoc($q_metodosPago)) {
													?>
														<option value="<?=$ft['id_metodo_pago']?>" <? if($id_metodo_pago==$ft['id_metodo_pago']){ ?>selected="1"<? } ?>><?=$ft['metodo_pago']?></option>
													<? 
													} 
													?>
												</select>
					    					</div>
					    					<div class="col-sm-2">
						    					<input type="text" class="form-control limpia oculto just_numbers" name="digitos" id="digitos" placeholder="# Cuenta (4 Digitos)" autocomplete="off" maxlength="4">
					    					</div>
					    					
<!-- ISR $ -
											<label for="iva" class="col-sm-2 control-label text-right">ISR (Retención):</label>
											<div class="col-sm-2">
												<input type="text" class="form-control limpia total2 just_numbers" id="isr" name="isr"  style="text-align:right;" maxlength="10">
					    					</div>		-->			    					
					  					</div>
					  					
					  					<div class="form-group">
											<label for="observacion" class="col-sm-2 control-label">Observación:</label>
											<div class="col-sm-6">
												<textarea class="form-control" rows="3" name="observacion" id="observacion" autocomplete="off" placeholder="Observaciones"><?=$ft['observacion']?></textarea>
					    					</div>
					    					
					    					<label for="total" class="col-sm-2 control-label text-right">Total:</label>
											<div class="col-sm-2">
												<input type="text" class="form-control limpia total2" id="total" name="total"  style="text-align:right;" maxlength="10" value="<?=$totales_con_iva?>">
												<input type="hidden" id="total_actualizado">
					    					</div>    					
					    					
					  					</div>
					  					<div class="form-group">
											<label for="condicionesPago" class="col-sm-2 control-label">Condiciones de Pago:</label>
											<div class="col-sm-6">
												<textarea class="form-control" rows="2" name="condicionesPago" id="condicionesPago" autocomplete="off" placeholder="Condiciones del Pago">CONTADO</textarea>
					    					</div>
					    					<!--<label for="estado" class="col-sm-2 control-label text-right">Estado:</label>
											<div class="col-sm-2">
												<select class="form-control" name="estado_pago">
													<?
													if($id){
														$selx = 'selected="selected"';
													}	
													?>
													<option value="0">No Pagada</option>
													<option <?=$selx?> value="1">Pagada</option>
												</select>
					    					</div>-->
					  					</div>
					  					<div class="form-group">
											<label for="formaPago" class="col-sm-2 control-label">Forma de Pago:</label>
											<div class="col-sm-6">
												<textarea class="form-control" rows="2" name="formaPago" id="formaPago" autocomplete="off" placeholder="Forma del Pago">PAGO EN UNA SOLA EXHIBICION</textarea>
					    					</div>
					  					</div>
									</div><!-- end step3-->
									</form>
								</div>
								<div class="panel-footer text-right">
									<button type="button" class="btn btn-primary" onclick="javascript:factura();" id="btn-factura" data-loading-text="Facturando..." >Generar Factura</button>
								</div>
							</div>
				</div>
	</div>
	
		
	</div>
</div>



	</div>
</div>
<script src="js/modalplug.js"></script>
<script>
$(function(){
	var datosURL = location.search;
	if (datosURL=="?Modulo=NuevaFactura") {
		agregarProducto();
	}
	$('#rfc').focus();
	$('#rfc').keyup(function(e){
		if(e.keyCode==13){
			consulta();
		}
	});

	$('#metodo_pago').change(function() {
		var metodo_pago_usado = $(this).val();
		if (metodo_pago_usado=="01" || metodo_pago_usado=="98") {
			$('#digitos').hide('fast');
			$('#digitos').val('');

		}else{
			$('#digitos').val('');
			$('#digitos').show('fast');
			$('#digitos').focus();			
		}
	});
	
	     
	$('.just_numbers').keyup(function () { 
	    var val = $(this).val();
	    if(isNaN(val)){
	         val = val.replace(/[^0-9\.]/g,'');
	         if(val.split('.').length>2) 
	             val =val.replace(/\.+$/,"");
	    }
	    $(this).val(val); 	    
	});

	$('#agregar').click(function() {
		var num = Math.random();
		num = num * 10000;
		var entero = num.toFixed();
		var cont= '';
		cont+='<div id="externalRow_'+entero+'" class="cuenta_productos" numero-fila="'+entero+'">';
		cont+='<div class="row">';
		cont+='<div class="col-xs-1">';
		cont+='<a href="javascript:void(0);" style="margin-top: 30px;" class="btn btn-sm btn-danger" role="button" id="" onclick="eliminar('+entero+')">x</a>';
		cont+='</div>';
		cont+='<div class="col-xs-1">';
		cont+='<label>Cantidad</label>';
		cont+='<input type="text" class="form-control" name="cantidad[]" onkeyup="javascript:cambioImportes('+entero+')" id="cantidad_'+entero+'" id_fila="'+entero+'" maxlength="6" autocomplete="off" value="1">';
		cont+='</div>';
		cont+='<div class="col-xs-2">';
		cont+='<label>Unidad</label>';
		cont+='<input type="hidden" name="clave[]" value="NA"/>';
		cont+='<select name="unidad[]" id="unidad_'+entero+'" class="form-control">';
		cont+='<option value="PIEZA" selected="selected">Pieza</option>';
		cont+='<option value="SERVICIO">Servicio</option>';
		cont+='<option value="METRO">Metro</option>';
		cont+='<option value="LITRO">Litro</option>';
		cont+='<option value="GALON">Galon</option>';
		cont+='<option value="KILO">Kilo</option>';
		cont+='<option value="PAQUETE">Paquete</option>';
		cont+='<option value="CAJA">Caja</option>';
		cont+='<option value="PAR">Par</option>';
		cont+='<option value="CONJUNTO">Conjunto</option>';
		cont+='<option value="NA">NA</option>';
		cont+='</select>';
		cont+='</div>';
		cont+='<div class="col-xs-3">';
		cont+='<label>Descripción</label>';
		cont+='<textarea class="form-control" rows="4" name="descripcion[]" id="descripcion_'+entero+'" autocomplete="off"></textarea>';
		cont+='</div>';
		cont+='<div class="col-xs-2">';
		cont+='<label>P.U.</label>';
		cont+='<input type="text" class="form-control just_numbers" name="precio[]" onkeyup="javascript:cambioImportes('+entero+')" id="precio_'+entero+'" id_fila="'+entero+'" autocomplete="off" value="0.0">';
		cont+='</div>';
		cont+='<div class="col-xs-1">';
		cont+='<label>I.V.A.</label>';
		cont+='<select id="iva_'+entero+'" class="form-control iva" onchange="javascript:cambioImpuestos('+entero+')">';
		cont+='<option value="1" selected="selected">16%</option>';
		cont+='<option value="0">0%</option>';
		cont+='</select>';
		cont+='</div>';
		cont+='<div class="col-xs-2">';
		cont+='<label>Importe</label>';
		cont+='<input type="text" class="form-control total2 suma_importe" name="importe[]" id="importe_'+entero+'" maxlength="10" style="text-align:right;" value="0.0">';
		cont+='</div>';
		cont+='</div><br><hr>';
		cont+='</div>';
		$('#agrega').append(cont);
	});
});

function cambioImportes(id_fila){
	var costo_unitario = parseFloat($('#precio_'+id_fila).val());
	var cantidad = parseFloat($('#cantidad_'+id_fila).val());
	if(!cantidad){ cantidad = 0; }
	if(!costo_unitario){ costo_unitario = 0; }
	
	var nuevo_importe = cantidad * costo_unitario;
	nuevo_importe = parseFloat(nuevo_importe);
	$('#importe_'+id_fila).val(nuevo_importe.toFixed(2));

	var total_importes = 0.0;
    $('.suma_importe').each(function(){
    	este_importe = parseFloat($(this).val());
    	total_importes = total_importes + este_importe;
	});
	//CHECK DE IVA
	var check_iva = $('#iva_'+id_fila+'').val();
	if(check_iva == 1){
		var iva = total_importes*0.16;
		iva = parseFloat(iva);
		total_con_iva = total_importes+iva;
		total_con_iva = parseFloat(total_con_iva);
	    $('#iva').val(iva.toFixed(2));
	    $('#total').val(total_con_iva.toFixed(2));
	    $('#total_actualizado').val(total_con_iva.toFixed(2));
	}else{
		var iva =  $('#iva').val();
		iva = parseFloat(iva);
		total_importes = total_importes + iva;
		$('#total').val(total_importes.toFixed(2));
		$('#total_actualizado').val(total_importes.toFixed(2));
	}
	
}

function cambioImpuestos(num){
		var iva = $('#iva_'+num+'').val();
		//alert(num);
		if (iva=="0") {
			var iva_actual = Number($('#iva').val());
			var cant_actual = Number($('#cantidad_'+num+'').val());
			var precio_actual = Number($('#precio_'+num+'').val());
			var tot_actual = Number($('#total').val());
			precio_actual = cant_actual * precio_actual;
			iva = precio_actual * .16;
			iva_actual = iva_actual - iva;
			iva_actual = iva_actual.toFixed(2);
			$('#iva').val(iva_actual);
			tot_actual = tot_actual - iva;
			tot_actual = tot_actual.toFixed(2);
			$('#total').val(tot_actual);
			$('#total_actualizado').val(tot_actual);
		}else{
			var iva_actual = Number($('#iva').val());
			var cant_actual = Number($('#cantidad_'+num+'').val());
			var precio_actual = Number($('#precio_'+num+'').val());
			var tot_actual = Number($('#total').val());
			precio_actual = cant_actual * precio_actual;
			iva = precio_actual * .16;
			iva_actual = iva_actual + iva;
			iva_actual = iva_actual.toFixed(2);
			$('#iva').val(iva_actual);
			tot_actual = tot_actual + iva;
			tot_actual = tot_actual.toFixed(2);
			$('#total').val(tot_actual);
			$('#total_actualizado').val(tot_actual);	
		}
	}
	
function calculaISR(){
		var isr = Number($('#isr_retenidoA').val());
		var total_actualizado = Number($('#total_actualizado').val());
		if (isr=="0") {
			$('#total').val(total_actualizado.toFixed(2));
		}else{
			tot_actual = total_actualizado - isr;
			tot_actual = tot_actual.toFixed(2);
			$('#total').val(tot_actual);	
		}
	}

function eliminar(id_fila){
	var totaleliminado = $('#importe_'+id_fila).val();
	var total = $('#total').val();
	//CHECK DE IVA
	var check_iva = $('#iva_'+id_fila+'').val();
	if(check_iva == 0){
		var nuevototal = Number(total) - Number(totaleliminado);
		var nuevototal_conIVA = Number(nuevototal);
		$('#total').val(nuevototal_conIVA.toFixed(2));
	}else{
		//le quitamos el IVA
		total = total / 1.16; 
		var nuevototal = Number(total) - Number(totaleliminado);
		var iva = nuevototal * 0.16;
		//le agregamos el nuevo iva
		var nuevototal_conIVA = Number(nuevototal) + Number(iva);
		$('#iva').val(iva.toFixed(2));
		$('#total').val(nuevototal_conIVA.toFixed(2));
	}
	
	
	$('#externalRow_'+id_fila).fadeOut();
	setTimeout(function(){
	  $('#externalRow_'+id_fila).remove();
	}, 1000);
}

function agregarProducto(){
	var num = Math.random();
	num = num * 10000;
	var entero = num.toFixed();
	var cont= '';
	cont+='<div id="externalRow_'+entero+'" class="cuenta_productos" numero-fila="'+entero+'">';
	cont+='<div class="row">';
	cont+='<div class="col-xs-1">';
	cont+='<a href="javascript:void(0);" style="margin-top: 30px;" class="btn btn-sm btn-danger" role="button" id="" onclick="eliminar('+entero+')">x</a>';
	cont+='</div>';
	cont+='<div class="col-xs-1">';
	cont+='<label>Cantidad</label>';
	cont+='<input type="text" class="form-control just_numbers" name="cantidad[]" onkeyup="javascript:cambioImportes('+entero+')" id="cantidad_'+entero+'" id_fila="'+entero+'" maxlength="6" autocomplete="off" value="1">';
	cont+='</div>';
	cont+='<div class="col-xs-2">';
	cont+='<label>Unidad</label>';
	cont+='<input type="hidden" name="clave[]" value="NA"/>';
	cont+='<select name="unidad[]" id="unidad_'+entero+'" class="form-control">';
	cont+='<option value="PIEZA" selected="selected">Pieza</option>';
	cont+='<option value="SERVICIO">Servicio</option>';
	cont+='<option value="METRO">Metro</option>';
	cont+='<option value="LITRO">Litro</option>';
	cont+='<option value="GALON">Galon</option>';
	cont+='<option value="KILO">Kilo</option>';
	cont+='<option value="PAQUETE">Paquete</option>';
	cont+='<option value="CAJA">Caja</option>';
	cont+='<option value="PAR">Par</option>';
	cont+='<option value="CONJUNTO">Conjunto</option>';
	cont+='<option value="NA">NA</option>';
	cont+='</select>';
	cont+='</div>';
	cont+='<div class="col-xs-3">';
	cont+='<label>Descripción</label>';
	cont+='<textarea class="form-control" rows="4" name="descripcion[]" id="descripcion_'+entero+'" autocomplete="off"></textarea>';
	cont+='</div>';
	cont+='<div class="col-xs-2">';
	cont+='<label>P.U.</label>';
	cont+='<input type="text" class="form-control just_numbers" name="precio[]" onkeyup="javascript:cambioImportes('+entero+')" id="precio_'+entero+'" id_fila="'+entero+'" autocomplete="off" value="0.0">';
	cont+='</div>';
	cont+='<div class="col-xs-1">';
	cont+='<label>I.V.A.</label>';
	cont+='<select id="iva_'+entero+'" class="form-control iva" onchange="javascript:cambioImpuestos('+entero+')">';
	cont+='<option value="1" selected="selected">16%</option>';
	cont+='<option value="0">0%</option>';
	cont+='</select>';
	cont+='</div>';
	cont+='<div class="col-xs-2">';
	cont+='<label>Importe</label>';
	cont+='<input type="text" class="form-control total2 suma_importe" name="importe[]" id="importe_'+entero+'" maxlength="10" style="text-align:right;" value="0.0">';
	cont+='</div>';
	cont+='</div><br><hr>';
	cont+='</div>';
	$('#agrega').append(cont);
}

function consulta(){
	// var codigo = $('#codigo').val();
	var rfc = $('#rfc').val();
	rfc = rfc.toUpperCase();
	$('#rfc').val(rfc);
	$('#msg_error').html('');
	$('#step2').hide();
	$('#step3').hide();
	
	$('#razon_social').val('');
	$('#calle').val('');
	$('#n_exterior').val('');
	$('#n_interior').val('');
	$('#colonia').val('');
	$('#cp').val('');
	$('#ciudad').val('');
	$('#municipio').val('');
	$('#estado').val('');
	$('#email').val('');
	$('#telefono').val('');
	$('#celular').val('');
	
	if(!rfc){
		$('#msg_error').html("Ingrese su RFC").show();
		$('#rfc').focus();
		return false
	}
	$('#btn-consulta').button('loading');
	$('#msg_error').hide();
	$.ajax({
	   url: "data/datos_cliente.php",
	   data: '&rfc='+rfc,
	   success: function(data){

	   		// $('#sub').hide();
	   		var datos = data.split('|');
	   		var valida=datos[0];
	   		if(valida==1){
		   		var razon_social = datos[1];
		   		var email = datos[2];
		   		var telefono = datos[3];
		   		var celular = datos[4];
		   		var calle = datos[5];
		   		var n_exterior = datos[6];
		   		var n_interior = datos[7];
		   		var colonia = datos[8];
		   		var cp = datos[9];
		   		var estado = datos[10];
		   		var municipio = datos[11];
		   		var ciudad = datos[12];
		   		var id_cliente = datos[13];

		   		$('#clienteID').val(id_cliente);
		   		$('#razon_social').val(razon_social);
		   		$('#calle').val(calle);
		   		$('#n_exterior').val(n_exterior);
				$('#n_interior').val(n_interior);
		   		$('#colonia').val(colonia);
		   		$('#cp').val(cp);
		   		
		   		$('#estado').val(estado);
			   	$('#municipio').val(municipio);
		   		$('#ciudad').val(ciudad);
		   		
		   		$('#email').val(email);
		   		$('#telefono').val(telefono);
		   		$('#celular').val(celular);

		   		$('#step2').show();
		   		$('#step3').show();
		   		$('#btn-consulta').button('reset');
	   		}else if(valida==2){
		   		$('#step2').show();
		   		$('#step3').show();
		   		$('#btn-consulta').button('reset');
	   		}
	   	},
	   	cache: false
	});
}

function factura(){
	
	$('#msg_error2,#msg_error').hide();
	//Paso 2
	var id_cliente 	 =  $('#clienteID').val();
	var razon_social =	$('#razon_social').val();
	var calle		 =	$('#calle').val();
	var n_exterior	 =	$('#n_exterior').val();
	var colonia		 =	$('#colonia').val();
	var cp			 =	$('#cp').val();
	var localidad	 =	$('#ciudad').val();
	var municipio	 =	$('#municipio').val();
	var estado		 =	$('#estado').val();
	var email		 =	$('#email').val();
	var metodo_pago	 =	$('#metodo_pago').val();
	var digitos		 =	$('#digitos').val();
	var total 		 =  $('#total').val();
	var iva 		 =  $('#iva').val();
	var observacion  =  $('#observacion').val();
	var condPago 	 =  $('#condicionesPago').val();
	var formaPago	 =  $('#formaPago').val();
	
	/*if(!razon_social){ 
		$('#msg_error').html('Ingrese razón social.').show();;
		return false;
	}
	if(!calle){ 
		$('#msg_error').html('Ingrese calle del domicilio fiscal.').show();;
		return false;
	}
	if(!n_exterior){ 
		$('#msg_error').html('Ingrese número exterior.').show();;
		return false;
	}
	if(!colonia){ 
		$('#msg_error').html('Ingrese colonia.').show();;
		return false;
	}
	if(!cp){ 
		$('#msg_error').html('Ingrese código postal.').show();;
		return false;
	}
	if(!municipio){ 
		$('#msg_error').html('Ingrese municipio.').show();;
		return false;
	}
	if(!estado){ 
		$('#msg_error').html('Seleccione el estado.').show();;
		return false;
	}
	/*if(!email){ 
		$('#msg_error').html('Ingrese email.').show();;
		return false;
	}*/
	if(!condPago){ 
		$('#msg_error').html('Ingrese condicion de pago.').show();;
		return false;
	}
	if(!formaPago){ 
		$('#msg_error').html('Ingrese forma del pago.').show();;
		return false;
	}
	if((metodo_pago=="TARJETA DE CREDITO")||(metodo_pago=="TARJETA DE DEBITO")||(metodo_pago=="TRANSFERENCIA ELECTRONICA DE FONDOS")||(metodo_pago=="CHEQUE")||(metodo_pago=="DEPOSITO BANCARIO")){
		if(!digitos){
			$('#msg_error2').html('Para este método de pago es necesario llenar el campo "# Cuenta (4 Digitos)".').show();
			$('#digitos').focus();
			return false;
		}
		
		if((metodo_pago=="TARJETA DE CREDITO")||(metodo_pago=="TARJETA DE DEBITO")){
			if(digitos.length != 4){
				$('#msg_error2').html('Debes capturar los últimos 4 digitos de la tarjeta.').show();;
				$('#digitos').focus();
				return false;
			}	
		}
	}

	//Paso 3
	var numero_productos = 0;
	var array_filas = [];
	$('.cuenta_productos').each(function(){
    	array_filas[numero_productos] = $(this).attr('numero-fila');
    	numero_productos ++;
	});
	for (numero_fila in array_filas) {
		var cantidad	= $('#cantidad_'+array_filas[numero_fila]).val();
		var precio		= $('#precio_'+array_filas[numero_fila]).val();
		var unidad		= $('#unidad_'+array_filas[numero_fila]).val();
		var descripcion	= $('#descripcion_'+array_filas[numero_fila]).val();
		
		if(!cantidad){ 
			$('#msg_error2').html('Ingrese cantidad.').show();
			return false;
		}
		if(!precio){ 
			$('#msg_error2').html('Ingrese costo unitario.').show();
			return false;
		}
		if(unidad==0){ 
			$('#msg_error2').html('Seleccione una unidad.').show();
			return false;
		}
		if(!descripcion){ 
			$('#msg_error2').html('Ingrese descripcion.').show();
			return false;
		}
	}

	if(!confirm('¿Está seguro que sus datos son correctos?')) return false;

	$('#btn-factura').button('loading');
	$('#msg_error').hide();
	/* Esta madre de va por CURL a data/factura.php */
	var datos = $('#form_datos').serialize()+'&'+$('#form_datos2').serialize();
	//alert(datos);
	//return false;
	/*
	Metronic.blockUI({
        target: '#step3',
        boxed: true,
        message: 'Generando Factura; Realizando Timbrado, espere un momento. <i class="fa fa-cloud-upload"></i>'
    });
    */

	$.post('../facturacion/facturacion/facturar_2.php',datos,function(data) {
		console.log(data);
		$data = data.split('|');
		if(data[0]==1){
			$('#btn-factura').button('reset');
			window.open("?Modulo=Facturacion&msg=1", "_self");
		}else{
			$('#msg_error2').html(data).show();
			$('#btn-factura').button('reset');
		}
	});

	// $.post('data/factura.php',datos,function(data) {
	// 	if(!isNaN(data)){
	// 		window.open("?Modulo=VerFacturas&id_factura="+data, "_self");
	// 	}else{
	// 		$('#msg_error').html(data).show();
	// 		$('#btn-factura').button('reset');
	// 	}
	// });	
}
	
</script>