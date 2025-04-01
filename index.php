<?php
session_start();
require_once 'config.php';
require_once 'database/db.php';
include 'includes/header.php';

$categorias = $conn->query("SELECT id_categoria, nombre_categoria FROM categorias")->fetch_all(MYSQLI_ASSOC);

if (isset($_GET['search']) || isset($_GET['categoria'])) {
    $search = $_GET['search'] ?? '';
    $categoria = $_GET['categoria'] ?? '';

    $query = "SELECT id_anuncio, id_estudiante, id_categoria, titulo, descripcion FROM anuncios WHERE (titulo LIKE '%$search%' OR descripcion LIKE '%$search%')";
    if ($categoria) {
        $query .= " AND id_categoria = $categoria";
    }

    $anuncios = $conn->query($query)->fetch_all(MYSQLI_ASSOC);

    header("Location: backend/views/ad/viewAd.php?search=$search&categoria=$categoria");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/styles/index.css">
</head>

<body>
    <div class="main-content">
        <div class="buscador-container">
            <h1><strong>¿Qué quieres encontrar?</strong></h1>
            <form action="" method="GET" class="buscador-form">
                <input type="text" name="search" id="searchInput" placeholder="Estoy buscando..." value="<?php echo $_GET['search'] ?? ''; ?>">
                <input type="hidden" name="categoria" id="categoriaSeleccionada" value="">
                <button type="button" class="category-btn" onclick="abrirModalCategorias()" id="botonCategoria">Todas las categorías</button>
                <button type="submit" class="search-btn"><img class="icon-index" src="<?= BASE_URL ?>/assets/icons/search.png" /></button>
            </form>

            <div id="modalCategorias" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="cerrarModalCategorias()">&times;</span>
                    <h2>Categorías</h2>
                    <form action="" method="GET" class="buscador-category">
                        <input type="text" id="buscadorCategoria" placeholder="Buscar una categoría..." onkeyup="filtrarCategorias()">
                    </form>
                    <ul id="listaCategorias">
                        <li data-categoria="" onclick="seleccionarCategoria('Todas las categorías', '')">Todas las categorías</li>
                        <?php foreach ($categorias as $categoria): ?>
                            <li data-categoria="<?php echo $categoria['id_categoria']; ?>" onclick="seleccionarCategoria('<?php echo htmlspecialchars($categoria['nombre_categoria']); ?>', '<?php echo $categoria['id_categoria']; ?>')">
                                <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="categorias-container">
                <?php foreach ($categorias as $categoria): ?>
                    <a href="<?php echo BASE_URL; ?>/backend/views/ad/viewAd.php?categoria=<?php echo $categoria['id_categoria']; ?>" class="categoria-card">
                        <img src="<?php echo BASE_URL; ?>/assets/icons/<?php echo strtolower($categoria['nombre_categoria']); ?>.png" alt="Icono de <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>" class="categoria-icon">
                        <p><?php echo htmlspecialchars($categoria['nombre_categoria']); ?></p>
                    </a>
                <?php endforeach; ?>
                <div class="carousel-container">
                    <div class="carousel-slide">

                        <div class="carousel-item">
                            <img src="assets/imgs/publi/banner1.jpg" alt="Publicidad 1">
                            <div class="carousel-text">Vende o compra lo que necesites a otros estudiantes!</div>
                        </div>

                        <div class="carousel-item">
                            <img src="assets/imgs/publi/banner2.jpg" alt="Publicidad 2">
                            <div class="carousel-text">Encuentra piso si eres estudiante cerca de ti</div>
                        </div>

                        <div class="carousel-item">
                            <img src="assets/imgs/publi/banner3.jpeg" alt="Publicidad 3">
                            <div class="carousel-text">Compra tecnologia o lo que te haga falta para estudiar</div>
                        </div>

                    </div>


                </div>

                <div class="como-funciona-section">
                    <h2>📦 ¿Cómo funciona Anuncia’t?</h2>
                    <div class="pasos-container">

                        <div class="paso-card">
                            <div class="paso-icon">🛒</div>
                            <h4>1. Publica tu anuncio</h4>
                            <p>Completa el formulario y sube tus fotos fácilmente.</p>
                        </div>

                        <div class="paso-card">
                            <div class="paso-icon">📨</div>
                            <h4>2. Recibe mensajes</h4>
                            <p>Otros estudiantes interesados te contactan directamente.</p>
                        </div>

                        <div class="paso-card">
                            <div class="paso-icon">🤝</div>
                            <h4>3. Concreta la venta</h4>
                            <p>Acuerda los detalles y finaliza la transacción.</p>
                        </div>

                    </div>
                </div>

                <div class="promo-cards-container">

                    <div class="promo-card">
                        <div class="promo-content">
                            <h3>Vende lo que ya no usas</h3>
                            <p>Publica tu anuncio totalmente <strong>GRATIS</strong> y encuentra compradores rápido entre estudiantes.</p>
                        </div>
                        <img src="assets/imgs/publi/vende.jpg" alt="Vender en Anuncia't">
                    </div>

                    <div class="promo-card">
                        <div class="promo-content">
                            <h3>Encuentra lo que necesitas</h3>
                            <p>Explora cientos de anuncios pensados para estudiantes como tú. Fácil, rápido y local.</p>
                        </div>
                        <img src="assets/imgs/publi/encuentra.jpg" alt="Buscar por Anuncia't">
                    </div>

                </div>


                <div class="beneficios-section">

                    <div class="reseñas-section">
                        <h2> Lo que dicen nuestros estudiantes</h2>

                        <div class="reseñas-fila">

                            <div class="reseña-card">
                                <img src="assets/imgs/publi/laptop.jpg" alt="Laptop usada">
                                <div class="reseña-texto">
                                    <p class="reseña-cita">“Vendí mi portátil en menos de 48 horas. Muy fácil y seguro.”</p>
                                    <div class="reseña-autor"><strong>Andrea G.</strong> – Ingeniería Informática, 📍 Valencia</div>
                                </div>
                            </div>

                            <div class="reseña-card">
                                <img src="assets/imgs/publi/englishClass.jpg" alt="Clases inglés">
                                <div class="reseña-texto">
                                    <p class="reseña-cita">“He conseguido varios alumnos para mis clases gracias a esta plataforma.”</p>
                                    <div class="reseña-autor"><strong>Pablo M.</strong> – Filología Inglesa, 📍 Sevilla</div>
                                </div>
                            </div>

                            <div class="reseña-card">
                                <img src="assets/imgs/publi/silla.jpg" alt="Silla estudio">
                                <div class="reseña-texto">
                                    <p class="reseña-cita">“Tenía cosas que no usaba y logré vender todo entre estudiantes.”</p>
                                    <div class="reseña-autor"><strong>Marta R.</strong> – Psicología, 📍 Barcelona</div>
                                </div>
                            </div>

                            <div class="reseña-card">
                                <img src="assets/imgs/publi/mochila.jpg" alt="Mochila">
                                <div class="reseña-texto">
                                    <p class="reseña-cita">“Vendí mi mochila del año pasado en un día. ¡Rápido y cómodo!”</p>
                                    <div class="reseña-autor"><strong>Lucas D.</strong> – Arquitectura</div>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="faq-section">
                        <h2>❓ Preguntas frecuentes</h2>

                        <div class="faq-item">
                            <input type="checkbox" id="faq1" hidden>
                            <label class="faq-question" for="faq1">¿Cuánto cuesta publicar?</label>
                            <div class="faq-answer">
                                <p>Publicar es completamente gratuito para todos los estudiantes registrados.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <input type="checkbox" id="faq2" hidden>
                            <label class="faq-question" for="faq2">¿Necesito registrarme?</label>
                            <div class="faq-answer">
                                <p>Sí, necesitas una cuenta para gestionar tus anuncios y recibir mensajes.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <input type="checkbox" id="faq3" hidden>
                            <label class="faq-question" for="faq3">¿Puedo guardar un anuncio?</label>
                            <div class="faq-answer">
                                <p>Sí. Puedes guardar un anuncio en la seccion favoritos para futuras compras.</p>
                            </div>
                        </div>
                    </div>

                </div>


            </div>
        </div>
    </div>
    </div>

    </div>


    <script>
        function abrirModalCategorias() {
            document.getElementById('modalCategorias').style.display = 'block';
        }

        function cerrarModalCategorias() {
            document.getElementById('modalCategorias').style.display = 'none';
        }

        function filtrarCategorias() {
            const filtro = document.getElementById('buscadorCategoria').value.toLowerCase();
            const categorias = document.querySelectorAll('#listaCategorias li');
            categorias.forEach(cat => {
                cat.style.display = cat.textContent.toLowerCase().includes(filtro) ? 'block' : 'none';
            });
        }

        function seleccionarCategoria(nombreCategoria, id) {
            document.getElementById('categoriaSeleccionada').value = id;
            document.getElementById('botonCategoria').textContent = nombreCategoria;
            cerrarModalCategorias();
        }
    </script>

    <?php
    include 'includes/footer.php';
    ?>