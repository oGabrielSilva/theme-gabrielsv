/**
 * Sistema de Modal para Respostas de Comentários e Deletar Comentário
 */
document.addEventListener('DOMContentLoaded', function() {
    const replyModal = document.getElementById('replyModal');
    if (!replyModal) return;

    // ============================================
    // DELETAR COMENTÁRIO
    // ============================================
    let commentToDelete = null;

    // Função helper para mostrar toast
    function showToast(message, type = 'success') {
        const toastEl = document.getElementById('commentToast');
        const toastBody = document.getElementById('commentToastBody');

        // Remover classes anteriores
        toastEl.classList.remove('text-bg-success', 'text-bg-danger');

        // Adicionar classe de cor
        toastEl.classList.add(type === 'success' ? 'text-bg-success' : 'text-bg-danger');

        // Definir mensagem
        toastBody.textContent = message;

        // Mostrar toast
        const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
        toast.show();
    }

    // Abrir modal de confirmação
    document.addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.delete-comment-btn');
        if (!deleteBtn) return;

        e.preventDefault();

        commentToDelete = deleteBtn.getAttribute('data-comment-id');

        const deleteModal = new bootstrap.Modal(document.getElementById('deleteCommentModal'));
        deleteModal.show();
    });

    // Confirmar deleção
    document.getElementById('confirmDeleteCommentBtn').addEventListener('click', function() {
        if (!commentToDelete) return;

        const confirmBtn = this;
        const cancelBtn = document.querySelector('#deleteCommentModal [data-bs-dismiss="modal"]');

        // Desabilitar botões
        confirmBtn.disabled = true;
        confirmBtn.textContent = 'Deletando...';
        cancelBtn.disabled = true;

        // Preparar FormData
        const formData = new FormData();
        formData.append('action', 'delete_user_comment');
        formData.append('comment_id', commentToDelete);
        formData.append('nonce', commentsData.nonce);

        // Enviar requisição
        fetch(commentsData.ajaxUrl, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(response => {
            // Verificar se a resposta é JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Resposta inválida do servidor');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Fechar modal
                const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteCommentModal'));
                deleteModal.hide();

                // Remover comentário do DOM com animação
                const commentElement = document.getElementById('comment-' + commentToDelete);
                if (commentElement) {
                    commentElement.style.transition = 'opacity 0.3s';
                    commentElement.style.opacity = '0';
                    setTimeout(() => {
                        commentElement.remove();
                        showToast('Comentário deletado com sucesso', 'success');
                    }, 300);
                }

                commentToDelete = null;
            } else {
                showToast(data.data.message || 'Erro ao deletar comentário', 'danger');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showToast('Erro de conexão. Tente novamente.', 'danger');
        })
        .finally(() => {
            confirmBtn.disabled = false;
            confirmBtn.textContent = 'Deletar';
            cancelBtn.disabled = false;
        });
    });

    // Limpar ao fechar modal
    document.getElementById('deleteCommentModal').addEventListener('hidden.bs.modal', function() {
        commentToDelete = null;
        document.getElementById('confirmDeleteCommentBtn').disabled = false;
        document.getElementById('confirmDeleteCommentBtn').textContent = 'Deletar';
        document.querySelector('#deleteCommentModal [data-bs-dismiss="modal"]').disabled = false;
    });

    // ============================================
    // RESPONDER COMENTÁRIO (MODAL)
    // ============================================

    // Armazenar a posição original do formulário
    const originalFormParent = document.getElementById('respond')?.parentElement;

    // Interceptar cliques nos botões de resposta
    document.addEventListener('click', function(e) {
        const replyBtn = e.target.closest('.comment-reply-link');
        if (!replyBtn) return;

        e.preventDefault();
        e.stopPropagation();

        // Capturar dados do comentário
        const commentId = replyBtn.getAttribute('data-comment-id');
        const commentAuthor = replyBtn.getAttribute('data-comment-author');
        const commentAvatar = replyBtn.getAttribute('data-comment-avatar');
        const commentDate = replyBtn.getAttribute('data-comment-date');
        const commentContent = replyBtn.getAttribute('data-comment-content');
        const href = replyBtn.getAttribute('href');

        // Extrair o comment_parent da URL
        const url = new URL(href, window.location.origin);
        const replyToId = url.searchParams.get('replytocom');

        // Atualizar conteúdo do modal com dados do comentário original
        document.getElementById('replyAuthorName').textContent = commentAuthor;
        document.getElementById('replyAuthorAvatar').src = commentAvatar;
        document.getElementById('replyAuthorAvatar').alt = commentAuthor;
        document.getElementById('replyCommentDate').textContent = commentDate;
        document.getElementById('replyCommentContent').textContent = commentContent;

        // Atualizar campo hidden comment_parent
        const respondDiv = document.getElementById('respond');
        if (respondDiv) {
            let parentInput = respondDiv.querySelector('#comment_parent');
            if (parentInput) {
                parentInput.value = replyToId;
            } else {
                // Criar campo se não existir
                parentInput = document.createElement('input');
                parentInput.type = 'hidden';
                parentInput.name = 'comment_parent';
                parentInput.id = 'comment_parent';
                parentInput.value = replyToId;
                respondDiv.querySelector('form').appendChild(parentInput);
            }

            // Mover formulário para o modal
            const container = document.getElementById('replyFormContainer');
            if (container) {
                container.innerHTML = '';
                container.appendChild(respondDiv);

                const title = respondDiv.querySelector('#reply-title');
                if (title) title.style.display = 'none';

                // Botão cancelar fecha o modal
                const cancelBtn = respondDiv.querySelector('#cancel-comment-reply-link');
                if (cancelBtn) {
                    cancelBtn.onclick = function(e) {
                        e.preventDefault();
                        const modal = bootstrap.Modal.getInstance(replyModal);
                        if (modal) modal.hide();
                        return false;
                    };
                }
            }

            // Abrir modal
            const modal = new bootstrap.Modal(replyModal);
            modal.show();
        }

        return false;
    });

    // Retornar formulário ao fechar modal
    replyModal.addEventListener('hidden.bs.modal', function() {
        const respondDiv = document.getElementById('respond');
        const parentInput = respondDiv?.querySelector('#comment_parent');

        // Resetar comment_parent para 0 (comentário raiz)
        if (parentInput) {
            parentInput.value = '0';
        }

        // Retornar formulário ao lugar original
        if (respondDiv && originalFormParent) {
            originalFormParent.appendChild(respondDiv);
        }
    });
});
