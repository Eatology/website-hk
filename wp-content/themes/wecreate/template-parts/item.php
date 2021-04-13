<?php
/**
 * The template for displaying content for index/archive/search.
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article" itemid="<?php echo esc_url( get_permalink() ); ?>" itemscope itemtype="http://schema.org/BlogPosting">

	<header>

		<?php
		the_title(
			sprintf(
				'<h2 itemprop="headline"><a href="%s" rel="bookmark" itemprop="url">',
				esc_url( get_permalink() )
			),
			'</a></h2>'
		);


		if ( 'post' === get_post_type() ) :
			?>
			<div>
				<?php
				wecreate_posted_on();
				wecreate_posted_by();
				?>
			</div>
		<?php endif; ?>

	</header>

	<?php wecreate_post_thumbnail(); ?>

	<div itemprop="description">
		<?php the_excerpt(); ?>
	</div>

</article>
