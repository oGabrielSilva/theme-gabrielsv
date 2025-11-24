<div class="navbar-item has-dropdown is-hoverable">
    <a class="navbar-link">
        Explore
    </a>
    <div class="navbar-dropdown">
        <?php
        $categories = get_categories(array(
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => true,
        ));

        if (!empty($categories)):
            foreach ($categories as $category):
                ?>
                <a class="navbar-item"
                    href="<?php echo esc_url(get_category_link($category->term_id)); ?>">
                    <span><?php echo esc_html(theme_get_category_name($category)); ?></span>
                </a>
            <?php endforeach;
        else: ?>
            <span class="navbar-item ">Nenhum assunto encontrado</span>
        <?php endif; ?>
    </div>
</div>