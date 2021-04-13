<?php

/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}
?>
<li <?php wc_product_class('', $product); ?>>
    <div class="meal-slider__wrapper">
        <?php
        /**
         * Hook: woocommerce_before_shop_loop_item.
         *
         * @hooked woocommerce_template_loop_product_link_open - 10
         */
        // do_action('woocommerce_before_shop_loop_item');
        /**
         * Hook: woocommerce_before_shop_loop_item_title.
         *
         * @hooked woocommerce_show_product_loop_sale_flash - 10
         * @hooked woocommerce_template_loop_product_thumbnail - 10
         */
        do_action('woocommerce_before_shop_loop_item_title');
        echo '<div class="meal-slider__content">';

        global $post;
        $terms = get_the_terms(get_the_ID(), 'product_cat');
        if ($terms && !is_wp_error($terms)) :
            if (!empty($terms)) {
                echo '<p>' . $terms[0]->name . '</p>';
            }
        endif;

        /**
         * Hook: woocommerce_shop_loop_item_title.
         *
         * @hooked woocommerce_template_loop_product_title - 10
         */
        do_action('woocommerce_shop_loop_item_title');

        /**
         * Hook: woocommerce_after_shop_loop_item_title.
         *
         * @hooked woocommerce_template_loop_rating - 5
         * @hooked woocommerce_template_loop_price - 10
         */
        do_action('woocommerce_after_shop_loop_item_title');
        // echo '<a href="'.get_the_permalink($post->ID).'" class="button-links button-links__product">'.__('View Details', 'eatology').'</a>';

        ?>
        <div class="price">
            <?php 
               echo $product->get_price_html();
            ?>
        </div>
        <div class="up-sell-btn-wrapper">
            <div class="quantity_wrapper">
                <span class="qty_text"> <?php echo __('Qty','eatology');?> </span> &nbsp;
                <button class="minus"><span class="icon-minus"></span></button>
                <input type="text" disabled="disabled" size="2" value="1" id="count" data-product-id="<?php echo $product->get_id() ?>" data-in-cart="<?php echo (Check_if_product_in_cart($product->get_id())) ? Check_if_product_in_cart($product->get_id())['in_cart'] : 0;
                                                                                                                                                        ?>" data-in-cart-qty="<?php echo (Check_if_product_in_cart($product->get_id())) ? Check_if_product_in_cart($product->get_id())['QTY'] : 0;
                            ?>" class="quantity  qty" max_value=<?php echo $product->get_max_purchase_quantity(); ?> min_value=<?php echo $product->get_min_purchase_quantity(); ?> min="1">

                <button type="button" class="plus"><span class="icon-plus"></span></button>
            </div>

            <div class="up-sells-add-to-cart-wrapper">
                <a href="<?php echo get_site_url(); ?>/?add-to-cart=<?php echo $product->get_id(); ?>&quantity=1" class="button-links button-links__product up-sells-add-to-cart"> <?php echo __('Add to Cart', 'eatology'); ?> </a>
            </div>

        </div>

        <?php
        /**
         * Hook: woocommerce_after_shop_loop_item.
         *
         * @hooked woocommerce_template_loop_product_link_close - 5
         * @hooked woocommerce_template_loop_add_to_cart - 10
         */
        // do_action('woocommerce_after_shop_loop_item');
        ?>
    </div>
    </div>
</li>