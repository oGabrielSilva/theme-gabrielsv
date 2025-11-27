<?php
function theme_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));

    register_nav_menus(array(
        'social-links' => __('Links de Redes Sociais', 'gabrielsv'),
    ));
}
add_action('after_setup_theme', 'theme_setup');

require_once get_template_directory() . '/inc/social-networks-config.php';

/**
 * Carrega o conteúdo de um template de e-mail em HTML para uma string.
 *
 * @param string $template_name O nome do arquivo de template (sem .php) em /template-parts/emails/.
 * @param array  $args Argumentos para usar no template.
 * @return string O conteúdo do e-mail em HTML.
 */
function get_email_template_html($template_name, $args = array())
{
    ob_start();
    $template_path = get_theme_file_path("/template-parts/emails/{$template_name}.php");

    if (file_exists($template_path)) {
        include $template_path;
    }

    return ob_get_clean();
}


// ============================================
// SECURITY & HIDE WORDPRESS
// ============================================

// Remover meta tags do WordPress
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');

// Esconder admin bar para Subscribers
function theme_hide_admin_bar()
{
    if (is_user_logged_in()) {
        $user = wp_get_current_user();
        if (
            !in_array('author', $user->roles) &&
            !in_array('editor', $user->roles) &&
            !in_array('administrator', $user->roles)
        ) {
            show_admin_bar(false);
        }
    }
}
add_action('after_setup_theme', 'theme_hide_admin_bar');

function theme_block_admin_for_subscribers()
{
    if (defined('DOING_AJAX') && DOING_AJAX) {
        return;
    }

    if (is_user_logged_in()) {
        $user = wp_get_current_user();
        if (
            !in_array('author', $user->roles) &&
            !in_array('editor', $user->roles) &&
            !in_array('administrator', $user->roles)
        ) {
            wp_redirect(home_url());
            exit;
        }
    }
}
add_action('admin_init', 'theme_block_admin_for_subscribers');

function theme_user_can_access_admin($user_id)
{
    $user = get_userdata($user_id);
    if (!$user)
        return false;

    return in_array('author', $user->roles) ||
        in_array('editor', $user->roles) ||
        in_array('administrator', $user->roles);
}

function theme_user_has_author_page($user_id)
{
    return theme_user_can_access_admin($user_id);
}

function theme_redirect_author_for_subscribers()
{
    if (is_author()) {
        $author = get_queried_object();
        if ($author && !theme_user_has_author_page($author->ID)) {
            global $wp_query;
            $wp_query->set_404();
            status_header(404);
            get_template_part('404');
            exit;
        }
    }
}
add_action('template_redirect', 'theme_redirect_author_for_subscribers');

function theme_get_client_ip()
{
    if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        return $_SERVER['HTTP_CF_CONNECTING_IP'];
    }

    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

function theme_styles()
{
    wp_enqueue_style('master', get_template_directory_uri() . '/resources/dist/css/master.min.css', array(), '1.0.0');

    wp_enqueue_style('style', get_stylesheet_uri(), array(), '1.0.0');
}
add_action('wp_enqueue_scripts', 'theme_styles');

function theme_scripts()
{
    // JavaScript compilado pelo Webpack (já incluirá componentes Bulma em TypeScript)
    wp_enqueue_script('main', get_template_directory_uri() . '/resources/dist/javascript/main.min.js', array(), '1.0.0', [
        'in_footer' => false,
    ]);

}
add_action('wp_enqueue_scripts', 'theme_scripts');

function theme_add_defer_attribute($tag, $handle, $src)
{
    if ('main' === $handle) {
        return str_replace('<script', '<script defer', $tag);
    }
    return $tag;
}
add_filter('script_loader_tag', 'theme_add_defer_attribute', 10, 3);

function theme_add_favicons()
{
    $favicon_path = get_template_directory_uri() . '/resources/favicon/';

    echo '<link rel="apple-touch-icon" sizes="180x180" href="' . $favicon_path . 'apple-touch-icon.png">' . "\n";
    echo '<link rel="icon" type="image/png" sizes="32x32" href="' . $favicon_path . 'favicon-32x32.png">' . "\n";
    echo '<link rel="icon" type="image/png" sizes="16x16" href="' . $favicon_path . 'favicon-16x16.png">' . "\n";
    echo '<link rel="manifest" href="' . $favicon_path . 'site.webmanifest">' . "\n";
    echo '<link rel="shortcut icon" href="' . $favicon_path . 'favicon.ico">' . "\n";
}
add_action('wp_head', 'theme_add_favicons');

function theme_add_lazy_loading($attr, $attachment, $size)
{
    if (isset($attr['fetchpriority']) && $attr['fetchpriority'] === 'high') {
        return $attr;
    }

    $attr['loading'] = 'lazy';
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'theme_add_lazy_loading', 10, 3);

function theme_lazy_load_avatars($args)
{
    $args['loading'] = 'lazy';
    return $args;
}
add_filter('get_avatar_data', function ($args, $id_or_email) {
    $args['extra_attr'] = 'loading="lazy"';
    return $args;
}, 10, 2);

