<?php
	if ( ! defined( 'ABSPATH' ) ) {
		die;
	} // Cannot access pages directly.
	if ( ! class_exists( 'RBFW_Pro_Pdf' ) ) {
		class RBFW_Pro_Pdf {
			public function __construct() {}
		}
		new RBFW_Pro_Pdf();
	}

	add_action( 'wp_ajax_generate_pdf', 'rbfw_events_generate_pdf' );
	add_action( 'wp_ajax_nopriv_generate_pdf', 'rbfw_events_generate_pdf' );
	function rbfw_events_generate_pdf() {
		if ( empty( $_GET['action'] ) || ! check_admin_referer( $_GET['action'] ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.', 'rbfw-pro' ) );
		}
		$order_id 			= isset( $_GET['order_id'] ) ? sanitize_text_field( $_GET['order_id'] ) : '';

		$document_type 		= isset( $_GET['document_type'] ) ? sanitize_text_field( $_GET['document_type'] ) : '';

		// global $wbtm;
		header( "Content-Type: application/pdf; charset=UTF-8" );
		echo generate_pdf( $order_id );
		exit;
	}

	function generate_pdf( $order_id ) {
		$upload_dir 	= wp_upload_dir();


        $receipt_id = get_post_meta($order_id, 'rbfw_order_id', true);



		$ticket_name 					= $upload_dir['basedir'] . '/' . __( 'Booking_Receipt_', 'rbfw-pro' ) . $receipt_id . '.pdf';
		$file_name 						= __( 'Booking_Receipt_', 'rbfw-pro' ) . $receipt_id . '.pdf';
		$html 							= rbfw_pro_create_pdf_tickets( $order_id );
		$mpdf 							= new \Mpdf\Mpdf();
		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
		$mpdf->autoScriptToLang 		= true;
		$mpdf->baseScript 				= 1;
		$mpdf->autoVietnamese 			= true;
		$mpdf->autoArabic 				= true;
		$mpdf->autoLangToFont 			= true;
		$mpdf->WriteHTML( $html );
		$mpdf->Output( $file_name, 'D' );
		exit;

	}

	function rbfw_pro_create_pdf_tickets( $order_id ) {
		$file_slug 		= 'order-pdf';
		$template_dir 	= sprintf( "%stemplates/%s.php", RBMW_PRO_PLUGIN_DIR, $file_slug );
		$template_dir 	= file_exists( $template_dir ) ? $template_dir : '';
		ob_start();
		include $template_dir;
		return ob_get_clean();

	}

	add_action( 'rbfw_pdf_attendee_info', 'rbfw_pdf_attendee_info_html' );
	function rbfw_pdf_attendee_info_html( $order_id ) {
		$reg_form_id = get_post_meta( $order_id, 'rbfw_reg_form_id', true ) ? get_post_meta( $order_id, 'rbfw_reg_form_id', true ) : 0;
		rbfw_pdf_show_attendee_info( $order_id, $reg_form_id );
	}

	add_action( 'rbfw_pdf_pin', 'rbfw_pdf_event_ticket_no_html' );
	function rbfw_pdf_event_ticket_no_html( $order_id ) {
		echo get_post_meta( $order_id, 'rbfw_pin', true );
	}

	add_action( 'rbfw_pdf_order_id', 'rbfw_pdf_event_order_id_html' );
	function rbfw_pdf_event_order_id_html( $order_id ) {
		echo $order_id;
	}

	add_action( 'rbfw_pdf_price', 'rbfw_pdf_event_ticket_price_html' );
	function rbfw_pdf_event_ticket_price_html( $order_id ) {
		echo wc_price( get_post_meta( $order_id, 'rbfw_duration_cost', true ) );
	}

	function rbfw_pdf_body_style() {}

	add_action( 'rbfw_pdf_logo', 'rbfw_display_pdf_logo' );
	function rbfw_display_pdf_logo() {
		$attachment_id = rbfw_get_option( 'rbfw_pdf_logo', 'rbfw_basic_pdf_settings', '' );
		$logo_url = wp_get_attachment_url( $attachment_id );
		if ( ! empty( $logo_url ) ) {
		 	echo "<img src=$logo_url />";
		}
	}

	add_action( 'rbfw_pdf_company_address', 'rbfw_display_pdf_address' );
	function rbfw_display_pdf_address() {
		$address = rbfw_get_option( 'rbfw_pdf_address', 'rbfw_basic_pdf_settings', '' );
		// Strip HTML tags and decode HTML entities to show clean text in PDF
		$clean_address = wp_strip_all_tags( $address );
		$clean_address = html_entity_decode( $clean_address, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
		echo $clean_address;
	}

	add_action( 'rbfw_pdf_company_phone', 'rbfw_display_pdf_phone' );
	function rbfw_display_pdf_phone() {
		$phone = rbfw_get_option( 'rbfw_pdf_phone', 'rbfw_basic_pdf_settings', '' );
		// Strip HTML tags and decode HTML entities to show clean text in PDF
		$clean_phone = wp_strip_all_tags( $phone );
		$clean_phone = html_entity_decode( $clean_phone, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
		echo $clean_phone;
	}

	add_action( 'rbfw_pdf_term_title', 'rbfw_display_pdf_terms_title' );
	function rbfw_display_pdf_terms_title() {
		$term_title = rbfw_get_option( 'rbfw_pdf_tc_title', 'rbfw_basic_pdf_settings', '' );
		// Strip HTML tags and decode HTML entities to show clean text in PDF
		$clean_title = wp_strip_all_tags( $term_title );
		$clean_title = html_entity_decode( $clean_title, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
		echo $clean_title;
	}

	add_action( 'rbfw_pdf_term_text', 'rbfw_display_pdf_terms_text' );
	function rbfw_display_pdf_terms_text() {
		$term_text = rbfw_get_option( 'rbfw_pdf_tc_text', 'rbfw_basic_pdf_settings', '' );
		if ( $term_text ) {
			// Strip HTML tags and decode HTML entities to show clean text in PDF
			$clean_text = wp_strip_all_tags( $term_text );
			$clean_text = html_entity_decode( $clean_text, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
			echo $clean_text;
		}
	}

	function rbfw_pro_get_pdf_ticket_attachment_file( $order_id = 0, $html = "", $download_pdf = true, $save_pdf = false ) {

		if (!is_plugin_active( 'magepeople-pdf-support-master/mage-pdf.php')) {
			return;
		}

		if ( $order_id == 0 ) {
			return new WP_Error( 'invalid_data', __( 'Invalid order id provided', 'rbfw-pro' ) );
		}
		$upload_dir = wp_upload_dir();
		$html = rbfw_pro_create_pdf_tickets( $order_id );
		$ticket_name = $upload_dir['basedir'] . '/' . __( 'Booking_Receipt_', 'rbfw-pro' ) . $order_id . '.pdf';
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->allow_charset_conversion = true;
		$mpdf->autoScriptToLang = true;
		$mpdf->baseScript = 1;
		$mpdf->autoVietnamese = true;
		$mpdf->autoArabic = true;
		$mpdf->autoLangToFont = true;
		$mpdf->WriteHTML( $html );
		$mpdf->Output( $ticket_name, 'F' );
		return $ticket_name;
	}

	function rbfw_pro_send_email($order_id) {

		if (empty( $order_id)) {
			return false;
		}

		$rbfw_attachment_email_status = get_post_meta($order_id, 'rbfw_attachment_email_status', true);

		if($rbfw_attachment_email_status == 'sent'){
			return;
		}

        $subject = rbfw_get_option( 'rbfw_email_subject', 'rbfw_basic_email_settings', 'Booking Confirmation Email' );
        $content = rbfw_get_option( 'rbfw_email_content', 'rbfw_basic_email_settings', 'Here is Booking Confirmation PDF Receipt' );

        // Decode HTML entities but KEEP HTML tags
        $pdf_email_content = html_entity_decode( $content, ENT_QUOTES | ENT_HTML5, 'UTF-8' );

        $from_email = rbfw_get_option( 'rbfw_email_from', 'rbfw_basic_email_settings', '' );
        $from_name = rbfw_get_option( 'rbfw_email_from_name', 'rbfw_basic_email_settings', '' );
        $email_status = rbfw_get_option( 'rbfw_email_status', 'rbfw_basic_email_settings', '' );

        if(!$email_status){
            $email_status = [];
        }

        $attachments = array();
        $headers = array(
            sprintf( "From: %s <%s>", $from_name, $from_email ),
            'Content-Type: text/html; charset=UTF-8', // Ensure HTML is rendered
        );

       if (in_array("processing", $email_status) || in_array("completed", $email_status))  {
            $attathment_file_url = rbfw_pro_get_pdf_ticket_attachment_file( $order_id, "", false, true );
            if ( ! is_wp_error( $attathment_file_url ) ) {
                $attachments[] = $attathment_file_url;
            }

            $email_address = get_post_meta($order_id, 'rbfw_billing_email', true);

            wp_mail( $email_address, $subject, $pdf_email_content, $headers, $attachments );
            update_post_meta($order_id, 'rbfw_attachment_email_status', 'sent');
        }
	}


	function rbfw_pdf_show_attendee_info( $attendee_id, $reg_form_id ) {
			?>
			<table class="rbfw_has_table_border">
				<thead>
					<tr>
						<td colspan="2"><?php rbfw_string('rbfw_text_billing_information',__('Billing Information','rbfw-pro')); ?></td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php rbfw_string('rbfw_text_name',__('Name','rbfw-pro')); echo ':'; ?></td>
						<td><?php echo get_post_meta( $attendee_id, 'rbfw_billing_name', true ); ?></td>
					</tr>
					<tr>
						<td><?php rbfw_string('rbfw_text_email',__('Email','rbfw-pro')); echo ':'; ?></td>
						<td><?php echo get_post_meta( $attendee_id, 'rbfw_billing_email', true ); ?></td>
					</tr>
					<?php if(!empty(get_post_meta( $attendee_id, 'rbfw_billing_phone', true ))): ?>
					<tr>
						<td><?php rbfw_string('rbfw_text_phone',__('Phone','rbfw-pro')); ?></td>
						<td><?php echo get_post_meta( $attendee_id, 'rbfw_billing_phone', true ); ?></td>
					</tr>
					<?php endif; ?>

					<?php if(!empty(get_post_meta( $attendee_id, 'rbfw_billing_address', true ))): ?>
					<tr>
						<td><?php rbfw_string('rbfw_text_address',__('Address','rbfw-pro')); ?></td>
						<td><?php echo get_post_meta( $attendee_id, 'rbfw_billing_address', true ); ?></td>
					</tr>
					<?php endif; ?>
				</tbody>
			</table>
			<?php
	}

	function rbfw_get_invoice_ajax_url( $args = array() ) {
		$default_args = array(
			'action' => 'generate_pdf',
			'document_type' => 'pdf',
			'order_id' => '',
		);
		$args = wp_parse_args( $args, $default_args );
		$build_url = http_build_query( $args );
		$nonce_url = wp_nonce_url( admin_url( "admin-ajax.php?" . $build_url ), $args['action'] );
		return apply_filters( 'wbtm_filters_invoice_ajax_url', $nonce_url );
	}


add_filter( 'woocommerce_my_account_my_orders_actions', 'rbfw_my_account_my_orders_custom_action', 10, 2 );
function rbfw_my_account_my_orders_custom_action( $actions, $order ) {

	$order_id = $order->get_id();
	$order          = wc_get_order( $order_id );
	$default_args = array(
		'action' => 'generate_pdf',
		'document_type' => 'pdf',
		'order_id' => $order_id,
	);
	$args = wp_parse_args( $default_args );
	$build_url = http_build_query( $args );
	$nonce_url = wp_nonce_url( admin_url( "admin-ajax.php?" . $build_url ), $args['action'] );

    $actions[] = array(
        'url'  => $nonce_url,
        'name' => rbfw_string_return('rbfw_text_download_booking_receipt',__('Download Booking Receipt','rbfw-pro')),
    );
    return $actions;
}

add_action('woocommerce_thankyou','rbfw_thankyou_page_pdf_btn');
add_action('rbfw_before_thankyou_page_info','rbfw_thankyou_page_pdf_btn');
add_action('rbfw_after_order_action_btn','rbfw_thankyou_page_pdf_btn');
function rbfw_thankyou_page_pdf_btn($order_id){

    $post = get_post($order_id);
    if ($post) {
        $parent_id = $post->post_parent;
        if ($parent_id) {
            if(isset($_COOKIE['parent_id']) && ($_COOKIE['parent_id'] == $parent_id) ) {
                return;
            }
            setcookie('parent_id', $parent_id);
            $order_id = $parent_id;
        }
    }

    $order_id = get_post_meta($order_id, '_rbfw_link_order_id', true);




	$default_args = array(
		'action' => 'generate_pdf',
		'document_type' => 'pdf',
		'order_id' => $order_id,
	);
	$args = wp_parse_args( $default_args );
	$build_url = http_build_query( $args );
	$nonce_url = wp_nonce_url( admin_url( "admin-ajax.php?" . $build_url ), $args['action'] );

	if (is_plugin_active( 'magepeople-pdf-support-master/mage-pdf.php')) {
		echo '<a href="'.$nonce_url.'" style="margin-top:20px;margin-bottom:20px;" class="rbfw_booking_receipt_btn"><i class="fa-solid fa-file-pdf"></i> '.rbfw_string_return('rbfw_text_download_booking_receipt',__('Download Booking Receipt','rbfw-pro')).'</a>';
	} 
	
	rbfw_pro_send_email($order_id);
}