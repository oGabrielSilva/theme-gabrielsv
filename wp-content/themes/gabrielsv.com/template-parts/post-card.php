<?php // Post Card Component ?>
<div class="card card-hover" style="height: 100%;">
    <?php if (has_post_thumbnail()): ?>
        <div class="card-image">
            <figure class="image is-16by9">
                <a href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
                    <?php the_post_thumbnail('blog-card', array(
                        'style' => 'object-fit: cover; width: 100%; height: 100%;',
                        'alt' => get_the_title()
                    )); ?>
                </a>
            </figure>
        </div>
    <?php endif; ?>

    <div class="card-content is-flex is-flex-direction-column" style="flex-grow: 1;">
        <div class="mb-2">
            <?php get_template_part('template-parts/post-categories'); ?>
        </div>

        <h2 class="title is-6 has-text-weight-bold mb-2">
            <a href="<?php the_permalink(); ?>" class="has-text-dark" style="text-decoration: none;">
                <?php the_title(); ?>
            </a>
        </h2>

        <p class="has-text-grey is-size-7 mb-3">
            <?php echo theme_get_limited_excerpt(get_the_ID(), 15); ?>
        </p>

        <div class="mt-auto">
            <?php get_template_part('template-parts/post-meta'); ?>
        </div>
    </div>
</div>
