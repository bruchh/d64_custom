<?php
/**
 * Working Groups Component
 *
 * Zeigt alle Arbeitsgruppen in einer strukturierten Liste.
 * Verwendet person-tile Component für einheitliche Darstellung.
 * 
 * Features:
 * - Automatische Abfrage aller Arbeitsgruppen
 * - Alphabetische Sortierung nach Titel
 * - Responsive Grid-Layout
 * - Fallback wenn keine Arbeitsgruppen vorhanden
 * - Performance-optimierte Query
 * 
 * Template Parts:
 * - components/section-header.php - Überschrift "Unsere Arbeitsgruppen"
 * - components/person-tile.php - Darstellung der einzelnen Arbeitsgruppen
 * 
 * Verwendung:
 * - Automatisch auf Arbeitsgruppen-Page eingebunden
 * - Kann auch auf anderen Seiten verwendet werden
 * - Responsive Darstellung für alle Bildschirmgrößen
 * 
 * ACF Integration:
 * - person-tile Component verarbeitet Arbeitsgruppen-spezifische Felder
 * - Flexible Field-Struktur je nach person-tile Implementation
 * 
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @package d64
 */

// Working Groups Query-Konfiguration
$working_groups_args = array(
    'post_type' => 'arbeitsgruppe',
    'post_status' => 'publish',
    'posts_per_page' => -1, // Alle Arbeitsgruppen
    'orderby' => 'title',
    'order' => 'ASC',
    'no_found_rows' => true, // Performance: keine Pagination
    'update_post_meta_cache' => true, // Meta-Cache für ACF-Felder
    'update_post_term_cache' => false // Keine Term-Cache (falls nicht benötigt)
);

// Arbeitsgruppen abfragen
$working_groups_query = new WP_Query($working_groups_args);
$groups_count = $working_groups_query->post_count;

// Post-Type existiert prüfen
$post_type_exists = post_type_exists('arbeitsgruppe');

if (!$post_type_exists) {
    // Error-Logging für Entwickler
    error_log('Working Groups Template: Post-Type "arbeitsgruppe" existiert nicht.');
}
?>

<!-- Working Groups Section -->
<section class="max-w-3xl m-auto py-10" 
         role="region"
         aria-labelledby="working-groups-heading">
    
    <!-- Section Header -->
    <?php 
    get_template_part('template-parts/components/section-header', null, [
        'text' => __('Unsere Arbeitsgruppen', 'd64'),
        'tag' => 'h2',
        'id' => 'working-groups-heading'
    ]); 
    ?>

    <!-- Working Groups Content -->
    <?php if (!$post_type_exists) : ?>
        
        <!-- Post-Type Error Fallback -->
        <div class="px-4 py-8 text-center" role="alert">
            <p class="text-red-600 font-medium mb-2">
                <?php esc_html_e('Konfigurationsfehler', 'd64'); ?>
            </p>
            <p class="text-gray-600">
                <?php esc_html_e('Der Post-Type "arbeitsgruppe" ist nicht verfügbar. Bitte wenden Sie sich an den Administrator.', 'd64'); ?>
            </p>
        </div>
        
    <?php elseif ($working_groups_query->have_posts()) : ?>
        
        <!-- Working Groups Grid -->
        <div class="flex flex-col gap-4 px-4" 
             role="list"
             aria-label="<?php echo esc_attr(sprintf(__('%d Arbeitsgruppen', 'd64'), $groups_count)); ?>">
            
            <?php 
            // Original post backup für saubere Wiederherstellung
            $original_post = isset($GLOBALS['post']) ? $GLOBALS['post'] : null;
            
            while ($working_groups_query->have_posts()) : 
                $working_groups_query->the_post(); 
            ?>
                <div role="listitem" class="working-group-item">
                    <?php 
                    get_template_part('template-parts/components/person-tile', null, [
                        'context' => 'arbeitsgruppe',
                        'post_type' => 'arbeitsgruppe',
                        'display_type' => 'working-group'
                    ]); 
                    ?>
                </div>
            <?php endwhile; ?>
            
            <!-- Screen Reader Info -->
            <div class="sr-only" aria-live="polite">
                <?php 
                echo esc_html(sprintf(
                    _n('%d Arbeitsgruppe verfügbar', '%d Arbeitsgruppen verfügbar', $groups_count, 'd64'),
                    $groups_count
                ));
                ?>
            </div>
        </div>
        
    <?php else : ?>
        
        <!-- No Working Groups Fallback -->
        <div class="px-4 py-8 text-center" role="status">
            <p class="text-lg text-gray-600 mb-4">
                <?php esc_html_e('Aktuell sind keine Arbeitsgruppen verfügbar.', 'd64'); ?>
            </p>
            <p class="text-sm text-gray-500">
                <?php esc_html_e('Neue Arbeitsgruppen werden regelmäßig gegründet. Schauen Sie gerne bald wieder vorbei!', 'd64'); ?>
            </p>
        </div>
        
    <?php endif; ?>
</section>

<?php
// Query-Daten und globalen Post-Zustand sicher zurücksetzen
wp_reset_postdata();

// Original post wiederherstellen falls manipuliert
if ($original_post) {
    $GLOBALS['post'] = $original_post;
}
