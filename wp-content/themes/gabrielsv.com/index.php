<?php get_header(); ?>

<main class="container my-5">
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
            <article class="mb-5 pb-5">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <?php if (has_post_thumbnail()): ?>
                            <a href="<?php the_permalink(); ?>" class="d-block mb-4" aria-label="<?php the_title_attribute(); ?>">
                                <?php the_post_thumbnail('blog-hero', array('class' => 'img-fluid w-100', 'style' => 'height: 400px; object-fit: cover;')); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="col-lg-4 d-flex flex-column justify-content-center">
                        <div>
                            <?php get_template_part('template-parts/post-categories'); ?>

                            <h1 class="display-6 fw-bold mb-3 mt-2">
                                <a href="<?php the_permalink(); ?>" class="text-decoration-none text-body">
                                    <?php the_title(); ?>
                                </a>
                            </h1>

                            <p class="text-muted mb-3">
                                <?php echo theme_get_limited_excerpt(get_the_ID(), 20); ?>
                            </p>

                            <div class="d-flex justify-content-between align-items-center">
                                <?php get_template_part('template-parts/post-meta'); ?>
                                <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-theme">
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
        <section class="mb-5">
            <h2 class="h5 fw-bold mb-4">Recentes</h2>
            <div class="row g-4">
                <?php while ($recent_posts->have_posts()):
                    $recent_posts->the_post(); ?>
                    <article class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm card-hover">
                            <?php if (has_post_thumbnail()): ?>
                                <a href="<?php the_permalink(); ?>" aria-label="Ir para o artigo: <?php the_title_attribute(); ?>">
                                    <?php the_post_thumbnail('blog-card', array('class' => 'card-img-top', 'style' => 'height: 200px; object-fit: cover;', 'alt' => get_the_title())); ?>
                                </a>
                            <?php endif; ?>

                            <div class="card-body d-flex flex-column">
                                <div class="mb-2">
                                    <?php get_template_part('template-parts/post-categories'); ?>
                                </div>

                                <h3 class="card-title h6 fw-bold mb-2">
                                    <a href="<?php the_permalink(); ?>" class="text-decoration-none text-body stretched-link">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>

                                <p class="card-text text-muted small mb-3">
                                    <?php echo theme_get_limited_excerpt(get_the_ID(), 15); ?>
                                </p>

                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <?php get_template_part('template-parts/post-meta'); ?>
                                        <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-theme position-relative"
                                            style="z-index: 2;">
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
            $default_category_id = get_option('default_category');
            $category_name = ($category->term_id == $default_category_id) ? 'Rabiscos' : $category->name;

            $category_posts = new WP_Query(array(
                'cat' => $category->term_id,
                'posts_per_page' => 3,
                'post_status' => 'publish'
            ));

            if ($category_posts->have_posts()):
                ?>
                <section class="mb-5 pb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="h5 fw-bold m-0"><?php echo esc_html($category_name); ?></h2>
                        <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="small text-decoration-none">
                            Ver tudo →
                        </a>
                    </div>

                    <div class="row g-4">
                        <?php while ($category_posts->have_posts()):
                            $category_posts->the_post(); ?>
                            <div class="col-12">
                                <article class="d-flex gap-2 gap-sm-3 pb-3">
                                    <?php if (has_post_thumbnail()): ?>
                                        <a href="<?php the_permalink(); ?>" class="flex-shrink-0"
                                            aria-label="<?php the_title_attribute(); ?>">
                                            <?php the_post_thumbnail('blog-thumb', array(
                                                'class' => 'img-fluid rounded-circle',
                                                'style' => 'width: 80px; height: 80px; object-fit: cover;',
                                                'alt' => get_the_title()
                                            )); ?>
                                        </a>
                                    <?php endif; ?>

                                    <div class="flex-grow-1 d-flex flex-column min-width-0">
                                        <h3 class="h6 fw-bold mb-2">
                                            <a href="<?php the_permalink(); ?>" class="text-decoration-none text-body">
                                                <?php the_title(); ?>
                                            </a>
                                        </h3>

                                        <p class="text-muted small mb-2">
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
        <section class="text-center py-5">
            <h2 class="h4 fw-bold mb-3">Nenhum post publicado ainda</h2>
            <p class="text-muted">Em breve, novos conteúdos sobre tecnologia estarão disponíveis.</p>
        </section>
    <?php endif;
    wp_reset_postdata(); ?>

</main>

<?php get_footer(); ?>