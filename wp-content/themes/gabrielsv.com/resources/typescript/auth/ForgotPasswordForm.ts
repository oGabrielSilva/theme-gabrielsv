import type { WordPressAjaxResponse } from '../types/wordpress';
import { showSuccess, showError } from '../utils/notifications';
import { closeModal } from '../bulma/Modals';

interface ForgotPasswordResponse {
  message: string;
}

export class ForgotPasswordForm {
  private form: HTMLFormElement;
  private submitBtn: HTMLButtonElement;
  private emailInput: HTMLInputElement;
  private modal: HTMLElement;

  constructor() {
    const form = document.getElementById('forgot-password-form') as HTMLFormElement | null;
    const submitBtn = document.getElementById('forgot-password-submit') as HTMLButtonElement | null;
    const emailInput = document.getElementById('forgot-password-email') as HTMLInputElement | null;
    const modal = document.getElementById('forgotPasswordModal');

    if (!form || !submitBtn || !emailInput || !modal) {
      throw new Error('Forgot password form elements not found');
    }

    this.form = form;
    this.submitBtn = submitBtn;
    this.emailInput = emailInput;
    this.modal = modal;

    this.setupEventListeners();
  }

  private setupEventListeners(): void {
    this.form.addEventListener('submit', this.handleSubmit.bind(this));

    // Limpar ao fechar modal (Bulma - observar quando perde classe is-active)
    const observer = new MutationObserver((mutations) => {
      mutations.forEach((mutation) => {
        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
          const target = mutation.target as HTMLElement;
          if (!target.classList.contains('is-active')) {
            this.resetForm();
          }
        }
      });
    });
    observer.observe(this.modal, { attributes: true });
  }

  private async handleSubmit(e: Event): Promise<void> {
    e.preventDefault();

    // Validação HTML5
    if (!this.form.checkValidity()) {
      this.form.reportValidity();
      return;
    }

    // Desabilitar botão e mostrar loading (Bulma)
    this.submitBtn.disabled = true;
    this.submitBtn.classList.add('is-loading');
    const originalText = this.submitBtn.textContent;
    this.submitBtn.textContent = 'Enviando...';

    try {
      const formData = new FormData();
      formData.append('action', 'password_reset_request');
      formData.append('email', this.emailInput.value);
      formData.append('nonce', authData.nonce);

      const response = await fetch(authData.ajaxUrl, {
        method: 'POST',
        body: formData,
        credentials: 'same-origin',
      });

      const data: WordPressAjaxResponse<ForgotPasswordResponse> = await response.json();

      if (data.success) {
        showSuccess(data.data.message || 'Link de recuperação enviado! Verifique seu e-mail.');

        // Fechar modal (Bulma)
        closeModal(this.modal);

        this.resetForm();
      } else {
        showError(data.data.message || 'Erro ao enviar link de recuperação. Tente novamente.');
      }
    } catch (error) {
      showError('Erro de conexão. Tente novamente.');
    } finally {
      this.submitBtn.disabled = false;
      this.submitBtn.classList.remove('is-loading');
      this.submitBtn.textContent = originalText || 'Enviar link de recuperação';
    }
  }

  private resetForm(): void {
    this.form.reset();
    this.submitBtn.disabled = false;
    this.submitBtn.classList.remove('is-loading');
    this.submitBtn.textContent = 'Enviar link de recuperação';
  }
}
