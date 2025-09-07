<?php
if (!defined('ABSPATH')) {
    die;
} // Cannot access pages directly.

function rbfw_review_form($post_id){
    $content  = '<div class="rbfw_review_form_wrapper" id="rbfw_review_form_wrapper">';
    $content .= '<div class="rbfw_write_review_heading">'.rbfw_string_return('rbfw_text_write_a_review',__('Write a Review','rbfw-pro')).'</div>';
    $content .= '<form method="POST" id="rbfw_review_form">';
    $content .= '<div class="rbfw-review-form-group">';
    $content .= '<input type="text" name="rbfw_review_title" placeholder="'.rbfw_string_return('rbfw_text_review_title',__('Review Title','rbfw-pro')).' *'.'" required>';
    $content .= '</div>';
    $content .= '<div class="rbfw-review-form-group">';
    $content .= '<textarea name="rbfw_review_msg" id="rbfw_review_msg" rows="10"  placeholder="'.rbfw_string_return('rbfw_text_review_description',__('Review Description','rbfw-pro')).' *'.'" required></textarea>';
    $content .= '</div>';

    if(! is_user_logged_in()){

        $content .= '<div class="rbfw-review-row">';
        $content .= '<div class="rbfw-review-col-6">';
        $content .= '<div class="rbfw-review-form-group">';
        $content .= '<input type="text" id="rbfw_review_author" name="rbfw_review_author"  placeholder="'.rbfw_string_return('rbfw_text_reviewer_name',__('Reviewer Name','rbfw-pro')).' *'.'"  required/>';
        $content .= '</div>';
        $content .= '</div>';
        $content .= '<div class="rbfw-review-col-6">';
        $content .= '<div class="rbfw-review-form-group">';
        $content .= '<input type="email" id="rbfw_review_email" name="rbfw_review_email" placeholder="'.rbfw_string_return('rbfw_text_reviewer_email',__('Reviewer Email','rbfw-pro')).' *'.'"  required/>';
        $content .= '</div>';
        $content .= '</div>';
        $content .= '</div>';

    } else {
        $current_user = wp_get_current_user();
        $email = $current_user->user_email;
        $author = $current_user->display_name;
        $content .= '<input type="hidden" name="rbfw_review_author" value="'.$author.'"/>';
        $content .= '<input type="hidden" name="rbfw_review_email" value="'.$email.'"/>';
    }

    $content .= '<div class="rbfw-review-row">';
    $content .= '<div class="rbfw-review-col-12">';
    $content .= '<div class="rbfw-review-form-group">';
    $content .= '<label class="rbfw-review-form-label">'.rbfw_string_return('rbfw_text_hygenic',__('Hygenic','rbfw-pro')).'</label>';

    $content .= '<div class="rbfw-review-input-group">';
    $content .= '<label class="rbfw_review_input_label"><input type="radio" name="rbfw_review_hygenic" value="1"/>'.rbfw_string_return('rbfw_text_terrible',__('Terrible','rbfw-pro')).'</label>';
    $content .= '<label class="rbfw_review_input_label"><input type="radio" name="rbfw_review_hygenic" value="2"/>'.rbfw_string_return('rbfw_text_poor',__('Poor','rbfw-pro')).'</label>';
    $content .= '<label class="rbfw_review_input_label"><input type="radio" name="rbfw_review_hygenic" value="3"/>'.rbfw_string_return('rbfw_text_average',__('Average','rbfw-pro')).'</label>';
    $content .= '<label class="rbfw_review_input_label"><input type="radio" name="rbfw_review_hygenic" value="4"/>'.rbfw_string_return('rbfw_text_very_good',__('Very Good','rbfw-pro')).'</label>';
    $content .= '<label class="rbfw_review_input_label"><input type="radio" name="rbfw_review_hygenic" value="5"/>'.rbfw_string_return('rbfw_text_excellent',__('Excellent','rbfw-pro')).'</label>';
    $content .= '</div>';

    $content .= '</div>';
    $content .= '</div>';

    $content .= '<div class="rbfw-review-col-12">';
    $content .= '<div class="rbfw-review-form-group">';
    $content .= '<label class="rbfw-review-form-label">'.rbfw_string_return('rbfw_text_quality',__('Quality','rbfw-pro')).'</label>';
    $content .= '<div class="rbfw-review-input-group">';
    $content .= '<label class="rbfw_review_input_label"><input type="radio" name="rbfw_review_quality" value="1"/>'.rbfw_string_return('rbfw_text_terrible',__('Terrible','rbfw-pro')).'</label>';
    $content .= '<label class="rbfw_review_input_label"><input type="radio" name="rbfw_review_quality" value="2"/>'.rbfw_string_return('rbfw_text_poor',__('Poor','rbfw-pro')).'</label>';
    $content .= '<label class="rbfw_review_input_label"><input type="radio" name="rbfw_review_quality" value="3"/>'.rbfw_string_return('rbfw_text_average',__('Average','rbfw-pro')).'</label>';
    $content .= '<label class="rbfw_review_input_label"><input type="radio" name="rbfw_review_quality" value="4"/>'.rbfw_string_return('rbfw_text_very_good',__('Very Good','rbfw-pro')).'</label>';
    $content .= '<label class="rbfw_review_input_label"><input type="radio" name="rbfw_review_quality" value="5"/>'.rbfw_string_return('rbfw_text_excellent',__('Excellent','rbfw-pro')).'</label>';
    $content .= '</div>';
    $content .= '</div>';
    $content .= '</div>';

    $content .= '<div class="rbfw-review-col-12">';
    $content .= '<div class="rbfw-review-form-group">';
    $content .= '<label class="rbfw-review-form-label">'.rbfw_string_return('rbfw_text_cost_value',__('Cost Value','rbfw-pro')).'</label>';
    $content .= '<div class="rbfw-review-input-group">';
    $content .= '<label class="rbfw_review_input_label"><input type="radio" name="rbfw_review_cost_value" value="1"/>'.rbfw_string_return('rbfw_text_terrible',__('Terrible','rbfw-pro')).'</label>';
    $content .= '<label class="rbfw_review_input_label"><input type="radio" name="rbfw_review_cost_value" value="2"/>'.rbfw_string_return('rbfw_text_poor',__('Poor','rbfw-pro')).'</label>';
    $content .= '<label class="rbfw_review_input_label"><input type="radio" name="rbfw_review_cost_value" value="3"/>'.rbfw_string_return('rbfw_text_average',__('Average','rbfw-pro')).'</label>';
    $content .= '<label class="rbfw_review_input_label"><input type="radio" name="rbfw_review_cost_value" value="4"/>'.rbfw_string_return('rbfw_text_very_good',__('Very Good','rbfw-pro')).'</label>';
    $content .= '<label class="rbfw_review_input_label"><input type="radio" name="rbfw_review_cost_value" value="5"/>'.rbfw_string_return('rbfw_text_excellent',__('Excellent','rbfw-pro')).'</label>';
    $content .= '</div>';
    $content .= '</div>';
    $content .= '</div>';

    $content .= '<div class="rbfw-review-col-12">';
    $content .= '<div class="rbfw-review-form-group">';
    $content .= '<label class="rbfw-review-form-label">'.rbfw_string_return('rbfw_text_staff',__('Staff','rbfw-pro')).'</label>';
    $content .= '<div class="rbfw-review-input-group">';
    $content .= '<label class="rbfw_review_input_label"><input type="radio" name="rbfw_review_staff" value="1"/>'.rbfw_string_return('rbfw_text_terrible',__('Terrible','rbfw-pro')).'</label>';
    $content .= '<label class="rbfw_review_input_label"><input type="radio" name="rbfw_review_staff" value="2"/>'.rbfw_string_return('rbfw_text_poor',__('Poor','rbfw-pro')).'</label>';
    $content .= '<label class="rbfw_review_input_label"><input type="radio" name="rbfw_review_staff" value="3"/>'.rbfw_string_return('rbfw_text_average',__('Average','rbfw-pro')).'</label>';
    $content .= '<label class="rbfw_review_input_label"><input type="radio" name="rbfw_review_staff" value="4"/>'.rbfw_string_return('rbfw_text_very_good',__('Very Good','rbfw-pro')).'</label>';
    $content .= '<label class="rbfw_review_input_label"><input type="radio" name="rbfw_review_staff" value="5"/>'.rbfw_string_return('rbfw_text_excellent',__('Excellent','rbfw-pro')).'</label>';
    $content .= '</div>';
    $content .= '</div>';
    $content .= '</div>';

    $content .= '<div class="rbfw-review-col-12">';
    $content .= '<div class="rbfw-review-form-group">';
    $content .= '<label class="rbfw-review-form-label">'.rbfw_string_return('rbfw_text_facilities',__('Facilities','rbfw-pro')).'</label>';
    $content .= '<div class="rbfw-review-input-group">';
    $content .= '<label class="rbfw_review_input_label"><input type="radio" name="rbfw_review_facilities" value="1"/>'.rbfw_string_return('rbfw_text_terrible',__('Terrible','rbfw-pro')).'</label>';
    $content .= '<label class="rbfw_review_input_label"><input type="radio" name="rbfw_review_facilities" value="2"/>'.rbfw_string_return('rbfw_text_poor',__('Poor','rbfw-pro')).'</label>';
    $content .= '<label class="rbfw_review_input_label"><input type="radio" name="rbfw_review_facilities" value="3"/>'.rbfw_string_return('rbfw_text_average',__('Average','rbfw-pro')).'</label>';
    $content .= '<label class="rbfw_review_input_label"><input type="radio" name="rbfw_review_facilities" value="4"/>'.rbfw_string_return('rbfw_text_very_good',__('Very Good','rbfw-pro')).'</label>';
    $content .= '<label class="rbfw_review_input_label"><input type="radio" name="rbfw_review_facilities" value="5"/>'.rbfw_string_return('rbfw_text_excellent',__('Excellent','rbfw-pro')).'</label>';
    $content .= '</div>';
    $content .= '</div>';
    $content .= '</div>';

    $content .= '<div class="rbfw-review-col-12">';
    $content .= '<div class="rbfw-review-form-group">';
    $content .= '<label class="rbfw-review-form-label">'.rbfw_string_return('rbfw_text_comfort',__('Comfort','rbfw-pro')).'</label>';
    $content .= '<div class="rbfw-review-input-group">';
    $content .= '<label class="rbfw_review_input_label"><input type="radio" name="rbfw_review_comfort" value="1"/>'.rbfw_string_return('rbfw_text_terrible',__('Terrible','rbfw-pro')).'</label>';
    $content .= '<label class="rbfw_review_input_label"><input type="radio" name="rbfw_review_comfort" value="2"/>'.rbfw_string_return('rbfw_text_poor',__('Poor','rbfw-pro')).'</label>';
    $content .= '<label class="rbfw_review_input_label"><input type="radio" name="rbfw_review_comfort" value="3"/>'.rbfw_string_return('rbfw_text_average',__('Average','rbfw-pro')).'</label>';
    $content .= '<label class="rbfw_review_input_label"><input type="radio" name="rbfw_review_comfort" value="4"/>'.rbfw_string_return('rbfw_text_very_good',__('Very Good','rbfw-pro')).'</label>';
    $content .= '<label class="rbfw_review_input_label"><input type="radio" name="rbfw_review_comfort" value="5"/>'.rbfw_string_return('rbfw_text_excellent',__('Excellent','rbfw-pro')).'</label>';
    $content .= '</div>';
    $content .= '</div>';
    $content .= '</div>';

    $content .= '</div>';
    $content .= '<button type="submit" class="rbfw_review_submit_btn">'.rbfw_string_return('rbfw_text_submit_review',__('Submit Review','rbfw-pro')).' </button>';
    $content .= '<input type="hidden" name="action" value="rbfw_submit_review"/>';
    $content .= '<input type="hidden" name="rbfw_review_item_id" value="'.$post_id.'"/>';
    ob_start();
    wp_nonce_field( 'rbfw_submit_review', 'rbfw_submit_review_nonce' );
    $content .= ob_get_clean();
    $content .= '</form>';
    $content .= '</div>';
    return $content;
}

