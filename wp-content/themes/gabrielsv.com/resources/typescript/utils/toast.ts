export type ToastType = 'success' | 'danger';

export function showToast(message: string, type: ToastType = 'success'): void {
  const toastEl = document.getElementById('commentToast');
  const toastBody = document.getElementById('commentToastBody');

  if (!toastEl || !toastBody) {
    return;
  }

  // Remover classes anteriores
  toastEl.classList.remove('text-bg-success', 'text-bg-danger');

  // Adicionar classe de cor
  toastEl.classList.add(type === 'success' ? 'text-bg-success' : 'text-bg-danger');

  // Definir mensagem
  toastBody.textContent = message;

  // Mostrar toast
  const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
  toast.show();
}
