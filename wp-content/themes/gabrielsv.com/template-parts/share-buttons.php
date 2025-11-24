<?php
/**
 * Botões de Compartilhamento
 * - Modo dropdown (single): mostra dropdown com opções
 * - Modo direct (outros): usa Web Share API ou copia link automaticamente
 */

$post_url = urlencode(get_permalink());
$post_title = urlencode(get_the_title());
$post_excerpt = urlencode(wp_trim_words(get_the_excerpt(), 20));
$share_mode = is_single() ? 'dropdown' : 'direct';
?>

<div class="dropdown is-right share-buttons-dropdown" data-share-wrapper
    data-post-url="<?php echo esc_attr(get_permalink()); ?>" data-post-title="<?php echo esc_attr(get_the_title()); ?>"
    data-post-excerpt="<?php echo esc_attr(wp_trim_words(get_the_excerpt(), 20)); ?>"
    data-share-mode="<?php echo esc_attr($share_mode); ?>">

    <div class="dropdown-trigger">
        <button type="button" class="button is-small share-trigger" aria-haspopup="true" aria-controls="share-dropdown"
            aria-label="Compartilhar artigo" title="Compartilhar" data-share-trigger>
            <span class="icon is-small">
                <?php get_template_part('template-parts/icons/share'); ?>
            </span>

            <?php if (is_single()): ?>
                <span>Compartilhar</span>
            <?php endif; ?>
        </button>
    </div>

    <div class="dropdown-menu" id="share-dropdown" role="menu">
        <div class="dropdown-content">

            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $post_url; ?>" class="dropdown-item"
                target="_blank" rel="noopener noreferrer" aria-label="Compartilhar no LinkedIn">
                <span class="icon is-small">
                    <?php get_template_part('template-parts/icons/linkedin'); ?>
                </span>
                <span>LinkedIn</span>
            </a>

            <a href="https://twitter.com/intent/tweet?url=<?php echo $post_url; ?>&text=<?php echo $post_title; ?>"
                class="dropdown-item" target="_blank" rel="noopener noreferrer" aria-label="Compartilhar no Twitter">
                <span class="icon is-small">
                    <?php get_template_part('template-parts/icons/twitter'); ?>
                </span>
                <span>Twitter</span>
            </a>

            <a href="https://api.whatsapp.com/send?text=<?php echo $post_title; ?>%20<?php echo $post_url; ?>"
                class="dropdown-item" target="_blank" rel="noopener noreferrer" aria-label="Compartilhar no WhatsApp">
                <span class="icon is-small">
                    <?php get_template_part('template-parts/icons/whatsapp'); ?>
                </span>
                <span>WhatsApp</span>
            </a>

            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post_url; ?>" class="dropdown-item"
                target="_blank" rel="noopener noreferrer" aria-label="Compartilhar no Facebook">
                <span class="icon is-small">
                    <?php get_template_part('template-parts/icons/facebook'); ?>
                </span>
                <span>Facebook</span>
            </a>

            <hr class="dropdown-divider">

            <a href="#" class="dropdown-item" data-copy-link aria-label="Copiar link do artigo">
                <span class="icon is-small">
                    <?php get_template_part('template-parts/icons/clipboard'); ?>
                </span>
                <span class="copy-link-text">Copiar link</span>
            </a>

            <hr class="dropdown-divider" data-web-share-divider>

            <a href="#" class="dropdown-item" data-web-share aria-label="Mais opções de compartilhamento">
                <span class="icon is-small">
                    <?php get_template_part('template-parts/icons/share'); ?>
                </span>
                <span>Mais opções</span>
            </a>
        </div>
    </div>
</div>