add_action( 'wp_ajax_rbfw_submit_review', 'rbfw_submit_review_func' );
add_action( 'wp_ajax_nopriv_rbfw_submit_review', 'rbfw_submit_review_func' );
function rbfw_submit_review_func(){
    check_ajax_referer( 'rbfw_submit_review', 'rbfw_submit_review_nonce' );
    $review_title   = isset($_POST['rbfw_review_title']) ? sanitize_text_field($_POST['rbfw_review_title']) : '';
    $review_msg     = isset($_POST['rbfw_review_msg']) ? sanitize_textarea_field($_POST['rbfw_review_msg']) : '';
    $review_author  = isset($_POST['rbfw_review_author']) ? sanitize_text_field($_POST['rbfw_review_author']) : '';
    $review_email   = isset($_POST['rbfw_review_email']) ? sanitize_email($_POST['rbfw_review_email']) : '';
    $review_item_id = isset($_POST['rbfw_review_item_id']) ? sanitize_text_field($_POST['rbfw_review_item_id']) : '';
    $review_hygenic = isset($_POST['rbfw_review_hygenic']) ? sanitize_text_field($_POST['rbfw_review_hygenic']) : '';
    $review_quality = isset($_POST['rbfw_review_quality']) ? sanitize_text_field($_POST['rbfw_review_quality']) : '';
    $review_cost_value = isset($_POST['rbfw_review_cost_value']) ? sanitize_text_field($_POST['rbfw_review_cost_value']) : '';
    $review_staff = isset($_POST['rbfw_review_staff']) ? sanitize_text_field($_POST['rbfw_review_staff']) : '';
    $review_facilities = isset($_POST['rbfw_review_facilities']) ? sanitize_text_field($_POST['rbfw_review_facilities']) : '';
    $review_comfort = isset($_POST['rbfw_review_comfort']) ? sanitize_text_field($_POST['rbfw_review_comfort']) : '';

    $args = array(
        'post_type'     => 'rbfw_item_reviews',
        'post_status'   => 'pending'
    );

    $review_id = wp_insert_post( $args );

    if(!empty($review_id)){

        update_post_meta($review_id, 'rbfw_review_title', $review_title);
        update_post_meta($review_id, 'rbfw_review_msg', $review_msg);
        update_post_meta($review_id, 'rbfw_review_author', $review_author);
        update_post_meta($review_id, 'rbfw_review_email', $review_email);
        update_post_meta($review_id, 'rbfw_review_item_id', $review_item_id);
        update_post_meta($review_id, 'rbfw_review_hygenic', $review_hygenic);
        update_post_meta($review_id, 'rbfw_review_quality', $review_quality);
        update_post_meta($review_id, 'rbfw_review_cost_value', $review_cost_value);
        update_post_meta($review_id, 'rbfw_review_staff', $review_staff);
        update_post_meta($review_id, 'rbfw_review_facilities', $review_facilities);
        update_post_meta($review_id, 'rbfw_review_comfort', $review_comfort);

    }

    wp_die();
}

