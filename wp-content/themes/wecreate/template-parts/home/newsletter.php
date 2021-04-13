<section id="newsletter" class="top-section">
    <?php
        // repeater inside a group
        if( have_rows('newsletter') ): while ( have_rows('newsletter') ) : the_row(); ?>
            <h2 class="h1"><?php echo get_sub_field('title'); ?></h2>
            <p class="text-small-center"><?php echo get_sub_field('content'); ?></p>

        <?php endwhile; endif; ?>
        <?php echo do_shortcode('[contact-form-7 id="83" title="Newsletter Signup"]'); ?>
</section>
