<nav class="navbar is-fixed-top" role="navigation" aria-label="Navegação principal">
    <div class="navbar-brand">
        <a class="navbar-item" href="/" role="banner" aria-label="Gabriel Silva - Página inicial">
            <span class="kb" aria-hidden="true">G</span>
            <span class="is-sr-only">Gabriel Silva - Voltar ao início</span>
        </a>

        <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarMenu">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>

        <div id="navbarMenu" class="navbar-menu">
            <div class="navbar-start">
                <a class="navbar-item" href="/">Início</a>
                <a class="navbar-item" href="<?php echo esc_url(home_url('/sobre')); ?>">Sobre</a>
                <a class="navbar-item" href="<?php echo esc_url(home_url('/contato')); ?>">Contato</a>
                <?php get_template_part('template-parts/ui/categories-dropdown') ?>
            </div>

            <div class="navbar-end">
                <div class="navbar-item">
                    <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                        <div class="field has-addons">
                            <div class="control is-small">
                                <input class="input is-small" type="search" name="s" placeholder="Buscar..."
                                       aria-label="Buscar" value="<?php echo get_search_query(); ?>"
                                       id="navbar-search-input" readonly>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="navbar-item">
                    <div class="buttons are-small">
                        <?php get_template_part('template-parts/ui/theme-button') ?>
                        <?php get_template_part('template-parts/ui/github-button') ?>
                    </div>
                </div>

                <div class="navbar-item">
                    <?php get_template_part('template-parts/user-dropdown') ?>
                </div>
            </div>
        </div>
</nav>

<?php // Modal de busca ?>
<form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" id="search-form">
    <div class="modal" id="searchModal">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Faça uma busca</p>
                <button class="delete" aria-label="close" type="button"></button>
            </header>
            <section class="modal-card-body">
                <div class="field">
                    <div class="control">
                        <input class="input" type="search" name="s" placeholder="O que você procura?"
                               aria-label="Buscar" value="<?php echo get_search_query(); ?>"
                               id="modal-search-input" autofocus>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <button class="button is-primary" type="submit" aria-label="Buscar" id="search-submit-btn">
                    Buscar
                    <span class="icon is-small ml-2">
                        <?php get_template_part('template-parts/icons/search'); ?>
                    </span>
                </button>
            </footer>
        </div>
    </div>
</form>
