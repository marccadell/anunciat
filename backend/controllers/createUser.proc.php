<?php
    session_start();
    require_once '../../database/db.php';
    require_once '../../config.php';

    $usu_nom = trim($_POST['usu_nom']);
    $usu_apellido = trim($_POST['usu_apellido']);
    $usu_email = trim($_POST['usu_email']);
    $usu_password = trim($_POST['usu_password']);
    $repetirClave = trim($_POST['repetirClave']);
    $usu_escuela = trim($_POST['usu_escuela']);
    $usu_ubicacion = trim($_POST['usu_ubicacion']);
    $usu_telefono = trim($_POST['usu_telefono']);
    $usu_fecha_nacimiento = trim($_POST['usu_fecha_nacimiento']);
    $usu_genero = trim($_POST['usu_genero']);

    $stmt = $conn->prepare("SELECT COUNT(*) FROM estudiantes WHERE LOWER(nombre) = LOWER(?) OR LOWER(email) = LOWER(?)");
    $stmt->bind_param("ss", $usu_nom, $usu_email);
    $stmt->execute();
    $stmt->bind_result($usuarioExistente);
    $stmt->fetch();
    $stmt->close();

    if ($usuarioExistente > 0) {
        header("Location: " . BASE_URL . "/backend/views/error.php?error=El usuario o correo ya está en uso");
        exit();
    }

    if ($usu_password !== $repetirClave) {
        header("Location: " . BASE_URL . "/backend/views/error.php?error=Las contraseñas no coinciden");
        exit();
    }

    $usu_foto = "";
    if (isset($_FILES['usu_foto']) && $_FILES['usu_foto']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['usu_foto']['tmp_name'];
        $fileName = uniqid() . "." . pathinfo($_FILES['usu_foto']['name'], PATHINFO_EXTENSION);
        $usu_foto = "assets/imgs/profiles/" . $fileName;

        if (!move_uploaded_file($fileTmpPath, "../../" . $usu_foto)) {
            header("Location: " . BASE_URL . "/backend/views/error.php?error=Error al subir la imagen.");
            exit();
        }
    } else {
        $usu_foto = "assets/imgs/dummie/estudiante.png";
    }

    $hashedPassword = md5($usu_password);

    $stmt = $conn->prepare("INSERT INTO estudiantes (nombre, apellido, email, password, escuela, ubicacion, telefono, fecha_nacimiento, genero, foto_perfil) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $usu_nom, $usu_apellido, $usu_email, $hashedPassword, $usu_escuela, $usu_ubicacion, $usu_telefono, $usu_fecha_nacimiento, $usu_genero, $usu_foto);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: " . BASE_URL . "/index.php");
        exit();
    } else {
        $stmt->close();
        $conn->close();
        header("Location: " . BASE_URL . "/backend/views/error.php?error=Error al registrar el usuario.");
        exit();
    }
?>
