<?php

include 'conexao.php';

$mensagem = "";
$sucesso = false;
$cliente = [];
$equipamento = [];
$tecnico = [];

try {
    $stmt = $conn->query("SELECT DISTINCT id, nome FROM CLIENTES ORDER BY nome");
    $cliente = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensagem = "Erro ao carregar clientes: " . $e->getMessage();
}

try {
    $stmt = $conn->query("SELECT DISTINCT id, modelo, marca, tipo, numero_serie FROM EQUIPAMENTO ORDER BY tipo");
    $equipamento = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensagem = "Erro ao carregar equipamentos: " . $e->getMessage();
}

try {
    $stmt = $conn->query("SELECT DISTINCT id, nome FROM TECNICO ORDER BY nome");
    $tecnico = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensagem = "Erro ao carregar técnicos: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_equipamento = $_POST['id_equipamento'] ?? '';
    $id_cliente = $_POST['id_cliente'] ?? '';
    $id_tecnico = !empty($_POST['id_tecnico']) ? $_POST['id_tecnico'] : null;
    $descricao_problema = $_POST['descricao_problema'] ?? '';
    $status_chamado = $_POST['status_chamado'] ?? 'Pendente';
    $data_chamado = date('Y-m-d');

    if(!empty($id_equipamento) && !empty($id_cliente) && !empty($descricao_problema)) {
        try {
            $sql = "INSERT INTO CHAMADO (id_equipamento, id_cliente, id_tecnico, descricao_problema, status_chamado, data_chamado)
            VALUES (:id_equipamento, :id_cliente, :id_tecnico, :descricao_problema, :status_chamado, :data_chamado)";
            $stmt = $conn->prepare($sql);

            $stmt->execute([
                ':id_equipamento' => $id_equipamento,
                ':id_cliente' => $id_cliente,
                ':id_tecnico' => $id_tecnico,
                ':descricao_problema' => $descricao_problema,
                ':status_chamado' => $status_chamado,
                ':data_chamado' => $data_chamado
            ]);

            $mensagem = "Chamado cadastrado com sucesso!";
            $sucesso = true;

        } catch (PDOException $e){
            $mensagem = "Erro ao cadastrar chamado: " . $e->getMessage();
        }
    } else {
        $mensagem = "Por favor, preencha os campos obrigatórios.";
    }
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Chamado - Assistência Técnica</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Abrir Novo Chamado</h1>
            <div class="space-x-2">
            <a href="index.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-200">
                Voltar ao Início
            </a>
            <a href="chamados.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-200">
                Ver Lista
            </a>
            </div>
        </div>

        <?php if (!empty($mensagem)): ?>
            <div class="mb-4 p-4 rounded bg-blue-100 text-blue-700 border border-blue-300">
                <?= htmlspecialchars($mensagem); ?>
            </div>
        <?php endif; ?>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="cadastrar_chamado.php" method="POST">
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="id_cliente">Cliente:</label>
                    <select name="id_cliente" id="id_cliente" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Selecione um cliente...</option>
                        <?php foreach ($cliente as $cli): ?>
                            <option value="<?= $cli['id']; ?>"><?= htmlspecialchars($cli['nome']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="id_equipamento">Equipamento:</label>
                    <select name="id_equipamento" id="id_equipamento" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Selecione um equipamento...</option>
                        <?php foreach ($equipamento as $eq): ?>
                            <option value="<?= $eq['id']; ?>">
                                <?= htmlspecialchars($eq['tipo'] . ' - ' . $eq['marca'] . ' ' . $eq['modelo']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="id_tecnico">Técnico Responsável (Opcional):</label>
                    <select name="id_tecnico" id="id_tecnico" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Nenhum atribuído por enquanto</option>
                        <?php foreach ($tecnico as $te): ?>
                            <option value="<?= $te['id']; ?>"><?= htmlspecialchars($te['nome']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="descricao_problema">Descrição do Problema:</label>
                    <textarea name="descricao_problema" id="descricao_problema" rows="4" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Descreva o defeito relatado pelo cliente..."></textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="status_chamado">Status Inicial:</label>
                    <select name="status_chamado" id="status_chamado" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="Pendente">Pendente</option>
                        <option value="Em Andamento">Em Andamento</option>
                        <option value="Concluído">Concluído</option>
                    </select>
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow transition duration-200">
                        Registar Chamado
                    </button>
                </div>

            </form>
        </div>
    </div>
<?php if ($sucesso): ?>
        <script>
            setTimeout(function() {
                window.location.href = "chamados.php";
            }, 2000);
        </script>
<?php endif; ?>
</body>
</html>