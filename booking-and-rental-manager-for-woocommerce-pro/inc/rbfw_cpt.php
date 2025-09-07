<?php
if (!defined('ABSPATH')) {
    die;
} // Cannot access pages directly.

function rbfw_pro_cpt()
{    

    $labels = array(
                'name'                  => __('Registration Form', 'booking-and-rental-manager-for-woocommerce-pro'),
                'singular_name'         => __('Registration Form', 'booking-and-rental-manager-for-woocommerce-pro'),
                'menu_name'             => __('Registration Form', 'booking-and-rental-manager-for-woocommerce-pro'),
                'name_admin_bar'        => __('Registration Form', 'booking-and-rental-manager-for-woocommerce-pro'),
                'archives'              => __('Registration Form' . ' List', 'booking-and-rental-manager-for-woocommerce-pro'),
                'attributes'            => __('Registration Form' . ' List', 'booking-and-rental-manager-for-woocommerce-pro'),
                'parent_item_colon'     => __('Registration Form' . ' Item:', 'booking-and-rental-manager-for-woocommerce-pro'),
                'all_items'             => __('Registration Form', 'booking-and-rental-manager-for-woocommerce-pro'),
                'add_new_item'          => __('Add New ' . 'Registration Form', 'booking-and-rental-manager-for-woocommerce-pro'),
                'add_new'               => __('Add New ' . 'Registration Form', 'booking-and-rental-manager-for-woocommerce-pro'),
                'new_item'              => __('New ' . 'Registration Form', 'booking-and-rental-manager-for-woocommerce-pro'),
                'edit_item'             => __('Edit ' . 'Registration Form', 'booking-and-rental-manager-for-woocommerce-pro'),
                'update_item'           => __('Update ' . 'Registration Form', 'booking-and-rental-manager-for-woocommerce-pro'),
                'view_item'             => __('View ' . 'Registration Form', 'booking-and-rental-manager-for-woocommerce-pro'),
                'view_items'            => __('View ' . 'Registration Form', 'booking-and-rental-manager-for-woocommerce-pro'),
                'search_items'          => __('Search ' . 'Registration Form', 'booking-and-rental-manager-for-woocommerce-pro'),
                'not_found'             => __('Registration Form' . ' Not found', 'booking-and-rental-manager-for-woocommerce-pro'),
                'not_found_in_trash'    => __('Registration Form' . ' Not found in Trash', 'booking-and-rental-manager-for-woocommerce-pro'),
                'featured_image'        => __('Registration Form' . ' Feature Image', 'booking-and-rental-manager-for-woocommerce-pro'),
                'set_featured_image'    => __('Set ' . 'Registration Form' . ' featured image', 'booking-and-rental-manager-for-woocommerce-pro'),
                'remove_featured_image' => __('Remove ' . 'Registration Form' . ' featured image', 'booking-and-rental-manager-for-woocommerce-pro'),
                'use_featured_image'    => __('Use as ' . 'Registration Form' . ' featured image', 'booking-and-rental-manager-for-woocommerce-pro'),
                'insert_into_item'      => __('Insert into ' . 'Registration Form', 'booking-and-rental-manager-for-woocommerce-pro'),
                'uploaded_to_this_item' => __('Uploaded to this ' . 'Registration Form', 'booking-and-rental-manager-for-woocommerce-pro'),
                'items_list'            => __('Registration Form' . ' list', 'booking-and-rental-manager-for-woocommerce-pro'),
                'items_list_navigation' => __('Registration Form' . ' list navigation', 'booking-and-rental-manager-for-woocommerce-pro'),
                'filter_items_list'     => __('Filter ' . 'Registration Form' . ' list', 'booking-and-rental-manager-for-woocommerce-pro'),
            );

        $args = array(
            'public'                => true,
            'show_in_menu'          => 'edit.php?post_type=rbfw_item',
            'labels'                => $labels,
            'supports'              => array('title', '', '', '', '', ''),
            'rewrite'               => array('slug' => 'rbfw_reg_form')
        );

        register_post_type('rbfw_reg_form', $args);

        $pending_reviews = rbfw_review_pending_number();

        $labels = array(
            'name'                  => __('Reviews', 'booking-and-rental-manager-for-woocommerce-pro'),
            'singular_name'         => __('Reviews', 'booking-and-rental-manager-for-woocommerce-pro'),
            'menu_name'             => __('Reviews', 'booking-and-rental-manager-for-woocommerce-pro'),
            'name_admin_bar'        => __('Reviews', 'booking-and-rental-manager-for-woocommerce-pro'),
            'archives'              => __('Reviews' . ' List', 'booking-and-rental-manager-for-woocommerce-pro'),
            'attributes'            => __('Reviews' . ' List', 'booking-and-rental-manager-for-woocommerce-pro'),
            'parent_item_colon'     => __('Reviews' . ' Item:', 'booking-and-rental-manager-for-woocommerce-pro'),
            'all_items'             => __('Reviews'. $pending_reviews, 'booking-and-rental-manager-for-woocommerce-pro'),
            'add_new_item'          => __('Add New ' . 'Reviews', 'booking-and-rental-manager-for-woocommerce-pro'),
            'add_new'               => __('Add New ' . 'Reviews', 'booking-and-rental-manager-for-woocommerce-pro'),
            'new_item'              => __('New ' . 'Reviews', 'booking-and-rental-manager-for-woocommerce-pro'),
            'edit_item'             => __('Edit ' . 'Reviews', 'booking-and-rental-manager-for-woocommerce-pro'),
            'update_item'           => __('Update ' . 'Reviews', 'booking-and-rental-manager-for-woocommerce-pro'),
            'view_item'             => __('View ' . 'Reviews', 'booking-and-rental-manager-for-woocommerce-pro'),
            'view_items'            => __('View ' . 'Reviews', 'booking-and-rental-manager-for-woocommerce-pro'),
            'search_items'          => __('Search ' . 'Reviews', 'booking-and-rental-manager-for-woocommerce-pro'),
            'not_found'             => __('No reviews found', 'booking-and-rental-manager-for-woocommerce-pro'),
            'not_found_in_trash'    => __('Reviews' . ' Not found in Trash', 'booking-and-rental-manager-for-woocommerce-pro'),
            'featured_image'        => __('Reviews' . ' Feature Image', 'booking-and-rental-manager-for-woocommerce-pro'),
            'set_featured_image'    => __('Set ' . 'Reviews' . ' featured image', 'booking-and-rental-manager-for-woocommerce-pro'),
            'remove_featured_image' => __('Remove ' . 'Reviews' . ' featured image', 'booking-and-rental-manager-for-woocommerce-pro'),
            'use_featured_image'    => __('Use as ' . 'Reviews' . ' featured image', 'booking-and-rental-manager-for-woocommerce-pro'),
            'insert_into_item'      => __('Insert into ' . 'Reviews', 'booking-and-rental-manager-for-woocommerce-pro'),
            'uploaded_to_this_item' => __('Uploaded to this ' . 'Reviews', 'booking-and-rental-manager-for-woocommerce-pro'),
            'items_list'            => __('Reviews' . ' list', 'booking-and-rental-manager-for-woocommerce-pro'),
            'items_list_navigation' => __('Reviews' . ' list navigation', 'booking-and-rental-manager-for-woocommerce-pro'),
            'filter_items_list'     => __('Filter ' . 'Reviews' . ' list', 'booking-and-rental-manager-for-woocommerce-pro'),
        );

        $args = array(
            'public'                => true,
            'show_in_menu'          => 'edit.php?post_type=rbfw_item',
            'labels'                => $labels,
            'supports'              => array('', '', '', '', '', ''),
            'rewrite'               => array('slug' => 'rbfw_item_reviews'),
            'capability_type'       => 'post',
            'capabilities'          => array(
                    'create_posts'  => 'do_not_allow',
                    // Removes support for the "Add New" function, including Super Admin's
            ),
            'map_meta_cap'          => true,
        );

        register_post_type('rbfw_item_reviews', $args);


}

add_action('init', 'rbfw_pro_cpt');