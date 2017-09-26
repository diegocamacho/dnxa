<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if(!$nombre) exit("El plan debe tener nombre.");
if(count($cantidad)==0) exit('Seleccione tratamientos.');

$nombre=limpiaStr($nombre,1,1);

	
mysql_query('BEGIN');
	
if($id_plan):

	$sql="UPDATE planes SET plan='$nombre', observacion = '$observacion' WHERE id_plan=$id_plan";
	$qu=mysql_query($sql) or $error=true;
	
	$sql="DELETE FROM planes_tratamientos WHERE id_plan=$id_plan";
	$qu=mysql_query($sql) or $error=true;

else:
	$sql="INSERT INTO planes (plan,observacion,activo) VALUES ('$nombre','$observacion',1)";
	$qu=mysql_query($sql) or $error=true;
	$id_plan=mysql_insert_id();

	
endif;


foreach($cantidad as $id => $val):
		
	$id_tratamiento=$id;
	$cant=abs($val);
	
	if(trim($cant)):
		$sq=@mysql_query("INSERT INTO planes_tratamientos (id_plan,id_tratamiento,cantidad)VALUES('$id_plan','$id_tratamiento','$cant')");
		if(!$sq) $error = true;
	endif;
	
endforeach;
	
	if($error):
		mysql_query('ROLLBACK');
		echo 'Ocurrió un error al guardar, intente más tarde por favor.';
	else:
		mysql_query('COMMIT');
		echo "1";
	endif;
