<?php
// ============================================
// THEME SETUP
// ============================================

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
}
add_action('after_setup_theme', 'theme_setup');

/**
 * Carrega o conteúdo de um template de e-mail em HTML para uma string.
 *
 * @param string $template_name O nome do arquivo de template (sem .php) em /template-parts/emails/.
 * @param array  $args Argumentos para extrair e usar no template.
 * @return string O conteúdo do e-mail em HTML.
 */
function get_email_template_html($template_name, $args = array())
{
    ob_start();
    $template_path = get_theme_file_path("/template-parts/emails/{$template_name}.php");

    if (file_exists($template_path)) {
        // Extrai as variáveis para serem usadas no template
        extract($args);
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
        // Apenas Author, Editor, Administrator podem ver a admin bar
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

// Bloquear wp-login.php (retorna 404)
function theme_block_wp_login()
{
    global $pagenow;
    if ($pagenow === 'wp-login.php' && !isset($_GET['action'])) {
        status_header(404);
        nocache_headers();
        include(get_query_template('404'));
        die();
    }
}
add_action('init', 'theme_block_wp_login', 1);

// Bloquear wp-admin para Subscribers (exceto AJAX)
function theme_block_admin_for_subscribers()
{
    // Não bloquear se for requisição AJAX
    if (defined('DOING_AJAX') && DOING_AJAX) {
        return;
    }

    if (is_user_logged_in()) {
        $user = wp_get_current_user();
        // Se não for Author, Editor ou Admin, bloquear wp-admin
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

// Helper: verificar se usuário pode acessar wp-admin
function theme_user_can_access_admin($user_id)
{
    $user = get_userdata($user_id);
    if (!$user)
        return false;

    return in_array('author', $user->roles) ||
        in_array('editor', $user->roles) ||
        in_array('administrator', $user->roles);
}

// Helper: verificar se usuário tem página de autor
function theme_user_has_author_page($user_id)
{
    return theme_user_can_access_admin($user_id);
}

// Redirecionar /author para Subscribers
function theme_redirect_author_for_subscribers()
{
    if (is_author()) {
        $author = get_queried_object();
        if ($author && !theme_user_has_author_page($author->ID)) {
            // Retornar 404 para autores sem permissão
            global $wp_query;
            $wp_query->set_404();
            status_header(404);
            get_template_part('404');
            exit;
        }
    }
}
add_action('template_redirect', 'theme_redirect_author_for_subscribers');

// ============================================
// AJAX ENDPOINTS - AUTENTICAÇÃO
// ============================================

// Endpoint para login customizado
function theme_ajax_custom_login()
{
    // Verificar nonce (CSRF protection)
    check_ajax_referer('auth_nonce', 'nonce');

    // Verificar dados recebidos
    if (empty($_POST['username']) || empty($_POST['password'])) {
        wp_send_json_error(array(
            'message' => 'Por favor, preencha todos os campos.',
        ));
    }

    $username = sanitize_text_field($_POST['username']);
    $password = $_POST['password'];
    $remember = !empty($_POST['remember']);

    // Tentar fazer login
    $credentials = array(
        'user_login' => $username,
        'user_password' => $password,
        'remember' => $remember,
    );

    $user = wp_signon($credentials, false);

    if (is_wp_error($user)) {
        wp_send_json_error(array(
            'message' => 'Usuário ou senha incorretos.',
        ));
    }

    // Login bem-sucedido
    $redirect_to = !empty($_POST['redirect_to']) ? esc_url_raw($_POST['redirect_to']) : home_url();

    wp_send_json_success(array(
        'redirect' => $redirect_to,
        'message' => 'Login realizado com sucesso!',
    ));
}
add_action('wp_ajax_nopriv_custom_login', 'theme_ajax_custom_login');

// Endpoint para registro de usuários
function theme_ajax_custom_register()
{
    // Verificar nonce (CSRF protection)
    check_ajax_referer('auth_nonce', 'nonce');

    // Verificar dados recebidos
    if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password'])) {
        wp_send_json_error(array(
            'message' => 'Por favor, preencha todos os campos.',
        ));
    }

    $username = sanitize_user($_POST['username']);
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];

    // Validar username
    if (!validate_username($username)) {
        wp_send_json_error(array(
            'message' => 'Nome de usuário inválido.',
        ));
    }

    // Validar e-mail
    if (!is_email($email)) {
        wp_send_json_error(array(
            'message' => 'E-mail inválido.',
        ));
    }

    // Verificar se username já existe
    if (username_exists($username)) {
        wp_send_json_error(array(
            'message' => 'Este nome de usuário já está em uso.',
        ));
    }

    // Verificar se email já existe
    if (email_exists($email)) {
        wp_send_json_error(array(
            'message' => 'Este e-mail já está cadastrado.',
        ));
    }

    // Criar usuário
    $user_id = wp_create_user($username, $password, $email);

    if (is_wp_error($user_id)) {
        wp_send_json_error(array(
            'message' => 'Erro ao criar usuário: ' . $user_id->get_error_message(),
        ));
    }

    // Definir role como subscriber
    $user = new WP_User($user_id);
    $user->set_role('subscriber');

    // Fazer login automático
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);

    // Sucesso
    wp_send_json_success(array(
        'redirect' => home_url(),
        'message' => 'Conta criada com sucesso!',
    ));
}
add_action('wp_ajax_nopriv_custom_register', 'theme_ajax_custom_register');

// Endpoint para atualizar perfil
function theme_ajax_update_profile()
{
    // Verificar nonce (CSRF protection)
    check_ajax_referer('profile_nonce', 'nonce');

    // Verificar se está logado
    if (!is_user_logged_in()) {
        wp_send_json_error(array(
            'message' => 'Você precisa estar logado para atualizar o perfil.',
        ));
    }

    $user_id = get_current_user_id();

    // Validar e-mail
    if (empty($_POST['email']) || !is_email($_POST['email'])) {
        wp_send_json_error(array(
            'message' => 'Por favor, informe um e-mail válido.',
        ));
    }

    // Preparar dados para atualização
    $user_data = array(
        'ID' => $user_id,
        'user_email' => sanitize_email($_POST['email']),
        'first_name' => sanitize_text_field($_POST['first_name']),
        'last_name' => sanitize_text_field($_POST['last_name']),
        'description' => sanitize_textarea_field($_POST['bio']),
        'user_url' => esc_url_raw($_POST['url']),
    );

    // Se houver senha, adicionar
    if (!empty($_POST['password'])) {
        $user_data['user_pass'] = $_POST['password'];
    }

    // Atualizar usuário
    $result = wp_update_user($user_data);

    if (is_wp_error($result)) {
        wp_send_json_error(array(
            'message' => 'Erro ao atualizar perfil: ' . $result->get_error_message(),
        ));
    }

    // Atualizar meta fields (redes sociais)
    update_user_meta($user_id, 'twitter', sanitize_text_field($_POST['twitter']));
    update_user_meta($user_id, 'linkedin', esc_url_raw($_POST['linkedin']));
    update_user_meta($user_id, 'github', esc_url_raw($_POST['github']));

    wp_send_json_success(array(
        'message' => 'Perfil atualizado com sucesso!',
    ));
}
add_action('wp_ajax_update_profile', 'theme_ajax_update_profile');

// ============================================
// AJAX: RECUPERAÇÃO DE SENHA
// ============================================

function theme_ajax_password_reset_request()
{
    // Verificar nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'auth_nonce')) {
        wp_send_json_error(array(
            'message' => 'Nonce inválido. Recarregue a página e tente novamente.',
        ));
    }

    // Rate limiting: máximo 3 tentativas por IP a cada 15 minutos
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $transient_key = 'password_reset_' . md5($ip_address);
    $attempts = get_transient($transient_key);

    if ($attempts && $attempts >= 3) {
        wp_send_json_error(array(
            'message' => 'Muitas tentativas de recuperação de senha. Aguarde 15 minutos e tente novamente.',
        ));
    }

    // Validar email
    $email = sanitize_email($_POST['email']);
    if (!is_email($email)) {
        wp_send_json_error(array(
            'message' => 'E-mail inválido.',
        ));
    }

    // Verificar se usuário existe
    $user = get_user_by('email', $email);
    if (!$user) {
        wp_send_json_error(array(
            'message' => 'Nenhuma conta encontrada com este e-mail.',
        ));
    }

    // Incrementar contador de tentativas
    $new_attempts = $attempts ? $attempts + 1 : 1;
    set_transient($transient_key, $new_attempts, 15 * MINUTE_IN_SECONDS);

    // Gerar token de reset
    $reset_key = get_password_reset_key($user);
    if (is_wp_error($reset_key)) {
        wp_send_json_error(array(
            'message' => 'Erro ao gerar link de recuperação. Tente novamente.',
        ));
    }

    // Criar link de reset
    $reset_url = add_query_arg(
        array(
            'action' => 'rp',
            'key' => $reset_key,
            'login' => rawurlencode($user->user_login),
        ),
        home_url('/auth')
    );

    // Enviar email
    $to = $user->user_email;
    $subject = '[' . get_bloginfo('name') . '] Recuperação de senha';
    $message = get_email_template_html('email-password-reset', [
        'user_name' => $user->display_name,
        'reset_url' => $reset_url,
    ]);

    $headers = array('Content-Type: text/html; charset=UTF-8');

    $sent = wp_mail($to, $subject, $message, $headers);

    if (!$sent) {
        wp_send_json_error(array(
            'message' => 'Erro ao enviar e-mail. Tente novamente mais tarde.',
        ));
    }

    wp_send_json_success(array(
        'message' => 'Link de recuperação enviado! Verifique seu e-mail.',
    ));
}
add_action('wp_ajax_nopriv_password_reset_request', 'theme_ajax_password_reset_request');

