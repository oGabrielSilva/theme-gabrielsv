/**
 * WordPress Global Variables (localized scripts)
 */

export interface WordPressAjaxResponse<T = Record<string, unknown>> {
  success: boolean;
  data: T;
}

export interface AuthData {
  ajaxUrl: string;
  nonce: string;
}

export interface ProfileData {
  ajaxUrl: string;
  nonce: string;
}

export interface CommentsData {
  ajaxUrl: string;
  nonce: string;
}

// Global variables exposed by WordPress
declare global {
  const authData: AuthData;
  const profileData: ProfileData;
  const commentsData: CommentsData;

  // Theme Manager
  interface Window {
    themeManager?: import('../main/ThemeManager').ThemeManager;
  }
}

export {};
