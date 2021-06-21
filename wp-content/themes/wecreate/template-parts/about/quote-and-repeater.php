<section id="quote-and-repeater" class="top-section">
    <?php 
        // repeater inside a group
        if( have_rows('about-us') ): while ( have_rows('about-us') ) : the_row(); ?>        
            <h3 class="h1"><?php echo get_sub_field('quote'); ?></h1>

    <?php if( have_rows('about_details') ): ?>
            <?php
                $about_side = 1;
                while ( have_rows('about_details') ) : the_row();       
                    $title       = get_sub_field('title'); 
                    $content = get_sub_field('content');  
                    $image = get_sub_field('image');  

                    $image_tag = '';
                    $bg_circle = '<div class="about-circle-wrapper">'.svg_circle('work-circle'). '</div>';

                    if( !empty( $image ) ):
                        $image_tag  = '<div class="img-wrapper"><img src="'.esc_url($image['sizes']['wecreate_blog']).'" alt="'.esc_attr($image['alt']).'" /></div>';
                    endif;

                    $content_div = '<div class="content-wrapper">
                            <h2>'.$title.'</h2>
                            <p>'.$content.'</p>
                        </div>';
                    ?>
                    <?php
                        if($about_side % 2 == 0){ 
                            $side = ' about-left';
                        } 
                        else{ 
                            $side = ' about-right';
                        }
                    ?>

                    <div class="about <?php echo $side;?>">
                        <?php
                            echo '<div class="svg-wrapper">'.$bg_circle.'</div>';
                            if($about_side % 2 == 0){ 
                                echo '<div class="about-content-wrapper">';
                                echo $image_tag;
                                echo $content_div; 
                                echo '</div>';                                       
                            } 
                            else{ 
                                echo '<div class="about-content-wrapper">';
                                echo $content_div;      
                                echo $image_tag;
                                echo '</div>';
                             } 
                        ?>
                    </div>
                    <?php $about_side++; ?>
                <?php endwhile; ?>
            <?php endif;
        endwhile; endif;
    ?>
</section>