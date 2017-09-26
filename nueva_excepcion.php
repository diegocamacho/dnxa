<?
	$id_evento = $_GET['id'];
	$fecha_evento = $_GET['fecha'];
	
	$id_evento = explode("|",$id_evento);
	$id_evento = $id_evento[1];
		
	$fecha_base = explode("T",$fecha_evento);
	$fecha_base = $fecha_base[0];
	/*$fecha_base = explode(" ",$fecha_base);
	switch($fecha_base[1]){
		case "Enero":
		 $mes = "01";
		break;
		case "Febrero":
		 $mes = "02";
		break;
		case "Marzo":
		 $mes = "03";
		break;
		case "Abril":
		 $mes = "04";
		break;
		case "Mayo":
		 $mes = "05";
		break;
		case "Junio":
		 $mes = "06";
		break;
		case "Julio":
		 $mes = "07";
		break;
		case "Agosto":
		 $mes = "08";
		break;
		case "Septiembre":
		 $mes = "09";
		break;
		case "Octubre":
		 $mes = "10";
		break;
		case "Noviembre":
		 $mes = "11";
		break;
		case "Diciembre":
		 $mes = "12";
		break;
	}
	
	$fecha_base = $fecha_base[2]."-".$mes."-".$fecha_base[0];*/
	$evento = mysql_fetch_array(mysql_query("SELECT descripcion FROM eventos WHERE id_evento='$id_evento'"));
	$evento = $evento[0];
	
?>
<style>
.foto{
	height: 150px;
	max-width: 240px;
}	
.titulo_producto{
	margin-top: 5px;
	display: block;
}

.color {
background:#ffffda;
-webkit-transition:background 1s;
-moz-transition:background 1s;
-o-transition:background 1s;
transition:background 1s
}

.color2 {
background:white;
-webkit-transition:background 2s;
-moz-transition:background 2s;
-o-transition:background 2s;
transition:background 2s
}
.ocultar{
	display: none;
}
</style>
<script src="assets/jquery.alphanumeric.js" type="text/javascript"></script>
<div class="page-content-inner">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light portlet-fit">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-book-open font-dark"></i>
                        <span class="caption-subject font-dark sbold uppercase">Nueva Excepción / <?=$evento?></span>
                    </div>
                    
                    <div class="actions">
						<a href="?Modulo=Agenda" class="btn btn-circle red-thunderbird"> Regresar / Salir</a>
                    </div>
                </div>
                <div class="portlet-body">
	                
	                
	                
                    <div class="row">
<!-- Datos del paciente -->	                    
						<div class="col-md-12">
								<div class="portlet box green">
                            	    <div class="portlet-title">
                            	        <div class="caption">Información del Evento</div>
                            	    </div>
                            	    <div class="portlet-body">
	                        	        
										<div class="form-body" style="margin-top: 20px;">
								
                            			    <form id="frm_datos" class="form-horizontal" role="form" onsubmit="return false">
			
												<div class="row">
													<div class="col-md-6">
<div class="form-group">
													<label for="nombre" class="col-md-2 control-label" style="text-align: left;">Evento:</label>
													<div class="col-md-10">
														<input type="text" class="form-control dat" value="<?=$evento?>" autocomplete="off" readonly>
													</div>
												</div>
			
												<div class="form-group">
													<label for="telefono" class="col-md-2 control-label" style="text-align: left;">Día a Modificar:</label>
													<div class="col-md-10">
														<input type="text" class="form-control dat" value="<?=fechaHoraMeridian2($fecha_evento)?>" readonly>
													</div>
												</div>
												
												<div class="form-group">
												    <label class="control-label col-md-4">Nueva Hora de Inicio</label>
												    <div class="col-md-4">
												        <div class="input-group">
												            <input type="text" class="form-control timepicker timepicker-24" name="hora1">
												            <span class="input-group-btn">
												                <button class="btn default" type="button">
												                    <i class="fa fa-clock-o"></i>
												                </button>
												            </span>
												        </div>
												    </div>
												</div>
												
												<div class="form-group">
												    <label class="control-label col-md-4">Nueva Hora Final</label>
												    <div class="col-md-4">
												        <div class="input-group">
												            <input type="text" class="form-control timepicker timepicker-24" name="hora2">
												            <span class="input-group-btn">
												                <button class="btn default" type="button">
												                    <i class="fa fa-clock-o"></i>
												                </button>
												            </span>
												        </div>
												    </div>
												</div>
												

													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label for="descripcion" class="col-md-3 control-label">Comentarios</label>
															<div class="col-md-9">
																<textarea class="form-control dat" autocomplete="off" name="comentarios" rows="5"></textarea>
															</div>
														</div>
													</div>

													
												</div>
												<input type="hidden" name="id_evento" value="<?=$id_evento?>" />
												<input type="hidden" name="fecha1" value="<?=$fecha_base?>" />
												<input type="hidden" name="fecha2" value="<?=$fecha_base?>" />
												
												<div class="form-actions text-right guardar" >
													<a role="button" class="btn blue-madison" onclick="guardaSolicitud();">Guardar Excepción</a>
												</div>
											</form>
											
										</div>

                            	    </div>
                            	</div>
                            	
						</div>					
					
					
					
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(function(){
	
	
	$('.numerico').numeric({allow:'.'});

	
});


function guardaSolicitud(){
	App.blockUI(
		{
            boxed: true,
            message: 'Guardando Excepción.'
        }
	);
	var datos	=	$('#frm_datos').serialize();
	$.post('ac/nueva_excepcion.php',datos,function(data){
		console.log(data);		
	    if(data==1){
				
			window.open("?Modulo=Agenda", "_self");
			//tal vez un if aqui.
			//
			
	    }else{
	    	App.unblockUI();
			alert(data);
	    }
	});
}
</script>