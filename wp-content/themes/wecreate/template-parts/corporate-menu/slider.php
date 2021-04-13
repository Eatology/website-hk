<section id="corporate-slider" class="top-section">
    <div class="meal-content">
        <?php if( have_rows('corporate') ): while ( have_rows('corporate') ) : the_row(); ?>
            <div class="corporate-slider carousel">
                <?php
                    while ( have_rows('corporate_slider') ) : the_row();       
                        $image   = get_sub_field('image');  
                        if( !empty( $image ) ):
                            echo '<div class="corporate-slider__slide corporate-slider__slideitem"><img class="customer-image" src="'.esc_url($image['sizes']['wecreate_blog']).'" alt="'.esc_attr($image['alt']).'" /> </div>';
                        endif; ?>
                <?php endwhile; wp_reset_query(); ?>
            </div>
        <?php endwhile; endif;?>
    </div>
</section>


