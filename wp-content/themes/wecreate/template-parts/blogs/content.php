<div class="article-body" itemprop="articleBody">
        <?php
        the_content(
            sprintf(
                /* translators: %s: Name of current post */
                __( 'Continue reading %s', 'wecreate' ),
                the_title( '<span class="screen-reader-text">', '</span>', false )
            )
        );

        wp_link_pages(
            array(
                'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'wecreate' ) . '</span>',
                'after'       => '</div>',
                'link_before' => '<span>',
                'link_after'  => '</span>',
                'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'wecreate' ) . ' </span>%',
                'separator'   => '<span class="screen-reader-text">, </span>',
            )
        );
        ?>  
</div><!-- .entry-content --> 