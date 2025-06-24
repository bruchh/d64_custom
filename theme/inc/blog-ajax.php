<?php
/**
 * Blog AJAX Functionality
 * 
 * Everything related to blog post filtering, search, and pagination
 * Quick edit file for blog-related changes
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * AJAX Blog Post Filter
 * Handles categories, authors, search, and pagination
 */
function fetch_filtered_posts() {
    $categories = isset($_POST['categories']) ? $_POST['categories'] : array();
    $authors = isset($_POST['authors']) ? $_POST['authors'] : array();
    $searchTerm = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 8,
        'paged' => $paged,
        's' => $searchTerm
    );

    // Filter by categories
    if (!empty($categories)) {
        $args['category__in'] = $categories;
    }

    // Filter by authors (ACF field)
    $meta_queries = array();
    if (!empty($authors)) {
        foreach ($authors as $author) {
            $meta_queries[] = array(
                'key' => 'author',
                'value' => '"' . $author . '"',
                'compare' => 'LIKE'
            );
        }
    }

    if (!empty($meta_queries)) {
        $args['meta_query'] = array('relation' => 'OR') + $meta_queries;
    }

    $query = new WP_Query($args);
    $output = array();

    ob_start();

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            get_template_part('template-parts/components/post-tile');
        endwhile;
        $output['content'] = ob_get_clean();

        // Pagination
        $output['pagination'] = paginate_links(array(
            'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
            'format' => '?paged=%#%',
            'current' => $paged,
            'total' => $query->max_num_pages,
            'type' => 'plain',
            'show_all' => false,
            'end_size' => 1,
            'mid_size' => 0,
            'prev_next' => true,
            'prev_text' => __('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>'),
            'next_text' => __('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>'),
            'add_args' => false,
            'add_fragment' => '',
        ));
    else :
        $page = get_page_by_path('inhalte');
        if ($page) :
            $no_content = get_field('no-content', $page->ID);
            $output['content'] = '<div class="place-center w-full flex flex-col gap-2 text-center">
                <div class="pb-2 ">
                    <span class="text-2xl w-10 rounded-full">🔍</span>
                </div>
                <p class="text-center">' . $no_content . '</p>
            </div>';
            $output['pagination'] = '';
        endif;
    endif;

    wp_reset_postdata();
    wp_send_json($output);
    die();
}
add_action('wp_ajax_fetch_filtered_posts', 'fetch_filtered_posts'); 
add_action('wp_ajax_nopriv_fetch_filtered_posts', 'fetch_filtered_posts');