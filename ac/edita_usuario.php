<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);

//Validamos datos completos
if(!$id_usuario) exit("Error al identificar el usuario.");
if($id_tipo_usuario<=0) exit("Debe escribir un tipo de usuario.");
if(!$nombre) exit("Debe escribir un nombre.");
if(!$email) exit("Debe escribir una direcci&oacute;n de Email.");
//if(!$usuario) exit("Debe escribir un usuario.");
//if(!$password) exit("Debe escribir una contraseña.");


//Formateamos y validamos los valores
$nombre=limpiaStr($nombre,1,1);
$id_usuario=escapar($id_usuario,1);
if(!validarEmail($email)) exit("El correo ".escapar($email)." no es v&aacute;lido, verifique el formato.");

if($password){
	$password=contrasena($password);
	$data=", pass='".$password."' ";
}
if($apellido){
	$apellido=limpiaStr($apellido,1,1);
	$nombre=$nombre." ".$apellido;
}
//Verificamos que el usuario no exista
$q=mysql_query("SELECT * FROM usuarios WHERE email='$email' AND id_usuario != $id_usuario ");
$valida=mysql_num_rows($q);
if($valida>0){
	exit("El email ".$email." esta en uso.");
}else{
	//Insertamos datos
	$sql="UPDATE usuarios SET id_tipo_usuario='$id_tipo_usuario', id_clinica='$id_clinica', nombre='$nombre', email='$email', celular='$celular',foto='$foto_final' ".$data." WHERE id_usuario=$id_usuario";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}
}
?>