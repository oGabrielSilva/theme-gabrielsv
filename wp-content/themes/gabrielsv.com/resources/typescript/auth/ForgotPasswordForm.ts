import type { WordPressAjaxResponse } from '../types/wordpress';
import { showGlobalToast } from '../utils/globalToast';

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
    this.modal.addEventListener('hidden.bs.modal', this.resetForm.bind(this));
  }

  private async handleSubmit(e: Event): Promise<void> {
    e.preventDefault();

    // Validação HTML5
    if (!this.form.checkValidity()) {
      this.form.classList.add('was-validated');
      return;
    }

    // Desabilitar botão e mostrar loading
    this.submitBtn.disabled = true;
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
        showGlobalToast(data.data.message || 'Link de recuperação enviado! Verifique seu e-mail.', 'success');

        // Fechar modal
        const modalInstance = bootstrap.Modal.getInstance(this.modal);
        modalInstance?.hide();

        this.resetForm();
      } else {
        showGlobalToast(data.data.message || 'Erro ao enviar link de recuperação. Tente novamente.', 'danger');
      }
    } catch (error) {
      showGlobalToast('Erro de conexão. Tente novamente.', 'danger');
    } finally {
      this.submitBtn.disabled = false;
      this.submitBtn.textContent = 'Enviar link de recuperação';
    }
  }

  private resetForm(): void {
    this.form.reset();
    this.form.classList.remove('was-validated');
    this.submitBtn.disabled = false;
    this.submitBtn.textContent = 'Enviar link de recuperação';
  }
}
