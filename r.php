<?php 
include 'conexionproyecto.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idcomida = $_POST['idcomida'];

    if (!$idcomida || !ctype_digit($idcomida) || intval($idcomida) <= 0) {
        $mensaje .= "<p>El ID debe ser un número entero positivo y no puede estar vacío.</p>";
    } elseif (!is_numeric($idcomida)) {
        $mensaje .= "<p>El ID debe ser un número válido.</p>";
    } else {
        $buscar = "SELECT nombrecomida, descripcioncomida, preciocomida FROM Comidas WHERE idcomida = ?";
        $stmt = $conexion->prepare($buscar);
        $stmt->bind_param("i", $idcomida);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado && $fila = $resultado->fetch_assoc()) {
            $mensaje .= "<h3>Comida Encontrada</h3>";
            $mensaje .= "<p><strong>Nombre:</strong> {$fila['nombrecomida']}</p>";
            $mensaje .= "<p><strong>Descripción:</strong> {$fila['descripcioncomida']}</p>";
            $mensaje .= "<p><strong>Precio:</strong> L.{$fila['preciocomida']}</p>";
        } else {
            $mensaje .= "<p>No se encontró ninguna comida con ese ID.</p>";
        }
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Resultado</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div id="resultado">
        <?php echo $mensaje; ?>
    </div>
    <br>
    <a href="r.html">Volver</a>
</body>
</html>