function rbfw_review_count_comments_by_id($post_id){
    if(empty($post_id)){
        return;
    }

    $args = array(
		'post_type' => 'rbfw_item_reviews',
		'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => 'rbfw_review_item_id',
                'value' => $post_id,
                'compare' => '==',
            )
        )
	);
    $the_query = new WP_Query($args);
    $count = $the_query->found_posts;
    return $count;
}

function rbfw_review_get_progress_bar_width($value){

    if(($value >= 0.1) && ($value <= 0.2)){
        $width = '20%';
    }elseif(($value >= 0.3) && ($value <= 0.4)){
        $width = '40%';
    }elseif(($value >= 0.5) && ($value <= 0.6)){
        $width = '60%';
    }elseif(($value >= 0.7) && ($value <= 0.8)){
        $width = '80%';
    }elseif(($value >= 0.9) && ($value <= 1)){
        $width = '100%';
    } else {
        $width = '0%';
    }

    $result = 'style="width:'.$width.'"';
    return $result;
}

function rbfw_review_value_round($value){

    if(($value >= 0.1) && ($value <= 0.2)){
        $result = 1;
    }elseif(($value >= 0.3) && ($value <= 0.4)){
        $result = 2;
    }elseif(($value >= 0.5) && ($value <= 0.6)){
        $result = 3;
    }elseif(($value >= 0.7) && ($value <= 0.8)){
        $result = 4;
    }elseif(($value >= 0.9) && ($value <= 1)){
        $result = 5;
    } else {
        $result = 0;
    }

    return $result;
}

