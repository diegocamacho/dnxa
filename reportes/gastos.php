<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_GET);
//print_r($_POST);
if(!$id_clinica) exit("No llego el identificador de la clínica");
if(!$id_cuenta) exit("No llego el identificador de la cuenta");
//if(!$id_metodo_pago) exit("Seleccione un metódo de pago válido");
if(!$fecha1) exit("Seleccione una fecha de inicio");
if(!$fecha2) exit("Seleccione una fecha final");
//$id_clinica="1";
//$fecha1="2016-12-01";
//$fecha2="2016-12-30";

//Sacamos ingresos
$sql="SELECT id_gasto, proveedor, metodo_pago,cuenta_gasto, fecha_gasto, monto, referencia,id_cuenta_receptora,books_gastos.activo  FROM books_gastos
LEFT JOIN books_proveedores ON books_proveedores.id_proveedor=books_gastos.id_proveedor
JOIN books_cuentas ON books_cuentas.id_cuenta=books_gastos.id_cuenta
LEFT JOIN books_metodo_pago ON books_metodo_pago.id_metodo_pago=books_gastos.id_metodo_pago
JOIN books_tipos_gasto ON books_tipos_gasto.id_tipo_gasto=books_gastos.id_tipo_gasto
WHERE books_gastos.id_cuenta=$id_cuenta AND DATE(fecha_gasto) BETWEEN '$fecha1' AND '$fecha2'";	
$q=mysql_query($sql);
$gastos=array();
while($datos=mysql_fetch_object($q)):
	$gastos[] = $datos;
endwhile;
$valida_gastos=count($gastos);
if($valida_gastos):
	//exit("Ok");
	$cuenta=6;
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	date_default_timezone_set('America/Mexico_City');
	setlocale(LC_MONETARY, 'Spanish_Mexican');
	
	if (PHP_SAPI == 'cli')
	die('Solo se puede ejecutar desde el navegador');
	require_once dirname(__FILE__) . '/Classes/PHPExcel.php';
	$cadena_fecha="DEL ".fechaLetra($fecha1)." AL ".fechaLetra($fecha2);
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getProperties()->setCreator("EPICMEDIA (www.peicmedia.pro)")
								 ->setLastModifiedBy("Epicmedia (www.epicmedia.pro)")
								 ->setTitle("REPORTE DE VENTAS")
								 ->setSubject("Ventas realizadas del".$fecha1." al ".$fecha2)
								 ->setDescription("Reporte generado por dentixa CRM potencializado por www.epicmedia.pro")
								 ->setKeywords("detinxa")
								 ->setCategory("ventas");
	$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue('A1','CLÍNICA: '.dameClinica($id_clinica));
	
	$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue('A2','REPORTE DE GASTOS');
            
	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A3', $cadena_fecha);
	/*
	$celdas_total=$cuenta-1;

	$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('A'.$cuenta, 'TOTAL')
		            ->setCellValue('B'.$cuenta, '=SUM(B6:B'.$celdas_total.')');
	*/	            
		            
	//Ingresos
	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A5', 'FECHA')
	            ->setCellValue('B5', 'CONCEPTO')
	            ->setCellValue('C5', 'MÉTODO DE PAGO')
	            ->setCellValue('D5', 'DESCRIPCIÓN')
	            ->setCellValue('E5', 'PROVEEDOR')
	            ->setCellValue('F5', 'CANCELACIONES')
	            ->setCellValue('G5', 'GASTOS');

	foreach($gastos as $gasto):
		if($gasto->activo==0):
			$monto="";
			$monto_cancelado=money_format('%i',$gasto->monto);
		else:
			$monto_cancelado="";
			$monto=money_format('%i',$gasto->monto);
		endif;
	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A'.$cuenta, fechaLetraDos($gasto->fecha_gasto))
	            ->setCellValue('B'.$cuenta, $gasto->cuenta_gasto)
	            ->setCellValue('C'.$cuenta, $gasto->metodo_pago)
	            ->setCellValue('D'.$cuenta, $gasto->referencia)
	            ->setCellValue('E'.$cuenta, $gasto->proveedor)
	            ->setCellValue('F'.$cuenta, $monto_cancelado)
	            ->setCellValue('G'.$cuenta, $monto);
	$cuenta++;
	endforeach;
	
	$celdas_total=$cuenta-1;
	
	$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('F'.$cuenta, '=SUM(F6:F'.$celdas_total.')')
		            ->setCellValue('G'.$cuenta, '=SUM(G6:G'.$celdas_total.')');
	
	//AQUI VA EL PEDO DE LOS FILTROS
	$objPHPExcel->getActiveSheet()->setAutoFilter('A5:C5');
	
	//AQUI TERMINA LOS FILTROS
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BReporte de Gastos&RDentixa BOOKS &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPágina &P of &N');
	$objPHPExcel->getActiveSheet()->getStyle('A5:G5')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A5:G5')->getFont()->setSize(12);
	
	$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->getFont()->setSize(12);
	
	$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getFont()->setSize(12);
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
	//Tamaños de las celdas
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(40);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
	//Mezclamos Celdas
	$objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
	$objPHPExcel->getActiveSheet()->mergeCells('A2:C2');
	$objPHPExcel->getActiveSheet()->mergeCells('A3:C3');
	//$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('gastos');
	
	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	
	
	
	
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Logo');
	$objDrawing->setPath('./logo.png');
	$objDrawing->setCoordinates('F2');
	$objDrawing->setHeight(34);
	$objDrawing->getShadow()->setVisible(true);
	$objDrawing->getShadow()->setDirection(45);
	$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
	
	
	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="Reporte_de_gastos_DENTIXA-BOOKS.xlsx"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');
	
	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
	exit;
	
	
	

else:
	echo "No hay gastos en los parametros seleccionados.";
endif;