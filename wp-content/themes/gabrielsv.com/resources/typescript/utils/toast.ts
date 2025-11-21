/**
 * @deprecated Use showNotification from notifications.ts instead
 * Este arquivo mantém compatibilidade com código legado
 */

import { showNotification } from './notifications';

export type ToastType = 'success' | 'danger';

/**
 * @deprecated Use showSuccess() or showError() from notifications.ts
 */
export function showToast(message: string, type: ToastType = 'success'): void {
  // Redirecionar para o novo sistema de notificações Bulma
  showNotification({
    message,
    type: type === 'success' ? 'success' : 'danger',
    duration: 3000,
    dismissible: true,
  });
}
