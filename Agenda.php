<?php
session_start();

// Inicializar agenda si no existe
if (!isset($_SESSION['agenda'])) {
    $_SESSION['agenda'] = array();
}

// Función para añadir o actualizar un contacto
function agregarActualizarContacto($nombre, $telefono) {
    $_SESSION['agenda'][$nombre] = $telefono;
}

// Función para eliminar un contacto
function eliminarContacto($nombre) {
    unset($_SESSION['agenda'][$nombre]);
}

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $telefono = trim($_POST['telefono']);

    if (empty($nombre)) {
        $error = "El nombre no puede estar vacío";
    } elseif (!empty($telefono) && !is_numeric($telefono)) {
        $error = "El teléfono debe ser numérico";
    } else {
        if (!empty($telefono)) {
            agregarActualizarContacto($nombre, $telefono);
        } elseif (array_key_exists($nombre, $_SESSION['agenda'])) {
            eliminarContacto($nombre);
        } else {
            $error = "No existe un contacto con ese nombre";
        }
    }

    if (isset($_POST['borrarTodo'])) {
        $_SESSION['agenda'] = array();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pequeña Agenda</title>
</head>
<body>
<?php if (!empty($error)) : ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>

<!-- Mostrar agenda -->
<h2>Agenda</h2>
<ul>
    <?php foreach ($_SESSION['agenda'] as $nombre => $telefono): ?>
        <li><?php echo htmlspecialchars($nombre) . ': ' . htmlspecialchars($telefono); ?></li>
    <?php endforeach; ?>
</ul>

<!-- Formulario para añadir o actualizar contactos -->
<h2>Añadir/Actualizar Contacto</h2>
<form action="" method="post">
    <label for="nombre">Nombre:</label>
    <input type="text" name="nombre" id="nombre">
    <label for="telefono">Teléfono:</label>
    <input type="text" name="telefono" id="telefono">
    <input type="submit" value="Guardar">
    <input type="submit" name="borrarTodo" value="Borrar Todos los Contactos">
</form>
</body>
</html>
