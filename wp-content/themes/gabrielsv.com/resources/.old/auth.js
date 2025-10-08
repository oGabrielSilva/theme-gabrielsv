document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('auth-form');
    const registerForm = document.getElementById('register-form');
    const loginErrorDiv = document.getElementById('auth-error');
    const registerErrorDiv = document.getElementById('register-error');
    const registerSuccessDiv = document.getElementById('register-success');
    const loginSubmitBtn = document.getElementById('auth-submit');
    const registerSubmitBtn = document.getElementById('register-submit');

    if (!loginForm || !registerForm) {
        return;
    }

    // Formatar automaticamente o username (apenas letras, números, _ e -)
    const usernameInput = document.getElementById('register-username');
    if (usernameInput) {
        usernameInput.addEventListener('input', function(e) {
            // Remove caracteres não permitidos e converte para minúsculas
            let value = e.target.value;
            value = value.toLowerCase(); // Converter para minúsculas
            value = value.replace(/[^a-z0-9_-]/g, ''); // Apenas a-z, 0-9, _ e -
            value = value.slice(0, 20); // Máximo 20 caracteres
            e.target.value = value;
        });
    }

    // Submit do login
    loginForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Validação HTML5
        if (!loginForm.checkValidity()) {
            loginForm.classList.add('was-validated');
            return;
        }

        // Obter valores
        const username = document.getElementById('auth-username').value;
        const password = document.getElementById('auth-password').value;
        const remember = document.getElementById('auth-remember').checked;

        // Desabilitar botão e mostrar loading
        loginSubmitBtn.disabled = true;
        loginSubmitBtn.textContent = 'Entrando...';
        loginErrorDiv.classList.add('d-none');

        try {
            // Preparar FormData
            const formData = new FormData();
            formData.append('action', 'custom_login');
            formData.append('username', username);
            formData.append('password', password);
            formData.append('remember', remember ? '1' : '0');
            formData.append('nonce', authData.nonce);

            // Enviar requisição
            const response = await fetch(authData.ajaxUrl, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            });

            const data = await response.json();

            if (data.success) {
                // Sucesso - redirecionar
                window.location.href = data.data.redirect || '/';
            } else {
                // Erro - mostrar mensagem
                loginErrorDiv.textContent = data.data.message || 'Erro ao fazer login. Tente novamente.';
                loginErrorDiv.classList.remove('d-none');
                loginSubmitBtn.disabled = false;
                loginSubmitBtn.textContent = 'Entrar';
            }
        } catch (error) {
            // Erro de rede
            loginErrorDiv.textContent = 'Erro de conexão. Tente novamente.';
            loginErrorDiv.classList.remove('d-none');
            loginSubmitBtn.disabled = false;
            loginSubmitBtn.textContent = 'Entrar';
        }
    });

    // Submit do registro
    registerForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Validar senhas
        const password = document.getElementById('register-password').value;
        const passwordConfirm = document.getElementById('register-password-confirm').value;

        if (password !== passwordConfirm) {
            document.getElementById('register-password-confirm').setCustomValidity('As senhas não coincidem.');
            registerForm.classList.add('was-validated');
            return;
        } else {
            document.getElementById('register-password-confirm').setCustomValidity('');
        }

        // Validação HTML5
        if (!registerForm.checkValidity()) {
            registerForm.classList.add('was-validated');
            return;
        }

        // Obter valores
        const username = document.getElementById('register-username').value;
        const email = document.getElementById('register-email').value;

        // Desabilitar botão e mostrar loading
        registerSubmitBtn.disabled = true;
        registerSubmitBtn.textContent = 'Criando conta...';
        registerErrorDiv.classList.add('d-none');
        registerSuccessDiv.classList.add('d-none');

        try {
            // Preparar FormData
            const formData = new FormData();
            formData.append('action', 'custom_register');
            formData.append('username', username);
            formData.append('email', email);
            formData.append('password', password);
            formData.append('nonce', authData.nonce);

            // Enviar requisição
            const response = await fetch(authData.ajaxUrl, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            });

            const data = await response.json();

            if (data.success) {
                // Sucesso - mostrar mensagem e fazer login automático
                registerSuccessDiv.textContent = data.data.message || 'Conta criada com sucesso! Redirecionando...';
                registerSuccessDiv.classList.remove('d-none');

                // Redirecionar após 2 segundos
                setTimeout(function() {
                    window.location.href = data.data.redirect || '/';
                }, 2000);
            } else {
                // Erro - mostrar mensagem
                registerErrorDiv.textContent = data.data.message || 'Erro ao criar conta. Tente novamente.';
                registerErrorDiv.classList.remove('d-none');
                registerSubmitBtn.disabled = false;
                registerSubmitBtn.textContent = 'Criar conta';
            }
        } catch (error) {
            // Erro de rede
            registerErrorDiv.textContent = 'Erro de conexão. Tente novamente.';
            registerErrorDiv.classList.remove('d-none');
            registerSubmitBtn.disabled = false;
            registerSubmitBtn.textContent = 'Criar conta';
        }
    });

    // Limpar formulário quando o modal for fechado
    const registerModal = document.getElementById('registerModal');
    if (registerModal) {
        registerModal.addEventListener('hidden.bs.modal', function() {
            registerForm.reset();
            registerForm.classList.remove('was-validated');
            registerErrorDiv.classList.add('d-none');
            registerSuccessDiv.classList.add('d-none');
            registerSubmitBtn.disabled = false;
            registerSubmitBtn.textContent = 'Criar conta';
        });
    }
});
