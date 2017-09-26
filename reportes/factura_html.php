<? set_time_limit(0); error_reporting(0);

include('../includes/db.php');
include('../includes/session.php');
include('../includes/funciones.php');
include('../includes/num_letra.php');


$id_factura = mysql_real_escape_string($_GET['id']);

if(!is_numeric($id_factura)) exit('ID incorrecto.');

$sql = "SELECT * FROM facturas WHERE id_factura = $id_factura";

$q = @mysql_query($sql);
$n = @mysql_num_rows($q);
if(!$n) exit('Error.');

$data = @mysql_fetch_assoc($q);
$uuid = $data['uuid'];
$tipo = $data['tipo'];
if($tipo == 2){
	$ruta_xml = "http://dentisxa.net/facturacion/facturacion/cfdi/".$uuid.".xml";
	
	$link_xml = 'http://dentisxa.net/facturacion/d.php?i='.$uuid.'&tipo=xml';
	$link_pdf = 'http://dentisxa.net/facturacion/d.php?i='.$uuid.'&tipo=pdf';
}else{
	$ruta_xml = "http://adminus.mx/dentista/facturacion/facturacion/cfdi/".$uuid.".xml";
	
	$link_xml = 'http://adminus.mx/dentista/facturacion/d.php?i='.$uuid.'&tipo=xml';
	$link_pdf = 'http://adminus.mx/dentista/facturacion/d.php?i='.$uuid.'&tipo=pdf';
}

$xml = @simplexml_load_file($ruta_xml); 
$ns = $xml->getNamespaces(true);
$xml->registerXPathNamespace('c', $ns['cfdi']);
$xml->registerXPathNamespace('t', $ns['tfd']);
 
$ret_isr = 0.00;
$ret_iva = 0.00;
$cantidad_iva = (double)0.00;
foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){ 
      $cfdiComprobante['version']; 
      
      $cfdiComprobante['fecha']; 
      
      $cfdiComprobante['sello']; 
      
      $total = (double)$cfdiComprobante['total'];
      $subtotal = (double)$cfdiComprobante['subTotal'];
      
      $cfdiComprobante['certificado']; 
      
      $cfdiComprobante['formaDePago']; 
      
      $cfdiComprobante['noCertificado']; 
      
      $cfdiComprobante['tipoDeComprobante']; 
      $cfdiComprobante['LugarExpedicion'];
      $cfdiComprobante['metodoDePago'];
      $cfdiComprobante['condicionesDePago'];
      
      
      $cfdiComprobante['serie'];
	  $cfdiComprobante['folio'];
	  
      $cfdiComprobante['motivoDescuento'];
	  $cfdiComprobante['Moneda'];
	  
	  $descuento = (double)$cfdiComprobante['descuento'];
	  $cfdiComprobante['motivoDescuento'];
      
} 
foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){ 
   $Emisor['rfc']; 
   
   $Emisor['nombre']; 
   
} 
foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:DomicilioFiscal') as $DomicilioFiscal){ 
   $DomicilioFiscal['pais']; 
   
   $DomicilioFiscal['calle']; 
   
   $DomicilioFiscal['estado']; 
   
   $DomicilioFiscal['colonia']; 
   
   $DomicilioFiscal['municipio']; 
   
   $DomicilioFiscal['noExterior']; 
   
   $DomicilioFiscal['codigoPostal']; 
   
} 
foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:ExpedidoEn') as $ExpedidoEn){ 
   $ExpedidoEn['pais']; 
   
   $ExpedidoEn['calle']; 
   
   $ExpedidoEn['estado']; 
   
   $ExpedidoEn['colonia']; 
   
   $ExpedidoEn['noExterior']; 
   
   $ExpedidoEn['codigoPostal']; 
   
} 

foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:RegimenFiscal') as $RegimenFiscal){ 
   $RegimenFiscal['Regimen']; 

} 


foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Receptor){ 
   $Receptor['rfc']; 
   
   $Receptor['nombre']; 
   
} 
foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor//cfdi:Domicilio') as $ReceptorDomicilio){ 
   $ReceptorDomicilio['pais']; 
   
   $ReceptorDomicilio['calle']; 
   
   $ReceptorDomicilio['estado']; 
   
   $ReceptorDomicilio['colonia']; 
   
   $ReceptorDomicilio['municipio']; 
   
   $ReceptorDomicilio['noExterior']; 
   
   $ReceptorDomicilio['noInterior']; 
   
   $ReceptorDomicilio['codigoPostal']; 
   
} 

foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Traslado){ 

   $Traslado['tasa'];
   $Traslado['importe'] = (double)$Traslado['importe'];
   $Traslado['impuesto']; 
   
   if($Traslado['impuesto']=='IVA'){
	   $cantidad_iva+=(double)$Traslado['importe'];
   }
     
   
} 

foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Retenciones//cfdi:Retencion') as $Retenciones){ 
   
   $Retenciones['importe']; 
   $Retenciones['impuesto']; 
   
   if($Retenciones['impuesto']=='IVA'){
	   $ret_iva+=(double)$Retenciones['importe'];
   }
   if($Retenciones['impuesto']=='ISR'){
	   $ret_isr+=(double)$Retenciones['importe'];
   }
     
   
} 

 
//ESTA ULTIMA PARTE ES LA QUE GENERABA EL ERROR
foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
   $tfd['selloCFD']; 
   
   $tfd['FechaTimbrado']; 
   
   $tfd['UUID']; 
   
   $tfd['noCertificadoSAT']; 
   
   $tfd['version']; 
   
   $tfd['selloSAT']; 
} 

function ProcesImpTot($ImpTot){
        $ArrayImpTot = explode(".", $ImpTot);
        $NumEnt = $ArrayImpTot[0];
        $NumDec = ProcesDecFac($ArrayImpTot[1]);
        return $NumEnt.".".$NumDec;
    }
    
function ProcesDecFac($Num){
    $FolDec = "";
    if ($Num < 10){$FolDec = "00000".$Num;}
    if ($Num > 9 and $Num < 100){$FolDec = $Num."0000";}
    if ($Num > 99 and $Num < 1000){$FolDec = $Num."000";}
    if ($Num > 999 and $Num < 10000){$FolDec = $Num."00";}
    if ($Num > 9999 and $Num < 100000){$FolDec = $Num."0";}
    return $FolDec;
}

?>


	<style>
		.titulos{
			background-color: #444d58;
			color: #FFF;
			/*padding-left: 5px;*/
		}
		.borde-azul{
			border: #444d58 1px solid ;
		}
		.borde-iz{
			border-left: #444d58 1px solid;
		}
		.borde-der{
			border-right: #444d58 1px solid;
		}
		.borde-bot{
			border-bottom: #444d58 1px solid;
		}
		
		.f11{
			font-family: 'Arial';
			font-size: 11px;
		}
		.f10{
			font-family: 'Arial';
			font-size: 10px;
		}
	</style>
<?
if($Emisor['nombre']) $emisor_nombre=$Emisor['nombre'];

// Emirso
$emisor_nombre = ($Emisor['nombre']) ? $Emisor['nombre'] : '';
$emisor_rfc = ($Emisor['rfc']) ? 'RFC: '.$Emisor['rfc'] : '';
$domicilio_fiscal_calle = ($DomicilioFiscal['calle']) ? $DomicilioFiscal['calle'] : '';
$domicilio_fiscal_nexterior = ($DomicilioFiscal['noExterior']) ? $DomicilioFiscal['noExterior'].',' : '';
$domicilio_fiscal_colonia = ($DomicilioFiscal['colonia']) ? $DomicilioFiscal['colonia'].',' : '';
$domicilio_fiscal_municipio = ($DomicilioFiscal['municipio']) ? $DomicilioFiscal['municipio'].',' : '';
$domicilio_fiscal_cp = ($DomicilioFiscal['codigoPostal']) ? 'C.P.: '.$DomicilioFiscal['codigoPostal'].'.' : '';
$domicilio_fiscal_localidad = ($DomicilioFiscal['localidad']) ? $DomicilioFiscal['localidad'].',' : '';
$domicilio_fiscal_estado = ($DomicilioFiscal['estado']) ? $DomicilioFiscal['estado'].',' : '';
$domicilio_fiscal_pais = ($DomicilioFiscal['pais']) ? $DomicilioFiscal['pais'] : '';
$regimen_fiscal = ($RegimenFiscal['Regimen']) ? $RegimenFiscal['Regimen'] : 'N/A';

