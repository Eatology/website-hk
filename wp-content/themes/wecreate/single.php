<?php
/**
 * The template for displaying all single posts
 *
 */

while ( have_posts() ) : the_post(); ?>
<div class="article-wrapper">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article" itemprop="mainEntity" itemscope itemtype="http://schema.org/BlogPosting">
    <?php 
        $is_article = in_category('article');
        $is_q_and_a = in_category('q-and-a');
        $is_recipe = in_category('recipe');
        $is_step_by_step = in_category('step-by-step');

        if ($is_article || $is_q_and_a || $is_step_by_step) {
            include "template-parts/blogs/hero.php"; 
            include "template-parts/blogs/header.php"; 
            include "template-parts/blogs/content.php"; 
        }

        if ($is_recipe) {
            include "template-parts/blogs/header-recipe.php"; 
        }        

        include "template-parts/blogs/also-like.php";

    endwhile; // End of the loop.
    ?>

    </article>
</div>