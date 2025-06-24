<?php
/**
 * Map & Locations Functionality
 * 
 * Everything related to maps, geocoding, and location management
 * Quick edit file for map-related changes
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * MAP SETTINGS PAGE
 */
function register_map_settings_page() {
    add_options_page(
        'Map Location Settings', 
        'Map Locations', 
        'manage_options', 
        'map-locations', 
        'render_map_settings_page'
    );
}
add_action('admin_menu', 'register_map_settings_page');

function register_map_settings() {
    register_setting('map_locations_settings', 'map_locations_post_types');
}
add_action('admin_init', 'register_map_settings');

function render_map_settings_page() {
    $enabled_post_types = get_option('map_locations_post_types', array());
    ?>
    <div class="wrap">
        <h2>Map Location Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('map_locations_settings'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">Enable Location Data For:</th>
                    <td>
                        <?php
                        $post_types = get_post_types(['public' => true], 'objects');
                        foreach ($post_types as $post_type) {
                            $checked = in_array($post_type->name, $enabled_post_types) ? 'checked' : '';
                            ?>
                            <label style="display: block; margin-bottom: 10px;">
                                <input type="checkbox" 
                                       name="map_locations_post_types[]" 
                                       value="<?php echo esc_attr($post_type->name); ?>"
                                       <?php echo $checked; ?>>
                                <?php echo esc_html($post_type->label); ?>
                            </label>
                            <?php
                        }
                        ?>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

/**
 * LOCATION META BOXES
 */
function add_location_meta_boxes() {
    $enabled_post_types = get_option('map_locations_post_types', array());
    
    foreach ($enabled_post_types as $post_type) {
        add_meta_box(
            'location_data',
            'Location Data',
            'render_location_meta_box',
            $post_type,
            'side',
            'default'
        );
    }
}
add_action('add_meta_boxes', 'add_location_meta_boxes');

function render_location_meta_box($post) {
    wp_nonce_field('save_location_meta', 'location_meta_nonce');
    
    $latitude = get_post_meta($post->ID, 'latitude', true);
    $longitude = get_post_meta($post->ID, 'longitude', true);
    $address = get_post_meta($post->ID, 'address', true);
    ?>
    <p>
        <label>Address:</label><br>
        <input type="text" name="address" value="<?php echo esc_attr($address); ?>" class="widefat">
    </p>
    <p>
        <button type="button" class="button" id="geocode-address">
            Geocode Address
        </button>
    </p>
    <p>
        <label>Latitude:</label><br>
        <input type="text" name="latitude" value="<?php echo esc_attr($latitude); ?>" class="widefat">
    </p>
    <p>
        <label>Longitude:</label><br>
        <input type="text" name="longitude" value="<?php echo esc_attr($longitude); ?>" class="widefat">
    </p>

    <script>
    jQuery(document).ready(function($) {
        $('#geocode-address').on('click', function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var address = $('input[name="address"]').val();
            
            if (!address) {
                alert('Please enter an address first.');
                return;
            }

            $button.prop('disabled', true).text('Geocoding...');

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'geocode_address',
                    address: address,
                    nonce: '<?php echo wp_create_nonce('geocode_address_nonce'); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        $('input[name="latitude"]').val(response.data.latitude);
                        $('input[name="longitude"]').val(response.data.longitude);
                    } else {
                        alert('Geocoding failed: ' + response.data);
                    }
                },
                error: function() {
                    alert('An error occurred while geocoding the address.');
                },
                complete: function() {
                    $button.prop('disabled', false).text('Geocode Address');
                }
            });
        });
    });
    </script>
    <?php
}

/**
 * SAVE LOCATION META
 */
function save_location_meta($post_id) {
    if (!isset($_POST['location_meta_nonce']) || 
        !wp_verify_nonce($_POST['location_meta_nonce'], 'save_location_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    $fields = ['address', 'latitude', 'longitude'];
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }

    // Generate GeoJSON after saving
    generate_geojson(get_post_type($post_id));
}
add_action('save_post', 'save_location_meta');

/**
 * GEOCODING AJAX HANDLER
 */
function handle_geocode_address() {
    check_ajax_referer('geocode_address_nonce', 'nonce');

    if (!current_user_can('edit_posts')) {
        wp_send_json_error('Insufficient permissions');
    }

    $address = sanitize_text_field($_POST['address']);
    $url = 'https://nominatim.openstreetmap.org/search?q=' . urlencode($address) . '&format=json&limit=1';
    
    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
        wp_send_json_error('Geocoding failed');
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);

    if (empty($data)) {
        wp_send_json_error('No coordinates found');
    }

    wp_send_json_success([
        'latitude' => $data[0]['lat'],
        'longitude' => $data[0]['lon']
    ]);
}
add_action('wp_ajax_geocode_address', 'handle_geocode_address');

