<?php

include 'conexao.php';

$mensagem = "";
$sucesso = false;
$cliente = null;

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: clientes.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $cpf = $_POST['cpf'] ?? '';
    $estado = $_POST['estado'] ?? '';

    if (!empty($nome) && !empty($email) && !empty($cpf)) {
        try {
            $sql = "UPDATE CLIENTES SET nome = :nome, email = :email, telefone = :telefone, cpf = :cpf, estado = :estado WHERE id = :id";
            $stmt = $conn->prepare($sql);

            $stmt->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':telefone' => $telefone,
                ':cpf' => $cpf,
                ':estado' => $estado,
                ':id' => $id  
            ]);

            $mensagem = "Cliente atualizado com sucesso!";
            $sucesso = true;
        } catch (PDOException $e) {
            $mensagem = "Erro ao autenticar cliente: " . $e->getMessage();
        }
    } else {
        $mensagem = "Por favor, preencha os campos obrigatórios.";
    }
}

try {
    $stmt = $conn->prepare("SELECT * FROM CLIENTES WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cliente) {
        header("Location: clientes.php");
        exit;
    }
} catch (PDOException $e) {
    die("Erro ao carregar dados do cliente: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente - Assistência Técnica</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <div class="container mx-auto px-4 py-8 max-w-xl">
        <!-- Cabeçalho -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Editar Cliente</h1>
            <a href="clientes.php" class="text-blue-600 hover:text-blue-800 font-semibold">&larr; Voltar à Lista</a>
        </div>

        <!-- Mensagem de Feedback -->
        <?php if (!empty($mensagem)): ?>
            <div class="mb-4 p-4 rounded bg-blue-100 text-blue-700 border border-blue-300">
                <?= htmlspecialchars($mensagem); ?>
            </div>
        <?php endif; ?>

        <!-- Formulário de Edição -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="editar_cliente.php?id=<?= $id; ?>" method="POST" class="space-y-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nome Completo:</label>
                    <input type="text" name="nome" value="<?= htmlspecialchars($cliente['nome']); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">E-mail:</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($cliente['email']); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Telefone:</label>
                        <input type="text" name="telefone" value="<?= htmlspecialchars($cliente['telefone'] ?? ''); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">CPF:</label>
                        <input type="text" name="cpf" value="<?= htmlspecialchars($cliente['cpf']); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Estado (UF):</label>
                    <input type="text" name="estado" maxlength="2" value="<?= htmlspecialchars($cliente['estado'] ?? ''); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-200">
                        Atualizar Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php if ($sucesso): ?>
        <script>
            setTimeout(function() {
                window.location.href = "clientes.php";
            }, 2000);
        </script>
<?php endif; ?>
</body>
</html>