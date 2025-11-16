<?php
require_once __DIR__ . '/../config/config.php';

header("Content-Type: application/json; charset=utf-8");

// --------------------------------------------------------
// VERIFICAÇÃO DO MÉTODO
// --------------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Método não permitido."]);
    exit;
}

// --------------------------------------------------------
// RECEBENDO DADOS DO FORM
// --------------------------------------------------------
$nome       = $_POST["nomeHemocentro"] ?? "";
$cnpj       = $_POST["cnpj"] ?? "";
$telefone   = $_POST["telefone"] ?? "";
$cep        = $_POST["cep"] ?? "";
$logradouro = $_POST["logradouro"] ?? "";
$cidade     = $_POST["cidade"] ?? "";
$estado     = $_POST["estado"] ?? "";

// --------------------------------------------------------
// VALIDAÇÃO BÁSICA
// --------------------------------------------------------
if (empty($nome) || empty($cnpj) || empty($telefone) ||
    empty($cep) || empty($logradouro) || empty($cidade) || empty($estado)) {

    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Preencha todos os campos obrigatórios."]);
    exit;
}

// --------------------------------------------------------
// CORREÇÃO AUTOMÁTICA DO NOME DA RUA (OSM BUG)
// --------------------------------------------------------
$logradouro_original = $logradouro; // salvar no banco do jeito certo

if (
    stripos($logradouro, "Felício Luizari") !== false ||
    stripos($logradouro, "Luizari") !== false
) {
    $logradouro = "Rua Luzari"; // nome reconhecido pela API
}

// --------------------------------------------------------
// GEOCODIFICAÇÃO — MAPS.CO
// --------------------------------------------------------
$enderecoCompleto = "$logradouro, $cidade, $estado, $cep, Brasil";

$apiKey = "691a4a2eb42ef970866722fmg541811"; // <-- substitua pela sua chave
$url = "https://geocode.maps.co/search?q=" . urlencode($enderecoCompleto) . "&api_key=" . $apiKey;

// Chamada CURL
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_USERAGENT => "DoeSangueJa_App/1.0"
]);

$dadosJson = curl_exec($ch);
$erroCurl = curl_error($ch);
$statusCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
curl_close($ch);

if ($erroCurl) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Erro na consulta: $erroCurl"]);
    exit;
}

$dadosGeo = json_decode($dadosJson, true);

// --------------------------------------------------------
// VALIDAÇÃO DO RESULTADO DE GEOLOCALIZAÇÃO
// --------------------------------------------------------
if (!is_array($dadosGeo) || count($dadosGeo) === 0) {
    http_response_code(422);
    echo json_encode(["success" => false, "message" => "Não foi possível encontrar as coordenadas deste endereço."]);
    exit;
}

$lat = $dadosGeo[0]["lat"] ?? null;
$lng = $dadosGeo[0]["lon"] ?? null;

if (!$lat || !$lng) {
    http_response_code(422);
    echo json_encode(["success" => false, "message" => "Coordenadas inválidas retornadas pela API."]);
    exit;
}

// --------------------------------------------------------
// INSERÇÃO NO BANCO DE DADOS
// --------------------------------------------------------
$sql = "INSERT INTO hemocentros (nome, cnpj, telefone, cep, endereco, cidade, estado, lat, lng)
        VALUES (:nome, :cnpj, :telefone, :cep, :endereco, :cidade, :estado, :lat, :lng)";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":nome"     => $nome,
        ":cnpj"     => $cnpj,
        ":telefone" => $telefone,
        ":cep"      => $cep,
        ":endereco" => $logradouro_original, // salva o correto
        ":cidade"   => $cidade,
        ":estado"   => $estado,
        ":lat"      => $lat,
        ":lng"      => $lng
    ]);

    echo json_encode(["success" => true, "message" => "Hemocentro cadastrado com sucesso!"]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Erro ao cadastrar: " . $e->getMessage()]);
}
?>
