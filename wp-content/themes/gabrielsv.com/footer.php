</div>
<hr>
<footer class="has-text-centered py-4">
    <div class="container">
        <div class="is-flex is-flex-direction-column is-justify-content-center is-align-items-center"
            style="gap: 1.5rem;">
            <div class="is-hidden">
                <div class="column is-3-tablet is-2-desktop pb-4">
                    <nav aria-label="Navegação por categorias">
                        <h6 class="mb-3 has-text-weight-bold">Acesse</h6>
                        <ul class="is-size-7">
                            <?php
                            $categories = get_categories(array(
                                'orderby' => 'name',
                                'order' => 'ASC',
                                'hide_empty' => true,
                            ));

                            if (!empty($categories)):
                                foreach ($categories as $category):
                                    ?>
                                    <li class="mb-2">
                                        <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>"
                                            class="text-decoration-none linkb">
                                            <?php echo esc_html(theme_get_category_name($category)); ?>
                                        </a>
                                    </li>
                                <?php endforeach;
                            endif; ?>
                        </ul>
                    </nav>
                </div>
            </div>

            <div>
                <div class="is-flex is-flex-direction-column-mobile is-flex-direction-row-tablet is-align-items-center-mobile is-align-items-flex-start-tablet"
                    style="gap: 1.5rem;">
                    <div>
                        <h5 class="kb title m-0 p-0" style="font-size: 5rem">G</h5>
                        <h5 class="mb-1 has-text-weight-bold is-size-6">oTech</h5>
                        <p class="mb-0 is-size-7">Transformando rabiscos em ideias, ideias em código.</p>

                        <div class="py-2">
                            <nav aria-label="">
                                <?php get_template_part('template-parts/ui/social-list') ?>
                            </nav>
                        </div>

                        <div>
                            <a href="<?php echo esc_url(home_url('/sobre')); ?>"
                                class="is-size-7 has-text-weight-medium" style="text-decoration: none;"
                                aria-label="Sobre o autor">
                                Sobre
                            </a>
                            |
                            <a href="<?php echo esc_url(home_url('/contato')); ?>"
                                class="is-size-7 has-text-weight-medium" style="text-decoration: none;"
                                aria-label="Entre em contato">
                                Contato
                            </a>
                            |
                            <a href="https://gabrielsv.com" target="_blank" rel="noopener noreferrer"
                                class="is-size-7 has-text-weight-medium" style="text-decoration: none;"
                                aria-label="Visitar site principal de Gabriel Silva">
                                Site
                            </a>
                            |
                            <a href="https://github.com/oGabrielSilva" target="_blank" rel="noopener noreferrer"
                                class="is-size-7 has-text-weight-medium" style="text-decoration: none;"
                                aria-label="Visitar GitHub de Gabriel Silva">
                                GitHub
                            </a>
                            |
                            <a href="<?php echo esc_url(get_bloginfo('rss2_url')); ?>"
                                class="is-size-7 has-text-weight-medium" style="text-decoration: none;"
                                aria-label="Feed RSS do blog">
                                RSS
                            </a>
                        </div>
                    </div>

                </div>
                <div class="pt-2">
                    <p class="m-0 p-0">
                        <small>
                            © <?php echo date('Y'); ?> Gabriel Henrique da Silva
                        </small>
                    </p>
                    <a href="<?php echo esc_url(home_url('/politica-de-privacidade')); ?>"
                        class="linkb me-3 is-size-7">Privacidade</a>
                    <a href="<?php echo esc_url(home_url('/politica-de-cookies')); ?>"
                        class="linkb me-3 is-size-7">Cookies</a>
                    <a href="<?php echo esc_url(home_url('/termos-de-uso')); ?>" class="linkb is-size-7">Termos</a>
                </div>
            </div>
        </div>
    </div>
</footer>
</div>

<?php // Global Notification Container (Bulma) ?>
<div class="notification-container"></div>

<?php get_template_part('template-parts/cookie-notice'); ?>

<?php // Scroll to Top Button ?>
<button id="scrollToTop" class="button is-rounded"
    style="position: fixed; bottom: 1rem; right: 1rem; display: none; z-index: 1030; box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);"
    aria-label="Voltar ao topo">
    <span class="icon">
        <?php get_template_part('template-parts/icons/arrow-up'); ?>
    </span>
</button>

<?php wp_footer(); ?>
</body>

</html>