<?php
/**
 * ❌ DESABILITADO: Só exibir dropdown para admins (não para visitantes)
 */
if (!current_user_can('manage_options')) {
    return;
}

if (!is_user_logged_in()) {
    // Usuário deslogado - mostrar ícone de login (exceto na própria página de login)
    if (!is_page('auth')) {
        ?>
        <a href="<?php echo home_url('/auth'); ?>" class="button is-small" aria-label="Entrar">
            <span class="icon">
                <?php get_template_part('template-parts/icons/log-in'); ?>
            </span>
        </a>
        <?php
    }
} else {
    // Usuário logado - mostrar dropdown com avatar
    $current_user = wp_get_current_user();
    $can_access_admin = theme_user_can_access_admin($current_user->ID);
    ?>
    <div class="dropdown is-right is-hoverable">
        <div class="dropdown-trigger">
            <button class="button is-small p-1" aria-haspopup="true" aria-controls="userDropdown" aria-label="Menu do usuário">
                <figure class="image is-24x24">
                    <?php echo get_avatar($current_user->ID, 24, '', '', array('class' => 'is-rounded')); ?>
                </figure>
            </button>
        </div>
        <div class="dropdown-menu" id="userDropdown" role="menu">
            <div class="dropdown-content">
                <?php if ($can_access_admin): ?>
                    <a class="dropdown-item is-flex is-align-items-center" style="gap: 0.5rem;" href="<?php echo admin_url(); ?>">
                        <span class="icon is-small">
                            <?php get_template_part('template-parts/icons/chart'); ?>
                        </span>
                        <span>Painel Administrativo</span>
                    </a>
                    <hr class="dropdown-divider">
                <?php endif; ?>
                <a class="dropdown-item is-flex is-align-items-center" style="gap: 0.5rem;" href="<?php echo wp_logout_url(home_url()); ?>">
                    <span class="icon is-small">
                        <?php get_template_part('template-parts/icons/log-out'); ?>
                    </span>
                    <span>Sair</span>
                </a>
            </div>
        </div>
    </div>
    <?php
}
?>