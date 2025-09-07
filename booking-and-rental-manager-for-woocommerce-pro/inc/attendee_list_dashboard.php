<?php

add_action('rbfw_admin_menu_after_inventory', 'rbmw_pro_attendee_list_menu');
function rbmw_pro_attendee_list_menu()
{
    add_submenu_page('edit.php?post_type=rbfw_item', __('Reports', 'rbfw-pro'), __('Reports', 'rbfw-pro'), 'manage_options', 'reports', 'rbmw_pro_attendee_list_dashboard');
}

function rbmw_pro_attendee_query($rbfw_id='', $event_date='', $show='',$filter_by='',$ev_filter_key='',$checkin_status='', $start_date = null, $end_date = null, $pickup_location_id = null, $dropoff_location_id = null)
{


   $filter_by = $filter_by ? $filter_by : 'event';

    if( $filter_by != 'event'){
        $rbfw_id = 0;
        $event_date = 0;
    }


    $billing_name_filter = $filter_by == 'name' && !empty($ev_filter_key) ? array(
        'key'     => 'rbfw_billing_name',
        'value'   => $ev_filter_key,
        'compare' => 'LIKE'
    ) : '';


    $email_filter = $filter_by == 'email' && !empty($ev_filter_key) ? array(
        'key'     => 'rbfw_billing_email',
        'value'   => $ev_filter_key,
        'compare' => '='
    ) : '';



    $phone_filter = $filter_by == 'phone' && !empty($ev_filter_key) ? array(
        'key'     => 'rbfw_billing_phone',
        'value'   => $ev_filter_key,
        'compare' => '='
    ) : '';

    $ticket_filter = $filter_by == 'ticket' && !empty($ev_filter_key) ? array(
        'key'     => 'rbfw_pin',
        'value'   => $ev_filter_key,
        'compare' => '='
    ) : '';

    $order_filter = $filter_by == 'order' && !empty($ev_filter_key) ? array(
        'key'     => 'rbfw_order_id',
        'value'   => $ev_filter_key,
        'compare' => '='
    ) : '';




    $item_filter = $rbfw_id > 0 ? array(
        'key'     => 'rbfw_id',
        'value'   => $rbfw_id,
        'compare' => '='
    ) : '';

    $pickup_location_name = !empty($pickup_location_id) ? get_term($pickup_location_id)->name : '';
    $rbfw_pickup_filter = !empty($pickup_location_id) ? array(
        'key'     => 'rbfw_pickup_point',
        'value'   => $pickup_location_name,
        'compare' => 'LIKE'
    ) : '';

    $dropoff_location_name = !empty($dropoff_location_id) ? get_term($dropoff_location_id)->name : '';
    $rbfw_dropoff_filter = !empty($dropoff_location_id) ? array(
        'key'     => 'rbfw_dropoff_point',
        'value'   => $dropoff_location_name,
        'compare' => 'LIKE'
    ) : '';

    if(!empty($start_date)){
        if(!empty($start_date) && empty($end_date)){
            $end_date = date("Y-m-d");
        }
        $dates = rbfw_getBetweenDates($start_date, $end_date);
        $dd_arr = ['relation' => 'OR'];
        if(!empty($dates)){
            foreach ($dates as $date) {
                $dd_arr[] = array(
                    'key'     => 'rbfw_start_datetime',
                    'value'   => $date,
                    'compare' => 'LIKE'
                );
            }
        }
    }else{
        $dd_arr = [];
    }

    $args = array(
        'post_type'         => 'rbfw_order_meta',
        'posts_per_page'    => $show,
        'meta_query'        => array(
            'relation'      => 'AND',
            array(
                'relation'  => 'AND',
                $item_filter,
                $billing_name_filter,
                $email_filter,
                $phone_filter,
                $ticket_filter,
                $order_filter,
                $rbfw_pickup_filter,
                $rbfw_dropoff_filter,

            ),
            $dd_arr,
            array(
                'relation' => 'OR',
                array(
                    'key'     => 'rbfw_order_status',
                    'value'   => 'completed',
                    'compare' => '='
                ),
                array(
                    'key'     => 'rbfw_order_status',
                    'value'   => 'processing',
                    'compare' => '='
                ),
                array(
                    'key'     => 'rbfw_order_status',
                    'value'   => 'partially-paid',
                    'compare' => '='
                )
            )
        )
    );

    $loop = new WP_Query($args);
    return $loop;
}


