<?php

// 
// MANUALLY SAVE PERMALINKS THE FIRST TIME YOU ADD TO A NEW SERVER
//


// get the sub district json file

global $sub_districts_array;
if (ICL_LANGUAGE_CODE == 'en') {
    $sub_districts_json = file_get_contents(get_stylesheet_directory() . "/resources/json/hk_en.json");
} else if (ICL_LANGUAGE_CODE == 'zh') {
    $sub_districts_json = file_get_contents(get_stylesheet_directory() . "/resources/json/hk_ch.json");
} else {
    $sub_districts_json = file_get_contents(get_stylesheet_directory() . "/resources/json/hk_en.json");
}
$sub_districts_array = (array) json_decode($sub_districts_json, true);



/** 
 * Function to format the date
 * @param string // date string
 * @return date
 */
if (!function_exists('customFormatDate')) {
    function customFormatDate($date)
        {
            $newdate = str_replace('/', '-', $date);
            return date('Y-m-d', strtotime($newdate));
        }
    }


// function to check if the earliest day is a holiday
function isEarliestDayAHoliday($earliestDay)
{
     // Check if the earliest available day is holiday
        // $holidays hold the holiday values set on backend plugin settings
        $holidays = get_option('jckwds_settings')['holidays_holidays_holidays'];

        // date_format is a value set on backend plugin setting
        $dte_format = get_option('jckwds_settings')['datesettings_datesettings_dateformat'];

        // replce mm with m and dd with d
        $new_dte_format = str_replace("mm", "m", $dte_format);
        $new_dte_format =  str_replace("dd", "d", $new_dte_format);

        // $minimum_day is a value set on backend plugin setting
        $minimum_day = get_option('jckwds_settings')['datesettings_datesettings_minimum'];

        // define the earliest date
        $earliestDAv = date_format(date_create($earliestDay), 'd/m/Y');

        $isHoliday = false;

        foreach ($holidays as $holiday) {
			// create array of holiday from and to
			$holiday_dates = [];
            $begin = str_replace('/', '-', $holiday['date']);
            $begin = new DateTime(date('Y-m-d', strtotime($begin)));
            $end = empty($holiday['date_to']) ? $holiday['date'] : $holiday['date_to'];
            $end = str_replace('/', '-', $end);
            $end = new DateTime(date('Y-m-d', strtotime($end)));
            $end->setTime(0, 0, 1); // // adding it so that the end date is inclusive
            $holidaterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
        
            //store each holidays in an array
            foreach ($holidaterange as $date) {
                array_push($holiday_dates, $date->format('d/m/Y'));
            }
            // check if earliest day exists on holiday array
            if (in_array($earliestDAv, $holiday_dates)) {
                $isHoliday = true;
            }
        }

        return $isHoliday;
}

/** 
 * Funtion to get the total holidays to ignore
 * @param string $paramDate // date string
 * @param string $start_type // to denote, if the holiays should be counted full or from today
 * say if holiday is two days viz. 2020-11-18 and 2020-11-19, and today is 2020-11-19 and $start_type = 'today', then total holiday count is returned as 1
 * say if holiday is two days viz. 2020-11-18 and 2020-11-19, and today is 2020-11-17 and $start_type = 'future', then total holiday count is returned as 2
 * @return int
 */
if(!function_exists('get_holidays_count'))
{
    function get_holidays_count($paramDate, $start_type)
    {

        // Check if the earliest available day is holiday
        // $holidays hold the holiday values set on backend plugin settings
        $holidays = get_option('jckwds_settings')['holidays_holidays_holidays'];

        // date_format is a value set on backend plugin setting
        $dte_format = get_option('jckwds_settings')['datesettings_datesettings_dateformat'];

        // replce mm with m and dd with d
        $new_dte_format = str_replace("mm", "m", $dte_format);
        $new_dte_format =  str_replace("dd", "d", $new_dte_format);

        // $minimum_day is a value set on backend plugin setting
        $minimum_day = get_option('jckwds_settings')['datesettings_datesettings_minimum'];

        // define the earliest date
        $earliestDAv = date_format(date_create($paramDate), 'd/m/Y');

        // echo "<br>Date : " .$paramDate. " ---- Earliest Date : " . $earliestDAv;

        $days_to_add = 0;

        foreach ($holidays as $holiday) {
			// create array of holiday from and to
			$holiday_dates = [];
            $begin = str_replace('/', '-', $holiday['date']);
            $begin = new DateTime(date('Y-m-d', strtotime($begin)));
            $end = empty($holiday['date_to']) ? $holiday['date'] : $holiday['date_to'];
            $end = str_replace('/', '-', $end);
            $end = new DateTime(date('Y-m-d', strtotime($end)));
            $end->setTime(0, 0, 1); // // adding it so that the end date is inclusive
            $holidaterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
        
            //store each holidays in an array
            foreach ($holidaterange as $date) {
                array_push($holiday_dates, $date->format('d/m/Y'));
            }

            // check if earliest day exists on holiday array
            if (in_array($earliestDAv, $holiday_dates)) {
                $sdate = $holiday['date'];
                $edate = empty($holiday['date_to']) ? $holiday['date'] : $holiday['date_to'];
                if($start_type == 'today')
                {
                    $start_date = date_create(customFormatDate('now'));
                }
                else if ($start_type == 'future')
                {
                    $start_date = date_create(customFormatDate($sdate));
                }
                $end_date = date_create(customFormatDate($edate));

                $diff = date_diff($start_date, $end_date);
                $days_to_add += (int)$diff->format("%d")+1; // adding 1 because date_diff for 1 day is 0. like date_diff(2020-12-13, 2020-12-14) is 0
            }
        }
            return $days_to_add;
    }
}


if(!function_exists('getDaysVariation'))
{
    function getDaysVariation()
    {
        /**
         * Check the earliest day available for delivery
         * if the earliest day is 'SUN', then add one more day as there is no delivery on Sunday
         * else if the earliest dau is 'SAT' and also customer chosen delivery days is 5, then add 2 more days, as Eatology only delivers on week days for five day meal plan and donot deliver on Sunday
         */

        $total_days = [];
        // $totals = getShippingFee();
        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            // get the number only from no of days string
            preg_match("/([0-9]+)/", $cart_item['variation']['attribute_pa_days'], $days);
            $days = $days[0];
            array_push($total_days, $days);
        }
        // get the max value from the store $total_days; as shipping charge should contain the maximum shipping day
        $max_day = max($total_days);

        return $max_day;
    }
}

/**
 * function to get the earliest available day
 * @return date
 */
