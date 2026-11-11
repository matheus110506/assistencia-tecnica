<?php

$host = "localhost";
$dbname = "DB_Empresa2";

try {
    $conn = new PDO("sqlsrv:Server=$host;Database=$dbname;TrustServerCertificate=1");

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro na conexão com o banco de dados: " . $e->getMessage();
    exit();
}

?>