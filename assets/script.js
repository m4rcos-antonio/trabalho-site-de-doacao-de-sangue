document.addEventListener('DOMContentLoaded', function() {
    const formulario = document.getElementById('formulario-recuperacao');
    const emailInput = document.getElementById('email');
    const mensagemStatus = document.getElementById('mensagem-status');
    const botaoEnviar = document.getElementById('botao-enviar');

    if (formulario) {
        formulario.addEventListener('submit', function(event) {
            // 1. Impedir o comportamento padrão de envio do formulário
            event.preventDefault();

            // Limpar mensagens anteriores
            mensagemStatus.textContent = '';
            mensagemStatus.style.color = '';
            botaoEnviar.disabled = true;
            botaoEnviar.textContent = 'Enviando...';

            // 2. Validação simples
            if (!emailInput.value || !emailInput.value.includes('@')) {
                mensagemStatus.textContent = 'Por favor, insira um e-mail válido.';
                mensagemStatus.style.color = '#e74c3c'; // Vermelho de erro
                botaoEnviar.disabled = false;
                botaoEnviar.textContent = 'Enviar Instruções';
                return;
            }

            // 3. Simular o envio (sem chamar o banco de dados)
            // Usamos um setTimeout para simular um atraso de rede
            setTimeout(function() {
                // Simulação de sucesso
                mensagemStatus.textContent = `Instruções de recuperação enviadas para ${emailInput.value}. Verifique sua caixa de entrada.`;
                mensagemStatus.style.color = '#27ae60'; // Verde de sucesso
                
                // Opcional: Limpar o campo após o "envio"
                emailInput.value = '';

                // Restaurar o botão
                botaoEnviar.disabled = false;
                botaoEnviar.textContent = 'Enviar Instruções';

            }, 1500); // Simula um atraso de 1.5 segundos
        });
    }
});
// Pegar o formulário
const form = document.getElementById('formCadastroDonor');

