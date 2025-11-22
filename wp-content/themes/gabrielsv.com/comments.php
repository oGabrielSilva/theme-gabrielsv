<?php
/**
 * Template de Comentários
 */

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area mt-5">

    <?php if (have_comments()): ?>
        <h2 class="comments-title title is-4 has-text-weight-bold mb-4">
            Comentários
        </h2>

        <ol class="comment-list" style="list-style: none; padding: 0;">
            <?php
            wp_list_comments(array(
                'style' => 'ol',
                'short_ping' => true,
                'avatar_size' => 60,
                'callback' => 'theme_comment_template'
            ));
            ?>
        </ol>

        <?php
        // Paginação de comentários
        if (get_comment_pages_count() > 1 && get_option('page_comments')):
            ?>
            <nav class="comment-navigation">
                <?php theme_bootstrap_pagination(); ?>
            </nav>
        <?php endif; ?>

    <?php endif; ?>

    <?php
    // Formulário de comentários - só mostra se comentários estiverem abertos
    if (comments_open()):
        if (is_user_logged_in()):
            $current_user = wp_get_current_user();
            $user_first_name = $current_user->user_firstname ? $current_user->user_firstname : $current_user->display_name;

            comment_form(array(
                'title_reply' => 'Deixe um comentário',
                'title_reply_before' => '<h3 class="title is-5 has-text-weight-bold mb-2">',
                'title_reply_after' => '</h3><p class="mb-3">' . sprintf('O que você está pensando, %s?', esc_html($user_first_name)) . '</p>',
                'title_reply_to' => 'Responder para %s',
                'cancel_reply_link' => 'Cancelar',
                'label_submit' => 'Comentar',
                'class_submit' => 'button is-primary',
                'logged_in_as' => '', // Remove a linha "Conectado como..."
                'comment_field' => '<div class="field mb-3">
                    <label for="comment" class="label is-sr-only">Seu comentário</label>
                    <div class="control">
                        <textarea id="comment" name="comment" class="textarea" rows="5" placeholder="Compartilhe sua opinião..." required></textarea>
                    </div>
                </div>',
                'submit_button' => '<button type="submit" name="submit" id="submit" class="button is-primary">%4$s</button>',
                'comment_notes_before' => '', // Remove "campos obrigatórios"
                'comment_notes_after' => '',
            ));
        else:
            ?>
            <div class="notification mt-4">
                <p class="mb-0">
                    Você precisa estar <a href="<?php echo home_url('/auth?redirect_to=' . urlencode(get_permalink())); ?>"
                        class="has-text-link">logado</a> para
                    comentar.
                </p>
            </div>
            <?php
        endif;
    endif;
    ?>

    <?php // Modal: Login Obrigatório ?>
    <div class="modal" id="loginRequiredModal">
        <div class="modal-background" data-modal-close="loginRequiredModal"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Login Necessário</p>
                <button class="delete" aria-label="close" data-modal-close="loginRequiredModal"></button>
            </header>
            <section class="modal-card-body has-text-centered">
                <p class="mb-0">Você precisa estar logado para responder comentários.</p>
            </section>
            <footer class="modal-card-foot is-justify-content-center">
                <a href="<?php echo home_url('/auth?redirect_to=' . urlencode(get_permalink())); ?>"
                    class="button is-primary">
                    Fazer Login
                </a>
            </footer>
        </div>
    </div>

    <?php // Modal para Responder Comentário ?>
    <div class="modal" id="replyModal">
        <div class="modal-background" data-modal-close="replyModal"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Responder comentário</p>
                <button class="delete" aria-label="close" data-modal-close="replyModal"></button>
            </header>
            <section class="modal-card-body">
                <?php // Comentário Original ?>
                <div class="card mb-3">
                    <div class="card-content">
                        <div class="media">
                            <div class="media-left">
                                <figure class="image is-48x48">
                                    <img id="replyAuthorAvatar" src="" alt="" class="is-rounded">
                                </figure>
                            </div>
                            <div class="media-content">
                                <p class="title is-6 mb-1" id="replyAuthorName"></p>
                                <p class="subtitle is-7" id="replyCommentDate"></p>
                            </div>
                        </div>
                        <div class="content">
                            <p id="replyCommentContent" class="is-size-7 mb-0"></p>
                        </div>
                    </div>
                </div>

                <?php // Formulário de Resposta ?>
                <div id="replyFormContainer"></div>
            </section>
        </div>
    </div>

    <?php // Modal de Confirmação: Deletar Comentário ?>
    <div class="modal" id="deleteCommentModal">
        <div class="modal-background" data-modal-close="deleteCommentModal"></div>
        <div class="modal-card is-small">
            <header class="modal-card-head">
                <p class="modal-card-title">Deletar comentário?</p>
                <button class="delete" aria-label="close" data-modal-close="deleteCommentModal"></button>
            </header>
            <section class="modal-card-body has-text-centered">
                <p class=" mb-0">Esta ação não pode ser desfeita.</p>
            </section>
            <footer class="modal-card-foot is-justify-content-center">
                <button type="button" class="button is-light mr-2"
                    data-modal-close="deleteCommentModal">Cancelar</button>
                <button type="button" class="button is-danger" id="confirmDeleteCommentBtn">Deletar</button>
            </footer>
        </div>
    </div>

</div>