/**
 * Sistema de notificações Bulma (substitui toasts Bootstrap)
 */

export type NotificationType =
  | "success"
  | "danger"
  | "warning"
  | "info"
  | "primary"
  | "link";

export interface NotificationOptions {
  message: string;
  type?: NotificationType;
  duration?: number;
  dismissible?: boolean;
}

/**
 * Exibe uma notificação na tela
 */
export function showNotification(options: NotificationOptions | string): void {
  // Permitir passar apenas string (retrocompatibilidade)
  const config: NotificationOptions =
    typeof options === "string"
      ? { message: options, type: "info", duration: 5000, dismissible: true }
      : { type: "info", duration: 5000, dismissible: true, ...options };

  const notification = document.createElement("div");
  notification.className = `notification is-${config.type}`;

  if (config.dismissible) {
    const deleteBtn = document.createElement("button");
    deleteBtn.className = "delete";
    deleteBtn.setAttribute("aria-label", "Fechar");
    notification.appendChild(deleteBtn);
  }

  const messageContainer = document.createElement("span");
  messageContainer.innerHTML = config.message;
  notification.appendChild(messageContainer);

  const container = document.querySelector(".notification-container");
  if (!container) {
    console.error("Notification container not found in DOM");
    return;
  }

  container.appendChild(notification);

  // Handler para fechar
  const deleteBtn = notification.querySelector(".delete");
  const removeNotification = () => {
    notification.style.animation = "fadeOut 0.3s";
    setTimeout(() => notification.remove(), 300);
  };

  if (deleteBtn) {
    deleteBtn.addEventListener("click", removeNotification);
  }

  // Auto-remove
  if (config.duration && config.duration > 0) {
    setTimeout(removeNotification, config.duration);
  }
}

/**
 * Atalhos para facilitar uso
 */
export const showSuccess = (message: string, duration = 5000) =>
  showNotification({ message, type: "success", duration });

export const showError = (message: string, duration = 5000) =>
  showNotification({ message, type: "danger", duration });

export const showWarning = (message: string, duration = 5000) =>
  showNotification({ message, type: "warning", duration });

export const showInfo = (message: string, duration = 5000) =>
  showNotification({ message, type: "info", duration });
