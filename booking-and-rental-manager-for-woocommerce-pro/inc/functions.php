<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.


add_action( 'init', 'rbfw_pro_language_load');
function rbfw_pro_language_load() {
	$plugin_dir = basename( dirname( __DIR__ ) ) . "/languages/";
	load_plugin_textdomain( 'rbfw-pro', false, $plugin_dir );
}


add_action( 'admin_enqueue_scripts', 'rbfw_pro_admin_scripts' );
function rbfw_pro_admin_scripts() {

	wp_enqueue_script( 'jquery-ui-datepicker' );
	// Select2 END
	wp_enqueue_style( 'rbfw-admin_style_pro', plugin_dir_url( __DIR__ ) . 'css/admin_style.css', array(), time() );
	wp_enqueue_script( 'rbfw-admin_script_pro', plugin_dir_url( __DIR__ ) . 'js/admin.js', array( 'jquery' ), time(), true );
}


// Enqueue Scripts for frontend
add_action( 'wp_enqueue_scripts', 'rbfw_pro_frontend_scripts' );
function rbfw_pro_frontend_scripts() {	
	wp_enqueue_style('rbfw-event-form-builder-style-front', plugin_dir_url( __DIR__ ) . 'css/front-mep-form-builder.css', array() );
	wp_enqueue_script('rbfw-event-form-builder-scripts-front', plugin_dir_url( __DIR__ ) . 'js/front-mep-form-builder.js', array( 'jquery' ), time(), true );
	wp_enqueue_script('form_builder_same_attendee', plugin_dir_url( __DIR__ ) . 'js/same_attendee_script.js', array( 'jquery' ), time(), true );
	wp_enqueue_style( 'dashicons' );
}

// PRO tab menu list function
add_action('rbfw_tab_menu_list','rbfw_pro_tab_menu_list');
function rbfw_pro_tab_menu_list($post_id){

	$review_system = rbfw_get_option('rbfw_review_system', 'rbfw_basic_review_settings', 'on');

	if($review_system != 'on'){
		return;
	}

	?>
	<li><a href="#" class="rbfw-review rbfw-tab-a" data-id="review"><i class="fa-solid fa-comment-dots"></i></a></li>
	<?php
}

add_action('rbfw_dt_review_tab','rbfw_dt_review_tab_function');
add_action('rbfw_muff_review_tab','rbfw_dt_review_tab_function');
function rbfw_dt_review_tab_function($post_id){

	$review_system = rbfw_get_option('rbfw_review_system', 'rbfw_basic_review_settings', 'on');

	if($review_system != 'on'){
		return;
	}

	global $rbfw;
	$review_count = rbfw_review_count_comments_by_id($post_id);
	echo esc_html($rbfw->get_option('rbfw_text_reviews', 'rbfw_basic_translation_settings', __('Reviews','booking-and-rental-manager-for-woocommerce')));
	echo ' ('.$review_count.')';
}

// PRO Review Style Two Function
add_action('rbfw_dt_review_content','rbfw_pro_dt_tab_content_style_two');
function rbfw_pro_dt_tab_content_style_two($post_id){

	$review_system = rbfw_get_option('rbfw_review_system', 'rbfw_basic_review_settings', 'on');

	if($review_system != 'on'){
		return;
	}

	global $rbfw;

	?>
	<div class="rbfw_dt_row_reviews">
	<div class="rbfw_dt_review_write_btn_wrapper">	
		<button class="rbfw_dt_review_write_btn"><?php echo esc_html($rbfw->get_option('rbfw_text_write_review', 'rbfw_basic_translation_settings', __('Write Review','booking-and-rental-manager-for-woocommerce'))); ?></button>
	</div>	
	<!-- Start Comments Section -->
	<div class="rbfw-review-section">
		<?php
		rbfw_review_display_comments($post_id);
		echo rbfw_review_form($post_id);
		?>
	</div>
	<!-- End Comments Section -->
	<div class="rbfw_dt_review_load_more_btn_wrapper">
		<button class="rbfw_dt_review_load_more_btn"><?php echo esc_html($rbfw->get_option('rbfw_text_load_more_reviews', 'rbfw_basic_translation_settings', __('Load More Reviews','booking-and-rental-manager-for-woocommerce'))); ?></button>
		
	</div>
	</div>
	<script>
	jQuery(document).ready(function(){
		jQuery('.rbfw_dt_review_write_btn').click(function (e) {
			jQuery('.rbfw_dt_row_reviews .rbfw_review_form_wrapper').show();
			document.getElementById('rbfw_review_form_wrapper').scrollIntoView({
				behavior: 'smooth'
			});		
		});
		let load_review_btn = jQuery('.rbfw_dt_review_load_more_btn');
		jQuery('.rbfw_dt_row_reviews ul.rbfw-review-list li:first-child:not(ul.children li)').append(load_review_btn);

		load_review_btn.click(function (e) { 
			load_review_btn.remove();
			jQuery('.rbfw_dt_row_reviews ul.rbfw-review-list li').show();		
		});
	});	
	</script>
	<?php

}

