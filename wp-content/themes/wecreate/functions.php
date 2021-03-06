<?php

/**
 * wecreate files includes
 *
 * The $includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 */

$includes = array(
	'lib/inc/class-wecreate-wrapping.php', 		// Theme wrapper class.
	'lib/inc/helpers.php',               		// Helper functions.
	'lib/inc/setup.php',                 		// Theme setup.
	'lib/inc/template-tags.php',         		// Custom template tags functions.
	'lib/inc/woocommerce.php',         			// Custom Woocommerce functions.
	'lib/inc/woocommerce-subscription.php',    	// Custom Woocommerce Subscription functions.
	'lib/inc/custom-js.php',         			// Custom JS
);

foreach ($includes as $file) {

	$filepath = locate_template($file);

	if (!$filepath) {
		/* translators: %s: Failed included file. */
		trigger_error(sprintf(esc_html_x('Error locating %s for inclusion', 'wecreate'), $file), E_USER_ERROR);
	}

	require_once $filepath;
}

unset($file, $filepath);

// login logo image override
function custom_loginlogo()
{
	echo '<style type="text/css">
	h1 a {background-image: url(' . get_bloginfo('template_directory') . '/resources/assets/images/eatology-logo.png) !important; background-size: 116px!important;
		height: 60px!important;
		width: 164px!important;}
	</style>';
}


add_action('login_head', 'custom_loginlogo');

//function my_customize_rest_cors() {
//    remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );
//    add_filter( 'rest_pre_serve_request', function( $value ) {
//        header( 'Access-Control-Allow-Origin: *' );
//        header( 'Access-Control-Allow-Methods: GET' );
//        header( 'Access-Control-Allow-Credentials: true' );
//        header( 'Access-Control-Expose-Headers: Link', false );
//        return $value;
//    } );
//}
//add_action( 'rest_api_init', 'my_customize_rest_cors', 15 );

// // search result pagination
// add_action( 'pre_get_posts',  'set_posts_per_page'  );
// function set_posts_per_page( $query ) {

//   global $wp_the_query;

//   if ( ( ! is_admin() ) && ( $query->is_search() ) ) {
// 	$query->set( 'posts_per_page', 10);
// 	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
// 	$query->set( 'paged', $paged);
//   }

//   return $query;
// }

if (!function_exists('isLighthouse'))
{
	function isLighthouse() {
		$useragent = $_SERVER['HTTP_USER_AGENT'] ?? '';

		return stripos($useragent, 'lighthouse') !== false || stripos($useragent, 'speed insights') !== false;
	}
}
