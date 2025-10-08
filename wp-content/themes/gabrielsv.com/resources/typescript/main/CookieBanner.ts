export function initCookieBanner(): void {
  const banner = document.getElementById('cookie-banner');
  const acceptBtn = document.getElementById('cookie-accept-btn');

  if (!banner || !acceptBtn) {
    return;
  }

  const cookieName = 'theme_cookies_accepted';

  function hasCookie(): boolean {
    return document.cookie.split(';').some((cookie) => cookie.trim().startsWith(`${cookieName}=`));
  }

  function setCookie(): void {
    const expires = new Date();
    expires.setTime(expires.getTime() + 365 * 24 * 60 * 60 * 1000);
    document.cookie = `${cookieName}=1;expires=${expires.toUTCString()};path=/;SameSite=Lax`;
  }

  if (!hasCookie()) {
    setTimeout(() => {
      banner.style.display = 'block';
    }, 1500);

    acceptBtn.addEventListener('click', () => {
      setCookie();
      banner.style.display = 'none';
    });

    setTimeout(() => {
      if (!hasCookie()) {
        setCookie();
        banner.style.display = 'none';
      }
    }, 30000);
  }
}
