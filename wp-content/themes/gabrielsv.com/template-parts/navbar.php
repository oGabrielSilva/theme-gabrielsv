<nav class="navbar is-fixed-top" role="navigation" aria-label="Navegação principal">
    <div class="container-fluid">
        <div class="navbar-brand">
            <a class="navbar-item" href="/" role="banner" aria-label="Gabriel Silva - Página inicial">
                <span class="kb" aria-hidden="true">G</span>
                <span class="is-sr-only">Gabriel Silva - Voltar ao início</span>
            </a>

            <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarMenu">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>

        <div id="navbarMenu" class="navbar-menu">
            <div class="navbar-start">
                <a class="navbar-item" href="/">Início</a>
                <a class="navbar-item" href="https://gabrielsv.com">Quem sou</a>
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
    </div>
</nav>

<?php // Modal de busca ?>
<form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
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
                <button class="button is-primary" type="submit" aria-label="Buscar">
                    Buscar
                    <span class="icon is-small ml-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m21 21-4.34-4.34" />
                            <circle cx="11" cy="11" r="8" />
                        </svg>
                    </span>
                </button>
            </footer>
        </div>
    </div>
</form>
