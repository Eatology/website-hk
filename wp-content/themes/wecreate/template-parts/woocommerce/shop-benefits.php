
<?php
    $meal_plan_id = apply_filters('wpml_object_id', 451, 'page', true);
?>
<section id="meal-benefits" class="top-section">
        <?php if( have_rows('shop_benefits', $meal_plan_id) ): ?>
            <div class="meal-benefits-slider">
            <?php while ( have_rows('shop_benefits', $meal_plan_id) ) : the_row(); ?>
                <?php
                    $icon   = get_sub_field('icon');  
                    $title   = get_sub_field('title');  
                    echo '<div class="meal-benefits-slider__slide">'.$icon . '<h6>'.$title.'</h6></div>';
                ?>
            <?php endwhile; ?>
            </div>
        <?php endif;?>
</section>