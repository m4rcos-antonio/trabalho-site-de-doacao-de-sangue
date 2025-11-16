<?php
require_once __DIR__ . '/../config/config.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Hemocentros - Doe Sangue Já</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="donor-page">
    <div class="donor-container">
        <div class="donor-back-link">
            <a href="home.html">← Voltar para o Dashboard</a>
        </div>

        <div class="donor-card">
            <div class="donor-card-header">
                <h1 class="donor-card-title">Cadastro de Hemocentro</h1>
                <p class="donor-card-description">Preencha os dados do novo centro de coleta de sangue</p>
            </div>

            <div class="donor-card-content">
                <form id="formCadastroHemocentro" class="donor-form">
                    <div class="donor-form-section">
                        <h3 class="donor-section-title">Informações Básicas</h3>
                        
                        <div class="donor-form-group">
                            <label for="nomeHemocentro">Nome do Hemocentro *</label>
                            <input 
                                type="text"
                                id="nomeHemocentro"
                                name="nomeHemocentro"
                                placeholder="Nome completo do Hemocentro"
                                required
                            >
                        </div>

                        <div class="donor-form-row">
                            <div class="donor-form-group">
                                <label for="cnpj">CNPJ *</label>
                                <input 
                                    type="text"
                                    id="cnpj"
                                    name="cnpj"
                                    placeholder="00.000.000/0000-00"
                                    required
                                >
                            </div>
                            <div class="donor-form-group">
                                <label for="telefone">Telefone *</label>
                                <input 
                                    type="tel"
                                    id="telefone"
                                    name="telefone"
                                    placeholder="(99) 99999-9999"
                                    required
                                >
                            </div>
                        </div>
                    </div>

                    <div class="donor-form-section">
                        <h3 class="donor-section-title">Endereço</h3>
                        
                        <div class="donor-form-row">
                            <div class="donor-form-group">
                                <label for="cep">CEP *</label>
                                <input 
                                    type="text"
                                    id="cep"
                                    name="cep"
                                    placeholder="00000-000"
                                    required
                                >
                            </div>
                        </div>

                        <div class="donor-form-group">
                            <label for="logradouro">Endereço *</label>
                            <input 
                                type="text"
                                id="logradouro"
                                name="logradouro"
                                placeholder="Rua/Avenida"
                                required
                            >
                            <p class="endereco-reminder">
                                <span class="emoji">⚠️</span> <strong>IMPORTANTE:</strong> Para a localização funcionar, inclua o <strong>número do local</strong> logo após o nome da rua (Ex: Rua A, 123).
                            </p>
                        </div>

                        <div class="donor-form-row">
                            <div class="donor-form-group">
                                <label for="cidade">Cidade *</label>
                                <input 
                                    type="text"
                                    id="cidade"
                                    name="cidade"
                                    placeholder="Cidade"
                                    required
                                >
                            </div>
                            <div class="donor-form-group">
                                <label for="estado">Estado *</label>
                                <select id="estado" name="estado" required>
                                    <option value="">Selecione</option>
                                    <option value="AC">AC</option>
                                    <option value="AL">AL</option>
                                    <option value="AP">AP</option>
                                    <option value="AM">AM</option>
                                    <option value="BA">BA</option>
                                    <option value="CE">CE</option>
                                    <option value="DF">DF</option>
                                    <option value="ES">ES</option>
                                    <option value="GO">GO</option>
                                    <option value="MA">MA</option>
                                    <option value="MT">MT</option>
                                    <option value="MS">MS</option>
                                    <option value="MG">MG</option>
                                    <option value="PA">PA</option>
                                    <option value="PB">PB</option>
                                    <option value="PR">PR</option>
                                    <option value="PE">PE</option>
                                    <option value="PI">PI</option>
                                    <option value="RJ">RJ</option>
                                    <option value="RN">RN</option>
                                    <option value="RS">RS</option>
                                    <option value="RO">RO</option>
                                    <option value="RR">RR</option>
                                    <option value="SC">SC</option>
                                    <option value="SP">SP</option>
                                    <option value="SE">SE</option>
                                    <option value="TO">TO</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="donor-form-actions">
                        <button type="submit" class="donor-btn donor-btn-primary">Cadastrar Hemocentro</button>
                        <a href="visualizar-mapa.html" class="donor-btn donor-btn-secondary">Ver Hemocentros</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../assets/script.js"></script>
</body>
</html>