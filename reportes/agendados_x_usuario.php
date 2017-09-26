<?
set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/Mexico_City');
setlocale(LC_MONETARY, 'Spanish_Mexican');

include("../includes/db.php");
include("../includes/funciones.php");

$tiempo_inicio = microtime(true);

extract($_GET);
//print_r($_POST);
if(!$fecha1) exit("Debe seleccionar fecha inicial");
if(!$fecha2) exit("Debe seleccionar fecha final");
$dias = array();
//CHECAMOS SI HAY DIAS DE DIFERENCIA
if($fecha1 != $fecha2){
	//ESTE CICLO VA A IR AGREGANDO FECHA POR FECHA HASTA QUE NOS ACABEMOS LOS DIAS 
	$begin = new DateTime($fecha1);
	$end = new DateTime($fecha2);
	$interval = new DateInterval('P1D');
	$period = new DatePeriod($begin, $interval, $end);

	foreach ( $period as $dt ) {
		array_push($dias, $dt->format('Y-m-d'));
	}
	array_push($dias, $end->format('Y-m-d')); //HAY QUE AGREGAR EL ULTIMO DIA
}else{
	$dias[0] = date("Y-m-d",strtotime($fecha1)); //SI ES IGUAL PUES SOLO HAY UN DIA QUE HAY QUE JALAR 
}

$t_consultorios = mysql_query("SELECT * FROM clinicas WHERE activo = 1");
$consultorios = array();
while($gt = mysql_fetch_assoc($t_consultorios)){
	array_push($consultorios, array("id"=>$gt['id_clinica'],"nom"=>$gt['clinica']));
}

$tipos_usuarios = mysql_query("SELECT * FROM usuarios WHERE activo = 1 AND id_usuario != 2");
$usuarios = array();
while($gt = mysql_fetch_assoc($tipos_usuarios)){
	array_push($usuarios, array("id"=>$gt['id_usuario'],"nom"=>$gt['nombre']));
}

	$str = 'A';
	$cuenta=9;
	$original = 9;
	$f_totales = array();
	$f_totales_n = array();
	

	
	if (PHP_SAPI == 'cli')
	die('Solo se puede ejecutar desde el navegador');
	require_once dirname(__FILE__) . '/Classes/PHPExcel.php';
	$cadena_fecha="DEL ".fechaLetra($fecha1)." AL ".fechaLetra($fecha2);
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getProperties()->setCreator("EPICMEDIA (www.epicmedia.pro)")
								 ->setLastModifiedBy("Epicmedia (www.epicmedia.pro)")
								 ->setTitle("REPORTE DE PACIENTES AGENDADOS POR USUARIO")
								 ->setSubject("Pacientes agendados por Usuario del ".$fecha1." al ".$fecha2)
								 ->setDescription("Reporte generado por Dentisxa CRM potencializado por www.epicmedia.pro")
								 ->setKeywords("dentisxa")
								 ->setCategory("pacientes");
	
	$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue('A3','REPORTE DE PACIENTES AGENDADOS POR USUARIO');
            	
    $objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A4', $cadena_fecha);  
	$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue('A6','PACIENTES AGENDADOS');  

for($y = 0; $y < count($consultorios); $y++){
	$id_clinica = $consultorios[$y]['id'];
	$cuenta = 9;
	$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($str.'7',$consultorios[$y]['nom']);
    $objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($str.'8','FECHA');      	
    $tit_1 = $str;
	$str = ++$str;
	for($z = 0; $z < count($usuarios); $z++){
		$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($str.'8',$usuarios[$z]['nom']);      	
	    $titulos[] = $str;
		$str = ++$str;
	}
	
	//CREAR EL ARRAY DE LOS DIAS PARA PODER IR AGREGANDOLOS AL DIA QUE CORRESPONDE
	for($i = 0;$i < count($dias);$i++){	
		array_push($f_totales,"=");
		array_push($f_totales_n,"=");
	}

	for($i = 0;$i < count($dias);$i++){	
	         	
	//CONSULTAMOS POR CADA UNO DE LOS DIAS QUE HAY EN LA LISTA				
		$dia_actual = $dias[$i];		
				
		$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($tit_1.$cuenta,fechaLetraDos($dia_actual)); 
		//PACIENTES POR DIA POR CADA UNO DE LOS usuarios
		for($x = 0; $x < count($usuarios); $x++){
			$paciente_dia = 0;
			$id_usuario = $usuarios[$x]['id'];
			
			//SACAMOS LOS PACIENTES QUE FUERON ATENDIDOS EN LA CLINICA ESE DIA 
			$pacientes_clinica = mysql_query("SELECT id_cita FROM citas WHERE id_clinica = '$id_clinica' AND DATE(fecha_hora_creacion) = '$dia_actual' AND id_usuario_agendo='$id_usuario' AND tipo=1");
			
			$paciente_dia = mysql_num_rows($pacientes_clinica);

			
			$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($titulos[$x].$cuenta,$paciente_dia); 
            
			$f_totales[$i] .= $titulos[$x].$cuenta."+";
		}
		$cuenta++;
	}
	
	$celdas_total=$cuenta-1;
	$cuenta += 2;

	$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue($tit_1.$cuenta, 'TOTAL');
    for($x = 0; $x < count($usuarios); $x++){
	    $objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue($titulos[$x].$cuenta, '=SUM('.$titulos[$x].'9:'.$titulos[$x].''.$celdas_total.')');
    }
    $str = ++$str;
    $titulos = array();
}
	

// 	AQUI VAN LOS TOTALES
	$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($str.'8','TOTAL');    	
	for($i = 0;$i < count($dias);$i++){
		for($x = 0; $x < count($usuarios); $x++){
		    $objPHPExcel->setActiveSheetIndex(0)
			            ->setCellValue($str.$original, '='.substr($f_totales[$i],0,-1));			
	    }
	    $original++;
	}
	
	
		
	$str = ++$str;
	$str = ++$str;
	$cuenta = 9;

	//AQUI TERMINA LOS FILTROS
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BReporte de Pacientes&RDentixa CRM &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPágina &P of &N');
	$objPHPExcel->getActiveSheet()->getStyle('A6:'.$str.'6')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A6:'.$str.'6')->getFont()->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle('A7:'.$str.'7')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A7:'.$str.'7')->getFont()->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle('A8:'.$str.'8')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A8:'.$str.'8')->getFont()->setSize(12);

	
	$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getFont()->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle('A4:C4')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A4:C4')->getFont()->setSize(12);
	
	$objPHPExcel->getActiveSheet()->getStyle('A5:C5')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A5:C5')->getFont()->setSize(12);
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
	//Tamaños de las celdas
	$str_2 = 'A';
	while($str_2 != $str){
		$objPHPExcel->getActiveSheet()->getColumnDimension($str_2)->setWidth(15);
		++$str_2;
	}
	//Mezclamos Celdas
	$objPHPExcel->getActiveSheet()->mergeCells('A1:C2');
	$objPHPExcel->getActiveSheet()->mergeCells('A3:C3');
	$objPHPExcel->getActiveSheet()->mergeCells('A4:C4');
	//$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);*/
	
	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('Pacientes Agendados Usuario');
	
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
	header('Content-Disposition: attachment;filename="Reporte_de_agendados_usuario_DENTIXA-CRM.xlsx"');
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

