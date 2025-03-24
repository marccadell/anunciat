<?php
    if (!empty($_SESSION['user_id'])) {
        $id = $_SESSION['user_id'];

        if ($_SESSION['es_admin']) {
            $stmt = $conn->prepare("SELECT nombre, foto_perfil FROM administradores WHERE id_admin = ?");
        } else {
            $stmt = $conn->prepare("SELECT nombre, foto_perfil FROM estudiantes WHERE id_estudiante = ?");
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        $fotoPerfil = $user['foto_perfil'] ? BASE_URL . '/' . $user['foto_perfil'] : (
            $_SESSION['es_admin'] ? BASE_URL . '/assets/imgs/dummie/administrador.png' : BASE_URL . '/assets/imgs/dummie/estudiante.png'
        );
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ANUNCIA'T</title>
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/styles/global.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/styles/header.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/styles/footer.css">
</head>
<body>
    <nav class="navbar">
        <h2 class="nav-title"><a href="<?= BASE_URL ?>/index.php"><img class="logo" src="<?= BASE_URL ?>/assets/imgs/logo.png"></a></h2>
        <div class="user-profile">
            <?php if (!empty($_SESSION['user_id'])) { ?>
                <a href="<?= BASE_URL ?>/backend/views/user/favorites.php">Favoritos</a>

                <?php if (!$_SESSION['es_admin']) : ?>
                    <a href="<?= BASE_URL ?>/backend/views/user/newAd.php">Publicar</a>
                <?php endif; ?>

                <img src="<?= $fotoPerfil ?>" alt="Foto de perfil" class="profile-photo">
                
                <div class="user-options">
                    <a href="<?= BASE_URL ?>/backend/views/profile.php">Mi Perfil</a>

                    <?php if ($_SESSION['es_admin']) : ?>
                        <a href="<?= BASE_URL ?>/backend/views/admin/dashboard.php">Dashboard</a>
                        <a href="<?= BASE_URL ?>/backend/views/admin/validateAd.php">Validar Anuncios</a>
                        <a href="<?= BASE_URL ?>/backend/views/admin/adminAds.php">Administrar Anuncios</a>
                        <a href="<?= BASE_URL ?>/backend/views/admin/adminCategories.php">Administrar Categorías</a>
                        <a href="<?= BASE_URL ?>/backend/views/admin/adminUsers.php">Administrar Usuarios</a>
                    <?php else : ?>
                        <a href="<?= BASE_URL ?>/backend/views/user/myAds.php">Mis Anuncios</a>
                        <a href="<?= BASE_URL ?>/backend/views/user/editAd.php">Editar Anuncio</a>
                        <a href="<?= BASE_URL ?>/backend/views/user/mySelectionAd.php">Mi Selección de Anuncios</a>
                        <a href="<?= BASE_URL ?>/backend/views/user/contact.php">Contactar</a>
                    <?php endif; ?>
                    
                    <a href="<?= BASE_URL ?>/backend/controllers/logout.proc.php">Cerrar Sesión</a>
                </div>
            <?php } else { ?>
                <a href="<?= BASE_URL ?>/backend/views/login.php">Login</a>
                <a href="<?= BASE_URL ?>/backend/views/createUser.php">Sign In</a>
            <?php } ?>
        </div>
    </nav>
