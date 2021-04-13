<?php
/**
 * wecreate Helper Functions
 *
 * Define here all your functions that will not be hooked to WordPress
 *
 * @package  WordPress
 * @subpackage wecreate
 * @since 1.0
 * @author Mystro Ken <mystroken@gmail.com>
 */

/**
 * Returns the full path to the main template file.
 *
 * @package wecreate
 * @since 1.0
 * @return string
 */
function wecreate_template_path() {
	return wecreate_Wrapping::$main_template;
}



/**
 * Returns the full path to an asset of the theme.
 *
 * @param string $file The asset name to load.
 */
function wecreate_asset( $file ) {
	return get_template_directory() . '/resources/assets/' . $file;
}


function get_all_posts_by_author($post_author, $language_code = ICL_LANGUAGE_CODE, $post_type = 'post', $post_status = 'publish' ) {
    global $sitepress;
     
    $current_lang = $sitepress->get_current_language();
    $sitepress->switch_lang( $language_code );
     
    $all_posts = new WP_Query( array( 'posts_per_page'=>-1, 'fields'=>'ids', 'author'=> $post_author, 'post_type'=>$post_type, 'post_status'=> $post_status  ) );

	$sitepress->switch_lang( $current_lang );
     
    return $count = $all_posts->found_posts;
}

// shortern excerpt
function get_excerpt($excerpt, $count ) {
    if (strlen($excerpt) < $count) {
        $excerpt = strip_tags($excerpt);
        $excerpt = substr($excerpt, 0, $count);
        $excerpt = substr($excerpt, 0, strripos($excerpt, " "));
    }

	return $excerpt;
}


function current_url() {
	$protocol = 'http://';
    if (isset($_SERVER['HTTPS']) &&
        ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
        isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
        $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
        $protocol = 'https://';
    } 
    return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}


function svg_circle($class_name) {
    if ($class_name) {
        $class_name = " class='".$class_name."'";
    }
    return '<svg width="489px" height="488px" viewBox="0 0 489 488" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"'.$class_name.'>
    <g id="Desktop-(XL)" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g id="03.-Dig-In-and-Enjoy" transform="translate(-1.000000, -17.000000)" fill="#FEF9F4">
            <g id="assets" transform="translate(0.035967, 0.000000)">
                <g id="Bitmap" transform="translate(0.035967, 0.000000)">
                    <g id="Group-56" transform="translate(0.964033, 0.000000)">
                        <circle id="Mask" cx="244.035967" cy="261" r="244"></circle>
                    </g>
                </g>
            </g>
        </g>
    </g>
</svg>';
}




function ___wejns_wp_whitespace_fix($input) {
	/* valid content-type? */
	$allowed = false;
	/* found content-type header? */
	$found = false;
	/* we mangle the output if (and only if) output type is text/* */
	foreach (headers_list() as $header) {
		if (preg_match("/^content-type:\\s+(text\\/|application\\/((xhtml|atom|rss)\\+xml|xml))/i", $header)) {
			$allowed = true;
		}
		if (preg_match("/^content-type:\\s+/i", $header)) {
			$found = true;
		}
	}
	/* do the actual work */
	if ($allowed || !$found) {
		return preg_replace("/\\A\\s*/m", "", $input);
	} else {
		return $input;
	}
}
/* start output buffering using custom callback */
ob_start("___wejns_wp_whitespace_fix");