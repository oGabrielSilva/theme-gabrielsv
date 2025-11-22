<?php get_header(); ?>

<main class="container my-6">


    <?php
    $latest_posts = new WP_Query(array(
        'posts_per_page' => 1,
        'post_status' => 'publish'
    ));
    ?>

    <?php // Hero Post ?>
    <?php if ($latest_posts->have_posts()): ?>
        <?php while ($latest_posts->have_posts()):
            $latest_posts->the_post(); ?>
            <article class="mb-6 pb-6">
                <div class="columns is-variable is-4">
                    <div class="column is-8-desktop">
                        <?php if (has_post_thumbnail()): ?>
                            <a href="<?php the_permalink(); ?>" class="is-block mb-4" aria-label="<?php the_title_attribute(); ?>">
                                <?php the_post_thumbnail('blog-hero', array('class' => 'image is-fullwidth', 'style' => 'height: 400px; object-fit: cover;')); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="column is-4-desktop is-flex is-flex-direction-column is-justify-content-center">
                        <div>
                            <?php get_template_part('template-parts/post-categories'); ?>

                            <h1 class="title is-3 has-text-weight-bold mb-3 mt-2">
                                <a href="<?php the_permalink(); ?>" style="text-decoration: none; color: inherit;">
                                    <?php the_title(); ?>
                                </a>
                            </h1>

                            <p class="mb-3">
                                <?php echo theme_get_limited_excerpt(get_the_ID(), 20); ?>
                            </p>

                            <div class="is-flex is-justify-content-space-between is-align-items-center">
                                <?php get_template_part('template-parts/post-meta'); ?>
                                <a href="<?php the_permalink(); ?>" class="button is-small">
                                    Ler
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        <?php endwhile;
        wp_reset_postdata(); ?>
    <?php endif; ?>

    <?php // Seção de Posts Recentes ?>
    <?php
    $recent_posts = new WP_Query(array(
        'posts_per_page' => 6,
        'post_status' => 'publish',
        'offset' => 1
    ));
    ?>

    <?php if ($recent_posts->have_posts()): ?>
        <section class="mb-6">
            <h2 class="title is-5 has-text-weight-bold mb-4">Recentes</h2>
            <div class="columns is-multiline is-variable is-4">
                <?php while ($recent_posts->have_posts()):
                    $recent_posts->the_post(); ?>
                    <article class="column is-6-tablet is-4-desktop">
                        <div class="card card-hover" style="height: 100%;">
                            <?php if (has_post_thumbnail()): ?>
                                <div class="card-image">
                                    <figure class="image">
                                        <a href="<?php the_permalink(); ?>"
                                            aria-label="Ir para o artigo: <?php the_title_attribute(); ?>">
                                            <?php the_post_thumbnail('blog-card', array('style' => 'height: 200px; object-fit: cover;', 'alt' => get_the_title())); ?>
                                        </a>
                                    </figure>
                                </div>
                            <?php endif; ?>

                            <div class="card-content is-flex is-flex-direction-column" style="flex-grow: 1;">
                                <div class="mb-2">
                                    <?php get_template_part('template-parts/post-categories'); ?>
                                </div>

                                <h3 class="title is-6 has-text-weight-bold mb-2">
                                    <a href="<?php the_permalink(); ?>" style="text-decoration: none; color: inherit;">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>

                                <p class="content is-size-7 mb-3">
                                    <?php echo theme_get_limited_excerpt(get_the_ID(), 15); ?>
                                </p>

                                <div class="mt-auto">
                                    <div class="is-flex is-justify-content-space-between is-align-items-center">
                                        <?php get_template_part('template-parts/post-meta'); ?>
                                        <a href="<?php the_permalink(); ?>" class="button is-small"
                                            style="position: relative; z-index: 2;">
                                            Ler
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endwhile;
                wp_reset_postdata(); ?>
            </div>
        </section>
    <?php endif; ?>

    <?php // Seção por Categorias ?>
    <?php
    $categories = get_categories(array(
        'orderby' => 'name',
        'order' => 'ASC',
        'hide_empty' => true,
        'number' => 3
    ));

    if (!empty($categories)):
        foreach ($categories as $category):
            $category_name = theme_get_category_name($category);

            $category_posts = new WP_Query(array(
                'cat' => $category->term_id,
                'posts_per_page' => 3,
                'post_status' => 'publish'
            ));

            if ($category_posts->have_posts()):
                ?>
                <section class="mb-6 pb-4">
                    <div class="is-flex is-justify-content-space-between is-align-items-center mb-4">
                        <h2 class="has-text-grey is-5 has-text-weight-bold m-0 is-family-monospace">
                            <?php echo esc_html($category_name); ?>
                        </h2>
                        <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="button is-ghost is-small">
                            Ver tudo
                        </a>
                    </div>

                    <div class="columns is-multiline is-variable is-4">
                        <?php while ($category_posts->have_posts()):
                            $category_posts->the_post(); ?>
                            <div class="column is-12">
                                <article class="is-flex pb-3" style="gap: 0.75rem;">
                                    <?php if (has_post_thumbnail()): ?>
                                        <a href="<?php the_permalink(); ?>" class="is-flex-shrink-0"
                                            aria-label="<?php the_title_attribute(); ?>">
                                            <?php the_post_thumbnail('blog-thumb', array(
                                                'class' => 'is-rounded',
                                                'style' => 'width: 80px; height: 80px; object-fit: cover;',
                                                'alt' => get_the_title()
                                            )); ?>
                                        </a>
                                    <?php endif; ?>

                                    <div class="is-flex-grow-1 is-flex is-flex-direction-column" style="min-width: 0;">
                                        <h3 class="title is-6 has-text-weight-bold mb-2">
                                            <a href="<?php the_permalink(); ?>" style="text-decoration: none; color: inherit;">
                                                <?php the_title(); ?>
                                            </a>
                                        </h3>

                                        <p class="content is-size-7 mb-2">
                                            <?php echo theme_get_limited_excerpt(get_the_ID(), 12); ?>
                                        </p>

                                        <div class="mt-auto">
                                            <?php get_template_part('template-parts/post-meta'); ?>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        <?php endwhile;
                        wp_reset_postdata(); ?>
                    </div>
                </section>
                <?php
            endif;
        endforeach;
    endif;
    ?>

    <?php // Seção sem posts ?>
    <?php
    $all_posts = new WP_Query(array('posts_per_page' => 1, 'post_status' => 'publish'));
    if (!$all_posts->have_posts()):
        ?>
        <section class="has-text-centered py-6">
            <h2 class="title is-4 has-text-weight-bold mb-3">Nenhum post publicado ainda</h2>
            <p>Em breve, novos conteúdos sobre tecnologia estarão disponíveis.</p>
        </section>
    <?php endif;
    wp_reset_postdata(); ?>

</main>

<?php get_footer(); ?>