if(!function_exists('getEarliestAvailableDates'))
{
    function getEarliestAvailableDates()
    {
        // check if Sunday falls on current day and after two days
        // if so, then add one more day, as sunday is not a delivery day
        $begin = new DateTime('now');
        $end = date('Y-m-d', strtotime(" +2 days"));


        // check if friday today // then set end date to monday ==== FRIDAY => MONDAY
        if($begin->format('D') == 'Fri')
        {
            // friday + 3 == monday
            $end = date('Y-m-d', strtotime(" +3 days"));
        }
        // check if Sat today // then set end date to Tuesday ==== SAT => TUE
        else if($begin->format('D') == 'Sat')
        {
            // Sat + 3 == tuesday
            $end = date('Y-m-d', strtotime(" +3 days"));
        }
        // check if Sun today // then set end date to Tuesday ==== SUN => TUE
        else if($begin->format('D') == 'Sun')
        {
            // Sat + 2 == tuesday
            $end = date('Y-m-d', strtotime(" +2 days"));
        }
        // check if Sun today // then set end date to Tuesday ==== SUN => TUE
        else if($begin->format('D') == 'Thu' && getDaysVariation()==5)
        {
            // Sat + 2 == tuesday
            $end = date('Y-m-d', strtotime(" +4 days"));
        }

        // check if today is a holiday
        if(isEarliestDayAHoliday(date_format($begin, 'Y-m-d')))
        {
            $holidays_count = get_holidays_count('now', 'today');
            $end = date('Y-m-d', strtotime($end . " +" . $holidays_count . " days"));
        }

        // check if tomorrow is a holiday
        $tomorrow = strtotime("now +1day");
        $tomrow = date("Y-m-d",$tomorrow); 
        if(isEarliestDayAHoliday($tomrow))
        {
            $holidays_count = get_holidays_count($tomrow, 'today');
            $end = date('Y-m-d', strtotime($end . " +" . $holidays_count . " days"));
        }

        // get product variations days that was selected
        $daysVariation = getDaysVariation();

        $day_of_end_date = date('D', strtotime($end));
        
        // for 5 day meal plan, as we deliver 5 day meal plan only on weekdays
        // if (($day_of_end_date == 'Sat' && $daysVariation == 5) || ($day_of_end_date == 'Sun' && $daysVariation == 5)) {
        //     $end = date('Y-m-d', strtotime($end . " +1 days"));
        // }
        // // for 6 day meal plan
        // else if ($day_of_end_date == 'Sun')
        // {
        //     $end = date('Y-m-d', strtotime($end . " +1 days"));
        // }
        
        $end = new DateTime(date('Y-m-d', strtotime($end)));
        
        $end->setTime(0, 0, 1); // adding it so that the end date is inclusive
        $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);

        // get product variations days that was selected
        $max_day = getDaysVariation();

        // $sunday = false;
        $weekendNFiveDay = false;
        // foreach ($daterange as $date) {

			// if (($date->format('D') == 'Sat' && $max_day == 5))
            // {
            //     $weekendNFiveDay = true;
            // }
        // }

        // if the date range has a sunday add one more day
        $days = 0;
        // if ($weekendNFiveDay){
        //     $days = 1; // add one more day for escaping Sat on Five day meal plan order
        // }

        $newEndDate = date_format($end, 'Y-m-d');
        $earliestDate = date('Y-m-d', strtotime($newEndDate." +".$days." days"));

        // check if the earliest date is a holiday, then add the total holidays except leave as it is
        if(isEarliestDayAHoliday($earliestDate))
        {
            // get holidays count
            $days_to_add = get_holidays_count($earliestDate, 'future');
            $str = " +".$days_to_add."day";
            $earliestDate = date('Y-m-d', strtotime($earliestDate . $str));
        }

        // checking one more time the new earliest day if it is a holiday again
        // check if the earliest date is a holiday, then add the total holidays except leave as it is
        if(isEarliestDayAHoliday($earliestDate))
        {
            // get holidays count
            $days_to_add = get_holidays_count($earliestDate, 'future');
            $str = " +".$days_to_add."day";
            $earliestDate = date('Y-m-d', strtotime($earliestDate . $str));
        }

        $checkSundayTheDay = date_create($earliestDate);
        
        // if (($checkSundayTheDay->format('D') == 'Sat' && $max_day == 5) || ($checkSundayTheDay->format('D') == 'Sun' && $max_day == 5)) {
        //     $earliestDate = date('Y-m-d', strtotime($earliestDate . " +1 days"));
        // }
        // else if ($checkSundayTheDay->format('D') == 'Sun') {
        //     $earliestDate = date('Y-m-d', strtotime($earliestDate . " +1 day"));
        // }
        return $earliestDate;
    }
}

/**
 * Check cart for product subscription variation.
 * @return bool
 */
if(!function_exists('wecreate_check_for_subscription_variation_in_cart_item'))
{
    function wecreate_check_for_subscription_variation_in_cart_item()
    {
        // Set our flag to be false until we find a product in that category.
        $has_subscription = false;
    
        if (!empty(WC()->cart)) :
        // Check each cart item for our category.
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $weeks = filter_var($cart_item['variation']['attribute_pa_duration']);
    
            if ($weeks == 'subscription') {
                $has_subscription = true;
            }
        }
        endif;
    
        return $has_subscription;
    }
}


/**
 * get no of days
 * @param string chargetype  // chargeable || non-chargeable
 * @return integer
 */
if(!function_exists('wecreate_prorata_chargetype'))
{
    function wecreate_prorata_chargetype($chargeType)
    {
       
        if (!empty(WC()->cart)) :

            // Loop over $cart items
            $total_product_price = 0;
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                $product_price = $cart_item['product_price_cart'];
                // query cart item data object
                $_product = $cart_item['data'];
                // get the sign up fee for that product
                $signup_fee = $_product->get_meta('_subscription_sign_up_fee', true);

                $total_product_price = $product_price + $signup_fee;
                $days = filter_var($cart_item['variation']['attribute_pa_days'], FILTER_SANITIZE_NUMBER_INT);
                $weeks = filter_var($cart_item['variation']['attribute_pa_duration']);
                
                if ($weeks == 'subscription') {
                    $subscription = true;
                }
                $sanitized_days = (int) preg_replace('/\D/ui', '', $days);
                // should
            }

            if ($subscription) {

                $earliestDate = getEarliestAvailableDates();

                // get the day of that earliest date
                $day_in_a_week = date('D', strtotime($earliestDate));

                // allowing week days delivery
                if ($sanitized_days == 5) {
                    switch ($day_in_a_week) {
                        case "Mon":
                            $days_past = 0;
                            $chargeableDays = 5;
                            break;
                        case "Tue":
                            $days_past = 1;
                            $chargeableDays = 4;
                            break;
                        case "Wed":
                            $days_past = 2;
                            $chargeableDays = 3;
                            break;
                        case "Thu":
                            $days_past = 3;
                            $chargeableDays = 2;
                            break;
                        case "Fri":
                            $days_past = 4;
                            $chargeableDays = 1;
                            break;
                        case "Sat":
                            $days_past = 0;
                            $chargeableDays = 5;
                            break;
                        case "Sun":
                            $days_past = 0;
                            $chargeableDays = 5;
                    }
                } else if ($sanitized_days == 6) {
                    switch ($day_in_a_week) {
                        case "Mon":
                            $days_past = 0;
                            $chargeableDays = 6;
                            break;
                        case "Tue":
                            $days_past = 1;
                            $chargeableDays = 5;
                            break;
                        case "Wed":
                            $days_past = 2;
                            $chargeableDays = 4;
                            break;
                        case "Thu":
                            $days_past = 3;
                            $chargeableDays = 3;
                            break;
                        case "Fri":
                            $days_past = 4;
                            $chargeableDays = 2;
                            break;
                        case "Sat":
                            $days_past = 5;
                            $chargeableDays = 1;
                            break;
                        case "Sun":
                            $days_past = 0;
                            $chargeableDays = 6;
                    }
                }

                // calculate subtotal based on cart item price and sign up fee; we could simply get the current subtotal using $cart_object->subtotal; but the prorata setting on plugin already changes the value, so we cannot get the real total amount
                if($chargeType == 'no_chargeable')
                {
                    $updated_price = - ($total_product_price / $sanitized_days * $days_past);
                    return $updated_price;
                }
                else if($chargeType == 'chargeable')
                {
                    return $chargeableDays;
                }
            }
        endif;
    }
}



