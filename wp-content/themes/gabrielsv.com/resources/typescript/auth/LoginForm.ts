import type { WordPressAjaxResponse } from '../types/wordpress';
import { showSuccess, showError } from '../utils/notifications';

interface LoginResponse {
  message: string;
  redirect?: string;
}

export class LoginForm {
  private form: HTMLFormElement;
  private submitBtn: HTMLButtonElement;
  private usernameInput: HTMLInputElement;
  private passwordInput: HTMLInputElement;
  private rememberInput: HTMLInputElement;

  constructor() {
    const form = document.getElementById('auth-form') as HTMLFormElement | null;
    const submitBtn = document.getElementById('auth-submit') as HTMLButtonElement | null;
    const usernameInput = document.getElementById('auth-username') as HTMLInputElement | null;
    const passwordInput = document.getElementById('auth-password') as HTMLInputElement | null;
    const rememberInput = document.getElementById('auth-remember') as HTMLInputElement | null;

    if (!form || !submitBtn || !usernameInput || !passwordInput || !rememberInput) {
      throw new Error('Login form elements not found');
    }

    this.form = form;
    this.submitBtn = submitBtn;
    this.usernameInput = usernameInput;
    this.passwordInput = passwordInput;
    this.rememberInput = rememberInput;

    this.setupEventListeners();
  }

  private setupEventListeners(): void {
    this.form.addEventListener('submit', this.handleSubmit.bind(this));
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
    this.submitBtn.textContent = 'Entrando...';

    try {
      const formData = new FormData();
      formData.append('action', 'custom_login');
      formData.append('username', this.usernameInput.value);
      formData.append('password', this.passwordInput.value);
      formData.append('remember', this.rememberInput.checked ? '1' : '0');
      formData.append('nonce', authData.nonce);

      // Adicionar redirect_to se presente na URL
      const urlParams = new URLSearchParams(window.location.search);
      const redirectTo = urlParams.get('redirect_to');
      if (redirectTo) {
        formData.append('redirect_to', redirectTo);
      }

      const response = await fetch(authData.ajaxUrl, {
        method: 'POST',
        body: formData,
        credentials: 'same-origin',
      });

      const data: WordPressAjaxResponse<LoginResponse> = await response.json();

      if (data.success) {
        showSuccess('Login realizado com sucesso!');
        setTimeout(() => {
          window.location.href = data.data.redirect || '/';
        }, 500);
      } else {
        showError(data.data.message || 'Erro ao fazer login. Tente novamente.');
      }
    } catch (error) {
      showError('Erro de conexão. Tente novamente.');
    } finally {
      this.submitBtn.disabled = false;
      this.submitBtn.classList.remove('is-loading');
      this.submitBtn.textContent = originalText || 'Entrar';
    }
  }
}
