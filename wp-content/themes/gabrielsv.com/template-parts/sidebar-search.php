<?php
/**
 * Formulário de Busca
 * Formulário simples para busca no blog
 */
?>

<section class="mb-6">
    <h2 class="title is-6 has-text-weight-bold mb-3">Faça uma busca</h2>
    <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" data-search-form>
        <div class="field has-addons">
            <div class="control is-expanded">
                <input class="input" type="search" name="s" placeholder="Busque por palavras-chave"
                    aria-label="Buscar no blog" value="<?php echo get_search_query(); ?>">
            </div>
            <div class="control">
                <button class="button is-primary" type="submit" aria-label="Buscar" data-search-submit>
                    <span class="icon is-small">
                        <?php get_template_part('template-parts/icons/search'); ?>
                    </span>
                </button>
            </div>
        </div>
    </form>
</section>