<?php
error_reporting(0);
date_default_timezone_set('Europe/London');

require_once 'Classes/PHPExcel/IOFactory.php';
include('../../includes/db.php');

#$inputFileName = 'plantilla.xlsx';

$dir_subida = './subidas/';
$fichero_subido = $dir_subida . basename($_FILES['archivo']['name']);


if (!move_uploaded_file($_FILES['archivo']['tmp_name'], $fichero_subido)) {
	header('Location: /app/?Modulo=Clientes&m='.base64_encode('Error al subir archivo .xlsx'));
    exit;
}

$inputFileName = 'subidas/'.$_FILES['archivo']['name'];


$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objReader->setReadDataOnly(true);
$objPHPExcel = $objReader->load($inputFileName);
$objWorksheet = $objPHPExcel->getActiveSheet();

$highestRow = $objWorksheet->getHighestRow();
$highestColumn = $objWorksheet->getHighestColumn();
$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
$rows = array();


$sql = "SELECT id_plan FROM planes WHERE activo = 1";
$q = mysql_query($sql);

while($data = mysql_fetch_assoc($q)):
	$planes[$data['id_plan']] = $data['id_plan'];
endwhile;

if($highestRow<8){
	header('Location: /app/?Modulo=Clientes&m='.base64_encode('Formato de archivo .xlsx inválido, utilice el formato compatible.'));
    exit;
}

$inicia = 1;
for ($row = 1; $row <= $highestRow; ++$row) {
  for ($col = 0; $col <= $highestColumnIndex; ++$col) {
    $rows[$col] = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
  }
  
 
  if($row>7):

	if(strlen($rows[0])>0){

		if(strlen($rows[0])<1):
			header('Location: /app/?Modulo=Clientes&m='.base64_encode('Paciente con nombre inválido o faltante.'));
		    exit;
	  	endif;
	  		  	
	  	if(!$rows[3]):
			header('Location: /app/?Modulo=Clientes&m='.base64_encode('Paciente '.$rows[0].' sin ID Cliente, verifique.'));
		    exit;
	  	endif;
	  	
		if(!existeCliente($rows[3])):
			header('Location: /app/?Modulo=Clientes&m='.base64_encode('Paciente '.$rows[0].' con Plan expirado o ID Cliente inexistente.'));
		    exit;
	  	endif;
		
	  	$pacientes['nombre'][$inicia] = $rows[0];
	  	$pacientes['tel'][$inicia] = $rows[1];
	  	$pacientes['email'][$inicia] = $rows[2];
	  	$pacientes['id_cliente'][$inicia] = $rows[3];
	 	$inicia++; 	
	}
  	
  endif;

}

	$file = file_put_contents('json.txt', json_encode($pacientes));
	header('Location: /app/?Modulo=PreImportacion&jalo=1');
	
	
function existeCliente($id_cliente){
	
	global $conexion;
	$hoy = date('Y-m-d');
	$sql = "SELECT id_plan FROM books_clientes WHERE id_cliente = $id_cliente AND fecha_final_plan >= '$hoy'";
	$q = mysql_query($sql);
	
	if(mysql_num_rows($q)>0):
		return true;
	else:
		return false;	
	endif;
	
	
}
