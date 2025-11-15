// ==========================
// RECUPERAÇÃO DE SENHA
// ==========================
document.addEventListener('DOMContentLoaded', function() {
    const formulario = document.getElementById('formulario-recuperacao');
    const emailInput = document.getElementById('email');
    const mensagemStatus = document.getElementById('mensagem-status');
    const botaoEnviar = document.getElementById('botao-enviar');

    if (formulario) {
        formulario.addEventListener('submit', function(event) {
            event.preventDefault();

            mensagemStatus.textContent = '';
            botaoEnviar.disabled = true;
            botaoEnviar.textContent = 'Enviando...';

            if (!emailInput.value || !emailInput.value.includes('@')) {
                mensagemStatus.textContent = 'Por favor, insira um e-mail válido.';
                mensagemStatus.style.color = '#e74c3c';
                botaoEnviar.disabled = false;
                botaoEnviar.textContent = 'Enviar Instruções';
                return;
            }

            setTimeout(function() {
                mensagemStatus.textContent = `Instruções enviadas para ${emailInput.value}.`;
                mensagemStatus.style.color = '#27ae60';
                emailInput.value = '';
                botaoEnviar.disabled = false;
                botaoEnviar.textContent = 'Enviar Instruções';
            }, 1500);
        });
    }
});

// ==========================
// CADASTRO DE DOADOR
// ==========================

const form = document.getElementById('formCadastroDonor');

if (form) {
    form.addEventListener('submit', function(event) {
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
        form.reset();
    });
}

// ==========================
// CADASTRO DE HEMOCENTRO
// ==========================

const formHemocentro = document.getElementById('formCadastroHemocentro');

if (formHemocentro) {
    formHemocentro.addEventListener('submit', function(event) {
        event.preventDefault();

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

        if (!nome || !cnpj || !telefone || !cep || !logradouro || !numero || !bairro || !cidade || !estado) {
            alert('Por favor, preencha os campos obrigatórios!');
            return;
        }

        if (cnpj.length < 14) {
            alert('CNPJ inválido!');
            return;
        }

        if (cep.length < 8) {
            alert('CEP inválido!');
            return;
        }

        const hemocentro = {
            id: Date.now(),
            nome, cnpj, telefone, cep, logradouro, numero, bairro,
            complemento, cidade, estado,
            dataCadastro: new Date().toLocaleDateString('pt-BR')
        };

        let hemocentros = JSON.parse(localStorage.getItem('hemocentros')) || [];
        hemocentros.push(hemocentro);
        localStorage.setItem('hemocentros', JSON.stringify(hemocentros));

        alert('Hemocentro cadastrado com sucesso!');
        formHemocentro.reset();
    });
}

// ==========================
// MAPA — SOMENTE SE EXISTIR O ELEMENTO #mapa
// ==========================

if (document.getElementById('mapa')) {

    var mapa = L.map('mapa').setView([-22.12, -51.39], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19
    }).addTo(mapa);

    let listaHemocentros = [];

    fetch("hemocentros.json")
        .then(r => r.json())
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
                L.marker([h.lat, h.lng])
                    .addTo(mapa)
                    .bindPopup("<b>" + h.nome + "</b><br>" + h.endereco);
            });
        });

    window.mostrarSelecionado = function() {
        let index = document.getElementById("listaHemocentros").value;
        if (index === "") return;

        let h = listaHemocentros[index];

        mapa.setView([h.lat, h.lng], 16);

        L.marker([h.lat, h.lng])
            .addTo(mapa)
            .bindPopup("<b>" + h.nome + "</b><br>" + h.endereco)
            .openPopup();
    };

    window.buscarEndereco = function() {
        let endereco = document.getElementById("endereco").value;

        if (endereco.trim() === "") {
            alert("Digite um endereço!");
            return;
        }

        let url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(endereco)}`;

        fetch(url)
            .then(r => r.json())
            .then(dados => {
                if (dados.length === 0) {
                    alert("Endereço não encontrado.");
                    return;
                }

                let lat = dados[0].lat;
                let lon = dados[0].lon;

                mapa.setView([lat, lon], 16);

                L.marker([lat, lon])
                    .addTo(mapa)
                    .bindPopup("Local encontrado:<br>" + endereco)
                    .openPopup();
            });
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
