<?php
/**
 * Template Name: Autenticação
 * Template para a página /auth
 */

// Redirecionar se já estiver logado (exceto se estiver resetando senha)
$is_reset_page = isset($_GET['action']) && $_GET['action'] === 'rp';

if (is_user_logged_in() && !$is_reset_page) {
    wp_redirect(home_url());
    exit;
}

// Verificar se é página de reset de senha
$show_reset_form = false;
$reset_error = '';
$reset_user = null;

if ($is_reset_page) {
    $reset_key = isset($_GET['key']) ? $_GET['key'] : '';
    $reset_login = isset($_GET['login']) ? $_GET['login'] : '';

    if (empty($reset_key) || empty($reset_login)) {
        $reset_error = 'Link de recuperação inválido.';
    } else {
        $reset_user = check_password_reset_key($reset_key, $reset_login);

        if (is_wp_error($reset_user)) {
            if ($reset_user->get_error_code() === 'expired_key') {
                $reset_error = 'Este link expirou. Solicite um novo link de recuperação.';
            } else {
                $reset_error = 'Link de recuperação inválido.';
            }
        } else {
            $show_reset_form = true;
        }
    }
}

get_header();
?>

<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">

                    <?php if ($show_reset_form): ?>
                        <?php // Formulário de Redefinição de Senha ?>
                        <h1 class="h3 fw-bold mb-4 text-center">Nova senha</h1>

                        <form id="reset-password-form" method="post" novalidate>
                            <input type="hidden" name="reset_key" value="<?php echo esc_attr($_GET['key']); ?>">
                            <input type="hidden" name="reset_login" value="<?php echo esc_attr($_GET['login']); ?>">

                            <div class="mb-3">
                                <label for="reset-password" class="form-label">Nova senha</label>
                                <input type="password"
                                       class="form-control"
                                       id="reset-password"
                                       name="password"
                                       required
                                       minlength="8"
                                       autocomplete="new-password">
                                <div class="form-text">Mínimo de 8 caracteres</div>
                                <div class="invalid-feedback">
                                    A senha deve ter no mínimo 8 caracteres.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="reset-password-confirm" class="form-label">Confirmar nova senha</label>
                                <input type="password"
                                       class="form-control"
                                       id="reset-password-confirm"
                                       name="password_confirm"
                                       required
                                       autocomplete="new-password">
                                <div class="invalid-feedback">
                                    As senhas não coincidem.
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100" id="reset-password-submit">
                                Redefinir senha
                            </button>
                        </form>

                    <?php elseif (!empty($reset_error)): ?>
                        <?php // Erro no link de recuperação ?>
                        <h1 class="h3 fw-bold mb-4 text-center">Link inválido</h1>
                        <div class="alert alert-danger" role="alert">
                            <?php echo esc_html($reset_error); ?>
                        </div>
                        <a href="<?php echo home_url('/auth'); ?>" class="btn btn-primary w-100">
                            Voltar ao login
                        </a>

                    <?php else: ?>
                        <?php // Formulário de Login ?>
                        <h1 class="h3 fw-bold mb-4 text-center">Entrar</h1>

                        <div id="auth-error" class="alert alert-danger d-none" role="alert"></div>

                        <?php // Formulário de Login ?>
                    <form id="auth-form" method="post" novalidate>
                        <div class="mb-3">
                            <label for="auth-username" class="form-label">E-mail ou usuário</label>
                            <input type="text"
                                   class="form-control"
                                   id="auth-username"
                                   name="username"
                                   required
                                   autocomplete="username"
                                   autofocus>
                            <div class="invalid-feedback">
                                Por favor, informe seu e-mail ou usuário.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="auth-password" class="form-label">Senha</label>
                            <input type="password"
                                   class="form-control"
                                   id="auth-password"
                                   name="password"
                                   required
                                   autocomplete="current-password">
                            <div class="invalid-feedback">
                                Por favor, informe sua senha.
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox"
                                   class="form-check-input"
                                   id="auth-remember"
                                   name="remember">
                            <label class="form-check-label" for="auth-remember">
                                Lembrar de mim
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3" id="auth-submit">
                            Entrar
                        </button>

                        <div class="text-center">
                            <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">
                                Esqueceu a senha?
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <span class="text-muted small">Não tem uma conta?</span>
                            <button type="button" class="btn btn-link btn-sm p-0 ms-1" data-bs-toggle="modal" data-bs-target="#registerModal">
                                Criar conta
                            </button>
                        </div>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php // Modal de Registro ?>
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerModalLabel">Criar conta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div id="register-error" class="alert alert-danger d-none" role="alert"></div>
                <div id="register-success" class="alert alert-success d-none" role="alert"></div>

                <form id="register-form" method="post" novalidate>
                    <div class="mb-3">
                        <label for="register-username" class="form-label">Nome de usuário</label>
                        <input type="text"
                               class="form-control"
                               id="register-username"
                               name="username"
                               required
                               pattern="[a-z0-9_-]{3,20}"
                               autocomplete="username"
                               placeholder="exemplo_123">
                        <div class="form-text">3-20 caracteres (minúsculas, números, _ e -). Formatado automaticamente.</div>
                        <div class="invalid-feedback">
                            Nome de usuário inválido.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="register-email" class="form-label">E-mail</label>
                        <input type="email"
                               class="form-control"
                               id="register-email"
                               name="email"
                               required
                               autocomplete="email">
                        <div class="invalid-feedback">
                            Por favor, informe um e-mail válido.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="register-password" class="form-label">Senha</label>
                        <input type="password"
                               class="form-control"
                               id="register-password"
                               name="password"
                               required
                               minlength="8"
                               autocomplete="new-password">
                        <div class="form-text">Mínimo de 8 caracteres</div>
                        <div class="invalid-feedback">
                            A senha deve ter no mínimo 8 caracteres.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="register-password-confirm" class="form-label">Confirmar senha</label>
                        <input type="password"
                               class="form-control"
                               id="register-password-confirm"
                               name="password_confirm"
                               required
                               autocomplete="new-password">
                        <div class="invalid-feedback">
                            As senhas não coincidem.
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100" id="register-submit">
                        Criar conta
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php // Modal de Recuperação de Senha ?>
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="forgotPasswordModalLabel">Recuperar senha</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">Digite seu e-mail para receber um link de recuperação de senha.</p>

                <form id="forgot-password-form" method="post" novalidate>
                    <div class="mb-3">
                        <label for="forgot-password-email" class="form-label">E-mail</label>
                        <input type="email"
                               class="form-control"
                               id="forgot-password-email"
                               name="email"
                               required
                               autocomplete="email">
                        <div class="invalid-feedback">
                            Por favor, informe um e-mail válido.
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100" id="forgot-password-submit">
                        Enviar link de recuperação
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