add_action('rbfw_muff_review_content','rbfw_pro_muff_tab_content_style_two');
function rbfw_pro_muff_tab_content_style_two($post_id){

	$review_system = rbfw_get_option('rbfw_review_system', 'rbfw_basic_review_settings', 'on');

	if($review_system != 'on'){
		return;
	}

	global $rbfw;

	?>
	<div class="rbfw_muff_row_reviews">
	
		<!-- Start Comments Section -->
		<div class="rbfw-review-section">
			<?php
			rbfw_review_display_comments($post_id);
			echo rbfw_review_form($post_id);
			?>
		</div>
		<!-- End Comments Section -->
		<div class="rbfw_muff_review_load_more_btn_wrapper">
			<button class="rbfw_muff_review_load_more_btn"><?php echo esc_html($rbfw->get_option('rbfw_text_load_more_reviews', 'rbfw_basic_translation_settings', __('Load More Reviews','booking-and-rental-manager-for-woocommerce'))); ?></button>
		</div>
	</div>
	<script>
	jQuery(document).ready(function(){
		jQuery('.rbfw_muff_review_write_btn').click(function (e) {
			jQuery('.rbfw_muff_row_reviews .rbfw_review_form_wrapper').show();
			document.getElementById('rbfw_review_form_wrapper').scrollIntoView({
				behavior: 'smooth'
			});
		});
		let load_review_btn = jQuery('.rbfw_muff_review_load_more_btn');
		jQuery('.rbfw_muff_row_reviews ul.rbfw-review-list li:first-child:not(ul.children li)').append(load_review_btn);

		load_review_btn.click(function (e) {
			load_review_btn.remove();
			jQuery('.rbfw_muff_row_reviews ul.rbfw-review-list li').show();
		});
	});
	</script>
	<?php

}

// PRO tab menu content function
add_action('rbfw_tab_content','rbfw_pro_tab_content');
function rbfw_pro_tab_content($post_id){

	$review_system = rbfw_get_option('rbfw_review_system', 'rbfw_basic_review_settings', 'on');

	if($review_system != 'on'){
		return;
	}

	global $rbfw;

	?>
	<div class="rbfw-tab" data-id="review">
	<div class="rbfw-sub-heading"><?php echo esc_html($rbfw->get_option('rbfw_text_reviews', 'rbfw_basic_translation_settings', __('Reviews','rent-manager-for-woocommerce'))); ?></div>
	
	<!-- Start Comments Section -->
	<div class="rbfw-review-section">
		<?php
		rbfw_review_display_comments($post_id);
		echo rbfw_review_form($post_id);
		?>
	</div>
	<!-- End Comments Section -->
	</div>
	<?php

}

/******************************************
 * Function: Get User Display Name By Email
 * Developer: Ariful
******************************************/
function rbfw_get_user_name_by_email($email = null){
    if( empty($email) ){
        return false;
    }
    else{
        $user_obj = get_user_by('email', $email);
        
		if(!empty($user_obj->first_name) && !empty($user_obj->last_name)):
        	$name = $user_obj->first_name . ' ' . $user_obj->last_name;
		else:
			$name =  $user_obj->display_name;
		endif;

        return $name;
    }
}

/******************************************
 * Functions: MPDF plugin installation notice
 * Developer: Ariful
******************************************/


function rbfw_mpdf_plugin_install($slug = null){
	if(isset($_GET['rbfw_plugin_install']) && rbfw_free_chk_plugin_folder_exist($_GET['rbfw_plugin_install']) == false){
		$slug = $_GET['rbfw_plugin_install'];
		if($slug == 'magepeople-pdf-support-master'){
			include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
			include_once( ABSPATH . 'wp-admin/includes/file.php' );
			include_once( ABSPATH . 'wp-admin/includes/misc.php' );
			include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
			$upgrader = new Plugin_Upgrader( new Plugin_Installer_Skin() );
			$upgrader->install('https://github.com/magepeopleteam/magepeople-pdf-support/archive/master.zip');
		}
	}
}

function rbfw_wp_plugin_installation_url($slug){
	if($slug){

		$url = admin_url('plugins.php').'?rbfw_plugin_install='.$slug;			
	}
	else{

		$url = '';
	}

	return $url;
}

function rbfw_wp_plugin_activation_url($slug){

	$url = admin_url('plugins.php').'?rbfw_plugin_activate='.$slug;

	return $url;
}

add_action( 'admin_init', 'rbfw_plugin_activate' );
function rbfw_plugin_activate(){
	if(isset($_GET['rbfw_plugin_activate']) && !is_plugin_active( $_GET['rbfw_plugin_activate'] )){
		$slug = $_GET['rbfw_plugin_activate'];
		$activate = activate_plugin( $slug );
		$url = admin_url('plugins.php');
		echo '<script>
		var url = "'.$url.'";
		window.location.replace(url);
		</script>';
	}
	else{
		return false;
	}
}

