# Sistema de Links de Redes Sociais Dinâmicos

Este sistema permite gerenciar links de redes sociais através do **WordPress Menus** nativo, com detecção automática de ícones baseada na URL.

## 📋 Como Usar

### 1. Configurar o Menu (WordPress Admin)

1. Acesse **Aparência > Menus** no painel do WordPress
2. Crie um novo menu ou edite um existente
3. Adicione **Links Personalizados** com as URLs das suas redes sociais:
   - URL: `https://x.com/seu_usuario`
   - Texto do link: `Twitter` (opcional - usado como title/aria-label)
4. Na seção **Configurações do Menu**, marque a opção **"Links de Redes Sociais"**
5. Salve o menu

### 2. Adicionar Links

Simplesmente adicione Custom Links com URLs como:

- `https://x.com/seu_usuario` → Detecta Twitter
- `https://github.com/seu_usuario` → Detecta GitHub
- `https://linkedin.com/in/seu-perfil` → Detecta LinkedIn
- `mailto:seu@email.com` → Detecta Email
- `https://qualquersite.com` → Usa ícone genérico

### 3. Reordenar Links

Arraste e solte os itens do menu para reorganizar a ordem de exibição.

---

## 🎨 Ícones SVG

### Ícones Disponíveis (Lucide Icons)

Os seguintes ícones já estão incluídos:

- ✅ `twitter.svg` - Twitter/X
- ✅ `facebook.svg` - Facebook
- ✅ `instagram.svg` - Instagram
- ✅ `linkedin.svg` - LinkedIn
- ✅ `youtube.svg` - YouTube
- ✅ `github.svg` - GitHub
- ✅ `mail.svg` - Email
- ✅ `link.svg` - Link genérico (fallback)

### Como Adicionar Mais Ícones

#### Opção 1: Lucide Icons (Recomendado para ícones básicos)