// Quando o formulário for enviado
form.addEventListener('submit', function(event) {
    event.preventDefault(); // Não recarregar a página
    
    // Pegar os valores dos campos
    const nome = document.getElementById('fullName').value;
    const cpf = document.getElementById('cpf').value;
    const dataNascimento = document.getElementById('dateOfBirth').value;
    const peso = document.getElementById('weight').value;
    const tipoSanguineo = document.getElementById('bloodType').value;
    const email = document.getElementById('email').value;
    const telefone = document.getElementById('phone').value;
    const endereco = document.getElementById('address').value;
    const cidade = document.getElementById('city').value;
    const estado = document.getElementById('state').value;
    const cep = document.getElementById('zipCode').value;
    
    // Validar se todos os campos estão preenchidos
    if (!nome || !cpf || !dataNascimento || !peso || !tipoSanguineo || !email || !telefone || !endereco || !cidade || !estado || !cep) {
        alert('Por favor, preencha todos os campos!');
        return;
    }
    
    // Validar idade (mínimo 18 anos)
    const dataNasc = new Date(dataNascimento);
    const hoje = new Date();
    let idade = hoje.getFullYear() - dataNasc.getFullYear();
    const mesAtual = hoje.getMonth();
    const mesNasc = dataNasc.getMonth();
    
    if (mesAtual < mesNasc || (mesAtual === mesNasc && hoje.getDate() < dataNasc.getDate())) {
        idade--;
    }
    
    if (idade < 18) {
        alert('Você deve ter pelo menos 18 anos para doar sangue!');
        return;
    }
    
    // Validar peso (mínimo 50 kg)
    if (peso < 50) {
        alert('Peso mínimo para doação é 50 kg!');
        return;
    }
    
    // Validar email
    if (!email.includes('@')) {
        alert('Email inválido!');
        return;
    }
    
    // Validar CPF (básico)
    if (cpf.length < 11) {
        alert('CPF deve ter pelo menos 11 dígitos!');
        return;
    }
    
    // Se passou em todas as validações, salvar os dados
    const doador = {
        id: Date.now(),
        nome: nome,
        cpf: cpf,
        dataNascimento: dataNascimento,
        peso: peso,
        tipoSanguineo: tipoSanguineo,
        email: email,
        telefone: telefone,
        endereco: endereco,
        cidade: cidade,
        estado: estado,
        cep: cep,
        dataCadastro: new Date().toLocaleDateString('pt-BR')
    };
    
    // Salvar no localStorage (armazenamento do navegador)
    let doadores = JSON.parse(localStorage.getItem('doadores')) || [];
    doadores.push(doador);
    localStorage.setItem('doadores', JSON.stringify(doadores));
    
    // Mostrar mensagem de sucesso
    alert('Doador cadastrado com sucesso!');
    
    // Limpar o formulário
    form.reset();
    
    
});
document.addEventListener('DOMContentLoaded', function() {
    const formulario = document.getElementById('formulario-recuperacao');
    const emailInput = document.getElementById('email');
    const mensagemStatus = document.getElementById('mensagem-status');
    const botaoEnviar = document.getElementById('botao-enviar');

    if (formulario) {
        formulario.addEventListener('submit', function(event) {
            // 1. Impedir o comportamento padrão de envio do formulário
            event.preventDefault();

            // Limpar mensagens anteriores
            mensagemStatus.textContent = '';
            mensagemStatus.style.color = '';
            botaoEnviar.disabled = true;
            botaoEnviar.textContent = 'Enviando...';

            // 2. Validação simples
            if (!emailInput.value || !emailInput.value.includes('@')) {
                mensagemStatus.textContent = 'Por favor, insira um e-mail válido.';
                mensagemStatus.style.color = '#e74c3c'; // Vermelho de erro
                botaoEnviar.disabled = false;
                botaoEnviar.textContent = 'Enviar Instruções';
                return;
            }

            // 3. Simular o envio (sem chamar o banco de dados)
            // Usamos um setTimeout para simular um atraso de rede
            setTimeout(function() {
                // Simulação de sucesso
                mensagemStatus.textContent = `Instruções de recuperação enviadas para ${emailInput.value}. Verifique sua caixa de entrada.`;
                mensagemStatus.style.color = '#27ae60'; // Verde de sucesso
                
                // Opcional: Limpar o campo após o "envio"
                emailInput.value = '';

                // Restaurar o botão
                botaoEnviar.disabled = false;
                botaoEnviar.textContent = 'Enviar Instruções';

            }, 1500); // Simula um atraso de 1.5 segundos
        });
    }
});

// Quando o formulário for enviado
if (form) {
    form.addEventListener('submit', function(event) {
    event.preventDefault(); // Não recarregar a página
    
    // Pegar os valores dos campos
    const nome = document.getElementById('fullName').value;
    const cpf = document.getElementById('cpf').value;
    const dataNascimento = document.getElementById('dateOfBirth').value;
    const peso = document.getElementById('weight').value;
    const tipoSanguineo = document.getElementById('bloodType').value;
    const email = document.getElementById('email').value;
    const telefone = document.getElementById('phone').value;
    const endereco = document.getElementById('address').value;
    const cidade = document.getElementById('city').value;
    const estado = document.getElementById('state').value;
    const cep = document.getElementById('zipCode').value;
    
    // Validar se todos os campos estão preenchidos
    if (!nome || !cpf || !dataNascimento || !peso || !tipoSanguineo || !email || !telefone || !endereco || !cidade || !estado || !cep) {
        alert('Por favor, preencha todos os campos!');
        return;
    }
    
    // Validar idade (mínimo 18 anos)
    const dataNasc = new Date(dataNascimento);
    const hoje = new Date();
    let idade = hoje.getFullYear() - dataNasc.getFullYear();
    const mesAtual = hoje.getMonth();
    const mesNasc = dataNasc.getMonth();
    
    if (mesAtual < mesNasc || (mesAtual === mesNasc && hoje.getDate() < dataNasc.getDate())) {
        idade--;
    }
    
    if (idade < 18) {
        alert('Você deve ter pelo menos 18 anos para doar sangue!');
        return;
    }
    
    // Validar peso (mínimo 50 kg)
    if (peso < 50) {
        alert('Peso mínimo para doação é 50 kg!');
        return;
    }
    
    // Validar email
    if (!email.includes('@')) {
        alert('Email inválido!');
        return;
    }
    
    // Validar CPF (básico)
    if (cpf.length < 11) {
        alert('CPF deve ter pelo menos 11 dígitos!');
        return;
    }
    
    // Se passou em todas as validações, salvar os dados
    const doador = {
        id: Date.now(),
        nome: nome,
        cpf: cpf,
        dataNascimento: dataNascimento,
        peso: peso,
        tipoSanguineo: tipoSanguineo,
        email: email,
        telefone: telefone,
        endereco: endereco,
        cidade: cidade,
        estado: estado,
        cep: cep,
        dataCadastro: new Date().toLocaleDateString('pt-BR')
    };
    
    // Salvar no localStorage (armazenamento do navegador)
    let doadores = JSON.parse(localStorage.getItem('doadores')) || [];
    doadores.push(doador);
    localStorage.setItem('doadores', JSON.stringify(doadores));
    
    // Mostrar mensagem de sucesso
    alert('Doador cadastrado com sucesso!');
    
    // Limpar o formulário
    form.reset();
    
    // Redirecionar para página de listagem (opcional)
    // window.location.href = 'listar-doadores.html';
    });
}

