<?php get_header(); ?>

<main class="container my-5">
    <?php while (have_posts()):
        the_post(); ?>

        <?php // Breadcrumbs ?>
        <?php get_template_part('template-parts/breadcrumbs'); ?>

        <?php // Header do Post (Full Width) ?>
        <header class="mb-5">
            <h1 class="display-5 fw-bold mb-3"><?php the_title(); ?></h1>

            <div class="mb-4">
                <?php get_template_part('template-parts/post-meta'); ?>
            </div>

            <?php if (has_post_thumbnail()): ?>
                <div class="mb-4">
                    <?php the_post_thumbnail('large', array(
                        'class' => 'img-fluid rounded-5 mx-auto d-block',
                        'alt' => get_the_title()
                    )); ?>
                </div>
            <?php endif; ?>
        </header>

        <div class="row">
            <?php // Conteúdo Principal ?>
            <div class="col-lg-8">
                <article id="post-<?php the_ID(); ?>" <?php post_class('pb-5 pe-lg-4'); ?>>

                    <?php // Conteúdo do Post ?>
                    <div class="post-content mb-5">
                        <?php the_content(); ?>
                    </div>

                    <?php // Footer do Post ?>
                    <footer class="post-footer pt-4 border-top">
                        <div class="mb-4">
                            <span class="small text-muted fw-bold">Tags: </span>
                            <?php
                            $tags = get_the_tags();
                            if ($tags):
                                $tag_links = array();
                                foreach ($tags as $tag):
                                    $tag_links[] = '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="text-decoration-none small" aria-label="Ver posts com a tag ' . esc_attr($tag->name) . '">' . esc_html($tag->name) . '</a>';
                                endforeach;
                                echo implode(', ', $tag_links);
                            else: ?>
                                <span class="small text-muted">Nenhuma tag</span>
                            <?php endif; ?>
                        </div>

                        <?php // Card do Autor ?>
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"
                                    class="d-flex flex-column flex-md-row gap-3 align-items-start text-decoration-none"
                                    aria-label="Ver perfil de <?php the_author(); ?>">
                                    <div class="flex-shrink-0">
                                        <?php echo get_avatar(get_the_author_meta('ID'), 80, '', get_the_author(), array('class' => 'rounded-circle')); ?>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h3 class="h6 fw-bold mb-1 text-body"><?php the_author(); ?></h3>
                                        <?php if (get_the_author_meta('description')): ?>
                                            <p class="text-muted small mb-0">
                                                <?php echo get_the_author_meta('description'); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </footer>

                </article>

                <?php // Comentários ?>
                <?php
                if (comments_open() || get_comments_number()):
                    comments_template();
                endif;
                ?>
            </div>

            <?php // Sidebar ?>
            <aside class="col-lg-4">
                <div class="pb-5">
                    <?php get_template_part('template-parts/post-categories'); ?>
                </div>

                <?php // Posts Relacionados ?>
                <?php
                $categories = wp_get_post_categories(get_the_ID());
                if (!empty($categories)):
                    $related_posts = new WP_Query(array(
                        'category__in' => $categories,
                        'post__not_in' => array(get_the_ID()),
                        'posts_per_page' => 4,
                        'post_status' => 'publish'
                    ));

                    if ($related_posts->have_posts()):
                        ?>
                        <section class="mb-5">
                            <h2 class="h6 fw-bold mb-3">Posts Relacionados</h2>
                            <div class="d-flex flex-column gap-3">
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
                $latest_posts = new WP_Query(array(
                    'posts_per_page' => 4,
                    'post_status' => 'publish',
                    'post__not_in' => array(get_the_ID())
                ));

                if ($latest_posts->have_posts()):
                    ?>
                    <section class="mb-5">
                        <h2 class="h6 fw-bold mb-3">Últimos Posts</h2>
                        <div class="d-flex flex-column gap-3">
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
                $author_posts = new WP_Query(array(
                    'author' => get_the_author_meta('ID'),
                    'post__not_in' => array(get_the_ID()),
                    'posts_per_page' => 4,
                    'post_status' => 'publish'
                ));

                if ($author_posts->have_posts()):
                    ?>
                    <section class="mb-5">
                        <h2 class="h6 fw-bold mb-3">Mais de <?php the_author(); ?></h2>
                        <div class="d-flex flex-column gap-3">
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
            </aside>

        </div>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>