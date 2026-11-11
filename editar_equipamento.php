<?php

include 'conexao.php';

$mensagem = "";
$sucesso = false;
$equipamento = null;

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: equipamentos.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $modelo = $_POST['modelo'] ?? '';
    $marca = $_POST['marca'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $numero_serie = $_POST['numero_serie'] ?? '';

    if (!empty($modelo) && !empty($marca) && !empty($tipo)) {
        try {
            $sql = "UPDATE EQUIPAMENTO SET modelo = :modelo, marca = :marca, tipo = :tipo, numero_serie = :numero_serie WHERE id = :id";
            $stmt = $conn->prepare($sql);

            $stmt->execute([
                ':modelo' => $modelo,
                ':marca' => $marca,
                ':tipo' => $tipo,
                ':numero_serie' => $numero_serie,
                ':id' => $id  
            ]);

            $mensagem = "Equipamento atualizado com sucesso!";
            $sucesso = true;
        } catch (PDOException $e) {
            $mensagem = "Erro ao autenticar equipamento: " . $e->getMessage();
        }
    } else {
        $mensagem = "Por favor, preencha os campos obrigatórios.";
    }
}

try {
    $stmt = $conn->prepare("SELECT * FROM EQUIPAMENTO WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $equipamento = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$equipamento) {
        header("Location: equipamentos.php");
        exit;
    }
} catch (PDOException $e) {
    die("Erro ao carregar dados do equipamento: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Equipamento - Assistência Técnica</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <div class="container mx-auto px-4 py-8 max-w-xl">
        <!-- Cabeçalho -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Editar Equipamento</h1>
            <a href="equipamentos.php" class="text-blue-600 hover:text-blue-800 font-semibold">&larr; Voltar à Lista</a>
        </div>

        <!-- Mensagem de Feedback -->
        <?php if (!empty($mensagem)): ?>
            <div class="mb-4 p-4 rounded bg-blue-100 text-blue-700 border border-blue-300">
                <?= htmlspecialchars($mensagem); ?>
            </div>
        <?php endif; ?>

        <!-- Formulário de Edição -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="editar_equipamento.php?id=<?= $id; ?>" method="POST" class="space-y-4">

                <!-- Tipo de Equipamento -->
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tipo de Equipamento:</label>
                    <input type="text" name="tipo" value="<?= htmlspecialchars($equipamento['tipo']); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Marca e Modelo -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Marca:</label>
                        <input type="text" name="marca" value="<?= htmlspecialchars($equipamento['marca']); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Modelo:</label>
                        <input type="text" name="modelo" value="<?= htmlspecialchars($equipamento['modelo']); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Número de Série -->
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Número de Série (Opcional):</label>
                    <input type="text" name="numero_serie" value="<?= htmlspecialchars($equipamento['numero_serie'] ?? ''); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Botão de Atualização -->
                <div class="pt-4">
                    <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-200">
                        Atualizar Equipamento
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php if ($sucesso): ?>
        <script>
            setTimeout(function() {
                window.location.href = "equipamentos.php";
            }, 2000);
        </script>
<?php endif; ?>
</body>
</html>