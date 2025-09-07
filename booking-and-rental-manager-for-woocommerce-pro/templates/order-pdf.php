<?php
if ( ! defined('ABSPATH')) exit;  // if direct access  

$template_dir = RBMW_PRO_PLUGIN_DIR.'templates/pdf-templates/default/default.php';
?>
<html><body>
<?php
$args = array(
    'posts_per_page' => -1,
    'post_type' => 'rbfw_order',
    'p' => $order_id
);

$query = new WP_Query($args);    

$tickets = $query->posts;

foreach ($tickets as $ticket) {
    $order_id = $ticket->ID;
    include $template_dir;
}