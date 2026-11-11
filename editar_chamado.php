<?php

include 'conexao.php';

$mensagem = "";
$sucesso = false;
$chamado = null;
$tecnico = [];

$id = $_GET['id'] ?? null;

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: chamados.php");
    exit;
}

try {
    $stmt = $conn->query("SELECT id, nome FROM TECNICO ORDER BY nome");
    $tecnico = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensagem = "Erro ao carregar técnicos: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_tecnico = !empty($_POST['id_tecnico']) ? $_POST['id_tecnico'] : null;
    $status_chamado = $_POST['status_chamado'] ?? '';
    $servico_efetuado = $_POST['servico_efetuado'] ?? '';

    if (!empty($status_chamado)) {
        try {
            $sql = "UPDATE CHAMADO
            SET id_tecnico = :id_tecnico,
                status_chamado = :status_chamado
            WHERE id = :id";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':id_tecnico' => $id_tecnico,
                ':status_chamado' => $status_chamado,
                ':id' => $id  
            ]);

            $stmtCheck = $conn->prepare("SELECT id_servico FROM CHAMADO_SERVICO WHERE id_chamado = :id_chamado");
            $stmtCheck->execute([':id_chamado' => $id]);
            $associacao = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($associacao) {
                $stmtUpServ = $conn->prepare("UPDATE SERVICO SET descricao = :descricao WHERE id = :id_servico");
                $stmtUpServ->execute([
                    ':descricao' => $servico_efetuado,
                    ':id_servico' => $associacao['id_servico']
                ]);
            } else if (!empty($servico_efetuado)) {
                $stmtInsServ = $conn->prepare("INSERT INTO SERVICO (descricao, status_servico) VALUES (:descricao, 'Em Andamento')");
                $stmtInsServ->execute([':descricao' => $servico_efetuado]);
                $novo_id_servico = $conn->lastInsertId();

                $stmtInsVinculo = $conn->prepare("INSERT INTO CHAMADO_SERVICO (id_chamado, id_servico) VALUES (:id_chamado, :id_servico)");
                $stmtInsVinculo->execute([
                    ':id_chamado' => $id,
                    ':id_servico' => $novo_id_servico
                ]);
            }

            header("Location: editar_chamado.php?id=" . $id . "&sucesso=1");

        } catch (PDOException $e) {
            $mensagem = "Erro ao atualizar chamado: " . $e->getMessage();
        }
    } else {
        $mensagem = "O campo Status é obrigatório.";
    }
}

if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1) {
    $mensagem = "Chamado atualizado com sucesso!";
    $sucesso = true;
}

try {
    $sql = "SELECT ch.id, ch.descricao_problema, ch.data_chamado, se.descricao AS servico_efetuado, ch.status_chamado, ch.id_tecnico, cl.nome AS nome_cliente, cl.telefone, eq.tipo AS tipo_equipamento, eq.marca, eq.modelo
    FROM CHAMADO ch
    INNER JOIN CLIENTES cl on ch.id_cliente = cl.id
    INNER JOIN EQUIPAMENTO eq on ch.id_equipamento = eq.id
    LEFT JOIN CHAMADO_SERVICO cs on cs.id_chamado = ch.id
    LEFT JOIN SERVICO se on cs.id_servico = se.id
    WHERE ch.id = :id";

    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $id]);
    $chamado = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$chamado) {
        header("Location: chamados.php");
        exit;
    }
} catch (PDOException $e) {
    $mensagem = "Erro ao carregar os dados do chamado: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Chamado #<?= htmlspecialchars($chamado['id']); ?> - Assistência Técnica</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <!-- Cabeçalho -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Atendimento / Chamado #<?= htmlspecialchars($chamado['id']); ?></h1>
            <a href="chamados.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-200">
                Voltar
            </a>
        </div>

        <!-- Mensagens de Alerta -->
        <?php if (!empty($mensagem) && !$sucesso): ?>
            <div class="mb-4 p-4 rounded bg-red-100 text-red-700 border border-red-300">
                <?= htmlspecialchars($mensagem); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($sucesso)): ?>
            <div class="mb-4 p-4 rounded bg-green-100 text-green-700 border border-green-300">
                <?= htmlspecialchars($mensagem); ?>
            </div>
        <?php endif; ?>

        <!-- Cartão de Informações Gerais (Apenas Leitura) -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6 grid grid-cols-1 md:grid-cols-2 gap-4 border-l-4 border-blue-500">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase">Cliente</p>
                <p class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($chamado['nome_cliente']); ?></p>
                <p class="text-sm text-gray-600">Tel: <?= htmlspecialchars($chamado['telefone'] ?? 'Não informado'); ?></p>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase">Equipamento</p>
                <p class="text-lg font-semibold text-gray-800">
                    <?= htmlspecialchars($chamado['tipo_equipamento'] . ' - ' . $chamado['marca'] . ' ' . $chamado['modelo']); ?>
                </p>
                <p class="text-sm text-gray-600">Aberto em: <?= !empty($chamado['data_chamado']) ? htmlspecialchars(date('d/m/Y', strtotime($chamado['data_chamado']))) : 'Não Informada'; ?></p>
            </div>
            <div class="md:col-span-2 mt-2">
                <p class="text-xs font-bold text-gray-500 uppercase">Defeito Relatado pelo Cliente</p>
                <p class="text-gray-700 bg-gray-50 p-3 rounded border mt-1">
                    <?= htmlspecialchars($chamado['descricao_problema']); ?>
                </p>
            </div>
        </div>

        <!-- Formulário de Atualização -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Atualizar Status e Serviço</h2>
            
            <form action="editar_chamado.php?id=<?= $id; ?>" method="POST">
                
                <!-- Status do Chamado -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="status_chamado">Status do Chamado:</label>
                    <select name="status_chamado" id="status_chamado" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="Pendente" <?= $chamado['status_chamado'] == 'Pendente' ? 'selected' : ''; ?>>Pendente</option>
                        <option value="Em Andamento" <?= $chamado['status_chamado'] == 'Em Andamento' ? 'selected' : ''; ?>>Em Andamento</option>
                        <option value="Concluído" <?= $chamado['status_chamado'] == 'Concluído' ? 'selected' : ''; ?>>Concluído</option>
                    </select>
                </div>

                <!-- Atribuir / Mudar Técnico -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="id_tecnico">Técnico Responsável:</label>
                    <select name="id_tecnico" id="id_tecnico" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Nenhum técnico atribuído</option>
                        <?php foreach ($tecnico as $te): ?>
                            <option value="<?= $te['id']; ?>" <?= $chamado['id_tecnico'] == $te['id'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($te['nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Serviço / Parecer Técnico -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="servico_efetuado">Serviço Efetuado / Parecer Técnico:</label>
                    <textarea name="servico_efetuado" id="servico_efetuado" rows="4" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Descreva os testes realizados, peças trocadas ou solução aplicada..."><?= htmlspecialchars($chamado['servico_efetuado'] ?? ''); ?></textarea>
                </div>

                <!-- Botão de Envio -->
                <div class="flex items-center justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow transition duration-200">
                        Salvar Alterações
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