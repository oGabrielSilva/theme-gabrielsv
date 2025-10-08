<?php
if (!is_user_logged_in()) {
    // Usuário deslogado - mostrar ícone de login (exceto na própria página de login)
    if (!is_page('auth')) {
        ?>
        <a href="<?php echo home_url('/auth'); ?>" class="btn btn-sm btn-theme" aria-label="Entrar">
            <span>
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
    <div class="dropdown">
        <button class="btn btn-sm btn-theme dropdown-toggle p-1" type="button" id="userDropdown" data-bs-toggle="dropdown"
            aria-expanded="false" aria-label="Menu do usuário">
            <?php echo get_avatar($current_user->ID, 24, '', '', array('class' => 'rounded-circle')); ?>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li>
                <a class="dropdown-item d-flex align-items-center gap-2" href="<?php echo home_url('/eu'); ?>">
                    <span>
                        <?php get_template_part('template-parts/icons/user'); ?>
                    </span>
                    Meu Perfil
                </a>
            </li>
            <?php if ($can_access_admin): ?>
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2" href="<?php echo admin_url(); ?>">
                        <span>
                            <?php get_template_part('template-parts/icons/chart'); ?>
                        </span>
                        Painel Administrativo
                    </a>
                </li>
            <?php endif; ?>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li>
                <a class="dropdown-item d-flex align-items-center gap-2" href="<?php echo wp_logout_url(home_url()); ?>">
                    <span>
                        <?php get_template_part('template-parts/icons/log-out'); ?>
                    </span>
                    Sair
                </a>
            </li>
        </ul>
    </div>
    <?php
}
?>