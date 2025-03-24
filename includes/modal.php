<?php
if (!isset($modal_id) || !isset($modal_message) || !isset($modal_action)) {
    die("Error: Faltan parametros para mostrar el modal.");
}
?>

<div id="<?php echo $modal_id; ?>" class="modal">
    <div class="modal-content">
        <p><?php echo $modal_message; ?></p>
        <form action="<?php echo $modal_action; ?>" method="post">
            <button type="submit" class="confirm-btn">Confirmar</button>
            <button type="button" class="cancel-btn" onclick="closeModal('<?php echo $modal_id; ?>')">Cancelar</button>
        </form>
    </div>
</div>

<script>
    function openModal(modalId) {
        document.getElementById(modalId).style.display = "flex";
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = "none";
    }
</script>

<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background: white;
        padding: 20px;
        border-radius: 5px;
        text-align: center;
        width: 300px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }

    .confirm-btn {
        background-color: red;
        color: white;
        padding: 10px;
        border: none;
        cursor: pointer;
        margin-right: 10px;
    }

    .cancel-btn {
        background-color: gray;
        color: white;
        padding: 10px;
        border: none;
        cursor: pointer;
    }
</style>
