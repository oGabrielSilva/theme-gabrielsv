import { initSearchModal } from "./main/SearchModal";
import { ThemeManager } from "./main/ThemeManager";
import { initCookieBanner } from "./main/CookieBanner";
import { ScrollToTop } from "./main/ScrollToTop";

document.addEventListener("DOMContentLoaded", () => {
  const themeManager = new ThemeManager();
  window.themeManager = themeManager;
  themeManager.init();

  initSearchModal();
  initCookieBanner();
  new ScrollToTop();
});