// add first name and last name to register
add_action('woocommerce_register_form_start', 'wecreateadd_name_woo_account_registration');

function wecreateadd_name_woo_account_registration()
{
?>

    <p class="form-row form-row-first">
        <label for="reg_billing_first_name"><?php _e('First name', 'woocommerce'); ?> <span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php if (!empty($_POST['billing_first_name'])) esc_attr_e($_POST['billing_first_name']); ?>" />
    </p>

    <p class="form-row form-row-last">
        <label for="reg_billing_last_name"><?php _e('Last name', 'woocommerce'); ?> <span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name" value="<?php if (!empty($_POST['billing_last_name'])) esc_attr_e($_POST['billing_last_name']); ?>" />
    </p>

    <div class="clear"></div>

<?php
}

///////////////////////////////
// 2. VALIDATE FIELDS

add_filter('woocommerce_registration_errors', 'wecreatevalidate_name_fields', 10, 3);

function wecreatevalidate_name_fields($errors, $username, $email)
{
    if (isset($_POST['billing_first_name']) && empty($_POST['billing_first_name'])) {
        $errors->add('billing_first_name_error', __('<strong>Error</strong>: First name is required!', 'woocommerce'));
    }
    if (isset($_POST['billing_last_name']) && empty($_POST['billing_last_name'])) {
        $errors->add('billing_last_name_error', __('<strong>Error</strong>: Last name is required!.', 'woocommerce'));
    }
    return $errors;
}

///////////////////////////////
// 3. SAVE FIELDS

add_action('woocommerce_created_customer', 'wecreatesave_name_fields');

function wecreatesave_name_fields($customer_id)
{
    if (isset($_POST['billing_first_name'])) {
        update_user_meta($customer_id, 'billing_first_name', sanitize_text_field($_POST['billing_first_name']));
        update_user_meta($customer_id, 'first_name', sanitize_text_field($_POST['billing_first_name']));
    }
    if (isset($_POST['billing_last_name'])) {
        update_user_meta($customer_id, 'billing_last_name', sanitize_text_field($_POST['billing_last_name']));
        update_user_meta($customer_id, 'last_name', sanitize_text_field($_POST['billing_last_name']));
    }
}


// add check password field
add_action('woocommerce_register_form', 'wc_register_form_password_repeat');
function wc_register_form_password_repeat()
{
?>
    <p class="form-row form-row-wide">
        <label for="reg_password2"><?php _e('Re-enter Password', 'woocommerce'); ?> <span class="required">*</span></label>
        <input type="password" class="input-text" name="password2" id="reg_password2" value="<?php if (!empty($_POST['password2'])) echo esc_attr($_POST['password2']); ?>" />
    </p>
    <?php
}

add_filter('woocommerce_registration_errors', 'registration_errors_validation', 10, 3);
function registration_errors_validation($reg_errors, $sanitized_user_login, $user_email)
{
    global $woocommerce;


    if ($_POST['password'] != $_POST['password2']) {
        return new WP_Error('registration-error', __('Passwords do not match.', 'woocommerce'));
    }

    return $reg_errors;
}


// Check the password and confirm password fields match before allow checkout to proceed.
add_action('woocommerce_checkout_process', 'confirm_password_checkout_validation');
function confirm_password_checkout_validation()
{
    if (!is_user_logged_in() && (WC()->checkout->must_create_account || !empty($_POST['createaccount']))) {
        if (strcmp($_POST['account_password'], $_POST['account_confirm_password']) !== 0)
            wc_add_notice(__("Passwords doesn’t match.", "woocommerce"), 'error');
    }
}

// ----- Add a confirm password field to the checkout page
function lit_woocommerce_confirm_password_checkout($checkout)
{
	if (isset(WC()->session)) {
    if (get_option('woocommerce_registration_generate_password') == 'no') {

        $fields = $checkout->get_checkout_fields();

        $fields['account']['account_confirm_password'] = array(
            'type'              => 'password',
            'label'             => __('Confirm password', 'woocommerce'),
            'required'          => true,
            'placeholder'       => _x('Confirm Password', 'placeholder', 'woocommerce')
        );

        $checkout->__set('checkout_fields', $fields);
    }
	}
}
add_action('woocommerce_checkout_init', 'lit_woocommerce_confirm_password_checkout', 10, 1);


// change password strength and remove meter
function iconic_min_password_strength($strength)
{
    return 1;
}
add_filter('woocommerce_min_password_strength', 'iconic_min_password_strength', 10, 1);

function iconic_remove_password_strength()
{
    wp_dequeue_script('wc-password-strength-meter');
}
add_action('wp_print_scripts', 'iconic_remove_password_strength', 10);


// redirect to home page after logout
add_action('wp_logout', 'auto_redirect_after_logout');

function auto_redirect_after_logout()
{
    wp_safe_redirect(home_url());
    exit;
}


/**
 * Remove the breadcrumbs 
 */
add_action('init', 'woo_remove_wc_breadcrumbs');
function woo_remove_wc_breadcrumbs()
{
    remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
}


/**
 * @snippet       Remove "Default Sorting" Dropdown @ WooCommerce Shop & Archive Pages
 */
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

/**
 * @snippet       Remove "Showing the Single Result" - WooCommerce
 */
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);


// add meal plan details to shop listing
function meal_plan_benefits_listing()
{
    if (is_shop()) {
        include get_template_directory() . "/template-parts/woocommerce/shop-benefits.php";
    }
}
add_action('woocommerce_before_shop_loop', 'meal_plan_benefits_listing');


// add meal plan details to shop listing
function shop_contents()
{
    if (is_shop()) {
        $shop_page_id = apply_filters('wpml_object_id', 451, 'page', true);
    ?>
        <section id="after-shop-loop-content" class="white-header">
            <p><?php
                $page_object = get_post($shop_page_id);
                echo $page_object->post_content;
                ?> </p>
        </section>
    <?php }
}
add_action('woocommerce_after_shop_loop', 'shop_contents');

// remove sidebar for woocommerce pages 
function remove_sidebar()
{
    if (is_shop() || is_product()) {
        remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
    }
}
add_action('woocommerce_before_main_content', 'remove_sidebar');



// remove add to cart and price from product listing
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price');


// 
// my account section
//



//
// Uncomment below for delivery calendar
//

function my_custom_my_account_menu_items( $items ) {

	$new_item = array( 
        'delivery-calendar' => __( 'Delivery Calendar', 'woocommerce' ),
        'meal-ratings' => __( 'Meal Ratings', 'woocommerce' ) 
    );
	
    // add item in 3rd place
	$items = array_slice($items, 0, 2, TRUE) + $new_item + array_slice($items, 2, NULL, TRUE);
    
    // remove the ff from woocommerce
    // -orders
    if(isset($items['orders'])) {
        unset($items['orders']);
    }
    // -addresses
    if(isset($items['edit-address'])) {
        unset($items['edit-address']);
    }

    return $items;

}
add_filter( 'woocommerce_account_menu_items', 'my_custom_my_account_menu_items' );

add_action('init', function() {
	add_rewrite_endpoint('delivery-calendar', EP_ROOT | EP_PAGES);
	add_rewrite_endpoint('meal-ratings', EP_ROOT | EP_PAGES);
});

