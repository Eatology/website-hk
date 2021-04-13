<?php
/**
 * Plugin Name: Wecreate Instagram
 * Plugin URI: http://www.wecreate.com.hk
 * Description: Pulling instagram posts then embed to the site
 * Version: 1.0
 * Author: Wecreate
 * Author URI: http://www.wecreate.com.hk
 */

if(! function_exists('wc_instagram_post')){
	refresh_token();
	function wc_instagram_post($atts) {
		
		$wc_token_id = get_option( 'Token_Id'); 
		$wc_count = get_option('Count_Id');
		$wc_title = get_option('Title_Id');
		$wc_url = get_option('Instagram_URL');
		if ($wc_token_id != null){
			$ch = curl_init();
			// curl_setopt($ch, CURLOPT_URL, 'https://api.instagram.com/v1/users/self/media/recent/?access_token='.$wc_token_id.'&count='.$wc_count);
			curl_setopt($ch, CURLOPT_URL, 'https://graph.instagram.com/me/media?fields=id,media_url,permalink,media_type&access_token='.$wc_token_id.'&limit='.$wc_count);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$result = curl_exec($ch);
			curl_close($ch);
			$result = json_decode($result, true);
			$imgs = "";
			if(isset($result['error_type'])){
			 	$imgs .=$result['error_message'];
			 	return $imgs;
			}else if(isset($result['data'])){ 
				
				$imgs .="<div class='wc-instagram-container'>";
				if($wc_url){
					$imgs .= "<a href='".$wc_url."' target='_blank' class='insta-link'><div class='wc-instagram-heading'>".$wc_title."</div></a>";
				}else{
					$imgs .= "<div class='wc-instagram-heading'>".$wc_title."</div>";
				}

					$imgs .= "<div class='wc-instagram-wrapper'>";
						foreach($result['data'] as $post){
							$imgs .= '<a href="'.$post['permalink'].'" target="_blank">';
								if($post['media_type'] == "IMAGE"):
									$imgs .= '<div class = "wc-instagram-img-wrap fab">'; 
										$imgs .= '<img src="'. $post['media_url'] .'">';
										
									$imgs .= '</div>';
								elseif($post['media_type'] == "VIDEO"):
									$imgs .= '<div class = "wc-instagram-img-wrap fab">'; 
										$imgs .= '<video src="'. $post['media_url'] .'" controls autoplay>';
										
									$imgs .= '</div>';

								endif; 
							$imgs .= '</a>';
						}
					$imgs .="</div>";
				$imgs .="</div>";
				
				return $imgs;
			}else{
				return "<center>No data available</center>";
			}
			
		}
	}

	
}
function refresh_token(){
	$wc_token_last_updated = get_option( 'Token_Last_Updated' );
	$token = get_option( 'Token_Id'); 
	$today = date("F j, Y");
	if(!$wc_token_last_updated_val){
		update_option( 'Token_Last_Updated', $today );
	}
	
	$last_updated = strtotime($wc_token_last_updated);
	$today = strtotime($today);
	$datediff = $today - $last_updated;

	$diff = round($datediff / (60 * 60 * 24));

	if($diff > 57){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token
&access_token=' . $token);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);
		update_option( 'Token_Last_Updated', $today );
		// $result = json_decode($result, true);
		// var_dump($result);
	}

}
add_shortcode('wc_display_instagram_post', 'wc_instagram_post');

add_action('admin_menu', 'instagram_setup_menu');
 
function instagram_setup_menu(){
        add_menu_page( 'Instagram Plugin Page', 'Instagram', 'manage_options', 'wecreate-instagram', 'instagram_init' );
}
 
function instagram_init(){

	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		 // variables for the field and option names 
	    $wc_token_name = 'Token_Id';
	    $wc_count_name = 'Count_Id';
	    $wc_title_name = 'Title_Id';
	     $wc_url_name  = 'Instagram_URL';
	    // $wc_app_secret_name = 'App_Secret';

	    $wc_hidden_field_name = 'mt_submit_hidden';
	    $wc_token_field = 'Token_Id';
	    $wc_count_field = 'Count_Id';
	    $wc_title_field = 'Title_Id';
	    $wc_url_field = 'Instagram_URL';

	    // $wc_app_secret_field = 'App_Secret';

	    // Read in existing option value from database
	    $wc_token_val = get_option( $wc_token_field );
	    $wc_count_val = get_option( $wc_count_field );
	    $wc_title_val = get_option( $wc_title_field );
	    $wc_url_val = get_option( $wc_url_field );

	    // $wc_app_secret_val = get_option( $wc_app_secret_field );
	    // See if the user has posted us some information
	    // If they did, this hidden field will be set to 'Y'
	    if( isset($_POST[ $wc_hidden_field_name ]) && $_POST[ $wc_hidden_field_name ] == 'Y' ) 
		{
	        // Read their posted value
	        $wc_token_val = $_POST[ $wc_token_field ];
	        $wc_count_val = $_POST[ $wc_count_field ];
	        $wc_title_val = $_POST[ $wc_title_field ];
	        $wc_url_val = $_POST[ $wc_url_field ];
	        // $wc_app_secret_val = $_POST[ $wc_app_secret_field ];

	        // Save the posted value in the database
	        update_option( $wc_token_name, $wc_token_val );
	        update_option( $wc_count_name, $wc_count_val );
	        update_option( $wc_title_name, $wc_title_val );
	        update_option( $wc_url_name, $wc_url_val );
	        // update_option( $wc_app_secret_name, $wc_app_secret_val );

	        // Put a "settings saved" message on the screen

			?>
			<div class="updated"><p><strong><?php _e('settings saved.', 'wc-menu' ); ?></strong></p></div>
			<?php

	    }

	    // Now display the settings editing screen

	    echo '<div class="wrap">';

	    // header

	    echo "<h2>" . __( 'Insert Instagram Access Token', 'wc-menu' ) . "</h2>";

	    // settings form
	    
	    ?>

		<form name="wc_form" method="post" action="">

			<input type="hidden" name="<?php echo $wc_hidden_field_name; ?>" value="Y">

			<p><?php _e(" Your Instagram Access Token : ", 'wc-menu' ); ?> 
				<br><input type="text" name="<?php echo $wc_token_field; ?>" value="<?php echo $wc_token_val; ?>" size="70">
			</p><hr />

			

			<p><?php _e(" Number of post : ", 'wc-menu' ); ?> 
				<br><input type="text" name="<?php echo $wc_count_field; ?>" value="<?php echo $wc_count_val; ?>" size="70">
			</p><hr />

			<p><?php _e(" Instagram URL (optional): ", 'wc-menu' ); ?> 
				<br><input type="text" name="<?php echo $wc_url_field; ?>" value="<?php echo $wc_url_val; ?>" size="70">
			</p><hr />
			<br>

			<p><?php _e(" Title : ", 'wc-menu' ); ?> 
				<br><input type="text" name="<?php echo $wc_title_field; ?>" value="<?php echo $wc_title_val; ?>" size="70">
			</p><hr />
			<br>
			<p class="submit">
				<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
			</p>

		</form>
		<h3>Instructions</h3>
		<p>Use this using shortcode, e.g. [wc_display_instagram_post]</p>
	</div>


	<?php
}

