<?php
	add_action( 'admin_head', 'rbmw_ajax_url', 5 );
function rbmw_ajax_url() {
	?>
	<script type="text/javascript">
		  let rbmw_ajax_url = "<?php echo admin_url( 'admin-ajax.php' ); ?>";
	</script>
	<?php
}
	add_action( 'wp_ajax_get_rbmw_pro_booking_in_calender','get_rbmw_pro_booking_in_calender' );
	add_action( 'wp_ajax_nopriv_get_rbmw_pro_booking_in_calender','get_rbmw_pro_booking_in_calender' );
	function get_rbmw_pro_booking_in_calender(){
		rbmw_pro_booking_in_calender();
		die();
	}
	add_action( 'wp_ajax_get_rbmw_pro_booking_in_calender_list','get_rbmw_pro_booking_in_calender_list' );
	add_action( 'wp_ajax_nopriv_get_rbmw_pro_booking_in_calender_list','get_rbmw_pro_booking_in_calender_list' );
	function get_rbmw_pro_booking_in_calender_list(){
        global $rbfw;
        $today = mktime(0, 0, 0);
		$date = !empty($_POST['date']) ? $_POST['date'] : $today;
		$event_id = !empty($_POST['event_id']) ? $_POST['event_id'] : 0;
		$q_date = date('Y-m-d',$date);
		$query=rbmw_pro_booking_query($q_date,$event_id);
		$attendee_query = $query->posts;
		?>
        <div class="order_list_area">
			<table>
				<thead>
                <tr>
                    <th><?php _e('Item', 'rbfw-pro'); ?></th>
                    <th><?php _e('Item Information', 'rbfw-pro'); ?></th>
                    <th><?php _e('Order ID', 'rbfw-pro'); ?></th>
                    <th><?php _e('Status', 'rbfw-pro'); ?></th>
                    <th><?php _e('Start Date', 'rbfw-pro'); ?></th>
                    <th><?php _e('End Date', 'rbfw-pro'); ?></th>
                    <?php do_action('mep_attendee_list_heading'); ?>
                </tr>
				</thead>
				<tbody>
                <?php
                foreach ($attendee_query as $_attendee) {
                    $attendee_id = $_attendee->ID;

                   $rbfw_item_quantity = get_post_meta($attendee_id , 'rbfw_item_quantity' ,true);

                    $order_id = get_post_meta($attendee_id, 'rbfw_link_order_id', true);
                    $order = wc_get_order($order_id);
                    $status              = ( $order && $order->get_status() === 'trash')? $order->get_status() : get_post_meta( $post_id, 'rbfw_order_status', true );


                    ?>
                    <tr class='attendee_<?php echo $attendee_id; ?>'>
                        <td><?php echo get_post_meta($attendee_id, 'ticket_name', true) ?></td>
                        <td>
                            <?php

                            $item_id = get_post_meta($attendee_id, 'rbfw_id', true) ;
                            $rent_type = get_post_meta($attendee_id, 'rbfw_rent_type', true);
                            $tax_status = '';

                            $service_info = get_post_meta($attendee_id, 'rbfw_service_info', true);
                            $service_info = !empty($service_info) ? $service_info : [];

                            if($rent_type == 'bike_car_sd' || $rent_type == 'appointment'){


                                $BikeCarSdClass = new RBFW_BikeCarSd_Function();

                                $rent_info = get_post_meta($attendee_id, 'rbfw_type_info', true);
                                $rent_info = !empty($rent_info) ? $rent_info : [];
                                $rent_info = $BikeCarSdClass->rbfw_get_bikecarsd_rent_info($item_id, $rent_info , get_post_meta($attendee_id, 'rbfw_start_date', true));

                                $service_info = $BikeCarSdClass->rbfw_get_bikecarsd_service_info($item_id, $service_info);

                                $pickup_point = get_post_meta($attendee_id, 'rbfw_pickup_point', true);
                                $dropoff_point = get_post_meta($attendee_id, 'rbfw_dropoff_point', true);

                            }elseif($rent_type == 'bike_car_md' || $rent_type == 'dress' || $rent_type == 'equipment' || $rent_type == 'others'){
                                $BikeCarMdClass = new RBFW_BikeCarMd_Function();
                                $service_info = $BikeCarMdClass->rbfw_get_bikecarmd_service_info($item_id, $service_info);
                                $service_infos = get_post_meta($attendee_id, 'rbfw_service_infos', true);

                                $variation_info = get_post_meta($attendee_id, 'rbfw_variation_info', true);
                                $variation_info = !empty($variation_info) ? $variation_info : [];

                                $pickup_point = get_post_meta($attendee_id, 'rbfw_pickup_point', true);
                                $dropoff_point = get_post_meta($attendee_id, 'rbfw_dropoff_point', true);
                                $rbfw_item_quantity = get_post_meta($attendee_id, 'rbfw_item_quantity', true);

                                $total_days = get_post_meta($attendee_id, 'total_days', true);
                            }elseif($rent_type == 'resort'){
                                $ResortClass = new RBFW_Resort_Function();
                                $package = get_post_meta($attendee_id, 'rbfw_resort_package', true);
                                $rent_info = get_post_meta($attendee_id, 'rbfw_type_info', true);
                                $rent_info = !empty($rent_info) ? $rent_info : [];
                                $rent_info  = $ResortClass->rbfw_get_resort_room_info($item_id, $rent_info, $package);
                                $service_info = $ResortClass->rbfw_get_resort_service_info($item_id, $service_info);
                            }

                            $duration_cost = get_post_meta($attendee_id, 'duration_cost', true);
                            $service_cost = get_post_meta($attendee_id, 'service_cost', true);
                            $total_cost = get_post_meta($attendee_id, 'ticket_price', true);
                            $discount_amount = get_post_meta($attendee_id, 'discount_amount', true);
                            $security_deposit_amount = get_post_meta($attendee_id, 'security_deposit_amount', true);
                            $security_deposit_amount = !empty($security_deposit_amount) ? (float)$security_deposit_amount: 0;
                            $discount_type = get_post_meta($attendee_id, 'discount_type', true);
                            $rbfw_regf_info = get_post_meta($attendee_id, 'rbfw_regf_info', true);
                            $rbfw_regf_info = !empty($rbfw_regf_info) ? $rbfw_regf_info : [];

                            /* End  loop*/
                            ?>
                            <table class="calendar_ticket_details">
                                <tbody>
                                <tr>
                                    <td><strong><?php rbfw_string('rbfw_text_item_type',__('Item Type','booking-and-rental-manager-for-woocommerce')); echo ':'; ?></strong></td>
                                    <td><?php echo rbfw_get_type_label($rent_type); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <button data-text="<?php _e('Less', 'rbfw-pro'); ?>" data-attendee_id="<?php echo $attendee_id ?>"><?php _e('View More', 'rbfw-pro'); ?></button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <table class="calendar_ticket_details more-info-<?php echo $attendee_id ?>" style="display: none">
                                <tbody>
                                <?php if($rent_type == 'bike_car_md' || $rent_type == 'bike_car_sd' || $rent_type == 'dress' || $rent_type == 'equipment' || $rent_type == 'others'){ ?>
                                    <?php if($pickup_point){ ?>
                                        <tr>
                                            <td><strong><?php rbfw_string('rbfw_text_pickup_location',__('Pickup Location','booking-and-rental-manager-for-woocommerce')); echo ':'; ?></strong></td>
                                            <td><?php echo esc_html($pickup_point); ?></td>
                                        </tr>
                                    <?php } ?>
                                    <?php if($pickup_point){ ?>
                                        <tr>
                                            <td><strong><?php rbfw_string('rbfw_text_dropoff_location',__('Drop-off Location','booking-and-rental-manager-for-woocommerce')); echo ':'; ?></strong></td>
                                            <td><?php echo esc_html($dropoff_point); ?></td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                                <?php if($rent_type == 'resort'){ ?>
                                    <tr>
                                        <td><strong><?php rbfw_string('rbfw_text_package',__('Package','booking-and-rental-manager-for-woocommerce')); echo ':'; ?></strong></td>
                                        <td><?php echo esc_html($package); ?></td>
                                    </tr>
                                <?php } ?>

                                <?php if($discount_type){ ?>
                                    <tr>
                                        <td><strong><?php echo $rbfw->get_option_trans('rbfw_text_discount_type', 'rbfw_basic_translation_settings', __('Discount Type','booking-and-rental-manager-for-woocommerce')); ?>:</strong></td>
                                        <td><?php echo $discount_type; ?></td>
                                    </tr>
                                <?php } ?>

                                <?php if($rent_type == 'bike_car_sd' || $rent_type == 'appointment'){ ?>
                                    <tr>
                                        <td colspan="2">
                                            <h3><?php rbfw_string('rbfw_text_rent_information',__('Rent Information','booking-and-rental-manager-for-woocommerce')); echo ':'; ?> </h3>
                                            <table class="calendar_ticket_details">
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
                                        <td colspan="2">
                                            <h3><?php rbfw_string('rbfw_text_room_information',__('Room Information','booking-and-rental-manager-for-woocommerce')); echo ':'; ?></h3>
                                            <table class="calendar_ticket_details">
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

                                <?php if ( ! empty( $service_info ) ){ ?>
                                    <tr>
                                        <td colspan="2">
                                            <h3>
                                                <?php rbfw_string('rbfw_text_extra_service_information',__('Extra Service Information','booking-and-rental-manager-for-woocommerce')); echo ':'; ?>
                                            </h3>
                                            <table class="calendar_ticket_details">
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
                                                } elseif($rent_type == 'resort'){
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
                                <?php } ?>

                                <?php if($rent_type == 'multiple_items'){
                                    $multiple_items_info = get_post_meta($attendee_id, 'multiple_items_info', true);
                                    $rbfw_category_wise_info = get_post_meta($attendee_id, 'rbfw_category_wise_info', true);
                                    $duration_qty = get_post_meta($attendee_id, 'duration_qty', true);
                                    $total_days = get_post_meta($attendee_id, 'total_days', true);
                                    if ( ! empty( $multiple_items_info ) ){ ?>
                                        <tr>
                                        <td colspan="2">
                                            <h2>Item informations</h2>
                                            <table>
                                                <?php foreach ($multiple_items_info as $key => $value){
                                                    ?>
                                                    <tr>
                                                        <th>
                                                            <?php echo esc_html($value['item_name']); ?>:
                                                        </th>
                                                        <td>(<?php echo wp_kses(wc_price($value['item_price']),rbfw_allowed_html()); ?> x <?php echo esc_html($value['item_qty']); ?> x <?php echo esc_html($duration_qty); ?>) = <?php echo wp_kses(wc_price((float)$value['item_price'] * (int)$value['item_qty'] * (int)$duration_qty),rbfw_allowed_html()); ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </table>
                                        </td>
                                        </tr>
                                    <?php }
                                    if ( ! empty( $rbfw_category_wise_info ) ){ ?>
                                <tr>
                                <td colspan="2">
                                    <h2>Service Category Info</h2>
                                    <table>
                                       <?php foreach ($rbfw_category_wise_info as $key => $value){ ?>
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
                                                                            echo '('.wp_kses(wc_price($item['price']),rbfw_allowed_html()). 'x'. esc_html($item['quantity']) . 'x' .esc_html($total_days) .'='.wp_kses(wc_price($item['price']*(int)$item['quantity'] * (int)$total_days),rbfw_allowed_html()).')';
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
                                    </td>
                                </tr>
                                    </table>
                                    <?php } ?>
                                <?php } ?>


                                <?php if ( ! empty( $service_infos ) ){ ?>
                                    <tr>
                                        <td>
                                            <h3><?php esc_html_e( 'Service Information:', 'booking-and-rental-manager-for-woocommerce' ); ?></h3>
                                            <?php foreach ($service_infos as $key => $value){ ?>
                                                <?php if(count($value)){ ?>
                                                    <table class="calendar_ticket_details">
                                                        <tr>
                                                            <td><?php echo $key; ?></td>
                                                        </tr>
                                                        <?php foreach ($value as $key1=>$item){ ?>
                                                            <tr>
                                                                <td><?php echo $item['name'] ?></td>
                                                                <td>
                                                                    <?php
                                                                    if($item['service_price_type']=='day_wise'){
                                                                        echo '('.wc_price($item['price']). 'x'. $item['quantity'] . 'x' .$total_days .'='.wc_price($item['price'] * $item['quantity'] * $total_days).')';
                                                                    }else{
                                                                        echo '('.wc_price($item['price']). 'x'. $item['quantity'] .'='.wc_price($item['price']*$item['quantity']).')';
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </table>
                                                <?php } ?>
                                            <?php } ?>
                                        </td>

                                    </tr>
                                <?php } ?>

                                <?php if(!empty($rbfw_regf_info)){ ?>
                                    <tr>
                                        <td colspan="2">
                                            <h3><?php rbfw_string('rbfw_text_customer_information',__('Customer Information','booking-and-rental-manager-for-woocommerce')); echo ':'; ?></h3>
                                            <table class="calendar_ticket_details">
                                                <?php
                                                foreach ($rbfw_regf_info as $info) {

                                                    $label = $info['label'];
                                                    $value = $info['value'];

                                                    if(filter_var($value, FILTER_VALIDATE_URL)){

                                                        $value = '<a href="'.esc_url($value).'" target="_blank" style="text-decoration:underline">'.esc_html__('View File','booking-and-rental-manager-for-woocommerce').'</a>';
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td><strong><?php echo $label; ?></strong></td>
                                                        <td><?php echo $value; ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </table>
                                        </td>

                                    </tr>
                                <?php } ?>


                                <?php if(!empty($variation_info)){
                                    foreach ($variation_info as $key => $value) {
                                        ?>
                                        <tr>
                                            <td><strong><?php echo esc_html($value['field_label']); ?></strong></td>
                                            <td><?php echo esc_html($value['field_value']); ?></td>
                                        </tr>
                                    <?php }  ?>
                                <?php }  ?>

                                <?php if($rent_type == 'bike_car_md' || $rent_type == 'dress' || $rent_type == 'equipment' || $rent_type == 'others'){ ?>
                                    <tr>
                                        <td><strong><?php rbfw_string('rbfw_text_duration_cost',__('Duration Cost','booking-and-rental-manager-for-woocommerce')); echo ':'; ?></strong></td>
                                        <td><?php $duration_cost_per_item = $duration_cost/$rbfw_item_quantity; echo wc_price($duration_cost_per_item).' * '.$rbfw_item_quantity .' = '. wc_price($duration_cost); ?></td>
                                    </tr>
                                <?php }else{ ?>
                                    <tr>
                                        <td><strong><?php rbfw_string('rbfw_text_duration_cost',__('Duration Cost','booking-and-rental-manager-for-woocommerce')); echo ':'; ?></strong></td>
                                        <td><?php echo wc_price($duration_cost); ?></td>
                                    </tr>
                                <?php } ?>


                                <?php if($service_cost){ ?>
                                    <tr>
                                        <td><strong><?php rbfw_string('rbfw_text_resource_cost',__('Resource Cost','booking-and-rental-manager-for-woocommerce')); echo ':'; ?></strong></td>
                                        <td><?php echo wc_price($service_cost); ?></td>
                                    </tr>
                                <?php } ?>

                                <?php if($discount_amount){ ?>
                                    <tr>
                                        <td><strong><?php echo $rbfw->get_option_trans('rbfw_text_discount', 'rbfw_basic_translation_settings', __('Discount','booking-and-rental-manager-for-woocommerce')); ?>:</strong></td>
                                        <td><?php echo wc_price($discount_amount); ?></td>
                                    </tr>
                                <?php } ?>


                                <?php if($security_deposit_amount){ ?>
                                    <tr>
                                        <td><strong><?php echo (!empty(get_post_meta($rbfw_id, 'rbfw_security_deposit_label', true)) ? get_post_meta($rbfw_id, 'rbfw_security_deposit_label', true) : 'Security Deposit'); ?>:</strong></td>
                                        <td><?php echo wc_price($security_deposit_amount); ?></td>
                                    </tr>
                                <?php } ?>

                                <tr>
                                    <td><strong><?php rbfw_string('rbfw_text_total_cost',__('Total Cost','booking-and-rental-manager-for-woocommerce')); echo ':'; ?></strong></td>
                                    <td><?php echo wc_price($total_cost).' '.$tax_status; ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </td>

                        <td><?php echo get_post_meta($attendee_id, 'rbfw_link_order_id', true); ?></td>
                        <td><?php echo esc_html($status); ?></td>
                        <td><?php echo rbfw_get_datetime(get_post_meta($attendee_id, 'rbfw_start_datetime', true), 'date-time-text'); ?></td>
                        <td><?php echo rbfw_get_datetime(get_post_meta($attendee_id, 'rbfw_end_datetime', true), 'date-time-text'); ?></td>
                        <?php do_action('mep_attendee_list_item', $attendee_id); ?>
                    </tr>
                    <?php } ?>
				</tbody>
			</table>
			<span class="close_order_list_area">X</span>
		</div>
		<?php
		die();
	}

	add_action('rbfw_admin_menu_after_inventory', 'rbmw_pro_booking_in_calender_menu');
	function rbmw_pro_booking_in_calender_menu()
	{
		add_submenu_page(
			'edit.php?post_type=rbfw_item',
			__('Booking  Calender', 'rbfw-pro'),
			__('Booking  Calender', 'rbfw-pro'),
			'manage_options',
			'booking_in_calender', 'rbmw_pro_booking_in_calender_dashboard');
	}
	function rbmw_pro_booking_in_calender_dashboard(){
		?>
		<div class="wrap">
		<h2><?php _e('Booking Calender', 'rbfw-pro'); ?></h2>
		<div class="filter_area">
			<label>
				<span class="filter_area_label"><?php _e('Item', 'rbfw-pro'); ?></span>
				<select name="event_id" id="mep_event_id" class="select2" required>
					<option value="0"><?php _e('Select Item', 'rbfw-pro'); ?></option>
					<?php
						$args = array(
							'post_type' => 'rbfw_item',
							'posts_per_page' => -1
						);
						$loop = new WP_Query($args);
						$events_query = $loop->posts;
						foreach ($events_query as $event) {
							?>
							<option value="<?php echo $event->ID; ?>"><?php echo get_the_title($event->ID); ?></option>
							<?php
						}
					?>
				</select>
			</label>
			<label>
			<span class="filter_area_label"></span>
			<button id='event_booking_filter_btn' type="button"><?php _e('Filter','rbfw-pro'); ?></button>
			</label>
		</div>

		<div class="booking_calender_area">
			<?php rbmw_pro_booking_in_calender(); ?>
			<div class="rbfw_bc_details_result" id="rbfw_bc_details_result"></div>	
		</div>
		<div class="rbfw-bc-page-ph">
                <div class="rbfw-ph-item">
                    <div class="rbfw-ph-col-12">
                        <div class="rbfw-ph-row">
                            <div class="rbfw-ph-col-12 big"></div>
                        </div>
                        <div class="rbfw-ph-row">
                            
                            <div class="rbfw-ph-col-12"></div>
                            <div class="rbfw-ph-col-12"></div>
							<div class="rbfw-ph-col-12"></div>
							<div class="rbfw-ph-col-12"></div>
							
                        </div>
                    </div>
                </div>
            </div>			
		</div>
		<?php
	}
	function rbmw_pro_booking_in_calender(){
		$today = mktime(0, 0, 0);
		$date = $_POST['date'] ?? $today;
		?>
			<?php rbmw_pro_booking_in_calender_head($date, $today); ?>
			<table class="booking_day_table">
				<tr>
					<th><span><?php _e('Sunday', 'rbfw-pro'); ?></span></th>
					<th><span><?php _e('Monday', 'rbfw-pro'); ?></span></th>
					<th><span><?php _e('Tuesday', 'rbfw-pro'); ?></span></th>
					<th><span><?php _e('Wednesday', 'rbfw-pro'); ?></span></th>
					<th><span><?php _e('Thursday', 'rbfw-pro'); ?></span></th>
					<th><span><?php _e('Friday', 'rbfw-pro'); ?></span></th>
					<th><span><?php _e('Saturday', 'rbfw-pro'); ?></span></th>
				</tr>
				<?php rbmw_pro_booking_in_calender_date($date, $today); ?>
			</table>

		<?php
	}

	function rbmw_pro_booking_in_calender_date($date, $today){
		$event_id = $_POST['event_id'] ?? 0;
		$month = date_i18n('m', $date);
		$year = date_i18n('Y', $date);

		$start_day_num = (int)date_i18n('w', mktime(0, 0, 0, $month, 01, $year));


		$month_day_num = ($month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31));
		$date_count = 1;
		while ($date_count <= $month_day_num) {
			?>
			<table class="booking_dade_table">
				<tr>
					<?php
						for ($i = $start_day_num; $i < 7; $i++) {
							if ($start_day_num > 0) {
								echo '<td colspan="' . $start_day_num . '"></td>';
								$start_day_num = 0;
							}
							if ($date_count <= $month_day_num) {
								$current_date = mktime(0, 0, 0, $month, $date_count, $year);
								$date_class = ($current_date == $today) ? 'current_date' : '';
								$date_num = ($date_count < 10) ? '0' . $date_count : $date_count;
								$q_date=$year.'-'.$month.'-'.$date_num;
								$query= rbmw_pro_booking_query($q_date,$event_id);
								$post_count= $query->post_count;
							
								$item_class= 'item_class_4';
								?>
								<td>
									<div class="allCenter <?php echo $date_class . ' ' . $item_class; ?>">
										<span class="rbfw_bcalendar_date"><?php echo $date_num; ?></span>
										<?php

										if($post_count>0){
											?>
											<span class="mp_date_exit_event_count" data-cuttent-date="<?php echo $current_date; ?>">View Details<span class="class_post_count">(<?php echo $post_count; ?>)</span></span>
											<?php
										}
										?>
									</div>
								</td>
								<?php
								$date_count++;
							} else {
								$col_span = 7 - $i;
								if ($col_span > 0) {
									echo '<td colspan="' . $col_span . '"></td>';
								}
								$i = 7;
							}
						}
						$start_day_num = 0;
					?>
				</tr>
			</table>

			<?php
		}

	}
	function rbmw_pro_booking_in_calender_head($date, $today){
		$day = date_i18n('d', $date);
		$month = date_i18n('m', $date);
		$year = date_i18n('Y', $date);

		$pre_month = mktime(0, 0, 0, (int)$month - 1, (int)$day, (int)$year);
		$next_month = mktime(0, 0, 0, (int)$month + 1, (int)$day, (int)$year);

		$pre_year = mktime(0, 0, 0, (int)$month, (int)$day, (int)$year - 1);
		$next_year = mktime(0, 0, 0, (int)$month, (int)$day, (int)$year + 1);
		?>
		<table class="booking_calenter_table">
			<tr>
				<th>
					<div data-date="<?php echo $pre_year; ?>">
						<span class="fa fa-angle-double-left"></span>
						<span><?php _e('Pre Year', 'rbfw-pro'); ?></span>
					</div>
				</th>
				<th>
					<div data-date="<?php echo $pre_month; ?>">
						<span class="fa fa-angle-left"></span>
						<span><?php _e('Pre Month', 'rbfw-pro'); ?></span>
					</div>
				</th>
				<th colspan="3">
					<div data-date="<?php echo $today; ?>" class="active">
					<?php echo date_i18n('F Y', $date); ?>
					</div>
					<?php if ($today != $date) { ?> /
						<div data-date="<?php echo $today; ?>">
							<?php echo date_i18n('F Y', $today); ?>
						</div>
					<?php }
					?>

				</th>
				<th>
					<div data-date="<?php echo $next_month; ?>">
						<span><?php _e('Next Month', 'rbfw-pro'); ?></span>
						<span class="fa fa-angle-right"></span>
					</div>
				</th>
				<th>
					<div data-date="<?php echo $next_year; ?>">
						<span><?php _e('Next Year', 'rbfw-pro'); ?></span>
						<span class="fa fa-angle-double-right"></span>
					</div>
				</th>
			</tr>
		</table>
		<?php
	}

	function rbmw_pro_booking_query($event_date,$event_id=0, $show=-1)
	{

		$event_id_filter = $event_id > 0 ? array(
			'key'     => 'rbfw_id',
			'value'   => $event_id,
			'compare' => '='
		) : '';

		$event_date_filter = $event_date > 0 ? array(
			
			'relation' => 'AND',
			array(
				'key'     => 'rbfw_start_date',
				'value'   =>  $event_date,
				'compare' => '<=',
				'type'    => 'DATE'
			),
			array(
				'key'     => 'rbfw_end_date',
				'value'   => $event_date,
				'compare' => '>=',
				'type'    => 'DATE'
			)			
		) : '';

		$args = array(
			'post_type'         => 'rbfw_order_meta',
			'posts_per_page'    => $show,
			'meta_query'        => array(
				'relation'      => 'AND',
				array(
					'relation'  => 'AND',
					$event_id_filter,
					$event_date_filter
				),
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