function rbfw_review_get_average_by_id($post_id, $type = null){

    if(empty($post_id)){
        return;
    }

    $args = array(
		'post_type' => 'rbfw_item_reviews',
		'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => 'rbfw_review_item_id',
                'value' => $post_id,
                'compare' => '==',
            )
        )
	);

	$the_query = new WP_Query($args);
    $review_count = rbfw_review_count_comments_by_id($post_id);
    $total_review = $review_count * 30;
    $total_rating = 0;


	if ( $the_query->have_posts() ) {

		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$review_id = get_the_ID();
            $review_title = get_post_meta($review_id, 'rbfw_review_title', true);
            $review_msg = get_post_meta($review_id, 'rbfw_review_msg', true);
            $review_author = get_post_meta($review_id, 'rbfw_review_author', true);
            $review_email = get_post_meta($review_id, 'rbfw_review_email', true);
            $review_item_id = get_post_meta($review_id, 'rbfw_review_item_id', true);
            $review_hygenic = !empty(get_post_meta($review_id, 'rbfw_review_hygenic', true)) ? get_post_meta($review_id, 'rbfw_review_hygenic', true) : 0;
            $review_quality = !empty(get_post_meta($review_id, 'rbfw_review_quality', true)) ? get_post_meta($review_id, 'rbfw_review_quality', true) : 0;
            $review_cost_value = !empty(get_post_meta($review_id, 'rbfw_review_cost_value', true)) ? get_post_meta($review_id, 'rbfw_review_cost_value', true) : 0;
            $review_staff = !empty(get_post_meta($review_id, 'rbfw_review_staff', true)) ? get_post_meta($review_id, 'rbfw_review_staff', true) : 0;
            $review_facilities = !empty(get_post_meta($review_id, 'rbfw_review_facilities', true)) ? get_post_meta($review_id, 'rbfw_review_facilities', true) : 0;
            $review_comfort = !empty(get_post_meta($review_id, 'rbfw_review_comfort', true)) ? get_post_meta($review_id, 'rbfw_review_comfort', true) : 0;
            $review_created_date = get_the_date('Y/m/d h:i a', $review_id);

            if($type == 'hygenic'){
                $total_rating += $review_hygenic;
            } elseif($type == 'quality'){
                $total_rating += $review_quality;
            } elseif($type == 'cost_value'){
                $total_rating += $review_cost_value;
            } elseif($type == 'staff'){
                $total_rating += $review_staff;
            } elseif($type == 'facilities'){
                $total_rating += $review_facilities;
            } elseif($type == 'comfort'){
                $total_rating += $review_comfort;
            } else {
                $total_rating += $review_hygenic + $review_quality + $review_cost_value + $review_staff + $review_facilities + $review_comfort;
            }
		}

	}
	wp_reset_postdata();

    $average_rating = 0;
    $percentage = 0;

    if($total_rating > 0){
        $percentage = ($total_rating * 100) / $total_review;
    }

    $average_rating = (($percentage * 5) / 100);
    $average_rating = rbfw_round_up($average_rating, 1);
    return $average_rating;
}

