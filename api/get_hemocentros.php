<?php
header("Content-Type: application/json; charset=utf-8");

require_once __DIR__ . '/../config/config.php';


$sql = "SELECT id, nome, endereco, cidade, estado, cep, lat, lng FROM hemocentros";

$stmt = $pdo->query($sql);
$dados = $stmt->fetchAll();

echo json_encode($dados, JSON_UNESCAPED_UNICODE);

?>