<?php
/**
 * Cookie Notice Banner
 */

// Não exibir se cookie já foi aceito
if (isset($_COOKIE['theme_cookies_accepted'])) {
    return;
}

// Não exibir na própria página de cookies
global $post;
if ($post && $post->post_name === 'politica-de-cookies') {
    return;
}
?>

<div id="cookie-banner" class="is-fixed"
    style="background-color: rgba(0,0,0,0.75); z-index: 9998; display: none; bottom: 0; left: 0; right: 0;" role="banner">

    <div class="container py-3">
        <div class="columns is-variable is-3 is-vcentered is-mobile">
            <div class="column">
                <p class="has-text-white is-size-7 mb-0">
                    Este site usa cookies.
                    <a href="<?php echo esc_url(home_url('/politica-de-cookies')); ?>"
                        class="has-text-white" style="text-decoration: underline;">
                        Saiba mais
                    </a>
                </p>
            </div>
            <div class="column is-narrow">
                <button type="button" id="cookie-accept-btn" class="button is-primary is-small">
                    Ok
                </button>
            </div>
        </div>
    </div>
</div>