<?php

/**
 * Configuração de Redes Sociais
 *
 * Este arquivo contém a configuração de todas as redes sociais suportadas pelo tema.
 * A detecção é feita pelo TÍTULO DO LINK no menu, não pela URL.
 *
 * Como usar:
 * 1. Crie um item de menu com um link qualquer.
 * 2. No "Texto do link", coloque o nome da rede social (ex: "Meu GitHub", "Twitter").
 * 3. O sistema detectará o ícone pelo texto.
 *
 * IMPORTANTE: Ícones SVG devem ser colocados em:
 * wp-content/themes/gabrielsv.com/template-parts/icons/social/
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Retorna a configuração de todas as redes sociais suportadas
 *
 * @return array Array associativo com configuração de cada rede social
 */
function get_social_networks_config()
{
    // Helper para criar validadores baseados no nome
    $create_validator = function ($keywords) {
        return function ($title) use ($keywords) {
            $title = strtolower($title);
            foreach ((array) $keywords as $keyword) {
                if (strpos($title, strtolower($keyword)) !== false) {
                    return true;
                }
            }
            return false;
        };
    };

    return array(
        // PRINCIPAIS
        'twitter' => ['name' => 'Twitter/X', 'icon' => 'twitter.svg', 'validator' => $create_validator(['twitter', 'x.com', 'x'])],
        'facebook' => ['name' => 'Facebook', 'icon' => 'facebook.svg', 'validator' => $create_validator('facebook')],
        'instagram' => ['name' => 'Instagram', 'icon' => 'instagram.svg', 'validator' => $create_validator(['instagram', 'instagr.am'])],
        'linkedin' => ['name' => 'LinkedIn', 'icon' => 'linkedin.svg', 'validator' => $create_validator('linkedin')],
        'youtube' => ['name' => 'YouTube', 'icon' => 'youtube.svg', 'validator' => $create_validator('youtube')],
        'tiktok' => ['name' => 'TikTok', 'icon' => 'tiktok.svg', 'validator' => $create_validator('tiktok')],

        // IMAGENS E DESIGN
        'pinterest' => ['name' => 'Pinterest', 'icon' => 'pinterest.svg', 'validator' => $create_validator('pinterest')],
        'behance' => ['name' => 'Behance', 'icon' => 'behance.svg', 'validator' => $create_validator('behance')],
        'dribbble' => ['name' => 'Dribbble', 'icon' => 'dribbble.svg', 'validator' => $create_validator('dribbble')],

        // MENSAGEIROS
        'whatsapp' => ['name' => 'WhatsApp', 'icon' => 'whatsapp.svg', 'validator' => $create_validator('whatsapp')],
        'telegram' => ['name' => 'Telegram', 'icon' => 'telegram.svg', 'validator' => $create_validator('telegram')],
        'discord' => ['name' => 'Discord', 'icon' => 'discord.svg', 'validator' => $create_validator('discord')],
        'slack' => ['name' => 'Slack', 'icon' => 'slack.svg', 'validator' => $create_validator('slack')],

        // DESENVOLVIMENTO
        'github' => ['name' => 'GitHub', 'icon' => 'github.svg', 'validator' => $create_validator('github')],
        'gitlab' => ['name' => 'GitLab', 'icon' => 'gitlab.svg', 'validator' => $create_validator('gitlab')],
        'stackoverflow' => ['name' => 'Stack Overflow', 'icon' => 'stack-overflow.svg', 'validator' => $create_validator('stack overflow')],

        // EMERGENTES
        'bluesky' => ['name' => 'Bluesky', 'icon' => 'bluesky.svg', 'validator' => $create_validator(['bluesky', 'bsky'])],
        'threads' => ['name' => 'Threads', 'icon' => 'threads.svg', 'validator' => $create_validator('threads')],

        // CONTEÚDO
        'medium' => ['name' => 'Medium', 'icon' => 'medium.svg', 'validator' => $create_validator('medium')],
        'tumblr' => ['name' => 'Tumblr', 'icon' => 'tumblr.svg', 'validator' => $create_validator('tumblr')],

        // MÍDIA
        'twitch' => ['name' => 'Twitch', 'icon' => 'twitch.svg', 'validator' => $create_validator('twitch')],
        'spotify' => ['name' => 'Spotify', 'icon' => 'spotify.svg', 'validator' => $create_validator('spotify')],
        'vimeo' => ['name' => 'Vimeo', 'icon' => 'vimeo.svg', 'validator' => $create_validator('vimeo')],
        'soundcloud' => ['name' => 'SoundCloud', 'icon' => 'soundcloud.svg', 'validator' => $create_validator('soundcloud')],
        'steam' => ['name' => 'Steam', 'icon' => 'steam.svg', 'validator' => $create_validator('steam')],

        // OUTRAS
        'snapchat' => ['name' => 'Snapchat', 'icon' => 'snapchat.svg', 'validator' => $create_validator('snapchat')],
        'reddit' => ['name' => 'Reddit', 'icon' => 'reddit.svg', 'validator' => $create_validator('reddit')],

        // CONTATO
        'email' => ['name' => 'Email', 'icon' => 'mail.svg', 'validator' => $create_validator(['email', 'mailto', 'e-mail'])],
        'phone' => ['name' => 'Telefone', 'icon' => 'phone.svg', 'validator' => $create_validator(['phone', 'telefone', 'tel'])],

        // FALLBACK
        'generic' => ['name' => 'Link', 'icon' => 'link.svg', 'validator' => function () {
            return true; }],
    );
}


/**
 * Detecta a rede social com base no TÍTULO do link
 *
 * @param string $title Título do item do menu
 * @return array Configuração da rede social detectada
 */
function detect_social_network($title)
{
    if (empty($title)) {
        $generic = get_social_networks_config()['generic'];
        return array_merge($generic, array('key' => 'generic'));
    }

    $networks = get_social_networks_config();

    $generic = $networks['generic'];
    unset($networks['generic']);

    foreach ($networks as $key => $network) {
        if (call_user_func($network['validator'], $title)) {
            return array_merge($network, array('key' => $key));
        }
    }

    return array_merge($generic, array('key' => 'generic'));
}

/**
 * Obtém o caminho completo do ícone SVG de uma rede social
 *
 * @param string $icon_filename Nome do arquivo SVG (ex: 'twitter.svg')
 * @return string Caminho completo para o arquivo SVG
 */
function get_social_icon_path($icon_filename)
{
    return get_template_directory() . '/template-parts/icons/social/' . $icon_filename;
}

/**
 * Renderiza o conteúdo SVG de um ícone social
 *
 * @param string $icon_filename Nome do arquivo SVG
 * @return string Conteúdo SVG ou string vazia se não encontrar
 */
function render_social_icon($icon_filename)
{
    $icon_path = get_social_icon_path($icon_filename);

    if (file_exists($icon_path)) {
        return file_get_contents($icon_path);
    }

    // Fallback: retorna ícone genérico se o específico não existir
    $generic_path = get_social_icon_path('link.svg');
    if (file_exists($generic_path)) {
        return file_get_contents($generic_path);
    }

    return '';
}