// Receptor
$receptor_nombre = ($Receptor['nombre']) ? $Receptor['nombre'] : '';
$receptor_rfc = ($Receptor['rfc']) ? 'RFC: '.$Receptor['rfc'] : '';
$receptor_calle = ($ReceptorDomicilio['calle']) ? $ReceptorDomicilio['calle'].',' : '';
$receptor_nexterior = ($ReceptorDomicilio['noExterior']) ? 'NO EXT.: '.$ReceptorDomicilio['noExterior'] : '';
$receptor_ninterior = ($ReceptorDomicilio['noInterior']) ? 'NO. INT.: '.$ReceptorDomicilio['noInterior'] : '';
$receptor_colonia = ($ReceptorDomicilio['colonia']) ? 'COL.: '.$ReceptorDomicilio['colonia'].',' : '';
$receptor_localidad = ($ReceptorDomicilio['localidad']) ? $ReceptorDomicilio['localidad'].',' : '';
$receptor_municipio = ($ReceptorDomicilio['municipio']) ? $ReceptorDomicilio['municipio'].',' : '';
$receptor_cp = ($ReceptorDomicilio['codigoPostal']) ? 'C.P.: '.$ReceptorDomicilio['codigoPostal'] : '';
$receptor_estado = ($ReceptorDomicilio['estado']) ? $ReceptorDomicilio['estado'].',' : '';
$receptor_pais = ($ReceptorDomicilio['pais']) ? $ReceptorDomicilio['pais'].'.' : '';

	
?>	
	<div class="modal-footer">
		<a role="button" data-log="14" class="log btn red-thunderbird" href="<?=$link_xml?>" >Descargar XML</a>
		<a role="button" data-log="13" class="log btn red-thunderbird" href="<?=$link_pdf?>" >Descargar PDF</a>
		<button type="button" class="btn blue" data-dismiss="modal" id="btn_cierra_modal">Cerrar</button>
	</div>

	<table width="780" border="0" cellpadding="0" cellspacing="0" class="f11" align="center">
		<tr>

			<td width="500" valign="top">
			    <table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
			    		<td width="400" height="150" valign="top">
			        		<p><b><?=$emisor_nombre?></b><br />
							<?=$domicilio_fiscal_calle?> <?=$domicilio_fiscal_nexterior?> <?=$domicilio_fiscal_colonia?> <?=$domicilio_fiscal_municipio?>  <?=$domicilio_fiscal_cp?><br />
							<?=$domicilio_fiscal_localidad?> <?=$domicilio_fiscal_estado?> <?=$domicilio_fiscal_pais?><br />
							<?=$emisor_rfc?></p>
							
							<p><b>Régimen Fiscal</b><br />
							<?=$regimen_fiscal?></p>
						</td>
					</tr>
				</table>
			</td>
			<td width="280" valign="top">
			    <table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="280" height="18" align="left" valign="bottom" ><p><b>FACTURA <?=$cfdiComprobante['serie']?><?=$cfdiComprobante['folio']?></b></p></td>
					</tr>
					<tr>
						<td height="24" valign="middle" class="titulos" >&nbsp; Fecha y hora de emisión</td>
					</tr>
					<tr>
						<td height="20" valign="top" ><?=$cfdiComprobante['fecha'];?></td>
					</tr>
					<tr>
						<td height="24" valign="middle" class="titulos" >&nbsp; Fecha y hora de certificacion</td>
					</tr>
					<tr>
						<td height="20" valign="top" ><?=$tfd['FechaTimbrado'];?></td>
					</tr>
					<tr>
						<td height="24" valign="middle" class="titulos" >&nbsp; Lugar</td>
					</tr>
					<tr>
						<td height="20" width="280" valign="top" ><?=$cfdiComprobante['LugarExpedicion'];?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	
	
	<table width="780" cellpadding="0" cellspacing="0" class="borde-azul f11" align="center">
	  	<tr>
	    	<td width="270" height="20" valign="middle" class="titulos">&nbsp;Receptor</td>
			<td width="230" height="20" valign="middle" class="titulos">&nbsp;Lugar de Expedicion</td>
			<td width="250" height="20" valign="middle" class="titulos">&nbsp;Datos Fiscales</td>
		</tr>
		<tr>
	    	<td width="270" height="100" valign="top" class="borde-der" style="padding-left: 5px;padding-right: 5px;padding-top: 5px;" >
			    <b><?=$receptor_nombre?></b><br />
				<?=$receptor_calle?> <?=$receptor_nexterior?> <?=$receptor_ninterior?><br />
				<?=$receptor_colonia?> <?=$receptor_localidad?> <?=$receptor_municipio?> <?=$receptor_cp?>
				<?=$receptor_estado?> <?=$receptor_pais?><br />
				<?=$receptor_rfc?>
			</td>
	    	<td width="230" height="100" valign="top" class="borde-der pad" style="padding-left: 5px;padding-top: 5px;">
			    <?=$domicilio_fiscal_calle?> <?=$domicilio_fiscal_nexterior?> <?=$domicilio_fiscal_colonia?> <?=$domicilio_fiscal_municipio?> <?=$domicilio_fiscal_cp?><br />
				<?=$domicilio_fiscal_localidad?> <?=$domicilio_fiscal_estado?> <?=$domicilio_fiscal_pais?>
			</td>
	    	<td width="240" height="100" valign="top" class="pad" style="padding-left: 5px;padding-right: 5px;padding-top: 5px;">
			    <b>Folio Sat</b><br />
				<?=$tfd['UUID']?><br />
				<b>Número de serie certificado emisor:</b><br />
				<?=$cfdiComprobante['noCertificado']?><br />
				<b>Número serie del certificado SAT:</b><br />
				<?=$tfd['noCertificadoSAT'];?>
			</td>
		</tr>
	</table>
