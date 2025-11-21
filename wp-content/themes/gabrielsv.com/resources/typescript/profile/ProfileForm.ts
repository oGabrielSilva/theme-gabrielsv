import type { WordPressAjaxResponse } from '../types/wordpress';
import { showSuccess, showError } from '../utils/notifications';

interface ProfileResponse {
  message: string;
}

export class ProfileForm {
  private form: HTMLFormElement;
  private submitBtn: HTMLButtonElement;
  private passwordField: HTMLInputElement;
  private passwordConfirmField: HTMLInputElement;

  constructor() {
    const form = document.getElementById('profile-form') as HTMLFormElement | null;
    const submitBtn = document.getElementById('profile-submit') as HTMLButtonElement | null;
    const passwordField = document.getElementById('profile-password') as HTMLInputElement | null;
    const passwordConfirmField = document.getElementById('profile-password-confirm') as HTMLInputElement | null;

    if (!form || !submitBtn || !passwordField || !passwordConfirmField) {
      throw new Error('Profile form elements not found');
    }

    this.form = form;
    this.submitBtn = submitBtn;
    this.passwordField = passwordField;
    this.passwordConfirmField = passwordConfirmField;

    this.setupEventListeners();
  }

  private setupEventListeners(): void {
    this.form.addEventListener('submit', this.handleSubmit.bind(this));
  }

  private async handleSubmit(e: Event): Promise<void> {
    e.preventDefault();

    // Validar senhas se preenchidas
    if (this.passwordField.value || this.passwordConfirmField.value) {
      if (this.passwordField.value !== this.passwordConfirmField.value) {
        this.passwordConfirmField.setCustomValidity('As senhas não coincidem.');
        this.form.reportValidity();
        return;
      } else {
        this.passwordConfirmField.setCustomValidity('');
      }
    }

    // Validação HTML5
    if (!this.form.checkValidity()) {
      this.form.reportValidity();
      return;
    }

    // Preparar FormData
    const formData = new FormData();
    formData.append('action', 'update_profile');
    formData.append('nonce', profileData.nonce);
    formData.append('first_name', (document.getElementById('profile-first-name') as HTMLInputElement).value);
    formData.append('last_name', (document.getElementById('profile-last-name') as HTMLInputElement).value);
    formData.append('email', (document.getElementById('profile-email') as HTMLInputElement).value);
    formData.append('bio', (document.getElementById('profile-bio') as HTMLTextAreaElement).value);
    formData.append('url', (document.getElementById('profile-url') as HTMLInputElement).value);
    formData.append('twitter', (document.getElementById('profile-twitter') as HTMLInputElement).value);
    formData.append('linkedin', (document.getElementById('profile-linkedin') as HTMLInputElement).value);
    formData.append('github', (document.getElementById('profile-github') as HTMLInputElement).value);

    if (this.passwordField.value) {
      formData.append('password', this.passwordField.value);
    }

    // Desabilitar botão e mostrar loading (Bulma)
    this.submitBtn.disabled = true;
    this.submitBtn.classList.add('is-loading');
    const originalText = this.submitBtn.textContent;
    this.submitBtn.textContent = 'Salvando...';

    try {
      const response = await fetch(profileData.ajaxUrl, {
        method: 'POST',
        body: formData,
        credentials: 'same-origin',
      });

      const data: WordPressAjaxResponse<ProfileResponse> = await response.json();

      if (data.success) {
        showSuccess(data.data.message || 'Perfil atualizado com sucesso!');
        this.passwordField.value = '';
        this.passwordConfirmField.value = '';
        window.scrollTo({ top: 0, behavior: 'smooth' });
      } else {
        showError(data.data.message || 'Erro ao atualizar perfil. Tente novamente.');
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }
    } catch (error) {
      showError('Erro de conexão. Tente novamente.');
      window.scrollTo({ top: 0, behavior: 'smooth' });
    } finally {
      this.submitBtn.disabled = false;
      this.submitBtn.classList.remove('is-loading');
      this.submitBtn.textContent = originalText || 'Salvar alterações';
    }
  }
}
