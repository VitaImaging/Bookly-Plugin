<?php
function rbfw_csv_thead()
{

  $pin_no         = rbfw_get_option('rbfw_purchase_list_ticket_no', 'rbfw_basic_purchase_list_settings', 'off');
  $name           = rbfw_get_option('rbfw_purchase_list_name', 'rbfw_basic_purchase_list_settings', 'on');
  $email          = rbfw_get_option('rbfw_purchase_list_email', 'rbfw_basic_purchase_list_settings', 'on');
  $phone          = rbfw_get_option('rbfw_purchase_list_phone', 'rbfw_basic_purchase_list_settings', 'off');
  $address        = rbfw_get_option('rbfw_purchase_list_address', 'rbfw_basic_purchase_list_settings', 'off');   
  $item    = rbfw_get_option('rbfw_purchase_list_ticket_type', 'rbfw_basic_purchase_list_settings', 'on');  
  $order_no       = rbfw_get_option('rbfw_purchase_list_order_no', 'rbfw_basic_purchase_list_settings', 'on');
  $datetime       = rbfw_get_option('rbfw_purchase_datetime', 'rbfw_basic_purchase_list_settings', 'on');
  $order_st       = rbfw_get_option('rbfw_purchase_list_billing_order_status', 'rbfw_basic_purchase_list_settings', 'on');
  $paid           = rbfw_get_option('rbfw_purchase_list_billing_paid', 'rbfw_basic_purchase_list_settings', 'on');
  $pmethod        = rbfw_get_option('rbfw_purchase_list_billing_method', 'rbfw_basic_purchase_list_settings', 'on');
  $pickup_point   = rbfw_get_option('rbfw_purchase_list_pickup_point', 'rbfw_basic_purchase_list_settings', 'off');
  $dropoff_point  = rbfw_get_option('rbfw_purchase_list_dropoff_point', 'rbfw_basic_purchase_list_settings', 'off');
  $extra_service  = rbfw_get_option('rbfw_purchase_list_extra_service', 'rbfw_basic_purchase_list_settings', 'on');
  $service  = rbfw_get_option('rbfw_purchase_list_service', 'rbfw_basic_purchase_list_settings', 'on');
  $item_quantity  = rbfw_get_option('rbfw_purchase_list_item_quantity', 'rbfw_basic_purchase_list_settings', 'on');

  $thead = [];

  if($order_no == 'on'){
    $thead[] = __('Order ID', 'rbfw-pro');
  }
  if($item == 'on'){
    $thead[] = __('Item', 'rbfw-pro');
  }
  if($item_quantity == 'on'){
    $thead[] = __('Item Quantity', 'rbfw-pro');
  }
  if($service == 'on'){
    $thead[] = __('Service', 'rbfw-pro');
  }
  if($extra_service == 'on'){
    $thead[] = __('Extra Service', 'rbfw-pro');
  }
  if($datetime == 'on'){
    $thead[] = __('Start Datetime', 'rbfw-pro');
  }
  if($datetime == 'on'){
    $thead[] = __('End Datetime', 'rbfw-pro');
  }
  if($pickup_point == 'on'){
    $thead[] = __('Pickup Point', 'rbfw-pro');
  }
  if($dropoff_point == 'on'){
    $thead[] = __('Drop-off Point', 'rbfw-pro');
  }
  if($pin_no == 'on'){
    $thead[] = __('PIN', 'rbfw-pro');
  }
  if($name == 'on'){
    $thead[] = __('Full Name', 'rbfw-pro');
  }
  if($email == 'on'){
    $thead[] = __('Email', 'rbfw-pro');
  }
  if($phone == 'on'){
    $thead[] = __('Phone', 'rbfw-pro');
  }  
  if($address == 'on'){
    $thead[] = __('Address', 'rbfw-pro');
  }
  if($order_st == 'on'){
    $thead[] = __('Order Status', 'rbfw-pro');
  }
  if($paid == 'on'){
    $thead[] = __('Paid Amount', 'rbfw-pro');
  }
  if($pmethod == 'on'){
    $thead[] = __('Payment Method', 'rbfw-pro');
  }

  
  return $thead;
}

