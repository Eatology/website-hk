<?php
/**
 * View Order
 *
 * Shows the details of a particular order on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/view-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

defined( 'ABSPATH' ) || exit;

$notes = $order->get_customer_order_notes();
?>

<div class="my-account-order-wrapper">
<div class="order-heading">
	<div class="order-number"><h2><?php echo __('Order reference number:', 'woocommerce') .  ' ' .$order->get_order_number();?></h2></div>
	<div class="order-status"><span class="oval"></span> <?php echo wc_get_order_status_name( $order->get_status() );?></div>

</div>

<div class="order-date">
<?php 
		$order_date = $order->get_meta( 'jckwds_date' );
		$order_time_slot = $order->get_meta( 'jckwds_timeslot' );
	?>

	<h3><?php _e('Date', 'eatology');?></h3>
	<p class="delivery-info"><?php _e('Deliveries can be sent two days after order on regular days and three days after order from Friday 8 pm to Saturday midnight.', 'eatology');?></p>
	<p class="delivery-times"><span class="delivery-label"><?php _e('Order Placed', 'eatology');?></span><span class="delivery-detail"><?php echo wc_format_datetime( $order->get_date_created() ) ?></span></p>
	<p class="delivery-times"><span class="delivery-label"><?php _e('Delivery Date', 'eatology');?></span> <span class="delivery-detail"><?php echo $order_date ?></span></p>
	<p class="delivery-times"><span class="delivery-label"><?php _e('Delivery Time', 'eatology');?></span> <span class="delivery-detail"><?php echo $order_time_slot ?></span></p>

</div>





<?php if ( $notes ) : ?>
	<h2><?php esc_html_e( 'Order updates', 'woocommerce' ); ?></h2>
	<ol class="woocommerce-OrderUpdates commentlist notes">
		<?php foreach ( $notes as $note ) : ?>
		<li class="woocommerce-OrderUpdate comment note">
			<div class="woocommerce-OrderUpdate-inner comment_container">
				<div class="woocommerce-OrderUpdate-text comment-text">
					<p class="woocommerce-OrderUpdate-meta meta"><?php echo date_i18n( esc_html__( 'l jS \o\f F Y, h:ia', 'woocommerce' ), strtotime( $note->comment_date ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
					<div class="woocommerce-OrderUpdate-description description">
						<?php echo wpautop( wptexturize( $note->comment_content ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>
		</li>
		<?php endforeach; ?>
	</ol>
<?php endif; ?>

<?php do_action( 'woocommerce_view_order', $order_id ); ?>
</div>