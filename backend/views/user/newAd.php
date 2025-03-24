<?php
    session_start();
    require_once '../../../config.php';
    require_once '../../../database/db.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../../../index.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT id_categoria, nombre_categoria FROM categorias");
    $stmt->execute();
    $result = $stmt->get_result();
    $categorias = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $stmt = $conn->prepare("SELECT DISTINCT estado_producto FROM familias");
    $stmt->execute();
    $result = $stmt->get_result();
    $tipos_producto = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar Anuncio</title>
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/styles/global.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/styles/newAd.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/styles/footer.css">
</head>
<body>
    <div class="main-content">
        <div class="container">
            <h1>Publicar Anuncio</h1>

            <form action="<?php echo BASE_URL; ?>/backend/controllers/newAd.proc.php" method="post" enctype="multipart/form-data">
                <label>Nombre del Artículo:</label>
                <input type="text" name="nombre_articulo" required>

                <label>Condición:</label>
                <select name="estado_producto" required>
                    <option value="Nuevo">Nuevo</option>
                    <option value="Excelente">Excelente</option>
                    <option value="Bueno">Bueno</option>
                    <option value="Regular">Regular</option>
                    <option value="Mal Estado">Mal Estado</option>
                </select>

                <label>Precio (€):</label>
                <input type="number" name="precio" min="0" step="0.01" required>

                <label>Categoría:</label>
                <select name="id_categoria" required>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?php echo $categoria['id_categoria']; ?>">
                            <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Descripción:</label>
                <textarea name="descripcion" required></textarea>

                <label>Características del Producto:</label>
                <input type="text" name="tag1" placeholder="Ejemplo: Kilometraje, Tamaño, Material, Marca" required>
                <input type="text" name="tag2" placeholder="Ejemplo: Año de fabricación, Color, Modelo">
                <input type="text" name="tag3" placeholder="Tag opcional">
                <input type="text" name="tag4" placeholder="Tag opcional">
                <input type="text" name="tag5" placeholder="Tag opcional">

                <label>Subir Fotos (Mínimo 1, Máximo 5):</label>
                <input type="file" name="fotos[]" accept="image/*" multiple required>

                <button type="submit">Publicar Anuncio</button>
            </form>

            <a href="<?php echo BASE_URL; ?>/index.php">Volver</a>
        </div>
    </div>
    
<?php include('../../../includes/footer.php'); ?>
