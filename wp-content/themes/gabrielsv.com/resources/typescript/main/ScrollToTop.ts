export class ScrollToTop {
  private button: HTMLElement | null;
  private scrollThreshold: number;

  constructor(scrollThreshold: number = 300) {
    this.button = document.getElementById('scrollToTop');
    this.scrollThreshold = scrollThreshold;
    this.init();
  }

  private init(): void {
    if (!this.button) return;

    window.addEventListener('scroll', () => this.toggleVisibility());
    this.button.addEventListener('click', () => this.scrollToTop());
  }

  private toggleVisibility(): void {
    if (!this.button) return;

    if (window.scrollY > this.scrollThreshold) {
      this.button.style.display = 'flex';
      this.button.style.alignItems = 'center';
      this.button.style.justifyContent = 'center';
    } else {
      this.button.style.display = 'none';
    }
  }

  private scrollToTop(): void {
    window.scrollTo({
      top: 0,
      behavior: 'smooth',
    });
  }
}
