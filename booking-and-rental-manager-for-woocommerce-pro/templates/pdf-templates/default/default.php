<?php
	// Template Name: Default Theme
	global $rbfw;
	$rbfw_pdf_bg_id 		= rbfw_get_option( 'rbfw_pdf_bg', 'rbfw_basic_pdf_settings', '' );
	$rbfw_pdf_bg_url 		= wp_get_attachment_url( $rbfw_pdf_bg_id );
	$rbfw_pdf_bg_color 		= rbfw_get_option( 'rbfw_pdf_bg_color', 'rbfw_basic_pdf_settings', '' );
	$rbfw_pdf_text_color 	= rbfw_get_option( 'rbfw_pdf_text_color', 'rbfw_basic_pdf_settings', '' );
	$mps_tax_switch = $rbfw->get_option('rbfw_mps_tax_switch', 'rbfw_basic_payment_settings', 'off');
	$mps_tax_format = $rbfw->get_option('rbfw_mps_tax_format', 'rbfw_basic_payment_settings', 'excluding_tax');
	$tax_status = '';

	$output = '<style>';
	if(!empty($rbfw_pdf_bg_url)){
		$output .= '.rbfw-pdf-body{ background-image:url('.esc_url($rbfw_pdf_bg_url).');background-size: contain;background-position: center center;background-repeat:no-repeat; }';
	}
	if(!empty($rbfw_pdf_bg_color)){
		$output .= '.rbfw-pdf-body{ background-color:'.$rbfw_pdf_bg_color.'; }';
	}
	if(!empty($rbfw_pdf_text_color)){
		$output .= '.rbfw-pdf-body table th,.rbfw-pdf-body table td{ color:'.$rbfw_pdf_text_color.'; }';
	}
	$output .= '</style>';
	echo $output;
	?>
	<style>
	.rbfw-pdf-body table.rbfw_has_table_border{
		width: 100%;
		text-align:left !important;
		border: 5px solid #d3d3d3;
		margin-bottom:20px;
	}
	.rbfw-pdf-body table.rbfw_has_table_border tbody td:nth-child(1){
		width: 50%;
	}
	.rbfw-pdf-body table.rbfw_has_table_border thead td{
		text-align:left !important;
	}
	.rbfw-pdf-body table.rbfw_has_table_border thead td,
	.rbfw-pdf-body table.rbfw_has_table_border tbody td{
		border: 1px solid #d3d3d3 !important;
		padding:5px;
		vertical-align: top;
	}
	.rbfw-pdf-body .rbfw_tc_table{
		text-align:left;
	}
	.rbfw-pdf-header-table{
		margin-bottom:20px;
	}
	.rbfw-pdf-header-table td{
		width: 50%;
	}
	.rbfw-pdf-body table.rbfw_has_table_border thead td{
		background-color:#f5f5f5;
	}
	.rbfw-pdf-body {
        page-break-after: always;
    }

    .rbfw-pdf-body:last-child {
        page-break-after: auto;
    }
	@media print {
        .rbfw-pdf-body {
            height: 99%;
            page-break-after: avoid;
            page-break-before: avoid;
        }
    }

	</style>

	<?php
	$rbfw_wc_order_id = !empty(get_post_meta($order_id,'rbfw_order_id',true)) ? get_post_meta($order_id,'rbfw_order_id',true) : '';
	$rbfw_wc_order_tax = !empty(get_post_meta($order_id,'rbfw_order_tax',true)) ? get_post_meta($order_id,'rbfw_order_tax',true) : 0;
	$rbfw_ticket_info = !empty(get_post_meta($order_id,'rbfw_ticket_info',true)) ? get_post_meta($order_id,'rbfw_ticket_info',true) : [];

	foreach ($rbfw_ticket_info as $ticket_info) {

	$rbfw_id = $ticket_info['rbfw_id'];
	$rent_item_type = $ticket_info['rbfw_rent_type'];

	$rbfw_start_datetime = rbfw_get_datetime($ticket_info['rbfw_start_datetime'], 'date-time-text');
	$rbfw_end_datetime = rbfw_get_datetime($ticket_info['rbfw_end_datetime'], 'date-time-text');

	if($rent_item_type == 'resort'){
		$rbfw_start_datetime = rbfw_get_datetime($ticket_info['rbfw_start_datetime'], 'date-text');
		$rbfw_end_datetime = rbfw_get_datetime($ticket_info['rbfw_end_datetime'], 'date-text');
	}

	if($rent_item_type == 'bike_car_sd' || $rent_item_type == 'appointment'){
		$rbfw_start_datetime = rbfw_get_datetime($ticket_info['rbfw_start_datetime'], 'date-time-text');
        if($ticket_info['rbfw_end_time']){
            $rbfw_end_datetime = rbfw_get_datetime($ticket_info['rbfw_end_datetime'], 'date-time-text');
        }else{
            $rbfw_end_datetime = rbfw_get_datetime($ticket_info['rbfw_end_datetime'], 'date-text');
        }
	}

	$type_info = !empty($ticket_info['rbfw_type_info']) ? $ticket_info['rbfw_type_info'] : [];
	$service_info = !empty($ticket_info['rbfw_service_info']) ? $ticket_info['rbfw_service_info'] : [];
	$variation_info = !empty($ticket_info['rbfw_variation_info']) ? $ticket_info['rbfw_variation_info'] : [];

	$tax = !empty($ticket_info['rbfw_mps_tax']) ? $ticket_info['rbfw_mps_tax'] : 0;

	$mps_tax_percentage = !empty(get_post_meta($rbfw_id, 'rbfw_mps_tax_percentage', true)) ? strip_tags(get_post_meta($rbfw_id, 'rbfw_mps_tax_percentage', true)) : '';



	$duration_cost = $ticket_info['duration_cost'] ? $ticket_info['duration_cost'] : 0;
	$service_cost = $ticket_info['service_cost'] ? $ticket_info['service_cost'] : 0;
	$total_cost = $ticket_info['ticket_price'];

	$discount_amount = !empty($ticket_info['discount_amount']) ? wc_price($ticket_info['discount_amount']) : '';
	$rbfw_regf_info = !empty($ticket_info['rbfw_regf_info']) ? $ticket_info['rbfw_regf_info'] : [];

	?>
	<div class='rbfw-pdf-body'>
		<div class="rbfw_body" <?php rbfw_pdf_body_style(); ?>>
			<div class="pdf-header">
				<table class="rbfw-pdf-header-table">
					<tr>
						<td><?php do_action( 'rbfw_pdf_logo' ); ?></td>
						<td>
							<?php do_action( 'rbfw_pdf_company_address' ); ?><br>
							<?php do_action( 'rbfw_pdf_company_phone' ); ?>
						</td>
					</tr>
				</table>
			</div>

			<div class="rbfw_tkt_row">
				<div class="rbfw_information">
					<table class="rbfw_has_table_border">
						<thead>
							<tr>
								<td colspan="2"><?php rbfw_string('rbfw_text_booking_information',__('Booking Information','rbfw-pro')); ?></td>
							</tr>
						</thead>
						<td>
							<tr>
								<td><?php rbfw_string('rbfw_text_item_name',__('Item Name','rbfw-pro')); echo ':'; ?></td>
								<td><?php echo $ticket_info['ticket_name'].' * '.$ticket_info['rbfw_item_quantity']; ?></td>
							</tr>


							<tr>
								<td><?php rbfw_string('rbfw_text_start_date_and_time',__('Start Date and Time','booking-and-rental-manager-for-woocommerce')); echo ':'; ?></td>
								<td><?php echo $rbfw_start_datetime; ?></td>
							</tr>

							<tr>
								<td><?php rbfw_string('rbfw_text_end_date_and_time',__('End Date and Time','booking-and-rental-manager-for-woocommerce')); echo ':'; ?></td>
								<td><?php echo $rbfw_end_datetime; ?></td>
							</tr>


							<?php if($rent_item_type != 'resort' && $rent_item_type != 'bike_car_sd' && $rent_item_type != 'appointment'): ?>

							<?php if($ticket_info['rbfw_pickup_point'] != '') { ?>
							<tr>
								<td><?php rbfw_string('rbfw_text_pickup_location',__('Pickup Location','rbfw-pro')); echo ':'; ?></td>
								<td><?php echo $ticket_info['rbfw_pickup_point']; ?></td>
							</tr>
							<?php } ?>

							<?php if($ticket_info['rbfw_dropoff_point'] != '') { ?>
							<tr>
								<td><?php rbfw_string('rbfw_text_dropoff_location',__('Drop-off Location','rbfw-pro')); echo ':'; ?></td>
								<td><?php echo $ticket_info['rbfw_dropoff_point']; ?></td>
							</tr>
							<?php } ?>
							<?php endif; ?>

                            <?php if(!empty($variation_info)){
                                foreach ($variation_info as $key => $value) {
                                    ?>
                                    <tr>
                                        <td><?php echo esc_html($value['field_label']); ?></td>
                                        <td><?php echo esc_html($value['field_value']); ?></td>
                                    </tr>
                                <?php } } ?>
                            <?php if($ticket_info['rbfw_item_quantity']){ ?>
                                <tr>
                                    <td><?php echo $rbfw->get_option('rbfw_text_quantity', 'rbfw_basic_translation_settings', __('Quantity','booking-and-rental-manager-for-woocommerce')); ?></td>
                                    <td><?php echo $ticket_info['rbfw_item_quantity']; ?></td>
                                </tr>
							<?php } ?>
							<?php if($rent_item_type == 'resort'): ?>
							<tr>
								<td><?php rbfw_string('rbfw_text_room_information',__('Room Information','rbfw-pro')); echo ':'; ?></td>
								<td>
									<?php
									if(!empty($type_info)){
										echo '<ol>';
										foreach ($type_info as $name => $qty) {
											echo '<li>'.$name.' x '.$qty.'</li>';
										}
										echo '</ol>';
									}
									?>
								</td>
							</tr>
							<?php endif; ?>

                            <?php if($rent_item_type == 'bike_car_sd' || $rent_item_type == 'appointment'): ?>
							<tr>
								<td><?php rbfw_string('rbfw_text_type_information',__('Type Information','rbfw-pro')); echo ':'; ?></td>
								<td>
									<?php
									if(!empty($type_info)){
										echo '<ol>';
										foreach ($type_info as $name => $qty) {
											echo '<li>'.$name.' x '.$qty.'</li>';
										}
										echo '</ol>';
									}
									?>
								</td>
							</tr>
							<?php endif; ?>

                            <?php if($rent_item_type == 'multiple_items'): ?>

                            <?php
                                $multiple_items_info = !empty($ticket_info['multiple_items_info']) ? $ticket_info['multiple_items_info'] : [];
                                $rbfw_category_wise_info = !empty($ticket_info['rbfw_category_wise_info']) ? $ticket_info['rbfw_category_wise_info'] : [];
                                ?>



                                <?php if ( ! empty( $multiple_items_info ) ){ ?>
                                    <tr>
                                    <td><?php esc_html_e('Selected Items','booking-and-rental-manager-for-woocommerce'); ?></td><td>
                                        <table>
                                        <?php
                                        foreach ($multiple_items_info as $key => $value){
                                            ?>
                                            <tr>
                                                <th>
                                                    <?php echo esc_html($value['item_name']); ?>:
                                                </th>
                                                <td>(<?php echo wp_kses(wc_price($value['item_price']),rbfw_allowed_html()); ?> x <?php echo esc_html($value['item_qty']); ?> x <?php echo esc_html($duration_qty); ?>) = <?php echo wp_kses(wc_price($value['item_price'] * $value['item_qty']),rbfw_allowed_html()); ?></td>
                                            </tr>
                                            <?php
                                        } ?>
                                        </td>
                                        </table>
                                    <?php
                                } ?>

                                <?php  if ( ! empty( $rbfw_category_wise_info ) ){ ?>
                                    <?php foreach ($rbfw_category_wise_info as $key => $value){ ?>
                                        <tr>
                                            <td><?php esc_html_e('Optional Add-ons','booking-and-rental-manager-for-woocommerce'); ?></td>
                                            <td>
                                        <table>
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
                                        </table>
                                        </td>
                                    <?php } ?>
                                <?php } ?>


                            <?php endif; ?>


							<?php if(!empty($service_info)): ?>
							<tr>
								<td><?php rbfw_string('rbfw_text_extra_service_information',__('Extra Service Information','rbfw-pro')); echo ':'; ?></td>
								<td>
									<?php
									if(!empty($service_info)){
										echo '<ol>';
										foreach ($service_info as $name => $qty) {
											echo '<li>'.$name.' x '.$qty.'</li>';
										}
										echo '</ol>';
									}
									?>
								</td>
							</tr>

                            <?php endif; ?>

							<?php if(!empty($rbfw_regf_info)){ ?>
							<tr>
								<td><?php rbfw_string('rbfw_text_customer_information',__('Customer Information','booking-and-rental-manager-for-woocommerce')); echo ':'; ?></td>
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
						</tbody>
					</table>
				</div>
				<div class="rbfw_atndee_information">
					<?php do_action( 'rbfw_pdf_attendee_info', $order_id); ?>
				</div>
			</div>

			<div class="rbfw_tkt_row">
				<table class="rbfw_has_table_border">
					<thead>
						<tr>
							<td colspan="2"><?php echo rbfw_string_return('rbfw_text_order_received',__('Order Information','rbfw-pro')); ?></td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php rbfw_string('rbfw_text_pin',__('PIN','rbfw-pro')); ?></td>
							<td><?php do_action( 'rbfw_pdf_pin', $order_id ); ?></td>
						</tr>
						<tr>
							<td><?php rbfw_string('rbfw_text_order_number',__('Order number','rbfw-pro')); echo ':'; ?></td>
							<td>
								<?php echo $order_id; ?>
							</td>
						</tr>
                        <?php if($rent_item_type == 'bike_car_md' || $rent_item_type == 'dress' || $rent_item_type == 'equipment' || $rent_item_type == 'others'){ ?>
                            <tr>
                                <td><?php rbfw_string('rbfw_text_duration_cost',__('Duration Cost','rbfw-pro')); echo ':'; ?></td>
                                <td><?php  $duration_cost_per_item = $duration_cost/$ticket_info['rbfw_item_quantity']; echo wc_price($duration_cost_per_item).' * '.$ticket_info['rbfw_item_quantity']. ' = '. wc_price($duration_cost); ?></td>
                            </tr>
                        <?php }else{ ?>
                            <tr>
                                <td><?php rbfw_string('rbfw_text_duration_cost',__('Duration Cost','rbfw-pro')); echo ':'; ?></td>
                                <td><?php echo wc_price($duration_cost); ?></td>
                            </tr>
                        <?php } ?>

						<?php if(!empty($service_cost)){ ?>
						<tr>
							<td><?php rbfw_string('rbfw_text_resource_cost',__('Resource Cost','rbfw-pro')); echo ':'; ?></td>
							<td><?php echo wc_price($service_cost); ?></td>
						</tr>
						<?php } ?>

						<?php if(!empty($rbfw_wc_order_tax)){ ?>
						<tr>
							<td><?php echo $rbfw->get_option('rbfw_text_order_tax', 'rbfw_basic_translation_settings', __('Order tax','rbfw-pro')); ?></td>
							<td><?php echo wc_price($rbfw_wc_order_tax); ?></td>
						</tr>
						<?php } ?>


						<?php if(!empty($discount_amount)){ ?>
						<tr>
							<td><?php rbfw_string('rbfw_text_discount', __('Discount','booking-and-rental-manager-for-woocommerce')); ?>:</td>
							<td><?php echo $discount_amount; ?></td>
						</tr>
						<?php } ?>

                        <tr>
                            <td><?php rbfw_string('rbfw_text_total_cost',__('Total Cost','rbfw-pro')); echo ':'; ?></td>
                            <td><?php echo wc_price($total_cost); ?>(<?php rbfw_string('rbfw_text_excluding_tax',__('Excluding tax','rbfw-pro')); ?>)</td>
                        </tr>

					</tbody>
				</table>
			</div>

			<div class="rbfw_tkt_row">
				<table class="rbfw_tc_table">
					<thead>
						<tr>
							<th style="text-align:left"><?php do_action( 'rbfw_pdf_term_title' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php do_action( 'rbfw_pdf_term_text' ); ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="page_break"></div>
	<?php } ?>