<?php get_header(); ?>

<main class="container my-6">
    <div class="columns is-centered">
        <div class="column is-8-desktop has-text-centered py-6">

            <?php // Erro 404 ?>
            <div class="mb-4">
                <h1 class="title is-1 has-text-grey">404</h1>
                <h2 class="title is-3 has-text-weight-bold mb-3">Página não encontrada</h2>
                <p class="has-text-grey subtitle mb-4">
                    Desculpe, a página que você está procurando não existe ou foi movida.
                </p>
            </div>

            <?php // Formulário de Pesquisa ?>
            <div class="mb-4">
                <p class="mb-3">Tente pesquisar pelo conteúdo que você procura:</p>
                <form role="search" method="get" class="is-flex is-justify-content-center"
                    action="<?php echo home_url('/'); ?>">
                    <div class="field has-addons" style="max-width: 500px;">
                        <div class="control is-expanded">
                            <input type="search" class="input" placeholder="Pesquisar..." name="s" required>
                        </div>
                        <div class="control">
                            <button class="button is-primary" type="submit">Pesquisar</button>
                        </div>
                    </div>
                </form>
            </div>

            <?php // Botões de Navegação ?>
            <div class="is-flex is-justify-content-center is-flex-wrap-wrap" style="gap: 1rem;">
                <a href="<?php echo home_url('/'); ?>" class="button is-primary is-inline-flex is-align-items-center" style="gap: 0.5rem;">
                    <span class="icon" style="width: 20px; height: 20px;">
                        <?php get_template_part('template-parts/icons/home'); ?>
                    </span>
                    <span>Voltar ao Início</span>
                </a>
            </div>

            <?php // Posts Recentes (Sugestões) ?>
            <?php
            $recent_posts = new WP_Query(array(
                'posts_per_page' => 3,
                'post_status' => 'publish'
            ));

            if ($recent_posts->have_posts()):
                ?>
                <div class="mt-6 pt-4" style="border-top: 1px solid #dbdbdb;">
                    <h3 class="title is-5 has-text-weight-bold mb-4">Ou confira nossos posts recentes:</h3>
                    <div class="columns is-variable is-4">
                        <?php while ($recent_posts->have_posts()):
                            $recent_posts->the_post(); ?>
                            <div class="column is-4">
                                <?php get_template_part('template-parts/post-card'); ?>
                            </div>
                        <?php endwhile;
                        wp_reset_postdata(); ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</main>

<?php get_footer(); ?>
