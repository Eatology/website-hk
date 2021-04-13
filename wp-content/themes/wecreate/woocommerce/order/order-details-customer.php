<?php

/**
 * Order Customer Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-customer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.4
 */

defined('ABSPATH') || exit;

$show_shipping = !wc_ship_to_billing_address_only() && $order->needs_shipping_address();
?>
<section class="woocommerce-customer-details">

	<?php if ($show_shipping) : ?>

		<section class="woocommerce-columns woocommerce-columns--2 woocommerce-columns--addresses col2-set addresses">
			<div class="woocommerce-column woocommerce-column--1 woocommerce-column--billing-address col-1">

			<?php endif; ?>

			<h2 class="woocommerce-column__title"><?php esc_html_e('Billing address', 'woocommerce'); ?></h2>

			<address>
				<?php echo $order->get_formatted_billing_full_name(); ?>
				<br>

				<?php

				$flat = get_post_meta($order->get_id(), 'billing_flat_number', true);
				$floor = get_post_meta($order->get_id(), 'billing_floor_number', true);
				$tower = get_post_meta($order->get_id(), 'billing_tower_block', true);
				$sub_district = get_post_meta($order->get_id(), 'billing_sub_district', true);

				if (!empty($flat)) {
					echo $flat . ", ";
				}

				if (!empty($floor)) {
					echo $floor . ",  ";
				}

				if (!empty($tower)) {
					echo $tower . "<br>";
				}

				?>

				<?php if ($order->get_billing_address_1()) : ?>
					<?php echo esc_html($order->get_billing_address_1()) . '<br>'; ?>
				<?php endif; ?>

				<?php if ($order->get_billing_address_2()) : ?>
					<?php echo esc_html($order->get_billing_address_2()) . "<br>"; ?>
				<?php endif; ?>

				<?php
				if (!empty($sub_district)) {
					echo $sub_district . "<br>";
				}
				?>
				<?php echo esc_html($order->get_billing_state()) . "<br>"; ?>

				<?php
				$asiamiles = get_post_meta($order->get_id(), 'billing_asia_miles', true);
				if (!empty($asiamiles)) {
					echo __('Asia Miles: ', 'woocommerce') . $asiamiles;
				}
				?>


				<?php if ($order->get_billing_phone()) : ?>
					<p class="woocommerce-customer-details--phone" style="margin-top: 10px;margin-bottom:0px"><?php echo esc_html($order->get_billing_phone()); ?></p>
				<?php endif; ?>

				<?php if ($order->get_billing_email()) : ?>
					<p class="woocommerce-customer-details--email" style="margin-top: 0px;margin-bottom:10px"><?php echo esc_html($order->get_billing_email()); ?></p>
				<?php endif; ?>
			</address>

			<?php if ($show_shipping) : ?>

			</div><!-- /.col-1 -->

			<div class="woocommerce-column woocommerce-column--2 woocommerce-column--shipping-address col-2">
				<h2 class="woocommerce-column__title"><?php esc_html_e('Shipping address', 'woocommerce'); ?></h2>
				<address>

					<?php echo $order->get_formatted_shipping_full_name(); ?>
					<br>

					<?php

					$flat = get_post_meta($order->get_id(), 'shipping_flat_number', true);
					$floor = get_post_meta($order->get_id(), 'shipping_floor_number', true);
					$tower = get_post_meta($order->get_id(), 'shipping_tower_block', true);
					$sub_district = get_post_meta($order->get_id(), 'shipping_sub_district', true);

					if (!empty($flat)) {
						echo $flat . ", ";
					}

					if (!empty($floor)) {
						echo $floor . ",  ";
					}

					if (!empty($tower)) {
						echo $tower . "<br>";
					}

					?>

					<?php if ($order->get_shipping_address_1()) : ?>
						<?php echo esc_html($order->get_shipping_address_1()) . '<br>'; ?>
					<?php endif; ?>

					<?php if ($order->get_shipping_address_2()) : ?>
						<?php echo esc_html($order->get_shipping_address_2()) . "<br>"; ?>
					<?php endif; ?>

					<?php
					if (!empty($sub_district)) {
						echo $sub_district . "<br>";
					}
					?>
					<?php echo esc_html($order->get_shipping_state()) . "<br>"; ?>

					<?php
					$asiamiles = get_post_meta($order->get_id(), 'shipping_asia_miles', true);
					if (!empty($asiamiles)) {
						echo __('Asia Miles: ', 'woocommerce') . $asiamiles;
					}
					?>

				</address>
			</div><!-- /.col-2 -->

		</section><!-- /.col2-set -->

	<?php endif; ?>

	<?php do_action('woocommerce_order_details_after_customer_details', $order); ?>

</section>