function rbmw_pro_attendee_table_heading()
{
    $pin_no         = rbfw_get_option('rbfw_purchase_list_ticket_no', 'rbfw_basic_purchase_list_settings', 'off');
    $billing_name   = rbfw_get_option('rbfw_purchase_list_name', 'rbfw_basic_purchase_list_settings', 'on');
    $email          = rbfw_get_option('rbfw_purchase_list_email', 'rbfw_basic_purchase_list_settings', 'on');
    $phone          = rbfw_get_option('rbfw_purchase_list_phone', 'rbfw_basic_purchase_list_settings', 'off');
    $address        = rbfw_get_option('rbfw_purchase_list_address', 'rbfw_basic_purchase_list_settings', 'off');
    $ticket_type    = rbfw_get_option('rbfw_purchase_list_ticket_type', 'rbfw_basic_purchase_list_settings', 'on');
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
?>
<tr>
<?php if($order_no == 'on'){ ?>    <th><?php _e('Order ID', 'rbfw-pro'); ?></th><?php } ?>
<?php if($ticket_type == 'on'){ ?>  <th><?php _e('Item', 'rbfw-pro'); ?></th><?php } ?>
<?php if($item_quantity == 'on'){ ?>  <th><?php _e('Item Quantity', 'rbfw-pro'); ?></th><?php } ?>
<?php if($service == 'on'){ ?><th><?php _e('Service', 'rbfw-pro'); ?></th><?php } ?>
<?php if($extra_service == 'on'){ ?><th><?php _e('Extra Service', 'rbfw-pro'); ?></th><?php } ?>
<?php if($datetime == 'on'){ ?>    <th><?php _e('Start Datetime', 'rbfw-pro'); ?></th><?php } ?>
<?php if($datetime == 'on'){ ?>    <th><?php _e('End Datetime', 'rbfw-pro'); ?></th><?php } ?>
<?php if($pickup_point == 'on'){ ?>    <th><?php _e('Pickup Point', 'rbfw-pro'); ?></th><?php } ?>
<?php if($dropoff_point == 'on'){ ?>    <th><?php _e('Drop-off Point', 'rbfw-pro'); ?></th><?php } ?>
<?php if($billing_name == 'on'){ ?>         <th><?php _e('Full Name', 'rbfw-pro'); ?></th> <?php } ?>
<?php if($email == 'on'){ ?>        <th><?php _e('Email', 'rbfw-pro'); ?></th> <?php } ?>
<?php if($phone == 'on'){ ?>        <th><?php _e('Phone', 'rbfw-pro'); ?></th> <?php } ?>
<?php if($address == 'on'){ ?>        <th><?php _e('Address', 'rbfw-pro'); ?></th> <?php } ?>
<?php if($pin_no == 'on'){ ?>    <th><?php _e('PIN', 'rbfw-pro'); ?></th> <?php } ?>
<?php if($order_st == 'on'){ ?>    <th><?php _e('Order Status', 'rbfw-pro'); ?></th><?php } ?>
<?php if($paid == 'on'){ ?>    <th><?php _e('Paid Amount', 'rbfw-pro'); ?></th><?php } ?>
<?php if($pmethod == 'on'){ ?><th><?php _e('Payment Method', 'rbfw-pro'); ?></th><?php } ?>

        <?php do_action('mep_attendee_list_heading'); ?>
        <th><?php _e('Action', 'rbfw-pro'); ?></th>
    </tr>
<?php
}


