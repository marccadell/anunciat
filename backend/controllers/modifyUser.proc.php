<?php
    session_start();
    require_once '../../database/db.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../../index.php");
        exit();
    }

    $id_usuario = $_SESSION['user_id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nueva_password = trim($_POST['nueva_password']);
        $confirmar_password = trim($_POST['confirmar_password']);

        if (!empty($nueva_password)) {
            if (empty($nueva_password) || $nueva_password !== $confirmar_password) {
                header("Location: ../views/error.php?error=Las contraseñas no coinciden o están vacias");
                exit();
            }
            $hashed_password = md5($nueva_password);
        }

        if ($_SESSION['es_admin']) {
            if (!empty($nueva_password)) {
                $stmt = $conn->prepare("UPDATE administradores SET password = ? WHERE id_admin = ?");
                $stmt->bind_param("si", $hashed_password, $id_usuario);
                $stmt->execute();
                $stmt->close();
            }
        } else {
            $nombre = trim($_POST['nombre']);
            $apellido = trim($_POST['apellido']);
            $email = trim($_POST['email']);
            $escuela = trim($_POST['escuela']);
            $ubicacion = trim($_POST['ubicacion']);
            $telefono = trim($_POST['telefono']);
            $fecha_nacimiento = trim($_POST['fecha_nacimiento']);
            $genero = trim($_POST['genero']);

            if (empty($nombre) || empty($email)) {
                header("Location: ../views/error.php?error=El nombre y el correo son obligatorios");
                exit();
            }

            $stmt = $conn->prepare("SELECT COUNT(*) FROM estudiantes WHERE (LOWER(nombre) = LOWER(?) OR LOWER(email) = LOWER(?)) AND id_estudiante != ?");
            $stmt->bind_param("ssi", $nombre, $email, $id_usuario);
            $stmt->execute();
            $stmt->bind_result($usuarioExistente);
            $stmt->fetch();
            $stmt->close();

            if ($usuarioExistente > 0) {
                header("Location: ../views/error.php?error=El nombre de usuario o correo ya está en uso");
                exit();
            }

            if (!empty($nueva_password)) {
                $stmt = $conn->prepare("UPDATE estudiantes SET nombre = ?, apellido = ?, email = ?, escuela = ?, ubicacion = ?, telefono = ?, fecha_nacimiento = ?, genero = ?, password = ? WHERE id_estudiante = ?");
                $stmt->bind_param("sssssssssi", $nombre, $apellido, $email, $escuela, $ubicacion, $telefono, $fecha_nacimiento, $genero, $hashed_password, $id_usuario);
            } else {
                if ($confirmar_password !== $nueva_password) {
                    header("Location: ../views/error.php?error=Las contraseñas no coinciden");
                    exit();
                }
                $stmt = $conn->prepare("UPDATE estudiantes SET nombre = ?, apellido = ?, email = ?, escuela = ?, ubicacion = ?, telefono = ?, fecha_nacimiento = ?, genero = ? WHERE id_estudiante = ?");
                $stmt->bind_param("ssssssssi", $nombre, $apellido, $email, $escuela, $ubicacion, $telefono, $fecha_nacimiento, $genero, $id_usuario);
            }
            $stmt->execute();
            $stmt->close();

            if (!empty($_FILES['fotoPerfil']['name'])) {
                $fileName = uniqid() . "." . pathinfo($_FILES['fotoPerfil']['name'], PATHINFO_EXTENSION);
                $ruta_foto = 'assets/imgs/uploads/' . $fileName;

                if (move_uploaded_file($_FILES['fotoPerfil']['tmp_name'], '../../' . $ruta_foto)) {
                    $stmt = $conn->prepare("UPDATE estudiantes SET foto_perfil = ? WHERE id_estudiante = ?");
                    $stmt->bind_param("si", $ruta_foto, $id_usuario);
                    $stmt->execute();
                    $stmt->close();
                } else {
                    header("Location: ../views/error.php?error=Error al subir la imagen");
                    exit();
                }
            }

            if (isset($_POST['eliminarFoto'])) {
                $stmt = $conn->prepare("SELECT foto_perfil FROM estudiantes WHERE id_estudiante = ?");
                $stmt->bind_param("i", $id_usuario);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
                $stmt->close();

                if ($user && !empty($user['foto_perfil'])) {
                    $fotoActual = '../../' . $user['foto_perfil'];
                    if (file_exists($fotoActual)) {
                        unlink($fotoActual);
                    }
                    $foto_default = 'assets/imgs/dummie/estudiante.png';
                    $stmt = $conn->prepare("UPDATE estudiantes SET foto_perfil = ? WHERE id_estudiante = ?");
                    $stmt->bind_param("si", $foto_default, $id_usuario);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }

        $conn->close();
        header("Location: ../views/profile.php?success=Datos actualizados");
        exit();
    }
?>
