<?php
require_once __DIR__ . '/../config/config.php';

header("Content-Type: application/json; charset=utf-8");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Método inválido"]);
    exit;
}

$email = $_POST["email"] ?? "";

if (empty($email)) {
    echo json_encode(["success" => false, "message" => "Informe o e-mail."]);
    exit;
}

// Verifica se o e-mail está cadastrado
$stmt = $pdo->prepare("SELECT id FROM doadores WHERE email = :email LIMIT 1");
$stmt->execute([":email" => $email]);
$usuario = $stmt->fetch();

if (!$usuario) {
    echo json_encode(["success" => false, "message" => "E-mail não encontrado."]);
    exit;
}

// Gera token
$token = bin2hex(random_bytes(32));
$expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

// Salva token na tabela DOADORES
$stmt = $pdo->prepare("
    UPDATE doadores 
    SET reset_token = :t, reset_token_expira = :e 
    WHERE email = :email
");
$stmt->execute([
    ":t" => $token,
    ":e" => $expira,
    ":email" => $email
]);

$link = "http://localhost/projeto/trabalho-site-de-doacao-de-sangue/view/redefinir.php?token=$token";


echo json_encode([
    "success" => true,
    "message" => "Link de redefinição criado!",
    "link" => $link
]);
