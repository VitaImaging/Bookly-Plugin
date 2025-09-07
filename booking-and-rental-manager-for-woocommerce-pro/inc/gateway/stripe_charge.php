<?php
/*
* Author 	:	MagePeople Team
* Developer :   Ariful
* Version	:	1.0.0
*/

if ( ! defined( 'ABSPATH' ) ) {
    die;
} // Cannot access pages directly.

use Omnipay\Omnipay;

if ( ! class_exists( 'RBFW_MPS_Stripe_Charge' ) ) {
    class RBFW_MPS_Stripe_Charge {
        public function __construct(){
            add_action('wp_ajax_rbfw_mps_stripe_charge', array($this, 'rbfw_mps_stripe_charge'));
            add_action('wp_ajax_nopriv_rbfw_mps_stripe_charge', array($this,'rbfw_mps_stripe_charge'));                          
        }

        public function rbfw_mps_stripe_charge(){
            check_ajax_referer( 'rbfw_mps_stripe_charge', 'security' );

            global $rbfw;
            $publishable_key = $rbfw->get_option('rbfw_mps_stripe_publishable_key', 'rbfw_basic_payment_settings');
            $secret_key = $rbfw->get_option('rbfw_mps_stripe_secret_key', 'rbfw_basic_payment_settings');
            $currency = $rbfw->get_option('rbfw_mps_currency', 'rbfw_basic_payment_settings');
            $t_page_id = $rbfw->get_option('rbfw_thankyou_page', 'rbfw_basic_gen_settings');
            $t_page_url = !empty($t_page_id) ? get_page_link($t_page_id) : '';
            $gateway_environment = $rbfw->get_option('rbfw_mps_payment_gateway_environment', 'rbfw_basic_payment_settings');

            $checkout_account = $rbfw->get_option('rbfw_mps_checkout_account', 'rbfw_basic_payment_settings','on');

            if (empty($publishable_key) || empty($secret_key) || empty($currency) || empty($t_page_id)) {
                return;
            }

            if (empty($_POST['stripeToken'])) {
                return;
            }
            
            $rbfw_thankyou_class = new Rbfw_Thankyou_Page();
            $MpsEmailClass = new Rbfw_Mps_Email();
            $RBFW_MPS_Function = new RBFW_MPS_Function();
            $post_id = isset($_POST['post_id']) ? strip_tags($_POST['post_id']) : '';
            $payment_method = isset($_POST['payment_method']) ? strip_tags($_POST['payment_method']) : '';
            $rent_type = isset($_POST['rent_type']) ? strip_tags($_POST['rent_type']) : '';
            $start_date = isset($_POST['start_date']) ? strip_tags($_POST['start_date']) : '';
            $start_time = isset($_POST['start_time']) ? strip_tags($_POST['start_time']) : '';
            $end_date = isset($_POST['end_date']) ? strip_tags($_POST['end_date']) : '';
            $end_time = isset($_POST['end_time']) ? strip_tags($_POST['end_time']) : '';
            $pickup_point = isset($_POST['pickup_point']) ? strip_tags($_POST['pickup_point']) : '';
            $dropoff_point = isset($_POST['dropoff_point']) ? strip_tags($_POST['dropoff_point']) : '';
            $type_info = !empty($_POST['type_info']) ? $_POST['type_info'] : [];
            $service_info = !empty($_POST['service_info']) ? $_POST['service_info'] : [];
            $first_name = isset($_POST['first_name']) ? strip_tags($_POST['first_name']) : '';
            $last_name = isset($_POST['last_name']) ? strip_tags($_POST['last_name']) : '';
            $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';           
            $package = !empty($_POST['package']) ? strip_tags($_POST['package']) : '';

            $variation_info = !empty($_POST['variation_info']) ? $_POST['variation_info'] : [];
            $item_quantity = !empty($_POST['item_quantity']) ? strip_tags($_POST['item_quantity']) : 1;
            $stripe_token = !empty($_POST['stripeToken']) ? strip_tags($_POST['stripeToken']) : '';

            $item_title = get_the_title($post_id); 
            $g_services = get_post_meta($post_id,'rbfw_extra_service_data',true);
            $g_services = !empty($g_services) ? array_column($g_services, 'service_price', 'service_name') : [];

            $duration_cost = 0;
            $service_cost = 0;
            $ticket_total_price = 0;


            $errors = '';

            if(empty($first_name)):
            $errors .= '<p class="mps_alert_warning"><i class="fa-solid fa-circle-info"></i> '.__('First name is required!','booking-and-rental-manager-for-woocommerce').'</p>';
            endif;

            if(empty($last_name)):
            $errors .= '<p class="mps_alert_warning"><i class="fa-solid fa-circle-info"></i> '.__('Last name is required!','booking-and-rental-manager-for-woocommerce').'</p>';
            endif;                

            if(empty($email)):
            $errors .= '<p class="mps_alert_warning"><i class="fa-solid fa-circle-info"></i> '.__('Email is required!','booking-and-rental-manager-for-woocommerce').'</p>';
            endif;

            if(!empty($errors)){
                echo $errors;
                wp_die();
            }

            /* Start Discount Calculations */
            $discount_type = '';
            $discount_amount = '';
            /* End Discount Calculations */

            $rbfw_regf_info = !empty($_POST['rbfw_regf_info']) ? $_POST['rbfw_regf_info'] : [];

            if($rent_type == 'bike_car_sd' || $rent_type == 'appointment'){
                $rbfw_bikecarsd = new RBFW_BikeCarSd_Function();
                $g_rent_types = get_post_meta($post_id,'rbfw_bike_car_sd_data',true);
                $g_rent_types = array_column($g_rent_types, 'price', 'rent_type');
                $type_info_merged_array = array_reduce($type_info, 'array_merge', array());
                $service_info_merged_array = array_reduce($service_info, 'array_merge', array());
                $ticket_info = $rbfw_bikecarsd->rbfw_bikecarsd_ticket_info($post_id,$start_date,$end_date,$type_info_merged_array,$service_info_merged_array,$start_time,$rbfw_regf_info);

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
                $type_info_merged = array_reduce($type_info, 'array_merge', array());
                $service_info_merged = array_reduce($service_info, 'array_merge', array());
                $ticket_total_price = $resortClass->rbfw_resort_price_calculation($post_id,$start_date,$end_date,$package,$type_info_merged,$service_info_merged,'rbfw_room_total_price'); 
                $ticket_info = $resortClass->rbfw_resort_ticket_info($post_id,$start_date,$end_date,$package,$type_info_merged,$service_info_merged,$rbfw_regf_info);

                
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
                $start_datetime = $start_date.' '.$start_time;
                $end_datetime = $end_date.' '.$end_time;
                $duration_cost = rbfw_price_calculation( $post_id, $start_datetime, $end_datetime, $start_date ) * (int)$item_quantity;
                $rbfw_enable_extra_service_qty = get_post_meta( $post_id, 'rbfw_enable_extra_service_qty', true ) ? get_post_meta( $post_id, 'rbfw_enable_extra_service_qty', true ) : 'no';

                if(!empty($service_info)){
                    foreach ($service_info as $service_arr) {
                        foreach ($service_arr as $service_name => $service_qty) {

                            if($item_quantity > 1 && $service_qty == 1 && $rbfw_enable_extra_service_qty != 'yes'){
                                $service_qty = $item_quantity;
                            }

                            if($service_qty > 0){
                                foreach ($g_services as $g_service_name => $g_service_price) {
                                    if($service_name == $g_service_name){
                                        $price = (float)$g_service_price * (int)$service_qty;
                                        $service_cost += $price;
                                    }
                                }
                            }
                        }
                    }
                }
                
                $service_info_merged = array_reduce($service_info, 'array_merge', array());

                $ticket_total_price = $duration_cost + $service_cost; 

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

                $ticket_info = $BikeCarMdClass->rbfw_bikecarmd_ticket_info($post_id, $start_datetime, $end_datetime, $pickup_point, $dropoff_point, $service_info_merged,$duration_cost,$service_cost,$ticket_total_price,$item_quantity,$start_date,$end_date,$start_time,$end_time,$variation_info, $discount_type, $discount_amount, $rbfw_regf_info);
        
            }

            
            define('STRIPE_API_KEY', $secret_key);
            define('STRIPE_RETURN_URL', $t_page_url);
            define('STRIPE_CURRENCY', $currency);

            if($gateway_environment == 'sandbox'){

                $gateway_environment = true;
            
            }elseif($gateway_environment == 'live'){
                
                $gateway_environment = false;
            
            }else{
                $gateway_environment = true;
            }
            
            $stripeGateway = Omnipay::create('Stripe');
            $stripeGateway->setApiKey(STRIPE_API_KEY);
            $stripeGateway->setTestMode($gateway_environment);

            try {

                $response = $stripeGateway->authorize([
                    'amount' => $ticket_total_price,
                    'currency' => STRIPE_CURRENCY,
                    'description' => $item_title,
                    'token' => $stripe_token,
                    'confirm' => true,
                ])->send();

                $payment_status = 'pending';
                $ref = $response->getTransactionReference();

                if($response->isSuccessful()) {
                    
                    $response = $stripeGateway->capture([
                        'amount' => $ticket_total_price,
                        'currency' => STRIPE_CURRENCY,
                        'transactionReference' => $ref,
                    ])->send();
             
                    $arr_payment_data = $response->getData();
                    
                    $msg = '<p class="mps_alert_login_success"><i class="fa-solid fa-circle-check"></i> '.__('Payment successful, redirecting...','booking-and-rental-manager-for-woocommerce-pro').'</p>';
                    
                    $return_url = STRIPE_RETURN_URL .'?paymentId='.$arr_payment_data['id'].'&paymentStatus='.$arr_payment_data['status'];    
                    $data = array(
                        'message' => $msg,
                        'returnUrl' => $return_url
                    );

                    header("Content-Type: application/json");
                    print(json_encode($data));
                    
                }
                else {
                    $error = '<p class="mps_alert_warning"><i class="fa-solid fa-circle-info"></i> '.$response->getMessage().'</p>';
                    echo $error;
                }

                if(is_user_logged_in()){
        
                    $current_user = wp_get_current_user();
                    $current_user_email = $current_user->user_email;
                    if($current_user_email == $email){
        
                        $order = $RBFW_MPS_Function->rbfw_mps_create_order($post_id, $rent_type, $start_date, $start_time, $end_date, $end_time, $pickup_point, $dropoff_point, $type_info, $service_info, $payment_method, $first_name, $last_name, $email, $package, $payment_status,'','',$ref, $item_quantity,$variation_info, $rbfw_regf_info);
        
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
        
                            $order = $RBFW_MPS_Function->rbfw_mps_create_order($post_id, $rent_type, $start_date, $start_time, $end_date, $end_time, $pickup_point, $dropoff_point, $type_info, $service_info, $payment_method, $first_name, $last_name, $email, $package, $payment_status,'','',$ref, $item_quantity,$variation_info, $rbfw_regf_info);
        
                            if(!empty($order)){
                                $order_id = $order['order_id'];
                                update_post_meta($order_id, 'rbfw_ticket_info', $ticket_info);
                            }
                        }
        
                    }else{
                        // Else Create order without account
                        $order = $RBFW_MPS_Function->rbfw_mps_create_order($post_id, $rent_type, $start_date, $start_time, $end_date, $end_time, $pickup_point, $dropoff_point, $type_info, $service_info, $payment_method, $first_name, $last_name, $email, $package, $payment_status,'','',$ref, $item_quantity,$variation_info, $rbfw_regf_info);
        
                        if(!empty($order)){
                            $order_id = $order['order_id'];
                            update_post_meta($order_id, 'rbfw_ticket_info', $ticket_info);
                        }
        
                    }
                }

                if(!empty($order)){
                    $order_id = $order['order_id'];
                    $email_status = get_post_meta($order_id, 'rbfw_order_email_status', true);
                    $payment_method = get_post_meta($order_id, 'rbfw_payment_method', true);

                    if($arr_payment_data['status'] == 'succeeded'){
                        update_post_meta($order_id, 'rbfw_order_status', 'processing');
                        update_post_meta($order_id, 'rbfw_payment_status', 'paid');
                    }else{
                        update_post_meta($order_id, 'rbfw_order_status', 'pending');
                        update_post_meta($order_id, 'rbfw_payment_status', 'unpaid');
                    }

                    if($email_status != 'sent'){
                        $MpsEmailClass->rbfw_mps_new_order_user_notification($order);
                        update_post_meta($order_id, 'rbfw_order_email_status', 'sent');
                    }
                }
                
            } catch(Exception $e) {
                $error = '<p class="mps_alert_warning"><i class="fa-solid fa-circle-info"></i> '.$e->getMessage().'</p>';
                echo $error;
               
            }
          
            wp_die();
        }
    }
    new RBFW_MPS_Stripe_Charge();
}
