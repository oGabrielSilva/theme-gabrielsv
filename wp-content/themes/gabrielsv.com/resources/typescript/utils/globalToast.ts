export type ToastType = 'success' | 'danger' | 'warning' | 'info';

export function showGlobalToast(message: string, type: ToastType = 'success'): void {
  const toastEl = document.getElementById('globalToast');
  const toastBody = document.getElementById('globalToastBody');

  if (!toastEl || !toastBody) {
    return;
  }

  // Remover classes anteriores
  toastEl.classList.remove('text-bg-success', 'text-bg-danger', 'text-bg-warning', 'text-bg-info');

  // Adicionar classe de cor
  const bgClass = `text-bg-${type}`;
  toastEl.classList.add(bgClass);

  // Definir mensagem
  toastBody.textContent = message;

  // Mostrar toast
  const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
  toast.show();
}
