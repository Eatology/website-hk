<?php
    /**
     * Admin - Order Subscription Edit page
     * Modify the line item shipping fee based on pa_days_variation
     */
    if(!function_exists('modify_subscription_items_shipping_fee'))
    {
        function modify_subscription_items_shipping_fee( $item, $package_key, $package, $subscription )
        {
            // // checking the $item data
            // $myfile = fopen("item.txt", "w") or die("Unable to open file!");
            // fwrite($myfile, $item['total']);
            // fclose($myfile);

            // // checking the $package_key data
            // $myfile3 = fopen("packagekey.txt", "w") or die("Unable to open file!");
            // fwrite($myfile3, $package_key);
            // fclose($myfile3);

            // // checking the $package data
            // $myfile4 = fopen("package.txt", "w") or die("Unable to open file!");
            // fwrite($myfile4, $package);
            // fclose($myfile4);

            //  // checking the $subscription data
            // $myfile2 = fopen("subscription.txt", "w") or die("Unable to open file!");
            // fwrite($myfile2, $subscription);
            // fclose($myfile2);
        
            // convert 100.00 to 100 ---> float to int
            // $old_total =round( $item['total'] );

            // get number of days variation value
            $days = 0;
            $decoded_subs = json_decode($subscription);
            $line_items = $decoded_subs->line_items;
            foreach($line_items as $litem)
            {
                $days = $litem->legacy_values->variation->attribute_pa_days;
            }

            // set the shipping total as Prev Shipping Total x No. of days
            $item->set_props( array(
                'total'        => wc_format_decimal( $item['total'] * intval($days) ),
            ) );
            
            $subscription->add_item( $item );

            $item->save();
        }
    }
    add_action('woocommerce_checkout_create_subscription_shipping_item', 'modify_subscription_items_shipping_fee', 10, 4);


    /**
     * Admin - Order Subscription Edit page
     * Modify the subscription total shipping fee based on pa_days_variation
     */
    if(!function_exists('modify_subscription_on_checkout'))
    {
        function modify_subscription_on_checkout( $subscription, $posted_data, $order, $cart )
        {
            //  // checking the $subscription data
            // $myfile1 = fopen("subscription_main.txt", "w") or die("Unable to open file!");
            // fwrite($myfile1, $subscription);
            // fclose($myfile1);

            //   // checking the $posted_data data
            // $myfile3 = fopen("posted_data.txt", "w") or die("Unable to open file!");
            // fwrite($myfile3, $posted_data);
            // fclose($myfile3);

            // // checking the $order data
            // $myfile4 = fopen("order.txt", "w") or die("Unable to open file!");
            // fwrite($myfile4, $order);
            // fclose($myfile4);

            //  // checking the $cart data
            // $myfile2 = fopen("cart.txt", "w") or die("Unable to open file!");
            // fwrite($myfile2, $cart->shipping_total);
            // fclose($myfile2);

            // get number of days variation value
            $days = 0;
            $decoded_subs = json_decode($subscription);
            $line_items = $decoded_subs->line_items;
            foreach($line_items as $litem)
            {
                $days = $litem->legacy_values->variation->attribute_pa_days;
            }

            // set the shipping total as Prev Shipping Total X No. of days
            $total_shipping_fee = $cart->shipping_total * $days;

            $subscription->set_shipping_total($total_shipping_fee);

            // The Previous cart total amount includes the old shipping fee, so we deduct it and add new shipping total fee
            // Subscription Total = Prev Cart Total - Prev Shipping Total + New Total Shipping Fee
            $subscription->set_total( $cart->total - $cart->shipping_total + $total_shipping_fee);

        }
    }
    add_action('woocommerce_checkout_create_subscription', 'modify_subscription_on_checkout', 10, 4);


/**
 * Funtion to get the total holidays count in a date range
 * @param string $start_date // date string
 * @param string $end_date // date string
 * @return int $holiday_count // total holidays on that date range
 */
