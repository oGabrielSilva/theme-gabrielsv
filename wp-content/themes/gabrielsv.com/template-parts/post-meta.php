<?php // Post Meta (Date & Author) ?>
<div class="d-flex flex-wrap gap-2 small text-muted align-items-center">
    <time datetime="<?php echo get_the_date('c'); ?>" class="d-flex align-items-center gap-1">
        <span style="width: 16px; height: 16px; display: inline-flex;" aria-hidden="true">
            <?php get_template_part('template-parts/icons/calendar'); ?>
        </span>
        <span><?php echo get_the_date('d M, Y'); ?></span>
    </time>
    <span aria-hidden="true">·</span>
    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"
       class="text-decoration-none text-muted d-flex align-items-center gap-1"
       aria-label="Ver posts de <?php the_author(); ?>">
        <span style="width: 16px; height: 16px; display: inline-flex;" aria-hidden="true">
            <?php get_template_part('template-parts/icons/user'); ?>
        </span>
        <span><?php the_author(); ?></span>
    </a>
</div>