1. Acesse [lucide.dev](https://lucide.dev)
2. Busque pelo ícone desejado (ex: "twitch")
3. Clique em "Copy SVG"
4. Cole o SVG em um novo arquivo nesta pasta
5. **IMPORTANTE**: Adicione o atributo `style` ao SVG:
   ```xml
   <svg xmlns="http://www.w3.org/2000/svg" style="width: 1.2rem !important; height: 1.2rem !important;" ...>
   ```
6. Salve como `nome-da-rede.svg`

#### Opção 2: Simple Icons (Para marcas de redes sociais)

1. Acesse [simpleicons.org](https://simpleicons.org)
2. Busque pela rede social (ex: "TikTok", "Mastodon", "Bluesky")
3. Clique no ícone para copiar o SVG
4. **IMPORTANTE**: Ajuste o SVG para o padrão do tema:
   ```xml
   <svg xmlns="http://www.w3.org/2000/svg" style="width: 1.2rem !important; height: 1.2rem !important;" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
       <!-- paths aqui -->
   </svg>
   ```
   - Simple Icons usa `fill="currentColor"` (ícones sólidos)
   - Lucide Icons usa `stroke="currentColor"` (ícones outline)
5. Salve como `nome-da-rede.svg`

#### Exemplo Prático: Adicionando TikTok

1. Vá em [simpleicons.org](https://simpleicons.org) e busque "TikTok"
2. Copie o SVG
3. Crie arquivo `tiktok.svg` nesta pasta:
   ```xml
   <svg xmlns="http://www.w3.org/2000/svg" style="width: 1.2rem !important; height: 1.2rem !important;" role="img" viewBox="0 0 24 24" fill="currentColor">
       <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
   </svg>
   ```
4. Agora URLs do TikTok serão detectadas automaticamente!

---

## 🔧 Arquivos do Sistema

### Estrutura de Arquivos

```
wp-content/themes/gabrielsv.com/
├── functions.php                        # Registra menu location + inclui config
├── inc/
│   └── social-networks-config.php       # Configuração completa das redes
├── template-parts/
│   ├── ui/
│   │   └── social-list.php              # Template que renderiza os links
│   └── icons/
│       └── social/                      # Pasta de ícones SVG
│           ├── README.md                # Este arquivo
│           ├── twitter.svg
│           ├── facebook.svg
│           ├── instagram.svg
│           ├── linkedin.svg
│           ├── youtube.svg
│           ├── github.svg
│           ├── mail.svg
│           └── link.svg                 # Fallback genérico
```

### Como Funciona

1. **functions.php:18-21** - Registra o menu location `social-links`
2. **functions.php:26** - Inclui `inc/social-networks-config.php`
3. **social-networks-config.php** - Define:
   - Array com todas as redes sociais
   - Validadores de URL (funções anônimas)
   - Funções de detecção e renderização
4. **social-list.php** - Template que:
   - Lê os itens do menu
   - Detecta a rede social pela URL
   - Renderiza o ícone SVG correspondente

---

## 🚀 Onde Mais Aplicar Este Sistema

Este sistema de **Menu Location + Detecção Automática** pode ser aplicado em várias outras áreas do tema:

### 1. **Footer com Múltiplas Colunas**

Criar menu locations para:
- `footer-column-1` → "Sobre"
- `footer-column-2` → "Recursos"
- `footer-column-3` → "Legal"

**Benefícios:**
- Usuário configura via Aparência > Menus
- Sem hardcode no template
- Fácil de adicionar/remover/reordenar

**Implementação:**
```php
// functions.php
register_nav_menus(array(
    'footer-column-1' => __('Footer Coluna 1', 'gabrielsv'),
    'footer-column-2' => __('Footer Coluna 2', 'gabrielsv'),
    'footer-column-3' => __('Footer Coluna 3', 'gabrielsv'),
));

// footer.php
<div class="footer-column">
    <h3>Sobre</h3>
    <?php wp_nav_menu(array('theme_location' => 'footer-column-1')); ?>
</div>
```

---

### 2. **Navegação Secundária**

Criar um menu secundário para:
- Links de documentação
- Páginas de ajuda
- Links externos

**Uso:**
```php
register_nav_menus(array(
    'secondary-menu' => __('Menu Secundário', 'gabrielsv'),
));
```

---

### 3. **Breadcrumbs Personalizados**

Usar menus hierárquicos para definir breadcrumbs customizados.

---

### 4. **Links de Idiomas (i18n)**

Criar menu de idiomas:
- Português
- English
- Español

Com ícones de bandeiras detectados automaticamente pela URL ou slug.

---

### 5. **Menu Mobile Customizado**

Criar um menu específico para mobile com menos itens.

**Uso:**
```php
register_nav_menus(array(
    'mobile-menu' => __('Menu Mobile', 'gabrielsv'),
));
```

---

### 6. **Menu de Categorias em Destaque**

Exibir categorias específicas como menu (ex: no header).

---

### 7. **Links Rápidos no Sidebar**

Menu de atalhos para posts populares ou páginas importantes.

---

### 8. **Menu de Autores/Contribuidores**

Lista de autores do blog com detecção automática de avatar pela URL do perfil.

---

### 9. **Menu de Downloads**

Links para recursos, PDFs, arquivos com ícones detectados por extensão:
- `.pdf` → ícone PDF
- `.zip` → ícone ZIP
- `.doc` → ícone Word

**Implementação similar:**
```php
function detect_file_type($url) {
    $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
    switch ($extension) {
        case 'pdf': return 'pdf.svg';
        case 'zip': return 'zip.svg';
        default: return 'file.svg';
    }
}
```

---

### 10. **Menu de Patrocinadores/Parceiros**

Exibir logos de parceiros com:
- URL do parceiro
- Logo detectado automaticamente pela URL
- Title/alt text configurável

---

## 💡 Vantagens do Sistema de Menus

✅ **Nativo do WordPress** - Sem plugins
✅ **Interface familiar** - Usuários já conhecem
✅ **Flexível** - Arrastar e soltar para reordenar
✅ **Escalável** - Adicionar infinitos links
✅ **Detecção automática** - Zero configuração para ícones
✅ **Fallback inteligente** - Ícone genérico se não detectar
✅ **Acessibilidade** - title e aria-label automáticos
✅ **Performance** - SVG inline (sem requisições HTTP)

---

## 🔍 Detecção de Redes Sociais

As seguintes redes são detectadas automaticamente:

| Rede Social | Domínios Detectados | Ícone |
|-------------|---------------------|-------|
| Twitter/X | x.com, twitter.com | twitter.svg |
| Facebook | facebook.com, fb.com, m.me | facebook.svg |
| Instagram | instagram.com, instagr.am | instagram.svg |
| LinkedIn | linkedin.com | linkedin.svg |
| YouTube | youtube.com, youtu.be | youtube.svg |
| TikTok | tiktok.com | tiktok.svg |
| GitHub | github.com | github.svg |
| GitLab | gitlab.com | gitlab.svg |
| Discord | discord.com, discord.gg | discord.svg |
| WhatsApp | wa.me, whatsapp.com | whatsapp.svg |
| Telegram | t.me, telegram.me | telegram.svg |
| Twitch | twitch.tv | twitch.svg |
| Reddit | reddit.com | reddit.svg |
| Medium | medium.com | medium.svg |
| Dev.to | dev.to | devto.svg |
| Mastodon | *mastodon*, mas.to, fosstodon.org | mastodon.svg |
| Bluesky | bsky.app, bsky.social | bluesky.svg |
| Threads | threads.net | threads.svg |
| Email | mailto: | mail.svg |
| **Outros** | Qualquer outra URL | link.svg |

*Observação: Mastodon é detectado se a palavra "mastodon" aparecer no domínio (rede descentralizada)*

---

## 🛠️ Adicionar Nova Rede Social

### Passo 1: Adicionar Ícone SVG

Crie o arquivo `nova-rede.svg` nesta pasta seguindo o padrão.

### Passo 2: Adicionar Configuração

Edite `inc/social-networks-config.php` e adicione:

```php
'novarede' => array(
    'name' => 'Nova Rede',
    'icon' => 'nova-rede.svg',
    'validator' => function ($url) {
        $url = strtolower($url);
        return strpos($url, 'novarede.com') !== false;
    }
),
```

### Passo 3: Pronto!

Agora URLs da nova rede serão detectadas automaticamente.

---

## 📚 Recursos

- [Lucide Icons](https://lucide.dev) - Ícones outline (estilo usado no tema)
- [Simple Icons](https://simpleicons.org) - Ícones de marcas (2000+ logos)
- [WordPress Menus](https://developer.wordpress.org/themes/functionality/navigation-menus/) - Documentação oficial

---

## 🐛 Troubleshooting

### Ícones não aparecem

1. Verifique se o arquivo SVG existe na pasta `template-parts/icons/social/`
2. Verifique se o nome do arquivo corresponde ao configurado em `social-networks-config.php`
3. Verifique se o SVG tem o atributo `style="width: 1.2rem !important; height: 1.2rem !important;"`

### Rede social não detectada

1. Verifique se a URL está correta (com `https://`)
2. Verifique se o validador está configurado em `inc/social-networks-config.php`
3. Teste o validador:
   ```php
   $network = detect_social_network('https://sua-url.com');
   var_dump($network);
   ```

### Menu não aparece

1. Vá em **Aparência > Menus**
2. Certifique-se de que marcou **"Links de Redes Sociais"** nas configurações do menu
3. Verifique se o menu tem pelo menos 1 item

---

**Criado com ❤️ para gabrielsv.com**
