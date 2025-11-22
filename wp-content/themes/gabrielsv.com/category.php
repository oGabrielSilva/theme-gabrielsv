<?php get_header(); ?>

<main class="container my-6">
    <header class="mb-6">
        <h1 class="title is-2 has-text-weight-bold mb-3">
            <?php
            $category = get_queried_object();
            echo esc_html(theme_get_category_name($category));
            ?>
        </h1>
        <?php if (category_description()): ?>
            <p class="subtitle">
                <?php echo category_description(); ?>
            </p>
        <?php endif; ?>
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
            <h2 class="title is-4 has-text-weight-bold mb-3">Nenhum post encontrado nesta categoria</h2>
            <p>Explore outras categorias ou volte para a página inicial.</p>
            <a href="<?php echo home_url('/'); ?>" class="button is-primary mt-3">Voltar ao Início</a>
        </div>
    <?php endif; ?>
</main>

<?php get_footer(); ?>