<?php
session_start();
include '../../../database/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['nivell'] !== 'administrador') {
    header("Location: ../../index.php");
    exit();
}

$id_admin = $_SESSION['user_id'];

$query = "SELECT id_usuario, usu_nom, usu_email, usu_nivell FROM usuarios";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            text-align: center;
            padding: 20px;
        }

        h2 {
            color: #008000;
        }

        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0px 4px 10px rgba(0, 128, 0, 0.2);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #008000;
            color: white;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        .icon {
            font-size: 20px;
            cursor: pointer;
            margin: 5px;
            text-decoration: none;
        }

        .edit {
            color: #f0ad4e;
        }

        .edit:hover {
            color: #ec971f;
        }

        .delete {
            color: #d9534f;
        }

        .delete:hover {
            color: #c9302c;
        }

        /* MODAL */
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
            padding: 25px;
            border-radius: 12px;
            text-align: left;
            width: 350px;
            box-shadow: 0px 4px 10px rgba(0, 128, 0, 0.3);
            position: relative;
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 24px;
            cursor: pointer;
            color: #555;
        }

        .close:hover {
            color: red;
        }

        .modal h3 {
            margin-top: 0;
            color: #008000;
            font-size: 22px;
            text-align: center;
        }

        .modal label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
            font-size: 14px;
        }

        .modal input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .modal button {
            width: 100%;
            background: #008000;
            color: white;
            border: none;
            padding: 10px;
            margin-top: 15px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .modal button:hover {
            background: #004d00;
        }
    </style>
</head>
<body>

    <h2>Gestión de Usuarios</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['id_usuario']; ?></td>
                <td><?php echo $row['usu_nom']; ?></td>
                <td><?php echo $row['usu_email']; ?></td>
                <td><?php echo ucfirst($row['usu_nivell']); ?></td>
                <td>
                    <a href="#" class="icon edit" onclick="openModal(<?php echo $row['id_usuario']; ?>, '<?php echo $row['usu_nom']; ?>', '<?php echo $row['usu_nivell']; ?>')" title="Editar Usuario">✏️</a>
                    <?php if ($row['usu_nivell'] !== 'administrador'): ?>
                        <a href="<?php echo BASE_URL; ?>/backend/views/admin/adminUsers.php?delete=<?php echo $row['id_usuario']; ?>" class="icon delete" onclick="return confirm('¿Seguro que quieres eliminar este usuario?')">🗑️</a>
                    <?php else: ?>
                        🔒
                    <?php endif; ?>
                </td>
            </tr>
        <?php } ?>

    </table>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Editar Usuario</h3>
            <form method="POST">
                <input type="hidden" id="edit_id" name="id_usuario">

                <label>Nombre:</label>
                <input type="text" id="edit_nombre" name="usu_nom"><br>

                <label>Nueva Contraseña:</label>
                <input type="password" name="nueva_password" placeholder="Introduce nueva contraseña"><br>

                <label>Confirmar Contraseña:</label>
                <input type="password" name="confirmar_password" placeholder="Confirma la contraseña"><br>

                <button type="submit" name="update">Guardar Cambios</button>
            </form>
        </div>
    </div>

    <script>
        function openModal(id, nombre, nivel) {
            const adminId = <?php echo $id_admin; ?>;

            document.getElementById('edit_id').value = id;
            const nombreInput = document.getElementById('edit_nombre');
            nombreInput.value = nombre;

            if (nivel === 'administrador' || id === adminId) {
                nombreInput.setAttribute('readonly', true);
            } else {
                nombreInput.removeAttribute('readonly');
            }

            document.getElementById('editModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target === modal) {
                modal.style.display = "none";
            }
        };
    </script>

</body>
</html>