function theme_ajax_password_reset_confirm()
{
    // Validar dados
    $reset_key = sanitize_text_field($_POST['reset_key']);
    $reset_login = sanitize_text_field($_POST['reset_login']);
    $password = $_POST['password'];

    if (empty($reset_key) || empty($reset_login) || empty($password)) {
        wp_send_json_error(array(
            'message' => 'Dados inválidos.',
        ));
    }

    // Verificar chave de reset
    $user = check_password_reset_key($reset_key, $reset_login);

    if (is_wp_error($user)) {
        if ($user->get_error_code() === 'expired_key') {
            wp_send_json_error(array(
                'message' => 'Este link expirou. Solicite um novo link de recuperação.',
            ));
        } else {
            wp_send_json_error(array(
                'message' => 'Link de recuperação inválido.',
            ));
        }
    }

    // Redefinir senha
    reset_password($user, $password);

    wp_send_json_success(array(
        'message' => 'Senha redefinida com sucesso! Redirecionando...',
    ));
}
add_action('wp_ajax_nopriv_password_reset_confirm', 'theme_ajax_password_reset_confirm');
add_action('wp_ajax_password_reset_confirm', 'theme_ajax_password_reset_confirm');

// ============================================
// AJAX: DELETAR COMENTÁRIO
// ============================================

