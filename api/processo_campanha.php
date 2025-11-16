<?php
// Inicia a sessão (necessário para controle de acesso futuro)
session_start();

// 1. Inclui a conexão com o banco de dados (config/config.php)
// Assumindo que o arquivo de configuração está um nível acima de 'api/'
require_once __DIR__ . '/../config/config.php'; 

// 2. Verifica se a requisição é POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../view/cadastro_campanha.php?erro=" . urlencode("Método de requisição inválido."));
    exit;
}

// 3. Coleta e Sanitiza os Dados
$nomeCampanha     = $_POST['nome-campanha'] ?? '';
$descricao        = $_POST['descricao'] ?? '';
$dataInicio       = $_POST['data-inicio'] ?? '';
$dataFim          = $_POST['data-fim'] ?? NULL; // Pode ser NULL se não for preenchido
$hemocentroId     = $_POST['id_hemocentro'] ?? ''; // ID do Hemocentro
$tiposSanguineos  = $_POST['tipos_sanguineos'] ?? []; // Array de tipos
$statusCampanha   = 'ATIVA'; // Define um status inicial padrão

// 4. Converte os Tipos Sanguíneos para String
// Se houver tipos selecionados, une-os com vírgulas. Ex: "O+,A-"
// Se não houver (array vazio), a string será vazia.
if (!empty($tiposSanguineos)) {
    $tiposString = implode(', ', $tiposSanguineos);
} else {
    $tiposString = '';
}

// 5. Validações Básicas
if (empty($nomeCampanha) || empty($descricao) || empty($dataInicio) || empty($hemocentroId)) {
    header("Location: ../view/cadastro_campanha.php?erro=" . urlencode("Por favor, preencha todos os campos obrigatórios (Nome, Descrição, Data de Início e Hemocentro)."));
    exit;
}

// 6. Prepara a Inserção no Banco de Dados
// NOTA: Assumimos que você tem uma tabela 'campanhas' com as colunas:
// id, nome, descricao, data_inicio, data_fim, id_hemocentro, tipos_sanguineos_necessarios, status
$sql = "INSERT INTO campanhas (
            nome, 
            descricao, 
            data_inicio, 
            data_fim, 
            id_hemocentro, 
            tipos_sanguineos_necessarios, 
            status
        ) VALUES (
            :nome, 
            :descricao, 
            :data_inicio, 
            :data_fim, 
            :id_hemocentro, 
            :tipos_sanguineos, 
            :status
        )";

try {
    $stmt = $pdo->prepare($sql);
    
    // Liga os valores
    $stmt->bindValue(':nome', $nomeCampanha);
    $stmt->bindValue(':descricao', $descricao);
    $stmt->bindValue(':data_inicio', $dataInicio);
    
    // Liga a data de fim. Usa NULL se a data for vazia.
    $stmt->bindValue(':data_fim', $dataFim ?: NULL); 
    
    $stmt->bindValue(':id_hemocentro', $hemocentroId, PDO::PARAM_INT);
    $stmt->bindValue(':tipos_sanguineos', $tiposString);
    $stmt->bindValue(':status', $statusCampanha);
    
    // 7. Executa a Inserção
    if ($stmt->execute()) {
        // Sucesso: Redireciona para o dashboard com mensagem
        header("Location: ../view/home.php?sucesso=" . urlencode("Campanha '{$nomeCampanha}' cadastrada com sucesso!"));
        exit;
    } else {
        // Erro na execução (embora a exceção já capture a maioria dos erros)
        header("Location: ../view/cadastro_campanha.php?erro=" . urlencode("Erro ao salvar dados da campanha no banco."));
        exit;
    }
} catch (PDOException $e) {
    // Trata erros de banco de dados
    // Normalmente, você logaria $e->getMessage() em vez de exibi-lo
    $mensagemErro = "Erro de BD ao cadastrar campanha. Código: " . $e->getCode();
    header("Location: ../view/cadastro_campanha.php?erro=" . urlencode($mensagemErro));
    exit;
}
?>