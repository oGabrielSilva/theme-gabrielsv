<?php // Breadcrumbs ?>
<nav aria-label="breadcrumb" class="mb-4 breadcrumb is-size-7">
    <ul>
        <li>
            <a href="<?php echo home_url('/'); ?>">Início</a>
        </li>

        <?php
        if (is_single()):
            // Post: Início > Categoria > Título do Post
            $categories = get_the_category();
            if (!empty($categories)):
                $category = $categories[0];
                ?>
                <li>
                    <a href="<?php echo get_category_link($category->term_id); ?>">
                        <?php echo esc_html(theme_get_category_name($category)); ?>
                    </a>
                </li>
            <?php endif; ?>
            <li class="is-active">
                <a aria-current="page"><?php the_title(); ?></a>
            </li>

        <?php elseif (is_category()): ?>
            <?php // Categoria: Início > Nome da Categoria ?>
            <li class="is-active">
                <a aria-current="page"><?php single_cat_title(); ?></a>
            </li>

        <?php elseif (is_tag()): ?>
            <?php // Tag: Início > Tag ?>
            <li class="is-active">
                <a aria-current="page"><?php single_tag_title(); ?></a>
            </li>

        <?php elseif (is_author()): ?>
            <?php // Autor: Início > Autor ?>
            <li class="is-active">
                <a aria-current="page"><?php the_author(); ?></a>
            </li>

        <?php elseif (is_search()): ?>
            <?php // Busca: Início > Resultados da Pesquisa ?>
            <li class="is-active">
                <a aria-current="page">Resultados da Pesquisa</a>
            </li>

        <?php elseif (is_404()): ?>
            <?php // 404: Início > Página não encontrada ?>
            <li class="is-active">
                <a aria-current="page">Página não encontrada</a>
            </li>

        <?php elseif (is_page()): ?>
            <?php // Página: Início > Título da Página ?>
            <li class="is-active">
                <a aria-current="page"><?php the_title(); ?></a>
            </li>

        <?php endif; ?>
    </ul>
</nav>