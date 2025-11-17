<?php
require_once __DIR__ . '/../config/config.php';

$token = $_POST["token"] ?? "";
$novaSenha = $_POST["nova_senha"] ?? "";

if (!$token || !$novaSenha) {
    die("Dados inválidos.");
}

$stmt = $pdo->prepare("SELECT id FROM usuarios WHERE reset_token = :t AND reset_token_expira > NOW()");
$stmt->execute([":t" => $token]);
$user = $stmt->fetch();

if (!$user) {
    die("Token inválido ou expirado.");
}

// Atualiza senha e limpa token
$stmt = $pdo->prepare("UPDATE usuarios 
                       SET senha = :s, reset_token = NULL, reset_token_expira = NULL
                       WHERE id = :id");
$stmt->execute([
    ":s" => password_hash($novaSenha, PASSWORD_DEFAULT),
    ":id" => $user["id"]
]);

header("Location: ../view/login.html");
exit;