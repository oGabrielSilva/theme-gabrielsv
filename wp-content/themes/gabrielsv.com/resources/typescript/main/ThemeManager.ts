import type { ThemeChangedDetail } from "../types/events";

type Theme = "light" | "dark";

export class ThemeManager {
  private readonly storageKey = "_sv_theme";
  private readonly defaultTheme: Theme = "light";
  private currentTheme: Theme;
  private lightIconNode: HTMLElement | null = null;
  private darkIconNode: HTMLElement | null = null;

  constructor() {
    this.currentTheme = this.getStoredTheme() || this.getSystemTheme();
  }

  public init(): void {
    this.loadIconReferences();
    this.applyTheme();
    this.setupEventListeners();
  }

  private loadIconReferences(): void {
    const firstButton = this.getToggleButtons()[0];
    if (!firstButton) return;

    const lightIcon = firstButton.querySelector<HTMLElement>(
      '[data-icon="light"]'
    );
    const darkIcon =
      firstButton.querySelector<HTMLElement>('[data-icon="dark"]');

    if (lightIcon) {
      this.lightIconNode = lightIcon.cloneNode(true) as HTMLElement;
      this.lightIconNode.classList.remove('is-hidden');
      lightIcon.remove();
    }

    if (darkIcon) {
      this.darkIconNode = darkIcon.cloneNode(true) as HTMLElement;
      this.darkIconNode.classList.remove('is-hidden');
      darkIcon.remove();
    }
  }

  public toggleTheme(): void {
    const newTheme: Theme = this.currentTheme === "light" ? "dark" : "light";
    this.setTheme(newTheme);
  }

  public setTheme(theme: Theme): void {
    this.currentTheme = theme;
    localStorage.setItem(this.storageKey, theme);
    this.applyTheme();

    const event = new CustomEvent<ThemeChangedDetail>("themeChanged", {
      detail: { theme: this.currentTheme },
    });
    document.dispatchEvent(event);
  }

  private applyTheme(): void {
    document.documentElement.setAttribute("data-theme", this.currentTheme);
    this.updateToggleIcons();
    this.updateAccessibilityStates();
  }

  private updateToggleIcons(): void {
    const toggleButtons = this.getToggleButtons();
    toggleButtons.forEach((button) => {
      button.querySelectorAll("[data-icon]").forEach((icon) => icon.remove());

      const iconNode =
        this.currentTheme === "light" ? this.darkIconNode : this.lightIconNode;

      if (iconNode) {
        const clonedIcon = iconNode.cloneNode(true) as HTMLElement;
        button.appendChild(clonedIcon);
      }
    });
  }

  private updateAccessibilityStates(): void {
    const toggleButtons = this.getToggleButtons();
    toggleButtons.forEach((button) => {
      const isPressed = this.currentTheme === "dark";
      button.setAttribute("aria-pressed", String(isPressed));
      const newLabel = `Alternar para tema ${isPressed ? "claro" : "escuro"}`;
      button.setAttribute("aria-label", newLabel);
    });
  }

  private setupEventListeners(): void {
    this.getToggleButtons().forEach((button) => {
      button.addEventListener("click", (e) => {
        e.preventDefault();
        this.toggleTheme();
      });
    });

    window
      .matchMedia("(prefers-color-scheme: dark)")
      .addEventListener("change", (e) => {
        if (!localStorage.getItem(this.storageKey)) {
          const systemTheme: Theme = e.matches ? "dark" : "light";
          this.currentTheme = systemTheme;
          this.applyTheme();
        }
      });
  }

  private getStoredTheme(): Theme | null {
    const stored = localStorage.getItem(this.storageKey);
    if (stored === "light" || stored === "dark") {
      return stored;
    }
    return null;
  }

  private getSystemTheme(): Theme {
    if (
      window.matchMedia &&
      window.matchMedia("(prefers-color-scheme: dark)").matches
    ) {
      return "dark";
    }
    return this.defaultTheme;
  }

  private getToggleButtons(): NodeListOf<HTMLElement> {
    return document.querySelectorAll('[data-func="toggle-theme"]');
  }
}
