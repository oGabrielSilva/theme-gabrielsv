<?php
/**
 * Template para o e-mail de notificação de resposta a um comentário.
 *
 * @var array $args
 * @var string $args['parent_user_name']
 * @var string $args['replier_name']
 * @var string $args['post_title']
 * @var string $args['parent_comment_excerpt']
 * @var string $args['comment_excerpt']
 * @var string $args['comment_link']
 */
?>
<html lang="pt-BR">

<body style="font-family: sans-serif; color: #333;">
    <p>Olá, <?php echo esc_html($args['parent_user_name']); ?>!</p>
    <p><strong><?php echo esc_html($args['replier_name']); ?></strong> respondeu ao seu comentário no post
        "<?php echo esc_html($args['post_title']); ?>".</p>

    <div style="margin: 25px 0;">
        <p>Seu comentário:</p>
        <blockquote style="margin: 0 0 15px 0; padding: 10px; background-color: #f8f9fa; border-left: 4px solid #dee2e6;">
            <em>"<?php echo esc_html($args['parent_comment_excerpt']); ?>"</em>
        </blockquote>

        <p>Resposta de <?php echo esc_html($args['replier_name']); ?>:</p>
        <blockquote style="margin: 0 0 15px 0; padding: 10px; background-color: #f0f8ff; border-left: 4px solid #0d6efd;">
            <em>"<?php echo esc_html($args['comment_excerpt']); ?>"</em>
        </blockquote>
    </div>

    <p>
        <a href="<?php echo esc_url($args['comment_link']); ?>"
            style="background-color: #0d6efd; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-size: 16px;">Ver
            Resposta</a>
    </p>
    <p>Atenciosamente,<br><?php echo get_bloginfo('name'); ?></p>
</body>

</html>
