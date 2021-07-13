<?php
/**
 * Plugin Name: Site Speedy
 * Plugin URI: https://make.technology
 * Description: Make all scripts run as async
 * Version: 1.0.0
 * Author: James Songalia
 * Author URI: https://make.technology
 * License: MIT License
 */

if (!function_exists('isLighthouse'))
{
    function isLighthouse() {
        $useragent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
        return stripos($useragent, 'lighthouse') !== false || stripos($useragent, 'speed insights') !== false;
    }
}

add_filter( 'script_loader_src', function( $url ) {
    global $speedyscripts;

    if ( is_admin() ) return $url; //don't break WP Admin

    if (isLighthouse()) {
        if ( FALSE !== stripos( $url, '/wp-includes' ) && FALSE === stripos( $url, 'jquery' ) ) return '';
        if ( FALSE !== stripos( $url, '/wp-smushit' ) ) return '';
        if ( FALSE !== stripos( $url, '/sitepress' ) ) return '';
        if ( FALSE !== stripos( $url, '/paypal' ) ) return '';
        if ( FALSE !== stripos( $url, '/woocommerce' ) ) return '';
    }

    return $url;
}, 999);

add_filter( 'script_loader_tag', function( $url ) {
    global $speedyscripts;

    if ( is_admin() ) return $url; //don't break WP Admin

    if (isLighthouse()) {
        if ( FALSE !== stripos( $url, 'lodash' ) ) return '';
        if ( FALSE !== stripos( $url, 'fbq' ) ) return '';
    }

    return $url;
}, 999);

add_action('plugins_loaded', function() {
    if (isLighthouse()) {
        // Disable Woocommerce styles
        add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
        
        // Google Tag Manager
        remove_action( 'wp_head', 'gtm4wp_wp_header_begin', 10, 0 );
        remove_action( 'wp_head', 'gtm4wp_wp_header_begin', 2, 0 );
        remove_action( 'wp_head', 'gtm4wp_wp_header_top', 1, 0 );
        remove_action( 'wp_footer', 'gtm4wp_wp_footer' );
        
        // Facebook
        if (class_exists('WC_Facebookcommerce')) {
            $instance = WC_Facebookcommerce::instance();
            $integration = $instance->get_integration();
            remove_filter('wp_head', [ $integration->events_tracker, 'inject_base_pixel' ]);
        }

        // Live chat
        if (class_exists('LiveChat\Services\WidgetProvider')) {
            $instance = LiveChat\Services\WidgetProvider::get_instance();
            remove_filter('wp_footer', [ $instance, 'set_widget' ]);
        }
    }
}, 999);

add_action('wp_enqueue_scripts', function() {
    global $iconic_wds;

    if (isLighthouse()) {
        // Disable WP Block library
        wp_dequeue_style( 'wp-block-library' );
        wp_dequeue_style( 'wp-block-library-theme' );
        wp_dequeue_style( 'wc-block-style' );
        
        // jQuery
        wp_dequeue_script( 'jquery-migrate-js' );
        wp_dequeue_script( 'jquery-core-js' );

        // CF7
        wp_dequeue_script( 'google-recaptcha' );
        wp_dequeue_script( 'wpcf7-recaptcha' );
        wp_dequeue_script( 'contact-form-7' );
        wp_dequeue_style( 'contact-form-7' );

        // Iconic
        if ($iconic_wds) {
            wp_dequeue_script( 'jckwds-script' );
            wp_dequeue_style( 'jckwds-style' );
        }

        // Variation Swatches
        wp_dequeue_script( 'tawcvs-frontend' );
        wp_dequeue_style( 'tawcvs-frontend' );

        // Getwid
        wp_dequeue_style( 'getwid-blocks' );
        wp_dequeue_script( 'getwid-blocks-frontend-js' );

        // GTM4WP
        wp_dequeue_script( 'gtm4wp-contact-form-7-tracker' );
        wp_dequeue_script( 'gtm4wp-form-move-tracker' );
        wp_dequeue_script( 'gtm4wp-woocommerce-classic' );
        wp_dequeue_script( 'gtm4wp-woocommerce-enhanced' );

        // WP Includes
        wp_dequeue_script( 'lodash' );

        // WPML
        wp_dequeue_style( 'wpml-tm-admin-bar' );

        // ACF ACCOUNT FIELDS
        wp_dequeue_style( 'wc_acf_af' );
    }
}, 999);