<br>


<table width="780" cellpadding="0" cellspacing="0" class="borde-azul f11" align="center">
	<thead>
    	<tr class="titulos">
			<th width="55" height="25" class="f11" style="padding-left: 5px;">Cantidad</th>
			<th width="80" height="25" class="f11">Unidad</th>
			<th width="80" height="25" class="f11">Clave</th>
			<th width="290" height="25" class="f11">Descripción</th>
			<th width="130" height="25" class="f11" align="right" style="text-align: right">Precio</th>
			<th width="125" height="25" class="f11" align="right" style="padding-right: 5px;text-align: right">Importe</th>
		</tr>
	</thead>
	<tbody>
<? foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto') as $Concepto){ ?>
    	<tr>
			<td width="55" height="20" style="padding-left: 5px;"><?=$Concepto['cantidad']?></td>
		    <td width="80" height="20"><?=$Concepto['unidad']?></td>
			<td width="80" height="20">
			<? $noId = $Concepto['noIdentificacion'];
				
				$arrnoId = str_split($noId,13);
				foreach($arrnoId as $val): ?>
				 <?= $val ?><br/>
				<? endforeach; ?>
			</td>
			<td width="290" height="20"><?=$Concepto['descripcion']?></td>
			<td width="130" height="20" align="right" style="padding-right: 5px;"><?=number_format((double)$Concepto['valorUnitario'],2)?></td>
			<td width="125" height="20" align="right" style="padding-right: 5px;"><?=number_format((double)$Concepto['importe'],2)?></td>
		</tr>
<? } ?>		
	</tbody>
</table>
<br>
<table width="780" class="borde-azul" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td height="20" colspan="2" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="520" height="20" valign="middle" class="f11"><?=mb_strtoupper(NumLet($total),'UTF-8')?></td>
        </tr>
      
      
    </table></td>
    <td width="260" rowspan="2" valign="top"><table width="100%" style="border-left:#444d58 1px solid;" cellpadding="0" cellspacing="0">
      <tr>
        <td width="130" height="98" align="right" valign="top" class="f11" ><b>Subtotal:</b><br>
          <b>Descuentos:</b><br>
          <b>IVA <?if($cantidad_iva>0){?>16%:<?}else{?>0%<?}?></b><br>
          <? if($ret_isr>1): ?>
          <b><?=$Retenciones['impuesto']?>:</b><br>
          <? endif; ?>
        </td>
          
          <td width="130" align="right" valign="top" class="f11" style="padding-right: 5px;"><b><?=number_format($subtotal,2)?></b><br>
            <b><?=number_format($descuento,2)?></b><br>
			<b><?=number_format($cantidad_iva,2)?></b><br>
			<? if($ret_isr>1): ?>
			<b><?=number_format($ret_isr,2)?></b><br>
			<? endif; ?>
			</td>
        </tr>
      
      
      <tr>
        <td height="20" align="right" valign="middle" class="f11 titulos"><b>TOTAL:</b></td>
          <td align="right" valign="middle" class="f11 titulos" style="padding-right: 5px;"><b><?=number_format($total,2)?></b></td>
        </tr>
      
      
      
    </table></td>
  </tr>
  <tr>
    <td width="190" height="98" valign="top" class="f11">
	    	FORMA DE PAGO:<br />
			MÉTODO DE PAGO:<br />
			NÚMERO CUENTA DE PAGO:<br />
			TIPO DE COMPROBANTE:<br />
			CONDICIONES DE PAGO:<br />
			MOTIVO DE DESCUENTO:<br />
			MONEDA: <br /></td>
    <td width="330" valign="top" class="f11">
	    	<?=mb_strtoupper($cfdiComprobante['formaDePago'], 'UTF-8')?><br />
			<?=mb_strtoupper($cfdiComprobante['metodoDePago'], 'UTF-8')?><br />
			<?=$cfdiComprobante['NumCtaPago']?><br />
			<?=mb_strtoupper($cfdiComprobante['tipoDeComprobante'], 'UTF-8')?><br /> 
			<?=mb_strtoupper($cfdiComprobante['condicionesDePago'], 'UTF-8')?><br /> 
			<?=mb_strtoupper($cfdiComprobante['motivoDescuento'], 'UTF-8')?><br />
			<?=mb_strtoupper($cfdiComprobante['Moneda'], 'UTF-8')?></td>
  </tr>
</table>



<div class="modal-footer">

</div>

