<header>

    <div class="entry-meta">
        <div class="date">
            <?php
                if (ICL_LANGUAGE_CODE == 'zh') {
                    $new_date = esc_html( get_the_date('Y年m月j日'));   
                } else {
                    $new_date = esc_html( get_the_date('F j, Y'));
                }
            ?>
            <time class="entry-date published" datetime="<?php echo get_the_date( DATE_W3C );?>" itemprop="datePublished"><?php echo $new_date;?></time>
        </div>
    </div>


    <h1 itemprop="headline"><?php the_title();?></h1>

    <div class="header-author">
        <span class="category">
                <?php 
                $categories = get_the_category();
 
                if ( ! empty( $categories ) ) {
                    echo esc_html( $categories[0]->name );   
                }
                ?>
        </span>

        <span class="byline">
            <?php _e('Written by', 'eatology');?> 
            <span class="author vcard" itemprop="author" itemscope="" itemtype="http://schema.org/Person">
                    <span itemprop="name"><?php echo esc_html( get_the_author() );?></span>
            </span>
        </span>
    </div>


 



</header>