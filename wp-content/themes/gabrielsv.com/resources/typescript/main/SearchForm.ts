export class SearchForm {
  private forms: NodeListOf<HTMLFormElement>;

  constructor() {
    // Suporta tanto o form do modal (ID) quanto forms com data-attribute
    const modalForm = document.getElementById('search-form') as HTMLFormElement;
    const dataForms = document.querySelectorAll<HTMLFormElement>('[data-search-form]');

    // Combinar ambos em um array
    const allForms: HTMLFormElement[] = [];
    if (modalForm) allForms.push(modalForm);
    dataForms.forEach(form => allForms.push(form));

    // Converter para NodeList simulado
    this.forms = allForms as any;

    this.init();
  }

  private init(): void {
    if (this.forms.length === 0) return;

    this.forms.forEach((form) => {
      this.setupForm(form);
    });
  }

  private setupForm(form: HTMLFormElement): void {
    const submitBtn = form.querySelector<HTMLButtonElement>('[data-search-submit]') ||
                     form.querySelector<HTMLButtonElement>('#search-submit-btn');

    if (!submitBtn) return;

    form.addEventListener('submit', () => {
      this.handleSubmit(submitBtn);
    });
  }

  private handleSubmit(submitBtn: HTMLButtonElement): void {
    // Não prevenir o submit - deixar o form redirecionar normalmente
    // Apenas adicionar loading indicator no botão
    submitBtn.disabled = true;
    submitBtn.classList.add('is-loading');
  }
}
