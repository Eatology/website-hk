<?php
if (ICL_LANGUAGE_CODE == 'zh')
{
	$shop_page_id = 3215;
}
else {
	$shop_page_id = 451;
}
?>

<section id="how-it-works" class="top-section">
    <?php
        // repeater inside a group
        if( have_rows('how_it_works') ): while ( have_rows('how_it_works') ) : the_row(); ?>
            <?php $button_text = get_sub_field('button_text'); ?>

            <h2 class="h1"><?php echo get_sub_field('title'); ?></h2>

    <?php if( have_rows('works') ): ?>
            <?php
                $how_side = 1;
                while ( have_rows('works') ) : the_row();
                    $title       = get_sub_field('title');
                    $content = get_sub_field('content');
                    $image = get_sub_field('image');
                    $svg = get_sub_field('svg');
                    if ($svg) {
                        $svg = '<div data-rellax-speed="3" data-rellax-percentage="0.3" class="rellax svg-span-wrapper">'.$svg. '</div>';
                    }
                    $image_tag = '';
                    $bg_circle = '<div data-rellax-speed="1"  class="rellax work-circle-wrapper">'.svg_circle('work-circle'). '</div>';

                    if( !empty( $image ) ):
                        $image_tag  = '<div class="img-wrapper"><img data-rellax-speed="1"  class="rellax" src="'.esc_url($image['sizes']['wecreate_blog']).'" alt="'.esc_attr($image['alt']).'" /></div>';
                    endif;

                    $content_div = '<div class="content-wrapper">
                            <h3 class="h2">'.$title.'</h3>
                            <p>'.$content.'</p>
                            <a href="'.get_the_permalink($shop_page_id). '" class="button-links">'. __($button_text, 'eatology').'</a>
                        </div>';
                    ?>
                    <?php
                        if($how_side % 2 == 0){
                            $side = ' how-right';
                        }
                        else{
                            $side = ' how-left';
                        }
                    ?>

                    <div class="work <?php echo $side;?>">
                        <?php
                            echo '<div class="svg-wrapper">'.$bg_circle  . $svg.'</div>';
                            if($how_side % 2 == 0){
                                echo '<div class="svg-wrapper">'.$bg_circle  . $svg.'</div>';
                                echo '<div class="work-content-wrapper">';
                                echo $content_div;
                                echo $image_tag;
                                echo '</div>';
                            }
                            else{
                                echo '<div class="work-content-wrapper">';
                                echo $image_tag;
                                echo $content_div;
                                echo '</div>';
                             }
                        ?>
                    </div>
                    <?php $how_side++; ?>
                <?php endwhile; ?>
            <?php endif;
        endwhile; endif;
    ?>
</section>
