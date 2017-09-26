<?
$sql="SELECT * FROM clinicas WHERE activo=1";
$q=mysql_query($sql);

$clinicas = array();

while($datos=mysql_fetch_object($q)):
	$clinicas[] = $datos;
endwhile;

//Tipo de gasto
$sql="SELECT * FROM books_tipos_gasto WHERE activo=1 AND eliminable=1 ORDER BY cuenta_gasto  ASC";
$q=mysql_query($sql);

$tipo_gatos = array();

while($datos=mysql_fetch_object($q)):
	$tipo_gatos[] = $datos;
endwhile;

//Tipo de ingresos
$sql="SELECT * FROM books_tipos_ingreso WHERE activo=1 AND eliminable=1 ORDER BY cuenta_ingreso  ASC";
$q=mysql_query($sql);
$tipo_ingresos = array();
while($datos=mysql_fetch_object($q)):
	$tipo_ingresos[] = $datos;
endwhile;

//Metódo de pago
$sql="SELECT * FROM books_metodo_pago WHERE activo=1 ORDER BY metodo_pago  ASC";
$q=mysql_query($sql);

$metodo_pago = array();

while($datos=mysql_fetch_object($q)):
	$metodo_pago[] = $datos;
endwhile;


//Operaciones
/*
$sql="SELECT id_gasto, proveedor, referencia, tipo_cuenta, alias, metodo_pago,cuenta_gasto, fecha_gasto, monto, referencia  FROM books_gastos
JOIN books_proveedores ON books_proveedores.id_proveedor=books_gastos.id_proveedor
JOIN books_cuentas ON books_cuentas.id_cuenta=books_gastos.id_cuenta
JOIN books_metodo_pago ON books_metodo_pago.id_metodo_pago=books_gastos.id_metodo_pago
JOIN books_tipos_gasto ON books_tipos_gasto.id_tipo_gasto=books_gastos.id_tipo_gasto";
$q=mysql_query($sql);

$operaciones = array();

while($datos=mysql_fetch_object($q)):
	$operaciones[] = $datos;
endwhile;
*/
if($_GET['id']):
	$id_empresa=escapar($_GET['id'],1);
	$consulta="AND id_empresa=$id_empresa ";
endif;
//Cuentas
$sql="SELECT id_cuenta,id_empresa,alias,tipo_cuenta,clinica FROM books_cuentas 
JOIN clinicas ON clinicas.id_clinica=books_cuentas.id_empresa
WHERE books_cuentas.activo=1 $consulta ORDER BY books_cuentas.id_empresa,tipo_cuenta ASC";
$q=mysql_query($sql);
$cuentas = array();
while($datos=mysql_fetch_object($q)):
	$cuentas[] = $datos;
endwhile;

