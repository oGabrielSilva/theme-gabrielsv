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
        <h2 class="comments-title h4 fw-bold mb-4">
            Comentários
        </h2>

        <ol class="comment-list list-unstyled">
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
                'title_reply_before' => '<h3 class="h5 fw-bold mb-2">',
                'title_reply_after' => '</h3><p class="text-muted mb-3">' . sprintf('O que você está pensando, %s?', esc_html($user_first_name)) . '</p>',
                'title_reply_to' => 'Responder para %s',
                'cancel_reply_link' => 'Cancelar',
                'label_submit' => 'Comentar',
                'class_submit' => 'btn btn-primary',
                'logged_in_as' => '', // Remove a linha "Conectado como..."
                'comment_field' => '<div class="mb-3">
                    <label for="comment" class="form-label visually-hidden">Seu comentário</label>
                    <textarea id="comment" name="comment" class="form-control" rows="5" placeholder="Compartilhe sua opinião..." required></textarea>
                </div>',
                'submit_button' => '<button type="submit" name="submit" id="submit" class="btn btn-primary">%4$s</button>',
                'comment_notes_before' => '', // Remove "campos obrigatórios"
                'comment_notes_after' => '',
            ));
        else:
            ?>
            <div class="alert alert-secondary mt-4">
                <p class="mb-0">
                    Você precisa estar <a href="<?php echo home_url('/auth?redirect_to=' . urlencode(get_permalink())); ?>" class="alert-link">logado</a> para
                    comentar.
                </p>
            </div>
            <?php
        endif;
    endif;
    ?>

    <?php // Modal: Login Obrigatório ?>
    <div class="modal fade" id="loginRequiredModal" tabindex="-1" aria-labelledby="loginRequiredModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginRequiredModalLabel">Login Necessário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <p class="mb-4">Você precisa estar logado para responder comentários.</p>
                    <a href="<?php echo home_url('/auth?redirect_to=' . urlencode(get_permalink())); ?>" class="btn btn-primary">
                        Fazer Login
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php // Modal para Responder Comentário ?>
    <div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="replyModalLabel">Responder comentário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <?php // Comentário Original ?>
                    <div class="card border mb-3">
                        <div class="card-body bg-body-secondary">
                            <div class="d-flex gap-2 align-items-start mb-2">
                                <img id="replyAuthorAvatar" src="" alt="" class="rounded-circle" width="40" height="40">
                                <div>
                                    <strong id="replyAuthorName" class="d-block"></strong>
                                    <small class="text-body-secondary" id="replyCommentDate"></small>
                                </div>
                            </div>
                            <div id="replyCommentContent" class="small text-body-secondary"></div>
                        </div>
                    </div>

                    <?php // Formulário de Resposta ?>
                    <div id="replyFormContainer"></div>
                </div>
            </div>
        </div>
    </div>

    <?php // Modal de Confirmação: Deletar Comentário ?>
    <div class="modal fade" id="deleteCommentModal" tabindex="-1" aria-labelledby="deleteCommentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="deleteCommentModalLabel">Deletar comentário?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body text-center pb-4">
                    <p class="text-muted mb-4">Esta ação não pode ser desfeita.</p>
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteCommentBtn">Deletar</button>
                </div>
            </div>
        </div>
    </div>

    <?php // Toast Container ?>
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <div id="commentToast" class="toast align-items-center border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="commentToastBody"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fechar"></button>
            </div>
        </div>
    </div>

</div>