<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 */

if ( ! function_exists( 'wecreate_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function wecreate_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail( 'wecreate_thumbnail_background', [ 'itemprop' => 'image' ] ); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

		<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php the_post_thumbnail( 'wecreate_thumbnail_medium', [ 'alt' => the_title_attribute( [ 'echo' => false ] ), 'itemprop' => 'image' ] ); ?>
		</a>

		<?php
		endif; // End is_singular().
	}
endif;



if ( ! function_exists( 'wecreate_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function wecreate_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'wecreate' ),
			'<span class="author vcard" itemprop="author" itemscope itemtype="http://schema.org/Person"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '"><span itemprop="name">' . esc_html( get_the_author() ) . '</span></a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

	}
endif;



if ( ! function_exists( 'wecreate_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function wecreate_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s" itemprop="datePublished">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s" itemprop="datePublished">%2$s</time><time class="updated" datetime="%3$s" itemprop="dateModified">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( 'Posted on %s', 'post date', 'wecreate' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

	}
endif;





// function set_posts_per_category( $query ) {
//     if ( !is_admin() && $query->is_main_query()) {
// 		if ($query->is_category() || $query->is_search() || is_author()) {
// 			$query->set( 'posts_per_page', '10' );
// 		}
// 		if ($query->is_tax('issues')) {
// 			$query->set( 'posts_per_page', '100' );
// 		}
		
// 		if ( $query->is_main_query() && is_search() ) {
// 			$query->set( 'post_type', 'post' );
// 		}         

// 	}
// 	if ( !is_admin() && $query->is_search()) {
// 		$query->set( 'posts_per_page', '10' );
// 	}
// }
// add_action( 'pre_get_posts', 'set_posts_per_category' );



function load_more_posts() {
	$offset        = $_POST['offset'];
	$lang          = $_POST['ajaxlang'];
	$category      = $_POST['category'];
	$topic_type    = $_POST['topic_type'];
	$issues    	   = $_POST['issues'];
	$author    	   = $_POST['author'];
	$search_term   = $_POST['search_term'];
	$size          = 10;
	$tax_query	   = [];
	
	global $sitepress;
	$currentlang = ICL_LANGUAGE_CODE;
	$sitepress->switch_lang($lang);

	$cats = array();

	if ($category != 0) {
		array_push($cats, $category); 
		$taxonomy = "category";
	}

	if ($topic_type != 0) {
		array_push($cats, $topic_type); 
		$taxonomy = "category";
	}		

	if ($issues != 0) {
		array_push($cats, $issues); 
		$taxonomy = 'issues';
	}		

	if ($topic_type != 0  || $category != 0) {
		$tax_query = array('relation' => 'AND');
		$tax_query[] = array(
			'taxonomy' => $taxonomy,
			'field'    => 'term_id',
			'terms'    => $cats,
			'operator' => 'AND'
		);
	}

	$args = array(
		'offset'          => $offset*$size,
		'posts_per_page'  => $size,
		'post_status'     => 'publish',
		'orderby'         => 'publish_date',
		'order'           => 'DESC',
		'tax_query' 	  => $tax_query,
	);

	if ($search_term != '') {
		$args['s'] = $search_term;
	}

	if ($author != '') {
		$args['author'] = $author;
	}	


	$posts = new WP_Query($args);
	$total_posts = $posts->found_posts;
  
	// print_r($posts->request);
	// die();

	if ($total_posts === 0) {
		echo '<p class="no-posts">'.__('No posts found', 'ariana') .'</p>';
	} else {
		if ($offset == 0) {
			echo '<input type="hidden" id="total_posts" name="total_posts" value="'. $total_posts .'">';
		}
		while ( $posts->have_posts() ) : $posts->the_post(); 
		
		if ($search_term != '' || $author != '') {
			include (get_stylesheet_directory() . "/template-parts/articles/listing.php");
		} else { ?>
			<div class="entry">
				<?php the_post_thumbnail('wecreate_thumbnail_medium'); ?>
				<div class="entry-content">
					<h5><?php
						foreach((get_the_category()) as $category) {
							if ($category->category_parent == 26) {
								echo ' <a href="' . get_category_link($category->cat_ID) . '" title="' . $category->name . '">' . $category->name . '</a>';
							}
						}
					?></h5>
					<h4><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>
					<div class="excerpt"><?php echo get_excerpt(get_the_excerpt(), 200); ?> ...</div>
					<p class="date"><?php the_time('j M Y') ?></p>
				</div>
			</div>
		<?php }
                

		
		endwhile; wp_reset_postdata();
		$sitepress->switch_lang($currentlang);
	}

	die();
  }
  add_action( 'wp_ajax_load_more_posts', 'load_more_posts' );
  add_action( 'wp_ajax_nopriv_load_more_posts', 'load_more_posts' );


function load_issues() {
	$lang          = $_POST['ajaxlang'];
	$issue    	   = $_POST['issue'];

	$taxonomy = 'issues';
	$args = array(
		'orderby' => 'id',
		'order' => 'DESC',
		'hide_empty' => true
	);
	if ($issue != 0) {
		$args['parent'] = $issue;
	}

	$terms = get_terms( $taxonomy, $args );

	//print_r($terms);

	foreach( $terms as $term ) {
		$id = $term->term_id;
		$parent = $term->parent;
		$slug = $term->slug;
		$name = $term->name;
		$description = $term->description;
		$image = get_field('issue_cover_image', 'category_' . $term->term_id . '' );
		
		include (get_stylesheet_directory() . "/template-parts/issues/listing.php");
	}

  	die();
}
add_action( 'wp_ajax_load_issues', 'load_issues' );
add_action( 'wp_ajax_nopriv_load_issues', 'load_issues' );
  
