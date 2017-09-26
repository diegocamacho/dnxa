<?
include("../includes/session.php");
include("../includes/db.php");
include("../includes/funciones.php");

extract($_POST);
//print_r($_POST);

//Validamos datos completos
if(!$id_tipo_usuario) exit("Seleccione un tipo de usuario.");
if(!$nombre) exit("Debe escribir un nombre.");
if(!$email) exit("Debe escribir un Email.");
if(!$password) exit("Debe escribir una contraseña.");

if($id_tipo_usuario==0) exit("Ningún usuario adicional puede ser administrador");
//Formateamos y validamos los valores
$id_tipo_usuario=limpiaStr($id_tipo_usuario,1);
$nombre=limpiaStr($nombre,1,1);
if(!validarEmail($email)) exit("El correo ".escapar($email)." no es válido, verifique el formato.");
$celular=limpiaStr($celular,1);
$password=contrasena($password);

if($apellido):
	$apellido=limpiaStr($apellido,1,1);
	$nombre=$nombre." ".$apellido;
endif;

if($id_tipo_usuario>2):
	if(!$id_clinica) exit("Si el usuario no es administrador debe seleccionar una clínica donde labora.");
endif;

//Verificamos que el usuario no exista
$q=mysql_query("SELECT * FROM usuarios WHERE email='$email' ");
$valida=mysql_num_rows($q);
if($valida>0){
	exit("La cuenta de correo ".$email." ya esta en uso.");
}else{
	//Insertamos datos
	$sql="INSERT INTO usuarios (id_tipo_usuario,id_clinica,nombre,email,pass,celular,foto) VALUES ('$id_tipo_usuario','$id_clinica','$nombre','$email','$password','$celular','$foto_final')";
	$q=mysql_query($sql);
	if($q){
		echo "1";
	}else{
		echo "Ocurrió un error, intente más tarde.";
	}
}
