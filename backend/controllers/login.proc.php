<?php
    // Inicia la sessió per poder guardar informació de l'usuari mentre navega
    session_start();

    // Connecta amb la base de dades
    require_once '../../database/db.php';

    // Guarda el que ha escrit l'usuari als camps "nom" i "clau", traient espais al principi i al final
    $nombre = trim($_POST['nombre']);
    $clave = trim($_POST['clave']);

    // Si els dos camps no estan buits, continua
    if (!empty($nombre) && !empty($clave)) {

        // Encripta la contrasenya per protegir-la
        $hashedPassword = md5($clave);

        // Prepara una consulta per comprovar si és un administrador amb aquest correu i contrasenya
        $stmt = $conn->prepare("SELECT id_admin AS id, nombre, foto_perfil FROM administradores WHERE email = ? AND password = ?");
        $stmt->bind_param("ss", $nombre, $hashedPassword); // Posa els valors dins la consulta
        $stmt->execute(); // Executa la consulta
        $result = $stmt->get_result(); // Guarda el resultat

        // Si s'ha trobat un administrador amb aquestes dades
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc(); // Guarda les dades de l'usuari
            $_SESSION['user_id'] = $user['id']; // Guarda l’ID a la sessió
            $_SESSION['nombre'] = $user['nombre']; // Guarda el nom
            $_SESSION['es_admin'] = true; // Marca que és un administrador
        } else {
            // Si no és admin, busca a la taula d’estudiants
            $stmt = $conn->prepare("SELECT id_estudiante AS id, nombre, foto_perfil FROM estudiantes WHERE email = ? AND password = ?");
            $stmt->bind_param("ss", $nombre, $hashedPassword);
            $stmt->execute();
            $result = $stmt->get_result();

            // Si s'ha trobat un estudiant amb aquestes dades
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nombre'] = $user['nombre'];
                $_SESSION['es_admin'] = false; // Marca que no és administrador
            }
        }

        // Si s’ha iniciat sessió correctament
        if (isset($_SESSION['user_id'])) {

            // Si l’usuari té una foto de perfil, la guarda
            if (!empty($user['foto_perfil'])) {
                $_SESSION['fotoPerfil'] = $user['foto_perfil'];
            } else {
                // Si no té foto, posa una imatge per defecte segons si és admin o estudiant
                $_SESSION['fotoPerfil'] = $_SESSION['es_admin']
                    ? "../../assets/imgs/dummie/administrador.png"
                    : "../../assets/imgs/dummie/estudiante.png";
            }

            // Si és admin, el redirigeix al panell d’administració
            if ($_SESSION['es_admin']) {
                header("Location: ../views/admin/dashboard.php");
            } else {
                // Si és estudiant, el porta a la pàgina principal
                header("Location: ../../index.php");
            }
            exit(); // Finalitza l'script
        } else {
            // Si no s’ha trobat cap usuari, envia a una pàgina d’error
            header("Location: ../views/error.php?error=Correu electrònic o contrasenya incorrectes.");
        }

        // Tanca la consulta
        $stmt->close();
    } else {
        // Si algun camp està buit, mostra un missatge
        echo "Si us plau, completa tots els camps.";
    }

    // Tanca la connexió amb la base de dades
    $conn->close();
?>
