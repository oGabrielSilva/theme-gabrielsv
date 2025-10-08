export function initSearchModal(): void {
  const navbarSearchInput = document.getElementById('navbar-search-input') as HTMLInputElement | null;
  const searchModal = document.getElementById('searchModal');
  const modalSearchInput = document.getElementById('modal-search-input') as HTMLInputElement | null;

  if (!navbarSearchInput || !searchModal || !modalSearchInput) {
    return;
  }

  const modalInstance = new bootstrap.Modal(searchModal);

  navbarSearchInput.addEventListener('focus', function () {
    modalInstance.show();
  });

  searchModal.addEventListener('shown.bs.modal', function () {
    navbarSearchInput.blur();
    modalSearchInput.focus();
  });
}
