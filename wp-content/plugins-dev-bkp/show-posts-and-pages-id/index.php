<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php
/*
Plugin Name: YYDevelopment - Show Pages ID
Description: Simple plugin that show you the pages and posts #id number
Version:     1.2.9
Author:      YYDevelopment
Author URI:  https://www.yydevelopment.co.il/
*/

// ================================================
// display the page id on the admin bar in the
// front-end part of the page
// ================================================

function adding_id_number_to_admin_bar( $wp_admin_bar ) {

	$post_id_num = "";

	// in case of page or blog post
	if( is_single() || is_page() ) {		
		$post_id_num =  "ID: " . get_the_ID();
	} // if( is_single() || is_page() ) {

	// in case of category page or tag page
	if( is_category() || is_tag() ) {

		$id_type = "";
		if(is_category()) { $id_type = "Category ";}
		if(is_tag()) { $id_type = "Tag ";}

		global $wp_query;
		$post_id_num =  $id_type . "ID: " . $wp_query->get_queried_object_id();
	} // if( is_category() ) {

	// in case of static home page
	if( is_home() ) {
		$blog_page_id = get_option( 'page_for_posts' );

		// incase the blog is on the home page
		if( !empty($blog_page_id) ) {
			$post_id_num =  "ID: " . $blog_page_id;
		} // if( $blog_page_id ) {

	} // if( !empty($blog_page_id) ) {

	// making sure the output the code only when it's page, post, category or tag
	if( !empty($post_id_num) ) {

		$args = array(
			'id'    => 'my_page',
			'title' => $post_id_num,
			'href'  => '',
			'meta'  => array( 'class' => 'my-toolbar-page' )
		);

		$wp_admin_bar->add_node( $args );

	} // if( !empty($post_id_num) ) {

} // function toolbar_link_to_mypage( $wp_admin_bar ) {

// making sure to load the function only on the front end and not the back end
if( !is_admin() ) {
	add_action( 'admin_bar_menu', 'adding_id_number_to_admin_bar', 9999 );
} // if( !is_admin() ) {

// ================================================
// display the plugin we have create on the wordpress
// post blog and pages
// ================================================

// ---------------------------------------------------------------
// adding the ID title to all the pages we are adding the id into
// ---------------------------------------------------------------
function add_id_title_to_table($columns) {
	return array_merge( $columns, array('show_id_num' => __('ID')) );
} // function add_id_title_to_table($columns) {

add_filter('manage_posts_columns' , 'add_id_title_to_table', 1); // adding id title to posts
add_filter('manage_pages_columns' , 'add_id_title_to_table', 1); // adding id title to pages
add_filter('manage_media_columns' , 'add_id_title_to_table', 1); // adding id title to media files
add_filter('manage_edit-comments_columns' , 'add_id_title_to_table', 1); // adding id title to comments
add_filter('manage_edit-category_columns' , 'add_id_title_to_table', 1); // adding id title to categories
add_filter('manage_edit-post_tag_columns' , 'add_id_title_to_table', 1); // adding id title to tags

// ---------------------------------------------------------------
// adding the ID title to all the pages we are adding the id into
// ---------------------------------------------------------------
function add_id_number_to_id_column( $column, $id ) {
	if( $column === "show_id_num" ) {
		echo $id;
	} // if( $column === "show_id_num" ) {
} // function add_id_number_to_id_column( $column, $id ) {

add_action('manage_posts_custom_column' , 'add_id_number_to_id_column', 2, 2); // adding id number to posts
add_action('manage_pages_custom_column' , 'add_id_number_to_id_column', 2, 2); // adding id number to pages
add_action('manage_media_custom_column' , 'add_id_number_to_id_column', 2, 2); // adding id number to media files
add_action('manage_comments_custom_column' , 'add_id_number_to_id_column', 2, 2); // adding id number to comments


// ---------------------------------------------------------------
// adding id to category and to tags
// ---------------------------------------------------------------

function add_id_number_to_categories_tags( $content, $column_name, $term_id ) {

	if( $column_name === "show_id_num" ) {
		echo $term_id;
	} // if( $column_name === "show_id_num" ) {

} // function add_id_number_to_id_column( $column, $id ) {


add_action('manage_category_custom_column' , 'add_id_number_to_categories_tags', 2, 3); // adding id number to categories
add_action('manage_post_tag_custom_column' , 'add_id_number_to_categories_tags', 2, 3); // adding id number to categories

// ================================================
// including admin notices flie
// ================================================

if( is_admin() ) {
	include_once('notices.php');
} // if( is_admin() ) {