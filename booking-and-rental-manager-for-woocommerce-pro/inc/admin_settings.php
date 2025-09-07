<?php


add_filter( 'rbfw_settings_sec_reg', 'rbfw_free_settings_sec', 100 );

function rbfw_free_settings_sec( $default_sec ) {
    $sections = array(
        array(
            'id'    => 'rbfw_license_settings',
            'title' => '<i class="fa-solid fa-address-card"></i>' . __( 'License', 'booking-and-rental-manager-for-woocommerce' )
        ),
    );
    return array_merge( $default_sec, $sections );
}

add_action('wsa_form_bottom_rbfw_license_settings', 'rbfw_licensing_page', 5);
function rbfw_licensing_page($form) {
    ?>
    <div class='mep-licensing-page'>
        <h3><?php esc_html_e( 'Booking and Rental Manager Licensing', 'booking-and-rental-manager-for-woocommerce' ); ?></h3>
        <p><?php esc_html_e( 'Thanks you for using our Booking and Rental Manager plugin. This plugin is free and no license is required. We have some additional addons to enhance features of this plugin functionality. If you have any addon you need to enter a valid license for that plugin below.', 'booking-and-rental-manager-for-woocommerce' ); ?></p>

        <div class="mep_licensae_info"></div>
        <table class='wp-list-table widefat striped posts mep-licensing-table'>
            <thead>
            <tr>
                <th><?php esc_html_e( 'Plugin Name', 'booking-and-rental-manager-for-woocommerce' ); ?></th>
                <th width=10%><?php esc_html_e( 'Order No', 'booking-and-rental-manager-for-woocommerce' ); ?></th>
                <th width=15%><?php esc_html_e( 'Expire on', 'booking-and-rental-manager-for-woocommerce' ); ?></th>
                <th width=30%><?php esc_html_e( 'License Key', 'booking-and-rental-manager-for-woocommerce' ); ?></th>
                <th width=10%><?php esc_html_e( 'Status', 'booking-and-rental-manager-for-woocommerce' ); ?></th>
                <th width=10%><?php esc_html_e( 'Action', 'booking-and-rental-manager-for-woocommerce' ); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php do_action('rbfw_license_page_addon_list'); ?>
            </tbody>
        </table>
    </div>
    <?php
}

if (!function_exists('mep_license_expire_date')) {
    function mep_license_expire_date($date) {
        if (empty($date) || $date == 'lifetime') {
            echo esc_html($date);
        } else {
            if (strtotime(current_time('Y-m-d H:i')) < strtotime(date('Y-m-d H:i', strtotime($date)))) {
                echo rbfw_get_datetime($date, 'date-time-text');
            } else {
                esc_html_e('Expired', 'booking-and-rental-manager-for-woocommerce');
            }
        }
    }
}


add_filter( 'rbfw_settings_sec_reg', 'rbfw_pro_settings_sec', 10 );
function rbfw_pro_settings_sec( $default_sec ) {
	$sections = array(
		array(
			'id'    => 'rbfw_basic_pdf_settings',
			'title' => '<i class="fa-solid fa-file-pdf"></i>' . __( 'PDF Settings', 'rbfw-pro' )
		),
		array(
			'id'    => 'rbfw_basic_email_settings',
			'title' => '<i class="fa-solid fa-envelope-circle-check"></i>' . __( 'Email Settings', 'rbfw-pro' )
		),
		array(
			'id'    => 'rbfw_basic_purchase_list_settings',
			'title' => '<i class="fa-solid fa-list-check"></i>' . __( 'Report Settings', 'rbfw-pro' )
		),
		array(
			'id'    => 'rbfw_basic_review_settings',
			'title' => '<i class="fa-regular fa-comment-dots"></i>' . __( 'Review Settings', 'rbfw-pro' )
		),
	);
	return array_merge( $default_sec, $sections );
}

