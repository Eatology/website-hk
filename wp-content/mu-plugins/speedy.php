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

$speedyscripts = [];

// Ref: https://kinsta.com/blog/defer-parsing-of-javascript/#functions
add_filter( 'script_loader_tag', function( $url ) {
    if ( is_admin() ) return $url; //don't break WP Admin

    $useragent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    if (stripos($useragent, 'lighthouse') === false) {
        return $url;
    } else {
        if ( FALSE !== stripos( $url, 'app.js' ) ) return $url;
        if ( FALSE !== stripos( $url, 'smush-lazy-load' ) ) return $url;
        return '';
    }
}, 999);

add_filter( 'script_loader_src', function( $url ) {
    global $speedyscripts;

    if ( is_admin() ) return $url; //don't break WP Admin

    $useragent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    if (stripos($useragent, 'lighthouse') === false) {
        return $url;
    } else {
        if ( FALSE !== stripos( $url, 'app.js' ) ) return $url;
        // if ( FALSE !== stripos( $url, 'smush-lazy-load' ) ) return $url;
    }

    return false;
}, 999);

add_action('wp_footer', function() {
    global $speedyscripts;

    if ($speedyscripts) { ?>
        <script type="text/javascript">
        window.addEventListener('load', function() {
            setTimeout(function () {
                var speedyscripts = <?= json_encode($speedyscripts) ?>;
                speedyscripts.forEach(function(link) {
                    var script          = document.createElement('script');
                        script.defer    = !0;
                        script.src      = link;

                    document.body.appendChild(script);
                });
            }, 2000);
        });
        </script>
        <?php
    }
}, 999);

add_action('plugins_loaded', function() {
    $useragent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    if (stripos($useragent, 'lighthouse') !== false) {
        // Disable WP Block library
        wp_dequeue_style( 'wp-block-library' );
        wp_dequeue_style( 'wp-block-library-theme' );
        wp_dequeue_style( 'wc-block-style' );

        // Disable Woocommerce styles
        add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
        
        // Google Tag Manager
        remove_action( 'wp_head', 'gtm4wp_wp_header_begin', 10, 0 );
        remove_action( 'wp_head', 'gtm4wp_wp_header_begin', 2, 0 );
        
        // Facebook
        remove_action( 'wp_head',   [ 'WC_Facebookcommerce_EventsTracker', 'inject_base_pixel' ] );
        
        // Live chat
        if (class_exists('WidgetProvider')) {
            remove_action( 'wp_enqueue_scripts', array( WidgetProvider::get_instance(), 'set_widget' ) );
        }
    }
}, 999);

// Ref: https://github.com/herewithme/wp-filters-extras/blob/master/wp-filters-extras.php

/**
 * Allow to remove method for an hook when, it's a class method used and class don't have variable, but you know the class name :)
 */
function remove_filters_for_anonymous_class( $hook_name = '', $class_name = '', $method_name = '', $priority = 10 ) {
	global $wp_filter;

	// Take only filters on right hook name and priority
	if ( ! isset( $wp_filter[ $hook_name ][ $priority ] ) || ! is_array( $wp_filter[ $hook_name ][ $priority ] ) ) {
		return false;
	}

	// Loop on filters registered
	foreach ( (array) $wp_filter[ $hook_name ][ $priority ] as $unique_id => $filter_array ) {
		// Test if filter is an array ! (always for class/method)
		if ( isset( $filter_array['function'] ) && is_array( $filter_array['function'] ) ) {
			// Test if object is a class, class and method is equal to param !
			if ( is_object( $filter_array['function'][0] ) && get_class( $filter_array['function'][0] ) && get_class( $filter_array['function'][0] ) == $class_name && $filter_array['function'][1] == $method_name ) {
				// Test for WordPress >= 4.7 WP_Hook class (https://make.wordpress.org/core/2016/09/08/wp_hook-next-generation-actions-and-filters/)
				if ( is_a( $wp_filter[ $hook_name ], 'WP_Hook' ) ) {
					unset( $wp_filter[ $hook_name ]->callbacks[ $priority ][ $unique_id ] );
				} else {
					unset( $wp_filter[ $hook_name ][ $priority ][ $unique_id ] );
				}
			}
		}

	}

	return false;
}
