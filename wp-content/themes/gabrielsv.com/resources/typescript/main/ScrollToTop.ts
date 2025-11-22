export class ScrollToTop {
  private button: HTMLElement | null;
  private scrollThreshold: number;
  private isVisible: boolean = false;

  constructor(scrollThreshold: number = 300) {
    this.button = document.getElementById('scrollToTop');
    this.scrollThreshold = scrollThreshold;

    if (this.button) {
      // Prevenir piscar na tela ao carregar a página
      this.button.style.display = 'none';
    }

    this.init();
  }

  private init(): void {
    if (!this.button) return;

    window.addEventListener('scroll', () => this.toggleVisibility());
    this.button.addEventListener('click', () => this.scrollToTop());
  }

  private toggleVisibility(): void {
    if (!this.button) return;

    const shouldBeVisible = window.scrollY > this.scrollThreshold;

    if (shouldBeVisible && !this.isVisible) {
      this.button.style.display = 'flex';
      this.button.style.alignItems = 'center';
      this.button.style.justifyContent = 'center';
      this.button.classList.remove('has-fade-out');
      this.button.classList.add('has-fade-in');
      this.isVisible = true;
    } else if (!shouldBeVisible && this.isVisible) {
      this.button.classList.remove('has-fade-in');
      this.button.classList.add('has-fade-out');
      setTimeout(() => {
        this.button.style.display = 'none';
      }, 300); // Duração da animação
      this.isVisible = false;
    }
  }

  private scrollToTop(): void {
    window.scrollTo({
      top: 0,
      behavior: 'smooth',
    });
  }
}
