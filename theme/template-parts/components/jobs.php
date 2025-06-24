<?php
/**
 * Jobs Section Component
 *
 * Zeigt alle veröffentlichten Stellenausschreibungen aus der "Jobs" Kategorie.
 * Verwendet post-tile Component für einheitliche Post-Darstellung.
 * 
 * Features:
 * - Automatische Abfrage aller Job-Posts
 * - Grid-Layout für responsive Darstellung
 * - Fallback-Message wenn keine Jobs verfügbar
 * - Section-Header mit konfigurierbarem Tag
 * 
 * Post-Struktur:
 * - Post-Type: 'post' (Standard WordPress Posts)
 * - Kategorie: 'jobs' (muss existieren)
 * - Status: 'publish' (nur veröffentlichte Posts)
 * - Sortierung: Standard WordPress (neueste zuerst)
 * 
 * ACF Felder (optional):
 * - 'no-content' - Custom Fallback-Text wenn keine Jobs vorhanden
 * 
 * Template Parts:
 * - components/section-header.php - Konfigurierbarer Überschrift
 * - components/post-tile.php - Einheitliche Post-Darstellung
 * 
 * Verwendung:
 * - Automatisch auf Jobs-Page eingebunden
 * - Kann auch auf anderen Seiten verwendet werden
 * - Responsive Grid-Layout für alle Bildschirmgrößen
 * 
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @package d64
 */

// Jobs Query-Konfiguration
$jobs_query_args = array(
    'post_type' => 'post',
    'category_name' => 'jobs',
    'posts_per_page' => -1, // Alle Jobs anzeigen
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC',
    'no_found_rows' => true, // Performance: keine Pagination-Daten
    'update_post_meta_cache' => false // Performance: Meta-Cache nur wenn nötig
);

// Jobs abfragen
$jobs_query = new WP_Query($jobs_query_args);
$jobs_count = $jobs_query->found_posts;

// Fallback-Text für "keine Jobs"
$no_jobs_message = get_field('no-content');
if (empty($no_jobs_message)) {
    $no_jobs_message = __('Aktuell sind keine offenen Stellen verfügbar. Schauen Sie gerne in Kürze wieder vorbei!', 'd64');
}
?>

<!-- Jobs Section -->
<section class="px-4 sm:px-0 max-w-3xl py-10 m-auto" 
         role="region"
         aria-labelledby="jobs-section-header">
    
    <div class="m-auto">
        
        <!-- Section Header -->
        <?php 
        get_template_part('template-parts/components/section-header', null, [
            'tag' => 'h2',
            'text' => __('Offene Stellen', 'd64'),
            'id' => 'jobs-section-header'
        ]); 
        ?>

        <!-- Jobs Grid -->
        <?php if ($jobs_query->have_posts()) : ?>
            <div class="grid grid-cols-1 gap-4" 
                 role="list" 
                 aria-label="<?php echo esc_attr(sprintf(__('%d offene Stellenausschreibungen', 'd64'), $jobs_count)); ?>">
                
                <?php while ($jobs_query->have_posts()) : $jobs_query->the_post(); ?>
                    <div role="listitem">
                        <?php get_template_part('template-parts/components/post-tile', null, [
                            'context' => 'jobs-listing',
                            'show_category' => false // Jobs-Kategorie nicht extra anzeigen
                        ]); ?>
                    </div>
                <?php endwhile; ?>
            </div>
            
            <!-- Screen Reader Info -->
            <div class="sr-only" aria-live="polite">
                <?php 
                echo esc_html(sprintf(
                    _n('%d Stellenausschreibung gefunden', '%d Stellenausschreibungen gefunden', $jobs_count, 'd64'),
                    $jobs_count
                ));
                ?>
            </div>
            
        <?php else : ?>
            
            <!-- No Jobs Fallback -->
            <div class="text-center py-8" role="status">
                <p class="text-lg text-gray-600 mb-4">
                    <?php echo esc_html($no_jobs_message); ?>
                </p>
                <p class="text-sm text-gray-500">
                    <?php esc_html_e('Folgen Sie uns auf Social Media, um über neue Stellenausschreibungen informiert zu werden.', 'd64'); ?>
                </p>
            </div>
            
        <?php endif; ?>
        
    </div>
</section>

<?php
// Query-Daten zurücksetzen (wichtig für weitere Loops)
wp_reset_postdata();