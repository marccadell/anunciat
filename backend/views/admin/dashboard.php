<?php
    session_start();
    require_once ('../../../config.php');
    require_once ('../../../database/db.php');
    include ('../../../includes/header.php');
?>

    <h1>Dashboard</h1>
    <?php
    if (!empty($_SESSION['nombre'])) {
        echo "Bienvenido " . $_SESSION['nombre'];
    } else {}
    ?>

<?php
    include ('../../../includes/footer.php');
?>
