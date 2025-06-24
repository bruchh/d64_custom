<?php
/**
 * Accordion Block
 * 
 * Simple, standalone accordion functionality
 * Quick edit file for accordion-related changes
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * REGISTER ACCORDION BLOCK
 */
function d64_register_accordion_block() {
    if (!function_exists('register_block_type')) {
        return;
    }
    
    register_block_type('d64/accordion', array(
        'render_callback' => 'd64_render_accordion_block'
    ));
}
add_action('init', 'd64_register_accordion_block');

/**
 * RENDER ACCORDION BLOCK
 */
function d64_render_accordion_block($attributes, $content) {
    $title = isset($attributes['title']) ? $attributes['title'] : 'Accordion Title';
    $unique_id = 'accordion-' . uniqid();
    $content_id = 'content-' . $unique_id;
    
    return sprintf(
        '<div class="d64-accordion" style="margin-bottom: 20px; border: 1px solid #021D4C; border-radius: 16px; overflow: clip;">
            <button 
                id="%s"
                class="d64-accordion-header"
                type="button"
                aria-expanded="false" 
                aria-controls="%s"
                style="background: #EDF2F4; padding: 16px; cursor: pointer; width: 100%%; text-align: left; display: flex; justify-content: space-between; align-items: center; border: none;">
                <strong>%s</strong>
                <svg 
                    class="d64-accordion-icon" 
                    aria-hidden="true" 
                    width="20" 
                    height="20" 
                    viewBox="0 0 24 24" 
                    fill="none" 
                    stroke="currentColor" 
                    stroke-width="2" 
                    stroke-linecap="round" 
                    stroke-linejoin="round"
                    style="transition: transform 0.3s ease;">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </button>
            <div 
                id="%s" 
                class="d64-accordion-content prose prose-sm prose-headings:mt-4" 
                style="padding: 12px; padding-top: 0px; display: none;">
                %s
            </div>
        </div>',
        esc_attr($unique_id),
        esc_attr($content_id),
        esc_html($title),
        esc_attr($content_id),
        $content
    );
}

/**
 * ACCORDION JAVASCRIPT
 * Add to footer on pages with accordion blocks
 */
function d64_accordion_scripts() {
    global $post;
    
    // Only load on pages with accordion blocks
    if (is_a($post, 'WP_Post') && has_block('d64/accordion', $post)) {
        add_action('wp_footer', 'd64_accordion_js');
    }
}
add_action('wp', 'd64_accordion_scripts');

function d64_accordion_js() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Find all accordion headers
        const accordionHeaders = document.querySelectorAll('.d64-accordion-header');
        
        accordionHeaders.forEach(function(header) {
            header.addEventListener('click', function(e) {
                e.preventDefault();
                
                const contentId = this.getAttribute('aria-controls');
                const content = document.getElementById(contentId);
                const icon = this.querySelector('.d64-accordion-icon');
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                
                if (content) {
                    if (isExpanded) {
                        // Close accordion
                        content.style.display = 'none';
                        this.setAttribute('aria-expanded', 'false');
                        if (icon) {
                            icon.style.transform = 'rotate(0deg)';
                        }
                    } else {
                        // Open accordion
                        content.style.display = 'block';
                        this.setAttribute('aria-expanded', 'true');
                        if (icon) {
                            icon.style.transform = 'rotate(180deg)';
                        }
                    }
                }
            });
            
            // Keyboard accessibility
            header.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
        });
    });
    </script>
    <?php
}

/**
 * ACCORDION HELPER FUNCTIONS
 */

// Check if current page has accordion blocks
function d64_has_accordion_blocks($post_id = null) {
    if (!$post_id) {
        global $post;
        $post_id = $post->ID ?? null;
    }
    
    if (!$post_id) {
        return false;
    }
    
    return has_block('d64/accordion', $post_id);
}

// Get all accordion blocks from a post
function d64_get_accordion_blocks($post_id = null) {
    if (!$post_id) {
        global $post;
        $post_id = $post->ID ?? null;
    }
    
    if (!$post_id) {
        return [];
    }
    
    $post_content = get_post_field('post_content', $post_id);
    $blocks = parse_blocks($post_content);
    $accordion_blocks = [];
    
    foreach ($blocks as $block) {
        if ($block['blockName'] === 'd64/accordion') {
            $accordion_blocks[] = $block;
        }
    }
    
    return $accordion_blocks;
}

/**
 * ADMIN STYLING FOR ACCORDION BLOCK
 */
function d64_accordion_admin_styles() {
    add_action('admin_head', function() {
        echo '<style>
            .d64-accordion-header {
                background: #EDF2F4 !important;
                border: 1px solid #021D4C !important;
                border-radius: 8px !important;
                padding: 12px 16px !important;
                width: 100% !important;
                text-align: left !important;
                cursor: pointer !important;
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
            }
            
            .d64-accordion-header:hover {
                background: #E2E8F0 !important;
            }
            
            .d64-accordion-content {
                border: 1px solid #021D4C !important;
                border-top: none !important;
                border-radius: 0 0 8px 8px !important;
                padding: 16px !important;
                background: white !important;
            }
        </style>';
    });
}

// Only load admin styles in block editor
function d64_accordion_admin_init() {
    $screen = get_current_screen();
    if ($screen && $screen->is_block_editor()) {
        d64_accordion_admin_styles();
    }
}
add_action('current_screen', 'd64_accordion_admin_init');