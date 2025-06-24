<?php
/**
 * ACF Setup & Configuration
 * 
 * All Advanced Custom Fields related functionality in one place
 * Quick edit file for ACF options, custom post types, etc.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * ACF OPTIONS PAGES
 */
if (function_exists('acf_add_options_page')) {
    // Navigation Options
    acf_add_options_page(array(
        'page_title' => 'Navigation',
        'menu_title' => 'Navigation',
        'menu_slug'  => 'navigation',
        'capability' => 'edit_posts',
        'redirect'   => false,
        'icon_url'   => 'dashicons-list-view',
        'position'   => '30'
    ));

    // Theme General Options
    acf_add_options_page(array(
        'page_title' => 'Theme Settings',
        'menu_title' => 'Theme Options',
        'menu_slug'  => 'theme-options',
        'capability' => 'edit_posts',
        'redirect'   => false,
        'icon_url'   => 'dashicons-admin-generic',
        'position'   => '31'
    ));
}

/**
 * CUSTOM POST TYPES
 * All custom post types with ACF integration
 */

// Register Locations Post Type
function register_locations_post_type() {
    $labels = array(
        'name'                  => _x('Locations', 'Post Type General Name', 'text_domain'),
        'singular_name'         => _x('Location', 'Post Type Singular Name', 'text_domain'),
        'menu_name'             => __('Locations', 'text_domain'),
        'name_admin_bar'        => __('Location', 'text_domain'),
        'archives'              => __('Location Archives', 'text_domain'),
        'attributes'            => __('Location Attributes', 'text_domain'),
        'parent_item_colon'     => __('Parent Location:', 'text_domain'),
        'all_items'             => __('All Locations', 'text_domain'),
        'add_new_item'          => __('Add New Location', 'text_domain'),
        'add_new'               => __('Add New', 'text_domain'),
        'new_item'              => __('New Location', 'text_domain'),
        'edit_item'             => __('Edit Location', 'text_domain'),
        'update_item'           => __('Update Location', 'text_domain'),
        'view_item'             => __('View Location', 'text_domain'),
        'view_items'            => __('View Locations', 'text_domain'),
        'search_items'          => __('Search Location', 'text_domain'),
        'not_found'             => __('Not found', 'text_domain'),
        'not_found_in_trash'    => __('Not found in Trash', 'text_domain'),
        'featured_image'        => __('Featured Image', 'text_domain'),
        'set_featured_image'    => __('Set featured image', 'text_domain'),
        'remove_featured_image' => __('Remove featured image', 'text_domain'),
        'use_featured_image'    => __('Use as featured image', 'text_domain'),
        'insert_into_item'      => __('Insert into location', 'text_domain'),
        'uploaded_to_this_item' => __('Uploaded to this location', 'text_domain'),
        'items_list'            => __('Locations list', 'text_domain'),
        'items_list_navigation' => __('Locations list navigation', 'text_domain'),
        'filter_items_list'     => __('Filter locations list', 'text_domain'),
    );
    
    $args = array(
        'label'                 => __('Location', 'text_domain'),
        'description'           => __('Custom Post Type for locations', 'text_domain'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'custom-fields'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
    );
    
    register_post_type('locations', $args);
}
add_action('init', 'register_locations_post_type', 0);

// Add more custom post types here as needed
// Example: Arbeitsgruppen, Personen, etc.

/**
 * ACF HELPER FUNCTIONS
 */

// Get ACF field with fallback
function d64_get_field($field_name, $post_id = null, $fallback = '') {
    if (function_exists('get_field')) {
        $value = get_field($field_name, $post_id);
        return !empty($value) ? $value : $fallback;
    }
    return $fallback;
}

// Get ACF option with fallback
function d64_get_option($option_name, $fallback = '') {
    if (function_exists('get_field')) {
        $value = get_field($option_name, 'option');
        return !empty($value) ? $value : $fallback;
    }
    return $fallback;
}

/**
 * CONTENT HELPER FUNCTIONS
 */

// Get navigation from ACF options
function d64_get_navigation($location = 'main') {
    $navigation = d64_get_option($location . '_navigation', []);
    
    if (!empty($navigation)) {
        echo '<nav class="navigation navigation-' . esc_attr($location) . '">';
        foreach ($navigation as $item) {
            $link = $item['link'] ?? [];
            if (!empty($link['url'])) {
                printf(
                    '<a href="%s" target="%s" class="nav-link">%s</a>',
                    esc_url($link['url']),
                    esc_attr($link['target'] ?? '_self'),
                    esc_html($link['title'] ?? '')
                );
            }
        }
        echo '</nav>';
    }
}

// Get hero section data
function d64_get_hero_data($post_id = null) {
    return [
        'title' => d64_get_field('hero_title', $post_id, get_the_title($post_id)),
        'subtitle' => d64_get_field('hero_subtitle', $post_id),
        'content' => d64_get_field('hero_content', $post_id),
        'image' => d64_get_field('hero_image', $post_id),
        'cta_button' => d64_get_field('hero_cta', $post_id),
        'background_color' => d64_get_field('hero_bg_color', $post_id, 'default')
    ];
}

/**
 * ACF VALIDATION & SANITIZATION
 */

// Validate email fields
function d64_validate_email_field($valid, $value, $field, $input) {
    if (!$valid || empty($value)) {
        return $valid;
    }
    
    if (!is_email($value)) {
        $valid = 'Please enter a valid email address.';
    }
    
    return $valid;
}
add_filter('acf/validate_value/type=email', 'd64_validate_email_field', 10, 4);

// Sanitize text fields
function d64_sanitize_text_field($value, $post_id, $field) {
    return sanitize_text_field($value);
}
add_filter('acf/update_value/type=text', 'd64_sanitize_text_field', 10, 3);

/**
 * ACF ADMIN CUSTOMIZATIONS
 */

// Hide ACF from non-admins (optional)
function d64_hide_acf_admin() {
    if (!current_user_can('manage_options')) {
        add_filter('acf/settings/show_admin', '__return_false');
    }
}
add_action('init', 'd64_hide_acf_admin');

// ACF Google Maps API (if needed)
function d64_acf_google_maps_api($api) {
    $api['key'] = d64_get_option('google_maps_api_key', '');
    return $api;
}
add_filter('acf/fields/google_map/api', 'd64_acf_google_maps_api');