function rbfw_round_up($value, $places=0) {
    if ($places < 0) { $places = 0; }
    $mult = pow(10, $places);
    return ceil($value * $mult) / $mult;
}
/*****************************************************
* Get average review rating stars
******************************************************/
function rbfw_review_display_average_rating($post_id = null, $template = null, $style = null) {

	global $post,$rbfw;
	$rating_score_label = $rbfw->get_option('rbfw_text_rating_score', 'rbfw_basic_translation_settings', __('Rating Score','rent-manager-for-woocommerce'));
	$total_reviews_label = $rbfw->get_option('rbfw_text_total_reviews', 'rbfw_basic_translation_settings', __('Total Reviews','rent-manager-for-woocommerce'));
	$rated_by_label = $rbfw->get_option('rbfw_text_rated_by', 'rbfw_basic_translation_settings', __('Rated By','rbfw-pro'));
	$user_label = $rbfw->get_option('rbfw_text_user', 'rbfw_basic_translation_settings', __('User','rbfw-pro'));

	if(!empty($post_id)){
		$post_id = $post_id;
	} else {
		$post_id = $post->ID;
	}

	$stars   = '';

    $total_reviews = rbfw_review_count_comments_by_id($post_id);
	$average = rbfw_review_get_average_by_id($post_id);

	if($total_reviews > 1){
		$user_label = $rbfw->get_option('rbfw_text_users', 'rbfw_basic_translation_settings', __('Users','rbfw-pro'));
	}

	if($total_reviews > 0):
		for ( $i = 1; $i <= $average + 1; $i++ ) {

			$width = intval( $i - $average > 0 ? 16 - ( ( $i - $average ) * 16 ) : 16 );

			if ( 0 === $width ) {
				continue;
			}

			$stars .= '<span style="overflow:hidden; width:' . $width . 'px" class="dashicons dashicons-star-filled"></span>';

			if ( $i - $average > 0 ) {
				$stars .= '<span style="overflow:hidden; position:relative; left:-' . $width .'px; width:16px;" class="dashicons dashicons-star-empty"></span>';
			}

		}

		if(5 - $average > 0){
			$count = 5 - ceil($average);

			for ( $d = 1; $d <= $count; $d++ ) {
				$stars .= '<span style="overflow:hidden; position:relative; left:-' . $width .'px;width: 16px;" class="dashicons dashicons-star-empty"></span>';
			}
		}

		$custom_content   = '<div class="rbfw-review-rating-wrapper">';

		if($template == 'muffin'){
			if($total_reviews > 2){
				$total_reviews = ($total_reviews - 1).'+';
			}
			$custom_content  .= '<div class="rbfw-star-rating">' . $stars .'</div>';
			if($style == 'style1'){
				$custom_content  .= '<div class="rbfw-review-rating-info">('.$average.'/5) '.$rated_by_label.' ' . $total_reviews .' '.$user_label.'</div>';
			}
			if($style == 'style2'){
				$custom_content  .= '<div class="rbfw-review-rating-info">'.$rated_by_label.' ' . $total_reviews .' '.$user_label.'</div>';
			}
		} else {
			$custom_content  .= '<div class="rbfw-star-rating">' . $stars .'</div>';
			$custom_content  .= '<div class="rbfw-review-rating-info">'.$rating_score_label.': ' . $average .' | '.$total_reviews_label.': ' . $total_reviews .'</div>';
		}

		$custom_content  .= '</div>';
	else:
		$custom_content = '';
	endif;

	return $custom_content;
}

