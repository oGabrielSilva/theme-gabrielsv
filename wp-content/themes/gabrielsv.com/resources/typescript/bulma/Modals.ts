/**
 * Inicializa o sistema de modais do Bulma
 * Suporta abertura via data-attribute, fechamento com ESC e click no background
 */
export function initBulmaModals(): void {
  // Abrir modals via data-modal-target ou data-modal-open
  document.querySelectorAll<HTMLElement>('[data-modal-target], [data-modal-open]').forEach((trigger) => {
    trigger.addEventListener('click', (e) => {
      e.preventDefault();
      const targetId = trigger.getAttribute('data-modal-target') || trigger.getAttribute('data-modal-open');
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
 * Abre um modal específico com animação de fade-in no fundo e slide-in no card
 */
export function openModal(modal: HTMLElement): void {
  const modalCard = modal.querySelector<HTMLElement>('.modal-card, .modal-content');

  // Resetar classes de animação de saída
  modal.classList.remove('has-fade-out');
  if (modalCard) {
    modalCard.classList.remove('has-slide-out-up');
  }

  // Adicionar classes de animação de entrada
  modal.classList.add('is-active', 'has-fade-in');
  if (modalCard) {
    modalCard.classList.add('has-slide-in-down');
  }

  document.documentElement.classList.add('is-clipped');
}

/**
 * Fecha um modal específico com animação de fade-out no fundo e slide-out no card
 */
export function closeModal(modal: HTMLElement): void {
  const modalCard = modal.querySelector<HTMLElement>('.modal-card, .modal-content');

  // Resetar classes de animação de entrada
  modal.classList.remove('has-fade-in');
  if (modalCard) {
    modalCard.classList.remove('has-slide-in-down');
  }

  // Adicionar classes de animação de saída
  modal.classList.add('has-fade-out');
  if (modalCard) {
    modalCard.classList.add('has-slide-out-up');
  }

  setTimeout(() => {
    // Limpar todas as classes após a animação
    modal.classList.remove('is-active', 'has-fade-out');
    if (modalCard) {
      modalCard.classList.remove('has-slide-out-up');
    }

    // Apenas remover 'is-clipped' se não houver outros modais ativos
    if (document.querySelectorAll('.modal.is-active').length === 0) {
      document.documentElement.classList.remove('is-clipped');
    }
  }, 300); // Duração da animação
}

/**
 * Fecha todos os modais abertos
 */
export function closeAllModals(): void {
  document.querySelectorAll<HTMLElement>('.modal.is-active').forEach((modal) => {
    closeModal(modal);
  });
}
