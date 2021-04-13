<?php

// $current_page_id = apply_filters('wpml_object_id', get_the_ID(), 'page', true);

$main_section = get_field('main_section', get_the_ID());

$image_1 = $main_section['image_1'];
$image_2 = $main_section['image_2'];
$image_3 = $main_section['image_3'];
$image_4 = $main_section['image_4'];
$image_5 = $main_section['image_5'];
$image_6 = $main_section['image_6'];

// diet assessment tool page
$diet_assessment_page_id = apply_filters('wpml_object_id', 2936, 'page', true);

// meal plans - shop page
$shop_page_id = apply_filters('wpml_object_id', 451, 'page', true);

?>
<section id="main-section" class="top-section">
  <?php if (!empty($image_1)) : ?>
    <img data-rellax-speed="-6" class="parallax-home rellax image-1" src="<?php echo esc_url($image_1['sizes']['wecreate_blog']); ?>" alt="<?php echo esc_attr($image_1['alt']); ?>" />
  <?php endif; ?>
  <?php if (!empty($image_2)) : ?>
    <img data-rellax-speed="7" class="parallax-home rellax image-2" src="<?php echo esc_url($image_2['sizes']['medium']); ?>" alt="<?php echo esc_attr($image_2['alt']); ?>" />
  <?php endif; ?>
  <?php if (!empty($image_3)) : ?>
    <img data-rellax-speed="8" class="parallax-home rellax image-3" src="<?php echo esc_url($image_3['sizes']['wecreate_product']); ?>" alt="<?php echo esc_attr($image_3['alt']); ?>" />
  <?php endif; ?>
  <?php if (!empty($image_4)) : ?>
    <img data-rellax-speed="4" class="parallax-home rellax image-4" src="<?php echo esc_url($image_4['sizes']['medium']); ?>" alt="<?php echo esc_attr($image_4['alt']); ?>" />
  <?php endif; ?>
  <?php if (!empty($image_5)) : ?>
    <img data-rellax-speed="6" class="parallax-home rellax image-5" src="<?php echo esc_url($image_5['sizes']['medium']); ?>" alt="<?php echo esc_attr($image_5['alt']); ?>" />
  <?php endif; ?>
  <?php if (!empty($image_6)) : ?>
    <img data-rellax-speed="-4" class="parallax-home rellax image-6" src="<?php echo esc_url($image_6['sizes']['wecreate_blog']); ?>" alt="<?php echo esc_attr($image_6['alt']); ?>" />
  <?php endif; ?>
  <div class="hero-content">
    <h1 class="large-header"><?php echo $main_section['headline']; ?></h1>

    <p class="text-small-center"><?php echo $main_section['blurb']; ?></p>

    <div class="main-section__buttons">
      <a href="<?php echo get_the_permalink($diet_assessment_page_id); ?>" title="<?php echo $main_section['button_1_text']; ?>" class="button-links button-links__alternate"><?php echo $main_section['button_1_text']; ?></a>
      <span class="spacer"></span>
      <a href="<?php echo get_the_permalink($shop_page_id); ?>" title="<?php echo $main_section['button_2_text']; ?>" class="button-links"><?php echo $main_section['button_2_text']; ?></a>
    </div>
  </div>
  <div class="curve"></div>


</section>