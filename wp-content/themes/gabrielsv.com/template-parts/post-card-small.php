<?php // Small Post Card ?>
<article class="d-flex gap-2 gap-sm-3">
    <?php if (has_post_thumbnail()): ?>
        <a href="<?php the_permalink(); ?>" class="flex-shrink-0"
           aria-label="<?php the_title_attribute(); ?>">
            <?php the_post_thumbnail('blog-thumb', array(
                'class' => 'img-fluid rounded-circle',
                'style' => 'width: 60px; height: 60px; object-fit: cover;',
                'alt' => get_the_title()
            )); ?>
        </a>
    <?php endif; ?>
    <div class="flex-grow-1 min-width-0">
        <h3 class="h6 fw-bold mb-1" style="font-size: 0.875rem;">
            <a href="<?php the_permalink(); ?>" class="text-decoration-none text-body">
                <?php the_title(); ?>
            </a>
        </h3>
        <div class="small text-muted">
            <time datetime="<?php echo get_the_date('c'); ?>">
                <?php echo get_the_date('d M, Y'); ?>
            </time>
        </div>
    </div>
</article>
