<?php
    session_start();
    require_once '../../database/db.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../../index.php");
        exit();
    }

    $id_usuario = $_SESSION['user_id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($_SESSION['es_admin']) {
            $stmt = $conn->prepare("SELECT COUNT(*) FROM administradores WHERE id_admin != ?");
            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();
            $stmt->bind_result($admin_count);
            $stmt->fetch();
            $stmt->close();

            if ($admin_count < 1) {
                header("Location: ../views/error.php?error=No puedes eliminar tu cuenta si eres el único administrador");
                exit();
            }

            $stmt = $conn->prepare("DELETE FROM administradores WHERE id_admin = ?");
        } else {
            $stmt = $conn->prepare("DELETE FROM estudiantes WHERE id_estudiante = ?");
        }

        $stmt->bind_param("i", $id_usuario);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            session_destroy();
            header("Location: ../../index.php?success=Cuenta eliminada correctamente");
            exit();
        } else {
            header("Location: ../views/error.php?error=Error al eliminar la cuenta");
            exit();
        }
    }
?>