// so you can use is_wc_endpoint_url( 'delivery-calendar' )
function my_custom_woocommerce_query_vars( $vars ) {
	$vars['delivery-calendar'] = 'delivery-calendar';
	$vars['meal-ratings'] = 'meal-ratings';
	return $vars;
}
add_filter( 'woocommerce_get_query_vars', 'my_custom_woocommerce_query_vars', 0 );


function my_custom_flush_rewrite_rules() {
    flush_rewrite_rules();
}

function my_custom_endpoint_content() {
    wc_get_template( 'myaccount/delivery-calendar.php');
}
add_action( 'woocommerce_account_delivery-calendar_endpoint', 'my_custom_endpoint_content' );

function my_custom_endpoint_content_meal() {
    wc_get_template( 'myaccount/meal-ratings.php');
}
add_action( 'woocommerce_account_meal-ratings_endpoint', 'my_custom_endpoint_content_meal' );








function myaccount_required_fields($account_fields)
{
    unset($account_fields['account_display_name']); // Display name
    return $account_fields;
}
add_filter('woocommerce_save_account_details_required_fields', 'myaccount_required_fields');


// Save the custom fields to DB
add_action('woocommerce_save_account_details', 'save_custom_fields_account', 12, 1);
function save_custom_fields_account($user_id)
{
    //  billing district
    if (isset($_POST['billing_region'])) {
        update_user_meta($user_id, 'billing_region', sanitize_text_field($_POST['billing_region']));
    }
    //  billing sub district
    if (isset($_POST['billing_postcode'])) {
        update_user_meta($user_id, 'billing_postcode', sanitize_text_field($_POST['billing_postcode']));
    }

    //  shipping district
    if (isset($_POST['shipping_region'])) {
        update_user_meta($user_id, 'shipping_region', sanitize_text_field($_POST['shipping_region']));
    } else {
        update_user_meta($user_id, 'shipping_region', sanitize_text_field($_POST['billing_region']));
    }


    //  shipping sub district
    if (isset($_POST['shipping_postcode'])) {
        update_user_meta($user_id, 'shipping_postcode', sanitize_text_field($_POST['shipping_postcode']));
    } else {
        update_user_meta($user_id, 'shipping_postcode', sanitize_text_field($_POST['billing_postcode']));
    }
}



/**
 * @snippet       Hide SKU, Cats, Tags @ Single Product Page - WooCommerce
 */
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);





function wecreate_add_before_details()
{
    $subscription_box_heading = get_field('subscription_box_heading', 'option');
    $subscription_box_sub_heading = get_field('subscription_box_sub_heading', 'option');
    $subscription_box_text = get_field('subscription_box_text', 'option');
    echo '
    <div class="product-subscription-wrapper-mobile">
        <div class="subscription-text">
            <h4>' . $subscription_box_heading . '</h4>
            <h5>' . $subscription_box_sub_heading . '</h5>
            <ul>
                ' . $subscription_box_text . '
            </ul>
        </div>
    </div>
    ';
}
add_action('woocommerce_after_single_product_summary', 'wecreate_add_before_details', 1);


/**
 * @snippet       Change Gallery Columns @ Single Product Page
 */
function wecreate_change_gallery_columns()
{
    return 1;
}
add_filter('woocommerce_product_thumbnails_columns', 'wecreate_change_gallery_columns');


//global array to reposition the elements to display as you want (e.g. kept 'start_date' before 'first_name' )
$wecreate_address_fields = array(
    'first_name',
    'last_name',
    'floor_number',
    'flat_number',
    'tower_block',
    'address_2',
    'address_1',
    'country',
    'state',
    'region',
    'postcode',
    'email',
    'phone',
    // 'asia_miles',
);


//global array only for extra fields
$wecreate_ext_fields = array(
    'region',
    // 'asia_miles',
    'floor_number',
    'flat_number',
    'tower_block',
    'postcode',
);


function wecreate_override_default_address_fields($address_fields)
{

    $temp_fields = array();


    // $address_fields['asia_miles'] = array(
    //     'label'     => __('Asia Miles', 'woocommerce'),
    //     'required'  => false,
    //     'class'     => array('form-row-wide floor-number'),
    //     'type'  => 'text',
    // );


    $address_fields['floor_number'] = array(
        'label'     => __('Floor Number', 'woocommerce'),
        'required'  => true,
        'class'     => array('form-row-first floor-number'),
        'type'  => 'text',
    );

    $address_fields['flat_number'] = array(
        'label'     => __('Flat Number', 'woocommerce'),
        'required'  => true,
        'class'     => array('form-row-last flat-number'),
        'type'  => 'text',
    );

    $address_fields['tower_block'] = array(
        'label'     => __('Tower Block', 'woocommerce'),
        'required'  => false,
        'class'     => array('form-row-first tower-block'),
        'type'  => 'text',
    );

    $address_fields['address_2'] = array(
        'class'     => array('form-row-last'),
        'label' => __('Building Name', 'woocommerce')
    );

    $address_fields['email'] = array(
        'class'     => array('form-row-first'),
    );

    $address_fields['phone'] = array(
        'class'     => array('form-row-last'),
    );

    $address_fields['region'] = array(
        'label'     => __('District', 'woocommerce'),
        'required'  => true,
        'type'      => 'select',
        'class'     => array('form-row-first region_field'),
        'options'    => array(
            '' => 'Select',
        )
    );

    $address_fields['postcode'] = array(
        'label'     => __('Sub District', 'woocommerce'),
        'type'      => 'select',
        'class'     => array('form-row-last sub_district_field update_totals_on_change'),
        'options'    => array(
            '' => 'Select',
        )
    );

    // adding the priority for each field 
    $address_fields['first_name']['priority'] = 10;
    $address_fields['last_name']['priority'] = 20;
    $address_fields['floor_number']['priority'] = 30;
    $address_fields['flat_number']['priority'] = 40;
    $address_fields['tower_block']['priority'] = 50;
    $address_fields['address_2']['priority'] = 60;
    $address_fields['address_1']['priority'] = 70;
    $address_fields['country']['priority'] = 80;
    $address_fields['state']['priority'] = 90;
    $address_fields['region']['priority'] = 94;
    $address_fields['postcode']['priority'] = 96;
    // $address_fields['asia_miles']['priority'] = 120;



    global $wecreate_address_fields;

    foreach ($wecreate_address_fields as $fky) {
        $temp_fields[$fky] = $address_fields[$fky];
    }

    $address_fields = $temp_fields;

    return $address_fields;
}
add_filter('woocommerce_default_address_fields', 'wecreate_override_default_address_fields');




// make billing post code required exclusively
add_filter('woocommerce_billing_fields', 'make_billing_subdistrict_required', 10, 1);
function make_billing_subdistrict_required($address_fields)
{
    $address_fields['billing_postcode']['required'] = true;
    return $address_fields;
}

// make shipping post code required exclusively
add_filter('woocommerce_shipping_fields', 'make_shipping_subdistrict_required', 10, 1);
function make_shipping_subdistrict_required($address_fields)
{
    $address_fields['shipping_postcode']['required'] = true;
    return $address_fields;
}



function wecreate_update_formatted_billing_address($address, $obj)
{

    global $wecreate_address_fields;

    if (is_array($wecreate_address_fields)) {

        foreach ($wecreate_address_fields as $waf) {
            $address[$waf] = $obj->{'billing_' . $waf};
        }
    }

    return $address;
}
add_filter('woocommerce_order_formatted_billing_address', 'wecreate_update_formatted_billing_address', 99, 2);


