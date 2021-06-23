<?php
/**
Plugin Name: ACF: Dynamic Year Select Field
Plugin URI:  https://wordpress.org/plugins/acf-dynamic-year-select-field/
Description: This plugin adds a dynamic year select for Advanced Custom Fields.
Version:     0.2.0
Author:      AMBR Detroit
Author URI:  https://ambrdetroit.com
License:     GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: acf-dynamic_year_select
*/
load_plugin_textdomain( 'acf-dynamic_year_select', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

/** For Advanced Custom Fields 5.x */
add_action( 'acf/include_field_types',
	function( $version ) {
		include_once 'acf-dynamic-year-select-v5.php';
	}
);


/** For Advanced Custom Fields 4.x */
add_action( 'acf/register_fields',
	function() {
		include_once 'acf-dynamic-year-select-v4.php';
	}
);
