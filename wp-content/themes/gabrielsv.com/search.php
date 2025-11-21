<?php get_header(); ?>

<main class="container my-6">
    <header class="mb-6">
        <h1 class="title is-2 has-text-weight-bold mb-3">
            Resultados da Pesquisa
        </h1>
        <p class="has-text-grey subtitle">
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
            <h2 class="title is-4 has-text-weight-bold mb-3">Nenhum resultado encontrado</h2>
            <p class="has-text-grey mb-4">Tente refinar sua pesquisa com outras palavras-chave.</p>

            <?php // Formulário de pesquisa ?>
            <form role="search" method="get" class="is-flex is-justify-content-center mb-4"
                action="<?php echo home_url('/'); ?>">
                <div class="field has-addons" style="max-width: 500px;">
                    <div class="control is-expanded">
                        <input type="search" class="input" placeholder="Pesquisar..."
                            value="<?php echo get_search_query(); ?>" name="s">
                    </div>
                    <div class="control">
                        <button class="button is-primary" type="submit">Pesquisar</button>
                    </div>
                </div>
            </form>

            <a href="<?php echo home_url('/'); ?>" class="button is-primary is-inline-flex is-align-items-center" style="gap: 0.5rem;">
                <span class="icon" style="width: 20px; height: 20px;">
                    <?php get_template_part('template-parts/icons/home'); ?>
                </span>
                <span>Voltar ao Início</span>
            </a>
        </div>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
