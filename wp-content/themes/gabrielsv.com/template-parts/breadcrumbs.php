<?php // Breadcrumbs ?>
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item">
            <a href="<?php echo home_url('/'); ?>" class="text-decoration-none">Início</a>
        </li>

        <?php
        if (is_single()):
            // Post: Início > Categoria > Título do Post
            $categories = get_the_category();
            if (!empty($categories)):
                $category = $categories[0];
                ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo get_category_link($category->term_id); ?>" class="text-decoration-none">
                        <?php echo esc_html(theme_get_category_name($category)); ?>
                    </a>
                </li>
            <?php endif; ?>
            <li class="breadcrumb-item active" aria-current="page">
                <?php the_title(); ?>
            </li>

        <?php elseif (is_category()): ?>
            <?php // Categoria: Início > Nome da Categoria ?>
            <li class="breadcrumb-item active" aria-current="page">
                <?php single_cat_title(); ?>
            </li>

        <?php elseif (is_tag()): ?>
            <?php // Tag: Início > Tag ?>
            <li class="breadcrumb-item active" aria-current="page">
                <?php single_tag_title(); ?>
            </li>

        <?php elseif (is_author()): ?>
            <?php // Autor: Início > Autor ?>
            <li class="breadcrumb-item active" aria-current="page">
                <?php the_author(); ?>
            </li>

        <?php elseif (is_search()): ?>
            <?php // Busca: Início > Resultados da Pesquisa ?>
            <li class="breadcrumb-item active" aria-current="page">
                Resultados da Pesquisa
            </li>

        <?php elseif (is_404()): ?>
            <?php // 404: Início > Página não encontrada ?>
            <li class="breadcrumb-item active" aria-current="page">
                Página não encontrada
            </li>

        <?php elseif (is_page()): ?>
            <?php // Página: Início > Título da Página ?>
            <li class="breadcrumb-item active" aria-current="page">
                <?php the_title(); ?>
            </li>

        <?php endif; ?>
    </ol>
</nav>
