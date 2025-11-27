<?php
$locations = get_nav_menu_locations();
$menu_id = $locations['social-links'] ?? null;
$menu_items = $menu_id ? wp_get_nav_menu_items($menu_id) : null;

if (!$menu_items || empty($menu_items)) {
    return;
}
?>

<ul class="is-flex is-flex-wrap-wrap is-justify-content-center is-align-items-center" style="gap: 0.5rem;">
    <?php foreach ($menu_items as $item):
        $network = detect_social_network($item->title);

        $icon_svg = render_social_icon($network['icon']);

        if (empty($icon_svg)) {
            continue;
        }

        $link_title = !empty($item->title) ? esc_attr($item->title) : esc_attr($network['name']);

        $target = !empty($item->target) ? $item->target : '_blank';
        $rel = 'noopener noreferrer';
        ?>
        <li>
            <a href="<?php echo esc_url($item->url); ?>" class="linkb" target="<?php echo esc_attr($target); ?>"
                rel="<?php echo esc_attr($rel); ?>" title="<?php echo $link_title; ?>"
                aria-label="<?php echo $link_title; ?>">
                <?php echo $icon_svg; ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>