<?php
        $db = "public_establishment_management_db";
        $host = "localhost";
        $user = "root";
        $pass = "";
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user,$pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
?>