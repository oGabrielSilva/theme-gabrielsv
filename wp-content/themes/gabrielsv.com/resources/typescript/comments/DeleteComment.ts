import type { WordPressAjaxResponse } from '../types/wordpress';
import { showToast } from '../utils/toast';

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
    const cancelBtn = document.querySelector<HTMLElement>('#deleteCommentModal [data-bs-dismiss="modal"]');

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

    // Limpar ao fechar modal
    this.deleteModal.addEventListener('hidden.bs.modal', this.resetModal.bind(this));
  }

  private handleDeleteClick(e: Event): void {
    const deleteBtn = (e.target as HTMLElement).closest<HTMLElement>('.delete-comment-btn');
    if (!deleteBtn) return;

    e.preventDefault();

    this.commentToDelete = deleteBtn.getAttribute('data-comment-id');

    const modal = new bootstrap.Modal(this.deleteModal);
    modal.show();
  }

  private async handleConfirmDelete(): Promise<void> {
    if (!this.commentToDelete) return;

    // Desabilitar botões
    this.confirmBtn.disabled = true;
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
        // Fechar modal
        const modalInstance = bootstrap.Modal.getInstance(this.deleteModal);
        modalInstance?.hide();

        // Remover comentário do DOM com animação
        const commentElement = document.getElementById('comment-' + this.commentToDelete);
        if (commentElement) {
          commentElement.style.transition = 'opacity 0.3s';
          commentElement.style.opacity = '0';
          setTimeout(() => {
            commentElement.remove();
            showToast('Comentário deletado com sucesso', 'success');
          }, 300);
        }

        this.commentToDelete = null;
      } else {
        showToast(data.data.message || 'Erro ao deletar comentário', 'danger');
      }
    } catch (error) {
      showToast('Erro de conexão. Tente novamente.', 'danger');
    } finally {
      this.confirmBtn.disabled = false;
      this.confirmBtn.textContent = 'Deletar';
      this.cancelBtn.removeAttribute('disabled');
    }
  }

  private resetModal(): void {
    this.commentToDelete = null;
    this.confirmBtn.disabled = false;
    this.confirmBtn.textContent = 'Deletar';
    this.cancelBtn.removeAttribute('disabled');
  }
}
