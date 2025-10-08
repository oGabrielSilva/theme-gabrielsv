<?php get_header(); ?>

<main class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center py-5">

            <?php // Erro 404 ?>
            <div class="mb-4">
                <h1 class="display-1 fw-bold text-muted">404</h1>
                <h2 class="h3 fw-bold mb-3">Página não encontrada</h2>
                <p class="text-muted lead mb-4">
                    Desculpe, a página que você está procurando não existe ou foi movida.
                </p>
            </div>

            <?php // Formulário de Pesquisa ?>
            <div class="mb-4">
                <p class="mb-3">Tente pesquisar pelo conteúdo que você procura:</p>
                <form role="search" method="get" class="d-flex justify-content-center"
                    action="<?php echo home_url('/'); ?>">
                    <div class="input-group" style="max-width: 500px;">
                        <input type="search" class="form-control" placeholder="Pesquisar..." name="s" required>
                        <button class="btn btn-primary" type="submit">Pesquisar</button>
                    </div>
                </form>
            </div>

            <?php // Botões de Navegação ?>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="<?php echo home_url('/'); ?>" class="btn btn-theme d-inline-flex align-items-center gap-2">
                    <span style="width: 20px; height: 20px; display: inline-flex;">
                        <?php get_template_part('template-parts/icons/home'); ?>
                    </span>
                    Voltar ao Início
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
                <div class="mt-5 pt-4 border-top">
                    <h3 class="h5 fw-bold mb-4">Ou confira nossos posts recentes:</h3>
                    <div class="row g-4">
                        <?php while ($recent_posts->have_posts()):
                            $recent_posts->the_post(); ?>
                            <div class="col-md-4">
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