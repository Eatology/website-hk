<section id="error-page" class="white-header">
    <div class="error-wrapper">
        <div class="error-image">
                <?php $image = get_field('error_image', 'option'); ?>
                <img src="<?php echo $image['sizes']['wecreate_half_cover'];?>" alt="<?php the_field('error_title', 'option'); ?>" />
        </div>
            
        <div class="error-content">
            <h1><?php the_field('error_title', 'option'); ?></h1>
            <p><?php the_field('error_description', 'option'); ?></p>
            <a href="/" class="return-link"><?php _e('Go back to Homepage', 'eatology');?></a>
        </div>
    </div>
</section>
