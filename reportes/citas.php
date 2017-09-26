<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

date_default_timezone_set('America/Mexico_City');
setlocale(LC_MONETARY, 'Spanish_Mexican');


extract($_GET);
//print_r($_POST);
//if(!$id_canal) exit("No llego el identificador del canal");
/*$id_clinica="1";
$fecha1="2016-12-01";
$fecha2="2016-12-30";
*/
/*
|Canal:
| - Whatsapp	 5		   2               40%		        1
| - Facebook    3		   1               30%		        1
| - Email       1		   1               100%   	        1
| - Sin Canal   1		   1               100%			    0	
*/
//Cabecera
//Citas atendidas
$sql="SELECT id_cita FROM citas WHERE citas.tipo=1 AND id_clinica='$id_clinica' AND DATE(fecha_hora) BETWEEN '$fecha1' AND '$fecha2' AND atendida=1 AND activo=1";
$q=mysql_query($sql);
$citas_atendidas=mysql_num_rows($q);
//Citas totales
$sql="SELECT id_cita FROM citas WHERE citas.tipo=1 AND id_clinica='$id_clinica' AND DATE(fecha_hora) BETWEEN '$fecha1' AND '$fecha2' AND activo=1";
$q=mysql_query($sql);
$citas_totales=mysql_num_rows($q);
//Citas nuevas
$sql="SELECT id_cita, pacientes.fecha_registro FROM citas 
JOIN pacientes ON pacientes.id_paciente=citas.id_paciente
WHERE citas.tipo=1 AND id_clinica='$id_clinica' AND DATE(fecha_registro) BETWEEN '$fecha1' AND '$fecha2' AND citas.activo=1";
$q=mysql_query($sql);
$nuevas_citas=mysql_num_rows($q);

$porcentaje_general=($citas_atendidas*100)/$citas_totales;

//Citas CONFIRMADAS
$sql="SELECT id_cita FROM citas WHERE citas.tipo=1 AND id_clinica='$id_clinica' AND DATE(fecha_hora) BETWEEN '$fecha1' AND '$fecha2' AND confirmada = 1 AND activo=1";
$q=mysql_query($sql);
$confirmadas=mysql_num_rows($q);

//Citas CONFIRMADAS ATENDIDAS
$sql="SELECT id_cita FROM citas WHERE citas.tipo=1 AND id_clinica='$id_clinica' AND DATE(fecha_hora) BETWEEN '$fecha1' AND '$fecha2' AND confirmada = 1  AND atendida=1 AND activo=1";
$q=mysql_query($sql);
$confirmados_atendidos=mysql_num_rows($q);

$porcentaje_confirmados = ($confirmados_atendidos*100)/$confirmadas;

//Sacamos las consultas
$sql = "SELECT pacientes.fecha_registro, citas.atendida, citas.confirmada, canales.canal FROM citas
JOIN pacientes ON pacientes.id_paciente=citas.id_paciente
LEFT JOIN canales ON canales.id_canal=pacientes.id_canal
WHERE citas.tipo=1 AND citas.id_clinica='$id_clinica' AND DATE(citas.fecha_hora) BETWEEN '$fecha1' AND '$fecha2' AND citas.activo=1";

$q = mysql_query($sql);
$citas = array();
while($datos = mysql_fetch_object($q)):

	$citas[] = $datos;
	
endwhile;

$valida_citas = count($citas);

$citados = array();
$atendidas = array();
$citados = array();
$confirmados = array();
$atendidas_confirmados = array();

