<div class="also-like also-like-wrapper">
    <?php
        // $terms = wp_get_post_tags($post->ID);
        // $term_array = []; 

        // foreach( $terms as $term ) {
        //     // $term_array[] = $term->slug;
        //     array_push($term_array, $term->slug);
        // }

        $current_cat = get_the_category($post->ID);

        $also_args = array(
        'post_type'       => 'post',
        'post_status'     => 'publish',
        'orderby'         => 'publish_date',
        'order'           => 'DESC,',
        'posts_per_page'  => 3,
        'post__not_in' 	=> array($post->ID),
        'tax_query' => array(
            array(
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => $current_cat[0]->slug,
            )
            )
        );    
        
        $also_posts = new WP_Query($also_args);

        if ($also_posts->found_posts > 0):
    ?>    
    <h3><?php echo get_field('also_like_title', 'option');?></h3>
    <div class="also-like__articles">
    <?php 
       while ( $also_posts->have_posts() ) : $also_posts->the_post(); ?>
            <div class="also-like__article">
                <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_post_thumbnail('wecreate_category_portrait'); ?></a>

                <h3><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>

            </div>
        <?php endwhile; wp_reset_postdata();?>
    </div>
    <?php 
        endif; 
    ?>
</div>