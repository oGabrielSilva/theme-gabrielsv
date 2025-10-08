<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="/" role="banner" aria-label="Gabriel Silva - Página inicial">
            <span class="kb" aria-hidden="true">G</span>
            <span class="visually-hidden">Gabriel Silva - Voltar ao início</span>
        </a>
        <button class="navbar-toggler btn btn-theme border-0 p-2" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#navbar-offcanvas" aria-controls="navbar-offcanvas" aria-expanded="false"
            aria-label="Abrir menu de navegação">

            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-ellipsis-icon lucide-ellipsis">
                <circle cx="12" cy="12" r="1" />
                <circle cx="19" cy="12" r="1" />
                <circle cx="5" cy="12" r="1" />
            </svg>

        </button>

        <div class="collapse navbar-collapse d-none d-lg-block" id="navbar-content-desktop">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="/">Início</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://gabrielsv.com">Quem sou</a>
                </li>
                <?php get_template_part('template-parts/ui/categories-dropdown') ?>
            </ul>

            <div class="d-flex gap-1 align-items-center justify-content-center">
                <form class="d-flex" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                    <div class="input-group input-group-sm">
                        <input class="form-control" type="search" name="s" placeholder="Buscar..." aria-label="Buscar"
                            value="<?php echo get_search_query(); ?>" id="navbar-search-input" readonly>
                    </div>
                </form>

                <div class="btn-group btn-group-sm">
                    <?php get_template_part('template-parts/ui/theme-button') ?>

                    <?php get_template_part('template-parts/ui/github-button') ?>
                </div>

                <?php get_template_part('template-parts/user-dropdown') ?>
            </div>
        </div>
    </div>
</nav>

<div class="offcanvas offcanvas-end" tabindex="-1" id="navbar-offcanvas" aria-labelledby="navbar-offcanvas-label">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="navbar-offcanvas-label">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Fechar menu"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="/">Início</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="https://gabrielsv.com">Quem sou</a>
            </li>
            <?php get_template_part('template-parts/ui/categories-dropdown') ?>
        </ul>

        <form class="mt-3" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
            <div class="input-group input-group-sm">
                <input class="form-control" type="search" name="s" placeholder="Buscar..." aria-label="Buscar"
                    value="<?php echo get_search_query(); ?>">
                <button class="btn btn-outline-secondary" type="submit" aria-label="Buscar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-search-icon lucide-search">
                        <path d="m21 21-4.34-4.34" />
                        <circle cx="11" cy="11" r="8" />
                    </svg>
                </button>
            </div>
        </form>

        <div class="mt-3 pt-3 border-top d-flex flex-column gap-2">
            <?php get_template_part('template-parts/ui/theme-button') ?>

            <?php get_template_part('template-parts/ui/github-button') ?>

            <?php get_template_part('template-parts/user-dropdown') ?>
        </div>
    </div>
</div>

<form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchModalLabel">Faça uma busca</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar busca"></button>
                </div>
                <div class="modal-body">
                    <input class="form-control" type="search" name="s" placeholder="O que você procura?"
                        aria-label="Buscar" value="<?php echo get_search_query(); ?>" id="modal-search-input" autofocus>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-theme" type="submit" aria-label="Buscar">
                        Buscar

                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-search-icon lucide-search ms-2">
                            <path d="m21 21-4.34-4.34" />
                            <circle cx="11" cy="11" r="8" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>