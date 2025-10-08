<?php get_header(); ?>

<main class="container my-5">
    <header class="mb-5">
        <h1 class="display-5 fw-bold mb-3">
            Tag: <?php single_tag_title(); ?>
        </h1>
        <?php if (tag_description()): ?>
            <p class="text-muted lead">
                <?php echo tag_description(); ?>
            </p>
        <?php endif; ?>
    </header>

    <?php if (have_posts()): ?>
        <div class="row g-4 mb-5">
            <?php while (have_posts()): the_post(); ?>
                <article class="col-md-6 col-lg-4">
                    <?php get_template_part('template-parts/post-card'); ?>
                </article>
            <?php endwhile; ?>
        </div>

        <?php // Paginação ?>
        <?php theme_bootstrap_pagination(); ?>

    <?php else: ?>
        <div class="text-center py-5">
            <h2 class="h4 fw-bold mb-3">Nenhum post encontrado com esta tag</h2>
            <p class="text-muted">Explore outras tags ou volte para a página inicial.</p>
            <a href="<?php echo home_url('/'); ?>" class="btn btn-theme mt-3">Voltar ao Início</a>
        </div>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
