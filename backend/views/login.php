<?php
    session_start();
    require_once '../../config.php';
    require_once '../../database/db.php';

    if (isset($_SESSION['es_admin'])) {
        if ($_SESSION['es_admin']) {
            header('Location: ' . BASE_URL . 'backend/views/admin/dashboard.php');
            exit();
        } else {
            header('Location: ' . BASE_URL . '/index.php');
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/styles/login.css">
</head>
<body>
    <div class="container">
        <h1>Inicia Sesión</h1>
        <form class="login-form" action="<?php echo BASE_URL; ?>/backend/controllers/login.proc.php" method="post">
            <div class="form-group">
                <label for="nombre">Correo Electrónico:</label>
                <input type="email" id="nombre" name="nombre" required>
            </div>

            <div class="form-group">
                <label for="clave">Contraseña:</label>
                <input type="password" id="clave" name="clave" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="button-submit">Enviar</button>
            </div>

            <a href='<?php echo BASE_URL; ?>/index.php'>Volver</a>
        </form>
    </div>
</body>
</html>
