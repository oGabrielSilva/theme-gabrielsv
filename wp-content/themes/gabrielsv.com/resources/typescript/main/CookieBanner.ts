export function initCookieBanner(): void {
  const banner = document.getElementById('cookie-banner');
  const acceptBtn = document.getElementById('cookie-accept-btn');

  if (!banner || !acceptBtn) {
    return;
  }

  // Prevenir piscar na tela ao carregar a página
  banner.style.display = 'none';

  const cookieName = 'theme_cookies_accepted';

  function hasCookie(): boolean {
    return document.cookie.split(';').some((cookie) => cookie.trim().startsWith(`${cookieName}=`));
  }

  function setCookie(): void {
    const expires = new Date();
    expires.setTime(expires.getTime() + 365 * 24 * 60 * 60 * 1000);
    document.cookie = `${cookieName}=1;expires=${expires.toUTCString()};path=/;SameSite=Lax`;
  }

  function showBanner(): void {
    banner.style.display = 'block';
    banner.classList.remove('has-fade-out');
    banner.classList.add('has-fade-in');
  }

  function hideBanner(): void {
    banner.classList.remove('has-fade-in');
    banner.classList.add('has-fade-out');
    setTimeout(() => {
      banner.style.display = 'none';
    }, 300); // Duração da animação
  }

  if (!hasCookie()) {
    setTimeout(() => {
      showBanner();
    }, 1500);

    acceptBtn.addEventListener('click', () => {
      setCookie();
      hideBanner();
    });

    setTimeout(() => {
      if (!hasCookie()) {
        setCookie();
        hideBanner();
      }
    }, 30000);
  }
}