$cambio=0;
$valida=count($cuentas);
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
	<div class="row">
		<div class="col-md-12">
			<? if($valida): ?>
			<!-- Confirmación -->
			<? if($_GET['msg']==1){ ?>
			  	<br>
			  	<div class="alert alert-dismissable alert-success">
			    		<button type="button" class="close" data-dismiss="alert">×</button>
			    		<p>La compra se ha agregado</p>
			    	</div>
			<? }if($_GET['msg']==2){ ?>
			  	<br>
			  	<div class="alert alert-dismissable alert-info">
			    		<button type="button" class="close" data-dismiss="alert">×</button>
			    		<p>La transferencia se ha efectuado</p>
			    	</div>
			<? }if($_GET['msg']==3){ ?>
			  	<br>
			  	<div class="alert alert-dismissable alert-success">
			    		<button type="button" class="close" data-dismiss="alert">×</button>
			    		<p>El ingreso se ha agregado</p>
			    	</div>
			<? } ?>
			<!-- Contenido -->
	        <!-- BEGIN BORDERED TABLE PORTLET-->
	        <div class="portlet light portlet-fit ">
	            <div class="portlet-title">
	                <div class="caption">
	                    <i class="icon-book-open font-dark"></i>
	                    <span class="caption-subject font-dark sbold uppercase">Operaciones de cuentas </span>
	                    <span class="caption-helper">(Ingresos - Egresos)</span>
	                </div>
	                <div class="actions">
	                                        
	                    <div class="btn-group">
	                        <a class="btn blue-chambray dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Operaciones
	                            <i class="fa fa-angle-down"></i>
	                        </a>
	                        <ul class="dropdown-menu">
	                            <li>
	                                <a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#nuevaCompra"> Nuevo Gasto </a>
	                            </li>
	                            <li>
	                                <a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#nuevoIngreso"> Nuevo Ingreso </a>
	                            </li>
	                            <li>
	                                <a href="javascript:;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#nuevaTransferencia"> Nueva Transferencia </a>
	                            </li>
	                            
	                        </ul>
	                    </div>
	                    
	                    
	                    
	                </div>
	            </div>
	            <div class="portlet-body">
	                <div class="table-scrollable table-scrollable-borderless">
	                    <table class="table table-hover table-light">
	                        <thead>
	                            <tr class="uppercase">
	                                <th> Empresa </th>
	                                <th> Cuenta </th>
	                                <!--<th width="120" style="text-align: right"> Ingresos </th>
	                                <th width="120" style="text-align: right"> Egresos </th>-->
	                                <th width="120" style="text-align: right"> Saldo </th>
	                                <th width="150"> </th>
	                            </tr>
	                        </thead>
	                        <tbody>
		                        
		                        <? foreach($cuentas as $cuenta): 
			                        $id_cuenta=$cuenta->id_cuenta;
			                        
			                        $ingresos=dameIngresos($id_cuenta);
			                        $egresos=dameEgresoso($id_cuenta);
			                        
			                        $saldo=$ingresos-$egresos;
			                        
			                        if($cambio!=$cuenta->id_empresa):
			                        	echo '<tr>
												<td> &nbsp; </td>
												<td> &nbsp; </td>
												<td> &nbsp; </td>

												<td align="right">
													&nbsp;
												</td>
											</tr>
			                        		<tr>
												<td> '.$cuenta->clinica.' </td>
												<td> &nbsp; </td>
												<td> &nbsp; </td>

												<td align="right">
													<a href="?Modulo=TransaccionesClinica&id='.$cuenta->id_empresa.'" role="button" class="btn  green-jungle btn-xs ">Todas</a>	
												</td>
											</tr>';
			                        endif;
			                        
		                        ?>
	                            <tr>
	                                <td> <?=$cuenta->clinica ?> </td>
	                                <td> <?=$cuenta->alias ?> (<?=dameTipo($cuenta->tipo_cuenta) ?>) </td>
	                                <!--<td align="right" class="font-dark"> <?=number_format($ingresos,2)?> </td>
	                                <td align="right" class="font-dark"> <?=number_format($egresos,2)?> </td>-->
	                                <td align="right" class="font-dark"> <?=number_format($saldo,2)?> </td>
	                                <td align="right">
	                                    <a href="?Modulo=Transacciones&id=<?= $id_cuenta ?>" role="button" class="btn  blue-chambray btn-xs ">Transacciones</a>
	                                </td>
	                            </tr>
	                            
	                            
	                            <? 
		                            
			                        
		                            $cambio=$cuenta->id_empresa;
		                            endforeach; ?>
	                            
	                        </tbody>
	                    </table>
	                </div>
	            </div>
	        </div>
	        <!-- END BORDERED TABLE PORTLET-->
	        <? else: ?>
	        <div class="alert alert-dismissable alert-warning">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<p>Aún no se han creado movimientos</p>
			</div>
	        <? endif; ?>	     
	    </div>
	</div>
</div>






