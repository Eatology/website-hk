<?php
/**
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}

$main_term  = get_the_terms( get_the_ID(), 'product_cat' );
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '' . $main_term[0]->slug, $product ); ?>>

	<?php
	/**
	 * Hook: woocommerce_before_single_product_summary.
	 *
	 * @hooked woocommerce_show_product_sale_flash - 10
	 * @hooked woocommerce_show_product_images - 20
	 */
	do_action( 'woocommerce_before_single_product_summary' );
	?>

	<div class="summary entry-summary">
		<?php
		/**
		 * Hook: woocommerce_single_product_summary.
		 *
		 * @hooked woocommerce_template_single_title - 5
		 * @hooked woocommerce_template_single_rating - 10
		 * @hooked woocommerce_template_single_price - 10
		 * @hooked woocommerce_template_single_excerpt - 20
		 * @hooked woocommerce_template_single_add_to_cart - 30
		 * @hooked woocommerce_template_single_meta - 40
		 * @hooked woocommerce_template_single_sharing - 50
		 * @hooked WC_Structured_Data::generate_product_data() - 60
		 */
		do_action( 'woocommerce_single_product_summary' );

        /**
         * Hook: woocommerce_after_single_product_summary.
         *
         * @hooked woocommerce_output_product_data_tabs - 10
         * @hooked woocommerce_upsell_display - 15
         * @hooked woocommerce_output_related_products - 20
         */

        if ($main_term[0]->slug === 'add-on-product') {
            do_action( 'woocommerce_after_single_product_summary' );
        }

        $rows = get_field('accordion_content');
        if( $rows ) : ?>
        <div class="addons-accordion">
            <?php foreach( $rows as $key => $row ) : ?>
            <div class="addons-tabs">
                <div class="addons-tabs__tab">
                    <input type="checkbox" id="chck<?=$key;?>">
                    <label class="tab-label" for="chck<?=$key;?>"><?= $row['title']; ?></label>
                    <div class="tab-content">
                        <?= $row['content']; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
	</div>

    <?php
        if ($main_term[0]->slug != 'add-on-product') {
            do_action( 'woocommerce_after_single_product_summary' );
        } elseif ($main_term[0]->slug === 'add-on-product') {
            $crosssell_ids = get_post_meta( get_the_ID(), '_crosssell_ids' ); 
            $crosssell_ids = $crosssell_ids[0];

            if(count($crosssell_ids)>0) :
?>
    <div class="product-cross-sells c-wellness-boutique">
        <h2>You might also like</h2>
        <div class="c-wellness-boutique-cards__lists">
                <?php
                        $args = array( 'post_type' => 'product', 'posts_per_page' => 3, 'post__in' => $crosssell_ids );
                        $loop = new WP_Query( $args );
                        while ( $loop->have_posts() ) : $loop->the_post();
                ?>

                    <div class="c-wellness-boutique__items">
                        <a class="c-wellness-boutique__items--image" href="<?php the_permalink(); ?>">
                            <figure>
                                <?php 
                                if (has_post_thumbnail( $loop->post->ID )) 
                                    echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog');
                                ?>
                            </figure>
                        </a>
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

                <?php
                    endwhile;
                    endif;
                ?>

        </div>
    </div>
  <?php } ?>

</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
<?php if ( $main_term[0]->slug === 'add-on-product' || $main_term[0]->slug === 'add-on-product-zh' ) : ?>
<script>
    shortDescription = document.querySelector('.woocommerce-product-details__short-description > p');
    content = document.querySelector('.entry-content');
    add = document.querySelector('form.cart div.quantity');
    
    shortDescription.remove;
    content.prepend(shortDescription);
    add.insertAdjacentHTML('afterbegin', '<a href="javascript:void(0);" class="value-button" id="decrease" onclick="decreaseValue()" value="Decrease Value"></a>');
    add.insertAdjacentHTML('beforeend', '<a href="javascript:void(0);" class="value-button" id="increase" onclick="increaseValue()" value="Increase Value"></a>');

    function increaseValue() {
        var value = parseInt(document.querySelector('.quantity .qty').value, 10);
        value = isNaN(value) ? 0 : value;
        value++;
        document.querySelector('.quantity .qty').value = value;
    }

    function decreaseValue() {
        var value = parseInt(document.querySelector('.quantity .qty').value, 10);
        value = isNaN(value) ? 0 : value;
        value < 1 ? value = 1 : '';
        value--;
        document.querySelector('.quantity .qty').value = value;
    }

    // For somewhat Reason its not working on mobile, while working fine in Desktop
    // window.addEventListener('load', (event) => {
    //     dots = document.querySelector('ol.flex-control-nav').cloneNode(true);
    //     image = document.querySelector('div.flex-viewport');
    //     thumb = document.querySelectorAll('.flex-control-thumbs > li');

    //     if (dots) {
    //         image.appendChild(dots);
    //     }

    //     thumb.forEach( (elem, i) => {
    //         image.querySelector('.flex-control-thumbs li:first-child').classList.add('active');

    //         elem.addEventListener('click', e => {
                
    //             image.querySelectorAll('.flex-control-thumbs li').forEach(list => {
    //                 list.classList.remove('active');
    //             });
                
    //             image.querySelector('.flex-control-thumbs li:nth-child( '+ (i+1) +' )').classList.add('active');
    //         });
            
    //     });
    // });

    jQuery(window).on('load', function(){
        let dots = jQuery('.flex-control-nav').clone(),
            image = jQuery('div.flex-viewport'),
            thumb = jQuery('.flex-control-thumbs > li');

        if (jQuery('.flex-control-nav').length > 0) {
            image.append(dots);
            image.find('.flex-control-thumbs > li:first-child').addClass('active');

            thumb.each(function(i){
                jQuery(this).on('click touchstart', function(){
                    image.find('.flex-control-thumbs li').removeClass('active');
                    image.find('.flex-control-thumbs li:nth-child( '+ (i+1) +' )').addClass('active');
                });
            });
        }
    });
   

</script>
<?php endif; ?>