function rbfw_review_display_comments($post_id){

    if(empty($post_id)){
        return;
    }

    $args = array(
		'post_type' => 'rbfw_item_reviews',
		'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => 'rbfw_review_item_id',
                'value' => $post_id,
                'compare' => '==',
            )
        )
	);

	$the_query = new WP_Query($args);

	if ( $the_query->have_posts() ) {
        echo '<ul class="rbfw-review-list">';
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$review_id = get_the_ID();
            $review_title = get_post_meta($review_id, 'rbfw_review_title', true);
            $review_msg = get_post_meta($review_id, 'rbfw_review_msg', true);
            $review_author = get_post_meta($review_id, 'rbfw_review_author', true);
            $review_email = get_post_meta($review_id, 'rbfw_review_email', true);
            $review_item_id = get_post_meta($review_id, 'rbfw_review_item_id', true);
            $review_hygenic = !empty(get_post_meta($review_id, 'rbfw_review_hygenic', true)) ? get_post_meta($review_id, 'rbfw_review_hygenic', true) : 0;
            $review_quality = !empty(get_post_meta($review_id, 'rbfw_review_quality', true)) ? get_post_meta($review_id, 'rbfw_review_quality', true) : 0;
            $review_cost_value = !empty(get_post_meta($review_id, 'rbfw_review_cost_value', true)) ? get_post_meta($review_id, 'rbfw_review_cost_value', true) : 0;
            $review_staff = !empty(get_post_meta($review_id, 'rbfw_review_staff', true)) ? get_post_meta($review_id, 'rbfw_review_staff', true) : 0;
            $review_facilities = !empty(get_post_meta($review_id, 'rbfw_review_facilities', true)) ? get_post_meta($review_id, 'rbfw_review_facilities', true) : 0;
            $review_comfort = !empty(get_post_meta($review_id, 'rbfw_review_comfort', true)) ? get_post_meta($review_id, 'rbfw_review_comfort', true) : 0;
            $review_created_date = get_the_date('Y/m/d h:i a', $review_id);
            $rating = $review_hygenic + $review_quality + $review_cost_value + $review_staff + $review_facilities + $review_comfort;
            $rating = round($rating / 5);
            $author_obj = get_user_by('email', $review_email);
            if(!empty($author_obj)){
                $user_id = $author_obj->ID;
                $avatar = get_avatar($user_id, 90);
                $avatar_default = get_option('avatar_default');
            }
            ?>

            <li class="rbfw-review-comment-list-item">
                <div class="rbfw-single-review-row" id="comment-<?php echo $post_id; ?>">
                        <div class="rbfw-single-review-col-2">
                            <div class="rbfw-single-review-info">
                                <div class="rbfw-single-review-info-header">
                                    <div class="rbfw-review-author">
                                        <?php if(isset($avatar) && $avatar && $avatar_default != 'blank'){ ?>
                                            <span><?php echo $avatar; ?></span>
                                        <?php } ?>
                                        <span><i class="fas fa-user-circle"></i> <?php echo $review_author; ?></span>
                                        <span class="rbfw-review-time"><i class="fas fa-clock"></i> <?php echo $review_created_date; ?></span>
                                    </div>
                                </div>
                                <div class="rbfw-review-content">
                                    <div class="rbfw-review-title"><?php echo $review_title; ?></div>
                                    <div class="rbfw-review-stars">
                                    <?php
                                    if($rating > 0):
                                        $unrating = 5 - (int)$rating;
                                        $stars = '';
                                        for ( $i = 1; $i <= $rating; $i++ ) {
                                            $stars .= '<span class="dashicons dashicons-star-filled"></span>';
                                        }
                                        for ( $i = 1; $i <= $unrating; $i++ ) {
                                            $stars .= '<span class="dashicons dashicons-star-empty"></span>';
                                        }

                                        echo $stars;
                                    endif;
                                    ?>
                                    </div>
                                    <div class="rbfw-review-text">
                                        <?php echo $review_msg; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </li>

            <?php
		}
        echo '</ul>';
	}
	wp_reset_postdata();
}

add_action( 'wp_footer', 'rbfw_submit_review_script_func' );
function rbfw_submit_review_script_func(){
    $review_success_msg = rbfw_string_return('rbfw_text_review_success_msg',__('Review has been submitted. It\'s pending for approval.','rbfw-pro'));
    ?>
    <script>
        jQuery(function () {
            jQuery("#rbfw_review_form").submit(function (e) {
                e.preventDefault();
                let data = jQuery(this).serialize();
                let review_success_msg = "<?php echo $review_success_msg; ?>";
                jQuery.ajax({
                    type: "POST",
                    url: rbfw_ajax_url,
                    data: data,
                    beforeSend: function() {
                        jQuery('.rbfw_review_submit_btn').append(' <i class="fas fa-spinner fa-spin"></i>');
                    },
                    success: function (response) {
                        jQuery('.rbfw_review_submit_btn i').remove();
                        jQuery('#rbfw_review_form')[0].reset();
                        jQuery('<div class="mps_alert_success">'+review_success_msg+'</div>').insertAfter('.rbfw_review_submit_btn');
                    }
                });
            });
        });
    </script>
    <?php
}

