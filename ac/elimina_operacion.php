<?
include("../includes/session.php");
include("../includes/db.php");
//exit("error");
extract($_POST);
//print_r($_POST);
//Validamos datos completos
if(!$tipo) exit("No llego el identificador del tipo de operación");
if(!$id) exit("No llego el identificador de la operación");

if($tipo==1):
	//Ingreso
	$sql="SELECT id_transferencia FROM books_ingresos WHERE id_ingreso=$id";
	$q=mysql_query($sql);
	$ft=mysql_fetch_assoc($q);
	if($ft['id_transferencia']):
		$id_transferencia=$ft['id_transferencia'];
		$sq=@mysql_query("DELETE FROM books_ingresos WHERE id_transferencia=$id_transferencia");
		if(!$sq) $error = true;
			
		$sq=@mysql_query("DELETE FROM books_gastos WHERE id_transferencia=$id_transferencia");
		if(!$sq) $error = true;
		
		$sq=@mysql_query("DELETE FROM books_transferencias WHERE id_transferencia=$id_transferencia");
		if(!$sq) $error = true;
		
		
		if($error):
		    mysql_query('ROLLBACK');
		    echo "Ocurrió un error, intente más tarde.";
		else:
		    mysql_query('COMMIT');
		    echo "1";
		endif;
	else:
	
		$sq=@mysql_query("DELETE FROM books_ingresos WHERE id_ingreso=$id");
		if(!$sq) $error = true;
		
		if($id_consulta):
			$sq=@mysql_query("DELETE FROM consultas WHERE id_consulta=$id_consulta");
			if(!$sq) $error = true;
			
			$sq=@mysql_query("DELETE FROM consultas_tratamientos WHERE id_consulta=$id_consulta");
			if(!$sq) $error = true;
		endif;
		
		if($error):
		    mysql_query('ROLLBACK');
		    echo "Ocurrió un error, intente más tarde.";
		else:
		    mysql_query('COMMIT');
		    echo "1";
		endif;
	endif;
elseif($tipo==2):
	//Egreso
	$sql="SELECT id_transferencia,id_pago_especialistas_lab FROM books_gastos WHERE id_gasto=$id";
	$q=mysql_query($sql);
	$ft=mysql_fetch_assoc($q);
	$id_pago_especialistas_lab = $ft['id_pago_especialistas_lab'];
	
	if($id_pago_especialistas_lab):
		$sq=@mysql_query("UPDATE pagos_especialistas_lab SET liquidado = 0 WHERE id_pago_especialistas_lab = $id_pago_especialistas_lab");
		if(!$sq) $error = true;
	endif;
	
	if($ft['id_transferencia']):
		$id_transferencia=$ft['id_transferencia'];
		$sq=@mysql_query("DELETE FROM books_ingresos WHERE id_transferencia=$id_transferencia");
		if(!$sq) $error = true;
			
		$sq=@mysql_query("DELETE FROM books_gastos WHERE id_transferencia=$id_transferencia");
		if(!$sq) $error = true;
		
		$sq=@mysql_query("DELETE FROM books_transferencias WHERE id_transferencia=$id_transferencia");
		if(!$sq) $error = true;
		
		
		
		if($error):
		    mysql_query('ROLLBACK');
		    echo "Ocurrió un error, intente más tarde.";
		else:
		    mysql_query('COMMIT');
		    echo "1";
		endif;
	else:
		$sql="DELETE FROM books_gastos WHERE id_gasto=$id";
		$q=mysql_query($sql);
		if($q){
			echo "1";
		}else{
			echo "Ocurrió un error al actualizar el usuario";
		}
	endif;
else:
	exit("Ocurrió un error, intenta nuevamente.");
endif;

?>