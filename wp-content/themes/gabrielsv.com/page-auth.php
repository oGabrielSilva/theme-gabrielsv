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
    <div class="columns is-centered">
        <div class="column is-5-desktop is-6-tablet">
            <div class="box">
                <div class="p-4">

                    <?php if ($show_reset_form): ?>
                        <?php // Formulário de Redefinição de Senha ?>
                        <h1 class="title is-3 has-text-weight-bold mb-4 has-text-centered">Nova senha</h1>

                        <form id="reset-password-form" method="post" novalidate>
                            <input type="hidden" name="reset_key" value="<?php echo esc_attr($_GET['key']); ?>">
                            <input type="hidden" name="reset_login" value="<?php echo esc_attr($_GET['login']); ?>">

                            <div class="field mb-3">
                                <label class="label" for="reset-password">Nova senha</label>
                                <div class="control">
                                    <input type="password"
                                           class="input"
                                           id="reset-password"
                                           name="password"
                                           required
                                           minlength="8"
                                           autocomplete="new-password">
                                </div>
                                <p class="help">Mínimo de 8 caracteres</p>
                                <p class="help is-danger is-hidden">
                                    A senha deve ter no mínimo 8 caracteres.
                                </p>
                            </div>

                            <div class="field mb-3">
                                <label class="label" for="reset-password-confirm">Confirmar nova senha</label>
                                <div class="control">
                                    <input type="password"
                                           class="input"
                                           id="reset-password-confirm"
                                           name="password_confirm"
                                           required
                                           autocomplete="new-password">
                                </div>
                                <p class="help is-danger is-hidden">
                                    As senhas não coincidem.
                                </p>
                            </div>

                            <button type="submit" class="button is-primary is-fullwidth" id="reset-password-submit">
                                Redefinir senha
                            </button>
                        </form>

                    <?php elseif (!empty($reset_error)): ?>
                        <?php // Erro no link de recuperação ?>
                        <h1 class="title is-3 has-text-weight-bold mb-4 has-text-centered">Link inválido</h1>
                        <div class="notification is-danger" role="alert">
                            <?php echo esc_html($reset_error); ?>
                        </div>
                        <a href="<?php echo home_url('/auth'); ?>" class="button is-primary is-fullwidth">
                            Voltar ao login
                        </a>

                    <?php else: ?>
                        <?php // Formulário de Login ?>
                        <h1 class="title is-3 has-text-weight-bold mb-4 has-text-centered">Entrar</h1>

                        <div id="auth-error" class="notification is-danger is-hidden" role="alert"></div>

                        <?php // Formulário de Login ?>
                    <form id="auth-form" method="post" novalidate>
                        <div class="field mb-3">
                            <label class="label" for="auth-username">E-mail ou usuário</label>
                            <div class="control">
                                <input type="text"
                                       class="input"
                                       id="auth-username"
                                       name="username"
                                       required
                                       autocomplete="username"
                                       autofocus>
                            </div>
                            <p class="help is-danger is-hidden">
                                Por favor, informe seu e-mail ou usuário.
                            </p>
                        </div>

                        <div class="field mb-3">
                            <label class="label" for="auth-password">Senha</label>
                            <div class="control">
                                <input type="password"
                                       class="input"
                                       id="auth-password"
                                       name="password"
                                       required
                                       autocomplete="current-password">
                            </div>
                            <p class="help is-danger is-hidden">
                                Por favor, informe sua senha.
                            </p>
                        </div>

                        <div class="field mb-3">
                            <label class="checkbox">
                                <input type="checkbox"
                                       id="auth-remember"
                                       name="remember">
                                Lembrar de mim
                            </label>
                        </div>

                        <button type="submit" class="button is-primary is-fullwidth mb-3" id="auth-submit">
                            Entrar
                        </button>

                        <div class="has-text-centered">
                            <button type="button" class="button is-text is-small p-0" data-modal-open="forgotPasswordModal">
                                Esqueceu a senha?
                            </button>
                        </div>

                        <div class="has-text-centered mt-3">
                            <span class="has-text-grey is-size-7">Não tem uma conta?</span>
                            <button type="button" class="button is-text is-small p-0 ml-1" data-modal-open="registerModal">
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
<div class="modal" id="registerModal">
    <div class="modal-background" data-modal-close="registerModal"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Criar conta</p>
            <button class="delete" aria-label="close" data-modal-close="registerModal"></button>
        </header>
        <section class="modal-card-body">
            <div id="register-error" class="notification is-danger is-hidden" role="alert"></div>
            <div id="register-success" class="notification is-success is-hidden" role="alert"></div>

            <form id="register-form" method="post" novalidate>
                <div class="field mb-3">
                    <label class="label" for="register-username">Nome de usuário</label>
                    <div class="control">
                        <input type="text"
                               class="input"
                               id="register-username"
                               name="username"
                               required
                               pattern="[a-z0-9_-]{3,20}"
                               autocomplete="username"
                               placeholder="exemplo_123">
                    </div>
                    <p class="help">3-20 caracteres (minúsculas, números, _ e -). Formatado automaticamente.</p>
                    <p class="help is-danger is-hidden">
                        Nome de usuário inválido.
                    </p>
                </div>

                <div class="field mb-3">
                    <label class="label" for="register-email">E-mail</label>
                    <div class="control">
                        <input type="email"
                               class="input"
                               id="register-email"
                               name="email"
                               required
                               autocomplete="email">
                    </div>
                    <p class="help is-danger is-hidden">
                        Por favor, informe um e-mail válido.
                    </p>
                </div>

                <div class="field mb-3">
                    <label class="label" for="register-password">Senha</label>
                    <div class="control">
                        <input type="password"
                               class="input"
                               id="register-password"
                               name="password"
                               required
                               minlength="8"
                               autocomplete="new-password">
                    </div>
                    <p class="help">Mínimo de 8 caracteres</p>
                    <p class="help is-danger is-hidden">
                        A senha deve ter no mínimo 8 caracteres.
                    </p>
                </div>

                <div class="field mb-3">
                    <label class="label" for="register-password-confirm">Confirmar senha</label>
                    <div class="control">
                        <input type="password"
                               class="input"
                               id="register-password-confirm"
                               name="password_confirm"
                               required
                               autocomplete="new-password">
                    </div>
                    <p class="help is-danger is-hidden">
                        As senhas não coincidem.
                    </p>
                </div>

                <button type="submit" class="button is-primary is-fullwidth" id="register-submit">
                    Criar conta
                </button>
            </form>
        </section>
    </div>
</div>

<?php // Modal de Recuperação de Senha ?>
<div class="modal" id="forgotPasswordModal">
    <div class="modal-background" data-modal-close="forgotPasswordModal"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Recuperar senha</p>
            <button class="delete" aria-label="close" data-modal-close="forgotPasswordModal"></button>
        </header>
        <section class="modal-card-body">
            <p class="has-text-grey is-size-7 mb-3">Digite seu e-mail para receber um link de recuperação de senha.</p>

            <form id="forgot-password-form" method="post" novalidate>
                <div class="field mb-3">
                    <label class="label" for="forgot-password-email">E-mail</label>
                    <div class="control">
                        <input type="email"
                               class="input"
                               id="forgot-password-email"
                               name="email"
                               required
                               autocomplete="email">
                    </div>
                    <p class="help is-danger is-hidden">
                        Por favor, informe um e-mail válido.
                    </p>
                </div>

                <button type="submit" class="button is-primary is-fullwidth" id="forgot-password-submit">
                    Enviar link de recuperação
                </button>
            </form>
        </section>
    </div>
</div>

<?php get_footer(); ?>
