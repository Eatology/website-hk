<?php
  $meal_slider = new WP_Query(
    array(
      'post_type'       => 'product',
      'post_status'     => 'publish',
      'orderby'         => 'post_date',
      'order'           => 'DESC',
      'posts_per_page'  => 12,
      'tax_query' => array(
        array(
            'taxonomy' => 'product_cat',
            'field'    => 'term_id',
            'terms'    => array( 1727, 1868 ),
            'operator' => 'NOT IN',
        ),
    ),
    )
  );
  $slide_count = 1;

  $meal_circle = svg_circle('meal-circle');
?>
<section id="latest-meal-plans" class="top-section">
    <div class="meal-circle__wrapper rellax" data-rellax-speed="7"><?php echo $meal_circle;?></div>
    <div class="meal-content">
        <?php $meal_plans = get_field('meal_plans'); ?>
        <h2 class="h1"><?php echo $meal_plans['title']; ?></h2>

        <p class="text-small-center"><?php echo $meal_plans['content']; ?></p>
    
        <div class="meal-slider carousel" data-flickity>
    <?php
      while($meal_slider->have_posts()) : $meal_slider->the_post(); ?>

      <div class="meal-slider__slide-<?php echo $slide_count; ?> meal-slider__slideitem">
        <div class="meal-slider__wrapper">
            <a href="<?php the_permalink() ?>"><?php the_post_thumbnail('wecreate_blog') ?></a>
            <div class="meal-slider__content">
                <?php 
                $terms = get_the_terms( get_the_ID(), 'product_cat' );
                foreach ($terms as $term) {
                    echo '<p>'.$term->name.'</p>';
                    break;
                }
            ?>
              <h3><a href="<?php the_permalink() ?>" class="product-header-links"><?php echo get_the_title(); ?></a></h3>
              <a href="<?php the_permalink() ?>" class="button-links button-links__product"><?php _e('Order Meal', 'eatology');?></a>

            </div>
        </div>          
      </div>
      <?php $slide_count++; ?>
    <?php endwhile; wp_reset_query(); ?>
  </div>


    </div>

</section>