if(!function_exists('get_holiday_count_in_date_range'))
{
    function get_holiday_count_in_date_range($start_date, $end_date)
    {
        // convert to datetime format for using DatePeriod
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);

        $end->setTime(0, 0, 1); // // adding it so that the end date is inclusive
        $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);

        // define empty array to hold dates as string within the date range
        // it will be later used to check if holiday is in this array list
        $renewal_order_all_dates = [];

        foreach ($daterange as $date) {
            array_push($renewal_order_all_dates, $date->format('Y-m-d'));
        }

        // $holidays hold the holiday values set on backend plugin settings
        $holidays = get_option('jckwds_settings')['holidays_holidays_holidays'];

        // define $holiday_count variable
        $holiday_count = 0;

        // loop through each $holidays
        foreach ($holidays as $holiday)
        {
            // holiday start date
            $sdate = $holiday['date'];
            // holiday end date
            $edate = empty($holiday['date_to']) ? $holiday['date'] : $holiday['date_to'];

            $sdate = str_replace('/', '-', $sdate);
            $formated_sdate = date('Y-m-d', strtotime($sdate));

            $edate = str_replace('/', '-', $edate);
            $formated_edate = date('Y-m-d', strtotime($edate));

            // if start date and end date are same, then directly check if it is in the $renewal_order_all_dates array
            // else loop through holiday start to holiday end date and loop through the days
            if($formated_sdate === $formated_edate)
            {
                if(in_array($formated_sdate, $renewal_order_all_dates))
                {
                    $holiday_count++;
                }
            }
            else {
                $h_s_date = new DateTime($formated_sdate);
                $h_e_date = new DateTime($formated_edate);

                $h_e_date->setTime(0, 0, 1); // adding it so that the end date is inclusive
                $holiday_date_range = new DatePeriod($h_s_date, new DateInterval('P1D'), $h_e_date);
                
                // loop through holiday start and end
                foreach ($holiday_date_range as $h_date) {
                    if(in_array($h_date->format('Y-m-d'), $renewal_order_all_dates))
                    {
                        $holiday_count++;
                    }
                }
            }
        }
        // return no. of holidays
        return $holiday_count;
    }
}

    // $renewal_create_date = date_create('2020-11-28');
    // $formatted_renewal_start_date = date_format($renewal_create_date, 'Y-m-d');
    // $formatted_renewal_end_date = date_add($renewal_create_date,date_interval_create_from_date_string("5 days"));
    // echo "Days " . get_holiday_count_in_date_range($formatted_renewal_start_date, date_format($formatted_renewal_end_date, "Y-m-d"));
    // echo get_holiday_count_in_date_range('2020-11-22', '2020-11-30' );

	/**
     * Subscription Renewal - Exclude total holidays amount in upcoming renewal order
     * 1. get the no. of selected days variation using custom WP query (5 or 6)
     * 2. get the renewal order created date i.e. start_date
     * 3. add no. of days to renewal order created date (say if renewal order created date is 2020-11-22 and days is 6, the renewal order end date is 2020-11-27)  i.e. end_date
     * 4. call get_holiday_count_in_date_range function to get no of holidays within that date range
     * 5. if no of holidays is > 0, then modify the amount based on no of holidays
     * 6. also, add the order item meta, as `Holiday x $days`
     * 7. save the renewal order and return 
     */
    if(!function_exists('modify_subscriptions_cart_total_excluding_holidays'))
    {
        function modify_subscriptions_cart_total_excluding_holidays($renewal_order, $subscription)
        {
            global $wpdb;
            $table_pre = $wpdb->prefix;

            $woocommerce_order_items_table = $table_pre . 'woocommerce_order_items';
            $woocommerce_order_itemmeta_table = $table_pre . 'woocommerce_order_itemmeta';
            $post_meta = $table_pre . 'postmeta';

            // get number of days variation value
            $days = 0;
            $decoded_subs = json_decode($subscription);

            $parent_id = $decoded_subs->parent_id;

            // getting the days variation from parent order from custom query as the $subscription did not have the variation value
            $order_item = $wpdb->get_row("SELECT `order_item_id` FROM $woocommerce_order_items_table WHERE `order_id`= $parent_id");
            $order_item_meta = $wpdb->get_row("SELECT `meta_value` FROM $woocommerce_order_itemmeta_table where `order_item_id` = $order_item->order_item_id and `meta_key` LIKE 'pa_days'");

            // no of days the plan was selected
            $days = intval($order_item_meta->meta_value);
            
            // prepare data for queryig holiday counts in the date range
            $decoded_renewal_order_obj = json_decode($renewal_order);

            $renewal_order_created_date = $decoded_renewal_order_obj->date_created->date;
            $renewal_create_date = date_create($renewal_order_created_date);
            $formatted_renewal_start_date = date_format($renewal_create_date, 'Y-m-d');
            $formatted_renewal_end_date = date_add($renewal_create_date,date_interval_create_from_date_string($days . " days"));
            // get the no of holidays
            $no_of_holidays = get_holiday_count_in_date_range($formatted_renewal_start_date, date_format($formatted_renewal_end_date, "Y-m-d"));

            $shipping_total = $decoded_renewal_order_obj->shipping_total;

            // if only holidays exists... then deduct holiday amount, else return current renewal order object
            if($no_of_holidays > 0)
            {

                $newPosOrderId = $renewal_order->get_id();
                
                // get the current total amount
                $total_amount = $renewal_order->get_total();

                // get one day amount
                // $total amount is the total renewal order amount
                // $days is the total no of days the plan was selected.. 5 or 6
                $oneday_amount = $total_amount / $days;

                $oneday_shipping = $shipping_total / $days;

                // set the total holiay discount amount
                // $no_of_holidays is the total holidays returned by a function... that requires the $renewal_order_created_date and the ($days + $renewal_create_date)
                $discount_amount = $oneday_amount * $no_of_holidays;
                $reduced_shipping_fee = $oneday_shipping * ($days - $no_of_holidays);

                // Custom WP query to get the renewal Order ID
                // $renewalOrder = $wpdb->get_row("SELECT `meta_value` FROM $post_meta where `post_id` = $newPosOrderId and `meta_key` LIKE '_subscription_renewal'");
                // $renewalOrderId = $renewalOrder->meta_value;

                // adding extra Line Order Holiday Item
                $item_id = wc_add_order_item($newPosOrderId, array('order_item_name'	=>	__('Holiday', 'eatology') . ' x ' . $no_of_holidays , 'order_item_type'	=>	'fee'));
                if ($item_id) {
                    wc_add_order_item_meta($item_id, '_line_total', '-'.$discount_amount);
                    wc_add_order_item_meta($item_id, '_line_tax', 0);
                    wc_add_order_item_meta($item_id, '_line_subtotal', '-'.$discount_amount);
                    wc_add_order_item_meta($item_id, '_line_subtotal_tax', 0);
                }


                // get the newly created renewal order -> order_item_id
                $order_item = $wpdb->get_row("SELECT `order_item_id` FROM $woocommerce_order_items_table WHERE `order_id`= $newPosOrderId and `order_item_name` LIKE 'Flat rate' and `order_item_type` like 'shipping'");
                
                // update the shipping cost meta with updated shipping cost
                $wpdb->update($woocommerce_order_itemmeta_table, array('meta_value'=>$reduced_shipping_fee,), array('order_item_id'=>$order_item->order_item_id, 'meta_key'=> 'cost'));

                // update total shipping fee 
                $renewal_order->set_shipping_total( $reduced_shipping_fee );
                
                // update the total amount
                $renewal_order->set_total( $total_amount - $discount_amount );

                $renewal_order->save();
            }

			return $renewal_order;
        }
    }
    add_filter('wcs_renewal_order_created', 'modify_subscriptions_cart_total_excluding_holidays', 10, 2);


    // filter to modify first renewal date
    if(!function_exists('modify_first_renewal_date'))
    {
        function modify_first_renewal_date($first_renewal_timestamp, $product_id, $from_date_param, $timezone)
        {
            $earliestDate = getEarliestAvailableDates();
            $earliestDate = new DateTime($earliestDate);
            // get the next monday from earliest available date
            // and convert date object to array
            $nextMon = (array)$earliestDate->modify('next monday');

            // $nextMon returns DateTime object as
            /* 
                    [date] => 2021-01-04 00:00:00.000000
                    [timezone_type] => 3
                    [timezone] => UTC

                */

            $first_renewal_timestamp = strtotime($nextMon['date']);
            return $first_renewal_timestamp;
        }
    }
    add_filter('woocommerce_subscriptions_product_first_renewal_payment_time', 'modify_first_renewal_date', 40, 4);