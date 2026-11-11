<?php

include 'conexao.php';

$mensagem = "";
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $cpf = $_POST['cpf'] ?? '';
    $estado = $_POST['estado'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if(!empty($nome) && !empty($email) && !empty($cpf)) {
        try {
            $sql = "INSERT INTO CLIENTES (nome, email, telefone, cpf, estado, senha)
            VALUES (:nome, :email, :telefone, :cpf, :estado, :senha)";
            $stmt = $conn->prepare($sql);

            $stmt->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':telefone' => $telefone,
                ':cpf' => $cpf,
                ':estado' => $estado,
                ':senha' => $senha
            ]);

            $mensagem = "Cliente cadastrado com sucesso!";
            $sucesso = true;
        } catch (PDOException $e){
            $mensagem = "Erro ao cadastrar cliente: " . $e->getMessage();
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
    <title>Cadastrar Cliente - Assistência Técnica</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <div class="container mx-auto px-4 py-8 max-w-xl">
        <!-- Cabeçalho -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Novo Cliente</h1>
            <div class="space-x-2">
            <a href="index.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-200">
                Voltar ao Início
            </a>
            <a href="clientes.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-200">
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
            <form action="cadastrar_cliente.php" method="POST" class="space-y-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nome Completo:</label>
                    <input type="text" name="nome" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">E-mail:</label>
                    <input type="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Telefone:</label>
                        <input type="text" name="telefone" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">CPF:</label>
                        <input type="text" name="cpf" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Estado (UF):</label>
                        <input type="text" name="estado" maxlength="2" placeholder="Ex: SP" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Senha:</label>
                        <input type="password" name="senha" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-200">
                        Guardar Cliente
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