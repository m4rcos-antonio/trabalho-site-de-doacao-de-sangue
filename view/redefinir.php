<?php
require_once __DIR__ . '/../config/config.php';

$token = $_GET["token"] ?? "";
if (!$token) {
    die("Token inválido.");
}

$stmt = $pdo->prepare("
    SELECT * FROM doadores
    WHERE reset_token = :t AND reset_token_expira > NOW()
");
$stmt->execute([":t" => $token]);
$user = $stmt->fetch();

if (!$user) {
    die("Token expirado ou inválido.");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Redefinir Senha</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<h2 style="text-align:center;">Redefinir Senha</h2>

<form method="POST" action="../api/redefinir_senha.php" style="width:300px;margin:auto;">
    <input type="hidden" name="token" value="<?php echo $token; ?>">

    <label>Nova senha:</label>
    <input type="password" name="nova_senha" required>

    <button type="submit">Salvar nova senha</button>
</form>

</body>
</html>
