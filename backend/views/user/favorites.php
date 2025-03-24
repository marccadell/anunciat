<?php
    session_start();
    require_once '../../../config.php';
    require_once '../../../database/db.php';
    include '../../../includes/header.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../error.php?error=Debes iniciar sesion para gestionar favoritos");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $ad_id = $_POST['ad_id'] ?? null;
    $action = $_POST['action'] ?? null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!$ad_id || !in_array($action, ['add', 'remove'])) {
            echo json_encode(["success" => false, "message" => "Solicitud inválida"]);
            exit();
        }

        if ($action === 'add') {
            $stmt = $conn->prepare("INSERT INTO favoritos (id_estudiante, id_anuncio) VALUES (?, ?) ON DUPLICATE KEY UPDATE id_favorito = id_favorito");
        } else {
            $stmt = $conn->prepare("DELETE FROM favoritos WHERE id_estudiante = ? AND id_anuncio = ?");
        }

        $stmt->bind_param("ii", $user_id, $ad_id);
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();

        echo json_encode(["success" => $success]);
        exit();
    }

    $stmt = $conn->prepare("SELECT a.*, e.ubicacion AS usu_ubicacion, e.email, e.foto_perfil, c.nombre_categoria, GROUP_CONCAT(i.ruta_imagen) AS fotos FROM favoritos f INNER JOIN anuncios a ON f.id_anuncio = a.id_anuncio LEFT JOIN imagenes_anuncios i ON a.id_anuncio = i.id_anuncio INNER JOIN estudiantes e ON a.id_estudiante = e.id_estudiante INNER JOIN categorias c ON a.id_categoria = c.id_categoria WHERE f.id_estudiante = ? GROUP BY a.id_anuncio");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $favorites = $stmt->get_result();
    $stmt->close();
    $conn->close();

    function formatFechaPublicacion($fecha) {
        $ahora = new DateTime();
        $fechaPublicacion = new DateTime($fecha);
        $diferencia = $ahora->diff($fechaPublicacion);

        if ($diferencia->days == 0) {
            return 'Publicado a las ' . $fechaPublicacion->format('H:i');
        } elseif ($diferencia->days <= 30) {
            return 'Publicado hace ' . $diferencia->days . ' d';
        } else {
            return 'Publicado hace ' . floor($diferencia->days / 30) . ' m';
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Favoritos</title>
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/styles/favorites.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/styles/adCard.css">
</head>
<body>
    <div class="main-content">
        <div class="container">
            <h1>Mis Anuncios Favoritos</h1>

            <?php if ($favorites->num_rows > 0): ?>
                <div class='anuncios-container'>
                    <?php while ($row = $favorites->fetch_assoc()): ?>
                        <div class='anuncio-card'>
                            <div class='anuncio-info'>
                                <!-- Cabecera del anuncio -->
                                <div class="anuncio-header">
                                    <img src='<?= BASE_URL . "/" . htmlspecialchars($row['foto_perfil']) ?>' alt='Foto de perfil' class='perfil-foto'>
                                    <p class='hora-publicacion'><?= formatFechaPublicacion($row['fecha_publicacion']) ?></p>
                                    <p class="ubicacion-anuncio">Venta de <?= htmlspecialchars($row['tipo_producto']) ?> (<?= htmlspecialchars($row['usu_ubicacion']) ?>)</p>
                                </div>
                                <!-- Titulo del anuncio -->
                                <h2 class="titulo"><?= htmlspecialchars($row['titulo']) ?></h2>
                                <!-- Descripcion del anuncio -->
                                <p class='descripcion'><?= nl2br(htmlspecialchars($row['descripcion'])) ?></p>
                                <!-- Tags especificaciones  -->

                                <div class="anuncio-tags">
                                    <div class="tags-container">
                                        <div class='tags'>
                                            <span>Categoria: <?= htmlspecialchars($row['nombre_categoria']) ?></span>
                                            <span><?= htmlspecialchars($row['estado']) ?></span>
                                        </div>
                                        <div class="precio">
                                            <span><?= number_format($row['precio'], 2) ?> €</span>
                                        </div>
                                    </div>

                                    <!-- Boton de contacto  -->
                                    <?php if (isset($_SESSION['user_id'])): ?>
                                        <button class='contacto-btn' onclick='mostrarEmail("<?= $row['email'] ?>")'>📞 Contacto</button>
                                    <?php else: ?>
                                        <p class='login-message'>Debes iniciar sesión para ver la información de contacto.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Botón de quitar de favoritos -->
                            <button id="fav-btn-<?= $row['id_anuncio'] ?>" class="remove-fav-btn"
                            onclick="toggleFavorite(<?= $row['id_anuncio'] ?>, 'fav-btn-<?= $row['id_anuncio'] ?>')">❌</button>

                            <!-- Mostrar imagenes del anuncio -->
                            <?php if (!empty($row['fotos'])):
                                $fotos = explode(',', $row['fotos']); ?>
                                <div class='anuncio-imagen'>
                                    <img src='<?= BASE_URL . "/" . htmlspecialchars($fotos[0]) ?>' alt='Imagen del anuncio'>
                                    <button class='ver-fotos-btn' onclick='abrirModal(<?= json_encode($fotos) ?>)'>Ver más fotos <div class="icon-fotos-container"><img class="icon-fotos" src="<?php echo BASE_URL; ?>/assets/icons/more.png"></div></button>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p>No tienes anuncios en favoritos.</p>
            <?php endif; ?>

            <a href='<?= BASE_URL ?>/index.php'>Volver</a>
        </div>

        <div id="modalFotos" class="modal">
            <div class="modal-content">
                <span class="close" onclick="cerrarModal()">&times;</span>
                <div class="carrusel-container">
                    <button class="carrusel-btn izq" onclick="cambiarImagen(-1)">❮</button>
                    <div id="carrusel" class="carrusel"></div>
                    <button class="carrusel-btn der" onclick="cambiarImagen(1)">❯</button>
                </div>
            </div>
        </div>

        <div id="modalEmail" class="modal">
            <div class="modal-content">
                <span class="close" onclick="cerrarModalEmail()">&times;</span>
                <p>Correo del vendedor: <strong id="emailVendedor"></strong></p>
            </div>
        </div>
    </div>


    <script>
        let indiceImagen = 0;
        let imagenes = [];

        function abrirModal(fotos) {
            imagenes = fotos;
            indiceImagen = 0;
            actualizarCarrusel();
            document.getElementById('modalFotos').style.display = "flex";
        }

        function cerrarModal() {
            document.getElementById('modalFotos').style.display = "none";
        }

        function cambiarImagen(direccion) {
            indiceImagen += direccion;
            if (indiceImagen < 0) indiceImagen = imagenes.length - 1;
            else if (indiceImagen >= imagenes.length) indiceImagen = 0;
            actualizarCarrusel();
        }

        function actualizarCarrusel() {
            let carrusel = document.getElementById('carrusel');
            carrusel.innerHTML = `<img src="<?php echo BASE_URL; ?>/${imagenes[indiceImagen]}" alt="Imagen del anuncio">`;
        }

        function toggleFavorite(ad_id, buttonId) {
            fetch("<?php echo BASE_URL; ?>/backend/controllers/favorites.proc.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `ad_id=${ad_id}&action=remove`
            }).then(response => response.json())
            .then(data => {
                if (data.success) location.reload();
                else alert("Error: " + data.message);
            }).catch(error => console.error("Error en la solicitud:", error));
        }

        function mostrarEmail(email) {
            document.getElementById('emailVendedor').innerText = email;
            document.getElementById('modalEmail').style.display = "block";
        }

        function cerrarModalEmail() {
            document.getElementById('modalEmail').style.display = "none";
        }
    </script>

<?php
    include '../../../includes/footer.php';
?>
