import { openModal } from '../bulma/Modals';

export function initSearchModal(): void {
  const navbarSearchInput = document.getElementById('navbar-search-input') as HTMLInputElement | null;
  const searchModal = document.getElementById('searchModal');
  const modalSearchInput = document.getElementById('modal-search-input') as HTMLInputElement | null;

  if (!navbarSearchInput || !searchModal || !modalSearchInput) {
    return;
  }

  navbarSearchInput.addEventListener('focus', function () {
    // Abrir modal (Bulma)
    openModal(searchModal);

    // Focar no input do modal após abrir
    navbarSearchInput.blur();
    setTimeout(() => {
      modalSearchInput.focus();
    }, 100);
  });
}
