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
