<div class="entry">
    <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_post_thumbnail('wecreate_blog'); ?></a>
    <div class="entry-content">
        <?php
            if (ICL_LANGUAGE_CODE == 'zh') {
                $new_date = esc_html( get_the_date('Y年m月j日'));
            } else {
                $new_date = esc_html( get_the_date('F j, Y'));
            }
        ?>
        <p class="date"><?php echo $new_date ?></p>
        <h2 class="h3"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
        <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>" class="button-links"><?php _e('Read More', 'eatology'); ?></a>

    </div>
</div>
