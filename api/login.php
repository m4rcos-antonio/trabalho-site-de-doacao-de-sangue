<?php
// === CÓDIGO TEMPORÁRIO PARA EXIBIR ERROS ===
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// ==========================================
// Inicia a sessão para armazenar o status de login

session_start();

// O caminho para config.php é crucial e deve ser corrigido se necessário
require_once __DIR__ . '/../config/config.php';

// Verifica se a requisição é POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    // Redireciona para a página de login se o acesso não for via formulário
    header("Location: ../view/login.html?erro=metodo_invalido");
    exit;
}

// 1. Coleta e sanitiza os dados
$email = $_POST["email"] ?? '';
$senha = $_POST["senha"] ?? '';

// Validação simples
if (empty($email) || empty($senha)) {
    header("Location: ../view/login.html?erro=preencha_campos");
    exit;
}

// 2. Busca o usuário pelo email
$sql = "SELECT id, nome, senha, nivel_acesso FROM doadores WHERE email = :email";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch();

    // 3. Verifica se o usuário foi encontrado
    if ($usuario) {
        // 4. Verifica a senha usando o hash
        if (password_verify($senha, $usuario['senha'])) {
            
            // Login BEM SUCEDIDO! Cria a sessão
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_name'] = $usuario['nome'];
            $_SESSION['user_level'] = $usuario['nivel_acesso']; // Será 'ADM' ou 'DOADOR'

            // Redireciona para o Dashboard (home.php)
            header("Location: ../view/home.php"); 
            exit;

        } else {
            // Senha incorreta
            $erro_msg = "Senha incorreta.";
        }
    } else {
        // Usuário não encontrado
        $erro_msg = "E-mail não cadastrado.";
    }
    
    // Se houve erro (senha ou email), redireciona com mensagem
    header("Location: ../view/login.html?erro=" . urlencode($erro_msg));
    exit;
    
} catch (PDOException $e) {
    // Erro de banco de dados (ex: falha na conexão)
    header("Location: ../view/login.html?erro=" . urlencode("Erro ao conectar ao banco de dados."));
    // Opcional: logar $e->getMessage() para depuração
    exit;
}
?>