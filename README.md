# Blog gabrielsv.com - WordPress Theme

Tema WordPress minimalista e moderno para blog de tecnologia e cultura geek, com sistema de autenticação customizado, foco em tipografia e performance.

---

## 🚀 Stack Tecnológica

- **WordPress** 6.x + **PHP** 8.x
- **Bootstrap** 5.3.8 (local, sem CDN)
- **TypeScript** → compilado via Webpack
- **CSS** → minificado via Webpack
- **Yarn** 4.6.0 (PnP mode)
- **Docker** (ambiente de desenvolvimento)

---

## 📁 Estrutura do Projeto

```
blog.gabrielsv.com/
├── compose.yaml                         # Docker Compose (WordPress + MySQL + MailHog)
├── package.json                         # Dependências do projeto
├── webpack.config.js                    # Build TypeScript e CSS
├── tsconfig.json                        # Configuração TypeScript
├── yarn.lock                            # Lock file (Yarn Berry)
├── .yarn/                               # Yarn PnP
│
├── wp-content/
│   └── themes/
│       └── gabrielsv.com/               # TEMA PRINCIPAL
│           ├── style.css                # Header do tema (obrigatório WP)
│           ├── functions.php            # 1024 linhas - todas funções do tema
│           │
│           ├── header.php               # <head> + navbar
│           ├── footer.php               # Footer + cookie notice
│           ├── index.php                # Home (hero + grid 3 colunas)
│           ├── single.php               # Post individual (2 colunas)
│           ├── page.php                 # Página padrão
│           ├── 404.php                  # Página de erro
│           ├── search.php               # Resultados de busca
│           ├── category.php             # Archive de categoria
│           ├── tag.php                  # Archive de tag
│           ├── author.php               # Archive de autor
│           ├── comments.php             # Sistema de comentários
│           ├── page-auth.php            # Login e registro (/auth)
│           ├── page-eu.php              # Perfil do usuário (/eu)
│           │
│           ├── template-parts/
│           │   ├── navbar.php           # Barra de navegação
│           │   ├── breadcrumbs.php      # Breadcrumbs
│           │   ├── user-dropdown.php    # Dropdown de usuário
│           │   ├── cookie-notice.php    # Banner de cookies
│           │   ├── post-card.php        # Card de post (grid)
│           │   ├── post-card-small.php  # Card pequeno (sidebar)
│           │   ├── post-meta.php        # Data + autor
│           │   ├── post-categories.php  # Botões de categoria
│           │   ├── emails/              # Templates de email
│           │   │   ├── email-comment-approved.php
│           │   │   ├── email-comment-reply.php
│           │   │   └── email-password-reset.php
│           │   ├── icons/               # 10 ícones SVG (16x16px)
│           │   │   ├── calendar.php
│           │   │   ├── user.php
│           │   │   ├── log-in.php
│           │   │   ├── log-out.php
│           │   │   ├── chart.php
│           │   │   ├── github.php
│           │   │   ├── linkedin.php
│           │   │   ├── twitter.php
│           │   │   ├── mail.php
│           │   │   ├── globe.php
│           │   │   └── home.php
│           │   └── ui/
│           │       ├── categories-dropdown.php
│           │       ├── github-button.php
│           │       ├── social-list.php
│           │       └── theme-button.php
│           │
│           └── resources/
│               ├── css/
│               │   └── master.css       # CSS customizado (tipografia + card-hover)
│               │
│               ├── typescript/
│               │   ├── main.ts          # Entry point principal
│               │   ├── auth.ts          # Entry point auth
│               │   ├── profile.ts       # Entry point profile
│               │   ├── comments.ts      # Entry point comments
│               │   │
│               │   ├── main/
│               │   │   ├── SearchModal.ts
│               │   │   ├── ThemeManager.ts (light/dark mode)
│               │   │   └── CookieBanner.ts
│               │   │
│               │   ├── auth/
│               │   │   ├── LoginForm.ts
│               │   │   ├── RegisterForm.ts
│               │   │   ├── ForgotPasswordForm.ts
│               │   │   └── ResetPasswordForm.ts
│               │   │
│               │   ├── profile/
│               │   │   └── ProfileForm.ts
│               │   │
│               │   ├── comments/
│               │   │   ├── ReplyComment.ts
│               │   │   └── DeleteComment.ts
│               │   │
│               │   ├── utils/
│               │   │   ├── toast.ts
│               │   │   └── globalToast.ts
│               │   │
│               │   └── types/
│               │       ├── wordpress.d.ts
│               │       └── events.d.ts
│               │
│               ├── dist/                # Output do Webpack
│               │   ├── css/
│               │   │   └── master.min.css
│               │   └── javascript/
│               │       ├── main.min.js
│               │       ├── auth.min.js
│               │       ├── profile.min.js
│               │       └── comments.min.js
│               │
│               └── lib/
│                   └── bootstrap/5_3_8/ # Bootstrap local (CSS + JS)
```

