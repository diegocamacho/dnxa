<?
error_reporting();
session_start();
require '../includes/db.php';
require '../includes/funciones.php';

date_default_timezone_set ("America/Mexico_City");
$fecha_hora=date("Y-m-d H:i:s");

//$user="diego@epicmedia.pro";
//$pass="c4ca4238a0b923820dcc509a6f75849b";

if(!$_POST['user']) exit("Debe escribir su usuario");
if(!$_POST['pass']) exit("Debe escribir su contraseña");

$user=$_POST['user'];
$pass=$_POST['pass'];


		$username=$user;
		$password=contrasena($pass);
		// Admin
		 $sql = "SELECT * FROM usuarios WHERE email='$username' AND pass='$password' AND activo='1' LIMIT 1";
		 $result = $conexion->query($sql);
		 
		 if ($result->num_rows < 1) { exit("Ocurrió un error, intente más tarde."); }

		 $row = $result->fetch_array(MYSQLI_ASSOC);
		 
			$_SESSION['s_id'] = $row['id_usuario'];
			$_SESSION['s_tipo'] = $row['id_tipo_usuario'];
			$_SESSION['s_id_clinica'] = $row['id_clinica'];
			$_SESSION['s_nombre'] = $row['nombre'];
			$_SESSION['s_display'] = $row['foto'];
			
			if($conexion->query("UPDATE usuarios SET ultimo_acceso='$fecha_hora' WHERE id_usuario='".$_SESSION['s_id']."'")){
				echo "1";
			}
		mysqli_close($conexion);