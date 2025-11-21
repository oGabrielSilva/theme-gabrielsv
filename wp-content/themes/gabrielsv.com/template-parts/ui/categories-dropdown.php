<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        Explore
    </a>
    <ul class="dropdown-menu">
        <?php
        $categories = get_categories(array(
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => true,
        ));

        if (!empty($categories)):
            foreach ($categories as $category):
                ?>
                <li>
                    <a class="dropdown-item" href="<?php echo esc_url(get_category_link($category->term_id)); ?>">
                        <?php echo esc_html(theme_get_category_name($category)); ?>
                        <span class="badge bg-secondary ms-2"><?php echo $category->count; ?></span>
                    </a>
                </li>
            <?php endforeach;
        else: ?>
            <li><span class="dropdown-item-text text-muted">Nenhum assunto encontrado</span></li>
        <?php endif; ?>
    </ul>
</li>