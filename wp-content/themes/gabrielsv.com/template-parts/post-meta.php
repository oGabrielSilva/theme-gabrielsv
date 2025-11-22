<?php // Post Meta (Date & Author) ?>
<div class="is-flex is-flex-wrap-wrap is-align-items-center is-size-7" style="gap: 0.5rem;">
    <time datetime="<?php echo get_the_date('c'); ?>" class="is-flex is-align-items-center" style="gap: 0.25rem;">
        <span class="icon is-small" style="width: 16px; height: 16px;" aria-hidden="true">
            <?php get_template_part('template-parts/icons/calendar'); ?>
        </span>
        <span><?php echo get_the_date('d M, Y'); ?></span>
    </time>
    <span aria-hidden="true">·</span>
    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"
        class=" is-flex is-align-items-center" style="gap: 0.25rem; text-decoration: none;"
        aria-label="Ver posts de <?php the_author(); ?>">
        <span class="icon is-small" style="width: 16px; height: 16px;" aria-hidden="true">
            <?php get_template_part('template-parts/icons/user'); ?>
        </span>
        <span><?php the_author(); ?></span>
    </a>
</div>