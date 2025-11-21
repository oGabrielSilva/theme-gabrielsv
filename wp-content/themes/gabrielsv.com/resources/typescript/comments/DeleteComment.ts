import type { WordPressAjaxResponse } from '../types/wordpress';
import { showSuccess, showError } from '../utils/notifications';
import { openModal, closeModal } from '../bulma/Modals';

interface DeleteCommentResponse {
  message: string;
}

export class DeleteComment {
  private commentToDelete: string | null = null;
  private deleteModal: HTMLElement;
  private confirmBtn: HTMLButtonElement;
  private cancelBtn: HTMLElement;

  constructor() {
    const deleteModal = document.getElementById('deleteCommentModal');
    const confirmBtn = document.getElementById('confirmDeleteCommentBtn') as HTMLButtonElement | null;
    const cancelBtn = document.querySelector<HTMLElement>('#deleteCommentModal .delete, #deleteCommentModal [data-modal-close]');

    if (!deleteModal || !confirmBtn || !cancelBtn) {
      throw new Error('Delete comment elements not found');
    }

    this.deleteModal = deleteModal;
    this.confirmBtn = confirmBtn;
    this.cancelBtn = cancelBtn;

    this.setupEventListeners();
  }

  private setupEventListeners(): void {
    // Abrir modal de confirmação
    document.addEventListener('click', this.handleDeleteClick.bind(this));

    // Confirmar deleção
    this.confirmBtn.addEventListener('click', this.handleConfirmDelete.bind(this));

    // Limpar ao fechar modal (Bulma - observar quando perde classe is-active)
    const observer = new MutationObserver((mutations) => {
      mutations.forEach((mutation) => {
        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
          const target = mutation.target as HTMLElement;
          if (!target.classList.contains('is-active')) {
            this.resetModal();
          }
        }
      });
    });
    observer.observe(this.deleteModal, { attributes: true });
  }

  private handleDeleteClick(e: Event): void {
    const deleteBtn = (e.target as HTMLElement).closest<HTMLElement>('.delete-comment-btn');
    if (!deleteBtn) return;

    e.preventDefault();

    this.commentToDelete = deleteBtn.getAttribute('data-comment-id');

    // Abrir modal (Bulma)
    openModal(this.deleteModal);
  }

  private async handleConfirmDelete(): Promise<void> {
    if (!this.commentToDelete) return;

    // Desabilitar botões (Bulma)
    this.confirmBtn.disabled = true;
    this.confirmBtn.classList.add('is-loading');
    const originalText = this.confirmBtn.textContent;
    this.confirmBtn.textContent = 'Deletando...';
    this.cancelBtn.setAttribute('disabled', 'true');

    try {
      const formData = new FormData();
      formData.append('action', 'delete_user_comment');
      formData.append('comment_id', this.commentToDelete);
      formData.append('nonce', commentsData.nonce);

      const response = await fetch(commentsData.ajaxUrl, {
        method: 'POST',
        body: formData,
        credentials: 'same-origin',
      });

      // Verificar se a resposta é JSON
      const contentType = response.headers.get('content-type');
      if (!contentType || !contentType.includes('application/json')) {
        throw new Error('Resposta inválida do servidor');
      }

      const data: WordPressAjaxResponse<DeleteCommentResponse> = await response.json();

      if (data.success) {
        // Fechar modal (Bulma)
        closeModal(this.deleteModal);

        // Remover comentário do DOM com animação
        const commentElement = document.getElementById('comment-' + this.commentToDelete);
        if (commentElement) {
          commentElement.style.transition = 'opacity 0.3s';
          commentElement.style.opacity = '0';
          setTimeout(() => {
            commentElement.remove();
            showSuccess('Comentário deletado com sucesso');
          }, 300);
        }

        this.commentToDelete = null;
      } else {
        showError(data.data.message || 'Erro ao deletar comentário');
      }
    } catch (error) {
      showError('Erro de conexão. Tente novamente.');
    } finally {
      this.confirmBtn.disabled = false;
      this.confirmBtn.classList.remove('is-loading');
      this.confirmBtn.textContent = originalText || 'Deletar';
      this.cancelBtn.removeAttribute('disabled');
    }
  }

  private resetModal(): void {
    this.commentToDelete = null;
    this.confirmBtn.disabled = false;
    this.confirmBtn.classList.remove('is-loading');
    this.confirmBtn.textContent = 'Deletar';
    this.cancelBtn.removeAttribute('disabled');
  }
}