add_action( 'wp_ajax_rbfw_review_unapproved_count', 'rbfw_review_pending_number_ajax_func' );
function rbfw_review_pending_number_ajax_func() {
    $type = "rbfw_item_reviews";
    $args = array(
        'numberposts'   => -1,
        'post_type'     => $type,
        'post_status'   => array('pending'),
    );
    $pending_count = count( get_posts( $args ) );
    $html = '';

    $html .= " <span class='rbfw_review_count_wrap'><span class='update-plugins count-$pending_count'><span class='plugin-count'>" . number_format_i18n($pending_count) . '</span></span></span>';

    echo $html;

    wp_die();
}

function rbfw_review_pending_number() {
    $type = "rbfw_item_reviews";
    $args = array(
        'numberposts'   => -1,
        'post_type'     => $type,
        'post_status'   => array('pending'),
    );
    $pending_count = count( get_posts( $args ) );
    $html = '';

    $html .= " <span class='rbfw_review_count_wrap'><span class='update-plugins count-$pending_count'><span class='plugin-count'>" . number_format_i18n($pending_count) . '</span></span></span>';

    return $html;
}

add_filter( 'manage_posts_columns', 'rbfw_review_custom_post_columns', 10, 2 );
function rbfw_review_custom_post_columns( $columns, $post_type ) {

  switch ( $post_type ) {

    case 'rbfw_item':
    unset( $columns['comments'] );
    unset( $columns['rbfw_type'] );
    break;

    case 'rbfw_item_reviews':
    unset( $columns['comments'], $columns['date'], $columns['title'] );
    $columns['rbfw_col_author'] = __('Author','rbfw-pro');
    $columns['rbfw_col_rating'] = __('Rating','rbfw-pro');
    $columns['rbfw_col_review'] = __('Review','rbfw-pro');
    $columns['rbfw_col_item'] = __('Item','rbfw-pro');
    $columns['rbfw_col_date'] = __('Submitted on','rbfw-pro');
    $columns['rbfw_col_action'] = __('Action','rbfw-pro');
    break;

  }
  return $columns;
}

add_action('manage_posts_custom_column', 'rbfw_review_display_custom_column_data', 10, 2);
function rbfw_review_display_custom_column_data($column_name, $post_id)
{
    $post_type = get_post_type($post_id);

    if ('rbfw_col_item' == $column_name && $post_type == 'rbfw_item_reviews') {
        $review_item_id = get_post_meta($post_id, 'rbfw_review_item_id', true);
        $review_item = !empty($review_item_id) ? '<a href="'.get_the_permalink($review_item_id).'">'.get_the_title($review_item_id).'</a>' : '';
        echo $review_item;
    }

    if ('rbfw_col_rating' == $column_name && $post_type == 'rbfw_item_reviews') {

        $review_hygenic = !empty(get_post_meta($post_id, 'rbfw_review_hygenic', true)) ? get_post_meta($post_id, 'rbfw_review_hygenic', true) : 0;
        $review_quality = !empty(get_post_meta($post_id, 'rbfw_review_quality', true)) ? get_post_meta($post_id, 'rbfw_review_quality', true) : 0;
        $review_cost_value = !empty(get_post_meta($post_id, 'rbfw_review_cost_value', true)) ? get_post_meta($post_id, 'rbfw_review_cost_value', true) : 0;
        $review_staff = !empty(get_post_meta($post_id, 'rbfw_review_staff', true)) ? get_post_meta($post_id, 'rbfw_review_staff', true) : 0;
        $review_facilities = !empty(get_post_meta($post_id, 'rbfw_review_facilities', true)) ? get_post_meta($post_id, 'rbfw_review_facilities', true) : 0;
        $review_comfort = !empty(get_post_meta($post_id, 'rbfw_review_comfort', true)) ? get_post_meta($post_id, 'rbfw_review_comfort', true) : 0;
        $rating = $review_hygenic + $review_quality + $review_cost_value + $review_staff + $review_facilities + $review_comfort;
        $rating = round($rating / 6);

        if($rating > 0):
            echo '<div class="rbfw-review-stars">';
            $unrating = 5 - (int)$rating;
            $stars = '';
            for ( $i = 1; $i <= $rating; $i++ ) {
                $stars .= '<span class="dashicons dashicons-star-filled"></span>';
            }
            for ( $d = 1; $d <= $unrating; $d++ ) {
                $stars .= '<span class="dashicons dashicons-star-empty"></span>';
            }

            echo $stars;

            echo '</div>';
        endif;
    }

    if ('rbfw_col_author' == $column_name && $post_type == 'rbfw_item_reviews') {

        $review_email = get_post_meta($post_id, 'rbfw_review_email', true);
        $user_data = get_user_by('email', $review_email);
        if(!empty($user_data)){

            $user_name = $user_data->display_name;
            $user_edit_link = get_edit_user_link($user_data->ID);
            echo '<a href="'.esc_url($user_edit_link).'">'.$user_name.'</a><br>';
            echo $review_email;

        } else {

            echo $review_email;
        }
    }
    if ('rbfw_col_review' == $column_name && $post_type == 'rbfw_item_reviews') {
        $review_title = get_post_meta($post_id, 'rbfw_review_title', true);
        $review_msg = get_post_meta($post_id, 'rbfw_review_msg', true);
        echo '<b>'.$review_title.'</b><br>';
        echo $review_msg;
    }
    if ('rbfw_col_date' == $column_name && $post_type == 'rbfw_item_reviews') {
        $post_created_date = get_the_date('Y/m/d h:i a', $post_id);
        echo $post_created_date;
    }

    if ('rbfw_col_action' == $column_name && $post_type == 'rbfw_item_reviews') {

        $post_status = get_post_status($post_id);
        $action_btn = '<a data-request="approve" data-id="'.$post_id.'" class="rbfw_review_approve_btn"'; if($post_status == 'pending'){ $action_btn .= 'style="display:inline-block"'; } $action_btn .='>'.__('Approve','rbfw-pro').'</a>';
        $action_btn .= '<a data-request="unapprove" data-id="'.$post_id.'" class="rbfw_review_unapprove_btn" '; if($post_status == 'publish'){ $action_btn .= 'style="display:inline-block"'; } $action_btn .='>'.__('Unapprove','rbfw-pro').'</a>';
        echo $action_btn;
    }
}

