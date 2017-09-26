<?
//include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");
date_default_timezone_set('America/Mexico_City');


//PRIMERO SACAMOS TODOS LOS CONSULTORIOS
$consultorios = mysql_query("SELECT nombre,email FROM pacientes WHERE activo = 1");

	$cuenta=6;
	
	
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	
	setlocale(LC_MONETARY, 'Spanish_Mexican');
	
	if (PHP_SAPI == 'cli')
	die('Solo se puede ejecutar desde el navegador');
	require_once dirname(__FILE__) . '/Classes/PHPExcel.php';
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getProperties()->setCreator("EPICMEDIA (www.epicmedia.pro)")
								 ->setLastModifiedBy("Epicmedia (www.epicmedia.pro)")
								 ->setTitle("NOMBRES Y EMAILS DE TODOS LOS PACIENTES")
								 ->setSubject("emails")
								 ->setDescription("Reporte generado por Dentisxa CRM potencializado por www.epicmedia.pro")
								 ->setKeywords("dentisxa")
								 ->setCategory("pacientes");
	
	$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue('A3','REPORTE DE TODOS LOS EMAILS DE LOS PACIENTES');
    $objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue('A4','NOMBRE');
    $objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue('B4','EMAIL');       	    

while($consultorio = mysql_fetch_assoc($consultorios)){
	
	
	$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue('A'.$cuenta,$consultorio['nombre']);
    $objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue('B'.$cuenta,$consultorio['email']);   
    
    $cuenta++;   	
    
}

	//AQUI TERMINA LOS FILTROS
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BReporte de Emails&RDentixa CRM &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPágina &P of &N');
	//$objPHPExcel->getActiveSheet()->getStyle('A'.$titulo2.':C'.$titulo2)->getFont()->setBold(true);
	//$objPHPExcel->getActiveSheet()->getStyle('A'.$titulo2.':C'.$titulo2)->getFont()->setSize(12);
	
	$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getFont()->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle('A4:C4')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A4:C4')->getFont()->setSize(12);
	
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
	//Tamaños de las celdas
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
	//Mezclamos Celdas
	$objPHPExcel->getActiveSheet()->mergeCells('A1:C2');
	$objPHPExcel->getActiveSheet()->mergeCells('A3:C3');
	//$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);*/
	
	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('Todos los Pacientes');
	
	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	
	
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Logo');
	$objDrawing->setPath('./logo.png');
	$objDrawing->setCoordinates('A1');
	$objDrawing->setHeight(34);
	$objDrawing->getShadow()->setVisible(true);
	$objDrawing->getShadow()->setDirection(45);
	$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
	
	
	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="Reporte_de_pacientes_email_DENTIXA-CRM.xlsx"');
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

