<?php

include 'conexao.php';

$id =$_GET['id'] ?? null;

if ($id) {
    try {
        $sql = "UPDATE EQUIPAMENTO SET ativo = 0 WHERE id = :id";
        $stmt = $conn->prepare($sql);

        $stmt->execute([':id' =>$id]);
        
        header("Location: equipamentos.php?sucesso=excluido");
        exit;

    } catch (PDOException $e) {
        header("Location: equipamentos.php?erro=softdelete");
        exit;
    }
}

// Se não houver ID, apenas volta
header("Location: equipamentos.php");
exit;

?>