<!-- Modal -->
<div class="modal fade" id="nuevaCompra">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Nuevo Gasto</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="msg_error"></div>
<!--Formulario -->
		<form id="frm_guarda" class="form-horizontal">
			
			<div class="form-group">
				<label for="direccion" class="col-md-3 control-label">Tipo de gasto</label>
				<div class="col-md-9">
					<select class="form-control" name="id_tipo_gasto" >
						<option value="0">Seleccione una</option>
						<? foreach($tipo_gatos as $tipo_gato): ?>
						<option value="<?=$tipo_gato->id_tipo_gasto?>"><?=$tipo_gato->cuenta_gasto?></option>
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
					<input type="text" maxlength="128" class="form-control dat" name="descripcion" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="telefono" class="col-md-3 control-label">Monto</label>
				<div class="col-md-4">
					<input type="text" maxlength="16" class="form-control dat" name="monto" autocomplete="off" value="0">
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
			
		</form>
		      
      </div>
      <div class="modal-footer">
      	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load" width="25" class="oculto" />
        <button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn blue-chambray btn_ac" onclick="guardaCompra()">Guardar Gasto</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->







<!-- Modal -->
<div class="modal fade" id="nuevoIngreso">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Nuevo Ingreso</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="msg_error3"></div>
<!--Formulario -->
		<form id="frm_guarda_ingreso" class="form-horizontal">
			
			<div class="form-group">
				<label for="direccion" class="col-md-3 control-label">Tipo de ingreso</label>
				<div class="col-md-9">
					<select class="form-control" name="id_tipo_ingreso" >
						<option value="0">Seleccione una</option>
						<? foreach($tipo_ingresos as $tipo_ingreso): ?>
						<option value="<?=$tipo_ingreso->id_tipo_ingreso?>"><?=$tipo_ingreso->cuenta_ingreso?></option>
						<? endforeach; ?>
					</select>
				</div>
			</div>
			
			<div class="form-group">
				<label for="direccion" class="col-md-3 control-label">Empresa</label>
				<div class="col-md-9">
					<select class="form-control" name="id_clinica" id="id_clinica_ingreso" >
						<option value="0">Seleccione una</option>
						<? foreach($clinicas as $clinica): ?>
						<option value="<?=$clinica->id_clinica?>"><?=$clinica->clinica?></option>
						<? endforeach; ?>
					</select>
				</div>
			</div>
			
			
			<div id="show_form_ingreso">
						
			</div>
			
			
			
			
			
			<hr>
			
			<div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Descripción</label>
				<div class="col-md-9">
					<input type="text" maxlength="128" class="form-control dat" name="descripcion" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="telefono" class="col-md-3 control-label">Monto</label>
				<div class="col-md-4">
					<input type="text" maxlength="16" class="form-control dat" name="monto" autocomplete="off" value="0">
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
			
		</form>
		      
      </div>
      <div class="modal-footer">
      	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load3" width="25" class="oculto" />
        <button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn blue-chambray btn_ac" onclick="guardaIngreso()">Guardar Ingreso</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->