---

## 🎨 Design System

### Tipografia

- **JetBrains Mono**: Headings (h1-h6), código, inputs
- **Inter**: Corpo de texto, parágrafos
- **Kablammo**: Logo "G" (apenas branding)

### Cores e Temas

- Neutros dominantes: `text-body`, `text-muted`
- Sistema light/dark mode via classe `.btn-theme` (JavaScript)
- Alto contraste para legibilidade (WCAG AA)

### Componentes

- **Cards com hover**: Classe `.card-hover` (translateY + shadow)
- **Botões**: `.btn-theme.btn-sm` (adapta ao tema light/dark)
- **Categorias**: Botões (não badges)
- **Tags**: Links simples separados por vírgula
- **Ícones**: SVG 16x16px inline

### Layout

- Mobile-first (Bootstrap grid)
- Home: Hero (1 post) + Grid 3 colunas (6 posts) + Seções por categoria
- Single: 2 colunas (conteúdo + sidebar)
- Sidebar: Posts relacionados, últimos posts, mais do autor

---

## 🔐 Sistema de Autenticação

### Login e Registro (`/auth`)

- Login customizado (esconde `wp-login.php`)
- Modal de registro Bootstrap
- Username: auto-formatação (lowercase, sem caracteres especiais)
- AJAX com TypeScript (classes modulares)
- Validação client-side + server-side

### Perfil (`/eu`)

- Edição de nome, email, bio, site
- Redes sociais: Twitter, LinkedIn, GitHub
- Alteração de senha opcional
- Avatar via Gravatar

### Recuperação de Senha

- Modal "Esqueci minha senha" na página `/auth`
- Link único via email
- Página de reset de senha (`/reset-password`)

### Permissões por Role

- **Subscriber**: Login, perfil, comentários (sem wp-admin)
- **Author+**: Acesso completo + página `/author/username`

### Dropdown de Usuário

- Header desktop + mobile (offcanvas)
- Avatar do usuário
- Links: Meu Perfil, Painel Admin (Author+), Sair

---

## 💬 Sistema de Comentários

### Funcionalidades

- Apenas usuários logados podem comentar
- Todos comentários pendentes de aprovação manual
- Reply via modal Bootstrap (mostra contexto do comentário pai)
- Delete apenas para autor do comentário ou moderadores
- Deslogados veem modal pedindo login ao tentar responder

### Visibilidade de Comentários Não Aprovados

- ✅ Visíveis: Autor do comentário, autor do post, moderadores
- ❌ Ocultos: Visitantes anônimos, outros usuários

### Notificações por Email

- Comentário aprovado → email para autor do comentário
- Resposta a comentário → email para autor do comentário pai
- Templates HTML personalizados (`template-parts/emails/`)
- Rate limiting para evitar spam

---

## 📧 Sistema de Email

### Notificações Implementadas

1. **Comentário aprovado** → autor do comentário
2. **Resposta a comentário** → autor do comentário pai
3. **Recuperação de senha** → usuário que solicitou

### Rate Limiting (WordPress Transients)

Previne spam e custos excessivos com provedores de email:

- **Password reset**: 3 tentativas por IP / 15 minutos
- **Comentário aprovado**: 1 email por usuário / 5 minutos
- **Reply**: 1 email por par de usuários (autor + respondente) / 5 minutos

Transients são armazenados na tabela `wp_options` e expiram automaticamente.

### Configuração SMTP

- **Produção**: Plugin **WP Mail SMTP** (Gmail, SendGrid, etc)
- **Desenvolvimento**: **MailHog** via Docker (localhost:8025)

---

## 🍪 Cookie Notice

Sistema customizado (substitui plugin Cookie Notice):

### Características

- Banner com delay de 1.5 segundos
- Botão "Ok" para aceite explícito
- Auto-aceite após 30 segundos (consent implícito)
- Não exibe na página `/politica-de-cookies`
- Cookie: `theme_cookies_accepted` (validade: 365 dias)

### Arquivos

- `template-parts/cookie-notice.php` (PHP)
- `resources/typescript/main/CookieBanner.ts` (TypeScript)

---

## 📝 Posts e Taxonomias

### Post (padrão)

- **Slug**: `/` ou `/{slug}`
- **Conteúdo**: Tecnologia, programação, tutoriais, análises técnicas
- **Taxonomias**:
  - Categorias: `category` (URL: `/category/{slug}`)
  - Tags: `post_tag` (URL: `/tag/{slug}`)
- **Archive**: Home (`index.php`)
- **Template**: `single.php`

### Estrutura de URLs

