import { initCookieBanner } from "./main/CookieBanner";
import { ScrollToTop } from "./main/ScrollToTop";
import { initSearchModal } from "./main/SearchModal";
import { ThemeManager } from "./main/ThemeManager";
// Componentes Bulma
import { initNavbarBurger } from "./bulma/NavbarBurger";
import { initBulmaModals } from "./bulma/Modals";

document.addEventListener("DOMContentLoaded", () => {
  // Inicializar navbar burger (mobile menu) - BULMA
  try {
    initNavbarBurger();
  } catch (error) {
    console.error("NavbarBurger error:", error);
  }

  // Inicializar sistema de modals Bulma
  try {
    initBulmaModals();
  } catch (error) {
    console.error("BulmaModals error:", error);
  }

  // Theme manager (modo escuro/claro)
  try {
    const themeManager = new ThemeManager();
    window.themeManager = themeManager;
    themeManager.init();
  } catch (error) {
    console.error("ThemeManager error:", error);
  }

  // Modal de busca (será atualizado para Bulma em fase posterior)
  try {
    initSearchModal();
  } catch (error) {
    console.error("SearchModal error:", error);
  }

  // Cookie banner
  try {
    initCookieBanner();
  } catch (error) {
    console.error("CookieBanner error:", error);
  }

  // Scroll to top button
  try {
    new ScrollToTop();
  } catch (error) {
    console.error("ScrollToTop error:", error);
  }
});

console.log("main loaded");
