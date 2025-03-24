<?php
    session_start();
    require_once '../../database/db.php';

    $nombre = trim($_POST['nombre']);
    $clave = trim($_POST['clave']);

    if (!empty($nombre) && !empty($clave)) {
        $hashedPassword = md5($clave);

        $stmt = $conn->prepare("SELECT id_admin AS id, nombre, foto_perfil FROM administradores WHERE email = ? AND password = ?");
        $stmt->bind_param("ss", $nombre, $hashedPassword);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['es_admin'] = true;
        } else {
            $stmt = $conn->prepare("SELECT id_estudiante AS id, nombre, foto_perfil FROM estudiantes WHERE email = ? AND password = ?");
            $stmt->bind_param("ss", $nombre, $hashedPassword);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nombre'] = $user['nombre'];
                $_SESSION['es_admin'] = false;
            }
        }

        if (isset($_SESSION['user_id'])) {
            if (!empty($user['foto_perfil'])) {
                $_SESSION['fotoPerfil'] = $user['foto_perfil'];
            } else {
                $_SESSION['fotoPerfil'] = $_SESSION['es_admin']
                    ? "../../assets/imgs/dummie/administrador.png"
                    : "../../assets/imgs/dummie/estudiante.png";
            }
            
            if ($_SESSION['es_admin']) {
                header("Location: ../views/admin/dashboard.php");
            } else {
                header("Location: ../../index.php");
            }
            exit();
        } else {
            header("Location: ../views/error.php?error=Correo electrónico o contraseña incorrectos.");
        }

        $stmt->close();
    } else {
        echo "Por favor, completa todos los campos.";
    }

    $conn->close();
?>
