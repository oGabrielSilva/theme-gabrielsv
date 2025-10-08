import type { ThemeChangedDetail } from '../types/events';

type Theme = 'light' | 'dark';

export class ThemeManager {
  private readonly storageKey = '_sv_theme';
  private readonly defaultTheme: Theme = 'light';
  private currentTheme: Theme;

  constructor() {
    this.currentTheme = this.getStoredTheme() || this.getSystemTheme();
  }

  public init(): void {
    this.applyTheme();
    this.setupEventListeners();
  }

  /**
   * Alterna entre o tema 'light' e 'dark'.
   */
  public toggleTheme(): void {
    const newTheme: Theme = this.currentTheme === 'light' ? 'dark' : 'light';
    this.setTheme(newTheme);
  }

  /**
   * Define um tema específico, atualiza o estado e o DOM.
   */
  public setTheme(theme: Theme): void {
    this.currentTheme = theme;
    localStorage.setItem(this.storageKey, theme);
    this.applyTheme();

    // Dispara um evento customizado para que outras partes do app possam reagir
    const event = new CustomEvent<ThemeChangedDetail>('themeChanged', {
      detail: { theme: this.currentTheme },
    });
    document.dispatchEvent(event);
  }

  private applyTheme(): void {
    document.documentElement.setAttribute('data-bs-theme', this.currentTheme);
    this.updateThemeButtons();
    this.updateThemeLinks();
    this.updateToggleIcons();
    this.updateAccessibilityStates();
  }

  private updateToggleIcons(): void {
    const toggleButtons = this.getToggleButtons();
    toggleButtons.forEach((button) => {
      const lightIcon = button.querySelector<HTMLElement>('[data-icon="light"]');
      const darkIcon = button.querySelector<HTMLElement>('[data-icon="dark"]');

      if (lightIcon && darkIcon) {
        lightIcon.classList.toggle('d-none', this.currentTheme === 'dark');
        darkIcon.classList.toggle('d-none', this.currentTheme === 'light');
      }
    });
  }

  private updateAccessibilityStates(): void {
    const toggleButtons = this.getToggleButtons();
    toggleButtons.forEach((button) => {
      const isPressed = this.currentTheme === 'dark';
      button.setAttribute('aria-pressed', String(isPressed));
      const newLabel = `Alternar para tema ${isPressed ? 'claro' : 'escuro'}`;
      button.setAttribute('aria-label', newLabel);
    });
  }

  private updateThemeButtons(): void {
    const themeButtons = document.querySelectorAll<HTMLElement>('.btn-theme');
    const buttonClass = this.currentTheme === 'light' ? 'btn-light' : 'btn-dark';
    const removeClass = this.currentTheme === 'light' ? 'btn-dark' : 'btn-light';

    themeButtons.forEach((button) => {
      button.classList.remove(removeClass);
      button.classList.add(buttonClass);
    });
  }

  private updateThemeLinks(): void {
    const themeLinks = document.querySelectorAll<HTMLElement>('.link-theme');
    const linkClass = this.currentTheme === 'light' ? 'link-dark' : 'link-light';
    const removeClass = this.currentTheme === 'light' ? 'link-light' : 'link-dark';

    themeLinks.forEach((link) => {
      link.classList.remove(removeClass);
      link.classList.add(linkClass);
    });
  }

  private setupEventListeners(): void {
    this.getToggleButtons().forEach((button) => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        this.toggleTheme();
      });
    });

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
      if (!localStorage.getItem(this.storageKey)) {
        const systemTheme: Theme = e.matches ? 'dark' : 'light';
        this.currentTheme = systemTheme;
        this.applyTheme();
      }
    });
  }

  private getStoredTheme(): Theme | null {
    const stored = localStorage.getItem(this.storageKey);
    if (stored === 'light' || stored === 'dark') {
      return stored;
    }
    return null;
  }

  private getSystemTheme(): Theme {
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
      return 'dark';
    }
    return this.defaultTheme;
  }

  private getToggleButtons(): NodeListOf<HTMLElement> {
    return document.querySelectorAll('[data-func="toggle-theme"]');
  }
}