function wecreate_update_formatted_shipping_address($address, $obj)
{

    global $wecreate_address_fields;

    if (is_array($wecreate_address_fields)) {

        foreach ($wecreate_address_fields as $waf) {
            $address[$waf] = $obj->{'shipping_' . $waf};
        }
    }


    return $address;
}
add_filter('woocommerce_order_formatted_shipping_address', 'wecreate_update_formatted_shipping_address', 99, 2);



function wecreate_my_account_address_formatted_address($address, $customer_id, $name)
{

    global $wecreate_address_fields;


    if (is_array($wecreate_address_fields)) {

        foreach ($wecreate_address_fields as $waf) {
            $address[$waf] = get_user_meta($customer_id, $name . '_' . $waf, true);
        }
    }

    return $address;
}
add_filter('woocommerce_my_account_my_address_formatted_address', 'wecreate_my_account_address_formatted_address', 99, 3);




function wecreate_add_extra_customer_field($fields)
{

    //take back up of email and phone fields as they will be lost after repositioning
    $email = $fields['email'];
    $phone = $fields['phone'];

    $fields = wecreate_override_default_address_fields($fields);

    //reassign email and phone fields
    $fields['email'] = $email;
    $fields['phone'] = $phone;

    global $wecreate_ext_fields;

    if (is_array($wecreate_ext_fields)) {

        foreach ($wecreate_ext_fields as $wef) {
            $fields[$wef]['show'] = false; //hide the way they are display by default as we have now merged them within the address field
        }
    }

    return $fields;
}
add_filter('woocommerce_admin_billing_fields', 'wecreate_add_extra_customer_field');



function wecreate_add_extra_customer_field_shipping($fields)
{


    $fields = wecreate_override_default_address_fields($fields);


    global $wecreate_ext_fields;

    if (is_array($wecreate_ext_fields)) {

        foreach ($wecreate_ext_fields as $wef) {
            $fields[$wef]['show'] = false; //hide the way they are display by default as we have now merged them within the address field
        }
    }

    unset($fields['email']);
    unset($fields['phone']);

    return $fields;
}
add_filter('woocommerce_admin_shipping_fields', 'wecreate_add_extra_customer_field_shipping');



/**
 * @snippet       Remove Additional Information Tab @ WooCommerce Single Product Page
 */
function wecreate_remove_product_tabs($tabs)
{
    unset($tabs['additional_information']);
    unset($tabs['reviews']);
    // unset($tabs['description']);
    return $tabs;
}
add_filter('woocommerce_product_tabs', 'wecreate_remove_product_tabs', 9999);




/**
 * Remove related products output
 */
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);


/**
 * Rename product data tabs
 */
add_filter('woocommerce_product_tabs', 'wecreate_woo_rename_tabs', 98);
function wecreate_woo_rename_tabs($tabs)
{
    $tabs['description']['title'] = __('Details', 'eatology');
    return $tabs;
}

// rename the description heading
add_filter('woocommerce_product_description_heading', 'wecreate_description_heading');
function wecreate_description_heading($heading)
{

    return __('Details', 'eatology');
}


/**

 * add back to meal plans

 */
function add_back_meal_plans()
{
    include(get_template_directory() . '/template-parts/woocommerce/back-to-meals.php');
}
add_action('woocommerce_before_single_product', 'add_back_meal_plans', 5);



/**

 * add header to cart

 */
function add_head_to_cart()
{
    include(get_template_directory() . '/template-parts/cart/header.php');
}
add_action('woocommerce_before_cart', 'add_head_to_cart', 5);
add_action('woocommerce_before_checkout_form', 'add_head_to_cart', 5);
add_action('woocommerce_before_thankyou', 'add_head_to_cart', 5);




// Remove Cross Sells From Default Position 
// Add them back UNDER the Cart Table

remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
add_action('woocommerce_after_cart', 'woocommerce_cross_sell_display', 50);


// ---------------------------------------------
// Display Cross Sells on 3 columns instead of default 4

function wecreate_change_cross_sells_columns($columns)
{
    return 3;
}
add_filter('woocommerce_cross_sells_columns', 'wecreate_change_cross_sells_columns');


// ---------------------------------------------
// Display Only 3 Cross Sells instead of default 4

function wecreate_change_cross_sells_product_no($columns)
{
    return 3;
}
add_filter('woocommerce_cross_sells_total', 'wecreate_change_cross_sells_product_no');


function wecreate_custom_related_products_text($translated_text, $text, $domain)
{

    if ($translated_text == 'Related products' || $translated_text == 'You may also like&hellip;' || $translated_text == 'You may be interested in&hellip;') {
        $translated_text = 'People also bought';
    }
    // check wpml strings is working with this
    return $translated_text;
}
add_filter('gettext', 'wecreate_custom_related_products_text', 20, 3);


/** 
 * Replace WooCommerce Delivery Slots labels. 
 * 
 * @param array $labels An array of labels.
 * @param WC_Order $order The WooCommerce order object.
 *                        
 * @return array
 */
function iconic_modify_delivery_slots_label($labels, $order)
{
    $labels['date']              = 'Start Date';

    return $labels;
}

add_filter('iconic_wds_labels', 'iconic_modify_delivery_slots_label', 10, 2);



// filter to add custom states
add_filter('woocommerce_states', 'wecreate_woo_state');
function wecreate_woo_state($states)
{
    $states['HK'] = array(
        'HONG KONG ISLAND' => __('Hong Kong Island', 'eatology'),
        'KOWLOON' => __('Kowloon', 'eatology'),
        'NEW TERRITORIES' => __('New Territories', 'eatology'),
        'ISLANDS' =>  __('Islands', 'eatology'),
    );
    return $states;
}

add_filter('default_checkout_billing_state', 'preselect_default_billing_state');
function preselect_default_billing_state()
{
    return 'HONG KONG'; // state code
}

add_filter('default_checkout_shipping_state', 'preselect_default_shipping_state');
function preselect_default_shipping_state()
{
    return 'HONG KONG'; // state code
}

// hook to process the custom field

/**
 * Update the order meta with field value
 */
add_action('woocommerce_checkout_update_order_meta', 'wecreate_update_order_meta');

function wecreate_update_order_meta($order_id)
{
    // update billing sub district meta
    if (!empty($_POST['billing_region'])) {
        update_post_meta($order_id, 'billing_region', sanitize_text_field($_POST['billing_region']));
    }
    // update billing Tower Block meta
    if (!empty($_POST['billing_tower_block'])) {
        update_post_meta($order_id, 'billing_tower_block', sanitize_text_field($_POST['billing_tower_block']));
    }
    // update billing Asia Miles meta
    // if (!empty($_POST['billing_asia_miles'])) {
    //     update_post_meta($order_id, 'billing_asia_miles', sanitize_text_field($_POST['billing_asia_miles']));
    // }
    // update billing Tower Block meta
    if (!empty($_POST['billing_floor_number'])) {
        update_post_meta($order_id, 'billing_floor_number', sanitize_text_field($_POST['billing_floor_number']));
    }
    // update billing Tower Block meta
    if (!empty($_POST['billing_flat_number'])) {
        update_post_meta($order_id, 'billing_flat_number', sanitize_text_field($_POST['billing_flat_number']));
    }

    // update shipping sub district meta
    if (!empty($_POST['shipping_region'])) {
        update_post_meta($order_id, 'shipping_region', sanitize_text_field($_POST['shipping_region']));
    }
    // update shipping Tower Block meta
    if (!empty($_POST['shipping_tower_block'])) {
        update_post_meta($order_id, 'shipping_tower_block', sanitize_text_field($_POST['shipping_tower_block']));
    }
    // update shipping Asia Miles meta
    // if (!empty($_POST['shipping_asia_miles'])) {
    //     update_post_meta($order_id, 'shipping_asia_miles', sanitize_text_field($_POST['shipping_asia_miles']));
    // }
    // update shipping Tower Block meta
    if (!empty($_POST['shipping_floor_number'])) {
        update_post_meta($order_id, 'shipping_floor_number', sanitize_text_field($_POST['shipping_floor_number']));
    }
    // update shipping Tower Block meta
    if (!empty($_POST['shipping_flat_number'])) {
        update_post_meta($order_id, 'shipping_flat_number', sanitize_text_field($_POST['shipping_flat_number']));
    }
}


