<?php
include("../includes/db.php");
include("../includes/funciones.php");

if(!$_GET['id']) exit("Error de ID");

$id = escapar($_GET['id'],1);

$sql = "SELECT * FROM eventos WHERE id_evento=$id";

$q = mysql_query($sql);
$data = mysql_fetch_object($q);
echo json_encode($data);