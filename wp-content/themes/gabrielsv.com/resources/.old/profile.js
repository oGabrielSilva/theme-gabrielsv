document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('profile-form');
    const successDiv = document.getElementById('profile-success');
    const errorDiv = document.getElementById('profile-error');
    const submitBtn = document.getElementById('profile-submit');
    const passwordField = document.getElementById('profile-password');
    const passwordConfirmField = document.getElementById('profile-password-confirm');

    if (!form) return;

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Limpar mensagens anteriores
        successDiv.classList.add('d-none');
        errorDiv.classList.add('d-none');
        form.classList.remove('was-validated');

        // Validar senhas se preenchidas
        if (passwordField.value || passwordConfirmField.value) {
            if (passwordField.value !== passwordConfirmField.value) {
                passwordConfirmField.setCustomValidity('As senhas não coincidem.');
                form.classList.add('was-validated');
                return;
            } else {
                passwordConfirmField.setCustomValidity('');
            }
        }

        // Validação HTML5
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        // Obter valores
        const formData = new FormData();
        formData.append('action', 'update_profile');
        formData.append('nonce', profileData.nonce);
        formData.append('first_name', document.getElementById('profile-first-name').value);
        formData.append('last_name', document.getElementById('profile-last-name').value);
        formData.append('email', document.getElementById('profile-email').value);
        formData.append('bio', document.getElementById('profile-bio').value);
        formData.append('url', document.getElementById('profile-url').value);
        formData.append('twitter', document.getElementById('profile-twitter').value);
        formData.append('linkedin', document.getElementById('profile-linkedin').value);
        formData.append('github', document.getElementById('profile-github').value);

        if (passwordField.value) {
            formData.append('password', passwordField.value);
        }

        // Desabilitar botão e mostrar loading
        submitBtn.disabled = true;
        submitBtn.textContent = 'Salvando...';

        try {
            // Enviar requisição
            const response = await fetch(profileData.ajaxUrl, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            });

            const data = await response.json();

            if (data.success) {
                // Sucesso
                successDiv.textContent = data.data.message || 'Perfil atualizado com sucesso!';
                successDiv.classList.remove('d-none');

                // Limpar campos de senha
                passwordField.value = '';
                passwordConfirmField.value = '';

                // Rolar para o topo
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                // Erro
                errorDiv.textContent = data.data.message || 'Erro ao atualizar perfil. Tente novamente.';
                errorDiv.classList.remove('d-none');
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        } catch (error) {
            // Erro de rede
            errorDiv.textContent = 'Erro de conexão. Tente novamente.';
            errorDiv.classList.remove('d-none');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Salvar alterações';
        }
    });
});