add_filter( 'rbfw_settings_sec_fields', 'rbfw_pro_settings_fields', 10 );
function rbfw_pro_settings_fields( $default_fields ) {
	$settings_fields = array(
		'rbfw_basic_pdf_settings'   => array(
			array(
				'name'    => 'rbfw_send_pdf',
				'label'   => __( 'Send Ticket', 'rbfw-pro' ),
				'desc'    => __( 'Do you want to send PDF ticket as an attachment with the confirmation email', 'rbfw-pro' ),
				'type'    => 'select',
				'default' => 'no',
				'options' => array(
					'no'  => __( 'No', 'rbfw-pro' ),
					'yes' => __( 'Yes', 'rbfw-pro' ),
				),
			),
			array(
				'id'      => 'rbfw_pdf_logo',
				'name'    => 'rbfw_pdf_logo',
				'label'   => __( 'Logo URL', 'rbfw-pro' ),
				'desc'    => __( 'Add your custom logo what will appear on the PDF', 'rbfw-pro' ),
				'type'    => 'media',
				'default' => '',
			),
			array(
				'name'    => 'rbfw_pdf_bg',
				'label'   => __( 'PDF Background Image URL' ),
				'desc'    => __( 'You can add a custom Background Image for PDF. The image width should be 680px', 'rbfw-pro' ),
				'type'    => 'media',
				'default' => '',
			),
			array(
				'name'    => 'rbfw_pdf_address',
				'label'   => __( 'Company address', 'rbfw-pro' ),
				'desc'    => __( 'Add your company address', 'rbfw-pro' ),
				'default' => '',
				'type'    => 'wysiwyg',
			),
			array(
				'name'        => 'rbfw_pdf_phone',
				'label'       => __( 'Phone Number', 'rbfw-pro' ),
				'desc'        => __( 'Add company phone number here', 'rbfw-pro' ),
				'type'        => 'text',
				'default'     => '',
				'placeholder' => 'l',
			),
			array(
				'name'    => 'rbfw_pdf_tc_title',
				'label'   => __( 'Terms & Condition Title' ),
				'desc'    => __( 'This T & C Text will display in the PDF footer', 'rbfw-pro' ),
				'type'    => 'text',
				'default' => '',
			),
			array(
				'name'    => 'rbfw_pdf_tc_text',
				'label'   => __( 'Terms & Condition Text' ),
				'desc'    => __( 'This T & C Text will display in the PDF footer', 'rbfw-pro' ),
				'type'    => 'wysiwyg',
				'default' => '',
			),
			array(
				'name'    => 'rbfw_pdf_bg_color',
				'label'   => __( 'PDF Background Color' ),
				'desc'    => __( 'Select PDF Body Background Color', 'rbfw-pro' ),
				'type'    => 'color',
				'default' => '',
			),
			array(
				'name'    => 'rbfw_pdf_text_color',
				'label'   => __( 'PDF Text Color' ),
				'desc'    => __( 'Select PDF Body text Color', 'rbfw-pro' ),
				'type'    => 'color',
				'default' => '',
			)
		),
		'rbfw_basic_email_settings' => array(
			array(
				'name'    => 'rbfw_email_status',
				'label'   => __( 'Send Email on' ),
				'desc'    => __( 'Send email with the ticket as attachment when these order status comes', 'rbfw-pro' ),
				'type'    => 'multicheck',
				'options' => array(
					'processing' => __( 'Processing', 'rbfw-pro' ),
					'completed'  => __( 'Completed', 'rbfw-pro' )
				),
			),
			array(
				'name'    => 'rbfw_email_subject',
				'label'   => __( 'Email Subject', 'rbfw-pro' ),
				'desc'    => __( 'Set email subject here', 'rbfw-pro' ),
				'type'    => 'text',
				'default' => 'Booking Confirmation Email',
			),
			array(
				'name'    => 'rbfw_email_content',
				'label'   => __( 'Email Content', 'rbfw-pro' ),
				'desc'    => __( 'Set the email body content here', 'rbfw-pro' ),
				'type'    => 'wysiwyg',
				'default' => 'Thank you for your order. Please download the attached pdf booking receipt and carry out the printed booking receipt.',
			),
			array(
				'name'        => 'rbfw_email_from_name',
				'label'       => __( 'Email From Name', 'rbfw-pro' ),
				'type'        => 'text',
				'placeholder' => __( 'Booking Management Department', 'rbfw-pro' ),
				'default'     => '',
			),
			array(
				'name'        => 'rbfw_email_from',
				'label'       => __( 'Email From', 'rbfw-pro' ),
				'type'        => 'text',
				'placeholder' => __( 'info@example.com', 'rbfw-pro' ),
				'default'     => '',
			),
		),

		'rbfw_basic_purchase_list_settings' => array(
			array(
				'name'        => 'rbfw_purchase_list_ticket_no',
				'label'       => __( 'Pin no.', 'rbfw-pro' ),
				'type'        => 'checkbox',
				'desc'     => __( 'Enable/Disable Order no. field', 'rbfw-pro' ),
			),
			array(
				'name'        => 'rbfw_purchase_list_name',
				'label'       => __( 'Name', 'rbfw-pro' ),
				'type'        => 'checkbox',
				'desc'     => __( 'Enable/Disable Name field', 'rbfw-pro' ),
			),
			array(
				'name'        => 'rbfw_purchase_list_email',
				'label'       => __( 'Email', 'rbfw-pro' ),
				'type'        => 'checkbox',
				'desc'     => __( 'Enable/Disable Email field', 'rbfw-pro' ),
			),
			array(
				'name'        => 'rbfw_purchase_list_phone',
				'label'       => __( 'Phone', 'rbfw-pro' ),
				'type'        => 'checkbox',
				'desc'     => __( 'Enable/Disable Phone field', 'rbfw-pro' ),
			),
			array(
				'name'        => 'rbfw_purchase_list_address',
				'label'       => __( 'Address', 'rbfw-pro' ),
				'type'        => 'checkbox',
				'desc'     => __( 'Enable/Disable Address field', 'rbfw-pro' ),
			),
			array(
				'name'        => 'rbfw_purchase_list_ticket_type',
				'label'       => __( 'Ticket Type', 'rbfw-pro' ),
				'type'        => 'checkbox',
				'desc'     => __( 'Enable/Disable Ticket Type field', 'rbfw-pro' ),
			),
			array(
				'name'        => 'rbfw_purchase_list_order_no',
				'label'       => __( 'Order no.', 'rbfw-pro' ),
				'type'        => 'checkbox',
				'desc'     => __( 'Enable/Disable Order no. field', 'rbfw-pro' ),
			),
			array(
				'name'        => 'rbfw_purchase_datetime',
				'label'       => __( 'Date & Time', 'rbfw-pro' ),
				'type'        => 'checkbox',
				'desc'     => __( 'Enable/Disable Date & Time field', 'rbfw-pro' ),
			),
			array(
				'name'        => 'rbfw_purchase_list_billing_order_status',
				'label'       => __( 'Order Status', 'rbfw-pro' ),
				'type'        => 'checkbox',
				'desc'     => __( 'Enable/Disable Order Status field', 'rbfw-pro' ),
			),
			array(
				'name'        => 'rbfw_purchase_list_billing_paid',
				'label'       => __( 'Price', 'rbfw-pro' ),
				'type'        => 'checkbox',
				'desc'     => __( 'Enable/Disable Price field', 'rbfw-pro' ),
			),
			array(
				'name'        => 'rbfw_purchase_list_billing_method',
				'label'       => __( 'Payment Method', 'rbfw-pro' ),
				'type'        => 'checkbox',
				'desc'     => __( 'Enable/Disable Payment Method field', 'rbfw-pro' ),
			),
			array(
				'name'        => 'rbfw_purchase_list_pickup_point',
				'label'       => __( 'Pickup Point', 'rbfw-pro' ),
				'type'        => 'checkbox',
				'desc'     => __( 'Enable/Disable Pickup Point field', 'rbfw-pro' ),
			),
			array(
				'name'        => 'rbfw_purchase_list_dropoff_point',
				'label'       => __( 'Drop-off Point', 'rbfw-pro' ),
				'type'        => 'checkbox',
				'desc'     => __( 'Enable/Disable Drop-off Point field', 'rbfw-pro' ),
			),
			array(
				'name'        => 'rbfw_purchase_list_service',
				'label'       => __( 'Service', 'rbfw-pro' ),
				'type'        => 'checkbox',
				'desc'     => __( 'Enable/Disable Service field', 'rbfw-pro' ),
			),
			array(
				'name'        => 'rbfw_purchase_list_extra_service',
				'label'       => __( 'Extra Service', 'rbfw-pro' ),
				'type'        => 'checkbox',
				'desc'     => __( 'Enable/Disable Extra Service field', 'rbfw-pro' ),
			),
			array(
				'name'        => 'rbfw_purchase_list_item_quantity',
				'label'       => __( 'Item Quantity', 'rbfw-pro' ),
				'type'        => 'checkbox',
				'desc'     => __( 'Enable/Disable Item Quantity field', 'rbfw-pro' ),
			),
		),
		'rbfw_basic_review_settings' => array(
			array(
				'name'    => 'rbfw_review_system',
				'label'   => __( 'Review System', 'rbfw-pro' ),
				'desc'    => __( 'Enable/Disable the review system', 'rbfw-pro' ),
				'type'    => 'select',
				'default' => 'on',
				'options' => array(
					'off'  => __( 'Disable', 'rbfw-pro' ),
					'on' => __( 'Enable', 'rbfw-pro' ),
				)
			)
		)
	);
	return array_merge( $default_fields, $settings_fields );
}


