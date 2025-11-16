<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Administrador - Doe Sangue Já</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
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
    
    <main id="container-principal" class="wide-form-page"> 
        <div id="caixa-login" class="form-card">
            <h2 class="titulo-principal">Cadastro de Administrador</h2>
            
            <form id="formulario-adm" action="../api/cadastro_admins.php" method="POST">
                
                <div class="campo-formulario">
                    <label for="fullName">Nome Completo:</label>
                    <input type="text" id="fullName" name="fullName" placeholder="Seu nome completo" required>
                    <div class="error-message" id="fullNameError"></div>
                </div>

                <div class="campo-formulario">
                    <label for="cpf">CPF:</label>
                    <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" required>
                    <div class="error-message" id="cpfError"></div>
                </div>

                <div class="campo-formulario">
                    <label for="birthDate">Data de Nascimento:</label>
                    <input type="date" id="birthDate" name="birthDate" required>
                    <div class="error-message" id="birthDateError"></div>
                </div>

                <div class="campo-formulario">
                    <label for="phone">Telefone:</label>
                    <input type="tel" id="phone" name="phone" placeholder="(00) 00000-0000" required>
                    <div class="error-message" id="phoneError"></div>
                </div>
                
                <div class="campo-formulario">
                    <label for="cep">CEP:</label>
                    <input type="text" id="cep" name="cep" placeholder="00000-000" required>
                </div>
                
                <div class="campo-formulario">
                    <label for="logradouro">Rua/Logradouro:</label>
                    <input type="text" id="logradouro" name="logradouro" placeholder="Rua..." required>
                </div>
                
                <div class="campo-formulario">
                    <label for="numero">Número:</label>
                    <input type="text" id="numero" name="numero" required>
                </div>
                
                <div class="campo-formulario">
                    <label for="bairro">Bairro:</label>
                    <input type="text" id="bairro" name="bairro" required>
                </div>

                <div class="campo-formulario">
                    <label for="cidade">Cidade:</label>
                    <input type="text" id="cidade" name="cidade" required>
                </div>
                
                <div class="campo-formulario">
                    <label for="estado">Estado:</label>
                    <input type="text" id="estado" name="estado" required>
                </div>

                <div class="campo-formulario">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" placeholder="seu.email@exemplo.com" required>
                    <div class="error-message" id="emailError"></div>
                </div>
              
                <div class="campo-formulario">
                    <label for="password">Senha:</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                    <div class="error-message" id="passwordError"></div>
                </div>

                <div class="campo-formulario">
                    <label for="confirmPassword">Confirmar Senha:</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="••••••••" required>
                    <div class="error-message" id="confirmPasswordError"></div>
                </div>
                
                <div class="campo-formulario full-width">
                    <button type="submit" id="botao-cadastrar">Cadastrar</button>
                </div>
            </form>

            <div class="form-links links-adicionais full-width">
                <p>Já é doador? <a href="login.html">Faça login</a></p>
            </div>
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
    
    <script src="../assets/script.js"></script>
</body>
</html>