<?php get_header(); ?>

<main class="container my-5">
    <header class="mb-5">
        <div class="row g-4 mb-4">
            <div class="col-md-auto d-flex justify-content-center">
                <?php echo get_avatar(get_the_author_meta('ID'), 120, '', get_the_author(), array('class' => 'rounded-circle')); ?>
            </div>
            <div class="col">
                <h1 class="h3 fw-bold mb-3">
                    <?php the_author(); ?>
                </h1>
                <?php if (get_the_author_meta('description')): ?>
                    <p class="text-muted mb-3">
                        <?php echo get_the_author_meta('description'); ?>
                    </p>
                <?php endif; ?>
                <div class="text-muted small mb-3">
                    <?php
                    $post_count = count_user_posts(get_the_author_meta('ID'));
                    printf(_n('%s post publicado', '%s posts publicados', $post_count), number_format_i18n($post_count));
                    ?>
                </div>

                <?php // Links de Contato ?>
                <div class="btn-group" role="group" aria-label="Links de contato">
                    <?php
                    $author_website = get_the_author_meta('user_url');
                    if ($author_website):
                        ?>
                        <a href="<?php echo esc_url($author_website); ?>" class="btn btn-sm btn-theme" target="_blank"
                            rel="noopener noreferrer" aria-label="Website de <?php the_author(); ?>">
                            <span>
                                <?php get_template_part('template-parts/icons/globe'); ?>
                            </span>
                        </a>
                    <?php endif; ?>

                    <?php
                    // E-mail removido por segurança - evita exposição pública de endereços de e-mail
                    // Se necessário no futuro, implementar formulário de contato ao invés de mailto:
                    ?>

                    <?php
                    $author_twitter = get_the_author_meta('twitter');
                    if ($author_twitter):
                        ?>
                        <a href="<?php echo esc_url($author_twitter); ?>" class="btn btn-sm btn-theme" target="_blank"
                            rel="noopener noreferrer" aria-label="Twitter de <?php the_author(); ?>">
                            <?php get_template_part('template-parts/icons/twitter'); ?>
                        </a>
                    <?php endif; ?>

                    <?php
                    $author_linkedin = get_the_author_meta('linkedin');
                    if ($author_linkedin):
                        ?>
                        <a href="<?php echo esc_url($author_linkedin); ?>" class="btn btn-sm btn-theme" target="_blank"
                            rel="noopener noreferrer" aria-label="LinkedIn de <?php the_author(); ?>">
                            <?php get_template_part('template-parts/icons/linkedin'); ?>

                        </a>
                    <?php endif; ?>

                    <?php
                    $author_github = get_the_author_meta('github');
                    if ($author_github):
                        ?>
                        <a href="<?php echo esc_url($author_github); ?>" class="btn btn-sm btn-theme" target="_blank"
                            rel="noopener noreferrer" aria-label="GitHub de <?php the_author(); ?>">
                            <?php get_template_part('template-parts/icons/github'); ?>

                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <?php if (have_posts()): ?>
        <div class="row g-4 mb-5">
            <?php while (have_posts()):
                the_post(); ?>
                <article class="col-md-6 col-lg-4">
                    <?php get_template_part('template-parts/post-card'); ?>
                </article>
            <?php endwhile; ?>
        </div>

        <?php // Paginação ?>
        <?php theme_bootstrap_pagination(); ?>

    <?php else: ?>
        <div class="text-center py-5">
            <h2 class="h4 fw-bold mb-3">Nenhum post publicado ainda</h2>
            <p class="text-muted">Este autor ainda não publicou nenhum conteúdo.</p>
            <a href="<?php echo home_url('/'); ?>" class="btn btn-theme mt-3">Voltar ao Início</a>
        </div>
    <?php endif; ?>
</main>

<?php get_footer(); ?>