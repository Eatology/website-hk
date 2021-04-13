<?php

/**
 * Email Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-addresses.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.9.0
 */

if (!defined('ABSPATH')) {
	exit;
}

$text_align = is_rtl() ? 'right' : 'left';
$address    = $order->get_formatted_billing_address();
$shipping   = $order->get_formatted_shipping_address();

?><table id="addresses" cellspacing="0" cellpadding="0" style="width: 100%; vertical-align: top; margin-bottom: 40px; padding:0;" border="0">
	<tr>
		<td style="text-align:<?php echo esc_attr($text_align); ?>; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; border:0; padding:0;" valign="top" width="50%">
			<h2><?php esc_html_e('Billing Information', 'woocommerce'); ?></h2>

			<address class="address">
				<?php echo $order->get_formatted_billing_full_name(); ?>
				<br>

				<?php

				$flat = get_post_meta($order->get_id(), 'billing_flat_number', true);
				$floor = get_post_meta($order->get_id(), 'billing_floor_number', true);
				$tower = get_post_meta($order->get_id(), 'billing_tower_block', true);
				$region = get_post_meta($order->get_id(), 'billing_region', true);


				if (!empty($flat)) {
					echo __('Flat: ', 'eatology') . $flat . ", ";
				}

				if (!empty($floor)) {
					echo __('Floor: ', 'eatology') . $floor . ", ";
				}

				if (!empty($tower)) {
					echo $tower;
				}

				echo "<br>";

				?>

				<?php if ($order->get_billing_address_1()) : ?>
					<?php echo esc_html($order->get_billing_address_1()) . '<br>'; ?>
				<?php endif; ?>

				<?php if ($order->get_billing_address_2()) : ?>
					<?php echo esc_html($order->get_billing_address_2()) . "<br>"; ?>
				<?php endif; ?>

				<?php
				if ($order->get_billing_postcode()) : ?>
					<?php 
						echo esc_html($GLOBALS['sub_districts_array'][$order->get_billing_state()][$region][$order->get_billing_postcode()]) . ', ';
						?>
				<?php endif; ?>


				<?php
				if (!empty($region)) {
					echo $region;
				}

				echo "<br>";
				?>
				<?php echo esc_html($order->get_billing_state()) . "<br><br>"; ?>

				<?php
				// $asiamiles = get_post_meta($order->get_id(), 'billing_asia_miles', true);
				// if (!empty($asiamiles)) {
				// 	echo __('Asia Miles: ', 'woocommerce') . $asiamiles . '<br>';
				// }

				$user = get_userdata($order->customer_id);
				if ($user === false) {
					$dob = __('N/A', 'woocommerce');
				} else {
					$day = get_user_meta($order->customer_id, 'birth_day', true);
					$month = get_user_meta($order->customer_id, 'birth_month', true);
					$year = get_user_meta($order->customer_id, 'birth_year', true);
					if (empty($day) || empty($month) || empty($year)) {
						$dob = __('N/A', 'woocommerce');
					} else {
						$dob = $day . ' ' . $month . ' ' . $year;
					}
				}
				echo __('Date of Birth: ', 'woocommerce') . $dob;
				?>


				<?php if ($order->get_billing_phone()) : ?>
					<p class="woocommerce-customer-details--phone" style="margin-top: 10px;margin-bottom:0px"><?php echo __('Phone: ', 'woocommerce') .  esc_html($order->get_billing_phone()); ?></p>
				<?php endif; ?>

				<?php if ($order->get_billing_email()) : ?>
					<p class="woocommerce-customer-details--email" style="margin-top: 0px;margin-bottom:10px"><?php echo esc_html($order->get_billing_email()); ?></p>
				<?php endif; ?>

			</address>
		</td>
		<?php if (!wc_ship_to_billing_address_only() && $order->needs_shipping_address() && $shipping) : ?>
			<td style="text-align:<?php echo esc_attr($text_align); ?>; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; padding:0;" valign="top" width="50%">
				<h2><?php esc_html_e('Shipping Information', 'woocommerce'); ?></h2>

				<address class="address">

					<?php echo $order->get_formatted_shipping_full_name(); ?>
					<br>

					<?php

					$flat = get_post_meta($order->get_id(), 'shipping_flat_number', true);
					$floor = get_post_meta($order->get_id(), 'shipping_floor_number', true);
					$tower = get_post_meta($order->get_id(), 'shipping_tower_block', true);
					$region = get_post_meta($order->get_id(), 'shipping_region', true);
					// $asiamiles = get_post_meta($order->get_id(), 'shipping_asia_miles', true);

					if (!empty($flat)) {
						echo __('Flat: ', 'eatology') . $flat . ", ";
					} else {
						$flat = get_post_meta($order->get_id(), 'billing_flat_number', true);
						echo __('Flat: ', 'eatology') . $flat . ", ";
					}

					if (!empty($floor)) {
						echo __('Floor: ', 'eatology') . $floor . ", ";
					} else {
						$floor = get_post_meta($order->get_id(), 'billing_floor_number', true);
						echo __('Floor: ', 'eatology') . $floor . ", ";
					}

					if (!empty($tower)) {
						echo $tower;
					} else {
						$tower = get_post_meta($order->get_id(), 'billing_tower_block', true);
						echo $tower;
					}
					echo "<br>";

					?>

					<?php if ($order->get_shipping_address_1()) : ?>
						<?php echo esc_html($order->get_shipping_address_1()) . '<br>'; ?>
					<?php endif; ?>

					<?php if ($order->get_shipping_address_2()) : ?>
						<?php echo esc_html($order->get_shipping_address_2()) . "<br>"; ?>
					<?php endif; ?>
					<?php
					if ($order->get_shipping_postcode()) : ?>
						<?php 
							echo esc_html($GLOBALS['sub_districts_array'][$order->get_shipping_state()][$region][$order->get_shipping_postcode()]) . ', ';
						?>
					<?php endif; ?>


					<?php
					if (!empty($region)) {
						echo $region;
					} else {
						$region = get_post_meta($order->get_id(), 'billing_region', true);
						echo $region;
					}
					echo "<br>";

					echo esc_html($order->get_shipping_state()) . "<br><br>"; ?>

					<?php
					// if (!empty($asiamiles)) {
					// 	echo __('Asia Miles: ', 'woocommerce') . $asiamiles . '<br>';
					// } else {
					// 	$asiamiles = get_post_meta($order->get_id(), 'billing_asia_miles', true);
					// 	echo __('Asia Miles: ', 'woocommerce') . $asiamiles . '<br>';
					// }
					?>
				</address>
			</td>
		<?php endif; ?>
	</tr>
</table>