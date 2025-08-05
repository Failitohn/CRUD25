<?php
include 'conexionproyecto.php';

$errores = [];
$datosComida = null;

// Si se envió solo el ID para buscar
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idcomida']) && !isset($_POST['nombrecomida'])) {
    $idcomida = $_POST['idcomida'];

    if (!ctype_digit($idcomida) || intval($idcomida) <= 0) {
        header("Location: u.html?msg=ID inválido. Debe ser un número entero positivo.");
        exit();
    }

    $stmt = $conexion->prepare("SELECT nombrecomida, descripcioncomida, preciocomida FROM Comidas WHERE idcomida = ?");
    $stmt->bind_param("i", $idcomida);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $fila = $resultado->fetch_assoc()) {
        $datosComida = $fila;
        $datosComida['idcomida'] = $idcomida;
    } else {
        header("Location: u.html?msg=No se encontró ninguna comida con ese ID.");
        exit();
    }
}

// Si se envió el formulario completo para actualizar
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombrecomida'])) {
    $idcomida = $_POST['idcomida'];
    $nombrecomida = $_POST['nombrecomida'];
    $descripcioncomida = $_POST['descripcioncomida'];
    $preciocomida = $_POST['preciocomida'];

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

    if (empty($errores)) {
        $update = "UPDATE Comidas SET nombrecomida=?, descripcioncomida=?, preciocomida=? WHERE idcomida=?";
        $stmt = $conexion->prepare($update);
        $stmt->bind_param("ssdi", $nombrecomida, $descripcioncomida, $preciocomida, $idcomida);

        if ($stmt->execute()) {
            echo "<script>alert('Comida actualizada correctamente'); window.location.href = 'u.html';</script>";
            exit();
        } else {
            $errores[] = "Error al actualizar: " . $conexion->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Comida</title>
    <link rel="stylesheet" href="estilo.css">
    <script>
        function confirmarActualizacion() {
            return confirm("¿Desea actualizar esta comida?");
        }
    </script>
</head>
<body>
    <?php if ($datosComida): ?>
    <form method="POST" action="u.php" onsubmit="return confirmarActualizacion();">
        <a href="c.html">Crear</a>
        <a href="r.html">Leer</a>
        <a href="u.html">Actualizar</a>
        <a href="d.html">Eliminar</a>

        <label><strong>Editar Comida</strong></label>

        <input type="hidden" name="idcomida" value="<?php echo $datosComida['idcomida']; ?>">

        <label>Nombre:</label>
        <input type="text" name="nombrecomida" id="nombrecomida"
               value="<?php echo htmlspecialchars($datosComida['nombrecomida']); ?>"
               required pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" title="Solo letras y espacios">

        <label>Descripción:</label>
        <input type="text" name="descripcioncomida" id="descripcioncomida"
               value="<?php echo htmlspecialchars($datosComida['descripcioncomida']); ?>"
               required pattern="[\s.,:;!?¡¿()-a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" title="Solo letras, números, espacios y signos permitidos">

        <label>Precio:</label>
        <input type="text" name="preciocomida" id="preciocomida"
               value="<?php echo htmlspecialchars($datosComida['preciocomida']); ?>"
               required pattern="\d+(\.\d{1,2})?" title="Número decimal positivo con hasta dos decimales">

        <button type="submit" id="actualizarcomida" name="actualizarcomida">Actualizar</button>
    </form>
    <?php endif; ?>

    <?php if (!empty($errores)): ?>
        <div style="color: #ff6b6b; max-width: 600px; margin: auto; padding-top: 20px;">
            <h3>Errores:</h3>
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</body>
</html>