add_action('admin_notices','rbfw_mpdf_notice');
function rbfw_mpdf_notice(){

    if (rbfw_woo_install_check() == 'Yes') {
	global $rbfw;
	$rbfw_send_pdf =  $rbfw->get_option('rbfw_send_pdf', 'rbfw_basic_pdf_settings', __('no','rbfw-pro'));
	if($rbfw_send_pdf == 'yes'):
		if(rbfw_free_chk_plugin_folder_exist('magepeople-pdf-support-master') == false):
		?>
		<div class="notice notice-error is-dismissible">
			<p><strong><?php _e( 'MagePeople PDF Support plugin is required.', 'rbfw-pro' ); ?></strong> <a class="rbfw_mpdf_install_btn" href="<?php echo esc_url(rbfw_wp_plugin_installation_url('magepeople-pdf-support-master')); ?>"><?php _e( 'Install', 'rbfw-pro' ); ?></a></p>
		</div>
		<?php
		endif;

		if(rbfw_free_chk_plugin_folder_exist('magepeople-pdf-support-master') == true && !is_plugin_active( 'magepeople-pdf-support-master/mage-pdf.php' )):
		?>
		<div class="notice notice-error is-dismissible">
			<p><strong><?php _e( 'MagePeople PDF Support plugin is not activated.', 'rbfw-pro' ); ?></strong> <a class="rbfw_mpdf_install_btn" href="<?php echo esc_url(rbfw_wp_plugin_activation_url('magepeople-pdf-support-master/mage-pdf.php')); ?>"><?php _e( 'Activate', 'rbfw-pro' ); ?></a></p>
		</div>
		<?php
		endif;
		rbfw_mpdf_plugin_install();
	endif;
    }
}
/******************************************
 * End MPDF plugin installation notice
******************************************/

add_filter('rbfw_payment_gateways','rbfw_payment_gateways_pro', 9);
function rbfw_payment_gateways_pro(){

	$pg = array(
		'offline' => 'Offline Payment',
		'paypal'  => 'Paypal Express',
		'stripe'  => 'Stripe'
	);

	return $pg;
}

/******************************************
 * Get Pro Plugin Data
******************************************/

if(!function_exists('rbfw_pro_get_plugin_data')) {
	function rbfw_pro_get_plugin_data($data) {
		if(rbfw_check_pro_active() == true){
			$dir_file = WP_PLUGIN_DIR . '/booking-and-rental-manager-for-woocommerce-pro/rent-pro.php';
			$get_rbfw_plugin_data = get_plugin_data($dir_file);
			$rbfw_data = $get_rbfw_plugin_data[$data];
			return $rbfw_data;

		} else {

			return false;
		}
	}
}

/******************************************
 * Get Registration Form List
******************************************/

function rbfw_pro_get_regf_list(){
	$args = array(
		'post_type' => 'rbfw_reg_form',
		'posts_per_page' => -1
	);

	$the_query = new WP_Query($args);
	$the_array = [];
	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$the_id = get_the_ID();
			$the_title = get_the_title();
			$the_array[$the_id] = $the_title;
		}
	}
	wp_reset_postdata();
	return $the_array;
}

/******************************************
 * Upload registration file through ajax
******************************************/
add_action('wp_ajax_nopriv_rbfw_regf_upload_file', 'rbfw_upload_from_path');
add_action('wp_ajax_rbfw_regf_upload_file', 'rbfw_upload_from_path' );

function rbfw_upload_from_path() {

	$image_url = $_FILES['file'];
	$path = preg_replace( '/wp-content(?!.*wp-content).*/', '', __DIR__ );
	require_once( $path . 'wp-load.php' );

	// it allows us to use wp_handle_upload() function
	require_once( ABSPATH . 'wp-admin/includes/file.php' );

	// you can add some kind of validation here
	if( empty( $image_url ) ) {
		wp_die( 'No files selected.' );
	}

	$upload = wp_handle_upload(
		$image_url,
		array( 'test_form' => false )
	);

	if( ! empty( $upload[ 'error' ] ) ) {
		wp_die( $upload[ 'error' ] );
	}

	// it is time to add our uploaded image into WordPress media library
	$attachment_id = wp_insert_attachment(
		array(
			'guid'           => $upload[ 'url' ],
			'post_mime_type' => $upload[ 'type' ],
			'post_title'     => basename( $upload[ 'file' ] ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		),
		$upload[ 'file' ]
	);

	if( is_wp_error( $attachment_id ) || ! $attachment_id ) {
		wp_die( 'Upload error.' );
	}

	// update medatata, regenerate image sizes
	require_once( ABSPATH . 'wp-admin/includes/image.php' );

	wp_update_attachment_metadata(
		$attachment_id,
		wp_generate_attachment_metadata( $attachment_id, $upload[ 'file' ] )
	);

	if($upload[ 'url' ])
	{
		$url = $upload[ 'url' ];
		echo json_encode(['url'=> $url]);
	}
	else{
		echo json_encode(['code'=>404, 'msg'=>'Some thing is wrong! Try again.']);
	}

	wp_die();
}