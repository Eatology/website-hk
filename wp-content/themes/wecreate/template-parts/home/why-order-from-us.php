<section id="why-order-from-us" class="top-section">
    <?php 
        // repeater inside a group
        if( have_rows('why_order') ): while ( have_rows('why_order') ) : the_row(); ?>
        <h1><?php echo get_sub_field('title'); ?></h1>
        <p class="text-small-center"><?php echo get_sub_field('content'); ?></p>

    <?php if( have_rows('why_icons') ): ?>
        <div class="why-icons">
            <?php
                while ( have_rows('why_icons') ) : the_row();       
                    $icon       = get_sub_field('icon'); 
                    $icon_label = get_sub_field('icon_label');  ?>
                    <div class="icon-group">
                        <?php echo $icon;?>
                        <h3><?php echo $icon_label;?></h3>
                    </div>
                <?php endwhile; ?>
                </div>
            <?php endif;

        endwhile; endif;
    ?>
</section>