function theme_ajax_delete_comment()
{
    // Verificar nonce (CSRF protection)
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'comment_nonce')) {
        wp_send_json_error(array(
            'message' => 'Nonce inválido. Recarregue a página e tente novamente.',
        ));
    }

    // Verificar se está logado
    if (!is_user_logged_in()) {
        wp_send_json_error(array(
            'message' => 'Você precisa estar logado para deletar comentários.',
        ));
    }

    // Validar comment_id
    if (empty($_POST['comment_id'])) {
        wp_send_json_error(array(
            'message' => 'ID do comentário não fornecido.',
        ));
    }

    $comment_id = intval($_POST['comment_id']);
    $comment = get_comment($comment_id);

    // Verificar se comentário existe
    if (!$comment) {
        wp_send_json_error(array(
            'message' => 'Comentário não encontrado.',
        ));
    }

    // Verificar se o usuário é o autor do comentário
    $current_user_id = get_current_user_id();
    if ($comment->user_id != $current_user_id) {
        wp_send_json_error(array(
            'message' => 'Você não pode deletar este comentário.',
        ));
    }

    // Deletar comentário permanentemente
    $result = wp_delete_comment($comment_id, true);

    if ($result) {
        wp_send_json_success(array(
            'message' => 'Comentário deletado com sucesso.',
        ));
    } else {
        wp_send_json_error(array(
            'message' => 'Erro ao deletar comentário. Tente novamente.',
        ));
    }
}
add_action('wp_ajax_delete_user_comment', 'theme_ajax_delete_comment');

