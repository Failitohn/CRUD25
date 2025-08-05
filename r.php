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
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Resultado</title>
    <style>
        body {
            font-family: 'Poppins', 'Segoe UI', Tahoma, sans-serif;
            background: linear-gradient(to bottom right, #0a0f2c, #1e3c72);
            padding: 60px 20px;
            color: #ffffff;
            min-height: 100vh;
            margin: 0;
        }

        .resultado-container {
            max-width: 600px;
            margin: 100px auto;
            padding: 40px;
            background: linear-gradient(to bottom right, #1a1a1a, #252525);
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.7);
            border-left: 6px solid #2196f3;
            text-align: center;
        }

        .resultado-mensaje {
            font-size: 20px;
            font-weight: 600;
            color: #90caf9;
            margin-bottom: 30px;
        }

        .boton-volver {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(to right, #2196f3, #1e88e5);
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .boton-volver:hover {
            background: linear-gradient(to right, #1976d2, #1565c0);
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="resultado-container">
        <div class="resultado-mensaje">
            <?php echo $mensaje; ?>
        </div>
        <a class="boton-volver" href="r.html">Volver</a>
    </div>
</body>
</html>