if($valida_citas):

	$cuenta = 7;

	require_once dirname(__FILE__) . '/Classes/PHPExcel.php';
	$cadena_fecha="DEL ".fechaLetra($fecha1)." AL ".fechaLetra($fecha2);
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getProperties()->setCreator("EPICMEDIA (www.peicmedia.pro)")
								 ->setLastModifiedBy("Epicmedia (www.epicmedia.pro)")
								 ->setTitle("REPORTE DE CITAS")
								 ->setSubject("Citas realizadas del".$fecha1." al ".$fecha2)
								 ->setDescription("Reporte generado por DENTISXA CRM potencializado por www.epicmedia.pro")
								 ->setKeywords("detinxa")
								 ->setCategory("citas");
	$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue('A1','CLÍNICA: '.dameClinica($id_clinica));
	
	$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue('A2','REPORTE DE CITAS');
            
	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A3', $cadena_fecha);            
	       
	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A5', 'MÉTODO')
	            ->setCellValue('B5', 'CITADOS')
	            ->setCellValue('C5', 'ATENDIDOS')
	            ->setCellValue('D5', 'ASISTENCIA')
	            ->setCellValue('E5', 'NUEVOS ATENDIDOS')
	            ->setCellValue('G5', 'CONFIRMADOS')
	            ->setCellValue('H5', 'ASISTENCIA CONFIRMADOS')
	            ->setCellValue('I5', 'CONFIRMADOS ATENDIDOS');
	//Info

	
	foreach($citas as $cita):

		if(!$cita->canal):
		  $cita->canal = 'DIRECTO';
		endif;
		
		$citados[$cita->canal]++;
		
		if($cita->atendida==1):
			$atendidas[$cita->canal]++;
		endif;
		
		if($cita->atendida==1 AND $cita->confirmada==1):
			$atendidas_confirmados[$cita->canal]++;
		endif;
		
		if($cita->confirmada==1):
			$confirmados[$cita->canal]++;
		endif;
	
		if(	($cita->fecha_registro>=$fecha1) AND ($cita->fecha_registro<=$fecha2) ):
			$nuevos[$cita->canal]++;
		endif;
	
		//echo "$canal CITADOS: $cit - ATENDIDOS: {$atendidas[$canal]} - NUEVOS: {$nuevos[$canal]}\n";
	
	endforeach;

	/*
	print_r($citados);
	print_r($atendidas);
	print_r($nuevos);
	*/
	//Generales
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A6', 'GENERAL')
		->setCellValue('B6', $citas_totales)
		->setCellValue('C6', $citas_atendidas)
		->setCellValue('D6', $porcentaje_general.'%')
		->setCellValue('E6', $nuevas_citas)
		->setCellValue('G6', $confirmadas)
		->setCellValue('H6', $porcentaje_confirmados.'%')
		->setCellValue('I6', $confirmados_atendidos);
		            
	foreach($citados as $canal => $cit):
		
		//echo "$canal CITADOS: $cit - ATENDIDOS: {$atendidas[$canal]} - NUEVOS: {$nuevos[$canal]}\n";
		$porcentaje_confirm_dato = 0;
		$porcentaje_dato = 0;
		$atendidos_dato = $atendidas[$canal];
		$confirmados_dato = $confirmados[$canal];
		$confirmados_atend_dato = $atendidas_confirmados[$canal];
		$nuevos_dato = $nuevos[$canal];
		if(($atendidos_dato)&&($nuevos_dato)):
			$porcentaje_dato =($atendidos_dato*100)/$cit;
		endif;
		
		if(($confirmados_dato)&&($confirmados_atend_dato)):
			$porcentaje_confirm_dato =($confirmados_atend_dato*100)/$confirmados_dato;
		endif;
		
		if(!$atendidos_dato) $atendidos_dato = '0';
		if(!$nuevos_dato) $nuevos_dato = '0';
		if(!$porcentaje_dato) $porcentaje_dato = '0';
		if(!$confirmados_dato) $confirmados_dato = '0';
		if(!$confirmados_atend_dato) $confirmados_atend_dato = '0';
		if(!$porcentaje_confirm_dato) $porcentaje_confirm_dato = '0';
		
		$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('A'.$cuenta, $canal)
		            ->setCellValue('B'.$cuenta, $cit)
		            ->setCellValue('C'.$cuenta, $atendidos_dato)
		            ->setCellValue('D'.$cuenta, $porcentaje_dato.'%')
		            ->setCellValue('E'.$cuenta, $nuevos_dato)
		            ->setCellValue('G'.$cuenta, $confirmados_dato)
		            ->setCellValue('H'.$cuenta, $porcentaje_confirm_dato.'%')
		            ->setCellValue('I'.$cuenta, $confirmados_atend_dato);
		$cuenta++;
		
	endforeach;



	//AQUI VA EL PEDO DE LOS FILTROS
	//$objPHPExcel->getActiveSheet()->setAutoFilter('A5:H5');
	
	//AQUI TERMINA LOS FILTROS
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BReporte de Citas&RDentixa CRM &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPágina &P of &N');
	$objPHPExcel->getActiveSheet()->getStyle('A5:I5')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A5:I5')->getFont()->setSize(12);

	$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getFont()->setSize(12);
	
	$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getFont()->setSize(12);
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
	//Tamaños de las celdas
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
	//Mezclamos Celdas
	$objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
	$objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
	$objPHPExcel->getActiveSheet()->mergeCells('A3:E3');
	$objPHPExcel->getActiveSheet()->mergeCells('A4:E4');
	//$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('citas');
	
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
	
	

	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="Reporte_de_citas_DENTIXA-CRM.xlsx"');
	header('Cache-Control: max-age=0');
	header('Cache-Control: max-age=1');
	
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
	header ('Cache-Control: cache, must-revalidate');
	header ('Pragma: public');
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
	exit;

	

else:
	echo "No hay consultas en los parametros seleccionados.";
endif;
