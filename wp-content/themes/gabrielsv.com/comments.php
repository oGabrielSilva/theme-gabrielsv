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
                'title_reply_after' => '</h3><p class="has-text-grey mb-3">' . sprintf('O que você está pensando, %s?', esc_html($user_first_name)) . '</p>',
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
            <div class="notification is-light mt-4">
                <p class="mb-0">
                    Você precisa estar <a href="<?php echo home_url('/auth?redirect_to=' . urlencode(get_permalink())); ?>" class="has-text-link">logado</a> para
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
            <section class="modal-card-body has-text-centered py-4">
                <p class="mb-4">Você precisa estar logado para responder comentários.</p>
                <a href="<?php echo home_url('/auth?redirect_to=' . urlencode(get_permalink())); ?>" class="button is-primary">
                    Fazer Login
                </a>
            </section>
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
                <div class="box has-background-light mb-3">
                    <div class="is-flex is-align-items-start mb-2" style="gap: 0.5rem;">
                        <img id="replyAuthorAvatar" src="" alt="" class="is-rounded" width="40" height="40">
                        <div>
                            <strong id="replyAuthorName" class="is-block"></strong>
                            <small class="has-text-grey" id="replyCommentDate"></small>
                        </div>
                    </div>
                    <div id="replyCommentContent" class="is-size-7 has-text-grey"></div>
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
            <section class="modal-card-body has-text-centered pb-4">
                <p class="has-text-grey mb-4">Esta ação não pode ser desfeita.</p>
                <button type="button" class="button is-light mr-2" data-modal-close="deleteCommentModal">Cancelar</button>
                <button type="button" class="button is-danger" id="confirmDeleteCommentBtn">Deletar</button>
            </section>
        </div>
    </div>

</div>
