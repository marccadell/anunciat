<?php
    session_start();
    require_once '../../database/db.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../../index.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id_estudiante = $_SESSION['user_id'];
        $titulo = trim($_POST['nombre_articulo']);
        $precio = $_POST['precio'];
        $id_categoria = $_POST['id_categoria'];
        $descripcion = trim($_POST['descripcion']) ?: null;
        $estado_producto = isset($_POST['estado_producto']) ? $_POST['estado_producto'] : 'Bueno';
        $tag1 = trim($_POST['tag1']);
        $tag2 = trim($_POST['tag2']) ?: null;
        $tag3 = trim($_POST['tag3']) ?: null;
        $tag4 = trim($_POST['tag4']) ?: null;
        $tag5 = trim($_POST['tag5']) ?: null;

        if (empty($_FILES['fotos']['name'][0])) {
            header("Location: ../views/error.php?error=Debes subir al menos 1 imagen.");
            exit();
        }

        if (count($_FILES['fotos']['name']) > 5) {
            header("Location: ../views/error.php?error=No puedes subir más de 5 imágenes.");
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO anuncios (id_estudiante, id_categoria, titulo, descripcion, precio) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iissd", $id_estudiante, $id_categoria, $titulo, $descripcion, $precio);

        if ($stmt->execute()) {
            $id_anuncio = $stmt->insert_id;
            $stmt->close();

            $stmt = $conn->prepare("INSERT INTO familias (id_anuncio, id_categoria, id_estudiante, estado_producto, tag1, tag2, tag3, tag4, tag5) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiissssss", $id_anuncio, $id_categoria, $id_estudiante, $estado_producto, $tag1, $tag2, $tag3, $tag4, $tag5);            
            $stmt->execute();
            $stmt->close();

            $ruta_ads = '../../assets/imgs/ads/';
            foreach ($_FILES['fotos']['tmp_name'] as $key => $tmp_name) {
                $fileExtension = strtolower(pathinfo($_FILES['fotos']['name'][$key], PATHINFO_EXTENSION));

                if ($fileExtension !== "jpg") {
                    header("Location: ../views/error.php?error=Solo se permiten imágenes en formato .jpg.");
                    exit();
                }

                $fileName = uniqid() . ".jpg";
                $ruta_imagen = 'assets/imgs/ads/' . $fileName;

                if (move_uploaded_file($tmp_name, $ruta_ads . $fileName)) {
                    $stmt = $conn->prepare("INSERT INTO imagenes_anuncios (id_anuncio, ruta_imagen) VALUES (?, ?)");
                    $stmt->bind_param("is", $id_anuncio, $ruta_imagen);
                    $stmt->execute();
                    $stmt->close();
                }
            }

            $conn->close();
            header("Location: ../../index.php?success=Anuncio publicado correctamente");
            exit();
        } else {
            $stmt->close();
            $conn->close();
            header("Location: ../views/error.php?error=Error al publicar el anuncio");
            exit();
        }
    }
?>
