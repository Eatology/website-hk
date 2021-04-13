<?php
/**
 * Subscription details table
 *
 * @author  Prospress
 * @package WooCommerce_Subscription/Templates
 * @since 2.6.0
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

		<?php
		foreach ( $subscription->get_items() as $item_id => $item ) {
			$_product  = apply_filters( 'woocommerce_subscriptions_order_item_product', $item->get_product(), $item );
			if ( apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
				?>
						<span class="subscription-meta"><?php esc_html_e( 'Product', 'woocommerce-subscriptions' ); ?>:</span>
						<span class="subscription-meta-product">
						<?php
						
						if ( $_product && ! $_product->is_visible() ) {
							echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', $item['name'], $item, false ) );
						} else {
							echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', sprintf( '<a href="%s">%s</a>', get_permalink( $item['product_id'] ), $item['name'] ), $item, false ) );
						}
						echo '</span>';

						/**
						 * Allow other plugins to add additional product information here.
						 *
						 * @param int $item_id The subscription line item ID.
						 * @param WC_Order_Item|array $item The subscription line item.
						 * @param WC_Subscription $subscription The subscription.
						 * @param bool $plain_text Wether the item meta is being generated in a plain text context.
						 */
						do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $subscription, false );

						wcs_display_item_meta( $item, $subscription );

						/**
						 * Allow other plugins to add additional product information here.
						 *
						 * @param int $item_id The subscription line item ID.
						 * @param WC_Order_Item|array $item The subscription line item.
						 * @param WC_Subscription $subscription The subscription.
						 * @param bool $plain_text Wether the item meta is being generated in a plain text context.
						 */
						do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $subscription, false );
						?>
						<span class="subscription-meta"><?php esc_html_e( 'Total', 'woocommerce-subscriptions' ); ?>:</span> <span class="subscription-meta-total"><?php echo wp_kses_post( $subscription->get_formatted_line_subtotal( $item ) ); ?></span>
				<?php
			}

		}