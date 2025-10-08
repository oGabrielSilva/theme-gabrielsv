/**
 * Custom Event Types
 */

export interface ThemeChangedDetail {
  theme: 'light' | 'dark';
}

export interface ThemeChangedEvent extends CustomEvent {
  detail: ThemeChangedDetail;
}

declare global {
  interface DocumentEventMap {
    themeChanged: ThemeChangedEvent;
  }
}

export {};
