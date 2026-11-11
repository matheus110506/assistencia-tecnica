<?php

include 'conexao.php';

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $stmtPeca = $conn->prepare("DELETE FROM CHAMADO_PECA WHERE id_chamado = :id");
        $stmtPeca->execute([':id' => $id]);

        $stmtVinculo = $conn->prepare("DELETE FROM CHAMADO_SERVICO WHERE id_chamado = :id");
        $stmtVinculo->execute([':id' => $id]);

        $sql = "DELETE FROM CHAMADO WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        
    } catch (PDOException $e) {
        die("Erro ao excluir chamado: " . $e->getMessage());
    }
}

header("Location: chamados.php");
exit;

?>