<?php

/**
 * Hook to add hidden product price before cart
 */
function wecreate_price_before_cart()
{
    global $product;
?>
    <input type="hidden" id="product_price_cart" name="product_price_cart" value="<?php $product->get_price(); ?>">
    <div class="before_add_to_cart_content">
        <p class="per_day_price">
            <span id="per_day_sale_price">N/A</span><span> <?php echo __('PER DAY', 'woocommerce'); ?></span>
        </p>

        <p class="total_price_before_cart">
            <span> <?php echo __('Total: ', 'woocommerce'); ?> </span> <span id="product_price_total_before_cart"><?php $product->get_price(); ?></span>
        </p>
    </div>
<?php
}
add_action('woocommerce_before_add_to_cart_button', 'wecreate_price_before_cart', 10);

/**
 * add product price to cart item
 */
function wecreate_add_product_price_to_cart_item($cart_item_data, $product_id, $variation_id)
{
    $product_price = filter_input(INPUT_POST, 'product_price_cart');
    if (empty($product_price)) {
        return $cart_item_data;
    }
    $cart_item_data['product_price_cart'] = $product_price;

    return $cart_item_data;
}
add_filter('woocommerce_add_cart_item_data', 'wecreate_add_product_price_to_cart_item', 10, 3);



// hook to change the product price
add_action('woocommerce_before_calculate_totals', 'modify_product_price', 10);

function modify_product_price($cart_object)
{
    if (is_admin() && !defined('DOING_AJAX'))
        return;
    // update the product price if only the `product_price_cart` key exists
    foreach ($cart_object->get_cart() as $hash => $value) {
        if (array_key_exists('product_price_cart', $value)) {
            $value['data']->set_price($value['product_price_cart']);
        }
    }
}

// DIET ADSSESSMENT TOOL FUNCTION
// custom ajax function to return the proposed diet
add_action('wp_ajax_nopriv_ajax_propose_meal', 'custom_propose_meal_plan');
add_action('wp_ajax_ajax_propose_meal', 'custom_propose_meal_plan');

