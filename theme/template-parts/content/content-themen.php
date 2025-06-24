<?php
/**
 * Content Template für Themen-Übersichtsseite
 *
 * Zeigt alle Blog-Posts mit erweiterten Filter- und Suchoptionen.
 * Verwendet AJAX für Performance-optimierte Filterung ohne Page Reload.
 * 
 * Features:
 * - Kategorie-Filter basierend auf WordPress Kategorien
 * - Autoren-Filter basierend auf ACF 'author' Feld
 * - Echtzeit-Textsuche mit Debouncing (1s delay)
 * - AJAX-Pagination ohne Page Reload
 * - URL-Parameter für shareable Filter-Links
 * - Browser Back/Forward Support
 * - Responsive Toggle für Mobile Filter-Menü
 * 
 * JavaScript Dependencies:
 * - jQuery (für AJAX-Calls)
 * - my_ajax_object.ajax_url (WordPress AJAX URL)
 * - fetch_filtered_posts AJAX Action
 * 
 * @package d64
 */

// Kategorien für Filter laden
$categories = get_categories();

// Autoren aus ACF 'author' Feld extrahieren
$posts_with_authors = get_posts(array(
    'post_type' => 'post',
    'numberposts' => -1,
    'meta_query' => array(
        array(
            'key' => 'author',
            'compare' => 'EXISTS'
        )
    )
));

$unique_authors = [];

// Alle Autoren aus Posts sammeln und deduplizieren
foreach ($posts_with_authors as $post) {
    $author_ids = get_field('author', $post->ID);

    // Handle ACF Array oder einzelnes Objekt
    if (is_array($author_ids) && !empty($author_ids)) {
        foreach ($author_ids as $author) {
            if (isset($author->ID) && isset($author->post_title) && !array_key_exists($author->ID, $unique_authors)) {
                $unique_authors[$author->ID] = $author->post_title;
            }
        }
    } elseif (isset($author_ids->ID) && isset($author_ids->post_title) && !array_key_exists($author_ids->ID, $unique_authors)) {
        $unique_authors[$author_ids->ID] = $author_ids->post_title;
    }
}

// Autoren alphabetisch sortieren
uasort($unique_authors, function($a, $b) {
    return strcmp($a, $b);
});
?>

<!-- Header mit Toggle für Mobile Filter -->
<div class="flex flex-row justify-between items-center pb-4 pt-4 md:pt-12 lg:pt-20">
    <h1 class="italic font-medium text-lg sm:text-xl md:text-2xl">
        <?php esc_html_e('Inhalte', 'd64'); ?>
    </h1>
    
    <!-- Mobile Filter Toggle Button -->
    <div class="flex flex-row justify-end lg:hidden">
        <button 
            id="toggle-filter-menu" 
            class="bg-d64blue-50 text-sm text-d64blue-900 font-medium rounded-full py-1 px-4 border-2 border-d64blue-50 hover:underline" 
            aria-label="<?php esc_attr_e('Filter ein-/ausblenden', 'd64'); ?>"
            aria-expanded="false"
            aria-controls="filter-menu">
            <?php esc_html_e('Suche & Filter', 'd64'); ?>
        </button>
    </div>
</div>
            
