<?php
if ( ! defined( 'ABSPATH' ) ) {
    die;
} // Cannot access pages directly.

add_action( 'rbfw_after_rent_item_type_table_row', 'rbfw_min_max_booking_day_form');

function panel_header($title, $description){
    ?>
    <section class="bg-light mt-5">
        <div>
            <label>
                <?php echo sprintf(__("%s",'booking-and-rental-manager-for-woocommerce'), $title ); ?>
            </label>
            <span style=" display: flex; flex-direction: column; "><?php echo sprintf(__("%s",'booking-and-rental-manager-for-woocommerce'), $description ); ?></span>
        </div>
    </section>
    <?php
}
function rbfw_min_max_booking_day_form() {


    global $post;
    $post_id = !empty($post->ID) ? $post->ID : '';

    if(empty($post_id)){
        return;
    }

    $rbfw_minimum_booking_day = get_post_meta($post_id, 'rbfw_minimum_booking_day', true);
    $rbfw_maximum_booking_day = get_post_meta($post_id, 'rbfw_maximum_booking_day', true);

    $rbfw_item_type = get_post_meta( $post_id, 'rbfw_item_type', true ) ? get_post_meta( $post_id, 'rbfw_item_type', true ) : 'bike_car_sd';
    ?>

    <div class="rbfw_min_max_booking_day_row" <?php if ( $rbfw_item_type == 'bike_car_sd' || $rbfw_item_type == 'appointment') { echo 'style="display:none"'; } ?>>
      <div>
          <?php panel_header('Minimum-Maximum day of booking', 'You can setup maximum day and minimum day booking by this settings.'); ?>
          <div class="booking-container">
              <div class="booking-input-group">
                  <label for="rbfw_minimum_booking_day"><?php esc_html_e( 'Minimum day of booking', 'booking-and-rental-manager-for-woocommerce' ); ?></label>
                  <input type="number"
                         name="rbfw_minimum_booking_day"
                         id="rbfw_minimum_booking_day"
                         value="<?php echo esc_attr($rbfw_minimum_booking_day); ?>"
                         placeholder="<?php esc_attr_e( 'Ex: 1', 'booking-and-rental-manager-for-woocommerce' ); ?>">
              </div>
              <div class="booking-input-group">
                  <label for="rbfw_maximum_booking_day"><?php esc_html_e( 'Maximum day of booking', 'booking-and-rental-manager-for-woocommerce' ); ?></label>
                  <input type="number"
                         name="rbfw_maximum_booking_day"
                         id="rbfw_maximum_booking_day"
                         value="<?php echo esc_attr($rbfw_maximum_booking_day); ?>"
                         placeholder="<?php esc_attr_e( 'Ex : 10', 'booking-and-rental-manager-for-woocommerce' ); ?>">
              </div>
          </div>

      </div>
<style>
    /* Custom CSS for Responsive Layout */
    .booking-container {
        display: flex;
        flex-wrap: wrap;
        /*gap: 15%;*/
        border: 5px solid var(--mage-light);
        padding: 20px;
        text-align: center;
    }
    /* Label styling */
    .booking-input-group label {
        font-size: 20px !important;
        margin-bottom: 5px;
        color: black !important;
        font-weight: 400 !important;
    }

    /* Input styling */
    .booking-input-group input[type="number"] {
        padding: 8px !important;
        font-size: 16px;
        width: 100% !important;
        max-width: 300px;
        box-sizing: border-box;
        border-radius: 5px;
        border: 1px solid #ccc !important;
        margin-top: 3%;
        text-align: center;
    }

    /* Media Query for Tablets and smaller devices */
    @media (max-width: 768px) {
        .booking-container {
            flex-direction: column;
        }
    }

    /* Media Query for very small devices */
    @media (max-width: 576px) {
        .booking-input-group label {
            font-size: 14px !important;
        }

        .booking-input-group input[type="number"] {
            font-size: 14px !important;
            padding: 6px;
        }
    }

</style>
    </div>

    <?php
}

add_action( 'save_post', 'rbfw_min_max_booking_day_form_save');

function rbfw_min_max_booking_day_form_save( $post_id ) {

    global $post;

    $post_id = !empty($post->ID) ? $post->ID : '';

    if(empty($post_id)){
        return;
    }

    if ( get_post_type( $post_id ) == 'rbfw_item' ) {
        
        if(isset($_POST['rbfw_minimum_booking_day'])){
            
            update_post_meta($post_id, 'rbfw_minimum_booking_day', sanitize_text_field($_POST['rbfw_minimum_booking_day']));
        }

        if(isset($_POST['rbfw_maximum_booking_day'])){
            
            update_post_meta($post_id, 'rbfw_maximum_booking_day', sanitize_text_field($_POST['rbfw_maximum_booking_day']));
        }
    }

}