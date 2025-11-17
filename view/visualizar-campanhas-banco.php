<?php
session_start();

// Verificar se está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html"); // Ajuste o caminho se necessário, ex: ../view/login.html
    exit();
}

// Incluir o arquivo de configuração do banco de dados (PDO)
require_once '../config/config.php';

/**
 * Visualizar Campanhas - Com Banco de Dados
 * Arquivo: visualizar-campanhas-banco.php
 * Descrição: Busca campanhas do banco de dados e exibe em cards
 */

// Buscar todas as campanhas
$sql = "SELECT * FROM campanhas ORDER BY data_inicio DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$campanhas = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campanhas - Doe Sangue Já</title>
    <!-- Caminho ajustado para a pasta assets -->
    <link rel="stylesheet" href="../assets/visualizar-campanhas-banco.css">
</head>
<body class="campanhas-page">
    <div class="campanhas-container">
        <!-- Voltar -->
        <div class="campanhas-back-link">
            <!-- Link ajustado para home.php na mesma pasta /view/ -->
            <a href="home.php">← Voltar</a>
        </div>

        <!-- Header -->
        <div class="campanhas-header">
            <h1>Campanhas de Doação</h1>
            <p>Conheça as campanhas ativas de doação de sangue</p>
        </div>

        <!-- Grid de Campanhas -->
        <div class="campanhas-grid">
            <?php if (count($campanhas) > 0): ?>
                <?php foreach ($campanhas as $campanha): ?>
                    <div class="campanhas-card">
                        <div class="campanhas-card-header">
                            <div class="campanhas-card-title">
                                <?php echo htmlspecialchars($campanha['nome']); ?>
                            </div>
                        </div>

                        <div class="campanhas-card-body">
                            <div class="campanhas-description">
                                <?php echo htmlspecialchars($campanha['descricao']); ?>
                            </div>

                            <div class="campanhas-info">
                                <div class="campanhas-info-item">
                                    <span class="campanhas-info-label">Início</span>
                                    <span class="campanhas-info-value">
                                        <?php echo date('d/m/Y', strtotime($campanha['data_inicio'])); ?>
                                    </span>
                                </div>

                                <div class="campanhas-info-item">
                                    <span class="campanhas-info-label">Fim</span>
                                    <span class="campanhas-info-value">
                                        <?php echo date('d/m/Y', strtotime($campanha['data_fim'])); ?>
                                    </span>
                                </div>

                                <div class="campanhas-info-item">
                                    <span class="campanhas-info-label">Centro ID</span>
                                    <span class="campanhas-info-value">
                                        <?php echo htmlspecialchars($campanha['hemocentro_id']); ?>
                                    </span>
                                </div>

                                <div class="campanhas-info-item">
                                    <span class="campanhas-info-label">ID Campanha</span>
                                    <span class="campanhas-info-value">
                                        <?php echo htmlspecialchars($campanha['id']); ?>
                                    </span>
                                </div>
                            </div>

                            <div class="campanhas-card-footer">
                                <!-- Função JavaScript ajustada para usar o nome do arquivo JS -->
                                <button class="campanhas-btn" onclick="verDetalhes('<?php echo htmlspecialchars($campanha['nome']); ?>', '<?php echo htmlspecialchars($campanha['descricao']); ?>', '<?php echo date('d/m/Y', strtotime($campanha['data_inicio'])); ?>', '<?php echo date('d/m/Y', strtotime($campanha['data_fim'])); ?>')">Ver Detalhes</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="campanhas-empty">
                    <p>Nenhuma campanha cadastrada no momento.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Caminho ajustado para a pasta assets -->
    <script src="../assets/visualizar-campanhas-banco.js"></script>
</body>
</html>
