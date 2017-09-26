<?php
//error_reporting(0);
/* Conexión en entorno local 
$servidor="localhost";
$usuario="root";
$clave="root";
$base="dentisxa2";*/

/* Conexión en producción */
$servidor="epicmedia.pro";
$usuario="epic_diego";
$clave="camacho";
$base="epic_dentisxa";
//exit("ESTAMOS ACTUALIZANDO SU SITEMA, ¡VOLVEREMOS PRONTO!");
$conexion = @mysql_connect ($servidor,$usuario,$clave) or die ("Ocurrió un error al conectarse.");
@mysql_select_db($base) or die ("No BD ");
?>