<?php
// Mostrar erros temporariamente (remover em produção)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/../config/config.php';

// Apenas aceita requisição POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../view/login.html?erro=metodo_invalido");
    exit;
}

// Coleta dos campos
$email = trim($_POST["email"] ?? '');
$senha = trim($_POST["senha"] ?? '');

// Campos vazios
if (empty($email) || empty($senha)) {
    header("Location: ../view/login.html?erro=preencha_campos");
    exit;
}

// Busca usuário
$sql = "SELECT id, nome, senha, nivel_acesso FROM doadores WHERE email = :email";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch();

    // Existe usuário?
    if ($usuario) {

        // Verifica senha
        if (password_verify($senha, $usuario['senha'])) {

            // Cria sessão
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_name'] = $usuario['nome'];
            $_SESSION['user_level'] = $usuario['nivel_acesso'];

            // REDIRECIONA PARA home.php (já corrigido)
            header("Location: ../view/home.html");
            exit;

        } else {
            $erro = "Senha incorreta.";
        }
    } else {
        $erro = "E-mail não cadastrado.";
    }

    // Redireciona com erro
    header("Location: ../view/login.html?erro=" . urlencode($erro));
    exit;

} catch (PDOException $e) {

    // Caso algo dê errado com o banco
    header("Location: ../view/login.html?erro=" . urlencode("Erro interno no servidor."));
    exit;
}
?>
