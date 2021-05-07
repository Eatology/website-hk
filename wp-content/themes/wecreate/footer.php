<?php

/**
 * The footer template file
 *
 * This is the content displayed on bottom of your content.
 * It is included on all the template files.
 */
$footer_fields = get_field('footer', 'option');

// check current language and set the page id
if (ICL_LANGUAGE_CODE == 'zh') {
	$privacy_policy_page_id = 3019;
	$terms_condition_page_id = 3021;
} else {
	$privacy_policy_page_id = 3;
	$terms_condition_page_id = 1172;
}

$styles = '';
if(get_field('wyswig')){
	$styles = 'padding: 0px; margin: 0px';
}

?>

<style>
    #read-more-btn{
        font-family: Roboto Light, serif;
        color: #4a0d66;
    }
</style>

<footer id="footer" style="<?= $styles ?>" role="contentinfo" itemscope itemtype="http://schema.org/WPFooter">


	<!-- footer for desktop -->
	<div class="footer-upper footer-upper-desktop">
		<div class="footer-upper__meals-left">
			<div class="h2"><?php echo $footer_fields['meal_header']; ?></div>
			<div class="menu-wrapper">
				<?php
				if (has_nav_menu('footer-meals-left')) :
					wp_nav_menu(['theme_location' => 'footer-meals-left']);
				endif;
				?>
			</div>
		</div>
		<div class="footer-upper__meals-right">
			<div class="h2">&nbsp;</div>
			<div class="menu-wrapper">
				<?php
				if (has_nav_menu('footer-meals-right')) :
					wp_nav_menu(['theme_location' => 'footer-meals-right']);
				endif;
				?>
			</div>
		</div>
		<div class="footer-upper__nav">
			<div class="h2"><?php echo $footer_fields['nav_header']; ?></div>
			<div class="footer-upper__primary_nav">
				<?php
				if (has_nav_menu('footer-primary')) :
					wp_nav_menu(['theme_location' => 'footer-primary']);
				endif;
				?>
			</div>
		</div>
		<div class="footer-upper__contact">
			<div class="h2"><?php echo $footer_fields['contact_header']; ?></div>
			<div class="contact-wrapper wrapper-address">
				<div class="contact-icon">
					<span class="icon-icon-location contact-address"></span>
				</div>
				<div class="contact-detail contact-address">
					<?php echo $footer_fields['contact_address']; ?>
				</div>
			</div>

			<div class="contact-wrapper wrapper-phone">
				<div class="contact-icon">
					<span class="icon-icon-phone"></span>
				</div>
				<div class="contact-detail">
					<?php echo $footer_fields['contact_phone_number']; ?>
				</div>
			</div>

			<div class="contact-wrapper wrapper-mail">
				<div class="contact-icon">
					<span class="icon-icon-mail"></span>
				</div>
				<div class="contact-detail">
					<a href="mailto:<?php echo $footer_fields['contact_email']; ?>"><?php echo $footer_fields['contact_email']; ?></a>
				</div>
			</div>
		</div>

		<div class="footer-upper__social">
			<div class="h2"><?php echo $footer_fields['social_header']; ?></div>
			<div class="social-wrapper">
				<?php echo $footer_fields['social_icons']; ?>
			</div>
		</div>

	</div>





	<!-- footer for mobile -->
	<div class="footer-upper footer-upper-mobile">

		<!-- custom accordion wecreate -->
		<div class="wecreate-accordion">
			<div class="wecreate-accordion-header">
				<label>
					<?php echo $footer_fields['meal_header']; ?>
					<span class="expand-collapse icon-chevron-down"></span>
				</label>
			</div>

			<div class="wecreate-accordion-body">
				<div class="footer-upper__meals-left">
					<div class="menu-wrapper">
						<?php
						if (has_nav_menu('footer-meals-left')) :
							wp_nav_menu(['theme_location' => 'footer-meals-left']);
						endif;
						?>
					</div>
				</div>
				<div class="footer-upper__meals-right">
					<div class="h2">&nbsp;</div>
					<div class="menu-wrapper">
						<?php
						if (has_nav_menu('footer-meals-right')) :
							wp_nav_menu(['theme_location' => 'footer-meals-right']);
						endif;
						?>
					</div>
				</div>

			</div>
		</div>


		<!-- custom accordion wecreate -->
		<div class="wecreate-accordion">
			<div class="wecreate-accordion-header">
				<label>
					<?php echo $footer_fields['nav_header']; ?>
					<span class="expand-collapse icon-chevron-down"></span>
				</label>
			</div>

			<div class="wecreate-accordion-body">
				<div class="footer-upper__primary_nav">
					<?php
					if (has_nav_menu('footer-primary')) :
						wp_nav_menu(['theme_location' => 'footer-primary']);
					endif;
					?>
				</div>

			</div>
		</div>


		<!-- custom accordion wecreate -->
		<div class="wecreate-accordion">
			<div class="wecreate-accordion-header">
				<label>
					<?php echo $footer_fields['contact_header']; ?>
					<span class="expand-collapse icon-chevron-down"></span>
				</label>
			</div>

			<div class="wecreate-accordion-body">
				<div class="footer-upper__nav">

					<div class="footer-upper__contact">
						<div class="contact-wrapper wrapper-address">
							<div class="contact-icon">
								<span class="icon-icon-location contact-address"></span>
							</div>
							<div class="contact-detail contact-address">
								<?php echo $footer_fields['contact_address']; ?>
							</div>
						</div>

						<div class="contact-wrapper wrapper-phone">
							<div class="contact-icon">
								<span class="icon-icon-phone"></span>
							</div>
							<div class="contact-detail">
								<?php echo $footer_fields['contact_phone_number']; ?>
							</div>
						</div>

						<div class="contact-wrapper wrapper-mail">
							<div class="contact-icon">
								<span class="icon-icon-mail"></span>
							</div>
							<div class="contact-detail">
								<a href="mailto:<?php echo $footer_fields['contact_email']; ?>"><?php echo $footer_fields['contact_email']; ?></a>
							</div>
						</div>
					</div>

				</div>

			</div>
		</div>


		<div class="footer-upper__social">
			<div class="h2"><?php echo $footer_fields['social_header']; ?></div>
			<div class="social-wrapper">
				<?php echo $footer_fields['social_icons']; ?>
			</div>
		</div>

	</div>




	<div class="footer-lower">
		<div class="footer-lower__copyright">
			<?php echo $footer_fields['copyright']; ?>
		</div>
		<div class="footer-lower__links">
			<a href="<?php echo get_the_permalink($privacy_policy_page_id); ?>"><?php _e('PRIVACY POLICY', 'eatology'); ?></a>
			<span>|</span>
			<a href="<?php echo get_the_permalink($terms_condition_page_id); ?>"><?php _e('TERMS & CONDITIONS', 'eatology'); ?></a>

		</div>
	</div>
	<p>

	</p>
</footer>
