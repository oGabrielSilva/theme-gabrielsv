import type { WordPressAjaxResponse } from '../types/wordpress';
import { showGlobalToast } from '../utils/globalToast';

interface RegisterResponse {
  message: string;
  redirect?: string;
}

export class RegisterForm {
  private form: HTMLFormElement;
  private submitBtn: HTMLButtonElement;
  private usernameInput: HTMLInputElement;
  private emailInput: HTMLInputElement;
  private passwordInput: HTMLInputElement;
  private passwordConfirmInput: HTMLInputElement;
  private modal: HTMLElement;

  constructor() {
    const form = document.getElementById('register-form') as HTMLFormElement | null;
    const submitBtn = document.getElementById('register-submit') as HTMLButtonElement | null;
    const usernameInput = document.getElementById('register-username') as HTMLInputElement | null;
    const emailInput = document.getElementById('register-email') as HTMLInputElement | null;
    const passwordInput = document.getElementById('register-password') as HTMLInputElement | null;
    const passwordConfirmInput = document.getElementById('register-password-confirm') as HTMLInputElement | null;
    const modal = document.getElementById('registerModal');

    if (!form || !submitBtn || !usernameInput || !emailInput || !passwordInput || !passwordConfirmInput || !modal) {
      throw new Error('Register form elements not found');
    }

    this.form = form;
    this.submitBtn = submitBtn;
    this.usernameInput = usernameInput;
    this.emailInput = emailInput;
    this.passwordInput = passwordInput;
    this.passwordConfirmInput = passwordConfirmInput;
    this.modal = modal;

    this.setupEventListeners();
  }

  private setupEventListeners(): void {
    // Formatar username automaticamente
    this.usernameInput.addEventListener('input', this.formatUsername.bind(this));

    // Submit
    this.form.addEventListener('submit', this.handleSubmit.bind(this));

    // Limpar ao fechar modal
    this.modal.addEventListener('hidden.bs.modal', this.resetForm.bind(this));
  }

  private formatUsername(e: Event): void {
    const input = e.target as HTMLInputElement;
    let value = input.value;
    value = value.toLowerCase();
    value = value.replace(/[^a-z0-9_-]/g, '');
    value = value.slice(0, 20);
    input.value = value;
  }

  private async handleSubmit(e: Event): Promise<void> {
    e.preventDefault();

    // Validar senhas
    if (this.passwordInput.value !== this.passwordConfirmInput.value) {
      this.passwordConfirmInput.setCustomValidity('As senhas não coincidem.');
      this.form.classList.add('was-validated');
      return;
    } else {
      this.passwordConfirmInput.setCustomValidity('');
    }

    // Validação HTML5
    if (!this.form.checkValidity()) {
      this.form.classList.add('was-validated');
      return;
    }

    // Desabilitar botão e mostrar loading
    this.submitBtn.disabled = true;
    this.submitBtn.textContent = 'Criando conta...';

    try {
      const formData = new FormData();
      formData.append('action', 'custom_register');
      formData.append('username', this.usernameInput.value);
      formData.append('email', this.emailInput.value);
      formData.append('password', this.passwordInput.value);
      formData.append('nonce', authData.nonce);

      const response = await fetch(authData.ajaxUrl, {
        method: 'POST',
        body: formData,
        credentials: 'same-origin',
      });

      const data: WordPressAjaxResponse<RegisterResponse> = await response.json();

      if (data.success) {
        showGlobalToast(data.data.message || 'Conta criada com sucesso! Redirecionando...', 'success');
        setTimeout(() => {
          window.location.href = data.data.redirect || '/';
        }, 2000);
      } else {
        showGlobalToast(data.data.message || 'Erro ao criar conta. Tente novamente.', 'danger');
      }
    } catch (error) {
      showGlobalToast('Erro de conexão. Tente novamente.', 'danger');
    } finally {
      this.submitBtn.disabled = false;
      this.submitBtn.textContent = 'Criar conta';
    }
  }

  private resetForm(): void {
    this.form.reset();
    this.form.classList.remove('was-validated');
    this.submitBtn.disabled = false;
    this.submitBtn.textContent = 'Criar conta';
  }
}