/**
 * Display billing custom field value on the admin order edit page
 */
add_action('woocommerce_admin_order_data_after_billing_address', 'wecreate_checkout_billing_field_display_admin_order_meta', 10, 1);

function wecreate_checkout_billing_field_display_admin_order_meta($order)
{

    $flat = get_post_meta($order->get_id(), 'billing_flat_number', true);
    $floor = get_post_meta($order->get_id(), 'billing_floor_number', true);
    $tower = get_post_meta($order->get_id(), 'billing_tower_block', true);
    $region = get_post_meta($order->get_id(), 'billing_region', true);

    echo '<p><strong>' . __('Full Address', 'woocommerce') . '</strong><br>';
    echo $order->get_formatted_billing_full_name() . "<br>";

    if (!empty($flat)) {
        echo __('Flat: ', 'eatology') . $flat . ", ";
    }

    if (!empty($floor)) {
        echo __('Floor: ', 'eatology') . $floor . ", ";
    }

    if (!empty($tower)) {
        echo $tower;
    }

    echo "<br>";

    if ($order->get_billing_address_1()) : ?>
        <?php echo esc_html($order->get_billing_address_1()) . '<br>'; ?>
    <?php endif; ?>

    <?php if ($order->get_billing_address_2()) : ?>
        <?php echo esc_html($order->get_billing_address_2()) . "<br>"; ?>
    <?php endif; ?>

    <?php
    if ($order->get_billing_postcode()) : ?>
        <?php
        echo esc_html($GLOBALS['sub_districts_array'][$order->get_billing_state()][$region][$order->get_billing_postcode()]) . ', ';
        ?>
    <?php endif; ?>

    <?php

    if (!empty($region)) {
        echo $region;
    }

    echo "<br>";

    echo esc_html($order->get_billing_state()) . "<br>";

    echo '</p>';

    // $asiamiles = get_post_meta($order->get_id(), 'billing_asia_miles', true);
    // if (!empty($asiamiles)) {
    //     echo '<p><strong>' . __('Asia Miles', 'woocommerce') . '</strong><br>' . $asiamiles . '</p>';
    // }
}


/**
 * Display shipping custom field value on the admin order edit page
 */
add_action('woocommerce_admin_order_data_after_shipping_address', 'wecreate_checkout_shipping_field_display_admin_order_meta', 10, 1);

function wecreate_checkout_shipping_field_display_admin_order_meta($order)
{

    $flat = get_post_meta($order->get_id(), 'shipping_flat_number', true);
    $floor = get_post_meta($order->get_id(), 'shipping_floor_number', true);
    $tower = get_post_meta($order->get_id(), 'shipping_tower_block', true);
    $region = get_post_meta($order->get_id(), 'shipping_region', true);
    // $asiamiles = get_post_meta($order->get_id(), 'shipping_asia_miles', true);


    echo '<p><strong>' . __('Full Address', 'woocommerce') . '</strong><br>';
    echo $order->get_formatted_shipping_full_name() . "<br>";

    if (!empty($flat)) {
        echo __('Flat: ', 'eatology') . $flat . ", ";
    } else {
        $flat = get_post_meta($order->get_id(), 'billing_flat_number', true);
        echo __('Flat: ', 'eatology') . $flat . ", ";
    }

    if (!empty($floor)) {
        echo __('Floor: ', 'eatology') . $floor . ", ";
    } else {
        $floor = get_post_meta($order->get_id(), 'billing_floor_number', true);
        echo __('Floor: ', 'eatology') . $floor . ", ";
    }

    if (!empty($tower)) {
        echo $tower;
    } else {
        $tower = get_post_meta($order->get_id(), 'billing_tower_block', true);
        echo $tower;
    }
    echo "<br>";

    if ($order->get_shipping_address_1()) : ?>
        <?php echo esc_html($order->get_shipping_address_1()) . '<br>'; ?>
    <?php endif; ?>

    <?php if ($order->get_shipping_address_2()) : ?>
        <?php echo esc_html($order->get_shipping_address_2()) . "<br>"; ?>
    <?php endif; ?>

    <?php
    if ($order->get_shipping_postcode()) : ?>
        <?php
        echo esc_html($GLOBALS['sub_districts_array'][$order->get_shipping_state()][$region][$order->get_shipping_postcode()]) . ', ';
        ?>
    <?php endif; ?>


    <?php
    if (!empty($region)) {
        echo $region;
    } else {
        $region = get_post_meta($order->get_id(), 'billing_region', true);
        echo $region;
    }
    echo "<br>";

    echo esc_html($order->get_shipping_state()) . "<br>";

    echo '</p>';

    // if (!empty($asiamiles)) {
    //     echo '<p><strong>' . __('Asia Miles', 'woocommerce') . '</strong><br>' . $asiamiles . '</p>';
    // } else {
    //     $asiamiles = get_post_meta($order->get_id(), 'billing_asia_miles', true);
    //     echo '<p><strong>' . __('Asia Miles', 'woocommerce') . '</strong><br>' . $asiamiles . '</p>';
    // }
}


//Check if Product in Cart Already
function Check_if_product_in_cart($product_ids)
{
    foreach (WC()->cart->get_cart() as $cart_item) :

        $items_id = $cart_item['product_id'];
        $QTY = $cart_item['quantity'];

        // for a unique product ID (integer or string value)
        if ($product_ids == $items_id) :
            return ['in_cart' => true, 'QTY' => $QTY];

        endif;

    endforeach;
}


// hook to redirect to cart page
/**
 * Redirect users after add to cart.
 */
function custom_redirect_to_cart($url)
{

    $url = wc_get_cart_url(); // URL to redirect to (1 is the page ID here)

    return $url;
}
add_filter('woocommerce_add_to_cart_redirect', 'custom_redirect_to_cart');



/************************************************* Hook to change the quantity increment decrement icon  **********************************************/
add_action('wecreate_before_checkout_quantity', 'wecreate_before_checkout_minus');
function wecreate_before_checkout_minus()
{
    echo '<button class="custom-minus"><span class="icon-minus"></span></button>';
}


add_action('wecreate_after_checkout_quantity', 'wecreate_after_checkout_plus');
function wecreate_after_checkout_plus()
{
    echo '<button type="button" class="custom-plus"><span class="icon-plus"></span></button>';
}

