<?php 
$servidor = "localhost";
$usuario = "root";      
$clave = "";             
$baseDatos = "dbasephp";

$conexion = $conexion = mysqli_connect($servidor, $usuario, $clave, $baseDatos);
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
 ?>