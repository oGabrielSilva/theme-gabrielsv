<?php get_header(); ?>

<main class="container my-5">
    <header class="mb-5">
        <h1 class="display-5 fw-bold mb-3">
            Resultados da Pesquisa
        </h1>
        <p class="text-muted lead">
            <?php
            global $wp_query;
            $total = $wp_query->found_posts;
            printf(
                _n(
                    '%s resultado encontrado para "%s"',
                    '%s resultados encontrados para "%s"',
                    $total
                ),
                number_format_i18n($total),
                '<strong>' . get_search_query() . '</strong>'
            );
            ?>
        </p>
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
            <h2 class="h4 fw-bold mb-3">Nenhum resultado encontrado</h2>
            <p class="text-muted mb-4">Tente refinar sua pesquisa com outras palavras-chave.</p>

            <?php // Formulário de pesquisa ?>
            <form role="search" method="get" class="d-flex justify-content-center mb-4"
                action="<?php echo home_url('/'); ?>">
                <div class="input-group" style="max-width: 500px;">
                    <input type="search" class="form-control" placeholder="Pesquisar..."
                        value="<?php echo get_search_query(); ?>" name="s">
                    <button class="btn btn-primary" type="submit">Pesquisar</button>
                </div>
            </form>

            <a href="<?php echo home_url('/'); ?>" class="btn btn-theme d-inline-flex align-items-center gap-2">
                <span style="width: 20px; height: 20px; display: inline-flex;">
                    <?php get_template_part('template-parts/icons/home'); ?>
                </span>
                Voltar ao Início
            </a>
        </div>
    <?php endif; ?>
</main>

<?php get_footer(); ?>