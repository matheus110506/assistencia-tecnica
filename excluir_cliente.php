<?php

include 'conexao.php';

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $sql = "UPDATE CLIENTES SET ativo = 0 WHERE id = :id";
        $stmt = $conn->prepare($sql);

        $stmt->execute([':id' => $id]);
        
        header("Location: clientes.php?sucesso=excluido");
        exit;

    } catch (PDOException $e) {
        header("Location: clientes.php?erro=softdelete");
        exit;
    }
}

// Se não houver ID, apenas volta
header("Location: clientes.php");
exit;

?>