function custom_propose_meal_plan()
{

    // standard calorie range for eatology
    $calorie_range = [
        [1200, 1500],
        [1500, 1800],
        [1800, 2200],
        [2200, 2600]
    ];

    $bmr_facor = [
        'sedentary' => [
            'weight_loss' => 0,
            'healthy_living' => 10,
            'muscle_building' => 38
        ],
        'lightly_active' => [
            'weight_loss' => 4,
            'healthy_living' => 30,
            'muscle_building' => 58
        ],
        'active' => [
            'weight_loss' => 20,
            'healthy_living' => 50,
            'muscle_building' => 78
        ],
        'gym_rat' => [
            'weight_loss' => 36,
            'healthy_living' => 70,
            'muscle_building' => 97
        ],
    ];


    $bmr_standard1 = '';
    $bmr1 = '';
    $bmr2 = '';

    $data = $_POST['vals'];
    if (!empty($data)) {
        // get the basic data
        $gender = $data['gender'];
        $height = $data['height'];
        $weight = $data['weight'];
        $age = $data['age'];
        $goal = $data['goal'];
        $activity_level = $data['activity_level'];
        $preference = $data['preference'];

        // calculate BMR
        if ($gender == 'male') {
            $bmr = 10 * $weight + 6.25 * $height - 5 * $age + 5;
        } else if ($gender == 'female') {
            $bmr = 10 * $weight + 6.25 * $height - 5 * $age - 161;
        }

        // Round to the nearest 100th -> Example 1970 calories will be 2000 calories

        $bmr1 = round($bmr);

        // $bmr1 = 1790;
        $bmr2 = round($bmr1 * ($bmr_facor[$activity_level][$goal] / 100 + 1));


        $mod = $bmr2 % 100;

        if ($mod >= 50) {
            $mod = $bmr2 % 100;
            $val = 100 - $mod;
            $bmr2 =  $bmr2 + $val;
        } else {
            $mod = $bmr2 % 100;
            $bmr2 = $bmr2 - $mod;
        }


        // $bmr2 = 3200;
        $upper = $bmr2 + 100;
        $lower = $bmr2 - 100;
        // BMR 1 needs to split into two variables lower = $bmr1 - 100 and upper = $bmr1 + 100


        // if ($lower > end($calorie_range)[1]) {
        //     $bmr_standard1 = end($calorie_range)[1];
        // } else if ($lower < $calorie_range[0][0]) {
        //     $bmr_standard1 = $calorie_range[0][0];
        // } else {
        foreach ($calorie_range as $cr) {
            $min = $cr[0];
            $max = $cr[1] - 1;
            if (in_array($lower, range($min, $max))) {
                // $html .= $bmr . "  is in range of  " . $cr[0] . ' & ' . $cr[1];
                // get the calorie after doing abs subtraction
                $val_1 = abs($lower - $cr[0]);
                $val_2 = abs($lower - $cr[1]);
                if ($val_1 < $val_2) {
                    $bmr_standard1 = $cr[0];
                } else {
                    $bmr_standard1 = $cr[1];
                }
            }
        }
        // }

        // second calculation - apply a factor to BMR
        //$bmr2 = round($bmr1 * ($bmr_facor[$activity_level][$goal] / 100 + 1));
        // if ($upper > end($calorie_range)[1]) {
        //     $bmr_standard2 = end($calorie_range)[1];
        // } else if ($upper < $calorie_range[0][0]) {
        //     $bmr_standard2 = $calorie_range[0][0];
        // } else {
        foreach ($calorie_range as $cr) {
            $min = $cr[0];
            $max = $cr[1] - 1;
            if (in_array($upper, range($min, $max))) {
                // $html .= $bmr . "  is in range of  " . $cr[0] . ' & ' . $cr[1];
                // get the calorie after doing abs subtraction
                $val_1 = abs($upper - $cr[0]);
                $val_2 = abs($upper - $cr[1]);
                if ($val_1 < $val_2) {
                    $bmr_standard2 = $cr[0];
                } else {
                    $bmr_standard2 = $cr[1];
                }
            }
        }
        // }



        $product_id_array = [];
        // suggest meal plan based on preference selected

        if ($goal == 'muscle_building') {
            $id = apply_filters('wpml_object_id', 520, 'post', true);
            array_push($product_id_array, $id);
        }


        switch ($preference) {
            case 'all_time_meat':
                $id1 = apply_filters('wpml_object_id', 256, 'post', true);
                $id2 = apply_filters('wpml_object_id', 257, 'post', true);
                $id3 = apply_filters('wpml_object_id', 246, 'post', true);
                array_push($product_id_array, $id1, $id2, $id3);
                break;
            case 'ocassional_meat':
                $id1 = apply_filters('wpml_object_id', 244, 'post', true);
                $id2 = apply_filters('wpml_object_id', 254, 'post', true);
                $id3 = apply_filters('wpml_object_id', 242, 'post', true);
                array_push($product_id_array, $id1, $id2, $id3);
                break;
            case 'never_meat':
                $id1 = apply_filters('wpml_object_id', 521, 'post', true);
                $id2 = apply_filters('wpml_object_id', 260, 'post', true);
                array_push($product_id_array, $id1, $id2);
                break;
            case 'plant_based':
                $id1 = apply_filters('wpml_object_id', 521, 'post', true);
                $id2 = apply_filters('wpml_object_id', 260, 'post', true);
                array_push($product_id_array, $id1, $id2);
                break;
            default:
                break;
        }
    } // end if(!empty($_POST))





    // get number of required calories based on comparison of bmr1, bmr2 with available product calories
    // loop through all products store the prodcut id and required caolorie(after calculation)
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    );

    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        $query = 0;
    }

    $final_bmr = [$lower, $upper];

    $calorie_recommend = [];

    if ($query) {
        $i = 0;
        while ($query->have_posts()) {
            $query->the_post();

            $product = wc_get_product();

            $en_id = get_the_ID();
            $ch_id = apply_filters('wpml_object_id', get_the_ID(), 'post', true, 'zh');

            $calories = explode(",", str_replace(' ', '', $product->get_attribute('calories')));

            // define variables to store smallest calorie and actual preselect calories
            $smallest_calorie_val = '';
            $preselect_calorie_val = '';

            foreach ($calories as $cal) {
                if (!empty($cal)) {

                    $smallest_calorie_val = abs($final_bmr[0] - $cal);
                    break;
                }
            }

            foreach ($final_bmr as $bmr) {


                foreach ($calories as $cal) {
                    if (!empty($cal)) {

                        if (abs($bmr - $cal) <= $smallest_calorie_val) {
                            $smallest_calorie_val = abs($bmr - $cal);
                            $preselect_calorie_val = $cal;
                        }
                    }
                }
            }

            // echo "<br> product name " . get_the_title();
            //  echo "<br> smallest Val " . $smallest_calorie_val;
            //  echo "<br> preselect calorie " . $preselect_calorie_val;
            //     echo "<br>";
            //     print_r($calories);

            //     echo "<br><br>";

            $calorie_recommend[$i]['en_product_id'] = $en_id;
            $calorie_recommend[$i]['ch_product_id'] = $ch_id;
            $calorie_recommend[$i]['preselect_calorie'] = $preselect_calorie_val;

            $i++;
        }
    }




    // make a query for product based on the id on array list
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 2,
        'post_status' => 'publish',
        'post__in' => $product_id_array,
    );


    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        $query = 0;
    }

    $html = '';
    $btn_text = ICL_LANGUAGE_CODE == 'en' ? 'See Plan' : '查看計劃';
    $html .= '<div class="recommended-meal-plan__wrapper">';
    if ($query) {
        while ($query->have_posts()) {
            $query->the_post();
            $html .= '<div class="recommended-meal-plan__item">
                <a href="' . get_the_permalink() . '"><img src="' . esc_url(get_the_post_thumbnail_url(get_the_ID(), 'full')) . '" alt="' . get_the_title() . '"> </a>
                <div class="recommended-meal-plan__content">';
            $terms = get_the_terms(get_the_ID(), 'product_cat');
            foreach ($terms as $term) {
                $html .= '<p>' . $term->name . '</p>';
                break;
            }
            $html .= '<h3><a href="' . get_the_permalink() . '" class="product-header-links"> ' . get_the_title() . '</a></h3>
                        <a href="' . get_the_permalink() . '" class="button-links button-links__product"> ' . $btn_text . ' </a>
                </div>
            </div>';
        }
    }

    $html .= '</div>';


    // prepare data to return
    $returnData = new stdClass();
    $returnData->bmr1 = $lower;
    $returnData->bmr2 = $upper;
    $returnData->product_html = $html;
    $returnData->calorie_recommend = $calorie_recommend;

    echo json_encode($returnData);
    die();
}

