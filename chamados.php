<?php

include 'conexao.php';

$chamado = [];
$mensagem = "";

try {
    $sql = "SELECT ch.id, ch.descricao_problema, se.descricao AS servico_efetuado, ch.status_chamado, ch.data_chamado, cl.nome AS nome_cliente, eq.tipo AS tipo_equipamento, te.nome AS nome_tecnico
    FROM CHAMADO ch
    JOIN CLIENTES cl on ch.id_cliente = cl.id
    JOIN EQUIPAMENTO eq on ch.id_equipamento = eq.id
    LEFT JOIN CHAMADO_SERVICO cs on cs.id_chamado = ch.id
    LEFT JOIN SERVICO se on cs.id_servico = se.id
    LEFT JOIN TECNICO te on ch.id_tecnico = te.id
    ORDER BY ch.id ASC";

    $stmt = $conn->query($sql);
    $chamado = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $mensagem = "Erro ao carregar os chamados: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chamados - Assistência Técnica</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <div class="container mx-auto px-4 py-8">
        <!-- Cabeçalho -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Gestão de Chamados</h1>
                <p class="text-gray-600">Acompanhamento dos atendimentos e reparações</p>
            </div>
            <div class="space-x-2">
                <a href="index.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-200">
                    Voltar ao início
                </a>
                <a href="clientes.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-200">
                    Clientes
                </a>
                <a href="equipamentos.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-200">
                    Equipamentos
                </a>
                <a href="cadastrar_chamado.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-200">
                    + Novo Chamado
                </a>
            </div>
        </div>

        <!-- Mensagem de Erro, se houver -->
        <?php if (!empty($mensagem)): ?>
            <div class="mb-4 p-4 rounded bg-red-100 text-red-700 border border-red-300">
                <?= htmlspecialchars($mensagem); ?>
            </div>
        <?php endif; ?>

        <!-- Tabela de Chamados -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-gray-200 text-gray-700 uppercase text-xs font-semibold tracking-wider">
                        <th class="px-5 py-3 text-left">ID</th>
                        <th class="px-5 py-3 text-left">Cliente</th>
                        <th class="px-5 py-3 text-left">Equipamento</th>
                        <th class="px-5 py-3 text-left">Técnico</th>
                        <th class="px-5 py-3 text-left">Problema Relatado</th>
                        <th class="px-5 py-3 text-left">Serviço Efetuado</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-left">Data</th>
                        <th class="px-5 py-3 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm">
                    <?php if (!empty($chamado)): ?>
                        <?php foreach ($chamado as $ch): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-5 py-4 whitespace-nowrap"><?= htmlspecialchars($ch['id']); ?></td>
                                <td class="px-5 py-4 whitespace-nowrap"><?= htmlspecialchars($ch['nome_cliente']); ?></td>
                                <td class="px-5 py-4 whitespace-nowrap"><?= htmlspecialchars($ch['tipo_equipamento']); ?></td>
                                <td class="px-5 py-4 whitespace-nowrap"><?= htmlspecialchars($ch['nome_tecnico']); ?></td>
                                <td class="px-5 py-4"><?= htmlspecialchars($ch['descricao_problema']); ?></td>
                                <td class="px-5 py-4"><?= htmlspecialchars($ch['servico_efetuado'] ?? 'Nenhum serviço efetuado'); ?></td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <?php 
                                        $statusClass = "bg-gray-200 text-gray-800";
                                        $status = $ch['status_chamado'] ?? 'Pendente';
                                        if ($status == 'Pendente') $statusClass = "bg-yellow-100 text-yellow-800";
                                        elseif ($status == 'Em Andamento') $statusClass = "bg-blue-100 text-blue-800";
                                        elseif ($status == 'Concluído') $statusClass = "bg-green-100 text-green-800";
                                    ?>
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full <?= $statusClass; ?>">
                                        <?= htmlspecialchars($status); ?>
                                    </span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap"><?= htmlspecialchars($ch['data_chamado'] ?? 'N/D'); ?></td>
                                <td class="px-5 py-4 whitespace-nowrap text-center space-x-2">
                                    <a href="editar_chamado.php?id=<?= $ch['id']; ?>" class="text-yellow-600 hover:text-yellow-900 font-semibold">Editar</a>
                                    <a href="excluir_chamado.php?id=<?= $ch['id']; ?>" class="text-red-600 hover:text-red-900 font-semibold" onclick="return confirm('Tem certeza que deseja excluir este chamado?');">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="px-5 py-5 text-center text-gray-500">Nenhum chamado registado no momento.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>