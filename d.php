<?php 

include 'conexionproyecto.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
$idcomida = $_POST['idcomida'];


    if (!ctype_digit($idcomida) || intval($idcomida) <= 0) {
        $errores[] = "El ID debe ser un número entero positivo.";
    }

    if (!empty($errores)) {
        $mensaje = implode("<br>", array_map("htmlspecialchars", $errores));
        header("Location: d.html?msg=" . urlencode($mensaje));
        exit();
    }

  $check = "SELECT idcomida FROM Comidas WHERE idcomida = $idcomida";
    $resultado = $conexion->query($check);

    if ($resultado->num_rows === 0) {
        $mensaje = "⚠️ El ID $idcomida no existe en la base de datos. No se pudo eliminar.";
        echo "<script>alert('$mensaje'); window.location.href = 'd.html';</script>";
        exit();
    }

    $delete = "DELETE FROM Comidas WHERE idcomida = $idcomida"; 


if ($conexion->query($delete)) {
    echo "<script>alert(' Comida se eliminó correctamente'); window.location.href = 'd.html';</script>";
    exit();
} else {
    die("Error al eliminar la comida: " . $conexion->error);
    }
    exit();
}
?>