<?php

include 'conexao.php';

$equipamento = [];
$erro = "";

try {
    $sql = "SELECT id, modelo, marca, tipo, numero_serie FROM EQUIPAMENTO WHERE ativo = 1";
    $stmt = $conn->query($sql);
    $equipamento = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $erro = "Erro ao carregar os equipamentos: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Equipamentos - Assistência Técnica</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <div class="container mx-auto px-4 py-8">
        <!-- Cabeçalho -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Gestão de Equipamentos</h1>
                <p class="text-gray-600">Lista de aparelhos registados no sistema</p>
            </div>
            <div class="space-x-2">
                <a href="index.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-200">
                    Voltar ao início
                </a>
                <a href="clientes.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-200">
                    Clientes
                </a>
                <a href="chamados.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-200">
                    Chamados
                </a>
                <a href="cadastrar_equipamento.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-200">
                    + Novo Equipamento
                </a>
            </div>
        </div>

        <!-- Mensagem de Erro, se houver -->
        <?php if (!empty($erro)): ?>
            <div class="mb-4 p-4 rounded bg-red-100 text-red-700 border border-red-300">
                <?= htmlspecialchars($erro); ?>
            </div>
        <?php endif; ?>

        <!-- Tabela de Equipamentos -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-gray-200 text-gray-700 uppercase text-xs font-semibold tracking-wider">
                        <th class="px-5 py-3 text-left">ID</th>
                        <th class="px-5 py-3 text-left">Tipo</th>
                        <th class="px-5 py-3 text-left">Marca</th>
                        <th class="px-5 py-3 text-left">Modelo</th>
                        <th class="px-5 py-3 text-left">Nº de Série</th>
                        <th class="px-5 py-3 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm">
                    <?php if (!empty($equipamento)): ?>
                        <?php foreach ($equipamento as $eq): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-5 py-4 whitespace-nowrap"><?= htmlspecialchars($eq['id']); ?></td>
                                <td class="px-5 py-4 whitespace-nowrap"><?= htmlspecialchars($eq['tipo']); ?></td>
                                <td class="px-5 py-4 whitespace-nowrap"><?= htmlspecialchars($eq['marca']); ?></td>
                                <td class="px-5 py-4 whitespace-nowrap"><?= htmlspecialchars($eq['modelo']); ?></td>
                                <td class="px-5 py-4 whitespace-nowrap"><?= htmlspecialchars($eq['numero_serie'] ?? 'N/D'); ?></td>
                                <td class="px-5 py-4 whitespace-nowrap text-center space-x-2">
                                    <a href="editar_equipamento.php?id=<?= $eq['id']; ?>" class="text-yellow-600 hover:text-yellow-900 font-semibold">Editar</a>
                                    <a href="excluir_equipamento.php?id=<?= $eq['id']; ?>" class="text-red-600 hover:text-red-900 font-semibold" onclick="return confirm('Tem certeza que deseja excluir este equipamento?');">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-5 py-5 text-center text-gray-500">Nenhum equipamento registado no momento.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>