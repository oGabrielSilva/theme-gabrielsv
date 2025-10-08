<?php get_header(); ?>

<main class="container my-5">
    <header class="mb-5">
        <h1 class="display-5 fw-bold mb-3">
            <?php
            $category = get_queried_object();
            $default_category_id = get_option('default_category');
            $category_name = ($category->term_id == $default_category_id) ? 'Rabiscos' : $category->name;
            echo esc_html($category_name);
            ?>
        </h1>
        <?php if (category_description()): ?>
            <p class="text-muted lead">
                <?php echo category_description(); ?>
            </p>
        <?php endif; ?>
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
            <h2 class="h4 fw-bold mb-3">Nenhum post encontrado nesta categoria</h2>
            <p class="text-muted">Explore outras categorias ou volte para a página inicial.</p>
            <a href="<?php echo home_url('/'); ?>" class="btn btn-theme mt-3">Voltar ao Início</a>
        </div>
    <?php endif; ?>
</main>

<?php get_footer(); ?>