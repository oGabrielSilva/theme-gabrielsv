<?php // Post Card Component ?>
<div class="card h-100 border-0 shadow-sm card-hover">
    <?php if (has_post_thumbnail()): ?>
        <a href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
            <?php the_post_thumbnail('blog-card', array(
                'class' => 'card-img-top',
                'style' => 'height: 200px; object-fit: cover;',
                'alt' => get_the_title()
            )); ?>
        </a>
    <?php endif; ?>

    <div class="card-body d-flex flex-column">
        <div class="mb-2">
            <?php get_template_part('template-parts/post-categories'); ?>
        </div>

        <h2 class="card-title h6 fw-bold mb-2">
            <a href="<?php the_permalink(); ?>" class="text-decoration-none text-body stretched-link">
                <?php the_title(); ?>
            </a>
        </h2>

        <p class="card-text text-muted small mb-3">
            <?php echo theme_get_limited_excerpt(get_the_ID(), 15); ?>
        </p>

        <div class="mt-auto">
            <?php get_template_part('template-parts/post-meta'); ?>
        </div>
    </div>
</div>
