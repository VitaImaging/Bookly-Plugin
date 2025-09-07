<?php
/*
* Author 	:	MagePeople Team
* Copyright	: 	mage-people.com
* Developer :   Ariful
* Version	:	1.0.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Rbfw_Mps_Email' ) ) {
    class Rbfw_Mps_Email {

        public function rbfw_mps_new_order_user_notification($order){
            $order_id = $order['order_id'];
            $status = get_post_meta($order_id, 'rbfw_order_status', true);

            if($status != 'processing'){
                return;
            }

            $user_email = get_post_meta($order_id, 'rbfw_billing_email');
            $admin_email = get_option('admin_email');
            $to = $user_email;

            $subject = rbfw_string_return('rbfw_text_ur_order_has_been_received',__('Your order has been received!','rbfw-pro'));
            $body = $this->rbfw_mps_email_template($order_id);
            $headers = [];
            $headers[] = 'Content-Type: text/html; charset=UTF-8';
            $headers[] = 'Cc: '.$admin_email;
            wp_mail( $to, $subject, $body, $headers );
        }

        public function rbfw_mps_email_template($order_id){
            global $rbfw;

            if(!empty($order_id)){
                $status = get_post_meta($order_id, 'rbfw_order_status', true);
                $billing_name = get_post_meta($order_id, 'rbfw_billing_name', true);
                $billing_email = get_post_meta($order_id, 'rbfw_billing_email', true);
                $payment_method = get_post_meta($order_id, 'rbfw_payment_method', true);
                $payment_id = get_post_meta($order_id, 'rbfw_payment_id', true);

                ob_start();
                ?>
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title><?php echo __('Your order has been received!', 'rbfw-pro'); ?></title>
                    <style>
                        table{
                            border: 0.1rem solid #dcd7ca;border-top-color: rgb(220, 215, 202);border-right-color: rgb(220, 215, 202);border-bottom-color: rgb(220, 215, 202);border-left-color: rgb(220, 215, 202);font-size: 14px;max-width: 100%;overflow: hidden;width: 100%;background: #fff;border-collapse: collapse;
                        }
                        table thead{background: #DDD;}
                        th, td {
                            line-height: 1.4;
                            margin: 0;
                            overflow: visible;
                            padding: 0.5em;
                            text-align:left; 
                        }
                        div.heading{
                            background: #c8c8c8;
                            padding: 20px;
                            color: #000;
                            font-weight: lighter;
                            font-size: 24px;
                        }
                        .rbfw_email_template_wrap{
                            background-color: #f7f7f7;
                            margin: 0;
                            padding: 70px;
                            width: 78%;
                            max-width: 100%;
                        }
                        table tr{
                            border-bottom: 1px solid #f5f5f5;
                        }
                    </style>
                </head>
                <body>
                <div class="rbfw_email_template_wrap">
                <div class="heading"><?php rbfw_string('rbfw_text_thankyou_ur_order_received',__('Thank you. Your order has been received.','rbfw-pro')); ?></div>
                <table>
                    <thead>
                        <tr>
                            <th colspan="2"><?php rbfw_string('rbfw_text_order_received',__('Order Information','rbfw-pro')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong><?php rbfw_string('rbfw_text_order_number',__('Order number','rbfw-pro')); echo ':'; ?></strong></td>
                            <td><?php echo esc_html($order_id); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php rbfw_string('rbfw_text_name',__('Name','rbfw-pro')); echo ':'; ?></strong></td>
                            <td><?php echo esc_html($billing_name); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php rbfw_string('rbfw_text_email',__('Email','rbfw-pro')); echo ':'; ?></strong></td>
                            <td><?php echo esc_html($billing_email); ?></td>
                        </tr>                        
                        <tr>
                            <td><strong><?php rbfw_string('rbfw_text_payment_method',__('Payment method','rbfw-pro')); echo ':'; ?></strong></td>
                            <td><?php echo esc_html($payment_method); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php rbfw_string('rbfw_text_status',__('Status','rbfw-pro')); echo ':'; ?></strong></td>
                            <td><?php echo esc_html($status); ?></td>
                        </tr>                      
                    </tbody>
                </table>

                    <?php
                /* Loop Ticket Info */
                    $ticket_infos = !empty(get_post_meta($order_id,'rbfw_ticket_info',true)) ? get_post_meta($order_id,'rbfw_ticket_info',true) : [];

                    foreach ($ticket_infos as $ticket_info) {
                
            
                $item_name = !empty($ticket_info['ticket_name']) ? $ticket_info['ticket_name'] : '';
                $rbfw_id = $ticket_info['rbfw_id'];
                $item_id = $rbfw_id;
                $rent_type = $ticket_info['rbfw_rent_type'];

                $rbfw_start_datetime = rbfw_get_datetime($ticket_info['rbfw_start_datetime'], 'date-time-text');
                $rbfw_end_datetime = rbfw_get_datetime($ticket_info['rbfw_end_datetime'], 'date-time-text');
                $rbfw_start_time = !empty($ticket_info['rbfw_start_time']) ? $ticket_info['rbfw_start_time'] : '';
                $rbfw_end_time =  !empty($ticket_info['rbfw_end_time']) ? $ticket_info['rbfw_end_time'] : '';

                if($rent_type == 'resort'){

                    $rbfw_start_datetime = rbfw_get_datetime($ticket_info['rbfw_start_datetime'], 'date-text');
                    $rbfw_end_datetime = rbfw_get_datetime($ticket_info['rbfw_end_datetime'], 'date-text');

                }elseif($rent_type == 'bike_car_sd' || $rent_type == 'appointment'){

                    $rbfw_start_datetime = rbfw_get_datetime($ticket_info['rbfw_start_datetime'], 'date-time-text');
                    $rbfw_end_datetime = rbfw_get_datetime($ticket_info['rbfw_end_datetime'], 'date-text');

                }else{

                    $rbfw_start_datetime = rbfw_get_datetime($ticket_info['rbfw_start_datetime'], 'date-time-text');
                    $rbfw_end_datetime = rbfw_get_datetime($ticket_info['rbfw_end_datetime'], 'date-time-text');
                }

                $tax = !empty($ticket_info['rbfw_mps_tax']) ? $ticket_info['rbfw_mps_tax'] : 0;
                $mps_tax_percentage = !empty(get_post_meta($rbfw_id, 'rbfw_mps_tax_percentage', true)) ? strip_tags(get_post_meta($rbfw_id, 'rbfw_mps_tax_percentage', true)) : '';
                $tax_status = '';


                if($rent_type == 'bike_car_sd' || $rent_type == 'appointment'){
                    $BikeCarSdClass = new RBFW_BikeCarSd_Function();
                    $rent_info = !empty($ticket_info['rbfw_type_info']) ? $ticket_info['rbfw_type_info'] : [];
                    $service_info = !empty($ticket_info['rbfw_service_info']) ? $ticket_info['rbfw_service_info'] : [];
                    $rent_info = $BikeCarSdClass->rbfw_get_bikecarsd_rent_info($item_id, $rent_info);
                    $service_info = $BikeCarSdClass->rbfw_get_bikecarsd_service_info($item_id, $service_info);

                }elseif($rent_type == 'bike_car_md' || $rent_type == 'dress' || $rent_type == 'equipment' || $rent_type == 'others'){
                    $BikeCarMdClass = new RBFW_BikeCarMd_Function();
                    $service_info = !empty($ticket_info['rbfw_service_info']) ? $ticket_info['rbfw_service_info'] : [];
                    $service_info = $BikeCarMdClass->rbfw_get_bikecarmd_service_info($item_id, $service_info);
                    $item_quantity = !empty($ticket_info['rbfw_item_quantity']) ? $ticket_info['rbfw_item_quantity'] : 1;
                    $pickup_point = !empty($ticket_info['rbfw_pickup_point']) ? $ticket_info['rbfw_pickup_point'] : '';
                    $dropoff_point = !empty($ticket_info['rbfw_dropoff_point']) ? $ticket_info['rbfw_dropoff_point'] : '';

                }elseif($rent_type == 'resort'){
                    $ResortClass = new RBFW_Resort_Function();
                    $package = $ticket_info['rbfw_resort_package'];
                    $rent_info = !empty($ticket_info['rbfw_type_info']) ? $ticket_info['rbfw_type_info'] : [];
                    $rent_info  = $ResortClass->rbfw_get_resort_room_info($item_id, $rent_info, $package);
                    $service_info = !empty($ticket_info['rbfw_service_info']) ? $ticket_info['rbfw_service_info'] : [];
                    $service_info = $ResortClass->rbfw_get_resort_service_info($item_id, $service_info);

                }else{
                    $rent_info = '';
                    $service_info = '';
                }

                $variation_info = !empty($ticket_info['rbfw_variation_info']) ? $ticket_info['rbfw_variation_info'] : [];
                $rbfw_regf_info = !empty($ticket_info['rbfw_regf_info']) ? $ticket_info['rbfw_regf_info'] : [];

                $duration_cost = wc_price($ticket_info['duration_cost']);
                $service_cost = wc_price($ticket_info['service_cost']);
                $total_cost = wc_price($ticket_info['ticket_price']);
                $discount_amount = !empty($ticket_info['discount_amount']) ? wc_price($ticket_info['discount_amount']) : '';
                /* End  loop*/
                ?>
            <table>
                <thead>
                    <tr>
                        <th colspan="2"><?php rbfw_string('rbfw_text_item_information',__('Item Information','rbfw-pro')); echo ':'; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong><?php rbfw_string('rbfw_text_item_name',__('Item Name','rbfw-pro')); echo ':'; ?></strong></td>
                        <td><?php echo esc_html($item_name); ?></td>
                    </tr>

                    <?php if($rent_type == 'bike_car_md' || $rent_type == 'dress' || $rent_type == 'equipment' || $rent_type == 'others'){ ?>
                    <tr>
                        <td><strong><?php rbfw_string('rbfw_text_pickup_location',__('Pickup Location','rbfw-pro')); echo ':'; ?></strong></td>
                        <td><?php echo esc_html($pickup_point); ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php rbfw_string('rbfw_text_dropoff_location',__('Drop-off Location','rbfw-pro')); echo ':'; ?></strong></td>
                        <td><?php echo esc_html($dropoff_point); ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($rent_type == 'resort'){ ?>
                    <tr>
                        <td><strong><?php rbfw_string('rbfw_text_package',__('Package','rbfw-pro')); echo ':'; ?></strong></td>
                        <td><?php echo esc_html($package); ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($rent_type == 'bike_car_sd' || $rent_type == 'appointment'){ ?>
                        <tr>
                            <td><strong><?php rbfw_string('rbfw_text_rent_information',__('Rent Information','rbfw-pro')); echo ':'; ?></strong></td>
                            <td>
                                <table class="wp-list-table widefat fixed striped table-view-list">
                                <?php
                                    if(!empty($rent_info)){
                                        foreach ($rent_info as $key => $value) {
                                            ?>
                                            <tr>
                                                <td><strong><?php esc_html_e($key); ?></strong></td>
                                                <td><?php echo $value;?></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                ?>
                                </table>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if($rent_type == 'resort'){ ?>
                    <tr>
                        <td><strong><?php rbfw_string('rbfw_text_room_information',__('Room Information','rbfw-pro')); echo ':'; ?></strong></td>
                        <td>
                            <table class="wp-list-table widefat fixed striped table-view-list">                     
                            <?php 
                                if(!empty($rent_info)){
                                    foreach ($rent_info as $key => $value) {
                                        ?>
                                        <tr>
                                            <td><strong><?php esc_html_e($key); ?></strong></td>
                                            <td><?php echo $value; ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                            ?>
                            </table>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td><strong><?php rbfw_string('rbfw_text_extra_service_information',__('Extra Service Information','rbfw-pro')); echo ':'; ?></strong></td>
                        <td>
                            <table class="wp-list-table widefat fixed striped table-view-list">                     
                            <?php 
                            if($rent_type == 'bike_car_sd' || $rent_type == 'appointment'){
                                if(!empty($service_info)){
                                    foreach ($service_info as $key => $value) {
                                        ?>
                                        <tr>
                                            <td><strong><?php echo $key; ?></strong></td>
                                            <td><?php echo $value; ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                            }
                            elseif($rent_type == 'bike_car_md' || $rent_type == 'dress' || $rent_type == 'equipment' || $rent_type == 'others'){
                                if(!empty($service_info)){
                                    foreach ($service_info as $key => $value) {
                                        ?>
                                        <tr>
                                            <td><strong><?php esc_html_e($key); ?></strong></td>
                                            <td><?php echo $value; ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                            }
                            elseif($rent_type == 'resort'){
                                if(!empty($service_info)){
                                    foreach ($service_info as $key => $value) {
                                        ?>
                                        <tr>
                                            <td><strong><?php esc_html_e($key); ?></strong></td>
                                            <td><?php echo $value; ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                            }
                            ?>
                            </table>
                        </td>
                    </tr>
                    <?php if(!empty($rbfw_regf_info)){ ?>
                    <tr>
                        <td><strong><?php rbfw_string('rbfw_text_customer_information',__('Customer Information','booking-and-rental-manager-for-woocommerce')); echo ':'; ?></strong></td>
                        <td>
                            <ol>
                            <?php
                            foreach ($rbfw_regf_info as $info) {

                                $label = $info['label'];
                                $value = $info['value'];

                                if(filter_var($value, FILTER_VALIDATE_URL)){

                                    $value = '<a href="'.esc_url($value).'" target="_blank" style="text-decoration:underline">'.esc_html__('View File','booking-and-rental-manager-for-woocommerce').'</a>';
                                }
                                ?>
                                <li><?php echo $label; ?>: <?php echo $value; ?></li>
                                <?php
                            }
                            ?>
                            </ol>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td><strong><?php rbfw_string('rbfw_text_start_date_and_time',__('Start Date and Time','rbfw-pro')); echo ':'; ?></strong></td>
                        <td><?php echo esc_html($rbfw_start_datetime); ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php rbfw_string('rbfw_text_end_date_and_time',__('End Date and Time','rbfw-pro')); echo ':'; ?></strong></td>
                        <td><?php echo esc_html($rbfw_end_datetime); ?></td>
                    </tr>
                    <?php if(!empty($variation_info)){ 
                    foreach ($variation_info as $key => $value) { 
                    ?>
                    <tr>
                        <td><strong><?php echo esc_html($value['field_label']); ?></strong></td>
                        <td><?php echo esc_html($value['field_value']); ?></td>
                    </tr>
                    <?php } } ?>
                    <tr>
                        <td><strong><?php rbfw_string('rbfw_text_duration_cost',__('Duration Cost','rbfw-pro')); echo ':'; ?></strong></td>
                        <td><?php echo $duration_cost; ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php rbfw_string('rbfw_text_resource_cost',__('Resource Cost','rbfw-pro')); echo ':'; ?></strong></td>
                        <td><?php echo $service_cost; ?></td>
                    </tr>

                    
                    <?php if(!empty($discount_amount)){ ?>
                    <tr>
                        <td><strong><?php echo $rbfw->get_option('rbfw_text_discount', 'rbfw_basic_translation_settings', __('Discount','booking-and-rental-manager-for-woocommerce')); ?>:</strong></td>
                        <td><?php echo $discount_amount; ?></td>
                    </tr>
                    <?php } ?>

                    <tr>
                        <td><strong><?php rbfw_string('rbfw_text_total_cost',__('Total Cost','rbfw-pro')); echo ':'; ?></strong></td>
                        <td><?php echo $total_cost.' '.$tax_status; ?></td>
                    </tr>
                </tbody>
            </table>

                    <?php } ?>
                </div>
                </body>
                </html>
                <?php
                $content = ob_get_clean();
                return $content;
            }
        }
    }
    new Rbfw_Mps_Email();
}