<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_GET);
//print_r($_POST);
if(!$id_clinica) exit("No llego el identificador de la clínica");
//$id_clinica="1";
//$fecha1="2016-12-01";
//$fecha2="2016-12-30";


//Sacamos las consultas
$sql="SELECT pacientes.id_canal, canales.canal, id_consulta FROM consultas
JOIN pacientes ON pacientes.id_paciente=consultas.id_paciente
LEFT JOIN canales ON canales.id_canal=pacientes.id_canal
WHERE consultas.id_clinica='$id_clinica' AND consultas.activo=1 AND DATE(consultas.fecha_hora) BETWEEN '$fecha1' AND '$fecha2'
ORDER BY id_canal ASC";
$q=mysql_query($sql);
$consultas=array();
while($datos=mysql_fetch_object($q)):
	$consultas[] = $datos;
endwhile;
$valida_consultas=count($consultas);

if($valida_consultas):
	//Ventas por Canal
	//Guardamos los datos en un arreglo
	foreach($consultas as $consulta):
		$id_consulta=$consulta->id_consulta;
		$id_canal=$consulta->id_canal;
		if(!$id_canal):
			$canal="DIRECTO";
		else:
			$canal=$consulta->canal;
		endif;
		
		$sql="SELECT SUM(precio) AS total FROM consultas_tratamientos WHERE id_consulta=$id_consulta AND activo=1";
		$q=mysql_query($sql);
		$dato=mysql_fetch_assoc($q);
//		echo $canal." ".$total_consulta=$dato['total']."<br>";
		$canal_arr[$canal]+=$dato['total'];
	endforeach;
	
	//Ventas por tratamiento
	$sql="SELECT tratamientos.tratamiento, SUM(consultas_tratamientos.precio) AS total FROM consultas_tratamientos
	JOIN consultas ON consultas.id_consulta=consultas_tratamientos.id_consulta
	JOIN tratamientos ON tratamientos.id_tratamiento=consultas_tratamientos.id_tratamiento
	WHERE consultas.id_clinica='$id_clinica' AND consultas.activo=1 AND DATE(consultas.fecha_hora) BETWEEN '$fecha1' AND '$fecha2'
	GROUP BY tratamientos.tratamiento";
	$q=mysql_query($sql);
	$tratamientos=array();
	while($datos=mysql_fetch_object($q)):
		$tratamientos[] = $datos;
	endwhile;
	
	
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
            	->setCellValue('A2','REPORTE DE VENTAS');
            
	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A3', $cadena_fecha);            
	       
	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A5', 'CANAL')
	            ->setCellValue('B5', 'TOTAL');
	//Canales            
	foreach($canal_arr as $canal => $total):
	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A'.$cuenta, $canal)
	            ->setCellValue('B'.$cuenta, money_format('%i',$total));
	$cuenta++;
	endforeach;

	$celdas_total=$cuenta-1;

	$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('A'.$cuenta, 'TOTAL')
		            ->setCellValue('B'.$cuenta, '=SUM(B6:B'.$celdas_total.')');
		            
		            
	//Tratamientos
	$titulo2=$cuenta+4;
	$p_total=$cuenta+5;
	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A'.$titulo2, 'TRATAMIENTO')
	            ->setCellValue('B'.$titulo2, 'TOTAL');
	$nueva_cuenta=$titulo2+1;            
	foreach($tratamientos as $tratamiento):
	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A'.$nueva_cuenta, $tratamiento->tratamiento)
	            ->setCellValue('B'.$nueva_cuenta, money_format('%i',$tratamiento->total));
	$nueva_cuenta++;
	endforeach;
	
	$celdas_total=$nueva_cuenta-1;

	$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('A'.$nueva_cuenta, 'TOTAL')
		            ->setCellValue('B'.$nueva_cuenta, '=SUM(B'.$p_total.':B'.$celdas_total.')');
	//AQUI VA EL PEDO DE LOS FILTROS
	//$objPHPExcel->getActiveSheet()->setAutoFilter('A5:H5');
	
	//AQUI TERMINA LOS FILTROS
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BReporte de Ventas&RDentixa CRM &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPágina &P of &N');
	$objPHPExcel->getActiveSheet()->getStyle('A5:C5')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A5:C5')->getFont()->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$titulo2.':C'.$titulo2)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$titulo2.':C'.$titulo2)->getFont()->setSize(12);
	
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
	$objPHPExcel->getActiveSheet()->setTitle('ventas');
	
	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	
	
	
	
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Logo');
	$objDrawing->setPath('./logo.png');
	$objDrawing->setCoordinates('E2');
	$objDrawing->setHeight(34);
	$objDrawing->getShadow()->setVisible(true);
	$objDrawing->getShadow()->setDirection(45);
	$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
	
	
	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="Reporte_de_ventas_DENTIXA-CRM.xlsx"');
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
	echo "No hay consultas en los parametros seleccionados.";
endif;
