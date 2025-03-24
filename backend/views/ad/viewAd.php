<?php
    session_start();
    require_once '../../../config.php';
    require_once '../../../database/db.php';
    include '../../../includes/header.php';

    $user_id = $_SESSION['user_id'] ?? 0;
    $ad_id = $_GET['ad_id'] ?? 0;
    $search = $_GET['search'] ?? '';
    $categoria = $_GET['categoria'] ?? '';

    $query = "SELECT a.*, e.*, e.ubicacion AS usu_ubicacion, e.email AS usu_email, e.foto_perfil AS usu_foto, c.nombre_categoria,
            f.tag1, f.tag2, f.tag3, f.tag4, f.tag5, f.estado_producto,
            GROUP_CONCAT(ia.ruta_imagen) AS fotos,
            (SELECT COUNT(*) FROM favoritos WHERE id_estudiante = $user_id AND id_anuncio = a.id_anuncio) AS es_favorito
            FROM anuncios a
            LEFT JOIN imagenes_anuncios ia ON a.id_anuncio = ia.id_anuncio
            INNER JOIN estudiantes e ON a.id_estudiante = e.id_estudiante
            INNER JOIN categorias c ON a.id_categoria = c.id_categoria
            LEFT JOIN familias f ON a.id_anuncio = f.id_anuncio
            WHERE 1";

    if ($search) {
        $query .= " AND (a.titulo LIKE '%$search%' OR a.descripcion LIKE '%$search%' OR f.tag1 LIKE '%$search%' OR f.tag2 LIKE '%$search%' OR f.tag3 LIKE '%$search%' OR f.tag4 LIKE '%$search%' OR f.tag5 LIKE '%$search%')";
    }
    if ($categoria) {
        $query .= " AND a.id_categoria = $categoria";
    }

    $query .= " GROUP BY a.id_anuncio ORDER BY a.fecha_publicacion DESC";

    $result = $conn->query($query);
    $result2 = $conn->query($query);

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
    <title>Filtrado de Anuncios</title>
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/styles/viewAd.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/styles/adCard.css">
</head>
<body>
    <div class="main-content">
        <div class="container">
            <h1>Resultados de Búsqueda</h1>
            
            <?php if ($result->num_rows > 0): ?>
            <div class='anuncios-container'>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class='anuncio-card'>
                        <div class='anuncio-info' onclick="mostrarModalAnuncio()">
                            <div class="anuncio-header">
                                <img src='<?= BASE_URL . "/" . htmlspecialchars($row['usu_foto']) ?>' alt='Foto de perfil' class='perfil-foto'>
                                <p class='hora-publicacion'><?= formatFechaPublicacion($row['fecha_publicacion']) ?></p>
                                <p class="ubicacion-anuncio">Venta de <?= htmlspecialchars($row['nombre_categoria']) ?> (<?= htmlspecialchars($row['usu_ubicacion']) ?>)</p>
                            </div>
                            <h2 class="titulo"><?= htmlspecialchars($row['titulo']) ?></h2>
                            <p class='descripcion'><?= nl2br(htmlspecialchars($row['descripcion'])) ?></p>
                            <span class="precio"><?= number_format($row['precio'], 2) ?> €</span>
                            <div class="anuncio-tags">
                                <div class="info-container-tags">
                                    <div class='tags'>
                                        <?php if (!empty($row['tag1'])): ?>
                                            <span><?= htmlspecialchars($row['tag1']) ?></span>
                                        <?php endif; ?>
                                        <?php if (!empty($row['tag2'])): ?>
                                            <span><?= htmlspecialchars($row['tag2']) ?></span>
                                        <?php endif; ?>
                                        <?php if (!empty($row['tag3'])): ?>
                                            <span><?= htmlspecialchars($row['tag3']) ?></span>
                                        <?php endif; ?>
                                        <?php if (!empty($row['tag4'])): ?>
                                            <span><?= htmlspecialchars($row['tag4']) ?></span>
                                        <?php endif; ?>
                                        <?php if (!empty($row['tag5'])): ?>
                                            <span><?= htmlspecialchars($row['tag5']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="anuncio-tags-pago">
                                <div class="info-container-pago">
                                    <span class="estado"><?= htmlspecialchars($row['estado_producto']) ?></span>
                                    <div class="precio-tag">
                                        <span>Preu: <?= number_format($row['precio'], 2) ?> €</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if (!empty($row['fotos'])):
                            $fotos = explode(',', $row['fotos']); ?>
                            <div class='anuncio-imagen'>
                                <img class="img-ad" src='<?= BASE_URL . "/" . htmlspecialchars($fotos[0]) ?>' alt='Imagen del anuncio'>
                                <button class='ver-fotos-btn' onclick='abrirModal(<?= json_encode($fotos) ?>)'>Ver más fotos <div class="icon-fotos-container"><img class="icon-fotos" src="<?php echo BASE_URL; ?>/assets/icons/more.png"></div></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <button id="fav-btn-<?= $row['id_anuncio'] ?>" class="favorito-btn"
                                onclick="toggleFavorite(<?= $row['id_anuncio'] ?>, 'fav-btn-<?= $row['id_anuncio'] ?>')">
                                <?= $row['es_favorito'] ? '❤️' : '🤍' ?>
                            </button>
                        <?php endif; ?>
                        
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No hay anuncios que coincidan con tu búsqueda.</p>
        <?php endif; ?>
            
            <a href='<?= BASE_URL ?>/index.php'>Volver</a>
        </div>

        <div id="modalAnuncio" class="modal-2">
            <div class="modal-content-2">
                <span class="close" onclick="cerrarModalAnuncio()">&times;</span>
                <?php if ($result2->num_rows > 0): ?>
                    <?php while ($anuncio = $result2->fetch_assoc()): ?>
                        <div class="modal-anuncio">
                                <div class='modal-anuncio-descripcion'>
                                    <h2><?php echo $anuncio['titulo']; ?></h2>
                                    <p><?php echo $anuncio['descripcion']; ?></p>
                                    <div class="info-producto-anuncio">
                                        <p><strong>Vendedor: </strong><img src='<?= BASE_URL . "/" . htmlspecialchars($anuncio['usu_foto']) ?>' alt='Foto de perfil' class='perfil-foto'> <?php echo $anuncio['nombre']; ?> <?php echo $anuncio['apellido']; ?></p>
                                        <p class="ubicacion-anuncio"><strong>Ubicación: </strong><?php echo $anuncio['usu_ubicacion']; ?></p>
                                        <p><strong>Categoría:</strong> <?php echo $anuncio['nombre_categoria']; ?></p>
                                        <p><strong>Estado:</strong> <?php echo $anuncio['estado_producto']; ?></p>
                                        <button class="ver-fotos-btn" onclick='abrirModalAnuncioFotos(<?= json_encode($fotos) ?>)'>Ver todas las fotos<div class="icon-fotos-container"><img class="icon-fotos" src="<?php echo BASE_URL; ?>/assets/icons/more.png"></div></button>
                                    </div>
                                </div>
                                <div class='modal-anuncio-familia'>
                                    <div class="tags-container">
                                        <div class='tags'>
                                            <p><strong>Informació bàsica:</strong></p>
                                            <span><?= htmlspecialchars($anuncio['tag1']) ?>dasgdg</span>
                                            <span><?= htmlspecialchars($anuncio['tag2'] ?? '') ?>dgdg</span>
                                            <span><?= htmlspecialchars($anuncio['tag3'] ?? '') ?>dgdgdg</span>
                                            <span><?= htmlspecialchars($anuncio['tag4'] ?? '') ?>dgdg</span>
                                            <span><?= htmlspecialchars($anuncio['tag5'] ?? '') ?>dsgdsg</span>
                                        </div>
                                    </div>
                                    <div class="modal-anuncio-precio">
                                        <span>Preu: <?= number_format($anuncio['precio'], 2) ?> €</span>
                                    </div>
                                    <?php if (isset($_SESSION['user_id'])): ?>
                                        <button class='contacto-btn' onclick='mostrarEmail("<?= $anuncio['usu_email'] ?>")'>Clica aquí per contactar</button>
                                    <?php else: ?>
                                        <p class='anuncio-login-message'>Debes iniciar sesión para ver la información de contacto.</p>
                                    <?php endif; ?>

                                </div>

                            </section>
                        </div>
                        

                        <div id="modalFotos2" class="modal-2">
                            <div class="modal-content-2">
                                <span class="close" onclick="cerrarModalAnuncioFotos()">&times;</span>
                                <div class="carrusel-container">
                                    <button class="carrusel-btn izq" onclick="cambiarImagenAnuncio(-1)">❮</button>
                                    <div id="carrusel2" class="carrusel"></div>
                                    <button class="carrusel-btn der" onclick="cambiarImagenAnuncio(1)">❯</button>
                                </div>
                            </div>
                        </div>
                        
                        
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No se puede acceder a la información del anuncio.</p>
                <?php endif; ?>
                
            </div>
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

        function abrirModalAnuncioFotos(fotos) {
            imagenes = fotos;
            indiceImagen = 0;
            actualizarCarruselAnuncio();
            document.getElementById('modalFotos2').style.display = "flex";
        }

        function cerrarModalAnuncioFotos() {
            document.getElementById('modalFotos2').style.display = "none";
        }

        function mostrarModalAnuncio() {
            document.querySelectorAll('.anuncio-card').forEach(card => {
                card.addEventListener('click', function () {
                    document.getElementById('modalAnuncio').style.display = 'flex';
                });
            });
        }

        function cerrarModalAnuncio() {
            document.getElementById('modalAnuncio').style.display = 'none';
        }

        function cambiarImagen(direccion) {
            indiceImagen += direccion;
            if (indiceImagen < 0) indiceImagen = imagenes.length - 1;
            else if (indiceImagen >= imagenes.length) indiceImagen = 0;
            actualizarCarrusel();
        }

        function cambiarImagenAnuncio(direccion) {
            indiceImagen += direccion;
            if (indiceImagen < 0) indiceImagen = imagenes.length - 1;
            else if (indiceImagen >= imagenes.length) indiceImagen = 0;
            actualizarCarruselAnuncio();
        }

        function actualizarCarrusel() {
            let carrusel = document.getElementById('carrusel');
            carrusel.innerHTML = `<img src="<?php echo BASE_URL; ?>/${imagenes[indiceImagen]}" alt="Imagen del anuncio">`;
        }

        function actualizarCarruselAnuncio() {
            let carrusel = document.getElementById('carrusel2');
            carrusel.innerHTML = `<img src="<?php echo BASE_URL; ?>/${imagenes[indiceImagen]}" alt="Imagen del anuncio">`;
        }

        function mostrarEmail(email) {
            document.getElementById('emailVendedor').innerText = email;
            document.getElementById('modalEmail').style.display = "block";
        }

        function cerrarModalEmail() {
            document.getElementById('modalEmail').style.display = "none";
        }

        function toggleFavorite(ad_id) {
            fetch("<?php echo BASE_URL; ?>/backend/controllers/favorites.proc.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `ad_id=${ad_id}&action=toggle`
            }).then(response => response.json())
            .then(data => {
                if (data.success) location.reload();
                else alert("Error al gestionar favoritos");
            }).catch(error => console.error("Error en la solicitud:", error));
        }
    </script>

<?php
    include '../../../includes/footer.php';
?>