<?php

require_once 'conexao.php';

try {
    $totalClientes = $conn->query("SELECT COUNT(*) FROM CLIENTES")->fetchColumn();
    $totalEquipamentos = $conn->query("SELECT COUNT(*) FROM EQUIPAMENTO")->fetchColumn();
    // Ajustado para contar tudo o que não estiver concluído ou usar status genéricos
    $totalChamadosPendentes = $conn->query("SELECT COUNT(*) FROM CHAMADO WHERE status_chamado IN ('Pendente', 'Em Andamento')")->fetchColumn();
    $totalChamadosConcluidos = $conn->query("SELECT COUNT(*) FROM CHAMADO WHERE status_chamado = 'Concluído'")->fetchColumn();

    $sqlRecentes = "SELECT ch.id, cl.nome AS nome_cliente, eq.tipo, ch.descricao_problema, ch.status_chamado, ch.data_chamado
    FROM CHAMADO ch
    INNER JOIN CLIENTES cl ON ch.id_cliente = cl.id
    INNER JOIN EQUIPAMENTO eq ON ch.id_equipamento = eq.id
    ORDER BY ch.id DESC
    OFFSET 0 ROWS FETCH NEXT 5 ROWS ONLY";

    $stmtRecentes = $conn->query($sqlRecentes);
    $ultimosChamados = $stmtRecentes->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $erroConsulta = "Erro ao carregar dados do dashboard: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Inicial - Assistência Técnica</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <!-- Container Principal -->
    <div class="min-h-screen flex flex-col">

        <!-- Barra de Navegação Superior -->
        <header class="bg-blue-600 text-white shadow-md">
            <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                <h1 class="text-xl font-bold">🔧 Sistema de Assistência Técnica</h1>
                <nav class="space-x-4">
                    <a href="index.php" class="bg-blue-700 px-3 py-2 rounded font-medium transition">Início</a>
                    <a href="clientes.php" class="hover:bg-blue-700 px-3 py-2 rounded transition">Clientes</a>
                    <a href="equipamentos.php" class="hover:bg-blue-700 px-3 py-2 rounded transition">Equipamentos</a>
                    <a href="chamados.php" class="hover:bg-blue-700 px-3 py-2 rounded transition">Chamados</a>
                </nav>
            </div>
        </header>

        <!-- Conteúdo do Dashboard -->
        <main class="max-w-7xl mx-auto px-4 py-8 flex-grow w-full">
            
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800">Painel de Controlo</h2>
                <p class="text-gray-600">Visão geral do sistema e indicadores operacionais.</p>
            </div>

            <?php if (isset($erroConsulta)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline"><?php echo $erroConsulta; ?></span>
                </div>
            <?php endif; ?>

            <!-- Cartões de Indicadores (KPIs) -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Clientes -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total de Clientes</p>
                        <p class="text-3xl font-bold text-gray-800"><?php echo $totalClientes ?? 0; ?></p>
                    </div>
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-full text-xl">👥</div>
                </div>

                <!-- Equipamentos -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Equipamentos Registados</p>
                        <p class="text-3xl font-bold text-gray-800"><?php echo $totalEquipamentos ?? 0; ?></p>
                    </div>
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-full text-xl">💻</div>
                </div>

                <!-- Chamados Abertos -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Chamados Abertos</p>
                        <p class="text-3xl font-bold text-amber-600"><?php echo $totalChamadosPendentes ?? 0; ?></p>
                    </div>
                    <div class="p-3 bg-amber-50 text-amber-600 rounded-full text-xl">⚠️</div>
                </div>

                <!-- Chamados Concluídos -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Chamados Concluídos</p>
                        <p class="text-3xl font-bold text-green-600"><?php echo $totalChamadosConcluidos ?? 0; ?></p>
                    </div>
                    <div class="p-3 bg-green-50 text-green-600 rounded-full text-xl">✅</div>
                </div>
            </div>

            <!-- Atalhos Rápidos -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Ações Rápidas</h3>
                <div class="flex flex-wrap gap-4">
                    <a href="cadastrar_cliente.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition shadow-sm">
                        + Novo Cliente
                    </a>
                    <a href="cadastrar_equipamento.php" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md font-medium transition shadow-sm">
                        + Novo Equipamento
                    </a>
                    <a href="cadastrar_chamado.php" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-md font-medium transition shadow-sm">
                        + Novo Chamado
                    </a>
                </div>
            </div>

            <!-- Tabela de Chamados Recentes -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Últimos 5 Chamados Registados</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-sm uppercase tracking-wider">
                                <th class="py-3 px-6 border-b">ID</th>
                                <th class="py-3 px-6 border-b">Cliente</th>
                                <th class="py-3 px-6 border-b">Equipamento</th>
                                <th class="py-3 px-6 border-b">Problema Relatado</th>
                                <th class="py-3 px-6 border-b">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 divide-y divide-gray-200">
                            <?php if (!empty($ultimosChamados)): ?>
                                <?php foreach ($ultimosChamados as $chamado): ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="py-4 px-6 font-medium">#<?php echo $chamado['id']; ?></td>
                                        <td class="py-4 px-6"><?php echo htmlspecialchars($chamado['nome_cliente']); ?></td>
                                        <td class="py-4 px-6"><?php echo htmlspecialchars($chamado['tipo']); ?></td>
                                        <td class="py-4 px-6 truncate max-w-xs"><?php echo htmlspecialchars($chamado['descricao_problema']); ?></td>
                                        <td class="py-4 px-6">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                <?php echo ($chamado['status_chamado'] == 'Concluído') ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800'; ?>">
                                                <?php echo htmlspecialchars($chamado['status_chamado']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="py-4 px-6 text-center text-gray-500">Nenhum chamado registado até o momento.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>

        <!-- Rodapé -->
        <footer class="bg-white border-t border-gray-200 py-4 mt-8">
            <div class="max-w-7xl mx-auto px-4 text-center text-sm text-gray-500">
                Sistema de Assistência Técnica &copy; 2026 - Projeto Infinity SA
            </div>
        </footer>

    </div>

</body>
</html>