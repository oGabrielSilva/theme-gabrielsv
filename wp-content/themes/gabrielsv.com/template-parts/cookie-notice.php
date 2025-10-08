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

<div id="cookie-banner" class="position-fixed bottom-0 start-0 end-0 shadow-lg"
    style="background-color: rgba(00,00,00,0.75); z-index: 9998; display: none;" role="banner">

    <div class="container py-3">
        <div class="row align-items-center g-3">
            <div class="col-lg-10 col-md-9">
                <p class="text-white small mb-0">
                    Este site usa cookies.
                    <a href="<?php echo esc_url(home_url('/politica-de-cookies')); ?>"
                        class="text-white text-decoration-underline">
                        Saiba mais
                    </a>
                </p>
            </div>
            <div class="col-lg-2 col-md-3 text-md-end">
                <button type="button" id="cookie-accept-btn" class="btn btn-primary btn-sm w-100 w-md-auto">
                    Ok
                </button>
            </div>
        </div>
    </div>
</div>