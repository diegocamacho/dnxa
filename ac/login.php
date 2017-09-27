<?
error_reporting(1);
session_start();
require '../includes/db.php';
require '../includes/funciones.php';

date_default_timezone_set ("America/Mexico_City");
$fecha_hora=date("Y-m-d H:i:s");

//$user="diego@epicmedia.pro";
//$pass="c4ca4238a0b923820dcc509a6f75849b";

if(!$_POST['user']) exit("Debe escribir su usuario");
if(!$_POST['pass']) exit("Debe escribir su contraseña");



		$usuario=mysql_real_escape_string($user);
		$contrasena=contrasena(mysql_real_escape_string($pass));
		// Admin
		 $sql = "SELECT * FROM usuarios WHERE email='$user' AND pass='$pass' AND activo='1' LIMIT 1";
		 $result = $conexion->query($sql);
		 
		 if ($result->num_rows > 0) { exit("Ocurrió un error,"); }

		 $row = $result->fetch_array(MYSQLI_ASSOC);
		 if (password_verify($password, $row['password'])) {
			$_SESSION['s_id'] = $row->id_usuario;
			$_SESSION['s_tipo'] = $row->id_tipo_usuario;
			$_SESSION['s_id_clinica'] = $row->id_clinica;
			$_SESSION['s_nombre'] = $row->nombre;
			$_SESSION['s_display'] = $row->foto;
			if($conexion->query("UPDATE usuarios SET ultimo_acceso='$fecha_hora' WHERE id_usuario='".$_SESSION['s_id']."'")){
				echo "1";
			}
		}else{
			exit("Ocurrió un error");
		}
		
		
		mysqli_close($conexion);