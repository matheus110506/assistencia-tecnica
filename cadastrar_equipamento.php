<?php

include 'conexao.php';

$mensagem = "";
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $modelo = $_POST['modelo'] ?? '';
    $marca = $_POST['marca'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $numero_serie = $_POST['numero_serie'] ?? '';

    if(!empty($modelo) && !empty($marca) && !empty($tipo)) {
        try {
            $sql = "INSERT INTO EQUIPAMENTO (modelo, marca, tipo, numero_serie)
            VALUES (:modelo, :marca, :tipo, :numero_serie)";
            $stmt = $conn->prepare($sql);

            $stmt->execute([
                ':modelo' => $modelo,
                ':marca' => $marca,
                ':tipo' => $tipo,
                ':numero_serie' => $numero_serie
            ]);

            $mensagem = "Equipamento cadastrado com sucesso!";
            $sucesso = true;
        } catch (PDOException $e){
            $mensagem = "Erro ao cadastrar equipamento: " . $e->getMessage();
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
    <title>Cadastrar Equipamento - Assistência Técnica</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <div class="container mx-auto px-4 py-8 max-w-xl">
        <!-- Cabeçalho -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Novo Equipamento</h1>
            <div class="space-x-2">
            <a href="index.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-200">
                Voltar ao Início
            </a>
            <a href="equipamentos.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-200">
                Ver Lista
            </a>
            </div>
        </div>

        <!-- Mensagem de Feedback -->
        <?php if (!empty($mensagem)): ?>
            <div class="mb-4 p-4 rounded bg-blue-100 text-blue-700 border border-blue-300">
                <?= htmlspecialchars($mensagem); ?>
            </div>
        <?php endif; ?>

        <!-- Formulário de Cadastro -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="cadastrar_equipamento.php" method="POST" class="space-y-4">
                
                <!-- Tipo de Equipamento -->
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tipo de Equipamento:</label>
                    <input type="text" name="tipo" placeholder="Ex: Notebook, Smartphone, Impressora" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Marca e Modelo -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Marca:</label>
                        <input type="text" name="marca" placeholder="Ex: Dell, Samsung" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Modelo:</label>
                        <input type="text" name="modelo" placeholder="Ex: Inspiron 15" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Número de Série (Opcional) -->
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Número de Série (Opcional):</label>
                    <input type="text" name="numero_serie" placeholder="Ex: SN123456789" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Botão de Envio -->
                <div class="pt-4">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-200">
                        Guardar Equipamento
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