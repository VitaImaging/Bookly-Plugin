<?php
if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

require_once(dirname(__FILE__) . "/gateway/vendor/autoload.php");
require_once(dirname(__FILE__) . "/functions.php");
require_once(dirname(__FILE__) . "/admin_settings.php");
require_once(dirname(__FILE__) . "/csv_export.php");
require_once(dirname(__FILE__) . "/attendee_list_dashboard.php");
require_once(dirname(__FILE__) . "/booking_in_calender.php");
require_once(dirname(__FILE__) . "/rbfw_pdf.php");
require_once(dirname(__FILE__) . "/rbfw_cpt.php");
require_once(dirname(__FILE__) . "/class-email-function.php");
require_once(dirname(__FILE__) . "/gateway/paypal_charge.php");
require_once(dirname(__FILE__) . "/gateway/stripe_charge.php");
//require_once(dirname(__FILE__) . "/gateway/stripe_enqueue.php");
require_once(dirname(__FILE__) . "/gateway/stripe_form.php");
require_once(dirname(__FILE__) . "/class-reg-form.php");
require_once(dirname(__FILE__) . "/reviews_functions.php");