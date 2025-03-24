<?php
    session_start();
    require_once '../../config.php';
    require_once '../../database/db.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../../index.php");
        exit();
    }

    $id_usuario = $_SESSION['user_id'];

    if ($_SESSION['es_admin']) {
        $stmt = $conn->prepare("SELECT nombre, email, foto_perfil FROM administradores WHERE id_admin = ?");
    } else {
        $stmt = $conn->prepare("SELECT nombre, apellido, email, escuela, ubicacion, telefono, fecha_nacimiento, genero, foto_perfil FROM estudiantes WHERE id_estudiante = ?");
    }

    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    $conn->close();

    $fotoPerfil = !empty($user['foto_perfil']) ? BASE_URL . '/' . $user['foto_perfil'] : (
        $_SESSION['es_admin'] ? BASE_URL . '/assets/imgs/dummie/administrador.png' : BASE_URL . '/assets/imgs/dummie/estudiante.png'
    );
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Usuario</title>
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/styles/modifyUser.css">
</head>
<body>
    <div class="container">
        <h1>Modificar Usuario</h1>

        <form class="modify-form" action="<?php echo BASE_URL; ?>/backend/controllers/modifyUser.proc.php" method="post" enctype="multipart/form-data">
            <?php if (!$_SESSION['es_admin']) : ?>
                <div class="form-group">
                    <label>Nombre:</label>
                    <input type="text" name="nombre" value="<?php echo htmlspecialchars($user['nombre']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Apellido:</label>
                    <input type="text" name="apellido" value="<?php echo htmlspecialchars($user['apellido']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Escuela:</label>
                    <input type="text" name="escuela" value="<?php echo htmlspecialchars($user['escuela']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Residencia:</label>
                    <input type="text" name="ubicacion" value="<?php echo htmlspecialchars($user['ubicacion']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Teléfono:</label>
                    <input type="text" name="telefono" value="<?php echo htmlspecialchars($user['telefono']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Fecha de Nacimiento:</label>
                    <input type="date" name="fecha_nacimiento" value="<?php echo htmlspecialchars($user['fecha_nacimiento']); ?>">
                </div>

                <div class="form-group">
                    <label>Género:</label>
                    <select name="genero">
                        <option value="Masculino" <?php echo ($user['genero'] == 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
                        <option value="Femenino" <?php echo ($user['genero'] == 'Femenino') ? 'selected' : ''; ?>>Femenino</option>
                        <option value="Otro" <?php echo ($user['genero'] == 'Otro') ? 'selected' : ''; ?>>Otro</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Foto de perfil actual:</label>
                    <img src="<?php echo $fotoPerfil; ?>" alt="Foto de perfil" class="profile-photo">
                </div>

                <div class="form-group">
                    <label for="fotoPerfil">Subir Nueva Foto:</label>
                    <input type="file" id="fotoPerfil" name="fotoPerfil" accept="image/*">
                </div>

                <div class="form-group">
                    <?php if (!str_contains($fotoPerfil, 'dummie')): ?>
                        <label>
                            <input type="checkbox" name="eliminarFoto"> Eliminar foto de perfil
                        </label>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label>Nueva Contraseña:</label>
                <input type="password" name="nueva_password">
            </div>

            <div class="form-group">
                <label>Confirmar Contraseña:</label>
                <input type="password" name="confirmar_password">
            </div>

            <button type="submit">Actualizar</button>
        </form>

        <a href="<?php echo BASE_URL; ?>/backend/views/profile.php">Volver</a>
    </div>
</body>
</html>