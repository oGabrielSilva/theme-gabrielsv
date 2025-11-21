/**
 * Inicializa o sistema de modais do Bulma
 * Suporta abertura via data-attribute, fechamento com ESC e click no background
 */
export function initBulmaModals(): void {
  // Abrir modals via data-modal-target
  document.querySelectorAll<HTMLElement>('[data-modal-target]').forEach((trigger) => {
    trigger.addEventListener('click', (e) => {
      e.preventDefault();
      const targetId = trigger.getAttribute('data-modal-target');
      if (!targetId) return;

      const modal = document.getElementById(targetId);
      if (modal) {
        openModal(modal);
      }
    });
  });

  // Fechar modals ao clicar no X ou background
  document.querySelectorAll<HTMLElement>('.modal .delete, .modal-background').forEach((closeBtn) => {
    closeBtn.addEventListener('click', () => {
      const modal = closeBtn.closest<HTMLElement>('.modal');
      if (modal) {
        closeModal(modal);
      }
    });
  });

  // Fechar com tecla ESC
  document.addEventListener('keydown', (e: KeyboardEvent) => {
    if (e.key === 'Escape') {
      document.querySelectorAll<HTMLElement>('.modal.is-active').forEach((modal) => {
        closeModal(modal);
      });
    }
  });
}

/**
 * Abre um modal específico
 */
export function openModal(modal: HTMLElement): void {
  modal.classList.add('is-active');
  document.documentElement.classList.add('is-clipped'); // Prevenir scroll do body
}

/**
 * Fecha um modal específico
 */
export function closeModal(modal: HTMLElement): void {
  modal.classList.remove('is-active');
  document.documentElement.classList.remove('is-clipped');
}

/**
 * Fecha todos os modais abertos
 */
export function closeAllModals(): void {
  document.querySelectorAll<HTMLElement>('.modal.is-active').forEach((modal) => {
    closeModal(modal);
  });
}