// Lógica para o formulário de Cadastro de Hemocentro
const formHemocentro = document.getElementById('formCadastroHemocentro');

if (formHemocentro) {
    formHemocentro.addEventListener('submit', function(event) {
        event.preventDefault(); // Não recarregar a página
        
        // Pegar os valores dos campos
        const nome = document.getElementById('nomeHemocentro').value;
        const cnpj = document.getElementById('cnpj').value;
        const telefone = document.getElementById('telefone').value;
        const cep = document.getElementById('cep').value;
        const logradouro = document.getElementById('logradouro').value;
        const numero = document.getElementById('numero').value;
        const bairro = document.getElementById('bairro').value;
        const complemento = document.getElementById('complemento').value;
        const cidade = document.getElementById('cidade').value;
        const estado = document.getElementById('estado').value;
        
        // Validar se os campos obrigatórios estão preenchidos
        if (!nome || !cnpj || !telefone || !cep || !logradouro || !numero || !bairro || !cidade || !estado) {
            alert('Por favor, preencha todos os campos obrigatórios (*)!');
            return;
        }
        
        // Validação básica de CNPJ (apenas tamanho)
        if (cnpj.length < 14) {
            alert('CNPJ inválido. Deve ter pelo menos 14 dígitos (sem formatação) ou 18 caracteres (com formatação).');
            return;
        }

        // Validação básica de CEP (apenas tamanho)
        if (cep.length < 8) {
            alert('CEP inválido. Deve ter pelo menos 8 dígitos (sem formatação) ou 9 caracteres (com formatação).');
            return;
        }
        
        // Se passou em todas as validações, salvar os dados
        const hemocentro = {
            id: Date.now(),
            nome: nome,
            cnpj: cnpj,
            telefone: telefone,
            cep: cep,
            logradouro: logradouro,
            numero: numero,
            bairro: bairro,
            complemento: complemento,
            cidade: cidade,
            estado: estado,
            dataCadastro: new Date().toLocaleDateString('pt-BR')
        };
        
        // Salvar no localStorage (armazenamento do navegador)
        let hemocentros = JSON.parse(localStorage.getItem('hemocentros')) || [];
        hemocentros.push(hemocentro);
        localStorage.setItem('hemocentros', JSON.stringify(hemocentros));
        
        // Mostrar mensagem de sucesso
        alert('Hemocentro cadastrado com sucesso!');
        
        // Limpar o formulário
        formHemocentro.reset();
        
        // Redirecionar para página de listagem (opcional)
        // window.location.href = 'listar_centros.html';
    });
}

// Função de navegação para os cards do dashboard
function navigateTo(page) {
    window.location.href = page;
}

// Função de logout (necessária para o botão Sair no header)
function logout() {
    // Aqui você adicionaria a lógica de limpeza de sessão (ex: localStorage.removeItem('token'))
    alert('Saindo do sistema...');
    window.location.href = 'login.html'; // Redireciona para a tela de login
}