<!-- Modal -->
<div class="modal fade" id="nuevaTransferencia">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title">Transferencia entre cuentas</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger oculto" role="alert" id="msg_error2"></div>
<!--Formulario -->
		<form id="frm_transferencia" class="form-horizontal">
			<h4 style="text-align: center;margin-bottom: 25px;">Configuración de salida</h4>
			<div class="form-group">
				<label for="direccion" class="col-md-3 control-label">Empresa</label>
				<div class="col-md-9">
					<select class="form-control" name="id_empresa_1" id="id_clinica_t1" >
						<option value="0">Seleccione una</option>
						<? foreach($clinicas as $clinica): ?>
						<option value="<?=$clinica->id_clinica?>"><?=$clinica->clinica?></option>
						<? endforeach; ?>
					</select>
				</div>
			</div>
			
			<div class="form-group">
				<label for="direccion" class="col-md-3 control-label">Cuenta</label>
				<div class="col-md-9">
					<select class="form-control" name="id_cuenta_1" id="id_cuenta_t1" >
						
					</select>
				</div>
			</div>
			
			<hr>
			<h4 style="text-align: center;margin-bottom: 25px;">Configuración de entrada</h4>
			<div class="form-group">
				<label for="direccion" class="col-md-3 control-label">Empresa</label>
				<div class="col-md-9">
					<select class="form-control" name="id_empresa_2" id="id_clinica_t2" >
						<option value="0">Seleccione una</option>
						<? foreach($clinicas as $clinica): ?>
						<option value="<?=$clinica->id_clinica?>"><?=$clinica->clinica?></option>
						<? endforeach; ?>
					</select>
				</div>
			</div>
			
			<div class="form-group">
				<label for="direccion" class="col-md-3 control-label">Cuenta</label>
				<div class="col-md-9">
					<select class="form-control" name="id_cuenta_2" id="id_cuenta_t2" >
						
					</select>
				</div>
			</div>
			
			<hr>
			<div class="form-group">
				<label for="nombre" class="col-md-3 control-label">Descripción</label>
				<div class="col-md-9">
					<input type="text" maxlength="128" class="form-control dat" name="descripcion" autocomplete="off">
				</div>
			</div>
			
			<div class="form-group">
				<label for="telefono" class="col-md-3 control-label">Monto</label>
				<div class="col-md-4">
					<input type="text" maxlength="16" class="form-control dat" name="monto" autocomplete="off" value="0">
				</div>
			</div>
			
			
		</form>
		      
      </div>
      <div class="modal-footer">
      	<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load2" width="25" class="oculto" />
        <button type="button" class="btn btn-default btn_ac" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn blue-chambray btn_ac" onclick="transferencia()">Transferir</button>
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
	
	//Para ingresos
	$('#id_clinica_ingreso').change(function(){
		var id_clinica = $('#id_clinica_ingreso').val();
		$.ajax({
			url: "data/books_select_nuevo_ingreso.php",
	   		data: 'id_clinica='+id_clinica,
	   		success: function(data){
		   		console.log(data);
		   		$('#show_form_ingreso').html(data);
		   		$('#show_form_ingreso').show();
	   	},
	   	cache: false
	   	});
		
	});
	
	
	
	
	/* Trabsferencias */
	$('#id_clinica_t1').change(function(){
		var id_clinica = $('#id_clinica_t1').val();
		$.ajax({
			url: "data/books_select_cuentas.php",
	   		data: 'id_clinica='+id_clinica,
	   		success: function(data){
		   		console.log(data);
		   		$('#id_cuenta_t1').html(data);
	   	},
	   	cache: false
	   	});
	});
	
	$('#id_clinica_t2').change(function(){
		var id_clinica = $('#id_clinica_t2').val();
		$.ajax({
			url: "data/books_select_cuentas.php",
	   		data: 'id_clinica='+id_clinica,
	   		success: function(data){
		   		console.log(data);
		   		$('#id_cuenta_t2').html(data);
	   	},
	   	cache: false
	   	});
	});
	
});	
	

function guardaCompra(){
	$('#msg_error').hide('Fast');
	$('.btn_ac').hide();
	$('#load').show();
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

function guardaIngreso(){
	$('#msg_error3').hide('Fast');
	$('.btn_ac').hide();
	$('#load3').show();
	var datos=$('#frm_guarda_ingreso').serialize();
	$.post('ac/nuevo_ingreso.php',datos,function(data){
	    if(data==1){
			window.open("?Modulo=Operaciones&msg=3", "_self");
	    }else{
	    	$('#load3').hide();
			$('.btn_ac').show();
			$('#msg_error3').html(data);
			$('#msg_error3').show('Fast');
	    }
	});
}


function transferencia(){
	$('#msg_error2').hide('Fast');
	$('.btn_ac').hide();
	$('#load2').show();
	var datos=$('#frm_transferencia').serialize();
	$.post('ac/transferencia.php',datos,function(data){
		console.log(data);
		var datos = data.split('|');
		
	    if(datos[0]==1){
		    
			if(datos[1]){
				$.post('http://localhost/imprimir_remoto.php','imprimir='+datos[1]);
				setTimeout(function() {
				
				window.open("?Modulo=Operaciones&msg=2", "_self");
				
				}, 1000);
			}
			
	    }else{
	    	$('#load2').hide();
			$('.btn_ac').show();
			$('#msg_error2').html(data);
			$('#msg_error2').show('Fast');
	    }
	});
}
</script>