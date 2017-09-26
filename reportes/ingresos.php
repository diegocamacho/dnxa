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
$sql="SELECT books_ingresos.*, books_clientes.cliente,books_metodo_pago.metodo_pago,books_tipos_ingreso.cuenta_ingreso,id_cuenta_emisora,id_consulta, books_ingresos.activo FROM books_ingresos 
LEFT JOIN books_clientes ON books_clientes.id_cliente=books_ingresos.id_cliente
LEFT JOIN books_metodo_pago ON books_metodo_pago.id_metodo_pago=books_ingresos.id_metodo_pago
JOIN books_tipos_ingreso ON books_tipos_ingreso.id_tipo_ingreso=books_ingresos.id_tipo_ingreso
WHERE id_cuenta=$id_cuenta AND DATE(fecha_ingreso) BETWEEN '$fecha1' AND '$fecha2'";	
$q=mysql_query($sql);
$ingresos=array();
while($datos=mysql_fetch_object($q)):
	$ingresos[] = $datos;
endwhile;
$valida_ingresos=count($ingresos);
if($valida_ingresos):
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
            	->setCellValue('A2','REPORTE DE INGRESOS');
            
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
	            ->setCellValue('E5', 'CANCELACIONES')
	            ->setCellValue('F5', 'INGRESOS');

	foreach($ingresos as $ingreso):
		if($ingreso->activo==0):
			$monto="";
			$monto_cancelado=money_format('%i',$ingreso->monto);
		else:
			$monto_cancelado="";
			$monto=money_format('%i',$ingreso->monto);
		endif;
	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A'.$cuenta, fechaLetraDos($ingreso->fecha_ingreso))
	            ->setCellValue('B'.$cuenta, $ingreso->cuenta_ingreso)
	            ->setCellValue('C'.$cuenta, $ingreso->metodo_pago)
	            ->setCellValue('D'.$cuenta, $ingreso->referencia)
	            ->setCellValue('E'.$cuenta, $monto_cancelado)
	            ->setCellValue('F'.$cuenta, $monto);
	$cuenta++;
	endforeach;
	
	$celdas_total=$cuenta-1;
	
	$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('E'.$cuenta, '=SUM(E6:E'.$celdas_total.')')
		            ->setCellValue('F'.$cuenta, '=SUM(F6:F'.$celdas_total.')');
	
	//AQUI VA EL PEDO DE LOS FILTROS
	$objPHPExcel->getActiveSheet()->setAutoFilter('A5:C5');
	
	//AQUI TERMINA LOS FILTROS
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BReporte de Ingresos&RDentixa BOOKS &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPágina &P of &N');
	$objPHPExcel->getActiveSheet()->getStyle('A5:F5')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A5:F5')->getFont()->setSize(12);
	
	$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->getFont()->setSize(12);
	
	$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getFont()->setSize(12);
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
	//Tamaños de las celdas
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(100);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
	//Mezclamos Celdas
	$objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
	$objPHPExcel->getActiveSheet()->mergeCells('A2:C2');
	$objPHPExcel->getActiveSheet()->mergeCells('A3:C3');
	//$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('ingresos');
	
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
	header('Content-Disposition: attachment;filename="Reporte_de_ingresos_DENTIXA-BOOKS.xlsx"');
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
	echo "No hay ingresos en los parametros seleccionados.";
endif;