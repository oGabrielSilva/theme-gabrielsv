/**
 * Inicializa o comportamento do navbar-burger do Bulma
 * Toggle entre menu aberto/fechado em mobile
 */
export function initNavbarBurger(): void {
  const burgers = document.querySelectorAll<HTMLElement>('.navbar-burger');

  burgers.forEach((burger) => {
    burger.addEventListener('click', () => {
      const targetId = burger.getAttribute('data-target');
      if (!targetId) return;

      const menu = document.getElementById(targetId);
      if (!menu) return;

      // Toggle classes is-active
      burger.classList.toggle('is-active');
      menu.classList.toggle('is-active');
    });
  });
}
