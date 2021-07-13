<?php
  /**
   * Template Name: My Account
   */

    $delivery_class = '';
    if ( is_user_logged_in() ) {
        $my_account_wrapper_class = "account-wrapper";
        $my_account_content_class = "account-content";
        $my_account_header        = '<h1>'.__('Your Account', 'eatology').'</h1>';
    } else {
        $my_account_wrapper_class = "login-wrapper";
        $my_account_content_class = "login-content";
        $my_account_header        = '';
    }

    if (is_wc_endpoint_url( 'delivery-calendar')) {
        $delivery_class = ' delivey-calendar-account';
    }
  

?>
<section id="my-account-page" class="white-header">
    <div class="<?php echo $my_account_wrapper_class ;?>">
        <?php 
            if ( !is_user_logged_in() ) : 
                echo '<div class="login-image">';
                if (is_wc_endpoint_url( 'lost-password')) {
                    $lost_password_image = get_field('lost_password_image'); 
                    if( !empty( $lost_password_image ) ):
                        echo '<img src="'.esc_url($lost_password_image['sizes']['wecreate_half_cover']).'" alt="'.esc_attr($lost_password_image['alt']).'" />';
                    endif; 
                } else {
                    $login_image_url    = get_field('login_image'); 
                    $register_image_url = get_field('register_image'); 
                    if( !empty( $login_image_url ) ):
                        echo  '<img class="login-image" src="'.esc_url($login_image_url['sizes']['wecreate_half_cover']).'" alt="'.esc_attr($login_image_url['alt']).'" />';
                    endif; 
                    if( !empty( $register_image_url ) ):
                        echo  '<img class="register-image" src="'.esc_url($register_image_url['sizes']['wecreate_half_cover']).'" alt="'.esc_attr($register_image_url['alt']).'" />';
                    endif;                                     
                }
            echo '</div>';
        ?>
            
        <?php endif ?>
        <div class="<?php echo $my_account_content_class . $delivery_class;?>">

            <?php if (is_wc_endpoint_url( 'delivery-calendar')): ?>
                <div class="delivery-calendar-header">
                    <a href="/my-account" class="subscriptions-go-back"><span class="icon-icon-back"></span> <?php esc_html_e( 'Back to Your Account', 'woocommerce-subscriptions' ); ?></a>
                    <div class="delivery-calendar-status">
                        <?php esc_html_e( 'Days on Hold:', 'eatology' ); ?> <span id="days-available">-</span>
                        <?php esc_html_e( 'Status:', 'eatology' ); ?> <span id="vip-status">-</span>
                    </div>
                </div>
            <?php else: ?> 
                <?php echo $my_account_header ;?>
            <?php endif; ?>
            <?php
                while ( have_posts() ) : the_post();
                the_content();
                endwhile; // End of the loop.
            ?>
        </div>
    </div>




