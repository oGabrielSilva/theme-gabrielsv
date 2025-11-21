/**
 * @deprecated Use showNotification from notifications.ts instead
 * Este arquivo mantém compatibilidade com código legado
 */

import { showNotification, NotificationType } from './notifications';

export type ToastType = 'success' | 'danger' | 'warning' | 'info';

/**
 * @deprecated Use showSuccess(), showError(), showWarning(), or showInfo() from notifications.ts
 */
export function showGlobalToast(message: string, type: ToastType = 'success'): void {
  // Redirecionar para o novo sistema de notificações Bulma
  showNotification({
    message,
    type: type as NotificationType,
    duration: 3000,
    dismissible: true,
  });
}