/**
 * GEOJSON GENERATION
 */
function generate_geojson($post_type) {
    $enabled_types = get_option('map_locations_post_types', array());
    
    if (!in_array($post_type, $enabled_types)) {
        return;
    }

    $query = new WP_Query([
        'post_type' => $post_type,
        'posts_per_page' => -1,
    ]);

    $features = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $latitude = get_post_meta(get_the_ID(), 'latitude', true);
            $longitude = get_post_meta(get_the_ID(), 'longitude', true);

            if ($latitude && $longitude) {
                $features[] = [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [(float)$longitude, (float)$latitude],
                    ],
                    'properties' => [
                        'title' => get_the_title(),
                        'description' => get_the_excerpt(),
                        'url' => get_permalink(),
                    ],
                ];
            }
        }
        wp_reset_postdata();
    }

    $geojson = [
        'type' => 'FeatureCollection',
        'features' => $features,
    ];

    $upload_dir = wp_upload_dir();
    $filename = $post_type . '_locations.geojson';
    $geojson_file = $upload_dir['basedir'] . '/' . $filename;
    file_put_contents($geojson_file, json_encode($geojson, JSON_PRETTY_PRINT));
}

/**
 * LOCATION MAP BLOCK
 */
function register_location_map_block() {
    register_block_type('d64/location-map', array(
        'render_callback' => 'render_location_map',
        'attributes' => array(
            'geojsonFile' => array(
                'type' => 'string',
                'default' => ''
            ),
            'zoom' => array(
                'type' => 'number',
                'default' => 12
            ),
            'address' => array(
                'type' => 'string',
                'default' => 'Berlin'
            )
        )
    ));
}
add_action('init', 'register_location_map_block');

/**
 * RENDER LOCATION MAP
 */
