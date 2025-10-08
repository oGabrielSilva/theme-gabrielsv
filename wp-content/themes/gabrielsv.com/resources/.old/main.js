(function () {
  class ThemeManager {
    constructor() {
      this.storageKey = '_sv_theme';
      this.defaultTheme = 'light';
      this.currentTheme = this.getStoredTheme() || this.getSystemTheme();
    }

    init() {
      this.applyTheme();
      this.setupEventListeners();
    }

    /**
     * Alterna entre o tema 'light' e 'dark'.
     */
    toggleTheme() {
      const newTheme = this.currentTheme === 'light' ? 'dark' : 'light';
      this.setTheme(newTheme);
    }

    /**
     * Define um tema específico, atualiza o estado e o DOM.
     * @param {string} theme - O tema a ser aplicado ('light' ou 'dark').
     */
    setTheme(theme) {
      this.currentTheme = theme;
      localStorage.setItem(this.storageKey, theme);
      this.applyTheme();

      // Dispara um evento customizado para que outras partes do app possam reagir
      document.dispatchEvent(
        new CustomEvent('themeChanged', {
          detail: { theme: this.currentTheme },
        })
      );
    }

    applyTheme() {
      document.documentElement.setAttribute('data-bs-theme', this.currentTheme);
      this.updateThemeButtons();
      this.updateToggleIcons();
      this.updateAccessibilityStates();
    }

    updateToggleIcons() {
      const toggleButtons = this.getToggleButtons();
      toggleButtons.forEach((button) => {
        const lightIcon = button.querySelector('[data-icon="light"]');
        const darkIcon = button.querySelector('[data-icon="dark"]');

        if (lightIcon && darkIcon) {
          // lightIcon.style.display = this.currentTheme === 'light' ? 'inline-block' : 'none';
          // darkIcon.style.display = this.currentTheme === 'dark' ? 'inline-block' : 'none';
          lightIcon.classList.toggle('d-none', this.currentTheme === 'dark');
          darkIcon.classList.toggle('d-none', this.currentTheme === 'light');
        }
      });
    }

    updateAccessibilityStates() {
      const toggleButtons = this.getToggleButtons();
      toggleButtons.forEach((button) => {
        const isPressed = this.currentTheme === 'dark';
        button.setAttribute('aria-pressed', isPressed);
        const newLabel = `Alternar para tema ${isPressed ? 'claro' : 'escuro'}`;
        button.setAttribute('aria-label', newLabel);
      });
    }

    updateThemeButtons() {
      const themeButtons = document.querySelectorAll('.btn-theme');
      const buttonClass = this.currentTheme === 'light' ? 'btn-light' : 'btn-dark';
      const removeClass = this.currentTheme === 'light' ? 'btn-dark' : 'btn-light';

      themeButtons.forEach((button) => {
        button.classList.remove(removeClass);
        button.classList.add(buttonClass);
      });
    }

    setupEventListeners() {
      this.getToggleButtons().forEach((button) => {
        button.addEventListener('click', (e) => {
          e.preventDefault();
          this.toggleTheme();
        });
      });

      window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        if (!localStorage.getItem(this.storageKey)) {
          const systemTheme = e.matches ? 'dark' : 'light';
          this.currentTheme = systemTheme;
          this.applyTheme();
        }
      });
    }

    getStoredTheme() {
      return localStorage.getItem(this.storageKey);
    }

    getSystemTheme() {
      if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        return 'dark';
      }
      return this.defaultTheme;
    }

    getToggleButtons() {
      return document.querySelectorAll('[data-func="toggle-theme"]');
    }
  }

  function initSearchModal() {
    const navbarSearchInput = document.getElementById('navbar-search-input');
    const searchModal = document.getElementById('searchModal');
    const modalSearchInput = document.getElementById('modal-search-input');

    if (navbarSearchInput && searchModal && modalSearchInput) {
      const modalInstance = new bootstrap.Modal(searchModal);

      navbarSearchInput.addEventListener('focus', function () {
        modalInstance.show();
      });

      searchModal.addEventListener('shown.bs.modal', function () {
        navbarSearchInput.blur();
        modalSearchInput.focus();
      });
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    const themeManager = new ThemeManager();
    window.themeManager = themeManager;
    themeManager.init();

    // Initialize search modal
    initSearchModal();

    document.addEventListener('themeChanged', (e) => {
      console.log(`Theme changed: ${e.detail.theme}`);
    });
  });
})();
