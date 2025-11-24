<?php get_header(); ?>

<main class="container my-6">
    <header class="mb-6">
        <div class="columns is-variable is-4 mb-4">
            <div class="column is-narrow is-flex is-justify-content-center">
                <figure class="image is-128x128">
                    <?php echo get_avatar(get_the_author_meta('ID'), 120, '', get_the_author(), array('class' => 'is-rounded')); ?>
                </figure>
            </div>
            <div class="column">
                <h1 class="title is-3 has-text-weight-bold mb-3">
                    <?php the_author(); ?>
                </h1>
                <?php if (get_the_author_meta('description')): ?>
                    <p class="mb-3">
                        <?php echo get_the_author_meta('description'); ?>
                    </p>
                <?php endif; ?>
                <div class="is-size-7 mb-3">
                    <?php
                    $post_count = count_user_posts(get_the_author_meta('ID'));
                    printf(_n('%s post publicado', '%s posts publicados', $post_count), number_format_i18n($post_count));
                    ?>
                </div>

                <?php // Links de Contato ?>
                <div class="buttons are-small" role="group" aria-label="Links de contato">
                    <?php
                    $author_website = get_the_author_meta('user_url');
                    if ($author_website):
                        ?>
                        <a href="<?php echo esc_url($author_website); ?>" class="button has-text-warning" target="_blank"
                            rel="noopener noreferrer" aria-label="Website de <?php the_author(); ?>">
                            <span class="icon">
                                <?php get_template_part('template-parts/icons/globe'); ?>
                            </span>
                        </a>
                    <?php endif; ?>

                    <?php
                    $author_twitter = get_the_author_meta('twitter');
                    if ($author_twitter):
                        ?>
                        <a href="<?php echo esc_url($author_twitter); ?>" class="button has-text-warning" target="_blank"
                            rel="noopener noreferrer" aria-label="Twitter de <?php the_author(); ?>">
                            <span class="icon">
                                <?php get_template_part('template-parts/icons/twitter'); ?>
                            </span>
                        </a>
                    <?php endif; ?>

                    <?php
                    $author_linkedin = get_the_author_meta('linkedin');
                    if ($author_linkedin):
                        ?>
                        <a href="<?php echo esc_url($author_linkedin); ?>" class="button has-text-warning" target="_blank"
                            rel="noopener noreferrer" aria-label="LinkedIn de <?php the_author(); ?>">
                            <span class="icon">
                                <?php get_template_part('template-parts/icons/linkedin'); ?>
                            </span>
                        </a>
                    <?php endif; ?>

                    <?php
                    $author_github = get_the_author_meta('github');
                    if ($author_github):
                        ?>
                        <a href="<?php echo esc_url($author_github); ?>" class="button has-text-warning" target="_blank"
                            rel="noopener noreferrer" aria-label="GitHub de <?php the_author(); ?>">
                            <span class="icon">
                                <?php get_template_part('template-parts/icons/github'); ?>
                            </span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <?php if (have_posts()): ?>
        <div class="columns is-multiline is-variable is-4 mb-6">
            <?php while (have_posts()):
                the_post(); ?>
                <article class="column is-6-tablet is-4-desktop">
                    <?php get_template_part('template-parts/post-card'); ?>
                </article>
            <?php endwhile; ?>
        </div>

        <?php // Paginação ?>
        <?php theme_bootstrap_pagination(); ?>

    <?php else: ?>
        <div class="has-text-centered py-6">
            <h2 class="title is-4 has-text-weight-bold mb-3">Nenhum post publicado ainda</h2>
            <p class="">Este autor ainda não publicou nenhum conteúdo.</p>
            <a href="<?php echo home_url('/'); ?>" class="button has-text-warning mt-3">Voltar ao Início</a>
        </div>
    <?php endif; ?>
</main>

<?php get_footer(); ?>