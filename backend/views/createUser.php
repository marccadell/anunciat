<?php
    session_start();
    require_once '../../config.php';
    require_once '../../database/db.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario</title>
    <link rel="stylesheet" type="text/css" href="../../styles/createUser.css">
</head>
<body>
    <div class="container">
        <h1>Crear Usuario</h1>
        <form action="<?= BASE_URL ?>/backend/controllers/createUser.proc.php" method="post" class="register-form" enctype="multipart/form-data">
            <div class="form-group">
                <label for="usu_nom">Nombre:</label>
                <input type="text" id="usu_nom" name="usu_nom" required>
            </div>

            <div class="form-group">
                <label for="usu_apellido">Apellido:</label>
                <input type="text" id="usu_apellido" name="usu_apellido" required>
            </div>

            <div class="form-group">
                <label for="usu_email">Correo Electrónico:</label>
                <input type="email" id="usu_email" name="usu_email" required>
            </div>

            <div class="form-group">
                <label for="usu_escuela">Escuela:</label>
                <input type="text" id="usu_escuela" name="usu_escuela">
            </div>

            <div class="form-group">
                <label for="usu_ubicacion">Residencia:</label>
                <input type="text" id="usu_ubicacion" name="usu_ubicacion">
            </div>

            <div class="form-group">
                <label for="usu_telefono">Teléfono:</label>
                <input type="text" id="usu_telefono" name="usu_telefono">
            </div>

            <div class="form-group">
                <label for="usu_fecha_nacimiento">Fecha de Nacimiento:</label>
                <input type="date" id="usu_fecha_nacimiento" name="usu_fecha_nacimiento">
            </div>

            <div class="form-group">
                <label for="usu_genero">Género:</label>
                <select id="usu_genero" name="usu_genero">
                    <option value="">Selecciona</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>

            <div class="form-group">
                <label for="usu_password">Contraseña:</label>
                <input type="password" id="usu_password" name="usu_password" required>
            </div>

            <div class="form-group">
                <label for="repetirClave">Confirmar Contraseña:</label>
                <input type="password" id="repetirClave" name="repetirClave" required>
            </div>

            <div class="form-group">
                <label for="usu_foto">Foto de perfil (opcional):</label>
                <input class="input-foto" type="file" id="usu_foto" name="usu_foto" accept="image/*">
            </div>

            <div class="form-actions">
                <button type="submit" class="button">Registrar Usuario</button>
            </div>

            <a href='<?= BASE_URL ?>/index.php'>Volver</a>
        </form>
    </div>
