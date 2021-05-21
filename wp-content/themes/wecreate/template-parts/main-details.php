<section id="main-details" class="top-section">
    <?php 
        if( have_rows('main_section') ): while ( have_rows('main_section') ) : the_row(); ?>
            <?php 
                $button_text    = get_sub_field('button_text'); 
                $button_url     = get_sub_field('button_url'); 
                $content        = get_sub_field('content'); 
                $image          = get_sub_field('main_image'); 
                $svg            = get_sub_field('svg'); 
            ?>                    

            <?php

                if ($svg) {
                    $svg = '<div class="svg-span-wrapper">'.$svg. '</div>';
                }
                $image_tag = '';
                $bg_circle = '<div class="work-circle-wrapper">'.svg_circle('work-circle'). '</div>';

                if( !empty( $image ) ):
                    $image_tag  = '<div class="img-wrapper"><img src="'.esc_url($image['sizes']['wecreate_blog']).'" alt="'.esc_attr($image['alt']).'" /></div>';
                endif;

                if ($button_text && $button_url) {
                    $button = '<a href="'.$button_url.'" class="button-links">'. __($button_text, 'eatology').'</a>';
                }                

                $content_div = '<div class="content-wrapper">
                    <h2>'.get_the_title().'</h2>
                        <p>'.$content.'</p>
                        '.$button.'
                    </div>';
                ?>


                <div class="about-detail-row">
                    <?php
                        echo '<div class="svg-wrapper">'.$bg_circle  . $svg.'</div>';
                        echo '<div class="about-detail-wrapper">';
                        echo $image_tag;
                        echo $content_div; 
                        echo '</div>';                          

                    ?>
                </div>
         <?php endwhile; endif; ?>
</section>