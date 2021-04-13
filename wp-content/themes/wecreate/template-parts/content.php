<?php
/**
 * The template for displaying content for single.
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article" itemprop="mainEntity" itemscope itemtype="http://schema.org/BlogPosting">

	<header>

		<?php

		the_title( '<h1 itemprop="headline">', '</h1>' );

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

	<div itemprop="articleBody">
		<?php
		the_content(
			sprintf(
				/* translators: %s: Name of current post */
				__( 'Continue reading %s', 'wecreate' ),
				the_title( '<span class="screen-reader-text">', '</span>', false )
			)
		);

		wp_link_pages(
			array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'wecreate' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'wecreate' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			)
		);
		?>
	</div><!-- .entry-content -->
	<small> by <?php the_author_posts_link() ?></small>
	<?php
	// Author bio.
	if ( is_single() && get_the_author_meta( 'description' ) ) :
		get_template_part( 'author-bio' );
	endif;
	?>

	<footer>
		<?php edit_post_link( __( 'Edit', 'wecreate' ), '<span class="edit-link">', '</span>' ); ?>
	</footer>

</article>
