<?php
if ( ! defined( 'ABSPATH' ) ) {
    die;
} // Cannot access pages directly.


add_shortcode('stripe', 'rbfw_stripe_shortcode_func');
function rbfw_stripe_shortcode_func($atts) {
    //include RBMW_PLUGIN_DIR_PRO . '/inc/paypal/stripe_config.php';
    ob_start();
    ?>
    <div>
    <?php if ( isset($_SESSION['payment_id']) ) { ?>
    <div class="success">
        <strong><?php echo 'Payment is successful. Payment ID is :'. $_SESSION['payment_id']; ?></strong>
        <strong><?php echo 'Payment is successful. Payment Ref is :'. $_SESSION['payment_ref']; ?></strong>
        <strong><?php echo 'Payment is successful. Payment Status is :'. $_SESSION['payment_status']; ?></strong>
    </div>
    <?php unset($_SESSION['payment_id']); ?>
<?php } elseif ( isset($_SESSION['payment_error']) ) { ?>
    <div class="error">
        <strong><?php echo $_SESSION['payment_error']; ?></strong>
    </div>
    <?php unset($_SESSION['payment_error']); ?>
<?php } ?>
 
<form  method="post" id="payment-form">
    <div class="form-row">
        <input type="text" name="amount" placeholder="Enter Amount" />
        <p><label for="card-element">Credit or debit card</label></p>
        <div id="card-element">
        <!-- A Stripe Element will be inserted here. -->
        </div>
  
        <!-- Used to display form errors. -->
        <div id="card-errors" role="alert"></div>
    </div>
    <p><button>Submit Payment</button></p>
</form>
 
<script>
var publishable_key = '<?php echo STRIPE_PUBLISHABLE_KEY; ?>';
</script>
    </div>
    <?php
    $content = ob_get_clean();
    return $content;
}