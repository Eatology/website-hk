<?php
/**
 * Template Name: Wellness Boutique
 */

?>

<section class="c-wellness-boutique">
    <div class="o-container">
        <h1 class="header-h1"><?php the_title(); ?></h1>
        <?php the_content(); ?>
        <!-- <p class="paragraph-text">In tellus ullamcorper pretium eget ut elit. Sit nisl, morbi a ut risus purus. Eget nisi pellentesque donec lacus, ac sed lectus quis.</p> -->
        <div class="c-wellness-boutique-cards">
            <input type="radio" name="filter-add-on" id="filter-all" checked="checked">
            <input type="radio" name="filter-add-on" id="filter-merchandise">
            <input type="radio" name="filter-add-on" id="filter-supplements">
            <div class="c-wellness-boutique-cards__actions">
                <ul class="js-optionTabs c-wellness-boutique-cards__actions-tabs">
                    <li class="js-all-btn nav-links"><label for="filter-all">All</label></li>
                    <li class="js-merchandise-btn nav-links"><label for="filter-merchandise">Merchandise</label></li>
                    <li class="js-supplements-btn nav-links"><label for="filter-supplements">Supplements</label></li>
                </ul>
                <div class="js-optionSort c-wellness-boutique-cards__actions-sort">
                    <div>
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M1.5 8C1.5 7.72386 1.72386 7.5 2 7.5H10C10.2761 7.5 10.5 7.72386 10.5 8C10.5 8.27614 10.2761 8.5 10 8.5H2C1.72386 8.5 1.5 8.27614 1.5 8Z" fill="#4A0D66"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M1.5 4C1.5 3.72386 1.72386 3.5 2 3.5H14C14.2761 3.5 14.5 3.72386 14.5 4C14.5 4.27614 14.2761 4.5 14 4.5H2C1.72386 4.5 1.5 4.27614 1.5 4Z" fill="#4A0D66"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M1.5 12C1.5 11.7239 1.72386 11.5 2 11.5H6C6.27614 11.5 6.5 11.7239 6.5 12C6.5 12.2761 6.27614 12.5 6 12.5H2C1.72386 12.5 1.5 12.2761 1.5 12Z" fill="#4A0D66"/>
                        </svg>
                        <span>Sort</span>
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.4717 5.52864C12.7321 5.78899 12.7321 6.2111 12.4717 6.47145L8.47173 10.4714C8.21138 10.7318 7.78927 10.7318 7.52892 10.4714L3.52892 6.47144C3.26857 6.2111 3.26857 5.78899 3.52892 5.52864C3.78927 5.26829 4.21138 5.26829 4.47173 5.52864L8.00033 9.05723L11.5289 5.52864C11.7893 5.26829 12.2114 5.26829 12.4717 5.52864Z" fill="#4A0D66"/>
                        </svg>
                    </div>
                    <ul class="js-optionDropdown">
                        <li class="active" data-sort="popular">Most popular</li>
                        <li data-sort="low">Price: Low to High</li>
                        <li data-sort="high">Price: High to Low</li>
                    </ul>
                </div>
            </div>
            <div class="js-card-list c-wellness-boutique-cards__lists">
            <?php
                $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => 8,
                    'product_cat' => 'add-on-product',
                    'meta_key' => 'total_sales',
                    'orderby' => 'meta_key',
                    'order' => 'ASC'
                );
                $loop = new WP_Query( $args );
                while ( $loop->have_posts() ) : $loop->the_post(); 
                global $product; 
            ?>
                <div class="js-card-item c-wellness-boutique__items" data-price="<?= $product->get_price(); ?>" data-popular="<?= get_post_meta( get_the_id(), 'total_sales', true); ?>"
                <?php
                    $main_term  = get_the_terms( $loop->post->ID, 'product_cat' );

                    foreach ( $main_term as $parent ) {
                        if ( $parent->parent != 0 ) {
                            echo 'data-cat="'.$parent->slug.'"';
                        }
                    }
                ?>
                >
                    <figure>
                        <?php 
                        if (has_post_thumbnail( $loop->post->ID )) 
                            echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog');
                        ?>
                    </figure>
                    <div class="content">
                        <div>
                            <a href="<?php the_permalink(); ?>" id="id-<?php the_id(); ?>" class="title" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                            <span class="price"><?= $product->get_price_html(); ?></span>
                        </div>
                        <?php
                            echo apply_filters(
                                'woocommerce_loop_add_to_cart_link',
                                sprintf(
                                    '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="o-btn %s product_type_%s">%s</a>',
                                    esc_url( $product->add_to_cart_url() ),
                                    esc_attr( $product->get_id() ),
                                    esc_attr( $product->get_sku() ),
                                    $product->is_purchasable() ? 'add_to_cart_button' : '',
                                    esc_attr( $product->product_type ),
                                    esc_html( $product->add_to_cart_text() )
                                ),
                                $product
                        );?>
                    </div>
                </div>
            <?php endwhile; ?>
            <?php wp_reset_query(); ?>
            </div>
        </div>
    </div>
</section>