add_action('admin_footer','rbfw_review_script_func');
function rbfw_review_script_func(){
    ?>
    <script>
        jQuery(function () {
            jQuery(document).on('click', '.rbfw_review_approve_btn,.rbfw_review_unapprove_btn', function(e) {
                e.preventDefault();
                let post_id = jQuery(this).attr('data-id');
                let data_request = jQuery(this).attr('data-request');

                jQuery.ajax({
                    type: "POST",
                    url: rbmw_ajax_url,
                    data: {
                        'action' : 'rbfw_review_status_update',
                        'post_id' : post_id,
                        'data_request' : data_request
                    },
                    beforeSend: function() {
                        jQuery('.rbfw_review_approve_btn[data-id='+post_id+'],.rbfw_review_unapprove_btn[data-id='+post_id+']').append(' <i class="fas fa-spinner fa-spin"></i>');
                    },
                    success: function (response) {

                        jQuery('.rbfw_review_approve_btn[data-id='+post_id+'] i,.rbfw_review_unapprove_btn[data-id='+post_id+'] i').remove();

                        if(response == 'approved'){
                            jQuery('.rbfw_review_approve_btn[data-id='+post_id+']').hide();
                            jQuery('.rbfw_review_unapprove_btn[data-id='+post_id+']').css('display', 'inline-block');
                            jQuery('#post-'+post_id).removeClass('status-pending').addClass('status-publish');
                        }
                        if(response == 'unapproved'){
                            jQuery('.rbfw_review_approve_btn[data-id='+post_id+']').css('display', 'inline-block');
                            jQuery('.rbfw_review_unapprove_btn[data-id='+post_id+']').hide();
                            jQuery('#post-'+post_id).removeClass('status-publish').addClass('status-pending');
                        }

                        jQuery.ajax({
                            type: "POST",
                            url: rbmw_ajax_url,
                            data: {
                                'action' : 'rbfw_review_unapproved_count',
                            },
                            success: function (response) {
                                jQuery('span.rbfw_review_count_wrap .rbfw_review_count_wrap').remove();
                                jQuery('span.rbfw_review_count_wrap').empty().append(response);
                            }
                        });
                    }
                });
            });
        });
    </script>
    <?php
}

add_action( 'wp_ajax_rbfw_review_status_update', 'rbfw_review_status_update_func' );
function rbfw_review_status_update_func(){

    if(!isset($_POST['post_id'])){
        return;
    }

    $post_id = $_POST['post_id'];
    $data_request = $_POST['data_request'];
    $status = '';

    if($data_request == 'approve'){
        $status = 'publish';
    }

    if($data_request == 'unapprove'){
        $status = 'pending';
    }

    $args = array(
		'post_type' => 'rbfw_item_reviews',
		'posts_per_page' => -1
	);

    $data = array(
        'ID' => $post_id,
        'post_status' => $status
    );

    $updated = wp_update_post($data);

    if($updated && $data_request == 'approve'){
        echo 'approved';
    }

    if($updated && $data_request == 'unapprove'){
        echo 'unapproved';
    }

    wp_die();
}

add_filter('post_row_actions','rbfw_review_remove_row_actions',10,2);
function rbfw_review_remove_row_actions( $actions, $post ) {
    $post_id = $post->ID;
    $post_type = get_post_type($post_id);

    if ($post_type == 'rbfw_item_reviews') {
        unset($actions['inline hide-if-no-js'],$actions['edit'],$actions['view']);
    }
    return $actions;
}