function theme_styles()
{
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/resources/lib/bootstrap/5_3_8/css/bootstrap.min.css', array(), '5.3.8');

    wp_enqueue_style('master', get_template_directory_uri() . '/resources/dist/css/master.min.css', array('bootstrap'), '1.0.0');

    wp_enqueue_style('style', get_stylesheet_uri(), array(), '1.0.0');
}
add_action('wp_enqueue_scripts', 'theme_styles');

function theme_scripts()
{
    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/resources/lib/bootstrap/5_3_8/js/bootstrap.bundle.min.js', array(), '5.3.8', [
        'in_footer' => true,
    ]);

    wp_enqueue_script('main', get_template_directory_uri() . '/resources/dist/javascript/main.min.js', array('bootstrap'), '1.0.0', [
        'in_footer' => false,
    ]);

    // Script de comentários apenas em posts com comentários
    if (is_singular() && comments_open()) {
        wp_enqueue_script('comments', get_template_directory_uri() . '/resources/dist/javascript/comments.min.js', array('bootstrap'), '1.0.0', [
            'in_footer' => true,
        ]);
        wp_localize_script('comments', 'commentsData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('comment_nonce'),
        ));
    }

    // Script de autenticação na página /auth
    if (is_page_template('page-auth.php')) {
        wp_enqueue_script('auth', get_template_directory_uri() . '/resources/dist/javascript/auth.min.js', array(), '1.0.0', [
            'in_footer' => true,
        ]);
        wp_localize_script('auth', 'authData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('auth_nonce'),
        ));
    }

    // Script de perfil na página /eu
    if (is_page_template('page-eu.php')) {
        wp_enqueue_script('profile', get_template_directory_uri() . '/resources/dist/javascript/profile.min.js', array(), '1.0.0', [
            'in_footer' => true,
        ]);
        wp_localize_script('profile', 'profileData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('profile_nonce'),
        ));
    }
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

// Adicionar favicons ao head
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

// Adicionar loading="lazy" em todas as imagens (exceto se fetchpriority="high")
function theme_add_lazy_loading($attr, $attachment, $size)
{
    // Não adicionar lazy loading se já tem fetchpriority="high"
    if (isset($attr['fetchpriority']) && $attr['fetchpriority'] === 'high') {
        return $attr;
    }

    $attr['loading'] = 'lazy';
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'theme_add_lazy_loading', 10, 3);

// Adicionar loading="lazy" em avatares
function theme_lazy_load_avatars($args)
{
    $args['loading'] = 'lazy';
    return $args;
}
add_filter('get_avatar_data', function ($args, $id_or_email) {
    $args['extra_attr'] = 'loading="lazy"';
    return $args;
}, 10, 2);

function theme_add_google_fonts()
{
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&family=Kablammo&display=swap" rel="stylesheet">' . "\n";
}
add_action('wp_head', 'theme_add_google_fonts');

