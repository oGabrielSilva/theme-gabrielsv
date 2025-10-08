</div>
<hr>
<footer class="text-center py-4">
    <div class="container">
        <div class="d-flex flex-column gap-3 justify-content-center align-items-center">
            <div class="d-none">
                <div class="col-md-3 pb-4 col-lg-2">
                    <nav aria-label="Navegação por categorias">
                        <h6 class="mb-3 fw-bold">Acesse</h6>
                        <ul class="list-unstyled small">
                            <?php
                            $categories = get_categories(array(
                                'orderby' => 'name',
                                'order' => 'ASC',
                                'hide_empty' => true,
                            ));

                            if (!empty($categories)):
                                foreach ($categories as $category):
                                    $default_category_id = get_option('default_category');
                                    $category_name = ($category->term_id == $default_category_id) ? 'Rabiscos' : $category->name;
                                    ?>
                                    <li class="mb-2">
                                        <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>"
                                            class="text-decoration-none linkb">
                                            <?php echo esc_html($category_name); ?>
                                        </a>
                                    </li>
                                <?php endforeach;
                            endif; ?>
                        </ul>
                    </nav>
                </div>
            </div>

            <div>
                <div class="d-flex flex-column flex-md-row align-items-md-start align-items-md-center gap-3">
                    <div class="text-muted small">
                        <h5 class="kb text-body" style="font-size: 5rem">g</h5>
                        <h5 class="mb-1 fw-bold">oTech</h5>
                        <p class="mb-0">Transformando rabiscos em ideias, ideias em código.</p>

                        <div class="py-2">
                            <nav aria-label="">
                                <?php get_template_part('template-parts/ui/social-list') ?>
                            </nav>
                        </div>

                        <div>
                            <a href="https://gabrielsv.com" target="_blank" rel="noopener noreferrer"
                                class="text-decoration-none small fw-medium"
                                aria-label="Visitar site principal de Gabriel Silva">
                                Site
                            </a>
                            |
                            <a href="https://github.com/oGabrielSilva" target="_blank" rel="noopener noreferrer"
                                class="text-decoration-none small fw-medium"
                                aria-label="Visitar GitHub de Gabriel Silva">
                                GitHub
                            </a>
                            |
                            <a href="<?php echo esc_url(get_bloginfo('rss2_url')); ?>"
                                class="text-decoration-none small fw-medium" aria-label="Feed RSS do blog">
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
                        class="text-decoration-none linkb me-3 small">Privacidade</a>
                    <a href="<?php echo esc_url(home_url('/politica-de-cookies')); ?>"
                        class="text-decoration-none linkb me-3 small">Cookies</a>
                    <a href="<?php echo esc_url(home_url('/termos-de-uso')); ?>"
                        class="text-decoration-none linkb small">Termos</a>
                </div>
            </div>
        </div>
    </div>
</footer>
</div>

<?php // Global Toast Container ?>
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
    <div id="globalToast" class="toast align-items-center border-0" role="alert" aria-live="assertive"
        aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="globalToastBody"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                aria-label="Fechar"></button>
        </div>
    </div>
</div>

<?php get_template_part('template-parts/cookie-notice'); ?>

<?php // Scroll to Top Button ?>
<button id="scrollToTop" class="btn btn-theme position-fixed bottom-0 end-0 m-3 rounded-circle shadow-lg"
    style="width: 3rem; height: 3rem; display: none; z-index: 1030;" aria-label="Voltar ao topo">
    <?php get_template_part('template-parts/icons/arrow-up'); ?>
</button>

<?php wp_footer(); ?>
</body>

</html>