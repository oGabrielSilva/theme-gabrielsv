import { showError, showSuccess } from "../utils/notifications";

/**
 * ShareButtons - Gerencia os botões de compartilhamento
 * Sempre mostra dropdown. Se Web Share API disponível, adiciona botão "Mais opções"
 * Suporta múltiplas instâncias na mesma página
 */
export class ShareButtons {
  private wrappers: NodeListOf<HTMLElement>;
  private hasWebShare: boolean = false;

  constructor() {
    this.wrappers = document.querySelectorAll("[data-share-wrapper]");
    this.hasWebShare = !!navigator.share;
    this.init();
  }

  private init(): void {
    if (this.wrappers.length === 0) return;

    this.wrappers.forEach((wrapper) => {
      this.initWrapper(wrapper);
    });
  }

  private initWrapper(wrapper: HTMLElement): void {
    const trigger = wrapper.querySelector<HTMLButtonElement>(
      "[data-share-trigger]"
    );
    const dropdown = wrapper;
    const dropdownMenu = wrapper.querySelector<HTMLElement>(".dropdown-menu");
    const copyLinkBtn =
      wrapper.querySelector<HTMLAnchorElement>("[data-copy-link]");
    const webShareBtn =
      wrapper.querySelector<HTMLAnchorElement>("[data-web-share]");
    const webShareDivider = wrapper.querySelector<HTMLElement>(
      "[data-web-share-divider]"
    );
    const shareMode = wrapper.dataset.shareMode || "dropdown";

    // Se modo direct, esconder o dropdown menu
    if (shareMode === "direct" && dropdownMenu) {
      dropdownMenu.style.display = "none";
    }

    // Se Web Share não estiver disponível, remover o botão
    if (!this.hasWebShare) {
      webShareBtn?.remove();
      webShareDivider?.remove();
    }

    this.attachEventListeners(
      wrapper,
      trigger,
      dropdown,
      copyLinkBtn,
      webShareBtn
    );
  }

  private attachEventListeners(
    wrapper: HTMLElement,
    trigger: HTMLButtonElement | null,
    dropdown: HTMLElement,
    copyLinkBtn: HTMLAnchorElement | null,
    webShareBtn: HTMLAnchorElement | null
  ): void {
    if (!trigger) return;

    const shareMode = wrapper.dataset.shareMode || "dropdown";

    // Evento de clique no botão principal
    trigger.addEventListener("click", (e) => {
      e.preventDefault();
      e.stopPropagation();

      if (shareMode === "direct") {
        // Modo direct: tenta Web Share API, senão copia link
        this.handleDirectShare(wrapper);
      } else {
        // Modo dropdown: toggle dropdown
        dropdown.classList.toggle("is-active");
      }
    });

    // Evento para Web Share API (se disponível)
    if (this.hasWebShare && webShareBtn) {
      webShareBtn.addEventListener("click", (e) =>
        this.handleWebShare(e, wrapper, dropdown)
      );
    }

    // Evento para copiar link
    if (copyLinkBtn) {
      copyLinkBtn.addEventListener("click", (e) =>
        this.handleCopyLink(e, wrapper, copyLinkBtn, dropdown)
      );
    }

    // Fechar dropdown ao clicar fora (apenas no modo dropdown)
    if (shareMode === "dropdown") {
      document.addEventListener("click", (e) => {
        const target = e.target as Node;
        if (!wrapper.contains(target)) {
          dropdown.classList.remove("is-active");
        }
      });
    }
  }

  private async handleDirectShare(wrapper: HTMLElement): Promise<void> {
    const postUrl = wrapper.dataset.postUrl || "";
    const postTitle = wrapper.dataset.postTitle || "";
    const postExcerpt = wrapper.dataset.postExcerpt || "";

    // Tentar Web Share API primeiro
    if (this.hasWebShare) {
      try {
        await navigator.share({
          title: postTitle,
          text: postExcerpt,
          url: postUrl,
        });
        return;
      } catch (err) {
        // Se usuário cancelar, não fazer nada
        if (err instanceof Error && err.name === "AbortError") {
          return;
        }
        // Se der erro, continua para copiar o link
      }
    }

    // Fallback: copiar link automaticamente
    try {
      await navigator.clipboard.writeText(postUrl);
      showSuccess("Link copiado para a área de transferência!", 3000);
    } catch (err) {
      showError("Erro ao copiar o link. Tente novamente.", 3000);
    }
  }

  private async handleWebShare(
    e: Event,
    wrapper: HTMLElement,
    dropdown: HTMLElement
  ): Promise<void> {
    e.preventDefault();

    const postUrl = wrapper.dataset.postUrl || "";
    const postTitle = wrapper.dataset.postTitle || "";
    const postExcerpt = wrapper.dataset.postExcerpt || "";

    try {
      await navigator.share({
        title: postTitle,
        text: postExcerpt,
        url: postUrl,
      });

      // Fechar dropdown após compartilhar
      dropdown.classList.remove("is-active");
    } catch (err) {
      // Se usuário cancelar, apenas não fazer nada
      // Ignorar erros silenciosamente
    }
  }

  private async handleCopyLink(
    e: Event,
    wrapper: HTMLElement,
    copyLinkBtn: HTMLAnchorElement,
    dropdown: HTMLElement
  ): Promise<void> {
    e.preventDefault();

    const url = wrapper.dataset.postUrl || "";
    const textSpan = copyLinkBtn.querySelector(".copy-link-text");

    if (!textSpan) return;

    const originalText = textSpan.textContent || "Copiar link";

    try {
      await navigator.clipboard.writeText(url);

      // Feedback visual
      textSpan.textContent = "Link copiado!";
      copyLinkBtn.classList.add("has-text-success");

      setTimeout(() => {
        textSpan.textContent = originalText;
        copyLinkBtn.classList.remove("has-text-success");
      }, 2000);

      // Fechar dropdown após copiar
      setTimeout(() => {
        dropdown.classList.remove("is-active");
      }, 2500);
    } catch (err) {
      textSpan.textContent = "Erro ao copiar";
      copyLinkBtn.classList.add("has-text-danger");

      setTimeout(() => {
        textSpan.textContent = originalText;
        copyLinkBtn.classList.remove("has-text-danger");
      }, 2000);
    }
  }
}