function rbmw_pro_attendee_list_items($rbfw_order_id)
{

    global $rbfw;
    $pin_no         = rbfw_get_option('rbfw_purchase_list_ticket_no', 'rbfw_basic_purchase_list_settings', 'off');
    $billing_name   = rbfw_get_option('rbfw_purchase_list_name', 'rbfw_basic_purchase_list_settings', 'on');
    $email          = rbfw_get_option('rbfw_purchase_list_email', 'rbfw_basic_purchase_list_settings', 'on');
    $phone          = rbfw_get_option('rbfw_purchase_list_phone', 'rbfw_basic_purchase_list_settings', 'off');
    $address        = rbfw_get_option('rbfw_purchase_list_address', 'rbfw_basic_purchase_list_settings', 'off');
    $ticket_type    = rbfw_get_option('rbfw_purchase_list_ticket_type', 'rbfw_basic_purchase_list_settings', 'on');
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


    $wc_order_id = get_post_meta($rbfw_order_id, 'rbfw_link_order_id', true);
    $pin            = get_post_meta($rbfw_order_id, 'rbfw_pin', true);

    //$rbfw_id =  get_post_meta($rbfw_order_id, 'rbfw_id', true);

   // $wc_order_details = wc_get_order($wc_order_id);

    $attendee_id = $rbfw_order_id;

    $order_id = get_post_meta($attendee_id, 'rbfw_order_id', true);
    $wc_link_order_id = get_post_meta($order_id, '_rbfw_link_order_id', true);
    $link_order_id = $wc_link_order_id;

    $download_url  = rbfw_get_invoice_ajax_url( array( 'order_id' =>  $link_order_id ) );


    $product_id =  get_post_meta($attendee_id, 'rbfw_id', true);

    $all_data = get_post_meta($attendee_id);

    //echo '<pre>';print_r($all_data);echo '<pre>';


    $ticket_item_name = !empty(get_post_meta($attendee_id,'ticket_name',true)) ? get_post_meta($attendee_id,'ticket_name',true) : '';
    $ticket_start_datetime = !empty(get_post_meta($attendee_id,'rbfw_start_datetime',true)) ? rbfw_get_datetime(get_post_meta($attendee_id,'rbfw_start_datetime',true), 'date-time-text') : '';
    $ticket_end_datetime = !empty(get_post_meta($attendee_id,'rbfw_end_datetime',true)) ? rbfw_get_datetime(get_post_meta($attendee_id,'rbfw_end_datetime',true), 'date-time-text') : '';
    $ticket_pickup_point = !empty(get_post_meta($attendee_id,'rbfw_pickup_point',true)) ? get_post_meta($attendee_id,'rbfw_pickup_point',true) : '';
    $ticket_dropoff_point = !empty(get_post_meta($attendee_id,'rbfw_dropoff_point',true)) ? get_post_meta($attendee_id,'rbfw_dropoff_point',true) : '';
    $ticket_ext_services = !empty(get_post_meta($attendee_id,'rbfw_service_info',true)) ? get_post_meta($attendee_id,'rbfw_service_info',true) : [];
    $view_ext_service = '';
    $view_ext_service_popup_id = rand();
    $ticket_view_rent_info = '';
    $ticket_rent_info = !empty(get_post_meta($attendee_id,'rbfw_type_info',true)) ? get_post_meta($attendee_id,'rbfw_type_info',true) : [];
    $ticket_item_quantity = !empty(get_post_meta($attendee_id,'rbfw_item_quantity',true)) ? get_post_meta($attendee_id,'rbfw_item_quantity',true) : [];
    $total_days = !empty(get_post_meta($attendee_id,'total_days',true)) ? get_post_meta($attendee_id,'total_days',true) : 1;
    $duration_qty = !empty(get_post_meta($attendee_id,'duration_qty',true)) ? get_post_meta($attendee_id,'duration_qty',true) : 1;



    ?>

    <tr class='attendee_<?php echo $attendee_id; ?>'>
        <?php if($order_no == 'on'){ ?> <td><?php echo $order_id; ?></td> <?php } ?>
        <?php if($ticket_type == 'on'){ ?> <td><?php echo $ticket_item_name; ?></td> <?php } ?>
        <?php if($item_quantity == 'on'){ ?>
            <td>
                <?php
                echo is_array($ticket_item_quantity) ? implode(', ', $ticket_item_quantity) : rtrim($ticket_item_quantity);
                ?>
            </td>
        <?php } ?>

        <?php

        $rbfw_rent_type =  get_post_meta($attendee_id, 'rbfw_rent_type', true);

        if($rbfw_rent_type != 'multiple_items'){
            if (!empty($ticket_rent_info)) {
                $ticket_view_rent_info = '<ul class="rental-list">';
                foreach ($ticket_rent_info as $name => $qty) {
                    $ticket_view_rent_info .= '<li>' . esc_html($name) . ' <strong>x ' . esc_html($qty) . '</strong></li>';
                }
                $ticket_view_rent_info .= '</ul>';
            }
            if(!empty($ticket_ext_services)){
                $view_ext_service.= '<div id="rbfw_psg_ext_serv_wrap_'.$view_ext_service_popup_id.'" class="mage_modal">';
                $view_ext_service.= '<table class="wp-list-table widefat striped posts">';
                $view_ext_service.= '<tr><th><strong>'.__('Extra Services:','rbfw-pro').'</strong></th></tr>';
                foreach ($ticket_ext_services as $service_name => $service_qty) {
                    $view_ext_service.= '<tr><td>'.$service_name.' x '.$service_qty.'</td></tr>';
                }
                $view_ext_service.= '</table>';
                $view_ext_service.= '</div>';
            }
            ?>
            <?php if($service == 'on'){ ?>
                <td><?php echo ($ticket_view_rent_info) ?></td>
            <?php } ?>

            <?php if($extra_service == 'on'){ ?>
                <td>
                    <?php  if(!empty($ticket_ext_services)){ ?>
                        <a href="#rbfw_psg_ext_serv_wrap_<?php echo $view_ext_service_popup_id; ?>" rel="mage_modal:open">
                            <?php _e('View','rbfw-pro'); ?>
                        </a>
                    <?php  } ?>
                    <?php  echo $view_ext_service;?>
                </td>
            <?php } ?>
        <?php }else{

            $multiple_items_info = get_post_meta($attendee_id, 'multiple_items_info', true);
            $rbfw_category_wise_info = get_post_meta($attendee_id, 'rbfw_category_wise_info', true);
            if ( ! empty( $multiple_items_info ) ){

                ?>

        <td>
            <table>

                    <?php

                foreach ($multiple_items_info as $key => $value){
                    ?>
                    <tr>
                        <th>
                            <?php echo esc_html($value['item_name']); ?>:
                        </th>
                        <td>(<?php echo wp_kses(wc_price($value['item_price']),rbfw_allowed_html()); ?> x <?php echo esc_html($value['item_qty']); ?> x <?php echo esc_html($duration_qty); ?>) = <?php echo wp_kses(wc_price($value['item_price'] * $value['item_qty'] * $duration_qty),rbfw_allowed_html()); ?></td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </td>


                <?php
            }
            if ( ! empty( $rbfw_category_wise_info ) ){ ?>
                <td>
                    <table>
                <?php
                foreach ($rbfw_category_wise_info as $key => $value){ ?>
                    <tr>
                        <th><?php echo esc_html($value['cat_title']); ?> </th>
                        <td>
                            <table>
                                <?php foreach ($value as $item){ ?>
                                    <?php if(isset($item['name'])){ ?>
                                        <tr>
                                            <td><?php echo esc_html($item['name']); ?></td>
                                            <td>
                                                <?php
                                                if($item['service_price_type']=='day_wise'){
                                                    echo '('.wp_kses(wc_price($item['price']),rbfw_allowed_html()). 'x'. esc_html($item['quantity']) . 'x' .esc_html($total_days) .'='.wp_kses(wc_price($item['price']*(int)$item['quantity']*$total_days),rbfw_allowed_html()).')';
                                                }else{
                                                    echo ('('.wp_kses(wc_price($item['price']),rbfw_allowed_html()). 'x'. esc_html($item['quantity']) .'='.wp_kses(wc_price($item['price']*$item['quantity']),rbfw_allowed_html())).')';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            </table>
                        </td>
                    </tr>
                <?php } ?>
                </table>
                    </td>
            <?php } ?>





        <?php } ?>

        <?php if($datetime == 'on'){ ?> <td><?php echo $ticket_start_datetime; ?></td> <?php } ?>
        <?php if($datetime == 'on'){ ?> <td><?php echo $ticket_end_datetime; ?></td> <?php } ?>
        <?php if($pickup_point == 'on'){ ?> <td><?php echo $ticket_pickup_point; ?></td><?php } ?>
        <?php if($dropoff_point == 'on'){ ?><td><?php echo $ticket_dropoff_point; ?></td><?php } ?>
        <?php if($billing_name == 'on'){ ?> <td><?php  echo get_post_meta($attendee_id, 'rbfw_billing_name', true); ?></td> <?php } ?>
        <?php if($email == 'on'){ ?> <td><?php echo get_post_meta($attendee_id, 'rbfw_billing_email', true); ?></td> <?php } ?>
        <?php if($phone == 'on'){ ?> <td><?php echo get_post_meta($attendee_id, 'rbfw_billing_phone', true); ?></td> <?php } ?>
        <?php if($address == 'on'){ ?> <td><?php echo get_post_meta($attendee_id, 'rbfw_billing_address', true); ?></td> <?php } ?>
        <?php if($pin_no == 'on'){ ?>  <td><?php echo $pin; ?></td>  <?php } ?>
        <?php if($order_st == 'on'){ ?> <td><?php echo get_post_meta($attendee_id, 'rbfw_order_status', true); ?></td> <?php } ?>
        <?php if($paid == 'on'){ ?> <td><?php echo wc_price(get_post_meta($attendee_id, 'ticket_price', true)); ?></td> <?php } ?>
        <?php if($pmethod == 'on') { $order = wc_get_order($order_id); $payment_method = $order ? $order->get_payment_method_title() : __('Order not found', 'rbfw-pro'); ?> <td><?php echo esc_html($payment_method); ?></td> <?php } ?>
        <?php do_action('mep_attendee_list_item', $attendee_id); ?>
        <td>
            <a class="_mpBtn_themeButton_xs" id="download_attendee_pdf" target='_blank' href="<?php echo esc_attr( $download_url ); ?>" title="<?php esc_html_e( 'Download Pdf.', 'rbfw-pro' ); ?>"><span class="dashicons dashicons-tickets-alt mp_zero"></span></a>
            <?php /*do_action('mep_attendee_list_item_action_middile', $attendee_id); */?><!--
            <span  title='Delete' class="mep_del_attendee" data-id=<?php /*echo $attendee_id; */?> order-id=<?php /*echo $attendee_id; */?> item-id=<?php /*echo $product_id; */?>><span class="dashicons dashicons-trash"></span></span>
            --><?php /*do_action('mep_attendee_list_item_action_after', $attendee_id); */?>
        </td>
    </tr>

<?php
}

function rbfw_attendee_query_stat($a_query, $attendee_id, $event_date, $checkin_status = 'all')
{
    // echo $attendee_id;
    $attendee_count = $a_query->post_count;
    $total_attendee_count = $a_query->found_posts;
?>
    <ul class="attendee_stat">
        <li class='total_attendee'>
            <?php _e('Total ', 'rbfw-pro');
            echo $total_attendee_count;
            _e(' Data Found', 'rbfw-pro'); ?>
        </li>
        <li class='showing_attendee'>
            <?php _e('Showing ', 'rbfw-pro');
            echo $attendee_count;
            _e(' Data', 'rbfw-pro'); ?>
        </li>
        <li class='attendee_export_btn'>
            <?php if ($total_attendee_count > 0) { ?>
                <a class='mep_export_csv' id="mep_export_customer_list" ><i class="fa-solid fa-cloud-arrow-down"></i> <?php _e('Export Reports','rbfw-pro'); ?></a>
            <?php } ?>
        </li>
    </ul>
<?php
}

function rbmw_pro_attendee_list_dashboard()
{
    $attendee_id = isset($_REQUEST['event_id']) ? strip_tags($_REQUEST['event_id']) : 0;
    $event_date = isset($_REQUEST['ea_event_date']) ? strip_tags($_REQUEST['ea_event_date']) : 0;

?>
    <div class="wrap">
        <h2><?php _e('Reports', 'rbfw-pro'); ?></h2>

        <div class='attendee_filter_section'>
            <ul>
                <li><?php _e('Filter List By:', 'rbfw-pro'); ?></li>
                <li><label for="event_filter"><input class='mep_attn_filter_by' type="radio" name='attendee_filter_by' id='event_filter' value='event' checked><?php _e('Item','rbfw-pro'); ?></label></li>
                <li><label for="name_filter"><input class='mep_attn_filter_by' type="radio" name='attendee_filter_by' id='name_filter' value='name'><?php _e('Name','rbfw-pro'); ?></label></li>
                <li><label for="order_filter"><input class='mep_attn_filter_by' type="radio" name='attendee_filter_by' id='order_filter' value='order'><?php _e('Order','rbfw-pro'); ?></label></li>
                <li><label for="phone_filter"><input class='mep_attn_filter_by' type="radio" name='attendee_filter_by' id='phone_filter' value='phone'><?php _e('Phone','rbfw-pro'); ?></label></li>
                <li><label for="email_filter"><input class='mep_attn_filter_by' type="radio" name='attendee_filter_by' id='email_filter' value='email'><?php _e('Email','rbfw-pro'); ?></label></li>
            </ul>

            <ul>
                <li>
                    <div class='event_filter'>
                        <div class='attendee_filter'>
                            <label for="mep_event_id"><span><?php esc_html_e('Item','rbfw-pro'); ?></span>
                                <select name="event_id" id="mep_event_id" class="select2" required>
                                    <option value="0"><?php _e('Select Item', 'rbfw-pro'); ?></option>
                                    <?php
                                    $args = array(
                                        'post_type' => 'rbfw_item',
                                        'posts_per_page' => -1
                                    );
                                    $loop = new WP_Query($args);
                                    $events_query = $loop->posts;
                                    foreach ($events_query as $event) { ?>
                                        <option value="<?php echo $event->ID; ?>" <?php if ($attendee_id == $event->ID) {  echo 'selected'; } ?>><?php echo get_the_title($event->ID); ?></option>
                                    <?php } ?>
                                </select>
                            </label>
                        </div>
                        <div class='attendee_filter'>
                            <label for="attendee_start_date_filter"><span><?php esc_html_e('From','rbfw-pro'); ?></span>
                            <input type="text" value='' id='attendee_start_date_filter' name='attendee_start_date_filter' placeholder="yyyy-mm-dd">
                            </label>
                            <label for="attendee_end_date_filter"><span><?php esc_html_e('To','rbfw-pro'); ?></span>
                            <input type="text" value='' name='attendee_end_date_filter' id='attendee_end_date_filter' placeholder="yyyy-mm-dd">
                            </label>
                        </div>
                        <div class=' attendee_filter' >
                            <?php
                            $taxonomy = get_terms( array(
                                'taxonomy' => 'rbfw_item_location',
                                'hide_empty' => false
                            ) );
                            ?>
                            <label for="attendee_pickup_location_filter"><span><?php esc_html_e('Pickup Location','rbfw-pro'); ?></span>
                                <select id="attendee_pickup_location_filter" name="attendee_pickup_location_filter">
                                    <option value=""><?php _e('Select Pickup Location', 'rbfw-pro'); ?></option>
                                    <?php foreach ($taxonomy as $term) { ?>
                                        <option value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
                                    <?php } ?>
                                </select>
                            </label>
                            <label for="attendee_dropoff_location_filter"><span><?php esc_html_e('Drop-off Location','rbfw-pro'); ?></span>
                                <select id="attendee_dropoff_location_filter" name="attendee_dropoff_location_filter">
                                    <option value=""><?php _e('Select Drop-off Location', 'rbfw-pro'); ?></option>
                                    <?php foreach ($taxonomy as $term) { ?>
                                    <option value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
                                    <?php } ?>
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class='attendee_key_filter' style="display: none;">
                        <input type="text" name='filter_key' value='' id='attendee_filter_key'>
                    </div>
                </li>
                <li id='filter_attitional_btn'>
                    <input type="hidden" id='mep_everyday_ticket_time' name='mep_attendee_list_filter_event_date' value='<?php echo $event_date; ?>'>
                </li>
                <?php do_action('mep_attendee_list_filter_form_before_btn'); ?>
            </ul>
            <div class="attendee_filter_btn_wrap">
                <button id='event_attendee_filter_btn'><?php _e('Filter','rbfw-pro'); ?></button>
            </div>
        </div>
        <div id='before_attendee_table_info'></div>

        <div id='event_attendee_list_table_item'>
            <?php

            $a_query = rbmw_pro_attendee_query('', '', 50);

            $attendee_query = $a_query->posts;

            rbfw_attendee_query_stat($a_query, $attendee_id, $event_date);

            $total_customer = count($attendee_query);
            ?>

            <table class="wp-list-table widefat striped posts">
                <thead>
                    <?php rbmw_pro_attendee_table_heading(); ?>
                </thead>
                <tbody>
                    <?php
                    if(!empty($total_customer)){
                        foreach ($attendee_query as $_attendee) {
                            $rbfw_order_id = $_attendee->ID;
                            rbmw_pro_attendee_list_items($rbfw_order_id);
                        }
                    }else{
                        ?>
                        <tr>
                            <td colspan="16" class="text_center"><?php _e('No data found!','rbfw-pro'); ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

<?php
}

add_action('admin_footer','rbfw_purchase_list_script');

function rbfw_purchase_list_script(){

    $loader_url = RBMW_PLUGIN_URL_PRO. '/images/spinner.gif';
    ?> 
    <script>
            jQuery(document).ready(function(jQuery) {
                
                <?php do_action('rbmw_pro_attendee_list_script'); ?>

                jQuery( "#attendee_start_date_filter" ).datepicker({
                            dateFormat: "yy-mm-dd"
                });
                jQuery('body').on('change', '#attendee_start_date_filter', function(e) {

                    let selected_date = jQuery(this).val();
                    const [gYear, gMonth, gDay] = selected_date.split('-');
                    jQuery("#attendee_end_date_filter").datepicker("destroy");
                    jQuery('#attendee_end_date_filter').datepicker({
                        dateFormat: 'yy-mm-dd',
                        minDate: new Date(gYear, gMonth - 1, gDay),
                    });
                });

                jQuery(document).on('click', '.mep_sync_data', function() {
                    var event_id = jQuery(this).data('id');
                    // alert(event_id);
                    if (event_id > 0) {
                        jQuery.ajax({
                            type: 'POST',
                            url: ajaxurl,
                            // url: ajaxurl,
                            data: {
                                "action": "rbmw_pro_ajax_attendee_sync",
                                "attendee_id": event_id
                            },
                            beforeSend: function() {
                                jQuery('#before_attendee_table_info').html('<h5 class="mep-processing"><?php _e('Please wait! Attendee data synchronizing from order data','rbfw-pro'); ?></h5>');
                            },
                            success: function(data) {
                                jQuery('#before_attendee_table_info').html(data);
                                window.location.reload();
                            }
                        });
                    } 
                    return false;
                });

                jQuery('#event_attendee_filter_btn').on('click', function() {
                    var event_id = jQuery('#mep_event_id').val();

                    // if (event_id > 0) {
                        var filter_by       = jQuery("input[name='attendee_filter_by']:checked").val();
                        var ev_filter_key   = jQuery('#attendee_filter_key').val();
                        var ev_event_date   = jQuery('#mep_everyday_ticket_time').val();
                        var re_event_date   = jQuery('#mep_recurring_date').val();
                        var re_event_datepicker   = jQuery('#mep_everyday_datepicker').val();
                        var checkin_status  = jQuery('#mep_attendee_checkin').val() ? jQuery('#mep_attendee_checkin').val() : '';
                        var event_date_t      = re_event_date ? re_event_date : ev_event_date;
                        var event_date      = event_date_t != 0 && event_date_t ? event_date_t : re_event_datepicker;
                        
                        // alert(event_date);
                        let attendee_start_date          = jQuery('#attendee_start_date_filter').val();
                        let attendee_end_date            = jQuery('#attendee_end_date_filter').val();
                        let attendee_pickup_location_id     = jQuery('#attendee_pickup_location_filter').val();
                        let attendee_dropoff_location_id    = jQuery('#attendee_dropoff_location_filter').val();

                        jQuery.ajax({
                            type: 'POST',
                            url: ajaxurl,
                            // url: ajaxurl,
                            data: {
                                "action": "rbmw_pro_ajax_attendee_filter",
                                "filter_by": filter_by,
                                "ev_filter_key": ev_filter_key,
                                "event_date": event_date,
                                "checkin_status": checkin_status,
                                "event_id": event_id,
                                "attendee_start_date": attendee_start_date,
                                "attendee_end_date": attendee_end_date,
                                "attendee_pickup_location_id": attendee_pickup_location_id,
                                "attendee_dropoff_location_id": attendee_dropoff_location_id
                            },
                            beforeSend: function() {
                                jQuery('#event_attendee_list_table_item').html('<img class="rbfw-loader" src="<?php echo $loader_url; ?>"/>');
                            },
                            success: function(data) {
                                jQuery('#event_attendee_list_table_item').html(data);
                                rbfw_export_customer_list();
                               
                            }
                        });
                    // } else {
                    //     alert('Please Select a Event From The List');
                    // }
                    return false;
                });

                function rbfw_export_customer_list(){
                    jQuery('#mep_export_customer_list').on('click', function(e) {
                        e.preventDefault();
                        
                        var event_id        = jQuery('#mep_event_id').val();
                        var filter_by       = jQuery("input[name='attendee_filter_by']:checked").val();
                        var ev_filter_key   = jQuery('#attendee_filter_key').val();
                        let attendee_start_date          = jQuery('#attendee_start_date_filter').val();
                        let attendee_end_date            = jQuery('#attendee_end_date_filter').val();
                        let attendee_pickup_location_id  = jQuery('#attendee_pickup_location_filter').val();
                        let attendee_dropoff_location_id = jQuery('#attendee_dropoff_location_filter').val();

                        var currentUrl = "<?php echo admin_url(); ?>edit.php?post_type=rbfw_item&page=reports&action=export_customer_list";
                        var url = new URL(currentUrl);
                        url.searchParams.set('event_id', event_id);
                        url.searchParams.set('filter_by', filter_by);
                        url.searchParams.set('ev_filter_key', ev_filter_key);
                        url.searchParams.set('attendee_start_date', attendee_start_date);
                        url.searchParams.set('attendee_end_date', attendee_end_date);
                        url.searchParams.set('attendee_pickup_location_id', attendee_pickup_location_id);
                        url.searchParams.set('attendee_dropoff_location_id', attendee_dropoff_location_id);
                        var newUrl = url.href;
                        window.location.href = newUrl;
                    });
                }
                rbfw_export_customer_list();
                

                jQuery(document).on('click', '.mep_del_attendee', function() {

                    var attendee_id = jQuery(this).attr("data-id");
                    var order_id = jQuery(this).attr("order-id");
                    var item_id = jQuery(this).attr("item-id");
                    jQuery.ajax({
                        type: 'POST',
                        // url: mep_ajax.mep_ajaxurl,
                        url: ajaxurl,
                        data: {
                            "action": "rbmw_pro_ajax_attendee_delete",
                            "attendee_id": attendee_id,
                            "order_id": order_id,
                            "item_id": item_id
                        },
                        beforeSend: function() {
                        },
                        success: function(data) {
                            jQuery('.attendee_' + attendee_id).hide();                     
                        }
                    });
                    return false;
                });


                jQuery(document).on('click', '.mep_attn_filter_by', function() {

                   var filter_by = jQuery("input[name='attendee_filter_by']:checked").val();
                
                   if(filter_by == 'event'){
                    jQuery('.event_filter').show();
                    jQuery('#filter_attitional_btn').show();
                    jQuery('.attendee_key_filter').hide();
                    jQuery('.attendee_date_filter').hide();
                    jQuery('.attendee_location_filter').hide();
                       
                   }else if(filter_by == 'date'){
                    jQuery('.event_filter').hide();
                    jQuery('#filter_attitional_btn').hide();
                    jQuery('.attendee_key_filter').hide();
                    jQuery('.attendee_location_filter').hide();
                    jQuery('.attendee_date_filter').show();
                   }else if(filter_by == 'location'){
                    jQuery('.event_filter').hide();
                    jQuery('#filter_attitional_btn').hide();
                    jQuery('.attendee_key_filter').hide();
                    jQuery('.attendee_date_filter').hide();
                    jQuery('.attendee_location_filter').show();
                   }else{
                    jQuery('.event_filter').hide();
                    jQuery('#filter_attitional_btn').hide();
                    jQuery('.attendee_date_filter').hide();
                    jQuery('.attendee_location_filter').hide();
                    jQuery('.attendee_key_filter').show();
                   }     

                });

            });
      
    </script>    
    <?php
}

add_action('wp_ajax_rbmw_pro_ajax_attendee_sync', 'rbmw_pro_ajax_attendee_sync');
add_action('wp_ajax_nopriv_rbmw_pro_ajax_attendee_sync', 'rbmw_pro_ajax_attendee_sync');
function rbmw_pro_ajax_attendee_sync()
{
    $attendee_id               = $_REQUEST['attendee_id'];
    $order_id                  = get_post_meta( $attendee_id, 'ea_order_id', true );
    // rbmw_pro_attendee_data_sync_from_order_meta($order_id,$attendee_id);
    die();
}


add_action('wp_ajax_rbmw_pro_ajax_attendee_filter', 'rbmw_pro_ajax_attendee_filter');
add_action('wp_ajax_nopriv_rbmw_pro_ajax_attendee_filter', 'rbmw_pro_ajax_attendee_filter');

function rbmw_pro_ajax_attendee_filter()
{
    $attendee_id               = isset($_REQUEST['event_id']) ? $_REQUEST['event_id'] : '';
    $event_date             = isset($_REQUEST['event_date']) ? $_REQUEST['event_date'] : '';
    $filter_by              = isset($_REQUEST['filter_by']) ? $_REQUEST['filter_by'] : '';
    $ev_filter_key          = isset($_REQUEST['ev_filter_key']) ? $_REQUEST['ev_filter_key'] : '';
    $checkin_status         = isset($_REQUEST['checkin_status']) ? $_REQUEST['checkin_status'] : '';

    $start_date = isset($_POST['attendee_start_date']) ? $_POST['attendee_start_date'] : '';
    $end_date = isset($_POST['attendee_end_date']) ? $_POST['attendee_end_date'] : '';
    $pickup_location_id = isset($_POST['attendee_pickup_location_id']) ? $_POST['attendee_pickup_location_id'] : '';
    $dropoff_location_id = isset($_POST['attendee_dropoff_location_id']) ? $_POST['attendee_dropoff_location_id'] : '';

    $a_query = rbmw_pro_attendee_query($attendee_id, $event_date, -1,$filter_by,$ev_filter_key,$checkin_status, $start_date, $end_date, $pickup_location_id, $dropoff_location_id);
    // echo '<pre>'; print_r($a_query); echo '</pre>';
    $attendee_query = $a_query->posts;
    $total_customer = count($attendee_query);
    rbfw_attendee_query_stat($a_query, $attendee_id, $event_date, $checkin_status);
?>
    <table class="wp-list-table widefat striped posts">
        <thead>
            <?php rbmw_pro_attendee_table_heading(); ?>
        </thead>
        <tbody>
            <?php
            if(!empty($total_customer)){
                foreach ($attendee_query as $_attendee) {
                    $attendee_id = $_attendee->ID;
                    rbmw_pro_attendee_list_items($attendee_id);
                }
            }else{
                ?>
                <tr>
                    <td colspan="16" class="text_center"><?php _e('Sorry, No data found!','rbfw-pro'); ?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
<?php

    wp_die();
}

/*
function rbmw_pro_ajax_attendee_filter()
{
    $item_id                = isset($_REQUEST['event_id']) ? $_REQUEST['event_id'] : '';
    $event_date             = isset($_REQUEST['event_date']) ? $_REQUEST['event_date'] : '';
    $filter_by              = isset($_REQUEST['filter_by']) ? $_REQUEST['filter_by'] : '';
    $ev_filter_key          = isset($_REQUEST['ev_filter_key']) ? $_REQUEST['ev_filter_key'] : '';
    $checkin_status         = isset($_REQUEST['checkin_status']) ? $_REQUEST['checkin_status'] : '';

    $start_date = isset($_POST['attendee_start_date']) ? $_POST['attendee_start_date'] : '';
    $end_date = isset($_POST['attendee_end_date']) ? $_POST['attendee_end_date'] : '';
    $pickup_location_id = isset($_POST['attendee_pickup_location_id']) ? $_POST['attendee_pickup_location_id'] : '';
    $dropoff_location_id = isset($_POST['attendee_dropoff_location_id']) ? $_POST['attendee_dropoff_location_id'] : '';

   $args = array(
    'post_type'         => 'rbfw_order',
    'posts_per_page'    => -1,
    );

    $query = new WP_Query($args);

?>
    <table class="wp-list-table widefat striped posts">
        <thead>
            <?php rbmw_pro_attendee_table_heading(); ?>
        </thead>
        <tbody>
            <?php
            $matched_id = [];
            if($query->have_posts()){
                while ( $query->have_posts() ) : $query->the_post();
                    global $post;
                    $post_id = $post->ID;
                    $ticket_types = !empty(get_post_meta($post_id, 'rbfw_ticket_info', true)) ? get_post_meta($post_id, 'rbfw_ticket_info', true) : [];
                    $i = 0;
                    foreach ($ticket_types as $ticket_type) {
                        $filter_item_name = get_the_title($item_id);
                        $ticket_item_name = !empty($ticket_type['ticket_name']) ? $ticket_type['ticket_name'] : '';

                        if($filter_item_name == $ticket_item_name){
                            $matched_id[$i] = $item_id;
                        }
                        $i++;
                    }
                    
                endwhile;
            }
            wp_reset_postdata();
            ?>
        </tbody>
    </table>
<?php

    wp_die();
}
*/


add_action('wp_ajax_rbmw_pro_ajax_attendee_delete', 'rbmw_pro_ajax_attendee_delete');
add_action('wp_ajax_nopriv_rbmw_pro_ajax_attendee_delete', 'rbmw_pro_ajax_attendee_delete');
function rbmw_pro_ajax_attendee_delete()
{
    $attendee_id            = $_REQUEST['attendee_id'];
    $order_id               = $_REQUEST['order_id'];
    $itemm_id               = $_REQUEST['item_id'];

    // Note: If you want to delete this item from order, just uncomment the below lines.
    /* 
    $order = wc_get_order($order_id);
    foreach ($order->get_items() as $item_id => $item) {
    $pro_id = wc_get_order_item_meta($item_id, '_rbfw_id', true);
    
    if ($pro_id == $itemm_id) {
        wc_delete_order_item($item_id);
    }
    }
    */

    wp_delete_post($attendee_id);
    
    wp_die();
}
