<?php
session_start();

// 1) Verifica se está logado
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_level'])) {
    header("Location: ../view/login.html");
    exit();
}

require_once '../config/config.php';

$usuario_id = $_SESSION['user_id'];
$nivel = $_SESSION['user_level']; // 'ADM' ou 'DOADOR'

// =============================================================
// 2) Buscar dados sempre na tabela DOADORES (admins incluídos)
// =============================================================
$sql = "SELECT * FROM doadores WHERE id = :id LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $usuario_id, PDO::PARAM_INT);
$stmt->execute();
$usuario = $stmt->fetch();

if (!$usuario) {
    die("Erro: Usuário não encontrado na tabela doadores.");
}

// =============================================================
// 3) Se for DOADOR, carregar informações de doação
// =============================================================
$doador = null;
$ultima_doacao = '-';
$proxima_doacao = '-';
$total_doacoes = 0;

if ($nivel === 'DOADOR') {
    $doador = $usuario;

    // Total de doações concluídas
    $sql_count = "SELECT COUNT(*) AS total FROM agendamentos 
                  WHERE doador_id = :id AND status = 'concluido'";
    $stmt_count = $pdo->prepare($sql_count);
    $stmt_count->bindParam(':id', $usuario_id);
    $stmt_count->execute();
    $row_count = $stmt_count->fetch();
    $total_doacoes = $row_count['total'] ?? 0;

    // Última doação
    $sql_ultima = "SELECT data_agendamento FROM agendamentos 
                   WHERE doador_id = :id AND status = 'concluido'
                   ORDER BY data_agendamento DESC LIMIT 1";
    $stmt_ultima = $pdo->prepare($sql_ultima);
    $stmt_ultima->bindParam(':id', $usuario_id);
    $stmt_ultima->execute();

    if ($stmt_ultima->rowCount() > 0) {
        $ultima = $stmt_ultima->fetch();
        $ultima_doacao = date('d/m/Y', strtotime($ultima['data_agendamento']));

        // Intervalo: homem 60, mulher 90
        // O campo 'genero' não está na imagem, mas assumiremos que existe
        $genero = $doador['genero'] ?? 'Masculino';
        $intervalo = ($genero === 'Feminino') ? 90 : 60;

        $data_prox = new DateTime($ultima['data_agendamento']);
        $data_prox->modify("+$intervalo days");
        $proxima_doacao = $data_prox->format('d/m/Y');
    }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Conta</title>
    <link rel="stylesheet" href="../assets/style.css"> 
</head>
<body class="conta-page"> 
    <header id="cabecalho">
        <div class="logo">
            <img src="../assets/img/gota-de-sangue.png" class="gota-de-sangue">
            <span class="nome-site">Doe Sangue Já</span>
        </div>
        <nav id="menu-navegacao">
            <a href="index.html" class="link-menu">Início</a>
            <a href="#" class="link-menu">Sobre</a>
            <a href="#" class="link-menu">Contato</a>
        </nav>
    </header>

<div class="conta-container">

    <div class="donor-card">
        <div class="conta-header">
            <h1>Minha Conta</h1>
            <p>Informações da sua conta</p>
        </div>

        <div class="conta-content">

            <div class="conta-profile">
                <div class="conta-avatar">👤</div>
                <div class="conta-profile-info">
                    <h2><?php echo htmlspecialchars($usuario['nome']); ?></h2>
                    <p><?php echo htmlspecialchars($usuario['email']); ?></p>
                    <p><strong>Nível:</strong> <?php echo $nivel; ?></p>
                </div>
            </div>

            <?php if ($nivel === 'ADM'): ?>
                <div class="conta-section">
                    <h3>Informações do Administrador</h3>

                    <div class="conta-grid">
                        <div class="conta-item">
                            <label>Telefone</label>
                            <p><?php echo htmlspecialchars($usuario['telefone'] ?? '-'); ?></p>
                        </div>

                        <div class="conta-item conta-full">
                            <label>Função</label>
                            <p>Administrador do Sistema</p>
                        </div>
                    </div>
                </div>

            <?php endif; ?>

            <?php if ($nivel === 'DOADOR'): ?>

                <div class="conta-section">
                    <h3>Informações Pessoais</h3>

                    <div class="conta-grid">
                        <div class="conta-item">
                            <label>CPF</label>
                            <p><?php echo htmlspecialchars($doador['cpf']); ?></p>
                        </div>

                        <div class="conta-item">
                            <label>Data de Nascimento</label>
                            <p><?php echo date('d/m/Y', strtotime($doador['data_nascimento'])); ?></p>
                        </div>
                        
                        <div class="conta-item">
                            <label>Peso</label>
                            <p><?php echo htmlspecialchars($doador['peso']); ?> kg</p>
                        </div>

                        <div class="conta-item">
                            <label>Gênero</label>
                            <p><?php echo htmlspecialchars($doador['genero'] ?? '-'); ?></p>
                        </div>

                        <div class="conta-item">
                            <label>Tipo Sanguíneo</label>
                            <p class="conta-blood"><?php echo htmlspecialchars($doador['tipo_sanguineo']); ?></p>
                        </div>

                        <div class="conta-item">
                            <label>Telefone</label>
                            <p><?php echo htmlspecialchars($doador['telefone']); ?></p>
                        </div>
                    </div>
                </div>

                <div class="conta-section">
                    <h3>Endereço</h3>

                    <div class="conta-grid">
                        <div class="conta-item conta-full">
                            <label>Endereço</label>
                            <p><?php echo htmlspecialchars($doador['endereco']); ?></p>
                        </div>

                        <div class="conta-item">
                            <label>Cidade</label>
                            <p><?php echo htmlspecialchars($doador['cidade']); ?></p>
                        </div>

                        <div class="conta-item">
                            <label>Estado</label>
                            <p><?php echo htmlspecialchars($doador['estado']); ?></p>
                        </div>

                        <div class="conta-item">
                            <label>CEP</label>
                            <p><?php echo htmlspecialchars($doador['cep']); ?></p>
                        </div>
                    </div>
                </div>

                <div class="conta-section">
                    <h3>Histórico de Doações</h3>

                    <div class="conta-grid">
                        <div class="conta-item">
                            <label>Total de Doações</label>
                            <p class="conta-blood"><?php echo $total_doacoes; ?></p>
                        </div>

                        <div class="conta-item">
                            <label>Última Doação</label>
                            <p><?php echo $ultima_doacao; ?></p>
                        </div>

                        <div class="conta-item">
                            <label>Próxima Doação</label>
                            <p><?php echo $proxima_doacao; ?></p>
                        </div>
                    </div>
                </div>

            <?php endif; ?>

            <div class="conta-actions">
                <a href="home.html" class="conta-btn conta-btn-sec">Voltar</a>
                <form method="POST" action="logout.php">
                    <button type="submit" class="conta-btn conta-btn-danger">Sair</button>
                </form>
            </div>

        </div>
    </div>
</div>

    <footer id="rodape">
        <div class="secao-rodape">
            <p>&copy; 2025 Doe Sangue Já. Todos os direitos reservados.</p>
        </div>
        <div class="secao-rodape">
            <a href="#" class="link-social">
                <!-- Ícone do Facebook -->
                <img src="../assets/img/logo-facebook.png" alt="">
            </a>
            <a href="#" class="link-social">
                <!-- Ícone do Instagram -->
                <img src="../assets/img/logo-instagram.png" alt="">
            </a>
            <a href="#" class="link-social">
                <!-- Ícone do Twitter -->
                <img src="../assets/img/logo-twitter.png" alt="">
            </a>
        </div>
    </footer>

</body>
</html>