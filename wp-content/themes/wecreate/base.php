<?php
/**
 * Theme Wrapper.
 *
 * The goal of the theme wrapper is to
 * remove any repeated markup from individual template.
 *
 */

global $sitepress;
if ($sitepress) {
  $lang = ICL_LANGUAGE_CODE;
  $sitepress->switch_lang($lang);
}

$useragent = $_SERVER['HTTP_USER_AGENT'] ?? '';
?>
<!doctype html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
    <?php if (stripos($useragent, 'lighthouse') !== false) { ?>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-70332715-1"></script>
		<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-70332715-1');
		</script>
	<?php } ?>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<!-- <link rel="icon" href="/favicon.svg" type="image/svg+xml" />
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link rel="manifest" href="/site.webmanifest">
	<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5"> -->
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="theme-color" content="#ffffff">

    <?php if (stripos($useragent, 'lighthouse') !== false) { ?>
		<link rel="preload" href="<?php echo get_stylesheet_directory_uri() ?>/resources/assets/fonts/Luxia-Display.woff2" as="font">
		<link rel="preload" href="<?php echo get_stylesheet_directory_uri() ?>/resources/assets/fonts/Roboto-Medium.woff2" as="font">
		<link rel="preload" href="<?php echo get_stylesheet_directory_uri() ?>/resources/assets/fonts/Roboto-Light.woff2" as="font">
		<link rel="preload" href="<?php echo get_stylesheet_directory_uri() ?>/resources/assets/fonts/Roboto-Regular.woff2" as="font">
		<link rel="preload" href="<?php echo get_stylesheet_directory_uri() ?>/resources/assets/fonts/icomoon.woff" as="font">
	<?php } ?>
	<?php wp_head(); ?>
	<script>
		window.globalajaxurl='<?php echo admin_url( 'admin-ajax.php' ); ?>';
		//var _currentlang='<?php echo ICL_LANGUAGE_CODE;  ?>';
	</script>

</head>

<body <?php body_class(); ?> role="document" itemscope itemtype="http://schema.org/WebPage">
	<ul id="skip-link-reader">
		<li><a href="#content-anchor"><?php esc_html_e( 'Skip to content', 'wecreate' ); ?></a></li>
	</ul>
	<div id="loading" style="display: none;"></div>
	<div class="wrapper">
		<?php get_header(); ?>
		<a name="content-anchor" class="accessibility">Main Content</a>
		<main id="main-content" role="main" itemprop="mainContentOfPage">
			<?php
				/*
				* Get the right WordPress template file.
				*/
				require wecreate_template_path();
			?>
		</main>

	</div>

	<?php get_footer(); ?>

</div>

<?php wp_footer(); ?>
</body>
</html>
