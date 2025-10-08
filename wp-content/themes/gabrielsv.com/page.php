<?php get_header(); ?>

<main class="container my-5">
    <?php while (have_posts()): the_post(); ?>

        <article id="page-<?php the_ID(); ?>" <?php post_class('mb-5'); ?>>

            <?php // Header da Página ?>
            <header class="mb-4">
                <h1 class="display-5 fw-bold mb-3"><?php the_title(); ?></h1>

                <?php if (has_post_thumbnail()): ?>
                    <div class="mb-4">
                        <?php the_post_thumbnail('large', array(
                            'class' => 'img-fluid w-100',
                            'style' => 'max-height: 400px; object-fit: cover;',
                            'alt' => get_the_title()
                        )); ?>
                    </div>
                <?php endif; ?>
            </header>

            <?php // Conteúdo da Página ?>
            <div class="page-content">
                <?php the_content(); ?>
            </div>

            <?php
            // Paginação para conteúdo dividido com <!--nextpage-->
            wp_link_pages(array(
                'before' => '<div class="page-links mt-4"><span class="page-links-title">Páginas:</span>',
                'after' => '</div>',
                'link_before' => '<span>',
                'link_after' => '</span>',
            ));
            ?>

        </article>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
