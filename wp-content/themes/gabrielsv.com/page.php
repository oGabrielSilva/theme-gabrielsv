<?php get_header(); ?>

<main class="container my-6">
    <?php while (have_posts()): the_post(); ?>

        <article id="page-<?php the_ID(); ?>" <?php post_class('mb-6'); ?>>

            <?php // Header da Página ?>
            <header class="mb-4">
                <h1 class="title is-2 has-text-weight-bold mb-3"><?php the_title(); ?></h1>

                <?php if (has_post_thumbnail()): ?>
                    <div class="mb-4">
                        <?php the_post_thumbnail('large', array(
                            'class' => 'image',
                            'style' => 'max-height: 400px; object-fit: cover; width: 100%;',
                            'alt' => get_the_title()
                        )); ?>
                    </div>
                <?php endif; ?>
            </header>

            <?php // Conteúdo da Página ?>
            <div class="page-content content">
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
