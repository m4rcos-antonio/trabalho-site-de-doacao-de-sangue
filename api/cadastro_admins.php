<?php
// Inicia a sessão para controle de acesso (mesmo que, por enquanto, a tela seja pública)
session_start();

// Importa a configuração do banco de dados (config/config.php)
require_once __DIR__ . '/../config/config.php';

// Verifica se a requisição é POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../view/cadastro_adm.php?erro=" . urlencode("Método de requisição inválido."));
    exit;
}

// 1. Coleta e Sanitiza os Dados
$nome = $_POST['fullName'] ?? '';
$cpf = $_POST['cpf'] ?? '';
$email = $_POST['email'] ?? '';
$senha = $_POST['password'] ?? '';
$confirmarSenha = $_POST['confirmPassword'] ?? '';

// 2. Validações Básicas (Adicione mais validações aqui se necessário!)
if (empty($nome) || empty($cpf) || empty($email) || empty($senha) || empty($confirmarSenha)) {
    header("Location: ../view/cadastro_adm.php?erro=" . urlencode("Por favor, preencha todos os campos obrigatórios."));
    exit;
}

if ($senha !== $confirmarSenha) {
    header("Location: ../view/cadastro_adm.php?erro=" . urlencode("As senhas não coincidem."));
    exit;
}

// 3. Cria o Hash da Senha e Define o Nível de Acesso
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);
$nivelAcesso = 'ADM'; // <-- PONTO CRUCIAL: define o usuário como ADM

// 4. Prepara a Inserção no Banco de Dados
// NOTA: A tabela é 'doadores', pois ela comporta ambos os níveis (ADM e DOADOR).
$sql = "INSERT INTO doadores (nome, cpf, email, senha, nivel_acesso) 
        VALUES (:nome, :cpf, :email, :senha, :nivel_acesso)";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':nome', $nome);
    $stmt->bindValue(':cpf', $cpf);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':senha', $senhaHash);
    $stmt->bindValue(':nivel_acesso', $nivelAcesso);
    
    // 5. Executa a Inserção
    if ($stmt->execute()) {
        // Sucesso: Redireciona para o login ou dashboard
        header("Location: ../view/login.html?sucesso=" . urlencode("Administrador cadastrado com sucesso! Faça login."));
        exit;
    } else {
        // Erro na execução
        header("Location: ../view/cadastro_adm.php?erro=" . urlencode("Erro ao salvar dados. Tente novamente."));
        exit;
    }

} catch (PDOException $e) {
    // Erro de conexão ou SQL (ex: e-mail ou CPF duplicado)
    // Para apresentação, você pode simplificar a mensagem de erro.
    $erro_msg = "Erro no cadastro. E-mail ou CPF já podem estar em uso.";
    
    header("Location: ../view/cadastro_adm.php?erro=" . urlencode($erro_msg));
    exit;
}

?>