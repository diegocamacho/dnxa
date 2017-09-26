<? session_start();

$s_id_usuario=$_SESSION['s_id'];
$s_tipo=$_SESSION['s_tipo'];
$s_nombre=$_SESSION['s_nombre'];
$s_id_clinica=$_SESSION['s_id_clinica'];
if(!isset($_SESSION['s_id'])){
	exit("Su sesión ha expirado.");
}
?>