add_action('wp_footer', 'custom_cart_quantity_plus_minus');
function custom_cart_quantity_plus_minus()
{
    ?>
    <script type="text/javascript">
        // jQuery to change the value of the product quantity             
        jQuery(document).ready(function($) {
            $(document).on('click', '.eatology_cart_table button.custom-plus, .eatology_cart_table button.custom-minus', function(e) {
                e.preventDefault();
                $('.eatology_cart_table button[type=submit]').prop("disabled", false);
                // Get current quantity values
                var qty = $(this).closest('.product-quantity').find('.qty');
                var val = parseFloat(qty.val());
                var max = parseFloat(qty.attr('max'));
                var min = parseFloat(qty.attr('min'));
                var step = parseFloat(qty.attr('step'));
                var updated_val = '';
                // Change the value if plus or minus
                if ($(this).is('.custom-plus')) {
                    if (max && (max <= val)) {
                        updated_val = max;
                    } else {
                        updated_val = val + step;
                    }
                } else {
                    if (min && (min >= val)) {
                        updated_val = min;
                    } else if (val > 1) {
                        updated_val = val - step;
                    }
                }

                qty.val(parseInt(updated_val));
                // $('.single-product-extra-detail .product-volume #item-qty').text(updated_val)
            });
        });
    </script>
<?php
}



/************************************************* SINGLE PRODUCT TEMPLATE  **********************************************/
add_action('woocommerce_before_add_to_cart_quantity', 'custom_display_quantity_minus');
function custom_display_quantity_minus()
{
    global $product;
    if ($product->is_sold_individually() || $product->get_stock_quantity() == 1) return;
    echo '<button class="custom-minus"><span class="icon-minus"></span></button>';
}


add_action('woocommerce_after_add_to_cart_quantity', 'custom_display_quantity_plus');
function custom_display_quantity_plus()
{
    global $product;
    if ($product->is_sold_individually() || $product->get_stock_quantity() == 1) return;
    echo '<button type="button" class="custom-plus"><span class="icon-plus"></span></button>';
}


add_action('wp_footer', 'custom_add_cart_quantity_plus_minus');
function custom_add_cart_quantity_plus_minus()
{
    if (!is_product()) return;

?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $(document).on('click', 'form.cart button.custom-plus, form.cart button.custom-minus', function(e) {
                e.preventDefault();

                // Get current quantity values
                var qty = $(this).closest('form.cart').find('input.qty');
                var val = parseFloat(qty.val());
                var max = parseFloat(qty.attr('max'));
                var min = parseFloat(qty.attr('min'));
                var step = parseFloat(qty.attr('step'));
                var updated_val = '';

                // Change the value if plus or minus
                if ($(this).is('.custom-plus')) {
                    if (max && (max <= val)) {
                        updated_val = max;
                    } else {
                        updated_val = val + step;
                    }
                } else {
                    if (min && (min >= val)) {
                        updated_val = min;
                    } else if (val > 1) {
                        updated_val = val - step;
                    }
                }
                qty.val(parseInt(updated_val));
            });
        });
    </script>
    <?php
}


/*********************** No shipping available text hook *****************/
add_filter('woocommerce_cart_no_shipping_available_html', 'change_noship_message');
add_filter('woocommerce_no_shipping_available_html', 'change_noship_message');
function change_noship_message()
{
    echo __("Please select the sub-district field to calculate the shipping fee", "eatology");
}


// disable postcode validate
add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields', 99);
function custom_override_checkout_fields($fields)
{

    unset($fields['billing']['billing_postcode']['validate']);
    unset($fields['shipping']['shipping_postcode']['validate']);
    // disable country field
    unset($fields['billing']['billing_country']);
    unset($fields['shipping']['shipping_country']);
    $fields["shipping"]["shipping_country"] = 'HK';
    $fields["billing"]["billing_country"] = 'HK';
    $fields['billing']['billing_phone']['class'] = array('form-row-first');
    $fields['billing']['billing_email']['class'] = array('form-row-last');

    return $fields;
}

/************************ update shipping fee based on days and weeks variation selected *********************/

    $load = true;

    add_filter('woocommerce_package_rates', 'total_days_based_shipping_fee', 10, 2);
    function total_days_based_shipping_fee($rates, $package)
    {

        // restore the old calculation without the subscription
        $total_days = [];
        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            // get the number only from no of days string
            preg_match("/([0-9]+)/", $cart_item['variation']['attribute_pa_days'], $days);
            // get the number only from no of weeks string
            preg_match("/([0-9]+)/", $cart_item['variation']['attribute_pa_duration'], $weeks);
            array_push($total_days, $days[1] * $weeks[1]);
        }
        // 
        $max_days = max($total_days);
    
        foreach ($rates as $rate_key => $rate) {
            if ('flat_rate' === $rate->method_id) {
    
                // Get rate cost and Custom cost
                $initial_cost = $rates[$rate_key]->cost;
                // Calculation
                $new_cost = $initial_cost * $max_days;
    
                // Set Custom rate cost
                $rates[$rate_key]->cost = round($new_cost, 2);
    
                // Taxes rate cost (if enabled)
                $new_taxes = array();
                $has_taxes = false;
                foreach ($rate->taxes as $key => $tax) {
                    if ($tax > 0) {
                        // Calculating the tax rate unit
                        $tax_rate = $tax / $initial_cost;
                        // Calculating the new tax cost
                        $new_tax_cost = $tax_rate * $new_cost;
                        // Save the calculated new tax rate cost in the array
                        $new_taxes[$key] = round($new_tax_cost, 2);
                        $has_taxes = true;
                    }
                }
                // Set new tax rates cost (if enabled)
                if ($has_taxes)
                    $rate->taxes = $new_taxes;
            }
        }

        return $rates;

    }

    // Set a minimum order amount for checkout
    // add_action( 'woocommerce_checkout_process', 'wc_minimum_order_amount' );
    // add_action( 'woocommerce_before_cart' , 'wc_minimum_order_amount' );

    // function wc_minimum_order_amount() {
    //     // Set this variable to specify a minimum order value
    //     $minimum = 50;

    //     if ( WC()->cart->total < $minimum ) {

    //         if( is_cart() ) {

    //             wc_print_notice( 
    //                 sprintf( 'Your current order total is %s — you must have an order with a minimum of %s to place your order ' , 
    //                     wc_price( WC()->cart->total ), 
    //                     wc_price( $minimum )
    //                 ), 'error' 
    //             );

    //         } else {

    //             wc_add_notice( 
    //                 sprintf( 'Your current order total is %s — you must have an order with a minimum of %s to place your order' , 
    //                     wc_price( WC()->cart->total ), 
    //                     wc_price( $minimum )
    //                 ), 'error' 
    //             );

    //         }
    //     }
    // }


// hook to customize shipping fee label
// seems this is for subscription feature
//add_filter('woocommerce_cart_shipping_method_full_label', 'wecreate_shipping_fee_labelx', 9999, 2);

function wecreate_shipping_fee_label($label, $method)
{

    if ($method->method_id == 'flat_rate') {
        $new_label = preg_replace('/^.+:/', '', $label);
        return $new_label;
    }
    return $label;
}



/**
 * Change minimum delivery date for public holidays.
 *
 * If a certain product is in the cart (ID 100), add
 * 4 days to the minimum selectable delivery date.
 *
 * @param array $min
 *
 * @return array
 */

// the following hook has been disabled .. as the function is covered on plugin update v1.11.0
// add_filter('iconic_wds_min_delivery_date', 'iconic_change_min_delivery_date');
// function iconic_change_min_delivery_date($min)
// {

