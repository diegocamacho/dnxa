<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if(!$nombre) exit("Falta nombre del paciente.");
if(!$id_cliente) exit("Falta Empresa.");
if(!$id_canal) exit("Seleccione un canal.");


mysql_query("BEGIN");

foreach($nombre as $id => $n):

	$sql = "INSERT INTO pacientes 
			(id_canal, id_cliente, fecha_registro, nombre, telefono, email, tipo) VALUES 
			('$id_canal','{$id_cliente[$id]}','$fecha_actual','$n','{$tel[$id]}','{$email[$id]}',1)";
	$q = mysql_query($sql);
	if(!$q): $error = 1; endif;
	
endforeach;


if(!$error):
	mysql_query('COMMIT');
	echo '1';
	file_put_contents('../uploader_masivo/excel/json.txt', '');
	unlink('../uploader_masivo/excel/json.txt');
else:
	mysql_query('ROLLBACK');
	echo 'Error al guardar los pacientes, contacte a soporte.';
endif;


	