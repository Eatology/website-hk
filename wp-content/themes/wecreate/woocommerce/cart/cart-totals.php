<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 2.3.6
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="cart_totals <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<form action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
		<table cellspacing="0" class="shop_table shop_table__order-summary shop_table_responsive">

			<tr class="cart-heading">
				<td colspan="2"><h3><?php esc_html_e( 'Order Summary', 'woocommerce' ); ?></h3></td>			
			</tr>

			<tr class="cart-subtotal">
				<th><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
				<td class="cart-subtotal__subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>"><?php wc_cart_totals_subtotal_html(); ?></td>
			</tr>
			
			<tr class="cart-subtotal">
				<th><?php esc_html_e( 'Shipping', 'woocommerce' ); ?></th>
				<?php 
					$cart_total = WC()->cart->get_cart_contents_total();
					if ($cart_total < 1000) {
						?>
							<td class="cart-subtotal__value">$100</td>
						<?php
					}else{
						?>
							<td class="cart-subtotal__value-free">Free Shipping</td>
						<?php
					}
				?>
				
			</tr>

			<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
				<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
					<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
					<td data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
				</tr>
			<?php endforeach; ?>	

			<?php if ( wc_coupons_enabled() ) { ?>
				<tr class="cart-order-coupon">
					<td colspan="2">
						<div>
							<div>
								<label for="coupon_code"><?php esc_html_e( 'COUPON CODE', 'woocommerce' ); ?></label>
								<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Enter coupon code', 'woocommerce' ); ?>" />
							</div>
							<button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply', 'woocommerce' ); ?>"><?php esc_attr_e( 'Apply', 'woocommerce' ); ?></button>
						</div>
					</td>
				</tr>
					<?php do_action( 'woocommerce_cart_coupon' ); ?>
			<?php } ?>

			<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

			<tr class="order-total">
				<th><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
				<td data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>"><?php wc_cart_totals_order_total_html(); ?></td>
			</tr>

			<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

			

		</table>
	</form>

	<div class="wc-proceed-to-checkout">
		<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
	</div>

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>
