<?php
/**
 * D64 Theme Functions
 * Simple, practical structure for quick edits
 * 
 * @package d64
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Theme version for cache busting
define('D64_VERSION', '0.1.0');

// Tailwind Typography classes
define('D64_TYPOGRAPHY_CLASSES', 'prose prose-neutral max-w-none prose-a:text-primary prose-figure:my-4 prose-figure:py-0 prose-figure:text-d64blue-900 prose-figure:!w-[min(100%,640px)] prose-img:max-w-full');

/**
 * CORE THEME SETUP
 */
function d64_setup() {
    load_theme_textdomain('d64', get_template_directory() . '/languages');
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('editor-styles');
    add_editor_style('style-editor.css');
    add_theme_support('responsive-embeds');
    remove_theme_support('block-templates');
}
add_action('after_setup_theme', 'd64_setup');

/**
 * SCRIPTS & STYLES
 */
function d64_scripts() {
    wp_enqueue_style('d64-style', get_stylesheet_uri(), [], D64_VERSION);
    wp_enqueue_script('d64-script', get_template_directory_uri() . '/js/script.min.js', ['jquery'], D64_VERSION, true);
    
    // AJAX setup
    wp_localize_script('d64-script', 'my_ajax_object', ['ajax_url' => admin_url('admin-ajax.php')]);
    
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'd64_scripts');

/**
 * BLOCK EDITOR SETUP
 */
function d64_enqueue_block_editor_script() {
    wp_enqueue_script('d64-editor', get_template_directory_uri() . '/js/block-editor.min.js', ['wp-blocks', 'wp-edit-post'], D64_VERSION, true);
}
add_action('enqueue_block_editor_assets', 'd64_enqueue_block_editor_script');

function d64_admin_scripts() {
    ?>
    <script>
        tailwindTypographyClasses = '<?php echo esc_attr(D64_TYPOGRAPHY_CLASSES); ?>'.split(' ');
    </script>
    <?php
}
add_action('admin_print_scripts', 'd64_admin_scripts');

function d64_tinymce_add_class($settings) {
    $settings['body_class'] = D64_TYPOGRAPHY_CLASSES;
    return $settings;
}
add_filter('tiny_mce_before_init', 'd64_tinymce_add_class');

/**
 * WIDGET AREAS
 */
function d64_widgets_init() {
    register_sidebar([
        'name'          => __('Footer', 'd64'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here to appear in your footer.', 'd64'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ]);
}
add_action('widgets_init', 'd64_widgets_init');

/**
 * IMAGE SIZES
 */
function d64_custom_image_sizes() {
    // 16:9 aspect ratio
    add_image_size('large-16-9', 1920, 1080, true);
    add_image_size('medium-16-9', 960, 540, true);
    add_image_size('small-16-9', 480, 270, true);

    // 1:1 aspect ratio
    add_image_size('large-1-1', 1200, 1200, true);
    add_image_size('medium-1-1', 600, 600, true);
    add_image_size('small-1-1', 300, 300, true);
}
add_action('after_setup_theme', 'd64_custom_image_sizes');

/**
 * EXCERPT MODIFICATIONS
 */
function d64_add_excerpt_to_pages() {
    add_post_type_support('page', 'excerpt');
}
add_action('init', 'd64_add_excerpt_to_pages');

function custom_excerpt_length($length) {
    return 999;
}
add_filter('excerpt_length', 'custom_excerpt_length');

function new_excerpt_more($more) {
    return '';
}
add_filter('excerpt_more', 'new_excerpt_more');

/**
 * GUTENBERG BLOCKS WHITELIST
 */
function d64_allowed_block_types($allowed_blocks) {
    return [
        'core/paragraph',
        'core/heading',
        'core/list',
        'core/quote',
        'core/table',
        'core/image',
        'core/separator',
        'core/youtube',
        'core/pullquote',
        'core/shortcode',
        'core/buttons',
        'd64/location-map',
        'd64/accordion'
    ];
}
add_filter('allowed_block_types', 'd64_allowed_block_types');

/**
 * TEMPLATE FUNCTIONS (from _tw theme)
 */
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/template-functions.php';

/**
 * LOAD FEATURE MODULES
 * Each file contains one specific functionality for quick edits
 */

// Blog filtering & search AJAX
if (file_exists(get_template_directory() . '/inc/blog-ajax.php')) {
    require get_template_directory() . '/inc/blog-ajax.php';
}

// All ACF related functionality (options, post types, helpers)
if (file_exists(get_template_directory() . '/inc/acf-setup.php')) {
    require get_template_directory() . '/inc/acf-setup.php';
}

// Complete map & locations functionality
if (file_exists(get_template_directory() . '/inc/map-locations.php')) {
    require get_template_directory() . '/inc/map-locations.php';
}

// Simple accordion block
if (file_exists(get_template_directory() . '/inc/accordion-block.php')) {
    require get_template_directory() . '/inc/accordion-block.php';
}