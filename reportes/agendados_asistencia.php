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

$t_consultorios = mysql_query("SELECT * FROM clinicas WHERE activo = 1");
$consultorios = array();
while($gt = mysql_fetch_assoc($t_consultorios)){
	array_push($consultorios, array("id"=>$gt['id_clinica'],"nom"=>$gt['clinica']));
}

	$str = 'A';
	$cuenta=8;
	$original = 8;
	$f_totales = array();
	$f_totales_n = array();
	$f_totales_c = array();
	
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
								 ->setTitle("REPORTE DE PACIENTES AGENDADOS")
								 ->setSubject("Pacientes agendados del".$fecha1." al ".$fecha2)
								 ->setDescription("Reporte generado por Dentisxa CRM potencializado por www.epicmedia.pro")
								 ->setKeywords("dentisxa")
								 ->setCategory("pacientes");
	
	$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue('A3','REPORTE DE PACIENTES AGENDADOS');
            	
    $objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A4', $cadena_fecha);  
	$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue('A6','PACIENTES AGENDADOS');  

    $objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($str.'7','FECHA');      	
    $tit_1 = $str;
	$str = ++$str;
	for($y = 0; $y < count($consultorios); $y++){
		$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($str.'7',$consultorios[$y]['nom']);      	
	    $titulos[] = $str;
		$str = ++$str;
	}
	
	//CREAR EL ARRAY DE LOS DIAS PARA PODER IR AGREGANDOLOS AL DIA QUE CORRESPONDE
	for($i = 0;$i < count($dias);$i++){	
		array_push($f_totales,"=");
		array_push($f_totales_n,"=");
		array_push($f_totales_c,"=");
	}