// Desativar API REST do WordPress
function theme_disable_rest_api()
{
    // Remover link da API REST do head
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');

    // Remover headers da API REST
    remove_action('template_redirect', 'rest_output_link_header', 11);

    // Desativar completamente a API REST para usuários não logados
    add_filter('rest_authentication_errors', function ($result) {
        if (!empty($result)) {
            return $result;
        }
        if (!is_user_logged_in()) {
            return new WP_Error('rest_not_logged_in', 'Você não tem permissão para acessar a API.', array('status' => 401));
        }
        return $result;
    });
}
add_action('init', 'theme_disable_rest_api');

// Adicionar tamanhos de imagem personalizados para o blog
function theme_add_image_sizes()
{
    add_image_size('blog-hero', 800, 400, true); // Para o post principal do hero
    add_image_size('blog-card', 400, 250, true); // Para cards secundários
    add_image_size('blog-thumb', 150, 100, true); // Para thumbnails pequenos
}
add_action('after_setup_theme', 'theme_add_image_sizes');


// Função helper para obter categorias formatadas
function theme_get_formatted_categories($post_id = null)
{
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $categories = get_the_category($post_id);
    $formatted_categories = array();

    if (!empty($categories)) {
        $default_category_id = get_option('default_category');

        foreach ($categories as $category) {
            $category_name = ($category->term_id == $default_category_id) ? 'Rabiscos' : $category->name;
            $formatted_categories[] = array(
                'name' => $category_name,
                'link' => get_category_link($category->term_id),
                'slug' => $category->slug
            );
        }
    }

    return $formatted_categories;
}

// Função helper para obter excerpt limitado
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

// Adicionar campos de redes sociais ao perfil do usuário
function theme_add_social_fields($user_fields)
{
    $user_fields['twitter'] = 'Twitter/X';
    $user_fields['linkedin'] = 'LinkedIn';
    $user_fields['github'] = 'GitHub';

    return $user_fields;
}
add_filter('user_contactmethods', 'theme_add_social_fields');

// Customizar paginação para usar classes do Bootstrap
function theme_bootstrap_pagination($args = array())
{
    $defaults = array(
        'mid_size' => 2,
        'prev_text' => '&laquo;',
        'next_text' => '&raquo;',
        'type' => 'array',
        'current' => max(1, get_query_var('paged')),
    );

    $args = wp_parse_args($args, $defaults);
    $links = paginate_links($args);

    if (!$links) {
        return;
    }

    echo '<nav aria-label="Navegação de páginas">';
    echo '<ul class="pagination justify-content-center">';

    foreach ($links as $link) {
        $class = 'page-item';

        // Detectar página ativa
        if (strpos($link, 'current') !== false) {
            $class .= ' active';
            $link = str_replace('page-numbers current', 'page-link', $link);
        } else {
            $link = str_replace('page-numbers', 'page-link', $link);
        }

        // Detectar dots
        if (strpos($link, 'dots') !== false) {
            $class .= ' disabled';
            $link = str_replace('dots', 'page-link', $link);
        }

        echo "<li class='$class'>$link</li>";
    }

    echo '</ul>';
    echo '</nav>';
}

// Configurações de comentários
function theme_comments_setup()
{
    // Bloquear envio de comentários para usuários deslogados
    add_filter('pre_comment_on_post', function ($post_id) {
        if (!is_user_logged_in()) {
            wp_die('Você precisa estar logado para comentar.', 'Erro', array('response' => 403));
        }
    });

    // Forçar aprovação manual de todos os comentários
    add_filter('pre_comment_approved', function ($approved) {
        return 0; // 0 = aguardando moderação
    });

    // Modificar query SQL para incluir comentários não aprovados quando necessário
    add_filter('comments_clauses', function ($clauses, $query) {
        $current_user_id = get_current_user_id();

        // Se não está logado, manter comportamento padrão (só aprovados)
        if (!$current_user_id) {
            return $clauses;
        }

        // Se tem permissão de moderação, mostrar todos os comentários
        if (current_user_can('moderate_comments')) {
            $clauses['where'] = str_replace(
                "comment_approved = '1'",
                "(comment_approved = '1' OR comment_approved = '0')",
                $clauses['where']
            );
            return $clauses;
        }

        // Para usuários logados sem permissão de moderação,
        // mostrar aprovados + seus próprios comentários não aprovados
        $clauses['where'] = str_replace(
            "comment_approved = '1'",
            "(comment_approved = '1' OR (comment_approved = '0' AND user_id = {$current_user_id}))",
            $clauses['where']
        );

        return $clauses;
    }, 10, 2);
}
add_action('init', 'theme_comments_setup');

