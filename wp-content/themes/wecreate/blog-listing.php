<?php

/**
 * Template Name: Blog listing
 */

if (ICL_LANGUAGE_CODE == 'zh') {
    $blog_page_id = 3115;
} else {
    $blog_page_id = 87;
}

$categories = get_categories();

// var to store categories slugs
$storedCat = [];
foreach ($categories as $cat) {
    array_push($storedCat, $cat->slug);
}

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$taxonomy = '';
$param = 'all';

if (isset($_GET['filter']) && !empty($_GET['filter'])) {
    $param = $_GET['filter'];
    if (in_array($param, $storedCat)) {
        $taxonomy = array(
            'taxonomy' => 'category',
            'field' => 'slug',
            'terms' => $param,
        );
    } else {
        $taxonomy = '';
        $param = 'all';
    }
}

$blogs = new WP_Query(
    array(
        'post_type'       => 'post',
        'post_status'     => 'publish',
        'orderby'         => 'publish_date',
        'order'           => 'DESC,',
        'posts_per_page'  => 12,
        'paged'          => $paged,
        'tax_query' => array(
            $taxonomy
        )
    )
);
?>
<section id="blog-listing">
    <h1><?php echo the_title(); ?></h1>

    <div class="filters">
        <?php $qvar = add_query_arg('filter', 'all', get_the_permalink($blog_page_id)); ?>
        <a href="<?php echo $qvar; ?>" class="<?php echo $param == 'all' ? 'active' : ''; ?>"><?php echo __('All', 'eaatology'); ?> </a>

        <?php
        foreach ($categories as $cat) {
            $qvar = add_query_arg('filter', $cat->slug, get_the_permalink($blog_page_id)); ?>
            <a href="<?php echo $qvar; ?>" class="<?php echo $param == $cat->slug ? 'active' : ''; ?>"><?php echo $cat->name; ?></a>

        <?php } ?>

    </div>

    <?php if ($blogs->have_posts()) { ?>
        <div id="article-listing">
            <?php
            // Start the loop.
            while ($blogs->have_posts()) : $blogs->the_post();
                include "template-parts/blogs/listing.php";
            endwhile; ?>

        </div>
    <?php

        // Previous/next page navigation.
        // the_posts_pagination(
        //     array(
        //         'prev_text'          => __( 'Previous page', 'wecreate' ),
        //         'next_text'          => __( 'Next page', 'wecreate' ),
        //         'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'wecreate' ) . ' </span>',
        //     )
        // );



        $total_pages = $blogs->max_num_pages;

        if ($total_pages > 1) {

            $current_page = max(1, get_query_var('paged'));
            $big = 999999999; // need an unlikely integer
            $param = 'all';
            if (isset($_GET['filter']) && !empty($_GET['filter'])) {
                $param = $_GET['filter'];
            }
            echo '<div class="custom_pagination">';
            echo paginate_links(array(
                // 'base' => get_pagenum_link(1) . '%_%',
                // 'format' => '/page/%#%',
                'format' => '?page=%#%',
                'current' => $current_page,
                'total' => $total_pages,
                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'prev_text'    => __('<span class="icon-chevron-down"></span>', 'eatology'),
                'next_text'    => __('<span class="icon-chevron-down"></span>', 'eatology'),
                'add_args' => array('filter' => $param)
            ));
            echo '</div>';
        }



        // If no blogs.
    } else {
        echo '<p>' .  __("No Blogs found.", "eatology") . '</p>';
    }
    ?>
</section>