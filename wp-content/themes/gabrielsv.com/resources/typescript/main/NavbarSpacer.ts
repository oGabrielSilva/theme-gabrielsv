export class NavbarSpacer {
  private navbar: HTMLElement | null;
  private resizeObserver: ResizeObserver | null = null;

  constructor() {
    this.navbar = document.querySelector<HTMLElement>(
      "nav.navbar.is-fixed-top"
    );
  }

  public init(): void {
    if (!this.navbar) {
      console.warn('Navbar with class "navbar is-fixed-top" not found');
      return;
    }

    this.updateSpacing();

    this.setupResizeObserver();
  }

  private updateSpacing(): void {
    if (!this.navbar) return;

    // getBoundingClientRect() é mais preciso que offsetHeight (considera transforms, borders, etc)
    const navbarHeight = this.navbar.getBoundingClientRect().height;

    // Adicionar 2rem extra usando calc()
    document.body.style.paddingTop = `calc(${navbarHeight}px + 2rem)`;
  }

  private setupResizeObserver(): void {
    if (!this.navbar) return;

    // Verificar se ResizeObserver está disponível (suporte moderno)
    if (typeof ResizeObserver !== 'undefined') {
      this.resizeObserver = new ResizeObserver(() => {
        this.updateSpacing();
      });

      this.resizeObserver.observe(this.navbar);
    } else {
      // Fallback para navegadores antigos (IE11, Safari < 13.1)
      console.warn('ResizeObserver not supported, using window resize fallback');
      window.addEventListener('resize', () => this.updateSpacing());
    }
  }

  public destroy(): void {
    if (this.resizeObserver) {
      this.resizeObserver.disconnect();
      this.resizeObserver = null;
    }
  }
}
