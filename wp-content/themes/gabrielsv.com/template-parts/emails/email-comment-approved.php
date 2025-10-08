<?php
/**
 * Template para o e-mail de notificação de comentário aprovado.
 *
 * @var array $args
 * @var string $args['user_name']
 * @var string $args['post_title']
 * @var string $args['comment_excerpt']
 * @var string $args['comment_link']
 */
?>
<html lang="pt-BR">

<body style="font-family: sans-serif; color: #333;">
    <p>Olá, <?php echo esc_html($args['user_name']); ?>!</p>
    <p>Seu comentário no post "<?php echo esc_html($args['post_title']); ?>" foi aprovado e já está visível para
        todos.</p>
    <p style="margin: 25px 0; padding: 15px; background-color: #f8f9fa; border-left: 4px solid #dee2e6;">
        <em>"<?php echo esc_html($args['comment_excerpt']); ?>"</em>
    </p>
    <p>
        <a href="<?php echo esc_url($args['comment_link']); ?>"
            style="background-color: #0d6efd; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-size: 16px;">Ver
            Comentário</a>
    </p>
    <p>Atenciosamente,<br><?php echo get_bloginfo('name'); ?></p>
</body>

</html>
