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
