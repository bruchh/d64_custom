<?php
/**
 * Scroll Columns Component
 *
 * Erstellt ein horizontales Layout mit sticky Überschrift und scrollbaren Listen.
 * Perfekt für Aufzählungen, Features, oder strukturierte Inhalte.
 * 
 * Layout-Struktur:
 * - Links: Sticky Überschrift mit Pfeil-Icon (bleibt beim Scrollen sichtbar)
 * - Rechts: Liste mit Inhalten (scrollbar)
 * - Responsive: Mobile = 2:3 Spalten, Desktop = 1:2 Spalten
 * 
 * ACF Struktur:
 * - 'inhalte' (Repeater) - Hauptcontainer für mehrere Sektionen
 *   - 'uberschrift' (Text) - Sticky Titel für die Sektion
 *   - 'list' (Repeater) - Liste der Items
 *     - 'item' (Textarea/Text) - Einzelner Listeneintrag
 * 
 * Features:
 * - Sticky Positioning für bessere UX beim Scrollen
 * 
 * 
 * @package d64
 */

// ACF-Daten prüfen und laden
if (!have_rows('inhalte')) {
    return; // Komponente nicht anzeigen wenn keine Daten
}
?>

<!-- Scroll Columns Section -->
<section class="max-w-3xl m-auto px-4 py-10" 
         role="region"
         aria-label="<?php esc_attr_e('Strukturierte Inhalte', 'd64'); ?>">
    
    <?php while (have_rows('inhalte')) : the_row(); ?>
        <?php
        // Sektion-Daten laden
        $section_title = get_sub_field('uberschrift');
        $has_list_items = have_rows('list');
        
        // Skip wenn keine Überschrift
        if (empty($section_title)) {
            continue;
        }
        
        // Unique ID für ARIA-Labeling
        $section_id = 'scroll-section-' . sanitize_title($section_title);
        ?>
        
        <div class="grid grid-cols-5 sm:grid-cols-3 w-full relative" 
             role="group"
             aria-labelledby="<?php echo esc_attr($section_id); ?>">
            
            <!-- Sticky Title Column -->
            <div class="h-full col-span-2 sm:col-span-1">
                <h2 id="<?php echo esc_attr($section_id); ?>" 
                    class="transition-opacity font-serif font-bold sticky top-[50vh] flex items-center flex-row gap-[2px] sm:gap-1 text-sm sm:text-base text-medium text-d64blue-900 border-2 border-d64blue-900 w-fit px-2 sm:px-4 py-1 rounded-full">
                    
                    <span><?php echo esc_html($section_title); ?></span>
                    
                    <!-- Arrow Icon (responsive sizing) -->
                    <span class="hidden sm:block" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" 
                             width="24" height="24" 
                             viewBox="0 0 24 24" 
                             fill="none" 
                             stroke="currentColor" 
                             stroke-width="2" 
                             stroke-linecap="round" 
                             stroke-linejoin="round" 
                             class="lucide lucide-arrow-right">
                            <path d="M5 12h14"/>
                            <path d="m12 5 7 7-7 7"/>
                        </svg>
                    </span>
                    
                    <span class="block sm:hidden" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" 
                             width="16" height="16" 
                             viewBox="0 0 24 24" 
                             fill="none" 
                             stroke="currentColor" 
                             stroke-width="2" 
                             stroke-linecap="round" 
                             stroke-linejoin="round" 
                             class="lucide lucide-arrow-right">
                            <path d="M5 12h14"/>
                            <path d="m12 5 7 7-7 7"/>
                        </svg>
                    </span>
                </h2>
            </div>

            <!-- Content List Column -->
            <?php if ($has_list_items) : ?>
                <ul class="flex flex-col gap-4 col-start-3 col-span-3 sm:col-span-2 sm:col-start-2 pb-10"
                    role="list"
                    aria-labelledby="<?php echo esc_attr($section_id); ?>">
                    
                    <?php while (have_rows('list')) : the_row(); ?>
                        <?php 
                        $list_item = get_sub_field('item');
                        
                        // Skip leere Items
                        if (empty($list_item)) {
                            continue;
                        }
                        ?>
                        <li class="sm:text-lg font-medium" role="listitem">
                            <?php echo wp_kses_post($list_item); ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
                
            <?php else : ?>
                <!-- Fallback wenn keine Liste vorhanden -->
                <div class="col-start-3 col-span-3 sm:col-span-2 sm:col-start-2 pb-10">
                    <p class="text-gray-500 italic">
                        <?php esc_html_e('Keine Inhalte verfügbar.', 'd64'); ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
        
    <?php endwhile; ?>
</section>

<?php