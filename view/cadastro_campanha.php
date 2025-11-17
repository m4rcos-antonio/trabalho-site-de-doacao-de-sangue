<?php
session_start();
// Inclui a conexão com o banco de dados (se for usada)
require_once __DIR__ . '/../config/config.php'; 

// Adiciona a lógica de variáveis para o cabeçalho (Mesmo que o ADM não precise logar aqui)
$nome_usuario = $_SESSION['user_name'] ?? 'ADM'; 
$nivel_acesso = $_SESSION['user_level'] ?? 'ADM'; 

// Prepara e executa a consulta para buscar todos os hemocentros
$sql = "SELECT id, nome, cidade, estado FROM hemocentros ORDER BY nome ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$hemocentros = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Aqui você pode adicionar lógica de verificação de login ADM, se desejar
// if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] !== 'ADM') { /* redirecionar */ }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Campanha - Doe Sangue Já</title>
    <link rel="stylesheet" href="../assets/style.css"> 
</head>
<body>
    <header id="cabecalho"> 
        <div class="logo">
            <img src="../assets/img/gota-de-sangue.png" class="gota-de-sangue" alt="Gota de Sangue">
            <span class="nome-site">Doe Sangue Já</span>
        </div>
        
        <div class="header-right">
            <div class="user-info">
                <div class="user-greeting">Bem-vindo,</div>
                <a href="visualizar-conta.php" class="user-name-link">
                    <div class="user-name" id="userName"><?php echo htmlspecialchars($nome_usuario); ?></div>
                </a>
            </div>
            <button class="logout-btn" onclick="window.location.href='logout.php'">Sair</button>
        </div>
    </header>

    <main id="container-principal" class="wide-form-page"> 
        <div id="caixa-login" class="form-card"> 
            <h1 class="titulo-principal">Cadastrar Nova Campanha</h1>
            
            <form id="formulario-campanha" action="../api/processo_campanha.php" method="POST">
                
                <div class="campo-formulario">
                    <label for="nome-campanha">Nome da Campanha:</label>
                    <input type="text" id="nome-campanha" name="nome-campanha" required>
                </div>

                <div class="campo-formulario">
                    <label for="hemocentro-id">Hemocentro Responsável:</label>
                    <select id="hemocentro-id" name="id_hemocentro" required>
                        <option value="" disabled selected>Selecione um Hemocentro</option>
                        <?php foreach ($hemocentros as $hemocentro): ?>
                            <option value="<?php echo $hemocentro['id']; ?>">
                                <?php echo htmlspecialchars($hemocentro['nome']); ?> 
                                (<?php echo htmlspecialchars($hemocentro['cidade']); ?>/<?php echo htmlspecialchars($hemocentro['estado']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="campo-formulario full-width">
                    <label for="descricao">Descrição:</label>
                    <textarea id="descricao" name="descricao" rows="4"></textarea>
                </div>

                <div class="campo-formulario">
                    <label for="data-inicio">Data de Início:</label>
                    <input type="date" id="data-inicio" name="data-inicio" required>
                </div>

                <div class="campo-formulario">
                    <label for="data-fim">Data de Fim:</label>
                    <input type="date" id="data-fim" name="data-fim">
                </div>

                <div class="campo-formulario full-width">
                    <label for="tipo-sanguineo">Tipos Sanguíneos Necessários (Opcional):</label>
                    
                    <select id="tipos-sanguineos" name="tipos_sanguineos[]" multiple required>
                        <option value="" disabled selected>Selecione (Ctrl+Clique para múltiplos)</option>
                        <option value="O+">O Positivo (O+)</option>
                        <option value="O-">O Negativo (O-)</option>
                        <option value="A+">A Positivo (A+)</option>
                        <option value="A-">A Negativo (A-)</option>
                        <option value="B+">B Positivo (B+)</option>
                        <option value="B-">B Negativo (B-)</option>
                        <option value="AB+">AB Positivo (AB+)</option>
                        <option value="AB-">AB Negativo (AB-)</option>
                    </select>
                </div>
                
                <div class="donor-form-actions">
                        <button type="submit" class="donor-btn donor-btn-primary">Cadastrar Campanha</button>
                    </div>>
                
            </form>
        </div>
    </main>

    <footer id="rodape">
        <div class="secao-rodape">
            <p>&copy; 2025 Doe Sangue Já. Todos os direitos reservados.</p>
        </div>
        <div class="secao-rodape">
            <a href="#" class="link-social">
                <img src="../assets/img/logo-facebook.png" alt="">
            </a>
            <a href="#" class="link-social">
                <img src="../assets/img/logo-instagram.png" alt="">
            </a>
            <a href="#" class="link-social">
                <img src="../assets/img/logo-twitter.png" alt="">
            </a>
        </div>
    </footer>
</body>
</html>