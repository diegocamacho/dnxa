<?
$id=$_GET['id'];

if(!$_GET['id']){
	echo '<div class="row"><div class="col-md-12"><div class="alert alert-danger" role="alert">No se puede mostrar el contenido</div></div></div>';
}else{

$sq="SELECT * FROM clientes WHERE id_cliente=$id";
$query=mysql_query($sq);
$ft=mysql_fetch_assoc($query);
//$val=mysql_num_rows($query);
/*
if(!$val){
	exit('<div class="row"><div class="col-md-12"><div class="alert alert-danger oculto" role="alert" id="msg_error"></div></div></div>');
}*/


$sql="SELECT * FROM estados_pais ORDER BY estado ASC";
$q=mysql_query($sql);
?>
<div class="row">		
	<div class="col-md-12">
		<div class="portlet light  portlet-fit">
			<div class="portlet-title">
				<div class="caption">
						<i class="icon-user font-green"></i>
						<span class="caption-subject font-dark bold uppercase">Edita Cliente Facturación</span>
				</div>
			</div>
			<div class="portlet-body">
				
				<form id="form_guarda" class="form-horizontal">
					<div class="alert alert-danger oculto" role="alert" id="msg_error"></div>
					<div class="form-group">
						<label for="razon_social" class="col-sm-2 control-label">RFC:</label>
						<div class="col-sm-9">
							<input type="text" class="form-control limpia" id="rfc" name="rfc" value="<?=$ft['rfc']?>" autocomplete="off">
	    				</div>
	  				</div>
	  				
					<div class="form-group">
						<label for="razon_social" class="col-sm-2 control-label">Razón Social:</label>
						<div class="col-sm-9">
							<input type="text" class="form-control limpia" id="razon_social" name="razon_social" value="<?=$ft['razon_social']?>" autocomplete="off">
	    				</div>
	  				</div>
	  				
	  				<div class="form-group">
						<label for="razon_social" class="col-sm-2 control-label">Representante o Contacto:</label>
						<div class="col-sm-9">
							<input type="text" class="form-control limpia" id="representante" name="representante" value="<?=$ft['representante']?>" autocomplete="off">
	    				</div>
	  				</div>

	  				<div class="form-group">
						<label for="direccion" class="col-sm-2 control-label">Calle:</label>
						<div class="col-sm-9">
							<input type="text" class="form-control limpia" id="calle" name="calle" value="<?=$ft['calle']?>" autocomplete="off">
	    				</div>
	  				</div>

	  				<div class="form-group">
						<label for="n_exterior" class="col-sm-2 control-label">Número Exterior:</label>
						<div class="col-sm-4">
							<input type="text" class="form-control limpia" id="n_exterior" name="n_exterior" value="<?=$ft['n_exterior']?>" autocomplete="off">
	    				</div>
	    				
	    				<label for="n_interior" class="col-sm-2 control-label">Número Interior:</label>
						<div class="col-sm-3">
							<input type="text" class="form-control limpia" id="n_interior" name="n_interior" value="<?=$ft['n_interior']?>" placeholder="Opcional" autocomplete="off">
	    				</div>
	  				</div>
	  				
	  				<div class="form-group">
						<label for="colonia" class="col-sm-2 control-label">Colonia:</label>
						<div class="col-sm-4">
							<input type="text" class="form-control limpia" id="colonia" name="colonia" value="<?=$ft['colonia']?>" autocomplete="off">
	    				</div>
	    				
	    				<label for="cp" class="col-sm-2 control-label">Código Postal:</label>
						<div class="col-sm-3">
							<input type="text" class="form-control limpia" id="cp" name="cp" value="<?=$ft['cp']?>" autocomplete="off" maxlength="6">
	    				</div>
	  				</div>

	  				<hr><br>

					<div class="form-group">
						<label for="estado" class="col-sm-2 control-label">Estado:</label>
						<div class="col-sm-4">
							<select class="form-control" name="estado" id="estado">
									<option value="">Seleccione un estado</option>
								<? while($ft_estados_pais=mysql_fetch_assoc($q)){ ?>
									<option value="<?=$ft_estados_pais['estado']?>" <? if($ft['estado_pais_cadena']==$ft_estados_pais['estado']){ ?>selected="1" <? } ?>><?=$ft_estados_pais['estado']?></option>
								<? } ?>
							</select>
	    				</div>
	  				</div>

	  				<div class="form-group">
						<label for="municipio" class="col-sm-2 control-label">Mpio o Delegación:</label>
						<div class="col-sm-4">
							<input type="text" class="form-control limpia" id="municipio" name="municipio" value="<?=$ft['municipio']?>" autocomplete="off">
	    				</div>

						<label for="ciudad" class="col-sm-2 control-label">Ciudad:</label>
						<div class="col-sm-3">
							<input type="text" class="form-control limpia" id="ciudad" name="ciudad" value="<?=$ft['ciudad']?>" autocomplete="off">
	    				</div>
	  				</div>

	  				<hr><br>

	  				<!--<div class="form-group">
						<label for="email" class="col-sm-2 control-label">Email:</label>
						<div class="col-sm-4">
							<input type="email" class="form-control limpia" id="email" name="email" value="<?=$ft['email']?>" autocomplete="off">
	    				</div>
	    			</div>-->

	  				<div class="form-group">
						<label for="telefono" class="col-sm-2 control-label">Teléfono:</label>
						<div class="col-sm-4">
							<input type="text" class="form-control limpia" id="telefono" name="telefono" value="<?=$ft['telefono']?>" maxlength="10" autocomplete="off">
	    				</div>
	    				
	    				<label for="celular" class="col-sm-2 control-label">Celular:</label>
						<div class="col-sm-3">
							<input type="text" class="form-control limpia" id="celular" name="celular" value="<?=$ft['celular']?>" maxlength="10" autocomplete="off">
	    				</div>
	  				</div>
	  				<input type="hidden" name="id_cliente" value="<?=$id?>" />
				</form>
				
			</div>
		 
			<div class="panel-footer text-right" style="background-color: white !important;">
				<img src="assets/global/img/loading-spinner-grey.gif" border="0" id="load" width="30" class="oculto" />
				<a href="?Modulo=ClientesFactura" role="button" class="btn btn-default btn_ac" >Cancelar</a>&nbsp;&nbsp;&nbsp;&nbsp;
				<button type="button" class="btn btn-primary btn_ac" onclick="javascript:editaCliente();">Guardar Cambios</button>
			</div>
			 
		</div>	
	</div>
</div>
<script>
function editaCliente(){
	$('#msg_error').hide('Fast');
	$('.btn_ac').hide();
	$('#load').show();
	var datos=$('#form_guarda').serialize();
	$.post('ac/edita_cliente_fact.php',datos,function(data){
		if(data==1){
			window.open("?Modulo=ClientesFactura&msg=2", "_self");
	    }else{
	    	$('#load').hide();
			$('.btn').show();
			$('#msg_error').html(data);
			$('#msg_error').show('Fast');
	    }
	});
}
</script>
<? } ?>