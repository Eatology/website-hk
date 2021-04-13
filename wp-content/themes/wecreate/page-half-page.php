<?php
  /**
   * Template Name: General Half Page
   */


  // Need to loop for gutenberg the_content()
?>
<section id="general-page" class="half-page white-header">
  <h1><?php echo the_title();?></h1>
    <?php
      while ( have_posts() ) : the_post();
        the_content();
      endwhile; // End of the loop.
    ?>
</section>
