<?php
/*
* Author 	:	MagePeople Team
* Developer :   Ariful
* Version	:	1.0.0
*/

if ( ! defined( 'ABSPATH' ) ) {
    die;
} // Cannot access pages directly.

if ( ! class_exists( 'RBFW_MPS_Stripe_Form' ) ) {
    class RBFW_MPS_Stripe_Form {
        public function __construct(){
            add_action('wp_ajax_rbfw_mps_stripe_form', array($this, 'rbfw_mps_stripe_form'));
            add_action('wp_ajax_nopriv_rbfw_mps_stripe_form', array($this,'rbfw_mps_stripe_form'));                          
        }

        public function rbfw_mps_stripe_form(){
            check_ajax_referer( 'rbfw_mps_place_order_form_submit', 'security' );

            global $rbfw;
            $publishable_key = $rbfw->get_option('rbfw_mps_stripe_publishable_key', 'rbfw_basic_payment_settings');

            define('STRIPE_PUBLISHABLE_KEY', $publishable_key);

            if (empty($publishable_key)) {
                wp_die();
            }
            $post_id = !empty($_POST['post_id']) ? $_POST['post_id'] : '';
            /* Start: Registration Form Variables */
            $rbfw_regf_info = !empty($_POST['rbfw_regf_info']) ? $_POST['rbfw_regf_info'] : [];
            $rbfw_regf_checkboxes[0] = !empty($_POST['rbfw_regf_checkboxes']) ? $_POST['rbfw_regf_checkboxes'] : [];
            $rbfw_regf_radio[0] = !empty($_POST['rbfw_regf_radio']) ? $_POST['rbfw_regf_radio'] : [];
            $rbfw_regf_info = array_merge($rbfw_regf_info, $rbfw_regf_checkboxes, $rbfw_regf_radio);
            $rbfw_regf_info = !empty($rbfw_regf_info) ? array_reduce($rbfw_regf_info, 'array_merge', array()) : [];

            if(class_exists('Rbfw_Reg_Form')){
                $ClassRegForm = new Rbfw_Reg_Form();
                $rbfw_regf_info = $ClassRegForm->rbfw_organize_regf_value_array_mps_func($post_id, $rbfw_regf_info);
            }

            $rbfw_regf_info = json_encode($rbfw_regf_info);
            /* End: Registration Form Variables */
            ?>
            
            <div class="rbfw_stripe_form_wrap">
            <form  method="post" id="rbfw_stripe_form">
                <div class="rbfw_stripe-form-row">
                    <label for="rbfw-stripe-card-element"><?php echo esc_html($rbfw->get_option('rbfw_text_credit_debit_card', 'rbfw_basic_translation_settings', __('Credit or debit card','booking-and-rental-manager-for-woocommerce'))); ?></label>
                    <div id="rbfw-stripe-card-element">
                    <!-- A Stripe Element will be inserted here. -->
                    </div>
            
                    <!-- Used to display form errors. -->
                    <div id="rbfw-stripe-card-errors" role="alert"></div>
                </div>
                <button class="rbfw_mps_stripe_pay_button"><?php esc_html_e('Pay Now','booking-and-rental-manager-for-woocommerce'); ?> <i class="fas fa-spin"></i></button>
                
                <?php wp_nonce_field( 'rbfw_mps_stripe_charge', 'rbfw_mps_stripe_checkout_nonce' ); ?>
            </form>
            </div>
            <?php
            $this->rbfw_mps_stripe_script($rbfw_regf_info);
            wp_die();
        }
        
        public function rbfw_mps_stripe_script($rbfw_regf_info = array()){
            global $rbfw;
            $postal_field = $rbfw->get_option('rbfw_mps_stripe_postal_field', 'rbfw_basic_payment_settings', 'off');
            ?>
            <script>
            // Create a Stripe client.
            var stripe = Stripe('<?php echo STRIPE_PUBLISHABLE_KEY; ?>');
             
            <?php 
            if($postal_field == 'on'){
            ?>    
                var postal_switch = false;
            <?php    
            }else{
            ?>    
                var postal_switch = true;
            <?php    
            }
            ?>
            // Create an instance of Elements.
            var elements = stripe.elements();

            // Custom styling can be passed to options when creating an Element.
            // (Note that this demo uses a wider set of styles than the guide below.)
            var style = {
                base: {
                    color: '#32325d',
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '14px',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a'
                }
            };

            // Create an instance of the card Element.
            var card = elements.create('card', { style: style, hidePostalCode: postal_switch  });

            // Add an instance of the card Element into the `card-element` <div>.
            card.mount('#rbfw-stripe-card-element');

            // Handle real-time validation errors from the card Element.
            card.addEventListener('change', function(event) {
                var displayError = document.getElementById('rbfw-stripe-card-errors');
                if (event.error) {
                    
                    jQuery(displayError).html('<p class="mps_alert_warning">'+event.error.message+'</p>');
                } else {

                    jQuery(displayError).empty();
                }
            });

            // Handle form submission.
            var form = document.getElementById('rbfw_stripe_form');
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                stripe.createToken(card).then(function(result) {
                    if (result.error) {
                        // Inform the user if there was an error.
                        var errorElement = jQuery('#rbfw-stripe-card-errors');
                        
                        jQuery(errorElement).html('<p class="mps_alert_warning">'+result.error.message+'</p>');
                    } else {

                        // Send the token to your server.
                        stripeTokenHandler(result.token);
                    }
                });
            });

            // Submit the form with the token ID.
            function stripeTokenHandler(token) {

                // Insert the token ID into the form so it gets submitted to the server
                let tokenlength = document.getElementsByName('stripeToken').length;
     
                if(tokenlength > 0){
                    jQuery('input[name="stripeToken"]').remove();
                }    
                    var form = document.getElementById('rbfw_stripe_form');
                    var token_field = document.createElement('input');
                    token_field.setAttribute('type', 'hidden');
                    token_field.setAttribute('name', 'stripeToken');
                    token_field.setAttribute('value', token.id);
                    form.appendChild(token_field);
                    var stripeTokenValue = token.id;

                    let start_date = '';
                    let start_time = '';
                    let end_date = '';
                    let end_time = '';
                    let pickup_point = '';
                    let dropoff_point = '';

                    let item_quantity = '';
                    let dpackage = '';
                    let type_array = {};
                    let service_array = {};
                    let variation_info = {};
                    let post_id = jQuery('input[name="rbfw_mps_post_id"]').val();
                    let security = jQuery('input[name="rbfw_mps_stripe_checkout_nonce"]').val();
                    let rent_type = jQuery('#rbfw_rent_type').val();
                    let first_name = jQuery('input[name="rbfw_mps_user_fname"]').val();
                    let last_name = jQuery('input[name="rbfw_mps_user_lname"]').val();
                    let email = jQuery('input[name="rbfw_mps_user_email"]').val();
                    let payment_method = jQuery('input[name="rbfw_mps_payment_method"]').val();
                    

                    if((rent_type == 'bike_car_md') || (rent_type == 'dress') || (rent_type == 'equipment') || (rent_type == 'others')){
                        start_date = jQuery('#pickup_date').val();
                        start_time = jQuery('#pickup_time').val();
                        end_date = jQuery('#dropoff_date').val();
                        end_time = jQuery('#dropoff_time').val();
                        pickup_point = jQuery('select[name="rbfw_pickup_point"]').val();
                        dropoff_point = jQuery('select[name="rbfw_dropoff_point"]').val();
                        let service_length = jQuery('.rbfw_bikecarmd_es_table tbody tr').length;



                        item_quantity = jQuery('select#rbfw_item_quantity').find(':selected').val();

                        if(item_quantity == ''){
                            item_quantity = 1;
                        }

                        for (let index = 0; index < service_length; index++) {
                            let qty = jQuery('input[name="rbfw_service_info['+index+'][service_qty]"]').val();
                            let data_type = jQuery('input[name="rbfw_service_info['+index+'][service_name]"]').val();
                            if(qty > 0){
                                service_array[data_type] = qty;
                            }
                        }

                        let variation_fields = jQuery('.rbfw_variation_field');
                        
                        for (let index = 0; index < variation_fields.length; index++) {
                            let field_label = jQuery('select[name="rbfw_variation_id_'+index+'"]').attr('data-field');
                            let field_id = 'rbfw_variation_id_'+index;
                            let field_value = jQuery('select[name="rbfw_variation_id_'+index+'"]').val();                           
                            let data = {};
                            data['field_id'] = field_id; 
                            data['field_label'] = field_label; 
                            data['field_value'] = field_value;
                            variation_info[index] = data;
                        }
                        
                    }else if(rent_type == 'bike_car_sd' || rent_type == 'appointment'){
                        start_date = jQuery('#rbfw_bikecarsd_selected_date').val();
                        start_time = jQuery('#rbfw_bikecarsd_selected_time').val();
                        let type_length = jQuery('.rbfw_bikecarsd_rt_price_table tbody tr').length;
                        let service_length = jQuery('.rbfw_bikecarsd_es_price_table tbody tr').length;

                        for (let index = 0; index < type_length; index++) {
                            let qty = jQuery('input[name="rbfw_bikecarsd_info['+index+'][qty]"]').val();
                            let data_type = jQuery('input[name="rbfw_bikecarsd_info['+index+'][qty]"]').attr('data-type');
                            if(qty > 0){
                                type_array[data_type] = qty;
                            }
                        }

                        for (let index = 0; index < service_length; index++) {
                            let qty = jQuery('input[name="rbfw_service_info['+index+'][service_qty]"]').val();
                            let data_type = jQuery('input[name="rbfw_service_info['+index+'][service_qty]"]').attr('data-type');
                            if(qty > 0){
                                service_array[data_type] = qty;
                            }
                        } 

                    }else if(rent_type == 'resort'){

                        start_date = jQuery('#checkin_date').val();
                        end_date = jQuery('#checkout_date').val();
                        dpackage = jQuery('.rbfw_room_price_category_tabs').attr('data-active');
                        let type_length = jQuery('.rbfw_resort_rt_price_table tbody tr').length;
                        let service_length = jQuery('.rbfw_resort_es_price_table tbody tr').length;

                        for (let index = 0; index < type_length; index++) {
                            let qty = jQuery('input[name="rbfw_room_info['+index+'][room_qty]"]').val();
                            let data_type = jQuery('input[name="rbfw_room_info['+index+'][room_qty]"]').attr('data-type');
                            if(qty > 0){
                                type_array[data_type] = qty;
                            }
                        }

                        for (let index = 0; index < service_length; index++) {
                            let qty = jQuery('input[name="rbfw_service_info['+index+'][service_qty]"]').val();
                            let data_type = jQuery('input[name="rbfw_service_info['+index+'][service_qty]"]').attr('data-type');
                            if(qty > 0){
                                service_array[data_type] = qty;
                            }
                        }

                    }
                    
                    let rbfw_regf_info = <?php echo $rbfw_regf_info; ?>;
                    console.log(rbfw_regf_info);

                    jQuery.ajax({
                        type: 'POST',
                        url: rbfw_ajax.rbfw_ajaxurl,
                        data: {
                            'action' : 'rbfw_mps_stripe_charge',
                            'stripeToken' : stripeTokenValue,
                            'post_id': post_id,
                            'rent_type': rent_type,
                            'start_date': start_date,
                            'start_time': start_time,
                            'end_date': end_date,
                            'end_time': end_time,
                            'pickup_point': pickup_point,
                            'dropoff_point': dropoff_point,

                            'item_quantity': item_quantity,
                            'package': dpackage,
                            'first_name' : first_name,
                            'last_name' : last_name,
                            'email' : email,
                            'payment_method' : payment_method,
                            'type_info[]': type_array,
                            'service_info[]': service_array,
                            'security' : security,
                            'variation_info' : variation_info,
                            'rbfw_regf_info' : rbfw_regf_info
                        },
                        beforeSend: function() {
                            jQuery('.rbfw_mps_payment_form_notice').empty();
                            jQuery('.rbfw_mps_stripe_pay_button i').addClass('fa-spinner');
                        },		
                        success: function (response) {

                            jQuery('.rbfw_mps_stripe_pay_button i').removeClass('fa-spinner');
     
                            if(typeof(response.message) != "undefined" && typeof(response.returnUrl) != "undefined"){
                                jQuery('.rbfw_mps_payment_form_notice').append(response.message);
                                window.location.replace(response.returnUrl);
                                
                            }else{
                                jQuery('.rbfw_mps_payment_form_notice').append(response);
                            }
                            
                        }
                    }); 
                
            }
            </script>
            <?php
        }
    }
    new RBFW_MPS_Stripe_Form();
}