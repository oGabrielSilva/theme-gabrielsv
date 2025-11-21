<?php // Small Post Card ?>
<article class="is-flex" style="gap: 0.75rem;">
    <?php if (has_post_thumbnail()): ?>
        <a href="<?php the_permalink(); ?>" class="is-flex-shrink-0"
           aria-label="<?php the_title_attribute(); ?>">
            <figure class="image is-64x64">
                <?php the_post_thumbnail('blog-thumb', array(
                    'class' => 'is-rounded',
                    'style' => 'width: 60px; height: 60px; object-fit: cover;',
                    'alt' => get_the_title()
                )); ?>
            </figure>
        </a>
    <?php endif; ?>
    <div class="is-flex-grow-1" style="min-width: 0;">
        <h3 class="title is-6 has-text-weight-bold mb-1" style="font-size: 0.875rem;">
            <a href="<?php the_permalink(); ?>" class="has-text-dark" style="text-decoration: none;">
                <?php the_title(); ?>
            </a>
        </h3>
        <div class="is-size-7 has-text-grey">
            <time datetime="<?php echo get_the_date('c'); ?>">
                <?php echo get_the_date('d M, Y'); ?>
            </time>
        </div>
    </div>
</article>
