<?php
//error_reporting(0);
/* Conexión en entorno local 
$servidor="localhost";
$usuario="root";
$clave="root";
$base="dentisxa2";*/


$servidor="108.179.194.93";
$usuario="epic_diego";
$clave="camacho";
$base="epic_dentisxa";
$conexion = mysqli_connect($servidor, $usuario, $clave, $base);
if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}