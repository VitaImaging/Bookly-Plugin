<?php
if ( ! defined( 'ABSPATH' ) ) {
    die;
} // Cannot access pages directly.

add_action('wp_footer', 'rbfw_mmbd_add_scripts');

function rbfw_mmbd_add_scripts()
{
    global $post;

    if(empty($post->ID)){

        return;

    } else {

        $post_id = $post->ID;
    }

    $rbfw_item_type = get_post_meta( $post_id, 'rbfw_item_type', true );

    if ( $rbfw_item_type != 'bike_car_sd' || $rbfw_item_type != 'appointment') {

        $rbfw_minimum_booking_day = !empty(get_post_meta($post_id, 'rbfw_minimum_booking_day', true)) ? get_post_meta($post_id, 'rbfw_minimum_booking_day', true) : 0;
        $rbfw_maximum_booking_day = !empty(get_post_meta($post_id, 'rbfw_maximum_booking_day', true)) ? get_post_meta($post_id, 'rbfw_maximum_booking_day', true) : 0;

        if(!empty($rbfw_minimum_booking_day) || !empty($rbfw_maximum_booking_day)){
    ?>
    <script>
    jQuery(document).ready(function() {

        jQuery('#checkin_date').change(function(e) {

            let min_days = <?php echo $rbfw_minimum_booking_day; ?>;
            let max_days = <?php echo $rbfw_maximum_booking_day; ?>;
            let selected_date = jQuery(this).val();
            selected_date = new Date(selected_date);
            let min_date = new Date(selected_date.setDate(selected_date.getDate() + min_days));

            jQuery("#checkout_date").datepicker("destroy");
            jQuery("#checkout_date").val('');
            jQuery("#checkout_date").attr('value', '');

            if(max_days > 0){

                max_days = max_days - min_days;
                let max_date = new Date(selected_date.setDate(selected_date.getDate() + max_days));
                jQuery('#checkout_date').datepicker({
                    dateFormat: 'yy-mm-dd',
                    minDate: min_date,
                    maxDate: max_date,
                });

            } else {
                jQuery('#checkout_date').datepicker({
                    dateFormat: 'yy-mm-dd',
                    minDate: min_date
                });  
            }

            });
        


        jQuery('#pickup_date').change(function(e) {

            let min_days = <?php echo $rbfw_minimum_booking_day; ?>;
            let max_days = <?php echo $rbfw_maximum_booking_day; ?>;
            let selected_date = jQuery(this).val();
            selected_date = new Date(selected_date); 
            let min_date = new Date(selected_date.setDate(selected_date.getDate() + min_days));

            console.log('min_date',min_date);

            jQuery("#dropoff_date").datepicker("destroy");
            jQuery("#dropoff_date").val('');
            jQuery("#dropoff_date").attr('value', '');

            if(max_days > 0){
                
                max_days = max_days - min_days;
                let max_date = new Date(selected_date.setDate(selected_date.getDate() + max_days));
                jQuery('#dropoff_date').datepicker({
                    dateFormat: 'yy-mm-dd',
                    minDate: min_date,
                    maxDate: max_date,
                });

            } else {
                jQuery('#dropoff_date').datepicker({
                    dateFormat: 'yy-mm-dd',
                    minDate: min_date
                });  
            }

        });

    });        
    </script>
    <?php
        }
    }
}