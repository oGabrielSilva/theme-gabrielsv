import type { WordPressAjaxResponse } from "../types/wordpress";
import { showSuccess, showError } from "../utils/notifications";

interface ResetPasswordResponse {
  message: string;
}

export class ResetPasswordForm {
  private form: HTMLFormElement;
  private submitBtn: HTMLButtonElement;
  private passwordInput: HTMLInputElement;
  private passwordConfirmInput: HTMLInputElement;

  constructor() {
    const form = document.getElementById(
      "reset-password-form"
    ) as HTMLFormElement | null;
    const submitBtn = document.getElementById(
      "reset-password-submit"
    ) as HTMLButtonElement | null;
    const passwordInput = document.getElementById(
      "reset-password"
    ) as HTMLInputElement | null;
    const passwordConfirmInput = document.getElementById(
      "reset-password-confirm"
    ) as HTMLInputElement | null;

    if (!form || !submitBtn || !passwordInput || !passwordConfirmInput) {
      throw new Error("Reset password form elements not found");
    }

    this.form = form;
    this.submitBtn = submitBtn;
    this.passwordInput = passwordInput;
    this.passwordConfirmInput = passwordConfirmInput;

    this.setupEventListeners();
  }

  private setupEventListeners(): void {
    this.form.addEventListener("submit", this.handleSubmit.bind(this));
  }

  private async handleSubmit(e: Event): Promise<void> {
    e.preventDefault();

    // Validar senhas
    if (this.passwordInput.value !== this.passwordConfirmInput.value) {
      this.passwordConfirmInput.setCustomValidity("As senhas não coincidem.");
      this.form.reportValidity();
      return;
    } else {
      this.passwordConfirmInput.setCustomValidity("");
    }

    // Validação HTML5
    if (!this.form.checkValidity()) {
      this.form.reportValidity();
      return;
    }

    // Desabilitar botão e mostrar loading (Bulma)
    this.submitBtn.disabled = true;
    this.submitBtn.classList.add("is-loading");
    const originalText = this.submitBtn.textContent;
    this.submitBtn.textContent = "Redefinindo...";

    try {
      const formData = new FormData(this.form);
      formData.append("action", "password_reset_confirm");
      formData.append("nonce", authData.nonce);

      const response = await fetch(authData.ajaxUrl, {
        method: "POST",
        body: formData,
        credentials: "same-origin",
      });

      const data: WordPressAjaxResponse<ResetPasswordResponse> =
        await response.json();

      if (data.success) {
        showSuccess(data.data.message || "Senha redefinida com sucesso!");
        setTimeout(() => {
          window.location.href = "/auth";
        }, 1000);
      } else {
        showError(data.data.message || "Erro ao redefinir senha. Tente novamente.");
      }
    } catch (error) {
      showError("Erro de conexão. Tente novamente.");
    } finally {
      this.submitBtn.disabled = false;
      this.submitBtn.classList.remove("is-loading");
      this.submitBtn.textContent = originalText || "Redefinir senha";
    }
  }
}
