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
 * @param array  $args Argumentos para usar no template.
 * @return string O conteúdo do e-mail em HTML.
 */
function get_email_template_html($template_name, $args = array())
{
    ob_start();
    $template_path = get_theme_file_path("/template-parts/emails/{$template_name}.php");

    if (file_exists($template_path)) {
        // Passa $args diretamente para o template sem usar extract()
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
// HELPER: OBTER IP DO CLIENTE
// ============================================

/**
 * Obtém o IP real do cliente, considerando proxies confiáveis.
 *
 * @return string IP do cliente
 */
function theme_get_client_ip()
{
    $ip_keys = array(
        'HTTP_CF_CONNECTING_IP', // Cloudflare
        'HTTP_X_FORWARDED_FOR',   // Proxy genérico
        'HTTP_X_REAL_IP',         // Nginx proxy
        'REMOTE_ADDR'             // Fallback
    );

    foreach ($ip_keys as $key) {
        if (!empty($_SERVER[$key])) {
            $ip = $_SERVER[$key];

            // Se for X-Forwarded-For, pode conter múltiplos IPs (pega o primeiro)
            if ($key === 'HTTP_X_FORWARDED_FOR' && strpos($ip, ',') !== false) {
                $ip_list = explode(',', $ip);
                $ip = trim($ip_list[0]);
            }

            // Validar se é IP válido
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return $ip;
            }
        }
    }

    // Fallback: retornar REMOTE_ADDR mesmo que seja privado
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

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

    // Verificar se email já existe (mensagem genérica para prevenir user enumeration)
    if (email_exists($email)) {
        wp_send_json_error(array(
            'message' => 'Não foi possível criar sua conta. Tente outro e-mail ou faça login.',
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
    $ip_address = theme_get_client_ip();
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

    // Incrementar contador de tentativas (antes de verificar usuário)
    $new_attempts = $attempts ? $attempts + 1 : 1;
    set_transient($transient_key, $new_attempts, 15 * MINUTE_IN_SECONDS);

    // Verificar se usuário existe
    $user = get_user_by('email', $email);

    // SEMPRE retornar sucesso (previne user enumeration)
    // Mas só envia email se usuário existir
    if ($user) {
        // Gerar token de reset
        $reset_key = get_password_reset_key($user);

        if (!is_wp_error($reset_key)) {
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
            wp_mail($to, $subject, $message, $headers);
        }
    }

    // Sempre retornar sucesso (mesmo se usuário não existir)
    wp_send_json_success(array(
        'message' => 'Se o e-mail estiver cadastrado, você receberá um link de recuperação.',
    ));
}
add_action('wp_ajax_nopriv_password_reset_request', 'theme_ajax_password_reset_request');

function theme_ajax_password_reset_confirm()
{
    // Verificar nonce (CSRF protection)
    check_ajax_referer('auth_nonce', 'nonce');

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

    // SEGURANÇA: Se usuário está logado, verificar se o token pertence a ele
    if (is_user_logged_in()) {
        $current_user_id = get_current_user_id();
        if ($current_user_id != $user->ID) {
            wp_send_json_error(array(
                'message' => 'Este link de recuperação pertence a outro usuário. Faça logout primeiro.',
            ));
        }
    }

    // Redefinir senha (já invalida o token automaticamente)
    reset_password($user, $password);

    // Garantir invalidação do token (segurança extra)
    global $wpdb;
    $wpdb->update(
        $wpdb->users,
        array('user_activation_key' => ''),
        array('ID' => $user->ID)
    );

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
    if ($comment->user_id !== $current_user_id) {
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

/**
 * AJAX: Submeter comentário
 */
function theme_ajax_submit_comment()
{
    // Verificar nonce
    check_ajax_referer('comment_nonce', 'nonce');

    // Verificar se usuário está logado
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Você precisa estar logado para comentar.'), 401);
    }

    // Validar campos obrigatórios
    $comment_post_ID = isset($_POST['comment_post_ID']) ? intval($_POST['comment_post_ID']) : 0;
    $comment_content = isset($_POST['comment']) ? trim($_POST['comment']) : '';
    $comment_parent = isset($_POST['comment_parent']) ? intval($_POST['comment_parent']) : 0;

    if (empty($comment_content)) {
        wp_send_json_error(array('message' => 'O comentário não pode estar vazio.'), 400);
    }

    if (!$comment_post_ID || !get_post($comment_post_ID)) {
        wp_send_json_error(array('message' => 'Post inválido.'), 400);
    }

    // Verificar se comentários estão abertos
    if (!comments_open($comment_post_ID)) {
        wp_send_json_error(array('message' => 'Os comentários estão fechados para este post.'), 403);
    }

    // Se for resposta, verificar se comentário pai existe
    if ($comment_parent > 0) {
        $parent_comment = get_comment($comment_parent);
        if (!$parent_comment || $parent_comment->comment_post_ID != $comment_post_ID) {
            wp_send_json_error(array('message' => 'Comentário pai inválido.'), 400);
        }
    }

    // Preparar dados do comentário
    $current_user = wp_get_current_user();
    $commentdata = array(
        'comment_post_ID' => $comment_post_ID,
        'comment_content' => $comment_content,
        'comment_parent' => $comment_parent,
        'user_id' => $current_user->ID,
        'comment_author' => $current_user->display_name,
        'comment_author_email' => $current_user->user_email,
        'comment_approved' => 1, // Auto-aprovar comentários de usuários logados
    );

    // Inserir comentário
    $comment_id = wp_new_comment($commentdata, true);

    if (is_wp_error($comment_id)) {
        wp_send_json_error(array('message' => 'Erro ao criar comentário: ' . $comment_id->get_error_message()), 500);
    }

    // Retornar sucesso
    wp_send_json_success(array(
        'message' => 'Comentário publicado com sucesso!',
        'comment_id' => $comment_id,
        'reload' => true
    ));
}
add_action('wp_ajax_submit_comment', 'theme_ajax_submit_comment');

function theme_styles()
{
    // CSS compilado pelo Webpack (já incluirá Bulma)
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

    // Script de comentários apenas em posts com comentários
    if (is_singular() && comments_open()) {
        wp_enqueue_script('comments', get_template_directory_uri() . '/resources/dist/javascript/comments.min.js', array(), '1.0.0', [
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

// Limpar cache de queries quando posts forem publicados/atualizados
function theme_clear_post_caches($post_id)
{
    // Limpar cache de posts relacionados
    delete_transient('related_posts_' . $post_id);

    // Limpar cache de últimos posts para todos os posts
    global $wpdb;
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_latest_posts_%'");
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_latest_posts_%'");

    // Limpar cache de posts do autor
    $author_id = get_post_field('post_author', $post_id);
    $wpdb->query($wpdb->prepare(
        "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
        '_transient_author_posts_%_' . $author_id
    ));
    $wpdb->query($wpdb->prepare(
        "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
        '_transient_timeout_author_posts_%_' . $author_id
    ));
}
add_action('save_post', 'theme_clear_post_caches');
add_action('delete_post', 'theme_clear_post_caches');

// Helper para queries com cache
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


// Helper para nome de categoria (converte "Uncategorized" para "Rabiscos")
function theme_get_category_name($category)
{
    $default_category_id = get_option('default_category');
    return ($category->term_id === $default_category_id) ? 'Rabiscos' : $category->name;
}

// Função helper para obter categorias formatadas
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
        global $wpdb;
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
        $user_condition = $wpdb->prepare("(comment_approved = '1' OR (comment_approved = '0' AND user_id = %d))", $current_user_id);
        $clauses['where'] = str_replace(
            "comment_approved = '1'",
            $user_condition,
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
    if ($parent_comment->user_id === $comment->user_id) {
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

    // Lógica PHP fica separada no topo
    $is_post_author = ($comment->user_id == get_the_author_meta('ID'));
    $avatar_size = 32; // Tamanho reduzido para economizar espaço

    // Prepara link de resposta para não poluir o HTML abaixo
    $reply_args = array_merge($args, array(
        'depth' => $depth,
        'max_depth' => $args['max_depth'],
        'reply_text' => 'Responder',
        'add_below' => 'comment',
    ));
    ?>

    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class('compact-comment mb-1', $comment); ?>>

        <div class="media mb-0 pt-2 pb-2" style="border: none;">

            <div class="media-left mr-2">
                <figure class="image is-32x32">
                    <?php echo get_avatar($comment, $avatar_size, '', '', array('class' => 'is-rounded')); ?>
                </figure>
            </div>

            <div class="media-content">

                <div class="content is-size-7 mb-1">
                    <strong><?php echo get_comment_author_link($comment); ?></strong>

                    <?php if ($is_post_author): ?>
                        <span class="tag is-primary is-rounded ml-1"
                            style="font-size: 0.6rem; height: 1.5em; padding: 0 0.5em;">Autor</span>
                    <?php endif; ?>

                    <span style="opacity: 0.6;" class="mx-1">•</span>

                    <time style="opacity: 0.7;" datetime="<?php comment_time('c'); ?>">
                        <?php printf('%s', get_comment_date()); ?>
                    </time>
                </div>

                <div class="content is-size-6 mb-1 text-break">
                    <?php if ('0' === $comment->comment_approved): ?>
                        <p class="is-size-7" style="font-style: italic; opacity: 0.8;">Aguardando moderação.</p>
                    <?php endif; ?>

                    <?php comment_text(); ?>
                </div>

                <div class="is-size-7">
                    <?php
                    // O WP retorna o link como string, então apenas imprimimos o resultado
                    comment_reply_link($reply_args);
                    ?>

                    <?php if (is_user_logged_in() && $comment->user_id === get_current_user_id()): ?>
                        <span style="opacity: 0.5;" class="mx-1">|</span>
                        <button type="button" class="button-reset delete-comment-btn" data-comment-id="<?php comment_ID(); ?>"
                            style="border:none; background:none; padding:0; color: inherit; cursor: pointer; font-size: inherit; opacity: 0.8;">
                            Excluir
                        </button>
                    <?php endif; ?>
                </div>

            </div>
        </div>

        <?php
}
