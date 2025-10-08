<?php
/**
 * Template para o e-mail de recuperação de senha.
 *
 * @var array $args Argumentos passados para o template.
 * @var string $args['user_name'] Nome de exibição do usuário.
 * @var string $args['reset_url'] URL para redefinição de senha.
 */
?>
<html lang="pt-BR">

<body style="font-family: sans-serif; color: #333;">
    <p>Olá, <?php echo esc_html($args['user_name']); ?>!</p>
    <p>Recebemos uma solicitação para redefinir sua senha. Se você não fez esta solicitação, pode ignorar este e-mail.</p>
    <p>Para criar uma nova senha, clique no botão abaixo:</p>
    <p style="margin: 25px 0;">
        <a href="<?php echo esc_url($args['reset_url']); ?>"
            style="background-color: #0d6efd; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-size: 16px;">Redefinir
            Senha</a>
    </p>
    <p>Este link expira em 24 horas.</p>
    <p>Atenciosamente,<br><?php echo get_bloginfo('name'); ?></p>
</body>

</html>