// ============================================
// EMAIL NOTIFICATIONS - COMENTÁRIOS
// ============================================

// Notificar quando comentário for aprovado
function theme_notify_comment_approved($new_status, $old_status, $comment)
{
    // Só notificar se mudou para "approved"
    if ($new_status !== 'approved' || $old_status === 'approved') {
        return;
    }

    // Verificar se comentário tem user_id (usuário logado)
    if (!$comment || !$comment->user_id) {
        return;
    }

    $user = get_userdata($comment->user_id);
    if (!$user) {
        return;
    }

    $post = get_post($comment->comment_post_ID);
    if (!$post) {
        return;
    }

    // Cooldown: evitar envio duplicado em 5 minutos
    $transient_key = 'email_comment_approved_' . $user->ID;
    if (get_transient($transient_key)) {
        return;
    }

    // Preparar e-mail
    $to = $user->user_email;
    $subject = '[' . get_bloginfo('name') . '] Seu comentário foi aprovado';
    $message = get_email_template_html('email-comment-approved', [
        'user_name' => $user->display_name,
        'post_title' => $post->post_title,
        'comment_excerpt' => wp_trim_words(wp_strip_all_tags($comment->comment_content), 30),
        'comment_link' => get_comment_link($comment->comment_ID),
    ]);

    $headers = array('Content-Type: text/html; charset=UTF-8');
    wp_mail($to, $subject, $message, $headers);

    // Bloquear novos envios pelos próximos 5 minutos
    set_transient($transient_key, true, 5 * MINUTE_IN_SECONDS);
}
add_action('transition_comment_status', 'theme_notify_comment_approved', 10, 3);

// Notificar quando comentário receber resposta
function theme_notify_comment_reply($comment_id, $comment)
{
    // Verificar se é uma resposta (tem comment_parent)
    if (!$comment->comment_parent) {
        return;
    }

    // Pegar comentário pai
    $parent_comment = get_comment($comment->comment_parent);
    if (!$parent_comment || !$parent_comment->user_id) {
        return;
    }

    // Não notificar se responder a si mesmo
    if ($parent_comment->user_id == $comment->user_id) {
        return;
    }

    $parent_user = get_userdata($parent_comment->user_id);
    if (!$parent_user) {
        return;
    }

    $post = get_post($comment->comment_post_ID);
    if (!$post) {
        return;
    }

    // Cooldown: evitar spam de notificações entre mesmos usuários
    $transient_key = sprintf(
        'email_reply_%d_from_%d',
        $parent_comment->user_id,  // quem vai receber
        $comment->user_id           // quem respondeu
    );

    if (get_transient($transient_key)) {
        // E-mail de resposta já enviado recentemente entre esses usuários
        return;
    }

    // Preparar e-mail
    $to = $parent_user->user_email;
    $subject = '[' . get_bloginfo('name') . '] Novo comentário respondendo ao seu';
    $message = get_email_template_html('email-comment-reply', [
        'parent_user_name' => $parent_user->display_name,
        'replier_name' => get_comment_author($comment),
        'post_title' => $post->post_title,
        'parent_comment_excerpt' => wp_trim_words(wp_strip_all_tags($parent_comment->comment_content), 20),
        'comment_excerpt' => wp_trim_words(wp_strip_all_tags($comment->comment_content), 30),
        'comment_link' => get_comment_link($comment_id),
    ]);

    $headers = array('Content-Type: text/html; charset=UTF-8');
    wp_mail($to, $subject, $message, $headers);

    // Bloquear novas notificações entre esses usuários pelos próximos 5 minutos
    set_transient($transient_key, true, 5 * MINUTE_IN_SECONDS);
}
add_action('comment_post', 'theme_notify_comment_reply', 10, 2);

