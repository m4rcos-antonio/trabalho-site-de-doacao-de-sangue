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
const form = document.getElementById('registerForm');

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
           
            document.querySelectorAll('.error-message').forEach(el => {
                el.classList.remove('show');
                el.textContent = '';
            });
            
            document.querySelectorAll('input').forEach(el => {
                el.classList.remove('error');
            });

            
            const fullName = document.getElementById('fullName').value.trim();
            const cpf = document.getElementById('cpf').value.trim();
            const birthDate = document.getElementById('birthDate').value;
            const phone = document.getElementById('phone').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            let isValid = true;

            
            if (!fullName) {
                showError('fullNameError', 'Nome completo é obrigatório');
                isValid = false;
            }

      
            if (!cpf) {
                showError('cpfError', 'CPF é obrigatório');
                isValid = false;
            } else if (!/^\d{3}\.\d{3}\.\d{3}-\d{2}$/.test(cpf)) {
                showError('cpfError', 'CPF deve estar no formato XXX.XXX.XXX-XX');
                isValid = false;
            }

           
            if (!birthDate) {
                showError('birthDateError', 'Data de nascimento é obrigatória');
                isValid = false;
            }

       
            if (!phone) {
                showError('phoneError', 'Telefone é obrigatório');
                isValid = false;
            } else if (!/^\(\d{2}\) \d{4,5}-\d{4}$/.test(phone)) {
                showError('phoneError', 'Telefone deve estar no formato (XX) XXXXX-XXXX');
                isValid = false;
            }

        
            if (!email) {
                showError('emailError', 'E-mail é obrigatório');
                isValid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showError('emailError', 'E-mail inválido');
                isValid = false;
            }

       
            if (!password) {
                showError('passwordError', 'Senha é obrigatória');
                isValid = false;
            } else if (password.length < 6) {
                showError('passwordError', 'Senha deve ter pelo menos 6 caracteres');
                isValid = false;
            }

           
            if (!confirmPassword) {
                showError('confirmPasswordError', 'Confirmação de senha é obrigatória');
                isValid = false;
            } else if (password !== confirmPassword) {
                showError('confirmPasswordError', 'Senhas não coincidem');
                isValid = false;
            }

            if (isValid) {
         
                console.log('Cadastro:', {
                    fullName,
                    cpf,
                    birthDate,
                    phone,
                    email,
                    password
                });
                alert('Cadastro realizado com sucesso!');
                form.reset();
               
            }
        });

       
        function showError(elementId, message) {
            const errorElement = document.getElementById(elementId);
            const inputId = elementId.replace('Error', '');
            const inputElement = document.getElementById(inputId);
            
            errorElement.textContent = message;
            errorElement.classList.add('show');
            inputElement.classList.add('error');
        }

     
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', function() {
                const errorId = this.id + 'Error';
                const errorElement = document.getElementById(errorId);
                if (errorElement) {
                    errorElement.classList.remove('show');
                    errorElement.textContent = '';
                }
                this.classList.remove('error');
            });
        });