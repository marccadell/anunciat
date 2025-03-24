<?php
    session_start();
    require_once '../../database/db.php';

    header("Content-Type: application/json");

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["success" => false, "message" => "Debes iniciar sesión"]);
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $ad_id = $_POST['ad_id'] ?? null;
    $action = $_POST['action'] ?? null;

    if (!$ad_id || !in_array($action, ['add', 'remove', 'toggle'])) {
        echo json_encode(["success" => false, "message" => "Solicitud inválida"]);
        exit();
    }

    if ($action === "toggle") {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM favoritos WHERE id_estudiante = ? AND id_anuncio = ?");
        $stmt->bind_param("ii", $user_id, $ad_id);
        $stmt->execute();
        $stmt->bind_result($favorito_existente);
        $stmt->fetch();
        $stmt->close();

        $action = ($favorito_existente > 0) ? "remove" : "add";
    }

    if ($action === "add") {
        $stmt = $conn->prepare("INSERT INTO favoritos (id_estudiante, id_anuncio) VALUES (?, ?) ON DUPLICATE KEY UPDATE id_favorito = id_favorito");
    } else {
        $stmt = $conn->prepare("DELETE FROM favoritos WHERE id_estudiante = ? AND id_anuncio = ?");
    }

    $stmt->bind_param("ii", $user_id, $ad_id);
    $success = $stmt->execute();
    $stmt->close();
    $conn->close();

    echo json_encode(["success" => $success, "message" => $success ? "Operación exitosa" : "Error al actualizar favoritos"]);
    exit();
?>
