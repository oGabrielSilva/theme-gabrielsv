import { openModal, closeModal } from '../bulma/Modals';

export class ReplyComment {
  private replyModal: HTMLElement;
  private originalFormParent: HTMLElement | null;

  constructor() {
    const replyModal = document.getElementById('replyModal');

    if (!replyModal) {
      throw new Error('Reply modal not found');
    }

    this.replyModal = replyModal;
    this.originalFormParent = document.getElementById('respond')?.parentElement || null;

    this.setupEventListeners();
  }

  private setupEventListeners(): void {
    // Interceptar cliques nos botões de resposta
    document.addEventListener('click', this.handleReplyClick.bind(this));

    // Retornar formulário ao fechar modal (Bulma - observar quando perde classe is-active)
    const observer = new MutationObserver((mutations) => {
      mutations.forEach((mutation) => {
        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
          const target = mutation.target as HTMLElement;
          if (!target.classList.contains('is-active')) {
            this.handleModalHidden();
          }
        }
      });
    });
    observer.observe(this.replyModal, { attributes: true });
  }

  private handleReplyClick(e: Event): void {
    const replyBtn = (e.target as HTMLElement).closest<HTMLAnchorElement>('.comment-reply-link');
    if (!replyBtn) return;

    e.preventDefault();
    e.stopPropagation();

    // Capturar dados do comentário
    const commentAuthor = replyBtn.getAttribute('data-comment-author');
    const commentAvatar = replyBtn.getAttribute('data-comment-avatar');
    const commentDate = replyBtn.getAttribute('data-comment-date');
    const commentContent = replyBtn.getAttribute('data-comment-content');

    if (!commentAuthor || !commentAvatar || !commentDate || !commentContent) {
      return;
    }

    // Extrair o comment_parent do atributo data-comment-id
    const replyToId = replyBtn.getAttribute('data-comment-id');

    if (!replyToId) {
      return;
    }

    // Atualizar conteúdo do modal
    this.updateModalContent(commentAuthor, commentAvatar, commentDate, commentContent, replyToId);

    // Abrir modal (Bulma)
    openModal(this.replyModal);
  }

  private updateModalContent(
    author: string,
    avatar: string,
    date: string,
    content: string,
    replyToId: string
  ): void {
    const authorNameEl = document.getElementById('replyAuthorName');
    const avatarEl = document.getElementById('replyAuthorAvatar') as HTMLImageElement | null;
    const dateEl = document.getElementById('replyCommentDate');
    const contentEl = document.getElementById('replyCommentContent');

    if (authorNameEl) authorNameEl.textContent = author;
    if (avatarEl) {
      avatarEl.src = avatar;
      avatarEl.alt = author;
    }
    if (dateEl) dateEl.textContent = date;
    if (contentEl) contentEl.textContent = content;

    // Atualizar campo hidden comment_parent
    const respondDiv = document.getElementById('respond');
    if (!respondDiv) return;

    let parentInput = respondDiv.querySelector<HTMLInputElement>('#comment_parent');
    if (parentInput) {
      parentInput.value = replyToId;
    } else {
      // Criar campo se não existir
      parentInput = document.createElement('input');
      parentInput.type = 'hidden';
      parentInput.name = 'comment_parent';
      parentInput.id = 'comment_parent';
      parentInput.value = replyToId;
      respondDiv.querySelector('form')?.appendChild(parentInput);
    }

    // Mover formulário para o modal
    const container = document.getElementById('replyFormContainer');
    if (container) {
      container.innerHTML = '';
      container.appendChild(respondDiv);

      const title = respondDiv.querySelector<HTMLElement>('#reply-title');
      if (title) title.style.display = 'none';

      // Botão cancelar fecha o modal (Bulma)
      const cancelBtn = respondDiv.querySelector<HTMLAnchorElement>('#cancel-comment-reply-link');
      if (cancelBtn) {
        cancelBtn.onclick = (e) => {
          e.preventDefault();
          closeModal(this.replyModal);
          return false;
        };
      }
    }
  }

  private handleModalHidden(): void {
    const respondDiv = document.getElementById('respond');
    const parentInput = respondDiv?.querySelector<HTMLInputElement>('#comment_parent');

    // Resetar comment_parent para 0 (comentário raiz)
    if (parentInput) {
      parentInput.value = '0';
    }

    // Retornar formulário ao lugar original
    if (respondDiv && this.originalFormParent) {
      this.originalFormParent.appendChild(respondDiv);
    }
  }
}
