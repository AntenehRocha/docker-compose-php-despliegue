<?php
session_start();

if (empty($_SESSION['usuario'])) {
    header('Location: ./index.php');
    exit;
}

$usuario = $_SESSION['usuario'];
$mensaje = '';

if (empty($_SESSION['mongo_guardado'])) {
    try {
        // 1. Conexión nativa usando las credenciales del docker-compose (host: mongo)
        $manager = new MongoDB\Driver\Manager("mongodb://root:example@mongo:27017/");

        // 2. Preparar los datos en un lote de escritura (BulkWrite)
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->insert([
            'nombre' => $usuario['nombre'],
            'apellidos' => $usuario['apellidos'],
            'dni' => $usuario['dni'],
            'email' => $usuario['e-mail'],
        ]);

        // 3. Ejecutar la operación en la base de datos 'practica' y colección 'usuarios'
        $manager->executeBulkWrite('practica.usuarios', $bulk);

        $_SESSION['mongo_guardado'] = true;
        $mensaje = 'Datos guardados localmente en el contenedor MongoDB.';
    } catch (MongoDB\Driver\Exception\Exception $e) {
        $mensaje = 'Error al guardar los datos nativos: ' . $e->getMessage();
    }
} else {
    $mensaje = 'Estos datos ya se habían guardado antes.';
}

$nombre = htmlspecialchars((string) $usuario['nombre'], ENT_QUOTES, 'UTF-8');
$apellidos = htmlspecialchars((string) $usuario['apellidos'], ENT_QUOTES, 'UTF-8');
$dni = htmlspecialchars((string) $usuario['dni'], ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars((string) $usuario['e-mail'], ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>resultados</title>
</head>
<body>
    <p>Hola <?php echo $nombre . ' ' . $apellidos; ?>, datos validados correctamente.</p>
    <p>DNI: <?php echo $dni; ?></p>
    <p>E-mail: <?php echo $email; ?></p>
    <p><strong><?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?></strong></p>
    <p><a href="index.php">Volver al formulario</a></p>
</body>
</html>
