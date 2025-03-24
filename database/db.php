<?php
    $servername = "localhost";
    $database = "anunciat";
    $username = "root";
    $password = "";


    $conn = mysqli_connect($servername, $username, $password, $database);

    if (!$conn)
    {
        die("Conexión fallida: " . mysqli_connect_error());
    }
    
?>