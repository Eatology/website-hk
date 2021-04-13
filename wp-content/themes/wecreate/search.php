<?php
$s = get_search_query();
$args = array('s' => $s);
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

// $search_query = new WP_Query( $args );
$search_query = new WP_Query("s=$s&showposts=10&paged=$paged");
?>
<section id="search-result">
	<div class="results-header">
		<h3 class="page-title"><?php
								/* translators: %s: search query. */
								printf(esc_html__('Search: %s', 'eatology'), '<span>' . get_search_query() . '</span>');
								?></h3>
		<p class="results-number">
			<?php
			$result_s = $search_query->found_posts > 1 || $search_query->found_posts == 0 ?  __('results found.', 'eatology') :  __(' result found.', 'eatology');
			echo '<span id="total_count">' . $search_query->found_posts . ' </span>' . $result_s;
			?>
		</p>
	</div>
	<div class="search-contents">
		<?php if ($search_query->have_posts()) : ?>

			<div id="article-listing">
			<?php
			/* Start the Loop */
			while ($search_query->have_posts()) : $search_query->the_post(); ?>
				<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
					<div class="entry">
						<div class="entry-content">
							<span><?php
									$post_type =  get_post_type(get_the_ID());
									if ($post_type == "post") {
										$post_type = __("Blog", 'eatology');
									} elseif ($post_type == "product") {
										$post_type = __("Meal Plan", 'eatology');
									}
									echo $post_type;
									?></span>
									
							<h3><?php echo get_the_title(); ?></h3>

							<?php 
								$excerpt = get_excerpt(get_the_excerpt(), 175);
								$keys = explode(" ", $s);
								$excerpt = preg_replace('/(' . implode('|', $keys) . ')/iu', '<strong class="search_term">\0</strong>', $excerpt);
							?>

							<div class="excerpt"><?php echo $excerpt; ?> </div>
						</div>
						<?php the_post_thumbnail('wecreate_search'); ?>
					</div>
				</a>
			<?php endwhile;

			echo '</div>';
			// Previous/next page navigation.
			// the_posts_pagination(
			// 	array(
			// 		'prev_text'          => __( 'Previous page', 'genese' ),
			// 		'next_text'          => __( 'Next page', 'genese' ),
			// 		'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'genese' ) . ' </span>',
			// 	)
			// );


			$total_pages = $search_query->max_num_pages;

			if ($total_pages > 1) {
	
				$current_page = max(1, get_query_var('paged'));
				$big = 999999999; // need an unlikely integer
				echo '<div class="custom_pagination">';
				echo paginate_links(array(
					// 'base' => get_pagenum_link(1) . '%_%',
					// 'format' => '/page/%#%',
					'format' => '?page=%#%',
					'current' => $current_page,
					'total' => $total_pages,
					'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
					'prev_text'    => __('<span class="icon-chevron-down"></span>', 'eatology'),
					'next_text'    => __('<span class="icon-chevron-down"></span>', 'eatology'),
				));
				echo '</div>';
			}



?>
		<?php else : ?>
			<div class="no-results">
				<p> <?php _e('No results found', 'eatology'); ?></p>
			</div>
		<?php endif; ?>
	</div>

</section>