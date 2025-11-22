import { closeModal } from '../bulma/Modals';
import { showNotification } from '../utils/notifications';

declare const commentsData: {
  ajaxUrl: string;
  nonce: string;
};

export class SubmitComment {
  private form: HTMLFormElement | null;
  private submitButton: HTMLButtonElement | null;
  private replyModal: HTMLElement | null;

  constructor() {
    this.form = document.getElementById('commentform') as HTMLFormElement;
    this.replyModal = document.getElementById('replyModal');
    this.submitButton = null;

    if (this.form) {
      this.setupEventListeners();
    }
  }

  private setupEventListeners(): void {
    if (!this.form) return;

    this.form.addEventListener('submit', this.handleSubmit.bind(this));
  }

  private async handleSubmit(e: Event): Promise<void> {
    e.preventDefault();
    e.stopPropagation();

    if (!this.form) return;

    // Encontrar botão de submit
    this.submitButton = this.form.querySelector('button[type="submit"]');

    // Validar textarea
    const textarea = this.form.querySelector<HTMLTextAreaElement>('#comment');
    if (!textarea || !textarea.value.trim()) {
      showNotification({ message: 'O comentário não pode estar vazio.', type: 'warning' });
      textarea?.focus();
      return;
    }

    // Desabilitar botão e mostrar loading
    if (this.submitButton) {
      this.submitButton.disabled = true;
      this.submitButton.classList.add('is-loading');
    }

    // Preparar dados do formulário
    const formData = new FormData(this.form);
    formData.append('action', 'submit_comment');
    formData.append('nonce', commentsData.nonce);

    try {
      const response = await fetch(commentsData.ajaxUrl, {
        method: 'POST',
        body: formData,
      });

      const data = await response.json();

      if (data.success) {
        showNotification({
          message: data.data.message || 'Comentário publicado com sucesso!',
          type: 'success'
        });

        // Fechar modal se estiver aberto
        if (this.replyModal) {
          closeModal(this.replyModal);
        }

        // Recarregar página após 1 segundo
        setTimeout(() => {
          window.location.reload();
        }, 1000);
      } else {
        showNotification({
          message: data.data?.message || 'Erro ao publicar comentário.',
          type: 'danger'
        });

        // Reabilitar botão
        if (this.submitButton) {
          this.submitButton.disabled = false;
          this.submitButton.classList.remove('is-loading');
        }
      }
    } catch (error) {
      console.error('Error submitting comment:', error);
      showNotification({
        message: 'Erro ao publicar comentário. Tente novamente.',
        type: 'danger'
      });

      // Reabilitar botão
      if (this.submitButton) {
        this.submitButton.disabled = false;
        this.submitButton.classList.remove('is-loading');
      }
    }
  }
}
