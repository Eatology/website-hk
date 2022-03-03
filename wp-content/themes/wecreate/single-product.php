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
?>
    <div class="product-cross-sells c-wellness-boutique" style="clear: both">
        <h2>You might also like</h2>
        <div class="c-wellness-boutique-cards__lists">
                <?php
                    if(count($crosssell_ids)>0){
                        $args = array( 'post_type' => 'product', 'posts_per_page' => 10, 'post__in' => $crosssell_ids );
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

                <?php endwhile; } ?>

        </div>
    </div>
  <?php } ?>

</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
<?php if ($main_term[0]->slug === 'add-on-product') : ?>
<script>
    shortDescription = document.querySelector('.woocommerce-product-details__short-description > p');
    content = document.querySelector('.entry-content');

    shortDescription.remove;
    content.prepend(shortDescription);

</script>
<?php endif; ?>