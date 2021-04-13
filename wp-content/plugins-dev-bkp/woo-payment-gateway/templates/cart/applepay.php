<?php
/**
 * @version 3.2.5
 * @package Braintree/Templates
 */
?>
<div class="wc-braintree-applepay-cart-checkout-container">
	<?php
	wc_braintree_get_template( 'applepay-button.php', array(
		'gateway' => $gateway,
		'button'  => $gateway->get_option( 'button' ),
		'type'    => $gateway->get_option( 'button_type_cart' )
	) ) ?>
</div>

