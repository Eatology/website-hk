<section id="what-our-customers-think" class="top-section">
    <?php 
        // repeater inside a group
        if( have_rows('customers') ): while ( have_rows('customers') ) : the_row(); ?>
        <h1><?php echo get_sub_field('title'); ?></h1>
    <?php if( have_rows('customers_slider') ): ?>
        <div class="customers-slider">
            <?php
                while ( have_rows('customers_slider') ) : the_row();       
                    $title   = get_sub_field('title'); 
                    $content = get_sub_field('content'); 
                    $name    = get_sub_field('name');  
                    $image   = get_sub_field('image');  
                    if( !empty( $image ) ):
                        $image_tag  = '<img class="customer-image" src="'.esc_url($image['sizes']['wecreate_search']).'" alt="'.esc_attr($image['alt']).'" />';
                    endif;                    
                    
                    ?>
                    <div class="customer">
                        <?php echo $image_tag;?>
                        <div class="customer-content">
                            <span class="icon-icon-quotation"></span>
                            <h3><?php echo $title;?></h3>
                            <p>“<?php echo $content;?>”</p>
                            <h4><?php echo $name;?></h4>
                        </div>
                        
                    </div>
                <?php endwhile; ?>
                </div>
            <?php endif;

        endwhile; endif;
    ?>
</section>