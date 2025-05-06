<?php
    // Indicates the name of the server where the database is located (usually "localhost" if it's on the same computer)
    $servername = "localhost";

    // Indicates the name of the database you want to access
    $database = "anunciat";

    // Username to access the database (usually "root" by default)
    $username = "root";

    // Password to access the database (here it's empty)
    $password = "";

    // Tries to connect to the database using the data above
    $conn = mysqli_connect($servername, $username, $password, $database);

    // If the connection fails, shows an error message and stops the program
    if (!$conn)
    {
        die("Connection failed: " . mysqli_connect_error());
    }
?>


<?php
    // Indica el nom del servidor on es troba la base de dades (normalment "localhost" si està al mateix ordinador)
    $servername = "localhost";

    // Indica el nom de la base de dades a la qual es vol accedir
    $database = "anunciat";

    // Nom d'usuari per accedir a la base de dades (per defecte sol ser "root")
    $username = "root";

    // Contrasenya per accedir a la base de dades (aquí està buida)
    $password = "";

    // Intenta connectar-se a la base de dades amb les dades anteriors
    $conn = mysqli_connect($servername, $username, $password, $database);

    // Si la connexió falla, mostra un missatge d'error i atura el programa
    if (!$conn)
    {
        die("Connexió fallida: " . mysqli_connect_error());
    }
?>
