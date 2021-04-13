<?php
/**
 * Template Name: About Us
 */

// Need to loop for gutenberg the_content()
?>
<style>
    .main-header {
        background: url('<?php the_post_thumbnail_url('full') ?>') center center no-repeat;
    }
    @media (max-width: 600px) {
        .main-header {
            background: url('<?php the_post_thumbnail_url('wecreate_mobile_cover') ?>') left center no-repeat;
        }
    }
</style>
<section class="main-header">
</section>



<?php 
    include "template-parts/main-details.php"; 
    include "template-parts/about/quote-and-repeater.php"; 
    include "template-parts/about/partners.php"; 
?>