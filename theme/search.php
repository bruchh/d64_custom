<?php
/**
 * Template für Suchergebnis-Seiten
 *
 * Fallback-Template falls die JavaScript-powered Suche nicht funktioniert.
 * Zeigt Suchergebnisse kategorisiert nach Post-Types (Posts, Pages, Personen).
 * 
 * Funktionalität:
 * - Separate Abfragen für jeden Post-Type
 * - Unterschiedliche Layouts je Post-Type:
 *   * Posts: Grid mit post-tile Component
 *   * Personen: Grid mit person-tile Component  
 *   * Pages: Einfache Liste mit Links
 * 
 * Post-Types:
 * - 'post' → "Blogbeiträge" (Grid-Layout)
 * - 'page' → "Seiten" (Listen-Layout)
 * - 'personen' → "Personen" (Grid-Layout)
 * 
 * Template Parts:
 * - components/post-tile.php (für Posts)
 * - components/person-tile.php (für Personen)
 * 
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 * @package d64
 */

get_header();

// Suchbegriff sicher laden
$search_query_string = get_search_query();
$has_any_results = false; // Track ob überhaupt Ergebnisse gefunden wurden
?>

<section id="primary">
    <main id="main" role="main">
        <div class="max-w-[1200px] m-auto px-4 pt-4 pb-16 flex flex-col gap-4">
            
            <!-- Search Results Header -->
            <header class="page-header">
                <?php
                printf(
                    /* translators: 1: search result title. 2: search term. */
                    '<h1 class="page-title pb-4 text-base font-regular">%1$s <span class="font-semibold">%2$s</span></h1>',
                    esc_html__('Suchbegriff:', 'd64'),
                    '<span class="search-term">' . esc_html($search_query_string) . '</span>'
                );
                ?>
            </header><!-- .page-header -->

            <?php
            // Post-Types für Suche definieren
            $post_types = array('post', 'page', 'personen');
            
            foreach ($post_types as $type) {
                
                // Query-Args für aktuellen Post-Type
                $query_args = array(
                    's' => $search_query_string,
                    'post_type' => $type,
                    'posts_per_page' => -1 // Alle Ergebnisse anzeigen
                );
                
                // Überschrift für Post-Type bestimmen
                $headline = '';
                switch ($type) {
                    case 'post':
                        $headline = __('Blogbeiträge', 'd64');
                        break;
                    case 'page':
                        $headline = __('Seiten', 'd64');
                        break;
                    case 'personen':
                        $headline = __('Personen', 'd64');
                        break;
                    default:
                        $headline = ucfirst($type);
                        break;
                }
                
                // Suche für aktuellen Post-Type ausführen
                $search_query = new WP_Query($query_args);
                
                if ($search_query->have_posts()) :
                    $has_any_results = true; // Mindestens ein Ergebnis gefunden
                    $result_count = $search_query->found_posts;
                ?>
                    
                    <!-- Post-Type Sektion -->
                    <div class="search-results-section" data-post-type="<?php echo esc_attr($type); ?>">
                        <h2 class="text-xl italic font-medium pb-4 md:pb-6">
                            <?php echo esc_html($headline); ?>
                            <span class="result-count text-sm font-normal text-gray-600">
                                (<?php echo esc_html(sprintf(_n('%d Ergebnis', '%d Ergebnisse', $result_count, 'd64'), $result_count)); ?>)
                            </span>
                        </h2>
                        
                        <?php
                        // Layout-Container basierend auf Post-Type
                        if ($type === 'personen' || $type === 'post') {
                            // Grid-Layout für Posts und Personen
                            echo '<div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-1" role="list" aria-label="' . esc_attr(sprintf(__('%s Suchergebnisse', 'd64'), $headline)) . '">';
                        } else if ($type === 'page') {
                            // Listen-Layout für Pages
                            echo '<ul class="flex flex-col gap-2" role="list" aria-label="' . esc_attr(sprintf(__('%s Suchergebnisse', 'd64'), $headline)) . '">';
                        }
                        
                        // Loop durch Suchergebnisse
                        while ($search_query->have_posts()) : 
                            $search_query->the_post();
                            
                            if ($type === 'post') {
                                // Posts mit post-tile Component
                                echo '<div role="listitem">';
                                get_template_part('template-parts/components/post-tile');
                                echo '</div>';
                                
                            } else if ($type === 'page') {
                                // Pages als einfache Links
                        ?>
                                <li role="listitem">
                                    <a href="<?php echo esc_url(get_permalink()); ?>"
                                       class="text-2xl font-medium hover:text-d64blue-500 transition-colors font-serif"
                                       aria-label="<?php echo esc_attr(sprintf(__('Zur Seite: %s', 'd64'), get_the_title())); ?>">
                                        <?php echo esc_html(get_the_title()); ?>
                                    </a>
                                </li>
                        <?php
                            } else if ($type === 'personen') {
                                // Personen mit person-tile Component
                                echo '<div role="listitem">';
                                get_template_part('template-parts/components/person-tile');
                                echo '</div>';
                            }
                            
                        endwhile;
                        
                        // Layout-Container schließen
                        if ($type === 'personen' || $type === 'post') {
                            echo '</div>';
                        } else if ($type === 'page') {
                            echo '</ul>';
                        }
                        ?>
                    </div>
                    
                <?php
                    wp_reset_postdata(); // Loop zurücksetzen
                endif;
            }
            
            // Falls gar keine Ergebnisse gefunden wurden
            if (!$has_any_results) {
                ?>
                <div class="no-results-section">
                    <h2 class="text-xl italic font-medium pb-4">
                        <?php esc_html_e('Keine Ergebnisse gefunden', 'd64'); ?>
                    </h2>
                    <p class="text-gray-600 mb-6">
                        <?php 
                        printf(
                            esc_html__('Für den Suchbegriff "%s" wurden keine Ergebnisse gefunden.', 'd64'),
                            '<strong>' . esc_html($search_query_string) . '</strong>'
                        ); 
                        ?>
                    </p>
                </div>
                <?php
            }
            ?>
        </div>
    </main><!-- #main -->
</section><!-- #primary -->

<?php
get_footer();