// Template customizado para comentários individuais
function theme_comment_template($comment, $args, $depth)
{
    $tag = ('div' === $args['style']) ? 'div' : 'li';
    ?>
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class('comment mb-4', $comment); ?> >
        <article class="comment-body card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex gap-3 mb-3">
                    <div class="flex-shrink-0">
                        <?php echo get_avatar($comment, $args['avatar_size'], '', '', array('class' => 'rounded-circle', 'loading' => 'lazy')); ?>
                    </div>
                    <div class="flex-grow-1">
                        <div class="comment-meta mb-2">
                            <strong class="comment-author">
                                <?php
                                // Link para página do autor (apenas se tiver role de Author+)
                                $comment_author_id = $comment->user_id;
                                if ($comment_author_id && theme_user_has_author_page($comment_author_id)) {
                                    printf(
                                        '<a href="%s" class="text-decoration-none">%s</a>',
                                        esc_url(get_author_posts_url($comment_author_id)),
                                        esc_html(get_comment_author($comment))
                                    );
                                } else {
                                    echo esc_html(get_comment_author($comment));
                                }
                                ?>
                            </strong>
                            <div class="small text-muted">
                                <time datetime="<?php comment_time('c'); ?>">
                                    <?php printf('%s às %s', get_comment_date('d/m/Y'), get_comment_time('H:i')); ?>
                                </time>
                                <?php if ('0' == $comment->comment_approved): ?>
                                    <span class="badge bg-warning text-dark ms-2">Aguardando moderação</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="comment-content">
                            <?php comment_text(); ?>
                        </div>

                        <?php
                        $reply_link = get_comment_reply_link(array_merge($args, array(
                            'depth' => $depth,
                            'max_depth' => $args['max_depth'],
                            'reply_text' => 'Responder',
                            'add_below' => 'comment'
                        )));

                        if ($reply_link):
                            // Adicionar dados do comentário para o modal
                            $comment_avatar = get_avatar_url($comment, array('size' => 40));
                            $comment_date = get_comment_date('d/m/Y \à\s H:i', $comment->comment_ID);
                            $comment_content = wp_trim_words(get_comment_text($comment->comment_ID), 50, '...');

                            // Se usuário não está logado, mudar comportamento do botão
                            if (!is_user_logged_in()) {
                                $reply_link = str_replace(
                                    'class="comment-reply-link"',
                                    'class="comment-reply-link text-decoration-none small" data-bs-toggle="modal" data-bs-target="#loginRequiredModal"',
                                    $reply_link
                                );
                                // Remover href para não scroll
                                $reply_link = preg_replace('/href="[^"]*"/', 'href="#"', $reply_link);
                            } else {
                                $reply_link = str_replace(
                                    'class="comment-reply-link"',
                                    'class="comment-reply-link text-decoration-none small" ' .
                                    'data-comment-id="' . get_comment_ID() . '" ' .
                                    'data-comment-author="' . esc_attr(get_comment_author()) . '" ' .
                                    'data-comment-avatar="' . esc_url($comment_avatar) . '" ' .
                                    'data-comment-date="' . esc_attr($comment_date) . '" ' .
                                    'data-comment-content="' . esc_attr($comment_content) . '"',
                                    $reply_link
                                );
                            }
                            echo '<div class="reply mt-2">' . $reply_link;

                            // Botão de deletar (apenas para o autor do comentário)
                            if (is_user_logged_in() && $comment->user_id == get_current_user_id()) {
                                echo ' <span class="text-muted">•</span> ';
                                echo '<button type="button" class="btn btn-link btn-sm text-danger text-decoration-none p-0 delete-comment-btn" data-comment-id="' . get_comment_ID() . '">Deletar</button>';
                            }

                            echo '</div>';
                        endif;
                        ?>
                    </div>
                </div>
            </div>
        </article>
        <?php
}