- Home: `blog.gabrielsv.com/`
- Post: `blog.gabrielsv.com/{slug}`
- Categoria: `blog.gabrielsv.com/category/{slug}`
- Tag: `blog.gabrielsv.com/tag/{slug}`
- Autor: `blog.gabrielsv.com/author/{username}`
- Busca: `blog.gabrielsv.com/?s={query}`

---

## ⚙️ Configuração Inicial

### 1. Clonar e Instalar Dependências

```bash
git clone <repo>
cd blog.gabrielsv.com
yarn install
```

### 2. Subir Docker

```bash
docker-compose up -d
```

Acesse: `http://localhost:5011`

### 3. Instalar WordPress

1. Complete a instalação do WordPress via browser
2. Ative o tema "gabrielsv" em Aparência > Temas

### 4. Criar Páginas Obrigatórias

No WordPress Admin, crie as seguintes páginas:

| Título                  | Slug                      | Template         |
| ----------------------- | ------------------------- | ---------------- |
| Auth                    | `auth`                    | **Autenticação** |
| Eu                      | `eu`                      | **Meu Perfil**   |
| Política de Privacidade | `politica-de-privacidade` | Padrão           |
| Política de Cookies     | `politica-de-cookies`     | Padrão           |
| Termos de Uso           | `termos-de-uso`           | Padrão           |

### 5. Configurar Email (SMTP)

- **Desenvolvimento**: MailHog já está no Docker (veja emails em `http://localhost:8025`)
- **Produção**: Instale plugin **WP Mail SMTP** e configure Gmail/SendGrid

### 6. Build Assets

```bash
yarn build
```

Ou para desenvolvimento:

```bash
yarn dev  # Modo watch
```

---

## 🔧 Desenvolvimento

### Scripts Disponíveis

```bash
yarn build    # Build produção (minifica CSS e JS)
yarn dev      # Build desenvolvimento + watch mode
```

### Webpack Build

**Entrada** (`resources/`):

- `typescript/main.ts` → `dist/javascript/main.min.js`
- `typescript/auth.ts` → `dist/javascript/auth.min.js`
- `typescript/profile.ts` → `dist/javascript/profile.min.js`
- `typescript/comments.ts` → `dist/javascript/comments.min.js`
- `css/master.css` → `dist/css/master.min.css`

**Plugins**: TerserPlugin (JS), CssMinimizerPlugin (CSS)

### Convenções de Código

- Prefixo de funções: `theme_*` (todas as 32 funções do tema)
- Sem `console.log()` ou `error_log()` em produção
- Comentários HTML convertidos em PHP comments: `<?php // ?>`
- TypeScript organizado em módulos por feature

---

## 📊 Funções do Tema

Total: **31 funções** prefixadas com `theme_*`

### Core

- `theme_setup()` - Configuração inicial do tema
- `theme_styles()` - Enqueue CSS
- `theme_scripts()` - Enqueue JS
- `theme_add_defer_attribute()` - Adiciona defer em scripts
- `theme_add_image_sizes()` - Tamanhos de imagem customizados

### Autenticação e AJAX

- `theme_ajax_custom_login()` - Login via AJAX
- `theme_ajax_custom_register()` - Registro via AJAX
- `theme_ajax_update_profile()` - Atualizar perfil via AJAX
- `theme_ajax_password_reset_request()` - Solicitar reset de senha
- `theme_ajax_password_reset_confirm()` - Confirmar reset de senha
- `theme_ajax_delete_comment()` - Deletar comentário via AJAX

### Segurança

- `theme_hide_admin_bar()` - Esconde admin bar para Subscribers
- `theme_block_admin_for_subscribers()` - Bloqueia /wp-admin
- `theme_redirect_author_for_subscribers()` - Redireciona /author
- `theme_user_can_access_admin()` - Helper de permissão
- `theme_user_has_author_page()` - Helper de permissão
- `theme_disable_rest_api()` - Desabilita REST API para não logados

### Email

- `get_email_template_html()` - Carrega template de email
- `theme_notify_comment_approved()` - Notifica aprovação
- `theme_notify_comment_reply()` - Notifica resposta

### Helpers

- `theme_get_formatted_categories()` - Array de categorias formatadas
- `theme_get_limited_excerpt()` - Excerpt limitado por palavras
- `theme_bootstrap_pagination()` - Paginação Bootstrap
- `theme_add_social_fields()` - Campos sociais no perfil
- `theme_comments_setup()` - Configuração de comentários
- `theme_comment_template()` - Template customizado de comentário

### Performance

- `theme_add_lazy_loading()` - Lazy loading em imagens
- `theme_lazy_load_avatars()` - Lazy loading em avatares
- `theme_add_preload_fonts()` - Preload Google Fonts
- `theme_add_google_fonts()` - Carrega fontes
- `theme_add_favicons()` - Adiciona favicons

---

## 📄 Licença

Tema proprietário desenvolvido para gabrielsv.com

**Autor**: Gabriel Silva