function theme_clear_post_caches($post_id)
{
    delete_transient('related_posts_' . $post_id);

    global $wpdb;
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_latest_posts_%'");
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_latest_posts_%'");

    $author_id = get_post_field('post_author', $post_id);

    $author_id = intval($author_id);

    $pattern_posts = $wpdb->esc_like('_transient_author_posts_') . '%_' . $author_id;
    $wpdb->query($wpdb->prepare(
        "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
        $pattern_posts
    ));

    $pattern_timeout = $wpdb->esc_like('_transient_timeout_author_posts_') . '%_' . $author_id;
    $wpdb->query($wpdb->prepare(
        "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
        $pattern_timeout
    ));
}
add_action('save_post', 'theme_clear_post_caches');
add_action('delete_post', 'theme_clear_post_caches');

function theme_get_cached_query($cache_key, $query_args, $cache_duration = 12 * HOUR_IN_SECONDS)
{
    $cached_query = get_transient($cache_key);

    if (false === $cached_query) {
        $cached_query = new WP_Query($query_args);
        set_transient($cache_key, $cached_query, $cache_duration);
    }

    return $cached_query;
}

function theme_add_google_fonts()
{
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&family=Kablammo&display=swap" rel="stylesheet">' . "\n";
}
add_action('wp_head', 'theme_add_google_fonts');

function theme_disable_rest_api()
{
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');

    remove_action('template_redirect', 'rest_output_link_header', 11);

    add_filter('rest_authentication_errors', function ($result) {
        if (!empty($result)) {
            return $result;
        }

        if (is_user_logged_in()) {
            return $result;
        }

        $current_route = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';

        if (strpos($current_route, 'wordfence') !== false) {
            return $result;
        }

        if (strpos($current_route, 'oembed') !== false) {
            return $result;
        }

        return new WP_Error(
            'rest_not_logged_in',
            'API não disponível publicamente.',
            array('status' => 401)
        );
    });
}
add_action('init', 'theme_disable_rest_api');

function theme_add_image_sizes()
{
    add_image_size('blog-hero', 800, 400, true); // Para o post principal do hero
    add_image_size('blog-card', 400, 250, true); // Para cards secundários
    add_image_size('blog-thumb', 150, 100, true); // Para thumbnails pequenos
}
add_action('after_setup_theme', 'theme_add_image_sizes');


function theme_get_category_name($category)
{
    $default_category_id = get_option('default_category');
    return ($category->term_id === $default_category_id) ? 'Rabiscos' : $category->name;
}

function theme_get_formatted_categories($post_id = null)
{
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $categories = get_the_category($post_id);
    $formatted_categories = array();

    if (!empty($categories)) {
        foreach ($categories as $category) {
            $formatted_categories[] = array(
                'name' => theme_get_category_name($category),
                'link' => get_category_link($category->term_id),
                'slug' => $category->slug
            );
        }
    }

    return $formatted_categories;
}

function theme_get_limited_excerpt($post_id = null, $word_limit = 15)
{
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $excerpt = get_the_excerpt($post_id);

    if (empty($excerpt)) {
        $content = get_post_field('post_content', $post_id);
        $excerpt = wp_strip_all_tags($content);
    }

    return wp_trim_words($excerpt, $word_limit);
}

function theme_add_social_fields($user_fields)
{
    $user_fields['twitter'] = 'Twitter/X';
    $user_fields['linkedin'] = 'LinkedIn';
    $user_fields['github'] = 'GitHub';

    return $user_fields;
}
add_filter('user_contactmethods', 'theme_add_social_fields');

function theme_bootstrap_pagination($args = array())
{
    $defaults = array(
        'mid_size' => 2,
        'prev_text' => 'Anterior',
        'next_text' => 'Próximo',
        'type' => 'array',
        'current' => max(1, get_query_var('paged')),
    );

    $args = wp_parse_args($args, $defaults);
    $links = paginate_links($args);

    if (!$links) {
        return;
    }

    echo '<nav class="pagination is-centered" role="navigation" aria-label="Navegação de páginas">';
    echo '<ul class="pagination-list">';

    foreach ($links as $link) {
        if (strpos($link, 'current') !== false) {
            $link = str_replace('page-numbers current', 'pagination-link is-current', $link);
            $link = str_replace('<span', '<a', $link);
            $link = str_replace('</span>', '</a>', $link);
            echo "<li>$link</li>";
        } elseif (strpos($link, 'dots') !== false) {
            echo '<li><span class="pagination-ellipsis">&hellip;</span></li>';
        } else {
            $link = str_replace('page-numbers', 'pagination-link', $link);
            echo "<li>$link</li>";
        }
    }

    echo '</ul>';
    echo '</nav>';
}

function theme_force_disable_registration()
{
    update_option('users_can_register', 0);

    if (isset($_GET['action']) && $_GET['action'] === 'register') {
        wp_redirect(home_url());
        exit;
    }
}
add_action('init', 'theme_force_disable_registration');


function theme_disable_comments()
{
    add_filter('comments_open', '__return_false', 20, 2);
    add_filter('pings_open', '__return_false', 20, 2);

    add_filter('comments_array', '__return_empty_array', 10, 2);
}
add_action('admin_init', 'theme_disable_comments');
