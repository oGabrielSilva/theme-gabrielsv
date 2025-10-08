<?php // Post Categories ?>
<?php
$categories = theme_get_formatted_categories();
if (!empty($categories)):
    foreach ($categories as $cat): ?>
        <a href="<?php echo esc_url($cat['link']); ?>"
           class="btn btn-sm btn-theme me-1 mb-2"
           aria-label="Ver posts da categoria <?php echo esc_attr($cat['name']); ?>">
            <?php echo esc_html($cat['name']); ?>
        </a>
    <?php endforeach;
endif;
?>