<!-- Hauptlayout: Posts + Sidebar -->
<div class="flex flex-col-reverse gap-4 lg:gap-8 lg:grid lg:grid-cols-3">
    
    <!-- Posts Container (AJAX-geladen) -->
    <main class="lg:col-span-2" role="main">
        <?php
        // Initial Query für erste Seitenladung
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => 10, 
            'post_status' => 'publish',
            'paged' => get_query_var('paged')
        );
        $query = new WP_Query($args);
        
        if ($query->have_posts()) : ?>
            <!-- Posts Container (wird via AJAX aktualisiert) -->
            <div id="posts-container" 
                 aria-live="polite" 
                 aria-label="<?php esc_attr_e('Blog-Posts Liste', 'd64'); ?>"
                 class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-1 gap-6 sm:gap-8">
                <!-- Posts werden hier via AJAX geladen -->
            </div>
            
        <?php else : ?>
            <p><?php esc_html_e('Sorry, no posts matched your criteria.', 'd64'); ?></p>
        <?php endif; ?>

        <!-- AJAX Pagination Container -->
        <div class="pagination pb-12 sm:pb-0 flex gap-0 sm:gap-2" 
             id="pagination-container" 
             aria-label="<?php esc_attr_e('Seiten-Navigation', 'd64'); ?>">
            <!-- Pagination wird hier via AJAX geladen -->
        </div>
        
        <?php wp_reset_postdata(); ?>
    </main>

    <!-- Filter Sidebar -->
    <aside class="h-full" role="complementary" aria-label="<?php esc_attr_e('Filter und Suche', 'd64'); ?>">
        
        <!-- Skip Links für bessere Accessibility -->
        <?php 
        $args_skip_link = [
            'url' => '#posts-container',
            'text' => __('Zu den Posts springen', 'd64'),
        ];
        get_template_part('template-parts/components/skip-link', null, $args_skip_link);

        $args_skip_link = [
            'url' => '#author-selection',
            'text' => __('Zur Autoren Auswahl springen', 'd64'),
        ];
        get_template_part('template-parts/components/skip-link', null, $args_skip_link);
        ?>

        <!-- Filter Menu (versteckt auf mobile, sticky auf desktop) -->
        <div id="filter-menu" 
             role="region" 
             aria-label="<?php esc_attr_e('Filter Menü', 'd64'); ?>"
             class="filter-buttons hidden lg:flex p-4 rounded-xl bg-d64blue-50 lg:sticky lg:top-36">
            <div class="flex flex-col gap-7 mb-8 w-full">
            
                <!-- Text-Suche -->
                <div id="text-search-container">
                    <h4 class="pb-3 text-sm font-medium italic">
                        <?php esc_html_e('Suche', 'd64'); ?>
                    </h4>
                    <label for="text-search-input" class="sr-only">
                        <?php esc_html_e('Suchbegriff eingeben', 'd64'); ?>
                    </label>
                    <input type="text" 
                           id="text-search-input" 
                           class="rounded-full bg-white text-sm font-medium py-2 px-3 w-full" 
                           placeholder="<?php esc_attr_e('Themen durchsuchen...', 'd64'); ?>"
                           aria-describedby="search-description">
                    <div id="search-description" class="sr-only">
                        <?php esc_html_e('Suche wird automatisch nach 1 Sekunde ausgeführt', 'd64'); ?>
                    </div>
                </div>

                <!-- Kategorie Filter -->
                <?php if (!empty($categories)) : ?>
                <div id="filter">
                    <h4 class="pb-3 text-sm font-medium italic">
                        <?php esc_html_e('Kategorien', 'd64'); ?>
                    </h4>
                    <div class="category-filters flex flex-wrap gap-1" role="group" aria-label="<?php esc_attr_e('Kategorie Filter', 'd64'); ?>">
                        <?php 
                        $current_category_id = (is_category()) ? get_queried_object_id() : null;
                        foreach ($categories as $category) {
                            $is_active = ($current_category_id == $category->term_id) ? 'active border-d64blue-900' : 'border-d64blue-50';
                            ?>
                            <button class="filter-button text-sm bg-white font-medium rounded-full py-1 px-2 border-2 border-white hover:underline <?php echo esc_attr($is_active); ?>" 
                                    data-filter-type="category" 
                                    data-id="<?php echo esc_attr($category->term_id); ?>"
                                    role="button"
                                    aria-pressed="<?php echo $current_category_id == $category->term_id ? 'true' : 'false'; ?>"
                                    aria-label="<?php echo esc_attr(sprintf(__('Filter nach Kategorie %s', 'd64'), $category->name)); ?>">
                                <?php echo esc_html($category->name); ?>
                            </button>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Autoren Filter -->
                <?php if (!empty($unique_authors)) : ?>
                <div id="author-selection">
                    <h4 class="pb-3 text-sm font-medium italic">
                        <?php esc_html_e('Autor*innen', 'd64'); ?>
                    </h4>
                    <div class="author-filters flex flex-wrap gap-1" role="group" aria-label="<?php esc_attr_e('Autoren Filter', 'd64'); ?>">
                        <?php foreach ($unique_authors as $id => $title): ?>
                            <button 
                                class="filter-button text-sm bg-white font-medium rounded-full py-1 px-2 border-2 border-white hover:underline"
                                data-id="<?php echo esc_attr($id); ?>"
                                data-filter-type="author"
                                role="button"
                                aria-pressed="false"
                                aria-label="<?php echo esc_attr(sprintf(__('Filter Beiträge von %s', 'd64'), $title)); ?>">
                                <?php echo esc_html($title); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </aside>
</div>

<style>
/* Pagination Styling */
#pagination-container {
    margin-top: 2rem;
}

.page-numbers {
    transition: all .2s ease-in-out;    
    border-radius: .5rem;
    padding: .25rem .75rem;
}

.page-numbers:hover {
    background: rgb(214 222 226);
}

.page-numbers.current {
    font-weight: 700;
}
</style>