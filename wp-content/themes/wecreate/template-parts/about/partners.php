<section id="partners-slider" class="top-section">
    <div class="meal-content">
        <?php if( have_rows('about-us') ): while ( have_rows('about-us') ) : the_row(); ?>
            <h3 class="h1"><?php echo strip_tags(get_sub_field('partners_title')); ?></h3>
            <div class="partners-slider carousel">
                <?php
                    while ( have_rows('partners_slider') ) : the_row();
                        $image   = get_sub_field('image');
                        if( !empty( $image ) ):
                            echo '<div class="partners-slider__slide partners-slider__slideitem"><img class="customer-image" src="'.esc_url($image['sizes']['wecreate_blog']).'" alt="'.esc_attr($image['alt']).'" /> </div>';
                        endif; ?>
                <?php endwhile; wp_reset_query(); ?>
            </div>
        <?php endwhile; endif;?>
    </div>
</section>