function render_location_map($attributes) {
    $zoom = isset($attributes['zoom']) ? $attributes['zoom'] : 12;
    $address = isset($attributes['address']) ? $attributes['address'] : 'Berlin';
    $post_type = isset($attributes['geojsonFile']) ? $attributes['geojsonFile'] : 'locations';
    
    $map_id = 'map-' . uniqid();
    
    $posts = get_posts([
        'post_type' => $post_type,
        'posts_per_page' => -1,
    ]);

    ob_start();
    ?>
    <div class="location-container pb-8">
        <!-- Tab Navigation -->
        <div class="flex mb-4 gap-1 mt-8">
            <button 
                class="bg-white border-2 border-d64blue-900 hover:bg-d64blue-50 rounded-full px-4 font-medium py-1 transition-colors tab-button active" 
                data-tab="map">
                Karte
            </button>
            <button 
                class="bg-white border-2 border-d64blue-50 hover:bg-d64blue-50 rounded-full px-4 font-medium py-1 transition-colors tab-button" 
                data-tab="list">
                Liste
            </button>
        </div>

        <!-- Map Tab -->
        <div class="tab-content" id="map-tab">
            <div class="map-wrapper" id="<?php echo esc_attr($map_id); ?>">
                <?php 
                echo do_shortcode(sprintf(
                    '[leaflet-map zoom="%d" address="%s" scrollwheel=1]',
                    esc_attr($zoom),
                    esc_attr($address)
                ));
                
                foreach ($posts as $post) {
                    $latitude = get_post_meta($post->ID, 'latitude', true);
                    $longitude = get_post_meta($post->ID, 'longitude', true);
                    $address = get_post_meta($post->ID, 'address', true);
                    $post_content = wp_strip_all_tags($post->post_content);
                    
                    if ($latitude && $longitude) {
                        $popup_content = sprintf(
                            '<div class="marker-popup cursor-pointer not-prose" data-title="%s" data-address="%s" data-content="%s">
                                <div class="flex flex-col">
                                <h4 class="font-semibold text-lg">%s</h4>
                                <p class="!my-1 font-medium">%s</p>
                                <p class="!my-1 pt-2">%s</p>
                                </div>
                            </div>',
                            esc_attr($post->post_title),
                            esc_attr($address),
                            esc_attr($post_content),
                            esc_html($post->post_title),
                            esc_html($address),
                            esc_html($post_content)
                        );
                        
                        echo do_shortcode(sprintf(
                            '[leaflet-marker lat="%s" lng="%s"]%s[/leaflet-marker]',
                            esc_attr($latitude),
                            esc_attr($longitude),
                            $popup_content
                        ));
                    }
                }
                ?>
            </div>
        </div>

        <!-- List Tab -->
        <div class="tab-content hidden" id="list-tab">
            <div class="h-[600px] overflow-y-auto">
                <?php foreach ($posts as $post) : 
                    $address = get_post_meta($post->ID, 'address', true);
                    ?>
                    <div class="location-item border-b border-gray-200 last:border-b-0">
                        <button class="map-accordion-header w-full text-left p-4 hover:bg-gray-50 transition-colors not-prose">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="font-semibold text-lg"><?php echo esc_html($post->post_title); ?></h3>
                                    <p class="text-gray-600"><?php echo esc_html($address); ?></p>
                                </div>
                                <svg class="map-accordion-icon w-5 h-5 transform transition-transform duration-200" 
                                     xmlns="http://www.w3.org/2000/svg" 
                                     viewBox="0 0 20 20" 
                                     fill="currentColor">
                                    <path fill-rule="evenodd" 
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" 
                                          clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                        <div class="map-accordion-content hidden px-4 pb-4 not-prose">
                            <div class="prose prose-sm max-w-none">
                                <?php echo apply_filters('the_content', $post->post_content); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Modal -->
        <div id="location-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 transition-opacity" style="backdrop-filter: blur(2px);">
            <div class="absolute inset-0 flex items-center justify-center p-4">
                <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <button id="close-modal" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    
                    <div class="p-6">
                        <h2 id="modal-title" class="text-2xl font-bold mb-4"></h2>
                        <p id="modal-address" class="text-gray-600 mb-4"></p>
                        <div id="modal-content" class="prose prose-sm max-w-none"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Map UI initialization
    window.initMapUI_<?php echo esc_js(str_replace('-', '_', $map_id)); ?> = function() {
        const mapElement = document.getElementById('<?php echo esc_js($map_id); ?>');
        if (!mapElement) return;
        
        const container = mapElement.closest('.location-container');
        if (!container) return;
        
        // Prevent duplicate initialization
        if (container.dataset.initialized === 'true') return;
        container.dataset.initialized = 'true';
        
        // Tab functionality
        const tabButtons = container.querySelectorAll('.tab-button');
        const tabContents = container.querySelectorAll('.tab-content');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                tabButtons.forEach(btn => {
                    btn.classList.remove('active', 'border-d64blue-900');
                    btn.classList.add('border-d64blue-50');
                });
                
                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });
                
                this.classList.add('active', 'border-d64blue-900');
                this.classList.remove('border-d64blue-50');
                
                const tabId = this.getAttribute('data-tab') + '-tab';
                const activeContent = container.querySelector('#' + tabId);
                if (activeContent) {
                    activeContent.classList.remove('hidden');
                }
            });
        });
        
        // Accordion functionality
        const accordionHeaders = container.querySelectorAll('.map-accordion-header');
        accordionHeaders.forEach(header => {
            header.addEventListener('click', function(e) {
                e.preventDefault();
                
                const content = this.nextElementSibling;
                const icon = this.querySelector('.map-accordion-icon');
                
                if (content) {
                    content.classList.toggle('hidden');
                    if (icon) {
                        icon.classList.toggle('rotate-180');
                    }
                }
            });
        });
        
        // Modal functionality
        const modal = document.getElementById('location-modal');
        const modalTitle = document.getElementById('modal-title');
        const modalContent = document.getElementById('modal-content');
        const modalAddress = document.getElementById('modal-address');
        const closeButton = document.getElementById('close-modal');
        
        function showModal(data) {
            modalTitle.textContent = data.title;
            modalContent.innerHTML = data.content;
            modalAddress.textContent = data.address;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function hideModal() {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
        
        if (closeButton) {
            closeButton.addEventListener('click', hideModal);
        }
        
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    hideModal();
                }
            });
        }
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                hideModal();
            }
        });
        
        // Handle marker clicks
        document.addEventListener('click', function(e) {
            const popup = e.target.closest('.marker-popup');
            if (popup) {
                showModal({
                    title: popup.dataset.title,
                    content: popup.dataset.content,
                    address: popup.dataset.address
                });
            }
        });
    };
    
    // Initialize only once, when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            window.initMapUI_<?php echo esc_js(str_replace('-', '_', $map_id)); ?>();
        });
    } else {
        // DOM already loaded
        window.initMapUI_<?php echo esc_js(str_replace('-', '_', $map_id)); ?>();
    }
    </script>
    <?php
    return ob_get_clean();
}