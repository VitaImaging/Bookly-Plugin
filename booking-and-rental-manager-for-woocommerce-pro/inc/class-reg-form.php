<?php
/*
* @Author 	:	MagePeople Team
* Copyright	: 	mage-people.com
* Developer :   Ariful
* Version	:	1.0.0
*/

if (!defined('ABSPATH')){
    exit;
}

if (!class_exists('Rbfw_Reg_Form')) {
    class Rbfw_Reg_Form{

        public function __construct(){
            add_action( 'add_meta_boxes', array($this, 'rbfw_reg_form_meta_box_func') );
            add_action( 'save_post', array($this, 'rbfw_regf_save_data') );
            add_action( 'wp_ajax_rbfw_regf_get_new_field_row', array($this, 'rbfw_regf_get_new_field_row') );
            add_action( 'rbfw_meta_box_tab_name', array($this, 'rbfw_regf_reg_form_tab_name'), 11);
            add_action( 'rbfw_meta_box_tab_content', array($this, 'rbfw_regf_reg_form_tab_content'), 11);
            add_action( 'save_post', array($this, 'rbfw_item_meta_save_data_func') );
            add_action( 'admin_init', array($this, 'rbfw_import_registration_form_func'));
            add_filter( 'rbfw_add_cart_function_after', array($this, 'rbfw_regf_add_cart_function'), 10, 2 );
            add_action( 'rbfw_after_cart_item_display', array($this, 'rbfw_regf_cart_item_display'), 10, 1 );
            add_action( 'wp_footer', array($this, 'rbfw_regf_custom_script'));
        }

        public function rbfw_reg_form_meta_box_func(){
            add_meta_box(
                'rbfw-reg-form-fields',
                'Registration Form Settings',
                array($this, 'rbfw_reg_form_fields_callback'),
                'rbfw_reg_form'
            );
        }

        public function section_header(){
            ?>
                <h2 class="mp_tab_item_title"><?php echo esc_html__('Registration Form Settings', 'booking-and-rental-manager-for-woocommerce' ); ?></h2>
                <p class="mp_tab_item_description"><?php echo esc_html__('Here you can configure registration form.', 'booking-and-rental-manager-for-woocommerce' ); ?></p>
                    
            <?php
        }

        public function panel_header($title,$description){
            ?>
                <section class="bg-light mt-5">
                    <div>
                        <label>
                            <?php echo sprintf(__("%s",'booking-and-rental-manager-for-woocommerce'), $title ); ?>
                        </label>
                        <span><?php echo sprintf(__("%s",'booking-and-rental-manager-for-woocommerce'), $description ); ?></span>
                    </div>
                </section>
            <?php
        }

        public function rbfw_reg_form_fields_callback(){
            global $post;
            $registration_form_id = $post->ID;
            $fullname_switch = !empty(get_post_meta( $registration_form_id, 'rbfw_regf_fullname_switch', true )) ? get_post_meta( $registration_form_id, 'rbfw_regf_fullname_switch', true ) : '';
            $fullname_label = get_post_meta( $registration_form_id, 'rbfw_regf_fullname_label', true );

            $email_switch = !empty(get_post_meta( $registration_form_id, 'rbfw_regf_email_switch', true )) ? get_post_meta( $registration_form_id, 'rbfw_regf_email_switch', true ) : '';
            $email_label = get_post_meta( $registration_form_id, 'rbfw_regf_email_label', true );

            $address_switch = !empty(get_post_meta( $registration_form_id, 'rbfw_regf_address_switch', true )) ? get_post_meta( $registration_form_id, 'rbfw_regf_address_switch', true ) : '';
            $address_label = get_post_meta( $registration_form_id, 'rbfw_regf_address_label', true );

            $phone_switch = !empty(get_post_meta( $registration_form_id, 'rbfw_regf_phone_switch', true )) ? get_post_meta( $registration_form_id, 'rbfw_regf_phone_switch', true ) : '';
            $phone_label = get_post_meta( $registration_form_id, 'rbfw_regf_phone_label', true );

            $gender_switch = !empty(get_post_meta( $registration_form_id, 'rbfw_regf_gender_switch', true )) ? get_post_meta( $registration_form_id, 'rbfw_regf_gender_switch', true ) : '';
            $gender_label = get_post_meta( $registration_form_id, 'rbfw_regf_gender_label', true );

            $custom_fields_array = get_post_meta( $registration_form_id, 'rbfw_regf_field_array', true );

            ?>
            <div class="rbfw_regf_wrapper">
                <form action="" method="POST">
                    <table class="rbfw_regf_table">
                        <tr>
                            <td><div class="rbfw_regf_input_checkbox"><input type="checkbox" name="rbfw_regf_fullname_switch" id="rbfw_regf_fullname_switch" value="<?php echo $fullname_switch; ?>" <?php checked( $fullname_switch, 'on' ); ?>/></div></td>
                            <td><div class="rbfw_regf_input_label"><label for="rbfw_regf_fullname_switch"><?php esc_html_e('Full Name','rbfw-pro'); ?></label></div></td>
                            <td><div class="rbfw_regf_input_field"><input type="text" name="rbfw_regf_fullname_label" class="widefat" placeholder="Enter the field label here. Default is: Enter Your Name" value="<?php if(!empty($fullname_label)){ echo $fullname_label; } ?>"/></div></td>
                        </tr>
                        <tr>
                            <td><div class="rbfw_regf_input_checkbox"><input type="checkbox" name="rbfw_regf_email_switch" id="rbfw_regf_email_switch" value="<?php echo $email_switch; ?>" <?php checked( $email_switch, 'on' ); ?>/></div></td>
                            <td><div class="rbfw_regf_input_label"><label for="rbfw_regf_email_switch"><?php esc_html_e('Email','rbfw-pro'); ?></label></div></td>
                            <td><div class="rbfw_regf_input_field"><input type="text" name="rbfw_regf_email_label" class="widefat" placeholder="Enter the field label here. Default is: Enter Your Email" value="<?php if(!empty($email_label)){ echo $email_label; } ?>"/></div></td>
                        </tr>
                        <tr>
                            <td><div class="rbfw_regf_input_checkbox"><input type="checkbox" name="rbfw_regf_address_switch" id="rbfw_regf_address_switch" value="<?php echo $address_switch; ?>" <?php checked( $address_switch, 'on' ); ?>/></div></td>
                            <td><div class="rbfw_regf_input_label"><label for="rbfw_regf_address_switch"><?php esc_html_e('Address','rbfw-pro'); ?></label></div></td>
                            <td><div class="rbfw_regf_input_field"><input type="text" name="rbfw_regf_address_label" class="widefat" placeholder="Enter the field label here. Default is: Enter Your Address" value="<?php if(!empty($address_label)){ echo $address_label; } ?>"/></div></td>
                        </tr>
                        <tr>
                            <td><div class="rbfw_regf_input_checkbox"><input type="checkbox" name="rbfw_regf_phone_switch" id="rbfw_regf_phone_switch" value="<?php echo $phone_switch; ?>" <?php checked( $phone_switch, 'on' ); ?>/></div></td>
                            <td><div class="rbfw_regf_input_label"><label for="rbfw_regf_phone_switch"><?php esc_html_e('Phone','rbfw-pro'); ?></label></div></td>
                            <td><div class="rbfw_regf_input_field"><input type="text" name="rbfw_regf_phone_label" class="widefat" placeholder="Enter the field label here. Default is: Enter Your Phone" value="<?php if(!empty($phone_label)){ echo $phone_label; } ?>"/></div></td>
                        </tr>
                        <tr>
                            <td><div class="rbfw_regf_input_checkbox"><input type="checkbox" name="rbfw_regf_gender_switch" id="rbfw_regf_gender_switch" value="<?php echo $gender_switch; ?>" <?php checked( $gender_switch, 'on' ); ?>/></div></td>
                            <td><div class="rbfw_regf_input_label"><label for="rbfw_regf_gender_switch"><?php esc_html_e('Gender','rbfw-pro'); ?></label></div></td>
                            <td><div class="rbfw_regf_input_field"><input type="text" name="rbfw_regf_gender_label" class="widefat" placeholder="Enter the field label here. Default is: Enter Your Gender" value="<?php if(!empty($gender_label)){ echo $gender_label; } ?>"/></div></td>
                        </tr>
                    </table>
                    <table class="rbfw_regf_custom_field_table rbfw_regf_table rbfw_regf_mt_10">
                        <tr>
                            <th><?php esc_html_e('Field Label','rbfw-pro'); ?></th>
                            <th><?php esc_html_e('Unique ID','rbfw-pro'); ?></th>
                            <th><?php esc_html_e('Input Type','rbfw-pro'); ?></th>
                            <th><?php esc_html_e('Required','rbfw-pro'); ?></th>
                            <th></th>
                        </tr>
                        <?php
                        if(!empty($custom_fields_array)){
                        foreach ($custom_fields_array as $key => $value) {
                            $field_label = !empty($value['field_label']) ? $value['field_label'] : '';
                            $unique_id = !empty($value['unique_id']) ? $value['unique_id'] : '';
                            $field_type = !empty($value['field_type']) ? $value['field_type'] : '';
                            $seperated_values = !empty($value['seperated_values']) ? $value['seperated_values'] : '';
                            $field_required = !empty($value['field_required']) ? $value['field_required'] : '';
                        ?>
                        <tr>
                            <td><div class="rbfw_regf_input_field"><input type="text" name="rbfw_regf_field_label[]"  placeholder="Field label" value="<?php echo esc_attr($field_label); ?>"/></div></td>
                            <td>
                                <div class="rbfw_regf_input_field"><input type="text" name="rbfw_regf_unique_id[]"  placeholder="Unique ID" value="<?php echo esc_attr($unique_id); ?>" <?php if(!empty($unique_id)){ echo 'readonly'; } ?> onkeypress="return event.which != 32"/></div>
                                <div class="rbfw_alert_info"><i class="fa fa-info-circle"></i> <?php esc_html_e('This field must not be empty, otherwise data will not save into database. Please don\'t use any space, use underscore( _ ) instead of space.','rbfw-pro'); ?></div>
                            </td>
                            <td>
                                <select name="rbfw_regf_field_type[]" class="rbfw_regf_field_type">
                                    <option value="" <?php if($field_type == ''){ echo 'selected'; } ?>><?php esc_html_e('Please Select Type','rbfw-pro');?></option>
                                    <option value="text" <?php if($field_type == 'text'){ echo 'selected'; } ?>><?php esc_html_e('Text','rbfw-pro');?></option>
                                    <option value="date" <?php if($field_type == 'date'){ echo 'selected'; } ?>><?php esc_html_e('Datepicker','rbfw-pro');?></option>
                                    <option value="textarea" <?php if($field_type == 'textarea'){ echo 'selected'; } ?>><?php esc_html_e('Textarea','rbfw-pro');?></option>
                                    <option value="radio" <?php if($field_type == 'radio'){ echo 'selected'; } ?>><?php esc_html_e('Radio','rbfw-pro');?></option>
                                    <option value="checkbox" <?php if($field_type == 'checkbox'){ echo 'selected'; } ?>><?php esc_html_e('Checkbox','rbfw-pro');?></option>
                                    <option value="select" <?php if($field_type == 'select'){ echo 'selected'; } ?>><?php esc_html_e('Dropdown','rbfw-pro');?></option>
                                    <option value="file" <?php if($field_type == 'file'){ echo 'selected'; } ?>><?php esc_html_e('Upload File','rbfw-pro');?></option>
                                </select>
                                <div class="rbfw_regf_seperated_value_wrap" <?php if(!empty($seperated_values)){ echo 'style="display:block"'; } ?>><textarea name="rbfw_regf_seperated_values[]" placeholder="<?php esc_attr_e('Enter the value. If values are multiple, do separate the values by comma(,)','rbfw-pro'); ?>"><?php echo esc_html($seperated_values); ?></textarea></div>
                            </td>
                            <td>
                                <select name="rbfw_regf_field_required[]">
                                    <option value="0" <?php if($field_required == '0'){ echo 'selected'; } ?>><?php esc_html_e('Not Required','rbfw-pro');?></option>
                                    <option value="1" <?php if($field_required == '1'){ echo 'selected'; } ?>><?php esc_html_e('Required','rbfw-pro');?></option>
                                </select>
                            </td>
                            <td>
                                <button type="button" class="rbfw_regf_remove_field_row"><span class="dashicons dashicons-trash"></span></button>
                            </td>
                        </tr>
                        <?php
                        }
                        }
                        ?>
                    </table>
                    <?php wp_nonce_field( 'rbfw_regf_action', 'rbfw_regf_field_nonce' ); ?>
                </form>
                <button class="rbfw_regf_add_new_field"><i class="fa-solid fa-circle-plus"></i> <?php esc_html_e('Add New Field','rbfw-pro'); ?></button>
            </div>
            <style>
                .rbfw_regf_wrapper{
                    overflow-x: scroll;
                    overflow-y: hidden;
                }
                .rbfw_regf_table{
                    width: 100%;
                    border: 1px solid #f5f5f5;
                }
                .rbfw_regf_table td{
                    padding: 8px;
                    vertical-align: top;
                }
                .rbfw_regf_table tr:nth-child(even) {
                    background-color: #fbfbfb;
                }
                .rbfw_regf_table tr:nth-child(odd) {
                    background-color: #00328014;
                }
                .rbfw_regf_add_new_field{
                    background-color: #0ec40e;
                    color: #fff;
                    border-radius: 3px;
                    border: 0;
                    padding: 0 10px 1px;
                    cursor: pointer;
                    font-size: 13px;
                    line-height: 26px;
                    height: 28px;
                    margin-top: 20px;
                    margin-bottom: 20px;
                }
                .rbfw_regf_add_new_field i {
                    margin-right: 5px;
                }
                .rbfw_regf_remove_field_row{
                    background-color: #d02222;
                    border: 0;
                    border-radius: 5px;
                    color: #fff;
                    padding: 5px;
                    cursor: pointer;
                }
                .rbfw_regf_mt_10{
                    margin-top: 10px;
                }
                .rbfw_regf_hidden_row{
                    display: none;
                }
                .rbfw_regf_seperated_value_wrap{
                    display: none;
                    margin-top: 10px;
                }
                .rbfw_regf_seperated_value_wrap textarea{
                    height: 70px;
                }
                .rbfw_regf_custom_field_table{
                    text-align:left;
                }
                .rbfw_regf_custom_field_table th{
                    padding: 8px;
                }
                .rbfw_regf_add_new_field_loader{
                    margin-left: 5px;
                }
            </style>
            <script>
            jQuery(document).ready(function(){
                jQuery('.rbfw_regf_add_new_field').click(function (e) {
                    e.preventDefault();

                    jQuery.ajax({
                        type: 'POST',
                        url: ajaxurl,
                        data: {
                        'action': 'rbfw_regf_get_new_field_row'
                        },
                        beforeSend: function() {
                            jQuery('.rbfw_regf_add_new_field').append('<span class="rbfw_regf_add_new_field_loader"><i class="fas fa-spinner fa-spin"></i></span>');
                        },
                        success: function (response) {
                            jQuery('.rbfw_regf_add_new_field_loader').remove();
                            jQuery('.rbfw_regf_custom_field_table').append(response);

                        },
                        error: function(response){
                            console.log(response);
                        }
                    });
                });
            });

            jQuery(document).on('click', '.rbfw_regf_remove_field_row', function() {
                if (confirm('Are You Sure , Remove this row ? \n\n 1. Ok : To Remove . \n 2. Cancel : To Cancel .')) {
                    jQuery(this).closest('tr').slideUp(250, function() {
                        jQuery(this).remove();
                    });
                } else {
                    return false;
                }
            });

            jQuery(document).on('change', '.rbfw_regf_field_type', function() {

                let this_val = jQuery(this).val();

                if(this_val == 'select' || this_val == 'radio' || this_val == 'checkbox'){

                    jQuery(this).siblings('.rbfw_regf_seperated_value_wrap').show();

                } else {

                    jQuery(this).siblings('.rbfw_regf_seperated_value_wrap').hide();
                }
            });
            </script>
            <?php
        }

        public function rbfw_regf_get_new_field_row(){
            ob_start();
            ?>
            <tr>
                <td><div class="rbfw_regf_input_field"><input type="text" name="rbfw_regf_field_label[]"  placeholder="Field label"/></div></td>
                <td>
                    <div class="rbfw_regf_input_field"><input type="text" name="rbfw_regf_unique_id[]"  placeholder="Unique ID" onkeypress="return event.which != 32"/></div>
                    <div class="rbfw_alert_info"><i class="fa fa-info-circle"></i> <?php esc_html_e('This field must not be empty, otherwise data will not save into database. Please don\'t use any space, use underscore( _ ) instead of space.','rbfw-pro'); ?></div>
                </td>
                <td>
                    <select name="rbfw_regf_field_type[]" class="rbfw_regf_field_type">
                        <option value=""><?php esc_html_e('Please Select Type','rbfw-pro');?></option>
                        <option value="text"><?php esc_html_e('Text','rbfw-pro');?></option>
                        <option value="date"><?php esc_html_e('Datepicker','rbfw-pro');?></option>
                        <option value="textarea"><?php esc_html_e('Textarea','rbfw-pro');?></option>
                        <option value="radio"><?php esc_html_e('Radio','rbfw-pro');?></option>
                        <option value="checkbox"><?php esc_html_e('Checkbox','rbfw-pro');?></option>
                        <option value="select"><?php esc_html_e('Dropdown','rbfw-pro');?></option>
                        <option value="file"><?php esc_html_e('Upload File','rbfw-pro');?></option>
                    </select>
                    <div class="rbfw_regf_seperated_value_wrap"><textarea name="rbfw_regf_seperated_values[]" placeholder="<?php esc_attr_e('Enter the value. If values are multiple, do separate the values by comma(,)','rbfw-pro'); ?>"></textarea></div>
                </td>
                <td>
                    <select name="rbfw_regf_field_required[]">
                        <option value="0"><?php esc_html_e('Not Required','rbfw-pro');?></option>
                        <option value="1"><?php esc_html_e('Required','rbfw-pro');?></option>
                    </select>
                </td>
                <td>
                    <button type="button" class="rbfw_regf_remove_field_row"><span class="dashicons dashicons-trash"></span></button>
                </td>
            </tr>
            <?php
            $content = ob_get_clean();
            echo $content;
            wp_die();
        }

        public function rbfw_regf_save_data($registration_form_id){

            if ( ! isset( $_POST['rbfw_regf_field_nonce'] ) || ! wp_verify_nonce( $_POST['rbfw_regf_field_nonce'], 'rbfw_regf_action' ) ) {
                return;
            }

            // Autosaving, bail.
            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return;
            }

            if ( ! current_user_can( 'edit_post', $registration_form_id ) ) {
                return;
            }

            if(isset($_POST['rbfw_regf_fullname_switch'])){
                update_post_meta($registration_form_id, 'rbfw_regf_fullname_switch', 'on');
            } else {
                update_post_meta($registration_form_id, 'rbfw_regf_fullname_switch', 'off');
            }

            if(isset($_POST['rbfw_regf_fullname_label'])){
                $fullname_label = sanitize_text_field($_POST['rbfw_regf_fullname_label']);
                update_post_meta($registration_form_id, 'rbfw_regf_fullname_label', $fullname_label);
            }

            if(isset($_POST['rbfw_regf_email_switch'])){
                update_post_meta($registration_form_id, 'rbfw_regf_email_switch', 'on');
            } else {
                update_post_meta($registration_form_id, 'rbfw_regf_email_switch', 'off');
            }

            if(isset($_POST['rbfw_regf_email_label'])){
                $email_label = sanitize_text_field($_POST['rbfw_regf_email_label']);
                update_post_meta($registration_form_id, 'rbfw_regf_email_label', $email_label);
            }

            if(isset($_POST['rbfw_regf_address_switch'])){
                update_post_meta($registration_form_id, 'rbfw_regf_address_switch', 'on');
            } else {
                update_post_meta($registration_form_id, 'rbfw_regf_address_switch', 'off');
            }

            if(isset($_POST['rbfw_regf_address_label'])){
                $address_label = sanitize_text_field($_POST['rbfw_regf_address_label']);
                update_post_meta($registration_form_id, 'rbfw_regf_address_label', $address_label);
            }

            if(isset($_POST['rbfw_regf_phone_switch'])){
                update_post_meta($registration_form_id, 'rbfw_regf_phone_switch', 'on');
            } else {
                update_post_meta($registration_form_id, 'rbfw_regf_phone_switch', 'off');
            }

            if(isset($_POST['rbfw_regf_phone_label'])){
                $phone_label = sanitize_text_field($_POST['rbfw_regf_phone_label']);
                update_post_meta($registration_form_id, 'rbfw_regf_phone_label', $phone_label);
            }

            if(isset($_POST['rbfw_regf_gender_switch'])){
                update_post_meta($registration_form_id, 'rbfw_regf_gender_switch', 'on');
            } else {
                update_post_meta($registration_form_id, 'rbfw_regf_gender_switch', 'off');
            }

            if(isset($_POST['rbfw_regf_gender_label'])){
                $gender_label = sanitize_text_field($_POST['rbfw_regf_gender_label']);
                update_post_meta($registration_form_id, 'rbfw_regf_gender_label', $gender_label);
            }

            if( isset($_POST['rbfw_regf_field_label']) ){
                $field_label = $_POST['rbfw_regf_field_label'];
                $unique_id = $_POST['rbfw_regf_unique_id'];
                $field_type = $_POST['rbfw_regf_field_type'];
                $seperated_values = $_POST['rbfw_regf_seperated_values'];
                $field_required = $_POST['rbfw_regf_field_required'];
                $count_field_rows = count($_POST['rbfw_regf_unique_id']);

                if(!empty($count_field_rows)){
                    $the_array = [];
                    for ($i = 0; $i < $count_field_rows; $i++) {
                        $the_array[$i]['field_label'] = sanitize_text_field($field_label[$i]);
                        $the_array[$i]['unique_id'] = sanitize_text_field(str_replace(' ', '', $unique_id[$i]));
                        $the_array[$i]['field_type'] = $field_type[$i];
                        $the_array[$i]['seperated_values'] = sanitize_text_field($seperated_values[$i]);
                        $the_array[$i]['field_required'] = $field_required[$i];
                    }
                    update_post_meta($registration_form_id, 'rbfw_regf_field_array', $the_array);
                }
            } else {
                delete_post_meta($registration_form_id, 'rbfw_regf_field_array');
            }
        }

        public function rbfw_regf_reg_form_tab_name(){
            ?>
            <li data-target-tabs="#rbfw_reg_form"><i class="fas fa-id-card"></i><?php esc_html_e( ' Registration Form', 'booking-and-rental-manager-for-woocommerce' ); ?></li>
            <?php
        }

        public function rbfw_regf_reg_form_tab_content($post_id){
            $regf_list_arr = rbfw_pro_get_regf_list();
            $rbfw_registration_form = get_post_meta($post_id, 'rbfw_registration_form', true);
            ?>
            <div class="mpStyle mp_tab_item" data-tab-item="#rbfw_reg_form">
                <?php $this->section_header(); ?>
                <?php $this->panel_header('Registration form','Here you can set form to registraion.'); ?>
                <section>
                    <div>
                        <label> <?php _e( 'Select Registration Form', 'booking-and-rental-manager-for-woocommerce' ); ?></label>
                        <span> <?php _e( 'Select a form to registration. If no form listed, click the link if visible.', 'booking-and-rental-manager-for-woocommerce' ); ?></span>
                    </div>
                    <?php if(!empty($regf_list_arr)): ?>
                        <select name="rbfw_registration_form" >
                            <option value=""> Select Form</option>
                            <?php foreach($regf_list_arr as $kay => $value): ?>
                                <option <?php echo ($kay==$rbfw_registration_form)?'selected':'' ?> value="<?php echo $kay; ?>"> <?php echo $value; ?> </option>
                            <?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <a href="edit.php?post_type=rbfw_reg_form"> <?php _e( 'Create a registration form', 'booking-and-rental-manager-for-woocommerce' ); ?></a>
                    <?php endif; ?>
                </section>
            </div>
            <?php
        }

        public function rbfw_item_meta_save_data_func($post_id){

            // Autosaving, bail.
            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return;
            }

            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }

            if(isset($_POST['rbfw_registration_form'])){
                $rbfw_registration_form = isset( $_POST['rbfw_registration_form'] ) ? rbfw_array_strip( $_POST['rbfw_registration_form'] ) : '';
                update_post_meta($post_id, 'rbfw_registration_form', $rbfw_registration_form);

            }

        }

        public function rbfw_import_registration_form_func(){

           // echo get_option('rbfw_default_reg_form');

           if(get_option('rbfw_default_reg_form') == 'imported'){
                return;
            }

            $regf_post_meta = array(
                'rbfw_regf_fullname_switch' => 'off',
                'rbfw_regf_fullname_label' => '',
                'rbfw_regf_email_switch' => 'off',
                'rbfw_regf_email_label' => '',
                'rbfw_regf_address_switch' => 'on',
                'rbfw_regf_address_label' => '',
                'rbfw_regf_phone_switch' => 'on',
                'rbfw_regf_phone_label' => '',
                'rbfw_regf_gender_switch' => 'on',
                'rbfw_regf_gender_label' => '',
            );

            $args = array(
                'post_title' 	=> 'Default Form',
                'post_status' 	=> 'publish',
                'post_type' 	=> 'rbfw_reg_form',
            );

            $regf_registration_form_id = wp_insert_post( $args );

            //echo $regf_registration_form_id;exit;

            if(!empty($regf_registration_form_id)){

                foreach ($regf_post_meta as $key => $value) {

                    update_post_meta($regf_registration_form_id, $key, $value);
                }
                $this->rbfw_update_item_meta($regf_registration_form_id);
                update_option('rbfw_default_reg_form', 'imported');

            }
        }

        public function rbfw_update_item_meta($regf_registration_form_id){

            if(empty($regf_registration_form_id)){
                return;
            }

            $args = array(
                'post_type' 	=> 'rbfw_item',
                'post_status' 	=> 'publish',
                'posts_per_page'=> -1
            );

            $the_query = new WP_Query($args);

            if ( $the_query->have_posts() ) {
                while ( $the_query->have_posts() ) {
                    $the_query->the_post();

                    $registration_form_id = get_the_ID();
                    update_post_meta($registration_form_id, 'rbfw_registration_form_id', $regf_registration_form_id);
                }
            }
            wp_reset_query();
        }

        public function rbfw_generate_regf_fields($rent_item_id){

            if(empty($rent_item_id)){
                return;
            }

            $registration_form_id = get_post_meta($rent_item_id, 'rbfw_registration_form', true);


           // echo $registration_form_id;exit;

            if(empty($registration_form_id)){
                return;
            }

            $args = array(
                'p'         => $registration_form_id,
                'post_type' => 'rbfw_reg_form'
            );

            $the_query = new WP_Query($args);
            $content = '';

            if ( $the_query->have_posts() ) {
                while ( $the_query->have_posts() ) {
                    $the_query->the_post();


                    $fullname_switch    = get_post_meta($registration_form_id, 'rbfw_regf_fullname_switch', true);
                    $fullname_label     = !empty(get_post_meta($registration_form_id, 'rbfw_regf_fullname_label', true)) ? get_post_meta($registration_form_id, 'rbfw_regf_fullname_label', true) : 'Full Name';
                    $email_switch       = get_post_meta($registration_form_id, 'rbfw_regf_email_switch', true);
                    $email_label        = !empty(get_post_meta($registration_form_id, 'rbfw_regf_email_label', true)) ? get_post_meta($registration_form_id, 'rbfw_regf_email_label', true) : 'Email';
                    $address_switch     = get_post_meta($registration_form_id, 'rbfw_regf_address_switch', true);
                    $address_label      = !empty(get_post_meta($registration_form_id, 'rbfw_regf_address_label', true)) ? get_post_meta($registration_form_id, 'rbfw_regf_address_label', true) : 'Address';
                    $phone_switch       = get_post_meta($registration_form_id, 'rbfw_regf_phone_switch', true);
                    $phone_label        = !empty(get_post_meta($registration_form_id, 'rbfw_regf_phone_label', true)) ? get_post_meta($registration_form_id, 'rbfw_regf_phone_label', true) : 'Phone';
                    $gender_switch      = get_post_meta($registration_form_id, 'rbfw_regf_gender_switch', true);
                    $gender_label       = !empty(get_post_meta($registration_form_id, 'rbfw_regf_gender_label', true)) ? get_post_meta($registration_form_id, 'rbfw_regf_gender_label', true) : 'Gender';
                    $field_array        = get_post_meta($registration_form_id, 'rbfw_regf_field_array', true);

                    $content.= '<div class="rbfw_regf_wrap">';
                    $content.= '<div class="rbfw_regf_form_header h5 text-primary mb-2">'.rbfw_string_return('rbfw_text_customer_information',__('Customer Information','booking-and-rental-manager-for-woocommerce')).'</div>';

                        if($fullname_switch == 'on'){
                            $content.= '<div class="rbfw_regf_group">';
                            $content.= '<label for="rbfw_regf_fullname">'.esc_html($fullname_label).'</label>';
                            $content.= '<input type="text" name="rbfw_regf_fullname" id="rbfw_regf_fullname" class="rbfw_regf_field" required="true"/>';
                            $content.= '<input type="hidden" name="rbfw_regf_fullname_label" value="'.esc_attr($fullname_label).'"/>';
                            $content.= '</div>';
                        }

                        if($email_switch == 'on'){
                            $content.= '<div class="rbfw_regf_group">';
                            $content.= '<label for="rbfw_regf_email">'.esc_html($email_label).'</label>';
                            $content.= '<input type="email" name="rbfw_regf_email" id="rbfw_regf_email" class="rbfw_regf_field" required="true"/>';
                            $content.= '<input type="hidden" name="rbfw_regf_email_label" value="'.esc_attr($email_label).'"/>';
                            $content.= '</div>';
                        }

                        if($address_switch == 'on'){
                            $content.= '<div class="rbfw_regf_group">';
                            $content.= '<label for="rbfw_regf_address">'.esc_html($address_label).'</label>';
                            $content.= '<input type="text" name="rbfw_regf_address" id="rbfw_regf_address" class="rbfw_regf_field" required="true"/>';
                            $content.= '<input type="hidden" name="rbfw_regf_address_label" value="'.esc_attr($address_label).'"/>';
                            $content.= '</div>';
                        }

                        if($phone_switch == 'on'){
                            $content.= '<div class="rbfw_regf_group">';
                            $content.= '<label for="rbfw_regf_phone">'.esc_html($phone_label).'</label>';
                            $content.= '<input type="text" name="rbfw_regf_phone" id="rbfw_regf_phone" class="rbfw_regf_field" required="true"/>';
                            $content.= '<input type="hidden" name="rbfw_regf_phone_label" value="'.esc_attr($phone_label).'"/>';
                            $content.= '</div>';
                        }


                        if($gender_switch == 'on'){
                            $content.= '<div class="rbfw_regf_group">';
                            $content.= '<label for="rbfw_regf_gender">'.esc_html($gender_label).'</label>';
                            $content.= '<input type="hidden" name="rbfw_regf_gender_label" value="'.esc_attr($gender_label).'"/>';
                            $content.= '<select name="rbfw_regf_gender" id="rbfw_regf_gender" class="rbfw_regf_field" required="true"/>';
                            $content.= '<option value="">'.rbfw_string_return('rbfw_text_pls_choose_option',__('Please choose the option','booking-and-rental-manager-for-woocommerce')).'</option>';
                            $content.= '<option value="male">'.rbfw_string_return('rbfw_text_male',__('Male','booking-and-rental-manager-for-woocommerce')).'</option>';
                            $content.= '<option value="female">'.rbfw_string_return('rbfw_text_female',__('Female','booking-and-rental-manager-for-woocommerce')).'</option>';
                            $content.= '<option value="other">'.rbfw_string_return('rbfw_text_other',__('Other','booking-and-rental-manager-for-woocommerce')).'</option>';
                            $content.= '</select>';
                            $content.= '</div>';
                            $content.= '<div class="rbfw_regf_group">';
                            // here will be new form field
                            $content.= '</div>';
                        }

                    if(!empty($field_array)){
                        foreach ($field_array as $key => $value) {
                            $field_label = $value['field_label'];
                            $unique_id = $value['unique_id'];
                            $field_type = $value['field_type'];
                            $seperated_values = $value['seperated_values'];

                            if( strpos($seperated_values, ',') !== false ) {
                                $seperated_values = !empty($value['seperated_values']) ? explode(',',$value['seperated_values']) : [];
                            }


                            if($value['field_required'] == '1'){
                                $field_required = 'required';
                            } else {
                                $field_required = '';
                            }


                            if($field_type == 'text'){
                                $content.= '<div class="rbfw_regf_group">';
                                $content.= '<label for="'.esc_attr($unique_id).'">'.esc_html($field_label).'</label>';
                                $content.= '<input type="hidden" name="'.esc_attr($unique_id).'_label" value="'.esc_attr($field_label).'"/>';
                                $content.= '<input type="text" name="'.esc_attr($unique_id).'" class="rbfw_regf_field" id="'.esc_attr($unique_id).'" '.esc_attr($field_required).'/>';
                                $content.= '</div>';
                            }

                            if($field_type == 'date'){
                                $content.= '<div class="rbfw_regf_group">';
                                $content.= '<label for="'.esc_attr($unique_id).'">'.esc_html($field_label).'</label>';
                                $content.= '<input type="hidden" name="'.esc_attr($unique_id).'_label" value="'.esc_attr($field_label).'"/>';
                                $content.= '<input type="date" name="'.esc_attr($unique_id).'" class="rbfw_regf_field" id="'.esc_attr($unique_id).'" '.esc_attr($field_required).'/>';
                                $content.= '</div>';
                            }

                            if($field_type == 'textarea'){
                                $content.= '<div class="rbfw_regf_group">';
                                $content.= '<label for="'.esc_attr($unique_id).'">'.esc_html($field_label).'</label>';
                                $content.= '<input type="hidden" name="'.esc_attr($unique_id).'_label" value="'.esc_attr($field_label).'"/>';
                                $content.= '<textarea name="'.esc_attr($unique_id).'" class="rbfw_regf_field" id="'.esc_attr($unique_id).'" '.esc_attr($field_required).'></textarea>';
                                $content.= '</div>';
                            }

                            if($field_type == 'radio'){
                                $content.= '<div class="rbfw_regf_group">';
                                $content.= '<label for="'.esc_attr($unique_id).'">'.esc_html($field_label).'</label>';
                                $content.= '<input type="hidden" name="'.esc_attr($unique_id).'_label" value="'.esc_attr($field_label).'"/>';
                                if(!empty($seperated_values) && is_array($seperated_values)){

                                    $i = 0;

                                    foreach ($seperated_values as $value) {

                                        $field_required = ($i == 0 && $field_required == 'required') ? 'required' : '';
                                        $content.= '<label><input type="radio" name="'.esc_attr($unique_id).'[]" class="rbfw_regf_field '.esc_attr($unique_id).'" value="'.esc_attr($value).'" '.esc_attr($field_required).'/>'.esc_html($value).'</label>';
                                        $i++;
                                    }

                                } else {
                                    $content.= '<label><input type="radio" name="'.esc_attr($unique_id).'" class="rbfw_regf_field '.esc_attr($unique_id).'" value="'.esc_attr($seperated_values).'" '.esc_attr($field_required).'/>'.esc_html($seperated_values).'</label>';
                                }

                                $content.= '</div>';
                            }

                            if($field_type == 'checkbox'){
                                $content.= '<div class="rbfw_regf_group">';
                                $content.= '<label for="'.esc_attr($unique_id).'">'.esc_html($field_label).'</label>';
                                $content.= '<input type="hidden" name="'.esc_attr($unique_id).'_label" value="'.esc_attr($field_label).'"/>';
                                if(!empty($seperated_values) && is_array($seperated_values)){

                                    $c = 0;

                                    foreach ($seperated_values as $value) {

                                        $field_required = ($c == 0 && $field_required == 'required') ? 'required' : '';
                                        $content.= '<label><input type="checkbox" class="rbfw_regf_field '.esc_attr($unique_id).'" name="'.esc_attr($unique_id).'[]" value="'.esc_attr($value).'" '.esc_attr($field_required).'/>'.esc_html($value).'</label>';
                                        $c++;
                                    }

                                } else {
                                    $content.= '<label><input type="checkbox" class="rbfw_regf_field '.esc_attr($unique_id).'" name="'.esc_attr($unique_id).'" value="'.esc_attr($seperated_values).'" '.esc_attr($field_required).'/>'.esc_html($seperated_values).'</label>';
                                }

                                $content.= '</div>';
                            }

                            if($field_type == 'select'){
                                $content.= '<div class="rbfw_regf_group">';
                                $content.= '<label for="'.esc_attr($unique_id).'">'.esc_html($field_label).'</label>';
                                $content.= '<input type="hidden" name="'.esc_attr($unique_id).'_label" value="'.esc_attr($field_label).'"/>';
                                if(!empty($seperated_values) && is_array($seperated_values)){

                                    $content.= '<select name="'.esc_attr($unique_id).'" class="rbfw_regf_field" id="'.esc_attr($unique_id).'" '.esc_attr($field_required).'>';
                                    $content.= '<option value="">'.rbfw_string_return('rbfw_text_pls_choose_option',__('Please choose the option','booking-and-rental-manager-for-woocommerce')).'</option>';

                                    foreach ($seperated_values as $value) {
                                        $content.= '<option value="'.esc_attr($value).'">'.esc_html($value).'</option>';
                                    }

                                    $content.= '</select>';
                                }

                                $content.= '</div>';
                            }

                            if($field_type == 'file'){
                                $content.= '<div class="rbfw_regf_group">';
                                $content.= '<label for="'.esc_attr($unique_id).'">'.esc_html($field_label).'</label>';
                                $content.= '<input type="hidden" name="'.esc_attr($unique_id).'_label" value="'.esc_attr($field_label).'"/>';
                                $content.= '<input type="hidden" name="'.esc_attr($unique_id).'" value=""/>';
                                $content.= '<input type="hidden" name="action" value="rbfw_regf_upload_file"/>';
                                $content.= '<div class="rbfw_regf_file_wrap"><input type="file" class="rbfw_regf_field" id="'.esc_attr($unique_id).'" '.esc_attr($field_required).' accept="image/*,.pdf"/></div>';
                                $content.= '</div>';
                            }


                        }
                    }
                    $content.= '</div>';
                }
            }
            wp_reset_query();

            return $content;
        }

        public function rbfw_check_regf_field_required_by_name($rent_item_id, $field_name){

            if(empty($rent_item_id) || empty($field_name)){
                return;
            }

            $registration_form_id   = get_post_meta($rent_item_id, 'rbfw_registration_form_id', true);
            $field_array            = get_post_meta($registration_form_id, 'rbfw_regf_field_array', true);

            $fullname_switch    = get_post_meta($registration_form_id, 'rbfw_regf_fullname_switch', true);
            $email_switch       = get_post_meta($registration_form_id, 'rbfw_regf_email_switch', true);
            $address_switch     = get_post_meta($registration_form_id, 'rbfw_regf_address_switch', true);
            $phone_switch       = get_post_meta($registration_form_id, 'rbfw_regf_phone_switch', true);
            $gender_switch      = get_post_meta($registration_form_id, 'rbfw_regf_gender_switch', true);
            $required = 0;

            if($field_name == 'rbfw_regf_fullname' && $fullname_switch == 'on'){
                $required = 1;
            }

            if($field_name == 'rbfw_regf_email' && $email_switch == 'on'){
                $required = 1;
            }

            if($field_name == 'rbfw_regf_address' && $address_switch == 'on'){
                $required = 1;
            }

            if($field_name == 'rbfw_regf_phone' && $phone_switch == 'on'){
                $required = 1;
            }

            if($field_name == 'rbfw_regf_gender' && $gender_switch == 'on'){
                $required = 1;
            }

            if(!empty($field_array)){

                foreach ($field_array as $value) {

                    $field_required = $value['field_required'];
                    $unique_id = $value['unique_id'];

                    if($unique_id == $field_name){

                        $required = $field_required;
                    }
                }
            }

            return $required;
        }

        public function rbfw_get_regf_field_label_by_name($rent_item_id, $field_name){

            if(empty($rent_item_id) || empty($field_name)){
                return;
            }

            $the_label              = '';
            $registration_form_id   = get_post_meta($rent_item_id, 'rbfw_registration_form_id', true);
            $field_array            = get_post_meta($registration_form_id, 'rbfw_regf_field_array', true);
            $fullname_label         = !empty(get_post_meta($registration_form_id, 'rbfw_regf_fullname_label', true)) ? get_post_meta($registration_form_id, 'rbfw_regf_fullname_label', true) : 'Full Name';
            $email_label            = !empty(get_post_meta($registration_form_id, 'rbfw_regf_email_label', true)) ? get_post_meta($registration_form_id, 'rbfw_regf_email_label', true) : 'Email';
            $address_label          = !empty(get_post_meta($registration_form_id, 'rbfw_regf_address_label', true)) ? get_post_meta($registration_form_id, 'rbfw_regf_address_label', true) : 'Address';
            $phone_label            = !empty(get_post_meta($registration_form_id, 'rbfw_regf_phone_label', true)) ? get_post_meta($registration_form_id, 'rbfw_regf_phone_label', true) : 'Phone';
            $gender_label           = !empty(get_post_meta($registration_form_id, 'rbfw_regf_gender_label', true)) ? get_post_meta($registration_form_id, 'rbfw_regf_gender_label', true) : 'Gender';

            if($field_name == 'rbfw_regf_fullname'){
                $the_label = $fullname_label;
            }

            if($field_name == 'rbfw_regf_email'){
                $the_label = $email_label;
            }

            if($field_name == 'rbfw_regf_address'){
                $the_label = $address_label;
            }

            if($field_name == 'rbfw_regf_phone'){
                $the_label = $phone_label;
            }

            if($field_name == 'rbfw_regf_gender'){
                $the_label = $gender_label;
            }

            if(!empty($field_array)){

                foreach ($field_array as $value) {
                    $field_label = $value['field_label'];
                    $unique_id = $value['unique_id'];

                    if($unique_id == $field_name){
                        $the_label = $field_label;
                    }
                }
            }

            return $the_label;
        }

        public function rbfw_get_regf_all_fields_name($rent_item_id){

            if(empty($rent_item_id)){
                return;
            }

            $registration_form_id   = get_post_meta($rent_item_id, 'rbfw_registration_form_id', true);
            $field_array            = get_post_meta($registration_form_id, 'rbfw_regf_field_array', true);


            $fullname_switch    = get_post_meta($registration_form_id, 'rbfw_regf_fullname_switch', true);
            $fullname_label     = !empty(get_post_meta($registration_form_id, 'rbfw_regf_fullname_label', true)) ? get_post_meta($registration_form_id, 'rbfw_regf_fullname_label', true) : 'Full Name';
            $email_switch       = get_post_meta($registration_form_id, 'rbfw_regf_email_switch', true);
            $email_label        = !empty(get_post_meta($registration_form_id, 'rbfw_regf_email_label', true)) ? get_post_meta($registration_form_id, 'rbfw_regf_email_label', true) : 'Email';
            $address_switch     = get_post_meta($registration_form_id, 'rbfw_regf_address_switch', true);
            $address_label      = !empty(get_post_meta($registration_form_id, 'rbfw_regf_address_label', true)) ? get_post_meta($registration_form_id, 'rbfw_regf_address_label', true) : 'Address';
            $phone_switch       = get_post_meta($registration_form_id, 'rbfw_regf_phone_switch', true);
            $phone_label        = !empty(get_post_meta($registration_form_id, 'rbfw_regf_phone_label', true)) ? get_post_meta($registration_form_id, 'rbfw_regf_phone_label', true) : 'Phone';
            $gender_switch      = get_post_meta($registration_form_id, 'rbfw_regf_gender_switch', true);
            $gender_label       = !empty(get_post_meta($registration_form_id, 'rbfw_regf_gender_label', true)) ? get_post_meta($registration_form_id, 'rbfw_regf_gender_label', true) : 'Gender';

            $the_array = [];

            if($fullname_switch == 'on'){
                $the_array[] = 'rbfw_regf_fullname';
            }

            if($email_switch == 'on'){
                $the_array[] = 'rbfw_regf_email';
            }

            if($address_switch == 'on'){
                $the_array[] = 'rbfw_regf_address';
            }

            if($phone_switch == 'on'){
                $the_array[] = 'rbfw_regf_phone';
            }

            if($gender_switch == 'on'){
                $the_array[] = 'rbfw_regf_gender';
            }

            if(!empty($field_array)){

                foreach ($field_array as $value) {
                    $label        = $value['field_label'];
                    $unique_id    = $value['unique_id'];
                    $the_array[]  = $unique_id;
                }
            }

            return $the_array;
        }

        public function rbfw_get_regf_new_fields_name($rent_item_id){

            if(empty($rent_item_id)){
                return;
            }

            $registration_form_id   = get_post_meta($rent_item_id, 'rbfw_registration_form_id', true);
            $field_array            = get_post_meta($registration_form_id, 'rbfw_regf_field_array', true);
            $the_array              = [];

            if(!empty($field_array)){

                foreach ($field_array as $value) {
                    $field_type             = $value['field_type'];
                    $unique_id              = $value['unique_id'];
                    $the_array[$unique_id]  = $field_type;
                }
            }

            return $the_array;
        }

        public function rbfw_organize_regf_value_array_mps_func($rent_item_id, $rbfw_regf_info){

            if(empty($rent_item_id) || empty($rbfw_regf_info)){
                return;
            }

            $registration_form_id   = get_post_meta($rent_item_id, 'rbfw_registration_form_id', true);
            $field_array            = get_post_meta($registration_form_id, 'rbfw_regf_field_array', true);
            $the_array              = [];

            $rbfw_regf_fullname_label     = !empty(get_post_meta($registration_form_id, 'rbfw_regf_fullname_label', true)) ? get_post_meta($registration_form_id, 'rbfw_regf_fullname_label', true) : 'Full Name';
            $rbfw_regf_email_label        = !empty(get_post_meta($registration_form_id, 'rbfw_regf_email_label', true)) ? get_post_meta($registration_form_id, 'rbfw_regf_email_label', true) : 'Email';
            $rbfw_regf_address_label      = !empty(get_post_meta($registration_form_id, 'rbfw_regf_address_label', true)) ? get_post_meta($registration_form_id, 'rbfw_regf_address_label', true) : 'Address';
            $rbfw_regf_phone_label        = !empty(get_post_meta($registration_form_id, 'rbfw_regf_phone_label', true)) ? get_post_meta($registration_form_id, 'rbfw_regf_phone_label', true) : 'Phone';
            $rbfw_regf_gender_label       = !empty(get_post_meta($registration_form_id, 'rbfw_regf_gender_label', true)) ? get_post_meta($registration_form_id, 'rbfw_regf_gender_label', true) : 'Gender';

            foreach ($rbfw_regf_info as $field_name => $field_value) {

                if($field_name == 'rbfw_regf_fullname'){
                    $the_array['fullname']['label'] = $rbfw_regf_fullname_label;
                    $the_array['fullname']['value'] = $field_value;
                }

                if($field_name == 'rbfw_regf_email'){
                    $the_array['email']['label']    = $rbfw_regf_email_label;
                    $the_array['email']['value']    = $field_value;
                }

                if($field_name == 'rbfw_regf_address'){
                    $the_array['address']['label']  = $rbfw_regf_address_label;
                    $the_array['address']['value']  = $field_value;
                }

                if($field_name == 'rbfw_regf_phone'){
                    $the_array['phone']['label']    = $rbfw_regf_phone_label;
                    $the_array['phone']['value']    = $field_value;
                }

                if($field_name == 'rbfw_regf_gender'){
                    $the_array['gender']['label']   = $rbfw_regf_gender_label;
                    $the_array['gender']['value']   = $field_value;
                }

                if(!empty($field_array)){

                    foreach ($field_array as $array_value) {
                        $label = $array_value['field_label'];
                        $unique_id = $array_value['unique_id'];


                        if(is_array($field_value) && !empty($field_value)){

                            $new_value = '';
                            $i = 1;
                            $count_value = count($field_value);

                            foreach ($field_value as $val) {

                                if($i < $count_value){
                                    $new_value .= $val.', ';
                                } else {
                                    $new_value .= $val;
                                }
                                $i++;
                            }
                            $field_value = $new_value;
                        }

                        if($unique_id == $field_name){
                            $the_array[$unique_id]['label']   = $label;
                            $the_array[$unique_id]['value']   = $field_value;
                        }
                    }
                }
            }

            return $the_array;
        }

        public function rbfw_regf_value_array_function($rbfw_id){
            $rbfw_regf_fullname         = isset( $_POST['rbfw_regf_fullname'] ) ? $_POST['rbfw_regf_fullname'] : '';
            $rbfw_regf_fullname_label   = isset( $_POST['rbfw_regf_fullname_label'] ) ? $_POST['rbfw_regf_fullname_label'] : '';
            $rbfw_regf_email            = isset( $_POST['rbfw_regf_email'] ) ? $_POST['rbfw_regf_email'] : '';
            $rbfw_regf_email_label      = isset( $_POST['rbfw_regf_email_label'] ) ? $_POST['rbfw_regf_email_label'] : '';
            $rbfw_regf_address          = isset( $_POST['rbfw_regf_address'] ) ? $_POST['rbfw_regf_address'] : '';
            $rbfw_regf_address_label    = isset( $_POST['rbfw_regf_address_label'] ) ? $_POST['rbfw_regf_address_label'] : '';
            $rbfw_regf_phone            = isset( $_POST['rbfw_regf_phone'] ) ? $_POST['rbfw_regf_phone'] : '';
            $rbfw_regf_phone_label      = isset( $_POST['rbfw_regf_phone_label'] ) ? $_POST['rbfw_regf_phone_label'] : '';
            $rbfw_regf_gender           = isset( $_POST['rbfw_regf_gender'] ) ? $_POST['rbfw_regf_gender'] : '';
            $rbfw_regf_gender_label     = isset( $_POST['rbfw_regf_gender_label'] ) ? $_POST['rbfw_regf_gender_label'] : '';

            $the_array = [];

            if(!empty($rbfw_regf_fullname_label)){
                $the_array['fullname']['label'] = $rbfw_regf_fullname_label;
                $the_array['fullname']['value'] = $rbfw_regf_fullname;
            }

            if(!empty($rbfw_regf_email_label)){
                $the_array['email']['label']    = $rbfw_regf_email_label;
                $the_array['email']['value']    = $rbfw_regf_email;
            }

            if(!empty($rbfw_regf_address_label)){
                $the_array['address']['label']  = $rbfw_regf_address_label;
                $the_array['address']['value']  = $rbfw_regf_address;
            }

            if(!empty($rbfw_regf_phone_label)){
                $the_array['phone']['label']    = $rbfw_regf_phone_label;
                $the_array['phone']['value']    = $rbfw_regf_phone;
            }

            if(!empty($rbfw_regf_gender_label)){
                $the_array['gender']['label']   = $rbfw_regf_gender_label;
                $the_array['gender']['value']   = $rbfw_regf_gender;
            }

            $rbfw_new_fields_array = $this->rbfw_get_regf_new_fields_name($rbfw_id);

            if(!empty($rbfw_new_fields_array)){

                foreach ($rbfw_new_fields_array as $unique_id => $field_type) {

                    if(isset( $_POST[$unique_id] )){
                        $value = $_POST[$unique_id];
                        $label = $_POST[$unique_id.'_label'];

                        if(is_array($value) && !empty($value)){

                            $new_value = '';
                            $i = 1;
                            $count_value = count($value);

                            foreach ($value as $val) {

                                if($i < $count_value){
                                    $new_value .= $val.', ';
                                } else {
                                    $new_value .= $val;
                                }
                                $i++;
                            }
                            $value = $new_value;
                        }

                        $the_array[$unique_id]['label']   = $label;
                        $the_array[$unique_id]['value']   = $value;
                    }
                }
            }

            return $the_array;
        }

        public function rbfw_regf_add_cart_function($cart_item_data, $rbfw_id){
            $rbfw_regf_fullname         = isset( $_POST['rbfw_regf_fullname'] ) ? $_POST['rbfw_regf_fullname'] : '';
            $rbfw_regf_fullname_label   = isset( $_POST['rbfw_regf_fullname_label'] ) ? $_POST['rbfw_regf_fullname_label'] : '';
            $rbfw_regf_email            = isset( $_POST['rbfw_regf_email'] ) ? $_POST['rbfw_regf_email'] : '';
            $rbfw_regf_email_label      = isset( $_POST['rbfw_regf_email_label'] ) ? $_POST['rbfw_regf_email_label'] : '';
            $rbfw_regf_address          = isset( $_POST['rbfw_regf_address'] ) ? $_POST['rbfw_regf_address'] : '';
            $rbfw_regf_address_label    = isset( $_POST['rbfw_regf_address_label'] ) ? $_POST['rbfw_regf_address_label'] : '';
            $rbfw_regf_phone            = isset( $_POST['rbfw_regf_phone'] ) ? $_POST['rbfw_regf_phone'] : '';
            $rbfw_regf_phone_label      = isset( $_POST['rbfw_regf_phone_label'] ) ? $_POST['rbfw_regf_phone_label'] : '';
            $rbfw_regf_gender           = isset( $_POST['rbfw_regf_gender'] ) ? $_POST['rbfw_regf_gender'] : '';
            $rbfw_regf_gender_label     = isset( $_POST['rbfw_regf_gender_label'] ) ? $_POST['rbfw_regf_gender_label'] : '';

            $cart_item_data['rbfw_regf_fullname']       = $rbfw_regf_fullname;
            $cart_item_data['rbfw_regf_fullname_label'] = $rbfw_regf_fullname_label;
            $cart_item_data['rbfw_regf_email']          = $rbfw_regf_email;
            $cart_item_data['rbfw_regf_email_label']    = $rbfw_regf_email_label;
            $cart_item_data['rbfw_regf_address']        = $rbfw_regf_address;
            $cart_item_data['rbfw_regf_address_label']  = $rbfw_regf_address_label;
            $cart_item_data['rbfw_regf_phone']          = $rbfw_regf_phone;
            $cart_item_data['rbfw_regf_phone_label']    = $rbfw_regf_phone_label;
            $cart_item_data['rbfw_regf_gender']         = $rbfw_regf_gender;
            $cart_item_data['rbfw_regf_gender_label']   = $rbfw_regf_gender_label;

            $the_array = [];

            if(!empty($rbfw_regf_fullname_label)){
                $the_array['fullname']['label'] = $rbfw_regf_fullname_label;
                $the_array['fullname']['value'] = $rbfw_regf_fullname;
            }

            if(!empty($rbfw_regf_email_label)){
                $the_array['email']['label']    = $rbfw_regf_email_label;
                $the_array['email']['value']    = $rbfw_regf_email;
            }

            if(!empty($rbfw_regf_address_label)){
                $the_array['address']['label']  = $rbfw_regf_address_label;
                $the_array['address']['value']  = $rbfw_regf_address;
            }

            if(!empty($rbfw_regf_phone_label)){
                $the_array['phone']['label']    = $rbfw_regf_phone_label;
                $the_array['phone']['value']    = $rbfw_regf_phone;
            }

            if(!empty($rbfw_regf_gender_label)){
                $the_array['gender']['label']   = $rbfw_regf_gender_label;
                $the_array['gender']['value']   = $rbfw_regf_gender;
            }

            $rbfw_new_fields_array = $this->rbfw_get_regf_new_fields_name($rbfw_id);


            if(!empty($rbfw_new_fields_array)){

                foreach ($rbfw_new_fields_array as $unique_id => $field_type) {

                    if(isset( $_POST[$unique_id] )){
                        $value = $cart_item_data[$unique_id] = $_POST[$unique_id];
                        $label = $cart_item_data[$unique_id.'_label'] = $_POST[$unique_id.'_label'];
                        $the_array[$unique_id]['label']   = $label;
                        $the_array[$unique_id]['value']   = $value;
                    }
                }
            }
            $cart_item_data['rbfw_regf_info'] = $the_array;
            return $cart_item_data;

        }

        public function rbfw_regf_cart_item_display($cart_item){
            $rbfw_regf_fullname         = $cart_item['rbfw_regf_fullname'] ? $cart_item['rbfw_regf_fullname'] : '';
            $rbfw_regf_fullname_label   = $cart_item['rbfw_regf_fullname_label'] ? $cart_item['rbfw_regf_fullname_label'] : '';
            $rbfw_regf_email            = $cart_item['rbfw_regf_email'] ? $cart_item['rbfw_regf_email'] : '';
            $rbfw_regf_email_label      = $cart_item['rbfw_regf_email_label'] ? $cart_item['rbfw_regf_email_label'] : '';
            $rbfw_regf_address          = $cart_item['rbfw_regf_address'] ? $cart_item['rbfw_regf_address'] : '';
            $rbfw_regf_address_label    = $cart_item['rbfw_regf_address_label'] ? $cart_item['rbfw_regf_address_label'] : '';
            $rbfw_regf_phone            = $cart_item['rbfw_regf_phone'] ? $cart_item['rbfw_regf_phone'] : '';
            $rbfw_regf_phone_label      = $cart_item['rbfw_regf_phone_label'] ? $cart_item['rbfw_regf_phone_label'] : '';
            $rbfw_regf_gender           = $cart_item['rbfw_regf_gender'] ? $cart_item['rbfw_regf_gender'] : '';
            $rbfw_regf_gender_label     = $cart_item['rbfw_regf_gender_label'] ? $cart_item['rbfw_regf_gender_label'] : '';
            $rbfw_id                    = $cart_item['rbfw_id'];
            $rbfw_new_fields_array      = $this->rbfw_get_regf_new_fields_name($rbfw_id);
            $rbfw_regf_info             = $cart_item['rbfw_regf_info'] ? $cart_item['rbfw_regf_info'] : [];

            if(empty($rbfw_regf_info)){
                return;
            }

            ?>
            <table class="rbfw_regf_cart_table">
                <tr>
                    <td colspan="2" class="rbfw_regf_heading"><?php echo rbfw_string_return('rbfw_text_customer_information',__('Customer Information','booking-and-rental-manager-for-woocommerce')); ?></td>
                </tr>
                <?php if(!empty($rbfw_regf_fullname_label)){ ?>
                <tr>
                    <th><?php echo esc_html($rbfw_regf_fullname_label); ?></th>
                    <td><?php echo esc_html($rbfw_regf_fullname); ?></td>
                </tr>
                <?php } ?>
                <?php if(!empty($rbfw_regf_email_label)){ ?>
                <tr>
                    <th><?php echo esc_html($rbfw_regf_email_label); ?></th>
                    <td><?php echo esc_html($rbfw_regf_email); ?></td>
                </tr>
                <?php } ?>
                <?php if(!empty($rbfw_regf_address_label)){ ?>
                <tr>
                    <th><?php echo esc_html($rbfw_regf_address_label); ?></th>
                    <td><?php echo esc_html($rbfw_regf_address); ?></td>
                </tr>
                <?php } ?>
                <?php if(!empty($rbfw_regf_phone_label)){ ?>
                <tr>
                    <th><?php echo esc_html($rbfw_regf_phone_label); ?></th>
                    <td><?php echo esc_html($rbfw_regf_phone); ?></td>
                </tr>
                <?php } ?>
                <?php if(!empty($rbfw_regf_gender_label)){ ?>
                <tr>
                    <th><?php echo esc_html($rbfw_regf_gender_label); ?></th>
                    <td><?php echo esc_html($rbfw_regf_gender); ?></td>
                </tr>
                <?php } ?>
                <?php
                if(!empty($rbfw_new_fields_array)){
                    foreach ($rbfw_new_fields_array as $unique_id => $field_type) {
                        if(!empty($cart_item[$unique_id])){
                            $label = $cart_item[$unique_id.'_label'];
                            $value = $cart_item[$unique_id];
                            if($field_type == 'file'){
                                $value = '<a target="_blank" href="'.esc_url($value).'">'.esc_html__('View File','rbfw-pro').'</a>';
                            }

                            if($field_type == 'checkbox' && is_array($value) && !empty($value)){
                                $new_value = '';
                                $i = 1;
                                $count_value = count($value);

                                foreach ($value as $val) {

                                    if($i < $count_value){
                                        $new_value .= $val.', ';
                                    } else {
                                        $new_value .= $val;
                                    }
                                    $i++;
                                }
                                $value = $new_value;
                            }
                            ?>
                            <tr>
                                <th><?php echo esc_html($label); ?></th>
                                <td><?php echo $value; ?></td>
                            </tr>
                            <?php
                        }

                    }
                }
                ?>
            </table>
            <?php
        }

        public function rbfw_regf_custom_script(){
            ?>
            <script>
            jQuery(document).on('change', '.rbfw_regf_group input[type=file]', function(e) {
                e.preventDefault();
                let this_parent = jQuery(this).parent('.rbfw_regf_file_wrap');
                let this_id = jQuery(this).attr('id');
                let target = this_parent.siblings('input[name='+this_id+']');
                let fileInputElement = document.getElementById(this_id);
                let fileName = fileInputElement.files[0].name;
                var formData = new FormData();
                formData.append('file', fileInputElement.files[0]);
                formData.append('action', 'rbfw_regf_upload_file');

                if(fileName == "")
                {
                    alert('Upload your file');
                    return false;

                } else {
                    var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
                    jQuery.ajax({
                        url:ajax_url,
                        type:"POST",
                        processData: false,
                        contentType: false,
                        data:  formData,
                        beforeSend: function() {
                            jQuery(this_parent).find('.rbfw_regf_file_loader').removeClass('up_success');
                            jQuery(this_parent).append('<span class="rbfw_regf_file_loader"><i class="fas fa-spinner fa-spin"></i></span>');
                        },
                        success : function( response ){

                            var returnedData = JSON.parse(response);
                            if(returnedData.url != ''){
                                jQuery(target).val(returnedData.url);
                                jQuery(this_parent).find('.rbfw_regf_file_loader').empty();
                                jQuery(this_parent).find('.rbfw_regf_file_loader').addClass('up_success').append('<i class="fa-solid fa-circle-check"></i>');
                            }else{
                                console.log(returnedData.msg);
                            }

                        },
                    });
                    return false;
                }
                return false;
            });
            </script>
            <?php
        }
    }
    new Rbfw_Reg_Form();
}
