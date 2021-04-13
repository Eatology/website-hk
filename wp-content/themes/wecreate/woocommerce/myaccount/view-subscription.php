<?php
/**
 * View Subscription
 *
 * Shows the details of a particular subscription on the account page
 *
 * @author  Prospress
 * @package WooCommerce_Subscription/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wc_print_notices();

/**
 * Gets subscription details table template
 * @param WC_Subscription $subscription A subscription object
 * @since 2.2.19
 */
echo "<div class='view-subscriptions-details'>";
do_action( 'woocommerce_subscription_details_table', $subscription );

/**
 * Gets subscription totals table template
 * @param WC_Subscription $subscription A subscription object
 * @since 2.2.19
 */
echo "<div class='shop-subscriptions-details'>";
do_action( 'woocommerce_subscription_totals_table', $subscription );
echo "</div>";
echo "<div class='customer-subscriptions-details'>";
echo '<div class="myaccount-line-address"></div>';
wc_get_template( 'order/order-details-customer.php', array( 'order' => $subscription ) );
echo "</div>";
echo "</div>";
?>

<div class="clear"></div>
