<?php 
    $class_cart = '';
    $class_checkout = '';
    $class_complete = '';
    if (is_cart()) {
        $class_cart = ' active';
        $class_cart_line = ' active';
        $class_checkout = '';
        $class_checkout_line = '';
        $class_complete = '';
    } elseif (is_checkout() && !is_wc_endpoint_url( 'order-received' )) {
        $class_cart = '';
        $class_cart_line = '';
        $class_checkout = ' active';
        $class_checkout_line = ' active';
        $class_complete = '';
    } elseif(is_checkout() &&  is_wc_endpoint_url( 'order-received' )) {
        $class_cart = '';
        $class_cart_line = '';
        $class_checkout = '';
        $class_checkout_line = ' active';
        $class_complete = ' active';
    }

?>

<div class="cart-header">
    <div class="cart-header__number<?php echo $class_cart;?>"><span>1</span></div>
    <div class="cart-header__divider<?php echo $class_cart_line;?>"></div>
    <div class="cart-header__number<?php echo $class_checkout;?>"><span>2</span></div>
    <div class="cart-header__divider<?php echo $class_checkout_line;?>"></div>
    <div class="cart-header__number<?php echo $class_complete;?>"><span>3</span></div>
</div>