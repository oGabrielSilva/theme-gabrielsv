export class SearchForm {
  private form: HTMLFormElement | null;
  private submitBtn: HTMLButtonElement | null;

  constructor() {
    this.form = document.getElementById('search-form') as HTMLFormElement;
    this.submitBtn = document.getElementById('search-submit-btn') as HTMLButtonElement;

    if (this.form && this.submitBtn) {
      this.setupEventListeners();
    }
  }

  private setupEventListeners(): void {
    if (!this.form) return;

    this.form.addEventListener('submit', this.handleSubmit.bind(this));
  }

  private handleSubmit(e: Event): void {
    // Não prevenir o submit - deixar o form redirecionar normalmente
    // Apenas adicionar loading indicator no botão

    if (this.submitBtn) {
      this.submitBtn.disabled = true;
      this.submitBtn.classList.add('is-loading');
    }
  }
}