for($i = 0;$i < count($dias);$i++){	
	         	
	//CONSULTAMOS POR CADA UNO DE LOS DIAS QUE HAY EN LA LISTA				
		$dia_actual = $dias[$i];		
				
		$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($tit_1.$cuenta,fechaLetraDos($dia_actual)); 
		//PACIENTES POR DIA POR CADA UNO DE LOS CONSULTORIOS
		for($x = 0; $x < count($consultorios); $x++){
			$paciente_dia = 0;
			$id_clinica = $consultorios[$x]['id'];
			
			//SACAMOS LOS PACIENTES QUE FUERON ATENDIDOS EN LA CLINICA ESE DIA 
			$pacientes_clinica = mysql_query("SELECT DISTINCT(citas.id_cita) FROM citas 
											WHERE id_clinica = '$id_clinica' AND DATE(fecha_hora) = '$dia_actual' AND citas.tipo=1 AND citas.activo=1");
			
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
    for($x = 0; $x < count($consultorios); $x++){
	    $objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue($titulos[$x].$cuenta, '=SUM('.$titulos[$x].'7:'.$titulos[$x].''.$celdas_total.')');
    }

	
	//APARTIR DE AQUI SON LOS QUE ASISTIERON
	$cuenta += 4;
	
	$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($tit_1.$cuenta,'PACIENTES QUE ASISTIERON');
    $cuenta++;
    $objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($tit_1.$cuenta,'FECHA');
    
    for($y = 0; $y < count($consultorios); $y++){
		$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($titulos[$y].$cuenta,$consultorios[$y]['nom']);      	
	}
	 $cuenta++;
	 $nuevos = $cuenta;
    
    //CONSULTAMOS POR CADA UNO DE LOS DIAS QUE HAY EN LA LISTA
	for($i = 0;$i < count($dias);$i++){				
		$dia_actual = $dias[$i];
		
				
		$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($tit_1.$cuenta,fechaLetraDos($dia_actual)); 
		//PACIENTES POR DIA POR CADA UNO DE LOS consultorios
		for($x = 0; $x < count($consultorios); $x++){
			$paciente_dia = 0;
			$id_clinica = $consultorios[$x]['id'];
			
			//SACAMOS LOS PACIENTES QUE FUERON ATENDIDOS EN LA CLINICA ESE DIA 
			$pacientes_clinica = mysql_query("SELECT DISTINCT(id_cita) FROM citas 
											WHERE id_clinica = '$id_clinica' AND DATE(fecha_hora) = '$dia_actual' AND citas.tipo=1 AND citas.activo=1");
			
			while($paciente = mysql_fetch_assoc($pacientes_clinica)){
				$id_cita = $paciente['id_cita'];
				$check = mysql_num_rows(mysql_query("SELECT * FROM consultas WHERE id_cita = '$id_cita'"));
				if($check == 1){
					$paciente_dia++;
				}
			}

			
			$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($titulos[$x].$cuenta,$paciente_dia); 
            	
            $f_totales_n[$i] .= $titulos[$x].$cuenta."+";	
       
		}
				
		$cuenta++;
	}
	
	$celdas_total=$cuenta-1;
	$cuenta += 2;

	$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue($tit_1.$cuenta, 'TOTAL');
    for($x = 0; $x < count($consultorios); $x++){
	    $objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue($titulos[$x].$cuenta, '=SUM('.$titulos[$x].$nuevos.':'.$titulos[$x].''.$celdas_total.')');
    }
    
    //APARTIR DE AQUI SON LOS QUE CONFIRMARON
	$cuenta += 4;
	
	$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($tit_1.$cuenta,'PACIENTES CONFIRMADOS');
    $cuenta++;
    $objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($tit_1.$cuenta,'FECHA');
    
    for($y = 0; $y < count($consultorios); $y++){
		$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($titulos[$y].$cuenta,$consultorios[$y]['nom']);      	
	}
	 $cuenta++;
	 $confirm = $cuenta;
    
    //CONSULTAMOS POR CADA UNO DE LOS DIAS QUE HAY EN LA LISTA
	for($i = 0;$i < count($dias);$i++){				
		$dia_actual = $dias[$i];
		
				
		$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($tit_1.$cuenta,fechaLetraDos($dia_actual)); 
		//PACIENTES POR DIA POR CADA UNO DE LOS consultorios
		for($x = 0; $x < count($consultorios); $x++){
			$paciente_dia = 0;
			$id_clinica = $consultorios[$x]['id'];
			
			//SACAMOS LOS PACIENTES QUE FUERON CONFIRMADOS EN LA CLINICA ESE DIA 
			$pacientes_clinica = mysql_query("SELECT DISTINCT(citas.id_cita) FROM citas 
											WHERE id_clinica = '$id_clinica' AND DATE(fecha_hora) = '$dia_actual' AND citas.tipo=1 AND citas.activo=1 AND citas.confirmada = 1");
			
			$paciente_dia = mysql_num_rows($pacientes_clinica);

			
			$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($titulos[$x].$cuenta,$paciente_dia); 
            	
            $f_totales_c[$i] .= $titulos[$x].$cuenta."+";	
       
		}
				
		$cuenta++;
	}
	
	$celdas_total=$cuenta-1;
	$cuenta += 2;

	$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue($tit_1.$cuenta, 'TOTAL');
    for($x = 0; $x < count($consultorios); $x++){
	    $objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue($titulos[$x].$cuenta, '=SUM('.$titulos[$x].$confirm.':'.$titulos[$x].''.$celdas_total.')');
    }

	
	
	$cuenta = 8;
	unset($titulos);


// 	AQUI VAN LOS TOTALES DE AGENDADOS, ASISTIERON Y CONFIRMADOS
	$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($str.'7','TOTAL');    	
	for($i = 0;$i < count($dias);$i++){
		for($x = 0; $x < count($consultorios); $x++){
		    $objPHPExcel->setActiveSheetIndex(0)
			            ->setCellValue($str.$original, '='.substr($f_totales[$i],0,-1));			
	    }
	    $original++;
	}
	
	$cuenta = $nuevos-1;
	
	$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($str.$cuenta,'TOTAL');  
    $cuenta++;  	
	for($i = 0;$i < count($dias);$i++){
		for($x = 0; $x < count($consultorios); $x++){
		    $objPHPExcel->setActiveSheetIndex(0)
			            ->setCellValue($str.$cuenta, '='.substr($f_totales_n[$i],0,-1));			
	    }
	    $cuenta++;
	}
	
	$cuenta = $confirm-1;
	
	$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($str.$cuenta,'TOTAL');  
    $cuenta++;  	
	for($i = 0;$i < count($dias);$i++){
		for($x = 0; $x < count($consultorios); $x++){
		    $objPHPExcel->setActiveSheetIndex(0)
			            ->setCellValue($str.$cuenta, '='.substr($f_totales_c[$i],0,-1));			
	    }
	    $cuenta++;
	}
		
	$str = ++$str;
	$str = ++$str;
	$cuenta = 8;
	
	//**************************  APARTIR DE AQUI VA EL DESMADRE DE LOS NUEVOS ********************************** //
	$f_totales = array();
	$f_totales_n = array();
	$f_totales_c = array();
	
	$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($str.'6','PACIENTES NUEVOS AGENDADOS');  

    $objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($str.'7','FECHA');      	
    $tit_1 = $str;
	$str = ++$str;
	for($y = 0; $y < count($consultorios); $y++){
		$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($str.'7',$consultorios[$y]['nom']);      	
	    $titulos[] = $str;
		$str = ++$str;
	}
	
	//CREAR EL ARRAY DE LOS DIAS PARA PODER IR AGREGANDOLOS AL DIA QUE CORRESPONDE
	for($i = 0;$i < count($dias);$i++){	
		array_push($f_totales,"=");
		array_push($f_totales_n,"=");
		array_push($f_totales_c,"=");
	}

for($i = 0;$i < count($dias);$i++){	
	         	
	//CONSULTAMOS POR CADA UNO DE LOS DIAS QUE HAY EN LA LISTA				
		$dia_actual = $dias[$i];		
				
		$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($tit_1.$cuenta,fechaLetraDos($dia_actual)); 
		//PACIENTES POR DIA POR CADA UNO DE LOS CONSULTORIOS
		for($x = 0; $x < count($consultorios); $x++){
			$paciente_dia = 0;
			$id_clinica = $consultorios[$x]['id'];
			
			//SACAMOS LOS PACIENTES QUE FUERON ATENDIDOS EN LA CLINICA ESE DIA 
			$pacientes_clinica = mysql_query("SELECT DISTINCT(citas.id_cita),id_paciente FROM citas 
											WHERE id_clinica = '$id_clinica' AND DATE(fecha_hora) = '$dia_actual' AND citas.tipo=1 AND citas.activo=1");
			
			while($paciente = mysql_fetch_assoc($pacientes_clinica)){
				$id_paciente = $paciente['id_paciente'];
				$check = mysql_num_rows(mysql_query("SELECT * FROM consultas WHERE id_paciente = '$id_paciente'"));
				if($check == 1){
					$paciente_dia++;
				}
			}
			
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
    for($x = 0; $x < count($consultorios); $x++){
	    $objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue($titulos[$x].$cuenta, '=SUM('.$titulos[$x].'7:'.$titulos[$x].''.$celdas_total.')');
    }

	
	//APARTIR DE AQUI SON LOS NUEVOS
	$cuenta += 4;
	
	$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($tit_1.$cuenta,'PACIENTES NUEVOS QUE ASISTIERON');
    $cuenta++;
    $objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($tit_1.$cuenta,'FECHA');
    
    for($y = 0; $y < count($consultorios); $y++){
		$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($titulos[$y].$cuenta,$consultorios[$y]['nom']);      	
	}
	 $cuenta++;
	 $nuevos = $cuenta;
    
    //CONSULTAMOS POR CADA UNO DE LOS DIAS QUE HAY EN LA LISTA
	for($i = 0;$i < count($dias);$i++){				
		$dia_actual = $dias[$i];
		
				
		$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($tit_1.$cuenta,fechaLetraDos($dia_actual)); 
		//PACIENTES POR DIA POR CADA UNO DE LOS consultorios
		for($x = 0; $x < count($consultorios); $x++){
			$paciente_dia = 0;
			$id_clinica = $consultorios[$x]['id'];
			
			//SACAMOS LOS PACIENTES QUE FUERON ATENDIDOS EN LA CLINICA ESE DIA 
			$pacientes_clinica = mysql_query("SELECT DISTINCT(id_cita),id_paciente FROM citas 
											WHERE id_clinica = '$id_clinica' AND DATE(fecha_hora) = '$dia_actual' AND citas.tipo=1 AND citas.activo=1");
			
			while($paciente = mysql_fetch_assoc($pacientes_clinica)){
				$id_cita = $paciente['id_cita'];
				$id_paciente = $paciente['id_paciente'];
				$check = mysql_num_rows(mysql_query("SELECT * FROM consultas WHERE id_cita = '$id_cita'"));
				if($check == 1){
					$check2 = mysql_num_rows(mysql_query("SELECT * FROM consultas WHERE id_paciente = '$id_paciente'"));
					if($check2 == 1){
						$paciente_dia++;
					}
				}
			}

			
			$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($titulos[$x].$cuenta,$paciente_dia); 
            	
            $f_totales_n[$i] .= $titulos[$x].$cuenta."+";	
       
		}
				
		$cuenta++;
	}
	
	$celdas_total=$cuenta-1;
	$cuenta += 2;

	$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue($tit_1.$cuenta, 'TOTAL');
    for($x = 0; $x < count($consultorios); $x++){
	    $objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue($titulos[$x].$cuenta, '=SUM('.$titulos[$x].$nuevos.':'.$titulos[$x].''.$celdas_total.')');
    }

	
	
	// ***************************** AQUI SE AGREGAN LOS CONFIRMADOS QUE ASISTIERON  ********************************** //
	//APARTIR DE AQUI SON LOS NUEVOS
	$cuenta += 4;
	
	$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($tit_1.$cuenta,'PACIENTES CONFIRMADOS QUE ASISTIERON');
    $cuenta++;
    $objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($tit_1.$cuenta,'FECHA');
    
    for($y = 0; $y < count($consultorios); $y++){
		$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($titulos[$y].$cuenta,$consultorios[$y]['nom']);      	
	}
	 $cuenta++;
	 $confirm = $cuenta;
    
    //CONSULTAMOS POR CADA UNO DE LOS DIAS QUE HAY EN LA LISTA
	for($i = 0;$i < count($dias);$i++){				
		$dia_actual = $dias[$i];
		
				
		$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($tit_1.$cuenta,fechaLetraDos($dia_actual)); 
		//PACIENTES POR DIA POR CADA UNO DE LOS consultorios
		for($x = 0; $x < count($consultorios); $x++){
			$paciente_dia = 0;
			$id_clinica = $consultorios[$x]['id'];
			
			//SACAMOS LOS PACIENTES QUE FUERON ATENDIDOS EN LA CLINICA ESE DIA 
			$pacientes_clinica = mysql_query("SELECT DISTINCT(id_cita),id_paciente FROM citas 
											WHERE id_clinica = '$id_clinica' AND DATE(fecha_hora) = '$dia_actual' AND citas.tipo=1 AND citas.activo=1 AND citas.confirmada = 1");
			
			while($paciente = mysql_fetch_assoc($pacientes_clinica)){
				$id_cita = $paciente['id_cita'];
				$id_paciente = $paciente['id_paciente'];
				$check = mysql_num_rows(mysql_query("SELECT * FROM consultas WHERE id_cita = '$id_cita'"));
				if($check == 1){
					$paciente_dia++;
				}
			}

			
			$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($titulos[$x].$cuenta,$paciente_dia); 
            	
            $f_totales_c[$i] .= $titulos[$x].$cuenta."+";	
       
		}
				
		$cuenta++;
	}
	
	$celdas_total=$cuenta-1;
	$cuenta += 2;

	$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue($tit_1.$cuenta, 'TOTAL');
    for($x = 0; $x < count($consultorios); $x++){
	    $objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue($titulos[$x].$cuenta, '=SUM('.$titulos[$x].$confirm.':'.$titulos[$x].''.$celdas_total.')');
    }

	
	
	$cuenta = 8;
	unset($titulos);


	// 	AQUI VAN LOS TOTALES
	$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($str.'7','TOTAL');    	
	for($i = 0;$i < count($dias);$i++){
		for($x = 0; $x < count($consultorios); $x++){
		    $objPHPExcel->setActiveSheetIndex(0)
			            ->setCellValue($str.$cuenta, '='.substr($f_totales[$i],0,-1));			
	    }
	    $cuenta++;
	}
	
	$cuenta = $nuevos-1;
	
	$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($str.$cuenta,'TOTAL');  
    $cuenta++;  	
	for($i = 0;$i < count($dias);$i++){
		for($x = 0; $x < count($consultorios); $x++){
		    $objPHPExcel->setActiveSheetIndex(0)
			            ->setCellValue($str.$cuenta, '='.substr($f_totales_n[$i],0,-1));			
	    }
	    $cuenta++;
	}
	
	$cuenta = $confirm-1;
	
	$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue($str.$cuenta,'TOTAL');  
    $cuenta++;  	
	for($i = 0;$i < count($dias);$i++){
		for($x = 0; $x < count($consultorios); $x++){
		    $objPHPExcel->setActiveSheetIndex(0)
			            ->setCellValue($str.$cuenta, '='.substr($f_totales_c[$i],0,-1));			
	    }
	    $cuenta++;
	}
		
	$str = ++$str;


	
	$nuevos = $nuevos-2;
	$confirm = $confirm-2;

	//AQUI TERMINA LOS FILTROS
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BReporte de Pacientes&RDentixa CRM &D');
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPágina &P of &N');
	$objPHPExcel->getActiveSheet()->getStyle('A6:'.$str.'6')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A6:'.$str.'6')->getFont()->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle('A7:'.$str.'7')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A7:'.$str.'7')->getFont()->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$nuevos.':'.$str.$nuevos)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$nuevos.':'.$str.$nuevos)->getFont()->setSize(12);
	$nuevos++;
	$objPHPExcel->getActiveSheet()->getStyle('A'.$nuevos.':'.$str.$nuevos)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$nuevos.':'.$str.$nuevos)->getFont()->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$confirm.':'.$str.$confirm)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$confirm.':'.$str.$confirm)->getFont()->setSize(12);
	$confirm++;
	$objPHPExcel->getActiveSheet()->getStyle('A'.$confirm.':'.$str.$confirm)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$confirm.':'.$str.$confirm)->getFont()->setSize(12);
	
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
	$objPHPExcel->getActiveSheet()->setTitle('Pacientes Agendados');
	
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
	header('Content-Disposition: attachment;filename="Reporte_de_agendados_asistencia_DENTIXA-CRM.xlsx"');
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