//     // $holidays hold the holiday values set on backend plugin settings
//     $holidays = get_option('jckwds_settings')['holidays_holidays_holidays'];

//     // date_format is a value set on backend plugin setting
//     $dte_format = get_option('jckwds_settings')['datesettings_datesettings_dateformat'];

//     // replce mm with m and dd with d
//     $new_dte_format = str_replace("mm", "m", $dte_format);
//     $new_dte_format =  str_replace("dd", "d", $new_dte_format);

//     // $minimum_day is a value set on backend plugin setting
//     $minimum_day = get_option('jckwds_settings')['datesettings_datesettings_minimum'];

//     // date_default_timezone_set('Asia/Hong_Kong');
//     // define tomorrow date
//     $tmrdate = new DateTime('tomorrow');

//     // echo "<pre>";
//     // print_r($tmrdate);
//     $tmrow  = date_format($tmrdate, $new_dte_format);

//     if (!function_exists('customFormatDate')) {
//         function customFormatDate($date)
//         {
//             $newdate = str_replace('/', '-', $date);
//             return date('Y-m-d', strtotime($newdate));
//         }
//     }

//     foreach ($holidays as $holiday) {
//         if (in_array($tmrow, $holiday)) {
//             $sdate = $holiday['date'];
//             $edate = empty($holiday['date_to']) ? $holiday['date'] : $holiday['date_to'];

//             // check if the public holiday is saturday; if so, add one more day to escape sunday (plugin didnot auto handle because we are using hook to define the min allowed days)
//             if (date('N', strtotime(customFormatDate($edate))) >= 6) {
//                 $plus_sunday = 1;
//             } else {
//                 $plus_sunday = 0;
//             }

//             $start_date = date_create(customFormatDate($sdate));
//             $end_date = date_create(customFormatDate($edate));
//             $diff = date_diff($start_date, $end_date);

//             $total_days = (int)$diff->format("%d") + 1 + $minimum_day + $plus_sunday; // +1 for the date diff because date_diff(2020-07-16, 2020-07-16) is 0

//             $days_to_add = $total_days;

//             // This filter returns an array containing the days to add and a timestamp.
//             return array(
//                 'days_to_add' => $days_to_add,
//                 'timestamp'   => strtotime(" +" . $days_to_add . " day", current_time('timestamp')),
//             );
//         }
//     }

//     return $min;
// }


/**
 * Exclude products from a particular category on the shop page
 */
function hide_addon_products_shop($q)
{

    if (is_shop()) {
        $tax_query = (array) $q->get('tax_query');
        $tax_query[] = array(
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => array('add-on-product'),
            'operator' => 'NOT IN',
        );
        $q->set('tax_query', $tax_query);
    }
}
add_action('woocommerce_product_query', 'hide_addon_products_shop');


function custom_attribute_label($label, $name, $product)
{
    if (!is_product()) {
        if ($name == 'pa_meals') {
            $label = __('Meals', 'woocommerce');
        }
    }
    return $label;
}
add_filter('woocommerce_attribute_label', 'custom_attribute_label', 10, 3);


/**
 * Add Prorata Fee
 */

function add_administration_fees($cart)
{
    // if (is_admin() && !defined('DOING_AJAX'))
    //     return;

        // check if the cart has a subscription variation; then only proceed
        if(wecreate_check_for_subscription_variation_in_cart_item())
        {
            // check if the cart is not recurring but initial purchase
            if (empty($cart->recurring_cart_key)) {
                $first_week_prorata = wecreate_prorata_chargetype('no_chargeable');
                if(intval($first_week_prorata) < 0)
                {
                    $cart->add_fee('First week pro-rata', $first_week_prorata);
                }
            }
        }
}
add_filter('woocommerce_cart_calculate_fees', 'add_administration_fees', 10, 1);


/**
 * Modify allowed days for delivery.
 * If a selected days variation is 5 in the cart, then disable saturday and sunday
 *
 * @param array $allowed_days
 *
 * @return array
 */
function iconic_change_allowed_days( $allowed_days ) {

    // if (is_admin() && !defined('DOING_AJAX'))
    //     return;

    // check if the cart has a subscription variation; then only proceed
    if(wecreate_check_for_subscription_variation_in_cart_item())
    {
        $total_days = [];
        // $totals = getShippingFee();
        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            // get the number only from no of days string
            preg_match("/([0-9]+)/", $cart_item['variation']['attribute_pa_days'], $days);
            $days = $days[0];
            array_push($total_days, $days);
        }

        // get the max value from the store $total_days; as shipping charge should contain the maximum shipping day
        $max_day = max($total_days);

        // if customer chooses 5 day meal plan, deliver only on week days
        if ( $max_day == 5 ) {
            return array(
                0 => false, // Sunday
                1 => true, // Monday
                2 => true, // Tuesday
                3 => true, // Wednesday
                4 => true, // Thursday
                5 => true, // Friday
                6 => false, // Saturday
            );
        }
    }
    return $allowed_days;
}

add_filter( 'iconic_wds_allowed_days', 'iconic_change_allowed_days' );



/**
 * Modify available dates 
 *
 * @param array $available_dates
 *
 * @return array
 */
function wecreate_modify_delivery_dates( $available_dates ) {
    
    // if (is_admin() && !defined('DOING_AJAX'))
    //     return;

        // check if the cart has a subscription variation; then only proceed
        if(wecreate_check_for_subscription_variation_in_cart_item())
        {
            // echo getEarliestAvailableDates();
            $enabledDay=date_create(getEarliestAvailableDates());
            $noRemove = [date_format($enabledDay,"d/m/Y")];

            foreach ( $available_dates as $key => $available_date ) {
                if ( !in_array( $available_date, $noRemove, true ) ) {
                    unset( $available_dates[ $key ] );
                }
            }
        }

	return $available_dates;
}
 add_filter( 'iconic_wds_available_dates', 'wecreate_modify_delivery_dates' ,10 , 1);

/**
 * HOOK to remove the subscription when both product variationsa re added to cart
 * Set a flag to indicate that the error message needs to be displayed. We can add the message to the $woocommerce global
 * yet because it will be removed by Subscriptions later on the 'add_to_cart_redirect' hook.
 */
// function eg_set_product_removed_message( $valid, $product_id, $quantity ) {
// 	global $woocommerce, $eg_set_product_removed_message;
 
// 	if ( $woocommerce->cart->get_cart_contents_count() > 0 && WC_Subscriptions_Product::is_subscription( $product_id ) ) {
// 		$eg_set_product_removed_message = $woocommerce->cart->get_cart_contents_count();
// 	}
 
// 	return $valid;
// }
// add_filter( 'woocommerce_add_to_cart_validation', 'eg_set_product_removed_message', 9, 3 );

// /**
//  * If the product removed flag is set, now we can add the message.
//  */
// function eg_show_product_removed_message( $url ) {
// 	global $woocommerce, $eg_set_product_removed_message;

// 	if ( isset( $eg_set_product_removed_message ) && is_numeric( $eg_set_product_removed_message ) ) {
// 		wc_add_notice( sprintf( _n( '%s product has been removed from your cart. Products and subscriptions can not be purchased at the same time.', '%s products have been removed from your cart. Products and subscriptions can not be purchased at the same time.', $eg_set_product_removed_message, 'wcsprm' ), $eg_set_product_removed_message ), 'error' );
// 	}
 
// 	return $url;
// }
// add_filter( 'add_to_cart_redirect', 'eg_show_product_removed_message', 11, 1 );