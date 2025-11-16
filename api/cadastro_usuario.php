<?php
// === CÓDIGO TEMPORÁRIO PARA EXIBIR ERROS (REMOVER APÓS O SUCESSO) ===
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// ==========================================

session_start(); 
require_once __DIR__ . '/../config/config.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../view/cadastro_doadores.html?erro=" . urlencode("Método de acesso inválido."));
    exit;
}

// 1. Coleta dos dados (Usando os nomes dos campos do seu HTML)
$fullName = $_POST['fullName'] ?? ''; 
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';
$bloodType = $_POST['bloodType'] ?? ''; 
$cpf = $_POST['cpf'] ?? '';
$birthDate = $_POST['birthDate'] ?? ''; 
$telefone = $_POST['phoneNumber'] ?? ''; // <input name="phoneNumber">
$address = $_POST['address'] ?? ''; 
$city = $_POST['city'] ?? '';
$state = $_POST['state'] ?? '';
$zipCode = $_POST['zipCode'] ?? '';

// ... Validação de campos obrigatórios ...

if (empty($fullName) || empty($email) || empty($senha) || empty($bloodType) || empty($cpf)) {
    header("Location: ../view/cadastro_doadores.html?erro=" . urlencode("Campos obrigatórios faltando."));
    exit;
}

try {
    // 2. Verifica se o E-mail já existe
    $sql_check = "SELECT id FROM doadores WHERE email = :email";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindValue(':email', $email);
    $stmt_check->execute();
    
    if ($stmt_check->rowCount() > 0) {
        header("Location: ../view/cadastro_doadores.html?erro=" . urlencode("Este e-mail já está cadastrado."));
        exit;
    }

    // 3. CRIAÇÃO DO HASH E NÍVEL DE ACESSO
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $nivel_acesso = 'DOADOR';

    // 4. Inserção no Banco de Dados - AGORA USANDO OS NOMES CORRETOS DA SUA TABELA
    $sql_insert = "INSERT INTO doadores (
        nome,             -- Corrigido de fullName para nome
        email, 
        senha, 
        nivel_acesso, 
        tipo_sangue,      -- Corrigido de bloodType para tipo_sangue
        cpf, 
        data_nascimento,  -- Corrigido de birthDate para data_nascimento
        telefone, 
        endereco,         -- Corrigido de address para endereco
        cidade,           -- Corrigido de city para cidade
        estado,           -- Corrigido de state para estado
        cep               -- Corrigido de zipCode para cep
    ) 
    VALUES (
        :nome, 
        :email, 
        :senha, 
        :nivel_acesso, 
        :tipo_sangue, 
        :cpf, 
        :data_nascimento, 
        :telefone, 
        :endereco, 
        :cidade, 
        :estado, 
        :cep
    )";
                   
    $stmt_insert = $pdo->prepare($sql_insert);
    
    // ATENÇÃO: Os bindValue usam as variáveis do PHP (dados do formulário)
    $stmt_insert->bindValue(':nome', $fullName);
    $stmt_insert->bindValue(':email', $email);
    $stmt_insert->bindValue(':senha', $senha_hash);
    $stmt_insert->bindValue(':nivel_acesso', $nivel_acesso);
    $stmt_insert->bindValue(':tipo_sangue', $bloodType);
    $stmt_insert->bindValue(':cpf', $cpf);
    $stmt_insert->bindValue(':data_nascimento', $birthDate);
    $stmt_insert->bindValue(':telefone', $telefone); 
    $stmt_insert->bindValue(':endereco', $address);
    $stmt_insert->bindValue(':cidade', $city);
    $stmt_insert->bindValue(':estado', $state);
    $stmt_insert->bindValue(':cep', $zipCode);
    
    $stmt_insert->execute();

    // 5. Cadastro BEM SUCEDIDO: Redirecionamento
    // Remova as linhas de depuração (ini_set) após confirmar que esta linha funciona
    header("Location: ../view/login.html?sucesso=" . urlencode("Cadastro realizado com sucesso! Faça seu login."));
    exit;

} catch (PDOException $e) {
    // Se ainda houver erro, esta linha irá exibir o erro de SQL na tela
    die("Erro de Inserção no Banco de Dados: " . $e->getMessage()); 
}
?>