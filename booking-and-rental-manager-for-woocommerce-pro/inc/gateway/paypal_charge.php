<?php
if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

use Omnipay\Omnipay;

add_action('init','rbfw_paypal_init');

function rbfw_paypal_init(){
    $rbfw_payment_settings = get_option("rbfw_basic_payment_settings");
    $client_id = !empty($rbfw_payment_settings['rbfw_mps_paypal_client_id']) ? $rbfw_payment_settings['rbfw_mps_paypal_client_id'] : '';
    $secret_key = !empty($rbfw_payment_settings['rbfw_mps_paypal_secret_key']) ? $rbfw_payment_settings['rbfw_mps_paypal_secret_key'] : '';

    define('CLIENT_ID', $client_id);
    define('CLIENT_SECRET', $secret_key);
}

// Paypal Charge Function
add_action('wp_loaded','rbfw_paypal_function');

function rbfw_paypal_function(){

    $rbfw_payment_settings = get_option("rbfw_basic_payment_settings");
    $rbfw_gen_settings = get_option("rbfw_basic_gen_settings");
    $client_id = !empty($rbfw_payment_settings['rbfw_mps_paypal_client_id']) ? $rbfw_payment_settings['rbfw_mps_paypal_client_id'] : '';
    $secret_key = !empty($rbfw_payment_settings['rbfw_mps_paypal_secret_key']) ? $rbfw_payment_settings['rbfw_mps_paypal_secret_key'] : '';
    $currency = !empty($rbfw_payment_settings['rbfw_mps_currency']) ? $rbfw_payment_settings['rbfw_mps_currency'] : '';
    $t_page_id = !empty($rbfw_gen_settings['rbfw_thankyou_page']) ? $rbfw_gen_settings['rbfw_thankyou_page'] : '';
    $t_page_url = !empty($t_page_id) ? get_page_link($t_page_id) : '';
    $gateway_environment = !empty($rbfw_payment_settings['rbfw_mps_payment_gateway_environment']) ? $rbfw_payment_settings['rbfw_mps_payment_gateway_environment'] : '';

    $current_obj_id = !empty($_POST['rbfw_mps_post_id']) ? $_POST['rbfw_mps_post_id'] : '';

    if(empty($client_id) || empty($secret_key) || empty($currency)){
        return;
    }

    if(empty($current_obj_id)){
        return;
    }

    $current_obj_url = get_permalink($current_obj_id);

    define('PAYPAL_RETURN_URL', $t_page_url);
    define('PAYPAL_CANCEL_URL', $current_obj_url);
    define('PAYPAL_CURRENCY', $currency); // set your currency here

    if($gateway_environment == 'sandbox'){
        
        $gateway_environment = true;

    }elseif($gateway_environment == 'live'){
        
        $gateway_environment = false;

    }else{
        $gateway_environment = true;
    }

    if (isset($_POST['rbfw_mps_checkout'])) {
    global $rbfw;    
    $duration_cost = 0;
    $service_cost = 0;
    $ticket_total_price = 0;
    $MpsEmailClass = new Rbfw_Mps_Email();
    $post_id = $_POST['rbfw_mps_post_id'];
    $rent_type = $_POST['rbfw_rent_type'];
    $first_name = $_POST['rbfw_mps_user_fname'];
    $last_name = $_POST['rbfw_mps_user_lname'];
    $email = $_POST['rbfw_mps_user_email'];
    $payment_method = $_POST['rbfw_mps_payment_method'];
    $item_title = get_the_title($post_id); 
    $g_services = get_post_meta($post_id,'rbfw_extra_service_data',true);
    $g_services = !empty($g_services) ? array_column($g_services, 'service_price', 'service_name') : [];
    $ticket_info = []; 
    $checkout_account = $rbfw->get_option('rbfw_mps_checkout_account', 'rbfw_basic_payment_settings','on');
    $package = '';
    $type_info_merged = [];
    $service_info_merged = [];
    $variation_info = [];


    /* Start Discount Calculations */
    $discount_type = '';
    $discount_amount = '';
    /* End Discount Calculations */

    /* Start: Get Registration Form Info */
    $rbfw_regf_info = [];

    if(class_exists('Rbfw_Reg_Form')){
        $ClassRegForm = new Rbfw_Reg_Form();
        $rbfw_regf_info = $ClassRegForm->rbfw_regf_value_array_function($post_id);
    }
    /* End: Get Registration Form Info */

    if($rent_type == 'bike_car_sd' || $rent_type == 'appointment'){

        $rbfw_bikecarsd = new RBFW_BikeCarSd_Function();
        $start_date = !empty($_POST['rbfw_bikecarsd_selected_date']) ? strip_tags($_POST['rbfw_bikecarsd_selected_date']) : '';
        $start_time = !empty($_POST['rbfw_bikecarsd_selected_time']) ? strip_tags($_POST['rbfw_bikecarsd_selected_time']) : '';
        $type_info = !empty($_POST['rbfw_bikecarsd_info']) ? $_POST['rbfw_bikecarsd_info'] : [];
        $type_info_merged = array_column($type_info,'qty','rent_type');
        $service_info = !empty($_POST['rbfw_service_info']) ? $_POST['rbfw_service_info'] : [];
        $service_info_merged = array_column($service_info,'service_qty','service_name'); 
        $type_info = $rbfw_bikecarsd->rbfw_get_bikecarsd_rent_array_reorder($post_id,$type_info);
        $service_info = $rbfw_bikecarsd->rbfw_get_bikecarsd_service_array_reorder($post_id,$service_info);
        $g_rent_types = get_post_meta($post_id,'rbfw_bike_car_sd_data',true);
        $g_rent_types = array_column($g_rent_types, 'price', 'rent_type');
        $ticket_info = $rbfw_bikecarsd->rbfw_bikecarsd_ticket_info($post_id,$start_date,$end_date,$type_info_merged,$service_info_merged,$start_time, $rbfw_regf_info);
        $type_info_merged = $type_info;
        $service_info_merged = $service_info;
        
        if(!empty($type_info)){
            foreach ($type_info as $type_arr) {
                foreach ($type_arr as $type_name => $type_qty) {
                    if($type_qty > 0){
                        foreach ($g_rent_types as $g_rent_type => $g_rent_type_price) {
                            if($type_name == $g_rent_type){
                                    $price = (float)$g_rent_type_price * (float)$type_qty;
                                    $duration_cost += $price;
                            }
                        }
                    }
                    
                }
            }
        }
        
        if(!empty($service_info)){
            foreach ($service_info as $service_arr) {
                foreach ($service_arr as $service_name => $service_qty) {
                    if($service_qty > 0){
                        foreach ($g_services as $g_service_name => $g_service_price) {
                            if($service_name == $g_service_name){
                                $price = (float)$g_service_price * (float)$service_qty;
                                $service_cost += $price;
                            }
                        }
                    }
                }
            }
        }

        $ticket_total_price = $duration_cost + $service_cost; 



    }
    elseif($rent_type == 'resort'){
        $resortClass = new RBFW_Resort_Function();
        $start_date = !empty($_POST['rbfw_start_datetime']) ? strip_tags($_POST['rbfw_start_datetime']) : '';
        $end_date = !empty($_POST['rbfw_end_datetime']) ? strip_tags($_POST['rbfw_end_datetime']) : '';
        $package = !empty($_POST['rbfw_room_price_category']) ? strip_tags($_POST['rbfw_room_price_category']) : '';
        $type_info = !empty($_POST['rbfw_room_info']) ? $_POST['rbfw_room_info'] : [];
        $type_info_merged = array_column($type_info,'room_qty','room_type');
        $service_info = !empty($_POST['rbfw_service_info']) ? $_POST['rbfw_service_info'] : [];
        $service_info_merged = array_column($service_info,'service_qty','service_name');
        $ticket_total_price = $resortClass->rbfw_resort_price_calculation($post_id,$start_date,$end_date,$package,$type_info_merged,$service_info_merged,'rbfw_room_total_price');
        $type_info = $resortClass->rbfw_get_resort_room_array_reorder($post_id,$type_info);
        $service_info = $resortClass->rbfw_get_resort_service_array_reorder($post_id,$service_info);       
        $ticket_info = $resortClass->rbfw_resort_ticket_info($post_id,$start_date,$end_date,$package,$type_info_merged,$service_info_merged,$rbfw_regf_info);
        $type_info_merged = $type_info;
        $service_info_merged = $service_info;

        

        if(function_exists('rbfw_get_discount_array')){

            $discount_arr = rbfw_get_discount_array($post_id, $start_date, $end_date, $ticket_total_price);
    
        } else {
    
            $discount_arr = [];
        } 

        if(!empty($discount_arr)){
            $ticket_total_price = $discount_arr['total_amount'];
            $discount_type = $discount_arr['discount_type'];
            $discount_amount = $discount_arr['discount_amount'];
        }
        
    }
    elseif(($rent_type == 'bike_car_md') || ($rent_type == 'dress') || ($rent_type == 'equipment') || ($rent_type == 'others')){
        $BikeCarMdClass = new RBFW_BikeCarMd_Function();
        $start_date = !empty($_POST['rbfw_pickup_start_date']) ? strip_tags($_POST['rbfw_pickup_start_date']) : '';
        $start_time = !empty($_POST['rbfw_pickup_start_time']) ? strip_tags($_POST['rbfw_pickup_start_time']) : '';
        $end_date = !empty($_POST['rbfw_pickup_end_date']) ? strip_tags($_POST['rbfw_pickup_end_date']) : '';
        $end_time = !empty($_POST['rbfw_pickup_end_time']) ? strip_tags($_POST['rbfw_pickup_end_time']) : '';
        $start_datetime = $start_date.' '.$start_time;
        $end_datetime = $end_date.' '.$end_time;

        $service_info = !empty($_POST['rbfw_service_info']) ? $_POST['rbfw_service_info'] : [];
        $service_info_merged = array_column($service_info,'service_qty','service_name');
        $service_info = $BikeCarMdClass->rbfw_get_bikecarmd_service_array_reorder($post_id,$service_info); 
        $service_info_merged = array_reduce($service_info, 'array_merge', array());

        $pickup_point = !empty($_POST['rbfw_pickup_point']) ? strip_tags($_POST['rbfw_pickup_point']) : '';
        $dropoff_point = !empty($_POST['rbfw_dropoff_point']) ? strip_tags($_POST['rbfw_dropoff_point']) : '';

        $item_quantity = !empty($_POST['rbfw_item_quantity']) ? strip_tags($_POST['rbfw_item_quantity']) : 1;
        $duration_cost = rbfw_price_calculation( $post_id, $start_datetime, $end_datetime, $start_date );
        $duration_cost = $duration_cost * (float)$item_quantity;

        $variation_data = get_post_meta($post_id,'rbfw_variations_data',true);
        $rbfw_enable_extra_service_qty = get_post_meta( $post_id, 'rbfw_enable_extra_service_qty', true ) ? get_post_meta( $post_id, 'rbfw_enable_extra_service_qty', true ) : 'no';
        
        if(!empty($variation_data)){
            $i = 0;
            foreach ($variation_data as $level_one_arr) {

                $selected_field_value = !empty($_POST[$level_one_arr['field_id']]) ? $_POST[$level_one_arr['field_id']] : [];

                $level_two_arr = $level_one_arr['value'];

                foreach ($level_two_arr as $level_two_arr_value) {
                    if($selected_field_value == $level_two_arr_value['name']){

                        $field_label = $level_one_arr['field_label'];
                        $field_id = $level_one_arr['field_id'];

                        $variation_info[$i]['field_id'] = $field_id;
                        $variation_info[$i]['field_label'] = $field_label;
                        $variation_info[$i]['field_value'] = $selected_field_value;
                    }
                }
                
                $i++;
            }
        }

        if(!empty($service_info)){
            foreach ($service_info as $service_arr) {
                foreach ($service_arr as $service_name => $service_qty) {

                    if($item_quantity > 1 && $service_qty == 1 && $rbfw_enable_extra_service_qty != 'yes'){
                        $service_qty = $item_quantity;
                    }

                    if($service_qty > 0){
                        foreach ($g_services as $g_service_name => $g_service_price) {
                            if($service_name == $g_service_name){
                                $price = (float)$g_service_price * (float)$service_qty;
                                $service_cost += $price;
                                $ticket_total_price += $price;
                            }
                        }
                    }
                }
            }
        }

        $ticket_total_price = $duration_cost + $service_cost; 

        $discount_arr = function_exists('rbfw_get_discount_array') ? rbfw_get_discount_array($post_id, $start_date, $end_date, $ticket_total_price) : '';

        if(!empty($discount_arr)){
            $ticket_total_price = $discount_arr['total_amount'];
            $discount_type = $discount_arr['discount_type'];
            $discount_amount = $discount_arr['discount_amount'];
        }

        $ticket_info = $BikeCarMdClass->rbfw_bikecarmd_ticket_info($post_id, $start_datetime, $end_datetime, $pickup_point, $dropoff_point, $service_info_merged,$duration_cost,$service_cost,$ticket_total_price,$item_quantity,$start_date,$end_date,$start_time,$end_time,$variation_info, $discount_type, $discount_amount,$rbfw_regf_info);

        $service_info_merged = $service_info;

    }

    $gateway = Omnipay::create('PayPal_Rest');
    $gateway->setClientId(CLIENT_ID);
    $gateway->setSecret(CLIENT_SECRET);
    $gateway->setTestMode($gateway_environment); //set it to 'false' when go live

    try {

        $response = $gateway->purchase(array(
            'amount' => $ticket_total_price,
            'items' => array(
                array(
                    'name' => $item_title,
                    'price' => $ticket_total_price,
                    'quantity' => 1
                ),
            ),
            'currency' => PAYPAL_CURRENCY,
            'returnUrl' => PAYPAL_RETURN_URL,
            'cancelUrl' => PAYPAL_CANCEL_URL,
        ))->send();

        $payment_status = 'pending';
        $RBFW_MPS_Function = new RBFW_MPS_Function();
        $ref = $response->getTransactionReference();

        if(is_user_logged_in()){

            $current_user = wp_get_current_user();
            $current_user_email = $current_user->user_email;
            if($current_user_email == $email){

                $order = $RBFW_MPS_Function->rbfw_mps_create_order($post_id, $rent_type, $start_date, $start_time, $end_date, $end_time, $pickup_point, $dropoff_point, $type_info_merged, $service_info_merged, $payment_method, $first_name, $last_name, $email, $package, $payment_status,'','', $ref, $item_quantity,$variation_info,$rbfw_regf_info);

                if(!empty($order)){
                    $order_id = $order['order_id'];
                    update_post_meta($order_id, 'rbfw_ticket_info', $ticket_info);
                }                 
            }else{
                return;
            }

        }else{

            // If Account creation is enabled
            if($checkout_account == 'on'){
                $user_id = wp_create_user( $email, '' ,$email );

                if ($user_id) {
                    wp_new_user_notification($user_id, 'both');
                    wp_set_current_user($user_id);
                    wp_set_auth_cookie($user_id);
                    update_user_meta( $user_id, 'first_name', $first_name );
                    update_user_meta( $user_id, 'last_name', $last_name );

                    $order = $RBFW_MPS_Function->rbfw_mps_create_order($post_id, $rent_type, $start_date, $start_time, $end_date, $end_time, $pickup_point, $dropoff_point, $type_info_merged, $service_info_merged, $payment_method, $first_name, $last_name, $email, $package, $payment_status,'','',$ref, $item_quantity,$variation_info,$rbfw_regf_info);

                    if(!empty($order)){
                        $order_id = $order['order_id'];
                        update_post_meta($order_id, 'rbfw_ticket_info', $ticket_info);
                    }
                }

            }else{
                // Else Create order without account
                $order = $RBFW_MPS_Function->rbfw_mps_create_order($post_id, $rent_type, $start_date, $start_time, $end_date, $end_time, $pickup_point, $dropoff_point, $type_info_merged, $service_info_merged, $payment_method, $first_name, $last_name, $email, $package, $payment_status,'','',$ref, $item_quantity,$variation_info,$rbfw_regf_info);

                if(!empty($order)){
                    $order_id = $order['order_id'];
                    update_post_meta($order_id, 'rbfw_ticket_info', $ticket_info);
                }

            }
        }

        if ($response->isRedirect()) {

            $response->redirect(); // this will automatically forward the customer

        } else {
            // not successful
            echo $response->getMessage();
        }

    } catch(Exception $e) {

        echo $e->getMessage();
    }

    }    
}

