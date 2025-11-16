<?php
require_once __DIR__ . '/../config/config.php'; 

header("Content-Type: application/json; charset=utf-8");

// Verifica se o método de requisição é POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Método não permitido."], JSON_UNESCAPED_UNICODE);
    exit;
}

// Coleta de dados do formulário
$nome = $_POST["nomeHemocentro"] ?? "";
$cnpj = $_POST["cnpj"] ?? "";
$telefone = $_POST["telefone"] ?? "";
$cep = $_POST["cep"] ?? "";
$logradouro = $_POST["logradouro"] ?? "";
$cidade = $_POST["cidade"] ?? "";
$estado = $_POST["estado"] ?? "";

// Validação básica de campos obrigatórios
if (empty($nome) || empty($cnpj) || empty($telefone) || empty($cep) || empty($logradouro) || empty($cidade) || empty($estado)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Preencha todos os campos obrigatórios."], JSON_UNESCAPED_UNICODE);
    exit;
}

// ==========================
// Lógica de Geocodificação (Nominatim) no PHP
// ==========================
// Monta o endereço completo para a consulta
$enderecoCompleto = "$logradouro, $cidade, $estado, $cep, Brasil";

$url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($enderecoCompleto);

// Configuração do User-Agent (Obrigatório para Nominatim)
$context = stream_context_create([
    'http' => [
        // Mantenha seu User-Agent para evitar bloqueio do Nominatim
        'header' => "User-Agent: DoeSangueJa_App/1.0 (seu.email@exemplo.com)\r\n" 
    ]
]);

// @ suprime erro caso a chamada falhe (ex: sem internet)
$dadosJson = @file_get_contents($url, false, $context);

$lat = null;
$lng = null;

if ($dadosJson !== false) {
    $dadosGeo = json_decode($dadosJson, true);
    // Tenta obter as coordenadas
    $lat = $dadosGeo[0]["lat"] ?? null;
    $lng = $dadosGeo[0]["lon"] ?? null;
}

// Se a geocodificação falhar, retorna um erro em vez de usar uma coordenada fixa
if (empty($lat) || empty($lng)) {
    http_response_code(422); 
    echo json_encode(["success" => false, "message" => "Não foi possível encontrar as coordenadas geográficas para este endereço. Verifique os dados."], JSON_UNESCAPED_UNICODE);
    exit;
}


// ==========================
// Inserção no Banco de Dados (PDO)
// ==========================
$sql = "INSERT INTO hemocentros (nome, cnpj, telefone, cep, endereco, cidade, estado, lat, lng)
        VALUES (:nome, :cnpj, :telefone, :cep, :endereco, :cidade, :estado, :lat, :lng)";

try {
    $stmt = $pdo->prepare($sql); 
    
    $stmt->bindValue(':nome', $nome);
    $stmt->bindValue(':cnpj', $cnpj);
    $stmt->bindValue(':telefone', $telefone);
    $stmt->bindValue(':cep', $cep);
    $stmt->bindValue(':endereco', $logradouro);
    $stmt->bindValue(':cidade', $cidade);
    $stmt->bindValue(':estado', $estado);
    $stmt->bindValue(':lat', $lat);
    $stmt->bindValue(':lng', $lng);

    $stmt->execute();

    http_response_code(201);
    //Retorna resposta padronizada para o JavaScript
    echo json_encode(["success" => true, "message" => "Hemocentro cadastrado com sucesso!"], JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Erro ao cadastrar: " . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
?>