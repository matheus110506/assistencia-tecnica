<?php

include 'conexao.php';

try{
    $stmt = $conn->query("SELECT id, nome, email, telefone, cpf, estado FROM CLIENTES WHERE ativo = 1");
    $cliente = $stmt->fetchall(PDO::FETCH_ASSOC); }
    catch (PDOException $e){
        echo 'Erro ao carregar os clientes: ' . $e->getMessage();
        $cliente = [];
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Clientes - Assistência Técnica</title>
    <!-- Tailwind CSS CDN para prototipagem rápida e visual limpo -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <div class="container mx-auto px-4 py-8">
        <!-- Cabeçalho da Página -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Lista de Clientes</h1>
            <div class="flex items-center gap-3">
            <a href="index.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-200">
                    Voltar ao início
            </a>
            <a href="equipamentos.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-200">
                Equipamentos
            </a>
            <a href="chamados.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-200">
                Chamados
            </a>
            <a href="cadastrar_cliente.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition duration-200">
                + Novo Cliente
            </a>
        </div>
    </div>

        <!-- Tabela de Listagem -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-xs font-semibold tracking-wider">
                        <th class="py-3 px-6 text-left">ID</th>
                        <th class="py-3 px-6 text-left">Nome</th>
                        <th class="py-3 px-6 text-left">E-mail</th>
                        <th class="py-3 px-6 text-left">Telefone</th>
                        <th class="py-3 px-6 text-left">CPF</th>
                        <th class="py-3 px-6 text-center">Estado</th>
                        <th class="py-3 px-6 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm">
                    <?php if (count($cliente) > 0): ?>
                        <?php foreach ($cliente as $cliente): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-6 text-left whitespace-nowrap"><?= $cliente['id']; ?></td>
                                <td class="py-3 px-6 text-left font-medium text-gray-800"><?= htmlspecialchars($cliente['nome']); ?></td>
                                <td class="py-3 px-6 text-left"><?= htmlspecialchars($cliente['email']); ?></td>
                                <td class="py-3 px-6 text-left"><?= htmlspecialchars($cliente['telefone']); ?></td>
                                <td class="py-3 px-6 text-left"><?= htmlspecialchars($cliente['cpf']); ?></td>
                                <td class="py-3 px-6 text-center"><?= htmlspecialchars($cliente['estado']); ?></td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-center space-x-3">
                                        <!-- Botão de Editar -->
                                        <a href="editar_cliente.php?id=<?= $cliente['id']; ?>" class="text-yellow-600 hover:text-yellow-900 font-semibold">Editar</a>
                                        <!-- Botão de Excluir com confirmação básica em JavaScript -->
                                        <a href="excluir_cliente.php?id=<?= $cliente['id']; ?>" onclick="return confirm('Tem a certeza de que deseja excluir este cliente?');" class="text-red-600 hover:text-red-900 font-semibold">Excluir</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="py-4 px-6 text-center text-gray-500">Nenhum cliente registado no momento.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>