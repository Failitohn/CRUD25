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
        echo "<script>alert('$mensaje'); window.location.href = 'u.html';</script>";
        exit();
    }

    $check = "SELECT idcomida FROM Comidas WHERE idcomida = '$idcomida'";
    $resultado = $conexion->query($check);

    if ($resultado->num_rows === 0) {
        echo "<script>alert('El ID no existe en la base de datos. No se pudo actualizar.'); window.location.href = 'u.html';</script>";
        exit();
    }

    $update = "UPDATE Comidas SET nombrecomida='$nombrecomida', descripcioncomida='$descripcioncomida', preciocomida='$preciocomida' WHERE idcomida = '$idcomida'";

    if ($conexion->query($update)) {
        echo "<script>alert('Comida se actualizó correctamente'); window.location.href = 'u.html';</script>";
        exit();
    } else {
        die("Error al actualizar la comida: " . $conexion->error);
    }
}
?>