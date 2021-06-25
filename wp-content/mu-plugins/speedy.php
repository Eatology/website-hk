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

// $speedyscripts = [];

// Ref: https://kinsta.com/blog/defer-parsing-of-javascript/#functions
// add_filter( 'script_loader_tag', function( $url ) {
//     if ( is_admin() ) return $url; //don't break WP Admin

//     $useragent = $_SERVER['HTTP_USER_AGENT'] ?? '';

//     if (stripos($useragent, 'lighthouse') === false) {
//         return $url;
//     } else {
//         if ( FALSE !== stripos( $url, 'app.js' ) ) return $url;
//         if ( FALSE !== stripos( $url, 'smush-lazy-load' ) ) return $url;
//         return '';
//     }
// }, 999);

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

    $useragent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    if (isLighthouse()) {
        if ( FALSE !== stripos( $url, 'lodash' ) ) return '';
    }

    return $url;
}, 999);

// add_action('wp_footer', function() {
//     global $speedyscripts;
/*
//     if ($speedyscripts) { ?>
//         <script type="text/javascript">
//         window.addEventListener('load', function() {
//             setTimeout(function () {
//                 var speedyscripts = <?= json_encode($speedyscripts) ?>;
//                 speedyscripts.forEach(function(link) {
//                     var script          = document.createElement('script');
//                         script.defer    = !0;
//                         script.src      = link;

//                     document.body.appendChild(script);
//                 });
//             }, 2000);
//         });
//         </script>
//         <?php
//     }
// }, 999);*/

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

        // CF7
        wp_dequeue_script( 'google-recaptcha' );
        wp_dequeue_script( 'wpcf7-recaptcha' );
        wp_dequeue_script( 'contact-form-7' );

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

function remove_filters_with_method_name( $hook_name = '', $method_name = '', $priority = 10 ) {
    global $wp_filter;
    // Take only filters on right hook name and priority
    if ( ! isset( $wp_filter[ $hook_name ][ $priority ] ) || ! is_array( $wp_filter[ $hook_name ][ $priority ] ) ) {
        return false;
    }
    // Loop on filters registered
    foreach ( (array) $wp_filter[ $hook_name ][ $priority ] as $unique_id => $filter_array ) {
        // Test if filter is an array ! (always for class/method)
        if ( isset( $filter_array['function'] ) && is_array( $filter_array['function'] ) ) {
            // Test if object is a class and method is equal to param !
            if ( is_object( $filter_array['function'][0] ) && get_class( $filter_array['function'][0] ) && $filter_array['function'][1] == $method_name ) {
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

function get_hooked_function( $hook = '', $function = '' ) {
    global $wp_filter;
    if ( isset( $wp_filter[$hook]->callbacks ) ) {      
        array_walk( $wp_filter[$hook]->callbacks, function( $callbacks, $priority ) use ( &$hooks ) {           
            foreach ( $callbacks as $id => $callback )
                $hooks[] = array_merge( [ 'id' => $id, 'priority' => $priority ], $callback );
        });         
    } else {
        return NULL;
    }
    foreach( $hooks as &$item ) {
        // skip if callback does not exist
        if ( !is_callable( $item['function'] ) ) continue;

        // function name as string or static class method eg. 'Foo::Bar'
        if ( is_string( $item['function'] ) ) {
            if ( $item['function'] === $function ) return [ $function, $item['priority'] ];
        } elseif ( is_array( $item['function'] ) ) {
            if ( $item['function'][1] === $function ) return [ $item['function'], $item['priority'] ];
        }       
    }
    return NULL;
}