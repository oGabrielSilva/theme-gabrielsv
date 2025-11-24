<?php get_header(); ?>

<main class="container my-6">
    <?php while (have_posts()):
        the_post(); ?>

        <?php // Breadcrumbs ?>
        <?php get_template_part('template-parts/breadcrumbs'); ?>

        <?php // Header do Post (Full Width) ?>
        <header class="mb-6">
            <h1 class="title is-2 has-text-weight-bold mb-3"><?php the_title(); ?></h1>

            <div class="mb-4 is-flex is-justify-content-space-between is-align-items-center is-flex-wrap-wrap"
                style="gap: 0.5rem;">
                <div>
                    <?php get_template_part('template-parts/post-meta'); ?>
                </div>
            </div>
        </header>

        <div class="columns is-desktop">
            <?php // Conteúdo Principal ?>
            <div class="column is-8-desktop">
                <article id="post-<?php the_ID(); ?>" <?php post_class('pb-6 px-3'); ?>>

                    <?php if (has_post_thumbnail()): ?>
                        <figure class="image">
                            <?php the_post_thumbnail('large', array(
                                'class' => 'image',
                                'style' => 'border-radius: 1.5rem; display: block;',
                                'alt' => get_the_title()
                            )); ?>

                            <?php if (!empty(get_the_post_thumbnail_caption())): ?>
                                <figcaption class="is-size-7 has-text-grey mt-2 is-italic has-text-centered">
                                    <?php echo wp_kses_post(get_the_post_thumbnail_caption()); ?>
                                </figcaption>
                            <?php endif; ?>
                        </figure>
                    <?php endif; ?>

                    <?php // Conteúdo do Post ?>
                    <div class="post-content content py-6">
                        <?php the_content(); ?>
                    </div>

                    <?php // Botões de Compartilhamento ?>
                    <div class="mb-5">
                        <?php get_template_part('template-parts/share-buttons'); ?>
                    </div>

                    <?php // Footer do Post ?>
                    <footer class="post-footer pt-4" style="border-top: 1px solid #dbdbdb;">
                        <div class="mb-4">
                            <span class="is-size-7  has-text-weight-bold">Tags: </span>
                            <?php
                            $tags = get_the_tags();
                            if ($tags):
                                $tag_links = array();
                                foreach ($tags as $tag):
                                    $tag_links[] = '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="is-size-7" style="text-decoration: none;" aria-label="Ver posts com a tag ' . esc_attr($tag->name) . '">' . esc_html($tag->name) . '</a>';
                                endforeach;
                                echo implode(', ', $tag_links);
                            else: ?>
                                <span class="is-size-7 ">Nenhuma tag</span>
                            <?php endif; ?>
                        </div>

                        <?php // Card do Autor ?>
                        <div class="box">
                            <div class="is-flex is-flex-direction-column-mobile is-flex-direction-row-tablet is-align-items-flex-start"
                                style="gap: 1rem;">
                                <div class="is-flex-shrink-0">
                                    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"
                                        aria-label="Ver perfil de <?php the_author(); ?>">
                                        <figure class="image is-96x96">
                                            <?php echo get_avatar(get_the_author_meta('ID'), 96, '', get_the_author(), array('class' => 'is-rounded')); ?>
                                        </figure>
                                    </a>
                                </div>
                                <div class="is-flex-grow-1">
                                    <h3 class="title is-5 has-text-weight-bold mb-2">
                                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"
                                            class="has-text-current" style="text-decoration: none;">
                                            <?php the_author(); ?>
                                        </a>
                                    </h3>

                                    <?php if (get_the_author_meta('description')): ?>
                                        <p class="content is-size-7 mb-3">
                                            <?php echo get_the_author_meta('description'); ?>
                                        </p>
                                    <?php endif; ?>

                                    <?php // Contador de posts ?>
                                    <p class="is-size-7 mb-3">
                                        <?php
                                        $post_count = count_user_posts(get_the_author_meta('ID'));
                                        printf(_n('%s post publicado', '%s posts publicados', $post_count), number_format_i18n($post_count));
                                        ?>
                                    </p>

                                    <?php // Redes sociais ?>
                                    <div class="buttons are-small" role="group" aria-label="Links de contato do autor">
                                        <?php
                                        $author_website = get_the_author_meta('user_url');
                                        if ($author_website):
                                            ?>
                                            <a href="<?php echo esc_url($author_website); ?>" class="button has-text-warning"
                                                target="_blank" rel="noopener noreferrer"
                                                aria-label="Website de <?php the_author(); ?>">
                                                <span class="icon is-small">
                                                    <?php get_template_part('template-parts/icons/globe'); ?>
                                                </span>
                                            </a>
                                        <?php endif; ?>

                                        <?php
                                        $author_twitter = get_the_author_meta('twitter');
                                        if ($author_twitter):
                                            ?>
                                            <a href="<?php echo esc_url($author_twitter); ?>" class="button has-text-warning"
                                                target="_blank" rel="noopener noreferrer"
                                                aria-label="Twitter de <?php the_author(); ?>">
                                                <span class="icon is-small">
                                                    <?php get_template_part('template-parts/icons/twitter'); ?>
                                                </span>
                                            </a>
                                        <?php endif; ?>

                                        <?php
                                        $author_linkedin = get_the_author_meta('linkedin');
                                        if ($author_linkedin):
                                            ?>
                                            <a href="<?php echo esc_url($author_linkedin); ?>" class="button has-text-warning"
                                                target="_blank" rel="noopener noreferrer"
                                                aria-label="LinkedIn de <?php the_author(); ?>">
                                                <span class="icon is-small">
                                                    <?php get_template_part('template-parts/icons/linkedin'); ?>
                                                </span>
                                            </a>
                                        <?php endif; ?>

                                        <?php
                                        $author_github = get_the_author_meta('github');
                                        if ($author_github):
                                            ?>
                                            <a href="<?php echo esc_url($author_github); ?>" class="button has-text-warning"
                                                target="_blank" rel="noopener noreferrer"
                                                aria-label="GitHub de <?php the_author(); ?>">
                                                <span class="icon is-small">
                                                    <?php get_template_part('template-parts/icons/github'); ?>
                                                </span>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </footer>

                </article>

            </div>

            <?php // Sidebar ?>
            <aside class="column is-4-desktop">
                <div class="pb-6">
                    <?php get_template_part('template-parts/post-categories'); ?>
                </div>

                <?php // Posts Relacionados ?>
                <?php
                $categories = wp_get_post_categories(get_the_ID());
                if (!empty($categories)):
                    $related_posts = theme_get_cached_query(
                        'related_posts_' . get_the_ID(),
                        array(
                            'category__in' => $categories,
                            'post__not_in' => array(get_the_ID()),
                            'posts_per_page' => 5,
                            'post_status' => 'publish'
                        )
                    );

                    if ($related_posts->have_posts()):
                        ?>
                        <section class="mb-6">
                            <h2 class="title is-6 has-text-weight-bold mb-3">Posts Relacionados</h2>
                            <div class="is-flex is-flex-direction-column" style="gap: 1rem;">
                                <?php while ($related_posts->have_posts()):
                                    $related_posts->the_post();
                                    get_template_part('template-parts/post-card-small');
                                endwhile;
                                wp_reset_postdata(); ?>
                            </div>
                        </section>
                        <?php
                    endif;
                endif;
                ?>

                <hr class="my-4">

                <?php // Últimos Posts ?>
                <?php
                $latest_posts = theme_get_cached_query(
                    'latest_posts_' . get_the_ID(),
                    array(
                        'posts_per_page' => 5,
                        'post_status' => 'publish',
                        'post__not_in' => array(get_the_ID())
                    )
                );

                if ($latest_posts->have_posts()):
                    ?>
                    <section class="mb-6">
                        <h2 class="title is-6 has-text-weight-bold mb-3">Últimos Posts</h2>
                        <div class="is-flex is-flex-direction-column" style="gap: 1rem;">
                            <?php while ($latest_posts->have_posts()):
                                $latest_posts->the_post();
                                get_template_part('template-parts/post-card-small');
                            endwhile;
                            wp_reset_postdata(); ?>
                        </div>
                    </section>
                    <?php
                endif;
                ?>

                <hr class="my-4">

                <?php // Posts do Mesmo Autor ?>
                <?php
                $author_posts = theme_get_cached_query(
                    'author_posts_' . get_the_ID() . '_' . get_the_author_meta('ID'),
                    array(
                        'author' => get_the_author_meta('ID'),
                        'post__not_in' => array(get_the_ID()),
                        'posts_per_page' => 5,
                        'post_status' => 'publish'
                    )
                );

                if ($author_posts->have_posts()):
                    ?>
                    <section class="mb-6">
                        <h2 class="title is-6 has-text-weight-bold mb-3">Mais de <?php the_author(); ?></h2>
                        <div class="is-flex is-flex-direction-column" style="gap: 1rem;">
                            <?php while ($author_posts->have_posts()):
                                $author_posts->the_post();
                                get_template_part('template-parts/post-card-small');
                            endwhile;
                            wp_reset_postdata(); ?>
                        </div>
                    </section>
                    <?php
                endif;
                ?>

                <hr class="my-4">

                <?php // Tags Populares (Top 5) ?>
                <?php
                $popular_tags = get_tags(array(
                    'orderby' => 'count',
                    'order' => 'DESC',
                    'number' => 10,
                    'hide_empty' => true
                ));

                if (!empty($popular_tags)):
                    ?>
                    <section class="mb-6">
                        <h2 class="title is-6 has-text-weight-bold mb-3">Tags Populares</h2>
                        <div class="tags">
                            <?php foreach ($popular_tags as $tag): ?>
                                <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="tag is-warning is-light"
                                    aria-label="Ver posts com a tag <?php echo esc_attr($tag->name); ?>">
                                    <?php echo esc_html($tag->name); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </section>
                    <?php
                endif;
                ?>

                <hr class="my-4">

                <?php // Formulário de Busca ?>
                <?php get_template_part('template-parts/sidebar-search'); ?>
            </aside>

        </div>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>