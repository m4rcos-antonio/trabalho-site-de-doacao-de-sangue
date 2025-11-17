document.addEventListener('DOMContentLoaded', function () {
    const formulario = document.getElementById('formulario-recuperacao');
    const emailInput = document.getElementById('email');
    const botaoEnviar = document.getElementById('botao-enviar');

    if (formulario) {
        formulario.addEventListener('submit', function (event) {
            event.preventDefault();

            botaoEnviar.disabled = true;
            botaoEnviar.textContent = 'Enviando...';

            const formData = new FormData();
            formData.append('email', emailInput.value);

            fetch('../api/esqueci.php', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    alert("Link para redefinir senha:\n\n" + data.link);
                } else {
                    alert("Erro: " + data.message);
                }
            })
            .catch(() => {
                alert("Erro ao se comunicar com o servidor.");
            })
            .finally(() => {
                botaoEnviar.disabled = false;
                botaoEnviar.textContent = 'Enviar Instruções';
            });
        });
    }
});



// ==========================
// CADASTRO DE DOADOR
// ==========================
const formDoador = document.getElementById('formCadastroDonor');

if (formDoador) {
    formDoador.addEventListener('submit', function(event) {
        event.preventDefault();

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

        if (!nome || !cpf || !dataNascimento || !peso || !tipoSanguineo ||
            !email || !telefone || !endereco || !cidade || !estado || !cep) {
            alert('Por favor, preencha todos os campos!');
            return;
        }

        const dataNasc = new Date(dataNascimento);
        const hoje = new Date();
        let idade = hoje.getFullYear() - dataNasc.getFullYear();

        if (hoje.getMonth() < dataNasc.getMonth() ||
            (hoje.getMonth() === dataNasc.getMonth() && hoje.getDate() < dataNasc.getDate())) {
            idade--;
        }

        if (idade < 18) {
            alert('Você deve ter pelo menos 18 anos para doar sangue!');
            return;
        }

        if (peso < 50) {
            alert('Peso mínimo para doação é 50 kg!');
            return;
        }

        if (!email.includes('@')) {
            alert('Email inválido!');
            return;
        }

        if (cpf.length < 11) {
            alert('CPF inválido!');
            return;
        }

        const doador = {
            id: Date.now(),
            nome, cpf, dataNascimento, peso, tipoSanguineo,
            email, telefone, endereco, cidade, estado, cep,
            dataCadastro: new Date().toLocaleDateString('pt-BR')
        };

        let doadores = JSON.parse(localStorage.getItem('doadores')) || [];
        doadores.push(doador);
        localStorage.setItem('doadores', JSON.stringify(doadores));

        alert('Doador cadastrado com sucesso!');
        formDoador.reset();
    });
}


// ==========================
// CADASTRO DE HEMOCENTRO - AGORA ENVIANDO PARA API PHP
// ==========================
const formHemocentro = document.getElementById('formCadastroHemocentro');

if (formHemocentro) {
    formHemocentro.addEventListener('submit', function(event) {
        event.preventDefault();

        const submitButton = formHemocentro.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.textContent = 'Cadastrando Hemocentro...'; 

        const formData = new FormData(formHemocentro);

        // Checagem básica dos campos necessários para o PHP fazer o Nominatim
        const nome = formData.get('nomeHemocentro');
        const cep = formData.get('cep');
        const logradouro = formData.get('logradouro');
        const cidade = formData.get('cidade');

        if (!nome || !cep || !logradouro || !cidade) {
            alert('Por favor, preencha os campos essenciais para o cadastro!');
            submitButton.disabled = false;
            submitButton.textContent = 'Cadastrar Hemocentro';
            return;
        }
        
        // Envio para a API PHP (que agora faz o Nominatim e salva no banco)
        fetch('../api/cadastro_hemocentro.php', {
            method: 'POST',
            body: formData 
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                formHemocentro.reset();
            } else {
                // Mensagem de erro virá do PHP, incluindo falha na geocodificação
                alert(`Erro ao cadastrar: ${data.message}`);
            }
        })
        .catch(error => {
            console.error('Erro na requisição final:', error);
            alert('Ocorreu um erro de comunicação com o servidor.');
        })
        .finally(() => {
            submitButton.disabled = false;
            submitButton.textContent = 'Cadastrar Hemocentro';
        });
    });
}


// ==========================
// MAPA — PUXANDO DO BANCO VIA PHP
// ==========================
if (document.getElementById('mapa')) {

    var mapa = L.map('mapa').setView([-22.12, -51.39], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19
    }).addTo(mapa);

    let listaHemocentros = [];

    // AGORA CARREGA DO BANCO (PHP) E NÃO MAIS JSON
    fetch("../api/get_hemocentros.php")
        .then(response => response.json())
        .then(dados => {

            listaHemocentros = dados;

            let select = document.getElementById("listaHemocentros");

            if (select) {
                dados.forEach((h, index) => {
                    let option = document.createElement("option");
                    option.value = index;
                    option.textContent = h.nome;
                    select.appendChild(option);
                });
            }

            dados.forEach(h => {
                // Para o mapa funcionar, o get_hemocentros.php DEVE retornar lat e lng
                L.marker([h.lat, h.lng])
                    .addTo(mapa)
                    .bindPopup(`
                        <b>${h.nome}</b><br>
                        ${h.endereco}, ${h.cidade} - ${h.estado}<br>
                        CEP: ${h.cep}<br>
                        Tel: ${h.telefone}
                    `);
            });
        });

    // Selecionar hemocentro
    window.mostrarSelecionado = function() {
        let index = document.getElementById("listaHemocentros").value;
        if (index === "") return;

        let h = listaHemocentros[index];

        mapa.setView([h.lat, h.lng], 16);

        L.marker([h.lat, h.lng])
            .addTo(mapa)
            .bindPopup(`
                <b>${h.nome}</b><br>
                ${h.endereco}, ${h.cidade} - ${h.estado}<br>
                CEP: ${h.cep}<br>
                Tel: ${h.telefone}
            `)
            .openPopup();
    };
}




// ==========================
// Funções gerais
// ==========================
function navigateTo(page) {
    window.location.href = page;
}

function logout() {
    alert('Saindo do sistema...');
    window.location.href = 'login.html';
}


// ==========================
// AUTOCOMPLETAR ENDEREÇO VIA CEP (Melhoria de Usabilidade)
// ==========================
const inputCep = document.getElementById('cep');
const inputLogradouro = document.getElementById('logradouro');
const inputCidade = document.getElementById('cidade');
const inputEstado = document.getElementById('estado');

if (inputCep) {
    inputCep.addEventListener('blur', function() {
        const cep = inputCep.value.replace(/\D/g, ''); // Remove caracteres não numéricos

        if (cep.length !== 8) return;

        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(response => response.json())
            .then(data => {
                if (data.erro) {
                    alert('CEP não encontrado. Preencha o endereço manualmente.');
                    return;
                }
                
                // Preenche os campos automaticamente
                if (inputLogradouro) inputLogradouro.value = data.logradouro;
                if (document.getElementById('bairro')) document.getElementById('bairro').value = data.bairro; // Adiciona Bairro
                if (inputCidade) inputCidade.value = data.localidade;
                if (inputEstado) inputEstado.value = data.uf;

                // Move o foco para o próximo campo para facilitar a digitação (Ex: Número)
                if (document.getElementById('numero')) {
                    document.getElementById('numero').focus();
                } else if (inputLogradouro) {
                    inputLogradouro.focus();
                }
            })
            .catch(error => {
                console.error("Erro na consulta ViaCEP:", error);
            });
    });
}