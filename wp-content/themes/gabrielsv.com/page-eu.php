<?php
/**
 * Template Name: Meu Perfil
 * Template para a página /eu
 *
 * ❌ DESABILITADO: Perfis públicos removidos
 */

// Redirecionar para home (sistema desabilitado)
wp_redirect(home_url());
exit;

// ============================================
// CÓDIGO ORIGINAL DESABILITADO (mantido para referência)
// ============================================
/*
// Redirecionar se não estiver logado
if (!is_user_logged_in()) {
    wp_redirect(home_url('/auth'));
    exit;
}

$current_user = wp_get_current_user();
$user_id = $current_user->ID;

get_header();
?>

<main class="container py-5">
    <div class="columns is-centered">
        <div class="column is-8-desktop">
            <h1 class="title is-3 has-text-weight-bold mb-4">Meu Perfil</h1>

            <div id="profile-success" class="notification is-success is-hidden" role="alert"></div>
            <div id="profile-error" class="notification is-danger is-hidden" role="alert"></div>

            <div class="box">
                <div class="p-4">
                    <form id="profile-form" novalidate>
                        <?php // Avatar ?>
                        <div class="has-text-centered mb-4">
                            <figure class="image is-96x96 mx-auto">
                                <?php echo get_avatar($user_id, 96, '', '', array('class' => 'is-rounded')); ?>
                            </figure>
                            <p class="is-size-7 mt-2">
                                O avatar é gerenciado pelo <a href="https://gravatar.com" target="_blank"
                                    rel="noopener">Gravatar</a>
                            </p>
                        </div>

                        <?php // Nome de usuário (somente leitura) ?>
                        <div class="field mb-3">
                            <label class="label" for="profile-username">Nome de usuário</label>
                            <div class="control">
                                <input type="text" class="input" id="profile-username"
                                    value="<?php echo esc_attr($current_user->user_login); ?>" readonly disabled>
                            </div>
                            <p class="help">O nome de usuário não pode ser alterado.</p>
                        </div>

                        <?php // Nome ?>
                        <div class="field mb-3">
                            <label class="label" for="profile-first-name">Nome</label>
                            <div class="control">
                                <input type="text" class="input" id="profile-first-name" name="first_name"
                                    value="<?php echo esc_attr($current_user->first_name); ?>">
                            </div>
                        </div>

                        <?php // Sobrenome ?>
                        <div class="field mb-3">
                            <label class="label" for="profile-last-name">Sobrenome</label>
                            <div class="control">
                                <input type="text" class="input" id="profile-last-name" name="last_name"
                                    value="<?php echo esc_attr($current_user->last_name); ?>">
                            </div>
                        </div>

                        <?php // E-mail ?>
                        <div class="field mb-3">
                            <label class="label" for="profile-email">E-mail</label>
                            <div class="control">
                                <input type="email" class="input" id="profile-email" name="email"
                                    value="<?php echo esc_attr($current_user->user_email); ?>" required>
                            </div>
                            <p class="help is-danger is-hidden">
                                Por favor, informe um e-mail válido.
                            </p>
                        </div>

                        <?php // Bio ?>
                        <div class="field mb-3">
                            <label class="label" for="profile-bio">Biografia</label>
                            <div class="control">
                                <textarea class="textarea" id="profile-bio" name="bio"
                                    rows="4"><?php echo esc_textarea($current_user->description); ?></textarea>
                            </div>
                        </div>

                        <?php // URL ?>
                        <div class="field mb-3">
                            <label class="label" for="profile-url">Site</label>
                            <div class="control">
                                <input type="url" class="input" id="profile-url" name="url"
                                    value="<?php echo esc_url($current_user->user_url); ?>" placeholder="https://">
                            </div>
                        </div>

                        <?php // Twitter/X ?>
                        <div class="field mb-3">
                            <label class="label" for="profile-twitter">Twitter/X</label>
                            <div class="control">
                                <input type="text" class="input" id="profile-twitter" name="twitter"
                                    value="<?php echo esc_attr(get_user_meta($user_id, 'twitter', true)); ?>"
                                    placeholder="@usuario">
                            </div>
                        </div>

                        <?php // LinkedIn ?>
                        <div class="field mb-3">
                            <label class="label" for="profile-linkedin">LinkedIn</label>
                            <div class="control">
                                <input type="url" class="input" id="profile-linkedin" name="linkedin"
                                    value="<?php echo esc_url(get_user_meta($user_id, 'linkedin', true)); ?>"
                                    placeholder="https://linkedin.com/in/usuario">
                            </div>
                        </div>

                        <?php // GitHub ?>
                        <div class="field mb-3">
                            <label class="label" for="profile-github">GitHub</label>
                            <div class="control">
                                <input type="url" class="input" id="profile-github" name="github"
                                    value="<?php echo esc_url(get_user_meta($user_id, 'github', true)); ?>"
                                    placeholder="https://github.com/usuario">
                            </div>
                        </div>

                        <hr class="my-4">

                        <?php // Alterar senha ?>
                        <h2 class="title is-5 has-text-weight-bold mb-3">Alterar senha</h2>
                        <p class=" is-size-7 mb-3">Deixe em branco se não quiser alterar.</p>

                        <div class="field mb-3">
                            <label class="label" for="profile-password">Nova senha</label>
                            <div class="control">
                                <input type="password" class="input" id="profile-password" name="password"
                                    autocomplete="new-password">
                            </div>
                        </div>

                        <div class="field mb-4">
                            <label class="label" for="profile-password-confirm">Confirmar nova senha</label>
                            <div class="control">
                                <input type="password" class="input" id="profile-password-confirm"
                                    name="password_confirm" autocomplete="new-password">
                            </div>
                            <p class="help is-danger is-hidden">
                                As senhas não coincidem.
                            </p>
                        </div>

                        <button type="submit" class="button is-primary" id="profile-submit">
                            Salvar alterações
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>
*/