<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hobby club";

    // Bağlantıyı oluştur
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Bağlantıyı kontrol et
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>