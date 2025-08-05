<?php 
include 'conexionproyecto.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idcomida = $_POST['idcomida'];
$nombrecomida = $_POST['nombrecomida'];
$descripcioncomida = $_POST['descripcioncomida'];
$preciocomida = $_POST['preciocomida'];


$errores = [];

    if (!ctype_digit($idcomida) || intval($idcomida) <= 0) {
        $errores[] = "El ID debe ser un número entero positivo.";
    }

    if (!$nombrecomida || !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombrecomida)) {
        $errores[] = "El nombre solo debe contener letras y espacios.";
    }

    if (!$descripcioncomida || !preg_match("/^[\p{L}\p{N}\s.,:;!?¡¿()-]+$/u", $descripcioncomida)) {
        $errores[] = "La descripción contiene caracteres inválidos.";
    }

    if (!is_numeric($preciocomida) || floatval($preciocomida) <= 0 || !preg_match("/^\d+(\.\d{1,2})?$/", $preciocomida)) {
        $errores[] = "El precio debe ser un número decimal positivo con hasta dos decimales.";
    }

    if (!empty($errores)) {
        $mensaje = implode("\\n", $errores);
        echo "<script>alert('$mensaje'); window.location.href = 'c.html';</script>";
        exit();
    }


$insert = "INSERT INTO Comidas (idcomida, nombrecomida, descripcioncomida, preciocomida) VALUES ('$idcomida', '$nombrecomida', '$descripcioncomida', '$preciocomida')";

if ($conexion->query($insert)) {
echo "<script>alert(' Comida se insertó correctamente'); window.location.href = 'c.html';</script>";
    exit();
} else {
    die("Error al agregar la comida: " . $conexion->error);
    }
    exit();
}

?>