function rbfw_csv_tbody()
{
  global $rbfw;
  $pin_no         = rbfw_get_option('rbfw_purchase_list_ticket_no', 'rbfw_basic_purchase_list_settings', 'off');
  $billing_name           = rbfw_get_option('rbfw_purchase_list_name', 'rbfw_basic_purchase_list_settings', 'on');
  $email          = rbfw_get_option('rbfw_purchase_list_email', 'rbfw_basic_purchase_list_settings', 'on');
  $phone          = rbfw_get_option('rbfw_purchase_list_phone', 'rbfw_basic_purchase_list_settings', 'off');
  $address        = rbfw_get_option('rbfw_purchase_list_address', 'rbfw_basic_purchase_list_settings', 'off');   
  $item    = rbfw_get_option('rbfw_purchase_list_ticket_type', 'rbfw_basic_purchase_list_settings', 'on');  
  $order_no       = rbfw_get_option('rbfw_purchase_list_order_no', 'rbfw_basic_purchase_list_settings', 'on');
  $datetime       = rbfw_get_option('rbfw_purchase_datetime', 'rbfw_basic_purchase_list_settings', 'on');
  $order_st       = rbfw_get_option('rbfw_purchase_list_billing_order_status', 'rbfw_basic_purchase_list_settings', 'on');
  $paid           = rbfw_get_option('rbfw_purchase_list_billing_paid', 'rbfw_basic_purchase_list_settings', 'on');
  $pmethod        = rbfw_get_option('rbfw_purchase_list_billing_method', 'rbfw_basic_purchase_list_settings', 'on');
  $pickup_point   = rbfw_get_option('rbfw_purchase_list_pickup_point', 'rbfw_basic_purchase_list_settings', 'off');
  $dropoff_point  = rbfw_get_option('rbfw_purchase_list_dropoff_point', 'rbfw_basic_purchase_list_settings', 'off');
  $extra_service  = rbfw_get_option('rbfw_purchase_list_extra_service', 'rbfw_basic_purchase_list_settings', 'on');
  $service  = rbfw_get_option('rbfw_purchase_list_service', 'rbfw_basic_purchase_list_settings', 'on');  
  $item_quantity  = rbfw_get_option('rbfw_purchase_list_item_quantity', 'rbfw_basic_purchase_list_settings', 'on');

  $tbody = [];
  $event_id               = isset($_GET['event_id']) ? $_GET['event_id'] : '';
  $event_date             = isset($_GET['event_date']) ? $_GET['event_date'] : '';
  $filter_by              = isset($_GET['filter_by']) ? $_GET['filter_by'] : '';
  $ev_filter_key          = isset($_GET['ev_filter_key']) ? $_GET['ev_filter_key'] : '';
  $checkin_status         = isset($_GET['checkin_status']) ? $_GET['checkin_status'] : '';
  $start_date             = isset($_GET['attendee_start_date']) ? $_GET['attendee_start_date'] : '';
  $end_date               = isset($_GET['attendee_end_date']) ? $_GET['attendee_end_date'] : '';
  $pickup_location_id     = isset($_GET['attendee_pickup_location_id']) ? $_GET['attendee_pickup_location_id'] : '';
  $dropoff_location_id    = isset($_GET['attendee_dropoff_location_id']) ? $_GET['attendee_dropoff_location_id'] : '';
  
  $a_query = rbmw_pro_attendee_query($event_id, $event_date, -1,$filter_by,$ev_filter_key,$checkin_status, $start_date, $end_date, $pickup_location_id, $dropoff_location_id);
  $attendee_query = $a_query->posts;

  foreach ($attendee_query as $_attendee) {
    $attendee_id = $_attendee->ID;
    $tdata  = [];
    $pin            = get_post_meta($attendee_id, 'rbfw_pin', true);
    $wc_order_id = get_post_meta($attendee_id, 'rbfw_order_id', true);

    $id = $wc_order_id;

    $ticket_item_name = !empty(get_post_meta($attendee_id,'ticket_name',true)) ? get_post_meta($attendee_id,'ticket_name',true) : '';
    $ticket_start_datetime = !empty(get_post_meta($attendee_id,'rbfw_start_datetime',true)) ? rbfw_get_datetime(get_post_meta($attendee_id,'rbfw_start_datetime',true), 'date-text') : '';
    $ticket_end_datetime = !empty(get_post_meta($attendee_id,'rbfw_end_datetime',true)) ? rbfw_get_datetime(get_post_meta($attendee_id,'rbfw_end_datetime',true), 'date-text') : '';
    $ticket_pickup_point = !empty(get_post_meta($attendee_id,'rbfw_pickup_point',true)) ? get_post_meta($attendee_id,'rbfw_pickup_point',true) : '';
    $ticket_dropoff_point = !empty(get_post_meta($attendee_id,'rbfw_dropoff_point',true)) ? get_post_meta($attendee_id,'rbfw_dropoff_point',true) : '';
    $ticket_ext_services = !empty(get_post_meta($attendee_id,'rbfw_service_info',true)) ? get_post_meta($attendee_id,'rbfw_service_info',true) : [];
    $ticket_view_ext_service = '';
    $ticket_view_rent_info = '';
    $ticket_rent_info = !empty(get_post_meta($attendee_id,'rbfw_type_info',true)) ? get_post_meta($attendee_id,'rbfw_type_info',true) : [];
    $ticket_item_quantity = !empty(get_post_meta($attendee_id,'rbfw_item_quantity',true)) ? get_post_meta($attendee_id,'rbfw_item_quantity',true) : [];

        if(!empty($ticket_ext_services)){

            foreach ($ticket_ext_services as $name => $qty) {
                $ticket_view_ext_service.= $name.' x '.$qty.', ';
            }
        }

        if (!empty($ticket_rent_info) && is_array($ticket_rent_info)) {
            foreach ($ticket_rent_info as $name => $qty) {
                $ticket_view_rent_info .= strip_tags($name) . ' x ' . (int)$qty . ', ';
            }
            $ticket_view_rent_info = rtrim($ticket_view_rent_info, ', '); // Trim the last comma
        }


        if($order_no == 'on'){
          $tdata[] = $id;
        }
        if($item == 'on'){
          $tdata[] = $ticket_item_name;
        }
        if($item_quantity == 'on'){
          $tdata[] = $ticket_item_quantity;
        }
        if($service == 'on'){
          $tdata[] = $ticket_view_rent_info;
        }
        if($extra_service == 'on'){
          $tdata[] = $ticket_view_ext_service;
        }
        if($datetime == 'on'){
          $tdata[] = $ticket_start_datetime;
        }
        if($datetime == 'on'){
          $tdata[] = $ticket_end_datetime;
        }
        if($pickup_point == 'on'){
          $tdata[] = $ticket_pickup_point;
        }
        if($dropoff_point == 'on'){
          $tdata[] = $ticket_dropoff_point;
        }
        if($pin_no == 'on'){
          $tdata[] = $pin;
        }
        if($billing_name == 'on'){
          $tdata[] = get_post_meta($attendee_id, 'rbfw_billing_name', true);
        }
        if($email == 'on'){
          $tdata[] = get_post_meta($attendee_id, 'rbfw_billing_email', true);
        }
        if($phone == 'on'){
          $tdata[] = get_post_meta($attendee_id, 'rbfw_billing_phone', true);
        }  
        if($address == 'on'){
          $tdata[] = get_post_meta($attendee_id, 'rbfw_billing_address', true);
        }
        if($order_st == 'on'){
          $tdata[] = get_post_meta($attendee_id, 'rbfw_order_status', true);
        }
        if($paid == 'on'){
          $tdata[] = get_post_meta($attendee_id, 'rbfw_ticket_total_price', true);
        }
        if($pmethod == 'on'){
          $tdata[] = get_post_meta($attendee_id, 'rbfw_payment_method', true);
        }
     
        $tbody[] = $tdata;
  }

  return $tbody;
}


if (isset($_GET['action']) && $_GET['action'] == 'export_customer_list') {

  add_action('admin_init', 'rbfw_export_default_form');
}

function rbfw_export_default_form()
{
    if (!current_user_can('manage_options')) {
        return false;
    }

    ob_start();

    $domain = $_SERVER['SERVER_NAME'];
    $filename = 'purchase_list_' . $domain . '_' . time() . '.csv';
    $thead = rbfw_csv_thead();
    $trow = rbfw_csv_tbody();
    
    $fh = @fopen('php://output', 'w');
    fprintf($fh, chr(0xEF) . chr(0xBB) . chr(0xBF));
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Content-Description: File Transfer');
    header('Content-type: text/csv');
    header("Content-Disposition: attachment; filename={$filename}");
    header('Expires: 0');
    header('Pragma: public');
    fputcsv($fh, $thead);
    foreach ($trow as $data_row) {
        fputcsv($fh, $data_row);
    }
    fclose($fh);
    ob_end_flush();
 
    die();
}