// Paypal Transaction Status Function
add_action('wp_loaded','rbfw_paypal_payment_status_confirmation');

function rbfw_paypal_payment_status_confirmation(){

    $rbfw_payment_settings = get_option("rbfw_basic_payment_settings");
    $gateway_environment = !empty($rbfw_payment_settings['rbfw_mps_payment_gateway_environment']) ? $rbfw_payment_settings['rbfw_mps_payment_gateway_environment'] : '';
    $client_id = !empty($rbfw_payment_settings['rbfw_mps_paypal_client_id']) ? $rbfw_payment_settings['rbfw_mps_paypal_client_id'] : '';
    $secret_key = !empty($rbfw_payment_settings['rbfw_mps_paypal_secret_key']) ? $rbfw_payment_settings['rbfw_mps_paypal_secret_key'] : '';
    $currency = !empty($rbfw_payment_settings['rbfw_mps_currency']) ? $rbfw_payment_settings['rbfw_mps_currency'] : '';


    if(empty($client_id) || empty($secret_key) || empty($currency)){
        return;
    }

    if($gateway_environment == 'sandbox'){
        
        $gateway_environment = true;

    }elseif($gateway_environment == 'live'){
        
        $gateway_environment = false;

    }else{
        $gateway_environment = true;
    }

    $MpsEmailClass = new Rbfw_Mps_Email();

    if (array_key_exists('paymentId', $_GET) && array_key_exists('PayerID', $_GET)) {

        $args = array(
            'post_type' => 'rbfw_order',
            'meta_query' => array(
                array(
                 'key' => 'rbfw_reference',
                 'value' => $_GET['paymentId'],
                 'compare' => '='
                ),
            )
        );
        $order = [];
        $the_query = new WP_Query($args);
        if ( $the_query->have_posts() ) {
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
                global $post;
                $order_id = $post->ID;
                $order['order_id'] = $order_id;
                $email_status = get_post_meta($order_id, 'rbfw_order_email_status', true);
                $payment_method = get_post_meta($order_id, 'rbfw_payment_method', true);
            }
        }

        if($payment_method != 'paypal'){
            return;
        }

        $gateway = Omnipay::create('PayPal_Rest');
        $gateway->setClientId(CLIENT_ID);
        $gateway->setSecret(CLIENT_SECRET);
        $gateway->setTestMode($gateway_environment);

        $transaction = $gateway->completePurchase(array(
            'payer_id'             => $_GET['PayerID'],
            'transactionReference' => $_GET['paymentId'],
        ));
        $response = $transaction->send();
    
        if ($response->isSuccessful()) {
            // The customer has successfully paid.
            update_post_meta($order_id, 'rbfw_order_status', 'processing');
            update_post_meta($order_id, 'rbfw_payment_status', 'paid');

            if($email_status != 'sent'){
                $MpsEmailClass->rbfw_mps_new_order_user_notification($order);
                update_post_meta($order_id, 'rbfw_order_email_status', 'sent');
            }
            
        }else{
            update_post_meta($order_id, 'rbfw_order_status', 'pending');
            update_post_meta($order_id, 'rbfw_payment_status', 'unpaid');            
        }
    }

}