add_action('wp_footer', 'custom_js');
function custom_js()
{
    $symbol = get_woocommerce_currency_symbol();

    $single_product_page = false;
    if (is_single()) {
        $single_product_page = true;
    }

    $checkout_page = is_checkout();


    $account_page = is_account_page();

    $current_lang = ICL_LANGUAGE_CODE;

    // preselect value for state
    if ($current_lang == 'en') {
        $preselected_state = 'HONG KONG ISLAND';
    } else if ($current_lang == 'zh') {
        $preselected_state = '港島';
        $current_lang = 'ch';
    } else {
        $preselected_state = 'HONG KONG ISLAND';
    }


    $billing_state = get_user_meta(get_current_user_id(), 'billing_state', true);
    $billing_region = get_user_meta(get_current_user_id(), 'billing_region', true);
    $billing_subdistrict = get_user_meta(get_current_user_id(), 'billing_postcode', true);

    $shipping_state = get_user_meta(get_current_user_id(), 'shipping_state', true);
    $shipping_region = get_user_meta(get_current_user_id(), 'shipping_region', true);
    $shipping_subdistrict = get_user_meta(get_current_user_id(), 'shipping_postcode', true);



    $product_single = is_single();
    // get the current product id
    if ($product_single) {
        $p_id = apply_filters('wpml_object_id', get_the_ID(), 'post', true);
    }
    else {
        $p_id = '';
    }

    $readmore = __('Read More', 'eatology');
    $readless = __('Show Less', 'eatology');

    $select_subdistrict_msg = __('Please select the sub-district field to calculate the shipping fee', 'eatology');

?>

    <script>
        // script to add the filter product select option for country and region
        jQuery(document).ready(function($) {

            var billing_state = "<?php echo !empty($billing_state) ? $billing_state : "" ?>";
            var billing_region = "<?php echo !empty($billing_region) ? $billing_region : "" ?>";
            var billing_subdistrict = "<?php echo !empty($billing_subdistrict) ? $billing_subdistrict : "" ?>";


            var shipping_state = "<?php echo !empty($shipping_state) ? $shipping_state : "" ?>";
            var shipping_region = "<?php echo !empty($shipping_region) ? $shipping_region : "" ?>";
            var shipping_subdistrict = "<?php echo !empty($shipping_subdistrict) ? $shipping_subdistrict : "" ?>";

            var is_checkout = '<?php echo $checkout_page; ?>';
            var is_account_page = '<?php echo $account_page; ?>';

            if (is_checkout) {

                // jQuery('body').trigger('update_checkout');
                setTimeout(function(){
                    jQuery('#billing_region').trigger('change');
                    jQuery('#billing_postcode').trigger('change');
                }, 3000)
                // clear the custom fields if shipping checkbox is not checked; to avoid getting value from shipping fields
                if ($('#ship-to-different-address-checkbox:checked').length <= 0) {
                    jQuery('#shipping_asia_miles').val('');
                    jQuery('#shipping_first_name').val('');
                    jQuery('#shipping_last_name').val('');
                    jQuery('#shipping_floor_number').val('');
                    jQuery('#shipping_flat_number').val('');
                    jQuery('#shipping_tower_block').val('');
                    jQuery('#shipping_address_1').val('');
                    jQuery('#shipping_address_2').val('');
                }
            }

            // initialize select 2 js for sub district field
            if (is_checkout || is_account_page) {

                var json_file_name = 'hk_' + '<?php echo $current_lang; ?>' + '.json';

                $('#billing_region').select2();
                $('#shipping_region').select2();
                // allow empty value to sub district
                $('#billing_postcode').select2({
                    allowClear: true,
                    placeholder: 'Select...'
                });
                $('#shipping_postcode').select2({
                    allowClear: true,
                    placeholder: 'Select...'
                });

                // call the json file for areas and append it
                var url = '<?php echo get_stylesheet_directory_uri(); ?>/resources/json/' + json_file_name;

                let json_file;
                var xhReq = new XMLHttpRequest();
                xhReq.open("GET", url, false);
                xhReq.send(null);
                var hk_areas = JSON.parse(xhReq.responseText);


                // on change state 
                $('#billing_state').on('change', function(e) {

                    if (jQuery('#billing_state').val() == 'KOWLOON' || jQuery('#billing_state').val() == 'HONG KONG ISLAND') {
                        jQuery('form.checkout #billing_postcode_field label span.optional').html('');
                    } else {
                        jQuery('form.checkout #billing_postcode_field label span.optional').html('*');
                    }

                    $('#billing_postcode').html('<option value="">Select</option>');
                    var selected_state = $(this).val();
                    var districts = hk_areas[selected_state];
                    var district_options = '';

                    $.each(districts, function(key, val) {
                        district_options += '<option value="' + key + '">' + key + '</option>';
                    });

                    $('#billing_region').html(district_options);

                    if (billing_region) {

                        $('#billing_region').val(billing_region).trigger('change');
                    }
                    $('#billing_region').trigger('change');
                    $('#billing_postcode').trigger('change');

                    // if shipping checkbox is not checked
                    if ($('#ship-to-different-address-checkbox:checked').length <= 0) {
                        $('#shipping_state').val(selected_state).trigger('change');
                        $('#shipping_region').html(district_options);
                        if (shipping_region) {
                            $('#shipping_region').val(shipping_region).trigger('change');
                        }
                        $('#shipping_region').trigger('change');
                        $('#shipping_postcode').trigger('change');
                    }
                });


                // update checkout shipping fee on sub district select
                $('#billing_postcode').on('change', function(e) {
                    e.preventDefault();
                    var selected_postcode = $(this).val();
                    if ($('#ship-to-different-address-checkbox:checked').length <= 0) {
                        $('#shipping_postcode').val(selected_postcode).trigger('change');
                    }
                })



                // on change district
                $('#billing_region').on('change', function(e) {
                    var selected_state = $('#billing_state').val();
                    var selected_region = $(this).val();
                    if (selected_state) {
                        if (selected_region) {
                            var subdistricts = hk_areas[selected_state][selected_region];
                            var subdistrict_options = '<option value=""> Select </option>';

                            $.each(subdistricts, function(key, val) {
                                subdistrict_options += '<option value="' + key + '">' + val + '</option>';
                            });

                            $('#billing_postcode').html(subdistrict_options);
                            if (billing_subdistrict) {
                                $('#billing_postcode').val(billing_subdistrict).trigger('change');
                            }
                            $('#billing_postcode').trigger('change');
                            // if shipping checkbox is not checked
                            if ($('#ship-to-different-address-checkbox:checked').length <= 0) {
                                $('#shipping_region').val(selected_region).trigger('change'); // activate the value and trigger change
                                $('#shipping_postcode').html(subdistrict_options);
                                if (shipping_subdistrict) {
                                    $('#shipping_postcode').val(shipping_subdistrict).trigger('change');
                                }
                                $('#shipping_postcode').trigger('change');
                            }
                        }
                    }
                });


                // JS function for Billing address
                // preselect billing state, district and subdistrict
                if (billing_state) {
                    $('#billing_state').val(billing_state).trigger('change');
                } else {
                    $('#billing_state').val('<?php echo $preselected_state; ?>').trigger('change');
                }


                // on change state 
                $('#shipping_state').on('change', function(e) {
                    e.preventDefault();

                    if (jQuery('#shipping_state').val() == 'KOWLOON' || jQuery('#shipping_state').val() == 'HONG KONG ISLAND') {
                        jQuery('form.checkout #shipping_postcode_field label span.optional').html('');
                    } else {
                        jQuery('form.checkout #shipping_postcode_field label span.optional').html('*');
                    }

                    $('#shipping_postcode').html('<option value="">Select</option>');
                    var selected_state = $(this).val();
                    var districts = hk_areas[selected_state];
                    var district_options = '';


                    $.each(districts, function(key, val) {
                        district_options += '<option value="' + key + '">' + key + '</option>';
                    });

                    $('#shipping_region').html(district_options);

                    if (shipping_region) {
                        $('#shipping_region').val(shipping_region).trigger('change');
                    }
                    $('#shipping_region').trigger('change');
                    $('#shipping_postcode').trigger('change');
                });

                // on change district
                $('#shipping_region').on('change', function(e) {
                    e.preventDefault();
                    var selected_state = $('#shipping_state').val();
                    var selected_region = $(this).val();
                    if (selected_state) {
                        if (selected_region) {

                            var subdistricts = hk_areas[selected_state][selected_region];
                            var subdistrict_options = '<option value=""> Select </option>';

                            $.each(subdistricts, function(key, val) {
                                subdistrict_options += '<option value="' + key + '">' + val + '</option>';
                            });

                            $('#shipping_postcode').html(subdistrict_options);

                            if (shipping_subdistrict) {
                                $('#shipping_postcode').val(shipping_subdistrict).trigger('change');
                            }
                            $('#shipping_postcode').trigger('change');
                        }
                    }

                });


                // JS Function for Shipping Address
                // preselect shipping state, district and subdistrict
                if (shipping_state) {
                    $('#shipping_state').val(shipping_state).trigger('change');
                } else {
                    $('#shipping_state').val('<?php echo $preselected_state; ?>').trigger('change');
                }
                $('#shipping_state').trigger('change');
            }



            /**************************** SINGLE PRODUCT VARIATION AND SUBSCRIPTION - START ******************************/

            var is_single = '<?php echo $single_product_page; ?>';

            // pre select the None option on no charged restrictions section
            if (is_single) {
                var first_opt = $('.wc-pao-addon-no-charge-restrictions p').first();
                $(first_opt).find('input[type=radio]').prop('checked', true);
            }

            // set the default day and week
            var days = 5,
                weeks = 1;

            // to get currency symbol from DB
            // var symbol = '// echo $symbol; ';

            // js function for thousand separator
            function formatPrice(price, day = '', week = '') {
                var returnPrice;
                if (day != "" && week != "") {
                    var per_day_price = parseInt(price) / ((parseInt(day) * parseInt(week)));
                    returnPrice = Number(parseFloat(per_day_price).toFixed(<?php echo wc_get_price_decimals(); ?>)).toLocaleString('en', {
                        minimumFractionDigits: 2
                    });
                } else {
                    returnPrice = Number(parseFloat(price).toFixed(<?php echo wc_get_price_decimals(); ?>)).toLocaleString('en', {
                        minimumFractionDigits: 2
                    });
                }
                //unescape function decodes encoded string; i.e. $#36; => $
                // var price = unescape(symbol) + returnPrice;
                return '$' + returnPrice;
            }

            // check if a duration is a subscription or usual weeks
            function checkDuration(weeks) {
                if (weeks == 'subscription') {
                    weeks = 1;
                    return weeks;
                }

                if (weeks != null) {
                    weeks = weeks.replace(/\D/g, "");
                    return weeks;
                }
            }

            // check if default selected
            $('.tawcvs-swatches .swatch').each(function() {
                if ($(this).hasClass('selected')) {
                    var string_val = $(this).data('value');

                    if (typeof(string_val) == 'number') {
                        string_val = string_val.toString();
                    }
                    var isDay = string_val.includes("day");
                    var isWeek = string_val.includes("week");
                    var isSubscription = string_val.includes("subscription");
                    if (isDay) {
                        days = string_val.replace(/\D/g, "");
                    } else if (isWeek) {
                        weeks = string_val.replace(/\D/g, "");
                    } else if (isSubscription) {
                        weeks = 1;
                    }

                }
            })

            // inject custom price for each checked input
            jQuery('.wc-pao-addon .wc-pao-addon-checkbox').each(function() {
                // gets the price from addon checkbox and sets to local storage for later usage
                var actual_price = jQuery(this).data('price');
                localStorage.setItem('actual_restriction_price', actual_price);
                var updated_price = actual_price * days * weeks;
                jQuery(this).data('price', updated_price);
                jQuery(this).data('raw-price', updated_price);
            });

            // vairation select event.. it shows the overall price before choosing any addons
            $(".single_variation_wrap").on("show_variation", function(event, variation) {

                // set the current variation price for later use on other functions/events
                localStorage.setItem('variation_price', variation.display_price);

                var days_in_string = variation.attributes.attribute_pa_days;
                if (days_in_string != null) {
                    days = days_in_string.replace(/\D/g, "");
                }

                var weeks_in_string = variation.attributes.attribute_pa_duration;

                weeks = checkDuration(weeks_in_string);

                var restriction_price = localStorage.getItem('actual_restriction_price');

                var var_price = variation.display_price;

                var total_checked = $(this).find('.wc-pao-addon .wc-pao-addon-checkbox:checked').length;

                var total_addons_amt = total_checked * days * weeks * restriction_price;

                var total_prd_price = parseInt(var_price) + parseInt(total_addons_amt);

                // update the value of product price on hidden element
                $('input#product_price_cart').val(total_prd_price);


                $('.before_add_to_cart_content #product_price_total_before_cart').text(formatPrice(total_prd_price));

                // add the perday price
                $('.per_day_price #per_day_sale_price').text(formatPrice(total_prd_price, days, weeks))

                // when the variations are shown, trigger the checkbox click twice to check and uncheck to follow total amount flow for specially 52 weeks/subscription
                jQuery('.wc-pao-addon-charged-restrictions .wc-pao-addon-wrap label input').trigger('click').trigger('click');
                // jQuery('.wc-pao-addon-charged-restrictions .wc-pao-addon-wrap label input').trigger('click');

            });


            // weeks and days handle - nt1 - this function updates the price for each addons
            $('.tawcvs-swatches .swatch').on('click', function() {
                var attr_name = $(this).parent().data('attribute_name');
                // check if current variation selection is week then get days
                if (attr_name == 'attribute_pa_duration') {
                    var days_in_string = $('.tawcvs-swatches[data-attribute_name="attribute_pa_days"] .selected').data('value');
                    if (days_in_string != null) {
                        days = days_in_string.replace(/\D/g, "");
                    }
                    weeks = checkDuration($(this).data('value'));
                }
                // check if current variation selection is days then get weeks
                else if (attr_name == 'attribute_pa_days') {
                    var weeks_in_string = $('.tawcvs-swatches[data-attribute_name="attribute_pa_duration"] .selected').data('value');
                    weeks = checkDuration(weeks_in_string);
                    days = $(this).data('value').replace(/\D/g, "");
                }

                // inject custom value for each checked input
                jQuery('.wc-pao-addon-field.wc-pao-addon-checkbox').each(function() {
                    var actual_price = jQuery(this).attr('data-price'); //cannot find the reason why jQuery(this).data('price') has inconsistent value
                    var updated_price = actual_price * days * weeks;
                    jQuery(this).data('price', updated_price);
                    jQuery(this).data('raw-price', updated_price);
                })

            })


            // action after the restrictions addons are selected
            $('.wc-pao-addon .wc-pao-addon-checkbox').on('change', function() {

                // get the days
                var days_in_string = $('.tawcvs-swatches[data-attribute_name="attribute_pa_days"] .selected').data('value');
                if (days_in_string != null) {
                    days = days_in_string.replace(/\D/g, "");
                }

                var weeks_in_string = $('.tawcvs-swatches[data-attribute_name="attribute_pa_duration"] .selected').data('value');

                weeks = checkDuration(weeks_in_string);

                // using setTimeOut to wait for addon js to complete the task first;
                setTimeout(function() {
                    // inject custom value for each checked input
                    jQuery('.wc-pao-addon-field.wc-pao-addon-checkbox').each(function() {
                        var actual_price = jQuery(this).attr('data-price'); //cannot find the reason why jQuery(this).data('price') has inconsistent value
                        var updated_price = actual_price * days * weeks;                
                        jQuery(this).data('price', updated_price);
                        jQuery(this).data('raw-price', updated_price);
                    })

                }, 500)

                var txt = $(this).data('label');
                // get the price decimals set on woocommerce backend
                var decimals;
                if (<?php echo wc_get_price_decimals(); ?> == 2) {
                    decimals = 100;
                } else if (<?php echo wc_get_price_decimals(); ?> == 3) {
                    decimals = 1000;
                }

                setTimeout(function() {
                    // get the sub total text 
                    // e.g. $2,290.00 / week for 52 weeks and a $365.00 sign-up fee
                    var final_price = $('.wc-pao-subtotal-line > .price > .amount').text();

                    // split the text with space and get the very first text which is the total price
                    // e.g. $2,290.00
                    var sanited_value = final_price.split(/(\s+)/)[0];

                    // get the numbers only from the above value
                    // e.g. 229000
                    sanited_value = sanited_value.replace(/\D/g, "");

                    // check if any decimal; then divide the final_price by total decimal separator set on woocommerce backend @var decimals
                    var show_price, after_price;
                    if (final_price.indexOf(".") >= 1) {
                        after_price = sanited_value / decimals
                        show_price = after_price;
                    } else {
                        after_price = sanited_value
                        show_price = after_price;
                    }

                    // if($('.tawcvs-swatches[data-attribute_name="attribute_pa_duration"] .selected').data('value') == 'subscription')
                    // {
                    //     after_price = after_price * 52;
                    // }

                    // adding per day price
                    $('.per_day_price #per_day_sale_price').text(formatPrice(show_price, days, weeks))
                    // udpate the price to pass over to cart page
                    $('input#product_price_cart').val(after_price);
                    // show the final price just above the add to cart button
                    $('.before_add_to_cart_content #product_price_total_before_cart').text(final_price);

                }, 700)
            });

            /**************************** SINGLE PRODUCT VARIATION AND SUBSCRIPTION - END ******************************/





            // onload checkout page - check if region is new territory
            var selected_billing_region = $('#billing_state').val();
            if (selected_billing_region == 'NEW TERRITORIES') {
                setTimeout(function() {
                    $('.woocommerce-checkout-review-order-table #shipping_method li:first-child label').text('<?php echo $select_subdistrict_msg; ?>')
                }, 4000)
            }

            var selected_shipping_region = $('#shipping_state').val();
            if (selected_shipping_region == 'NEW TERRITORIES') {
                setTimeout(function() {
                    $('.woocommerce-checkout-review-order-table #shipping_method li:first-child label').text('<?php echo $select_subdistrict_msg; ?>')
                }, 4000)
            }

            // on billing select state, if it is new territory, replace the shipping text
            $('#billing_state').on('select2:select', function(e) {
                var selected = $(this).val();
                if (selected == 'NEW TERRITORIES') {
                    setTimeout(function() {
                        $('.woocommerce-checkout-review-order-table #shipping_method li:first-child label').text('<?php echo $select_subdistrict_msg; ?>')
                    }, 4000)
                } else if (selected == 'ISLANDS') {
                    setTimeout(function() {
                        $('.woocommerce-checkout-review-order-table td[data-title="Shipping"]').text('No Shipping')
                    }, 4000)
                }
            })


            // on shipping select state, if it is new territory, replace the shipping text
            $('#shipping_state').on('select2:select', function(e) {
                var selected = $(this).val();
                if (selected == 'NEW TERRITORIES') {
                    setTimeout(function() {
                        $('.woocommerce-checkout-review-order-table #shipping_method li:first-child label').text('<?php echo $select_subdistrict_msg; ?>')
                    }, 4000)
                } else if (selected == 'ISLANDS') {
                    setTimeout(function() {
                        $('.woocommerce-checkout-review-order-table td[data-title="Shipping"]').text('No Shipping')
                    }, 4000)
                }
            })


            // on shipping check box change funct
            $('#ship-to-different-address-checkbox').change(function() {
                if ($('#ship-to-different-address-checkbox:checked').length > 0) {
                    var selected = $('#shipping_state').val();
                    if (selected == 'ISLANDS') {
                        setTimeout(function() {
                            $('.woocommerce-checkout-review-order-table td[data-title="Shipping"]').text('No Shipping')
                        }, 4000)
                    }

                } else {
                    var selected = $('#billing_state').val();
                    if (selected == 'ISLANDS') {
                        setTimeout(function() {
                            $('.woocommerce-checkout-review-order-table td[data-title="Shipping"]').text('No Shipping')
                        }, 4000)
                    }
                }
            })


            // Add Event Listner on the Plush button 
            $('.up-sell-btn-wrapper .plus').click(function() {

                $(this).prev().val(+$(this).prev().val() + 1);
                var url = $(this).parent().parent().find('a.up-sells-add-to-cart').attr('href');
                var s = url.substring(0, url.indexOf('quantity='));
                var qntty = $(this).parent().parent().find('input.qty').val();

                $(this).parent().parent().find('a.up-sells-add-to-cart').attr('href', s + "quantity=" + qntty);

            });


            $('.up-sell-btn-wrapper .minus').click(function() {

                if ($(this).next().val() > 1) {
                    $(this).next().val(+$(this).next().val() - 1);

                    var url = $(this).parent().parent().find('a.up-sells-add-to-cart').attr('href');
                    var s = url.substring(0, url.indexOf('quantity='));
                    var qntty = $(this).parent().parent().find('input.qty').val();

                    $(this).parent().parent().find('a.up-sells-add-to-cart').attr('href', s + "quantity=" + qntty);
                }

            });

            // toggle special request
            $('.variations_form .wecreate-accordion .wecreate-accordion-header label').on('click', function() {
                $('#product-addons-total,.wc-pao-addon-container').slideToggle(500, function() {});
                $(this).find('span').toggleClass('rotated');
            })

            // toggle footer content
            $('.footer-upper-mobile .wecreate-accordion .wecreate-accordion-header label').on('click', function() {
                $(this).parent().next().slideToggle(500, function() {});
                $(this).find('span').toggleClass('rotated');
                console.log($(this).find('span'));
            })


            /*
             *
             ***********************************  DIET ASSESSMENT TOOL START *******************************
             *
             */

            // $('.diet_assessment-form .tab-header a').on('click',function(e){
            //     e.preventDefault();
            //     var tab_id = $(this).data('ids');
            //     $('.diet_assessment-form .tabcontent').hide();
            //     $('#'+tab_id).fadeIn();
            // })

            $('.radio-group .radio').click(function() {
                $(this).parent().find('.radio').removeClass('selected');
                $(this).addClass('selected');
            });

            // on step 1 > click next || click on step 2 tab header
            $('#step1 .third_section button').on('click', function() {

                var step = $(this).data('id');
                var image = $(this).data('image');
                var gender = $('.male_female_wrapper .radio-group .selected').data('value');
                var height = $('#height_selector').val();
                var weight = $('#weight_selector').val();
                var age = $('#age_selector').val();

                // trigger step 2 tab header click
                // $('.diet_assessment-form .tab-header a[data-ids=step2]').trigger('click');

                // assign the active class
                if (gender && height && weight && age) {
                    $('.diet_assessment-form  .tab-header a').removeClass('active');
                    $('.diet_assessment-form .tab-header a[data-ids=' + step + ']').addClass('active');
                    // hide all image and show next/prev image
                    $('.diet_assessment-image').hide();
                    $('#' + image).fadeIn();

                    $('html,body').animate({
                        scrollTop: 0
                    }, 'slow');

                    // hide all the tabContent and show only step 2 content
                    $('.tabcontent').hide();
                    $('#' + step).fadeIn();

                } else {
                    alert('Please select personal info field');
                }
            })

            // on step 2 > click next || click on step 3 tab header
            $('#step2 .second_section button').on('click', function() {


                var step = $(this).data('id');
                var image = $(this).data('image');

                var activity_level = $('.activity_level_wrapper .radio-group .selected').data('value');

                // trigger step 3 tab header click
                // $('.diet_assessment-form .tab-header a[data-ids=step3]').trigger('click');

                if (activity_level) {
                    // assign the active class
                    $('.diet_assessment-form  .tab-header a').removeClass('active');
                    $('.diet_assessment-form .tab-header a[data-ids=' + step + ']').addClass('active');

                    // hide all image and show next/prev image
                    $('.diet_assessment-image').hide();
                    $('#' + image).fadeIn();

                    $('html,body').animate({
                        scrollTop: 0
                    }, 'slow');

                    // hide all the tabContent and show only step 2 content
                    $('.tabcontent').hide();
                    $('#' + step).fadeIn();

                } else {
                    alert('Please select activity level');
                }
            })

            // on step 3 > click next || click on step 4 tab header
            $('#step3 .second_section button').on('click', function() {

                var step = $(this).data('id');
                var image = $(this).data('image');

                var goal = $('.goal_wrapper .radio-group .selected').data('value');

                // trigger step 4 tab header click
                // $('.diet_assessment-form .tab-header a[data-ids=step4]').trigger('click');

                if (goal) {
                    // assign the active class
                    $('.diet_assessment-form  .tab-header a').removeClass('active');
                    $('.diet_assessment-form .tab-header a[data-ids=' + step + ']').addClass('active');

                    // hide all image and show next/prev image
                    $('.diet_assessment-image').hide();
                    $('#' + image).fadeIn();

                    $('html,body').animate({
                        scrollTop: 0
                    }, 'slow');

                    // hide all the tabContent and show only step 2 content
                    $('.tabcontent').hide();
                    $('#' + step).fadeIn();

                } else {
                    alert('Please select your goal');
                }

            })

            // on step 4 > click next || click on result tab header
            $('#step4 .second_section button').on('click', function() {

                var step = $(this).data('id');
                var image = $(this).data('image');

                var preference = $('.preference_wrapper .radio-group .selected').data('value');

                // trigger step 4 tab header click
                // $('.diet_assessment-form .tab-header a[data-ids=step4]').trigger('click');

                if (preference) {
                    // assign the active class
                    $('.diet_assessment-form  .tab-header a').removeClass('active');
                    $('.diet_assessment-form .tab-header a[data-ids=' + step + ']').addClass('active');

                    // hide all image and show next/prev image
                    $('.diet_assessment-image').hide();
                    $('#' + image).fadeIn();

                    $('html,body').animate({
                        scrollTop: 0
                    }, 'slow');

                    // hide all the tabContent and show only step 2 content
                    $('.tabcontent').hide();
                    $('#' + step).fadeIn();

                } else {
                    alert('Please select your preference')
                }

                if (step == 'submit') {

                    // hide all image and show next/prev image
                    $('.diet_assessment-image').hide();
                    $('#' + image).fadeIn();

                    var gender = $('.male_female_wrapper .radio-group .selected').data('value');
                    var height = $('#height_selector').val();
                    var weight = $('#weight_selector').val();
                    var age = $('#age_selector').val();
                    var activity_level = $('.activity_level_wrapper .radio-group .selected').data('value');
                    var goal = $('.goal_wrapper .radio-group .selected').data('value');
                    var preference = $('.preference_wrapper .radio-group .selected').data('value');

                    if (gender && height && weight && age && activity_level && goal && preference) {
                        var form_vals = {
                            'gender': gender,
                            'height': height,
                            'weight': weight,
                            'age': age,
                            'activity_level': activity_level,
                            'goal': goal,
                            'preference': preference
                        }

                        jQuery('.eat_loading').show();
                        jQuery.ajax({
                            url: '<?php echo admin_url('admin-ajax.php'); ?>',
                            type: 'post',
                            data: {
                                action: 'ajax_propose_meal',
                                vals: form_vals
                            },
                            beforeSend: function() {

                                $('.diet_assessment-form .form_wrapper').hide();
                                $('.diet_assessment-form .result_wrapper').fadeIn();
                            },
                            success: function(res) {

                                jQuery('.eat_loading').hide();

                                $('.result_content').fadeIn();
                                $('.meet_dietician_wrapper').fadeIn();

                                var resp = JSON.parse(res);
                                var products_html = resp['product_html'];
                                var calorie_recommend = resp['calorie_recommend'];
                                var bmr1 = resp['bmr1'];
                                var bmr2 = resp['bmr2'];

                                localStorage.setItem('calories', JSON.stringify(calorie_recommend));

                                // store the user data to localstorage
                                var user_data = {
                                    'gender': gender,
                                    'height': height,
                                    'weight': weight,
                                    'age': age,
                                    'activity_level': activity_level,
                                    'goal': goal,
                                    'preference': preference,
                                    'bmr1': bmr1,
                                    'bmr2': bmr2,
                                };

                                localStorage.setItem('user_data', JSON.stringify(user_data));

                                var text_inside_progress_bar = bmr1 + ' to ' + bmr2;
                                var text_inside_progress_bar_kcal = "kcal";

                                var progress_val = parseInt(bmr1) / parseInt(bmr2);
                                // round to two nearest decimal
                                progress_val = Math.round((progress_val + Number.EPSILON) * 100) / 100

                                // initiate circular progress
                                $('.circle').circleProgress({
                                    startAngle: -1.55,
                                    size: 150,
                                    value: progress_val,
                                    thickness: 8,
                                    fill: {
                                        color: '#4A0D66'
                                    }
                                });

                                $('.result_body #calorie_val').text(text_inside_progress_bar);
                                $('.result_body #kcal_text').text(text_inside_progress_bar_kcal);

                                $('#diet_assessment-page .diet_assessment-form .result_wrapper #recommended_products').html(products_html);
                            }
                        });
                    } else {
                        alert('Please make sure all steps are filled');
                    }
                }
            })

            /*
             *
             ***********************************  DIET ASSESSMENT TOOL END *******************************
             *
             */


            // toogle coupon on checkout
            $('.dropdown').on('click', function() {
                jQuery(this).toggleClass('rotated');
                jQuery('.showcoupon').trigger('click');
            })
            // toogle login form on checkout
            $('.dropdownlogin').on('click', function() {
                jQuery(this).toggleClass('rotated');
                jQuery('.showlogin').trigger('click');
            })


            // read more 
            var minimized_elements = $('.single-product div.woocommerce-product-details__short-description');

            minimized_elements.each(function() {
                var t = $(this).text();
                if (t.length < 500) return;

                $(this).append('<p><a href="#" class="read_less" style="color:#4A0D66;"><?php echo $readless; ?></a></p>')
                $(this).wrapInner('<div class="original"></div>');

                $(this).append('<div class="intro"><p>' +
                    t.slice(0, 500) +
                    '<span>... </span><a href="#" class="read_more" style="color:#4A0D66;"><?php echo $readmore; ?></a></p></div>'
                );

                $(this).find('.original').hide();
            });

            $('a.read_more', minimized_elements).click(function(event) {
                event.preventDefault();
                $(this).closest('.intro').hide().prev('.original').show();
            });

            $('a.read_less', minimized_elements).click(function(event) {
                event.preventDefault();
                $(this).closest('.original').hide().next('.intro').show();

            });



            // replace the per week shipping price with the correct value
            // jQuery( document.body ).on( 'updated_checkout', function(){
            //     var per_week_price = jQuery('table.woocommerce-checkout-review-order-table tr.woocommerce-shipping-totals #shipping_method .woocommerce-Price-amount').html();
            //   setTimeout(function(){
            //     jQuery('table.woocommerce-checkout-review-order-table tr.shipping.recurring-total span.woocommerce-Price-amount').html(per_week_price);
            //   }, 2000)
            // });

            // // // replace the per week shipping price with the correct value on cart page
            // var per_week_price_cart = jQuery('.cart-collaterals .shop_table__order-summary .woocommerce-shipping-totals .woocommerce-shipping-methods >li .woocommerce-Price-amount').html();
            //   setTimeout(function(){
            //     jQuery('.cart-collaterals .shop_table__order-summary  tr.shipping.recurring-total span.woocommerce-Price-amount').html(per_week_price_cart);
            //   }, 2000)

        });


        jQuery(window).load(function() {

            var is_single = '<?php echo $product_single; ?>';
            var p_id = '<?php echo $p_id; ?>';

            // preselect the calorie value in prduct detail page
            if (localStorage.getItem("calories")) {
                var calorieObject = localStorage.getItem('calories');

                var parsedCalories = JSON.parse(calorieObject);

                for (let i = 0; i < parsedCalories.length; i++) {
                    if (parsedCalories[i]['en_product_id'] == p_id || parsedCalories[i]['ch_product_id'] == p_id) {
                        if (!jQuery('form.variations_form table.variations span[data-value=' + parsedCalories[i]['preselect_calorie'] + ']').hasClass('selected')) {
                            jQuery('form.variations_form table.variations span[data-value=' + parsedCalories[i]['preselect_calorie'] + ']').trigger('click');
                            break;
                        }
                    }
                }
            }


            if (jQuery('#billing_state').val() == 'KOWLOON' || jQuery('#billing_state').val() == 'HONG KONG ISLAND') {
                jQuery('form.checkout #billing_postcode_field label span.optional').html('');
            } else {
                jQuery('form.checkout #billing_postcode_field label span.optional').html('*');
            }

            if (jQuery('#shipping_state').val() == 'KOWLOON' || jQuery('#shipping_state').val() == 'HONG KONG ISLAND') {
                jQuery('form.checkout #shipping_postcode_field label span.optional').html('');
            } else {
                jQuery('form.checkout #shipping_postcode_field label span.optional').html('*');
            }
        })
    </script>

<?php
}
