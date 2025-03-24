<?php
    session_start();
    $errorMessage = $_GET['error'] ?? "Ha ocurrido un error desconocido.";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <link rel="stylesheet" type="text/css" href="../../styles/error.css">
</head>
<body>
    <div class="error-container">
        <h1 class="error-title">¡Error!</h1>
        <p class="error-message"><?php echo htmlspecialchars($errorMessage); ?></p>
        <a href="javascript:history.back()" class="back-button">Volver</a>
    </div>
</body>
</html>