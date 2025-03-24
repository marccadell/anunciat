    <?php if ($result->num_rows > 0): ?>
        <div class='anuncios-container'>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class='anuncio-card'>
                    <div class='anuncio-info' onclick="mostrarModalAnuncio()">
                        <div class="anuncio-header">
                            <img src='<?= BASE_URL . "/" . htmlspecialchars($row['usu_foto']) ?>' alt='Foto de perfil' class='perfil-foto'>
                            <p class='hora-publicacion'><?= formatFechaPublicacion($row['fecha_publicacion']) ?></p>
                            <p class="ubicacion-anuncio">Venta de <?= htmlspecialchars($row['tipo_producto']) ?> (<?= htmlspecialchars($row['usu_ubicacion']) ?>)</p>
                        </div>
                        <h2 class="titulo"><?= htmlspecialchars($row['titulo']) ?></h2>
                        <p class='descripcion'><?= nl2br(htmlspecialchars($row['descripcion'])) ?></p>
                        <div class="anuncio-tags">
                            <div class="tags-container">
                                <span class="categoria">Categoria: <?= htmlspecialchars($row['nombre_categoria']) ?></span>
                                <span>Estado: <?= htmlspecialchars($row['estado_producto']) ?></span>
                                <div class='tags'>
                                    <span><?= htmlspecialchars($row['tag1']) ?></span>
                                    <span><?= htmlspecialchars($row['tag2'] ?? '') ?></span>
                                    <span><?= htmlspecialchars($row['tag3'] ?? '') ?></span>
                                    <span><?= htmlspecialchars($row['tag4'] ?? '') ?></span>
                                    <span><?= htmlspecialchars($row['tag5'] ?? '') ?></span>
                                </div>
                                <div class="precio">
                                    <span><?= number_format($row['precio'], 2) ?> €</span>
                                </div>
                            </div>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <button class='contacto-btn' onclick='mostrarEmail("<?= $row['usu_email'] ?>")'>📞 Contacto</button>
                            <?php else: ?>
                                <p class='login-message'>Debes iniciar sesión para ver la información de contacto.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <button id="fav-btn-<?= $row['id_anuncio'] ?>" class="favorito-btn"
                            onclick="toggleFavorite(<?= $row['id_anuncio'] ?>, 'fav-btn-<?= $row['id_anuncio'] ?>')">
                            <?= $row['es_favorito'] ? '❤️' : '🤍' ?>
                        </button>
                    <?php endif; ?>
                    <?php if (!empty($row['fotos'])):
                        $fotos = explode(',', $row['fotos']); ?>
                        <div class='anuncio-imagen'>
                            <img class="imagen-foto" src='<?= BASE_URL . "/" . htmlspecialchars($fotos[0]) ?>' alt='Imagen del anuncio'>
                            <button class='ver-fotos-btn' onclick='abrirModal(<?= json_encode($fotos) ?>)'>Ver más fotos</button>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No hay anuncios que coincidan con tu búsqueda.</p>
    <?php endif; ?>

    