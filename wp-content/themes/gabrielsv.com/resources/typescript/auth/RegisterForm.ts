import type { WordPressAjaxResponse } from '../types/wordpress';
import { showSuccess, showError } from '../utils/notifications';

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
      this.form.reportValidity();
      return;
    } else {
      this.passwordConfirmInput.setCustomValidity('');
    }

    // Validação HTML5
    if (!this.form.checkValidity()) {
      this.form.reportValidity();
      return;
    }

    // Desabilitar botão e mostrar loading (Bulma)
    this.submitBtn.disabled = true;
    this.submitBtn.classList.add('is-loading');
    const originalText = this.submitBtn.textContent;
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
        showSuccess(data.data.message || 'Conta criada com sucesso! Redirecionando...');
        setTimeout(() => {
          window.location.href = data.data.redirect || '/';
        }, 2000);
      } else {
        showError(data.data.message || 'Erro ao criar conta. Tente novamente.');
      }
    } catch (error) {
      showError('Erro de conexão. Tente novamente.');
    } finally {
      this.submitBtn.disabled = false;
      this.submitBtn.classList.remove('is-loading');
      this.submitBtn.textContent = originalText || 'Criar conta';
    }
  }

  private resetForm(): void {
    this.form.reset();
    this.submitBtn.disabled = false;
    this.submitBtn.classList.remove('is-loading');
    this.submitBtn.textContent = 'Criar conta';
  }
}
