<?php
    session_start();
    require_once('../../config.php');
    require_once('../../database/db.php');

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../../index.php");
        exit();
    }

    $id_usuario = $_SESSION['user_id'];

    if ($_SESSION['es_admin']) {
        $stmt = $conn->prepare("SELECT nombre, email, foto_perfil FROM administradores WHERE id_admin = ?");
    } else {
        $stmt = $conn->prepare("SELECT nombre, email, foto_perfil FROM estudiantes WHERE id_estudiante = ?");
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
    <title>Mi Perfil</title>
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/styles/profile.css">
</head>
<body>
    <div class="profile-wrapper">
        <div class="profile-container">
            <img src="<?php echo $fotoPerfil; ?>" alt="Foto de Perfil" class="profile-photo">
            <div class="profile-name"><?php echo htmlspecialchars($user['nombre']); ?></div>
            <div class="profile-email"><?php echo htmlspecialchars($user['email']); ?></div>

            <div class="profile-actions">
                <a href="<?php echo BASE_URL; ?>/backend/views/modifyUser.php">Editar Perfil</a>

                <form id="deleteForm" action="<?php echo BASE_URL; ?>/backend/controllers/deleteAccount.proc.php" method="POST" style="display: inline;">
                    <button type="button" class="delete-button" onclick="confirmDelete()">Eliminar Cuenta</button>
                </form>
            </div>

            <a href="<?php echo BASE_URL; ?>/index.php" class="back-button">Volver a Inicio</a>
        </div>
    </div>

    <script>
        function confirmDelete() {
            if (confirm("¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer.")) {
                document.getElementById("deleteForm").submit();
            }
        }
    </script>
</body>
</html>