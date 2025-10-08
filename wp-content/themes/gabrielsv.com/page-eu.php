<?php
/**
 * Template Name: Meu Perfil
 * Template para a página /eu
 */

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
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="h3 fw-bold mb-4">Meu Perfil</h1>

            <div id="profile-success" class="alert alert-success d-none" role="alert"></div>
            <div id="profile-error" class="alert alert-danger d-none" role="alert"></div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form id="profile-form" novalidate>
                        <?php // Avatar ?>
                        <div class="text-center mb-4">
                            <?php echo get_avatar($user_id, 96, '', '', array('class' => 'rounded-circle')); ?>
                            <p class="small text-muted mt-2">
                                O avatar é gerenciado pelo <a href="https://gravatar.com" target="_blank" rel="noopener">Gravatar</a>
                            </p>
                        </div>

                        <?php // Nome de usuário (somente leitura) ?>
                        <div class="mb-3">
                            <label for="profile-username" class="form-label">Nome de usuário</label>
                            <input type="text"
                                   class="form-control"
                                   id="profile-username"
                                   value="<?php echo esc_attr($current_user->user_login); ?>"
                                   readonly
                                   disabled>
                            <div class="form-text">O nome de usuário não pode ser alterado.</div>
                        </div>

                        <?php // Nome ?>
                        <div class="mb-3">
                            <label for="profile-first-name" class="form-label">Nome</label>
                            <input type="text"
                                   class="form-control"
                                   id="profile-first-name"
                                   name="first_name"
                                   value="<?php echo esc_attr($current_user->first_name); ?>">
                        </div>

                        <?php // Sobrenome ?>
                        <div class="mb-3">
                            <label for="profile-last-name" class="form-label">Sobrenome</label>
                            <input type="text"
                                   class="form-control"
                                   id="profile-last-name"
                                   name="last_name"
                                   value="<?php echo esc_attr($current_user->last_name); ?>">
                        </div>

                        <?php // E-mail ?>
                        <div class="mb-3">
                            <label for="profile-email" class="form-label">E-mail</label>
                            <input type="email"
                                   class="form-control"
                                   id="profile-email"
                                   name="email"
                                   value="<?php echo esc_attr($current_user->user_email); ?>"
                                   required>
                            <div class="invalid-feedback">
                                Por favor, informe um e-mail válido.
                            </div>
                        </div>

                        <?php // Bio ?>
                        <div class="mb-3">
                            <label for="profile-bio" class="form-label">Biografia</label>
                            <textarea class="form-control"
                                      id="profile-bio"
                                      name="bio"
                                      rows="4"><?php echo esc_textarea($current_user->description); ?></textarea>
                        </div>

                        <?php // URL ?>
                        <div class="mb-3">
                            <label for="profile-url" class="form-label">Site</label>
                            <input type="url"
                                   class="form-control"
                                   id="profile-url"
                                   name="url"
                                   value="<?php echo esc_url($current_user->user_url); ?>"
                                   placeholder="https://">
                        </div>

                        <?php // Twitter/X ?>
                        <div class="mb-3">
                            <label for="profile-twitter" class="form-label">Twitter/X</label>
                            <input type="text"
                                   class="form-control"
                                   id="profile-twitter"
                                   name="twitter"
                                   value="<?php echo esc_attr(get_user_meta($user_id, 'twitter', true)); ?>"
                                   placeholder="@usuario">
                        </div>

                        <?php // LinkedIn ?>
                        <div class="mb-3">
                            <label for="profile-linkedin" class="form-label">LinkedIn</label>
                            <input type="url"
                                   class="form-control"
                                   id="profile-linkedin"
                                   name="linkedin"
                                   value="<?php echo esc_url(get_user_meta($user_id, 'linkedin', true)); ?>"
                                   placeholder="https://linkedin.com/in/usuario">
                        </div>

                        <?php // GitHub ?>
                        <div class="mb-3">
                            <label for="profile-github" class="form-label">GitHub</label>
                            <input type="url"
                                   class="form-control"
                                   id="profile-github"
                                   name="github"
                                   value="<?php echo esc_url(get_user_meta($user_id, 'github', true)); ?>"
                                   placeholder="https://github.com/usuario">
                        </div>

                        <hr class="my-4">

                        <?php // Alterar senha ?>
                        <h2 class="h5 fw-bold mb-3">Alterar senha</h2>
                        <p class="text-muted small mb-3">Deixe em branco se não quiser alterar.</p>

                        <div class="mb-3">
                            <label for="profile-password" class="form-label">Nova senha</label>
                            <input type="password"
                                   class="form-control"
                                   id="profile-password"
                                   name="password"
                                   autocomplete="new-password">
                        </div>

                        <div class="mb-4">
                            <label for="profile-password-confirm" class="form-label">Confirmar nova senha</label>
                            <input type="password"
                                   class="form-control"
                                   id="profile-password-confirm"
                                   name="password_confirm"
                                   autocomplete="new-password">
                            <div class="invalid-feedback">
                                As senhas não coincidem.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" id="profile-submit">
                            Salvar alterações
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>
