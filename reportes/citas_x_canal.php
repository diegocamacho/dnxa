<?
set_time_limit(0);
include("../includes/db.php");
include("../includes/funciones.php");
date_default_timezone_set('America/Mexico_City');

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

//PRIMERO SACAMOS TODOS LOS CONSULTORIOS
$consultorios = mysql_query("SELECT * FROM clinicas WHERE activo=1");

$tipos_canales = mysql_query("SELECT * FROM canales WHERE activo = 1");
$canales = array();
while($gt = mysql_fetch_assoc($tipos_canales)){
	array_push($canales, array("id"=>$gt['id_canal'],"nom"=>$gt['canal']));
}


	$str = 'A';
	$cuenta=9;
	$original = 9;
	$f_totales = array();
	$formulas_t = array();
	
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	
	setlocale(LC_MONETARY, 'Spanish_Mexican');
	
	if (PHP_SAPI == 'cli')
	die('Solo se puede ejecutar desde el navegador');
	require_once dirname(__FILE__) . '/Classes/PHPExcel.php';
	$cadena_fecha="DEL ".fechaLetra($fecha1)." AL ".fechaLetra($fecha2);
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getProperties()->setCreator("EPICMEDIA (www.epicmedia.pro)")
								 ->setLastModifiedBy("Epicmedia (www.epicmedia.pro)")
								 ->setTitle("REPORTE DE CITAS NUEVAS POR CANAL")
								 ->setSubject("Citas nuevas agendadas realizadas del".$fecha1." al ".$fecha2)
								 ->setDescription("Reporte generado por Dentisxa CRM potencializado por www.epicmedia.pro")
								 ->setKeywords("dentisxa")
								 ->setCategory("citas");
	
	$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue('A3','REPORTE DE CITAS NUEVAS POR CANAL');
            	
    $objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A4', $cadena_fecha);    

while($consultorio = mysql_fetch_assoc($consultorios)){
	
	$id_clinica = $consultorio['id_clinica'];	
	$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($str.'6','CLÍNICA: '.dameClinica($id_clinica));
    $objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($str.'7','CITAS NUEVAS AGENDADAS:');
    $objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($str.'8','FECHA');      	
    $tit_1 = $str;
	$str = ++$str;
	for($y = 0; $y < count($canales); $y++){
		$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($str.'8',$canales[$y]['nom']);      	
	    $titulos[] = $str;
		$str = ++$str;
	}
	//$str = ++$str;

	
	//CREAR EL ARRAY DE LOS DIAS PARA PODER IR AGREGANDOLOS AL DIA QUE CORRESPONDE
	for($i = 0;$i < count($dias);$i++){	
		array_push($f_totales,"=");
	}
	
	//CONSULTAMOS POR CADA UNO DE LOS DIAS QUE HAY EN LA LISTA
	for($i = 0;$i < count($dias);$i++){				
		$dia_actual = $dias[$i];
		
				
		$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($tit_1.$cuenta,fechaLetraDos($dia_actual)); 
		//PACIENTES POR DIA POR CADA UNO DE LOS CANALES
		for($x = 0; $x < count($canales); $x++){
			$paciente_dia = 0;
			$id_canal = $canales[$x]['id'];
			
			//SACAMOS LOS PACIENTES QUE FUERON ATENDIDOS EN LA CLINICA ESE DIA 
			$pacientes_clinica = mysql_query("SELECT DISTINCT(citas.id_paciente),pacientes.id_canal FROM citas 
											JOIN pacientes ON pacientes.id_paciente = citas.id_paciente
											WHERE id_clinica = '$id_clinica' AND DATE(fecha_hora) = '$dia_actual' AND pacientes.id_canal='$id_canal' AND citas.tipo=1 AND citas.activo=1");
			
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
    for($x = 0; $x < count($canales); $x++){
	    $objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue($titulos[$x].$cuenta, '=SUM('.$titulos[$x].'9:'.$titulos[$x].''.$celdas_total.')');
    }

	$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue($str.'8', 'TOTAL');
	for($i = 0;$i < count($dias);$i++){
		for($x = 0; $x < count($canales); $x++){
		    $objPHPExcel->setActiveSheetIndex(0)
			            ->setCellValue($str.$original, '='.substr($f_totales[$i],0,-1));			
	    }
	    $original++;
	}
	
	$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue($str.$cuenta, '=SUM('.$str.'9:'.$str.''.$celdas_total.')');

	
	
	$str = ++$str;
	$str = ++$str;
	$cuenta = 9;
	$original = 9;
	unset($titulos);
}
	
		
	

	//AQUI TERMINA LOS FILTROS
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BReporte de Cita&RDentixa CRM &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPágina &P of &N');
	$objPHPExcel->getActiveSheet()->getStyle('A6:'.$str.'6')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A6:'.$str.'6')->getFont()->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle('A7:'.$str.'7')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A7:'.$str.'7')->getFont()->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle('A8:'.$str.'8')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A8:'.$str.'8')->getFont()->setSize(12);
	//$objPHPExcel->getActiveSheet()->getStyle('A'.$titulo2.':C'.$titulo2)->getFont()->setBold(true);
	//$objPHPExcel->getActiveSheet()->getStyle('A'.$titulo2.':C'.$titulo2)->getFont()->setSize(12);
	
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
	$objPHPExcel->getActiveSheet()->setTitle('Citas por Canal');
	
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
	header('Content-Disposition: attachment;filename="Reporte_de_citas_canales_DENTIXA-CRM.xlsx"');
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

/*
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
		
		$sql="SELECT SUM(precio) AS total FROM consultas_tratamientos WHERE id_consulta=$id_consulta";
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
	

		
	
	

else:
	echo "No hay consultas en los parámetros seleccionados.";
endif;
*/