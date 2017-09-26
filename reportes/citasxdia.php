<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_GET);
//print_r($_POST);
if(!$id_clinica) exit("No llego el identificador de la clínica");
//$id_clinica="1";
$fecha1=strtotime($fecha1);
$fecha2=strtotime($fecha2);
	
	
	$cuenta=6;
	
	
	date_default_timezone_set('America/Mexico_City');
	
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
            	->setCellValue('A2','REPORTE DE CITAS POR DÍA');
            
	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A3', $cadena_fecha);            
	       
	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A5', 'CANAL')
	            ->setCellValue('B5', 'TOTAL');
	
	for($i=$fecha1; $i <= $fecha2; $i = $i + 86400){
		$fecha=date('Y-m-d',$i);
		
		$sql="SELECT id_cita FROM citas WHERE citas.tipo=1 AND DATE(fecha_hora) = '$fecha' AND activo=1";
		$q=mysql_query($sql);
		$cuantos=mysql_num_rows($q);
	
		$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('A'.$cuenta, fechaLetraDos($fecha))
		            ->setCellValue('B'.$cuenta, $cuantos);
		$cuenta++;
	}

	$celdas_total=$cuenta-1;

	
		            
	//AQUI VA EL PEDO DE LOS FILTROS
	//$objPHPExcel->getActiveSheet()->setAutoFilter('A5:H5');
	
	//AQUI TERMINA LOS FILTROS
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BReporte de Citas&RDentixa CRM &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPágina &P of &N');
	$objPHPExcel->getActiveSheet()->getStyle('A5:C5')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A5:C5')->getFont()->setSize(12);
	
	$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->getFont()->setSize(12);
	
	$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getFont()->setSize(12);
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
	//Tamaños de las celdas
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
	//Mezclamos Celdas
	$objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
	$objPHPExcel->getActiveSheet()->mergeCells('A2:C2');
	$objPHPExcel->getActiveSheet()->mergeCells('A3:C3');
	$objPHPExcel->getActiveSheet()->mergeCells('A4:C4');
	//$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('citasxdia');
	
	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	
	
	
	
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Logo');
	$objDrawing->setPath('./logo.png');
	$objDrawing->setCoordinates('C2');
	$objDrawing->setHeight(34);
	$objDrawing->getShadow()->setVisible(true);
	$objDrawing->getShadow()->setDirection(45);
	$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
	
	
	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="Reporte_de_citas_dia_DENTIXA-CRM.xlsx"');
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
	