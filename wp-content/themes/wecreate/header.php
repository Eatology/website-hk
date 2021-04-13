<?php
/**
 * The header template file
 *
 * This is the content displayed on top of your content.
 * It is included on all the template files.
 *
 */


if (ICL_LANGUAGE_CODE == 'zh')
{
	$account_id = 4458;
	$shop_page_id = 3215;
}
else {
	$account_id = 9;
	$shop_page_id = 451;
}

?>
<header id="header" class="header" role="banner" itemscope itemtype="http://schema.org/WPHeader">
	<div class="container">
		<div class="header__nav">
			<nav class="nav">
				<?php
					if (has_nav_menu('primary')) :			
						wp_nav_menu(['theme_location' => 'primary']);
					endif;
				?>
			</nav>
		</div>

		<div class="header__corporate">
			<div class="header__logo">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" title="<?php bloginfo( 'name' ); ?>"><?php the_field('site_logo', 'option'); ?></a>
			</div>
		</div>

		<div class="header__actions">
			<div class="header__language">
				<!-- language  -->
				<?php 
					do_action('wpml_add_language_selector');
				?>
			</div>	

			<div class="header__cart_icon">
				<!-- cart  -->
				<div class="cart"><a href="<?php echo wc_get_cart_url(); ?>?eatology_cart" title="<?php _e('Cart', 'eatology');?>" name="eatology_cart"><div class="cart-amount"><?php echo WC()->cart->get_cart_contents_count(); ?></div><span class="icon-icon-cart"></span></a></div>


			</div>

			<div class="header__user_icon">
				<!-- user  -->
				<a href="<?php echo get_the_permalink($account_id);?>" title="<?php _e('My Account', 'eatology');?>"><span class="icon-icon-user"></span></a>
			</div>


			<div class="header__search_icon">
				<!-- search  -->
				<span class="icon-icon-search"></span>
			</div>

			<div class="header__button">
				<!-- button  -->
				<?php $order_button = get_field('order_button', 'option'); ?>
				<a href="<?php echo get_the_permalink($shop_page_id);?>" title="<?php echo $order_button; ?>"><?php echo $order_button; ?></a>
			</div>			

			
		</div>	
	</div>

	
</header>

<!-- Desktop search -->
<div class="search">
	<div class="search__box">
		<form method="GET" action="/"> 
			<div class="search__fields"><input type="text" required value="<?php echo $s;?>" name="s" class="search__input" placeholder="<?php _e('Search', 'eatology');?>" autofocus /><span class="icon-icon-close"></span></div>
		</form>		
	</div>
</div>


<!-- Mobile header -->
<section id="header-mobile">	
	<div class="header__logo">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" title="<?php bloginfo( 'name' ); ?>"><?php the_field('site_logo_mobile', 'option'); ?></a>
			</div>
	<div class="header-mobile__actions">
		<div class="header__cart_icon">
			<!-- cart  -->
			<div class="cart"><a href="<?php echo wc_get_cart_url(); ?>?eatology_cart" title="<?php _e('Cart', 'eatology');?>" name="eatology_cart"><div class="cart-amount"><?php echo WC()->cart->get_cart_contents_count(); ?></div><span class="icon-icon-cart"></span></a></div>
		</div>
		<div class="header__hamburger">
			<!-- cart  -->
			<span class="icon-burger-menu"></span>
		</div>		
	</div>	
</section>
<!-- Mobile nav -->
<section id="header-mobile-nav">
	<div class="search__box-mobile">
		<form method="GET" action="/" name="mobile_search_form"> 
			<div class="search__fields-mobile"><input type="text" required value="<?php echo $s;?>" name="s" class="search__input-mobile" placeholder="<?php _e('Search', 'ariana');?>" autofocus /><span class="icon-icon-search"></span></div>
		</form>		
	</div>	
	<div class="mobile-nav-wrapper">
		<div class="user-account">
			<ul>
				<?php if ( is_user_logged_in() ) : ?>
					<?php $txt = __('My Account', 'eatology'); ?>
					<li><a href="<?php echo wp_logout_url('/') ?>"><?php echo __('Log out', 'eatology');?></a></li>
				<?php else: ?>					
					<?php $txt = __('Login', 'eatology'); ?>
				<?php endif; ?>
				<li><a href="<?php echo get_the_permalink($account_id);?>"><?php echo $txt; ?></a></li>
			</ul>
		</div>		
		<nav class="nav-mobile">
			<?php
				if (has_nav_menu('primary')) :			
					wp_nav_menu(['theme_location' => 'primary']);
				endif;
			?>
		</nav>

		<div class="language-switcher-wrapper">
			<ul>
			<?php 
					do_action('wpml_add_language_selector');
				?>
			</ul>
		</div>	

	</div>

	<div class="header__button">
		<!-- button  -->
		<a href="<?php echo get_the_permalink($shop_page_id);?>" title="<?php echo $order_button; ?>"><?php echo $order_button; ?></a>
	</div>			
</section>