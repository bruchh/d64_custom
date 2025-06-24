<?php
/**
 * Map Page Template
 *
 * Spezielle Seiten-Template für Karten-Darstellungen.
 * Zeigt Seiten-Inhalt mit Custom Blocks für Leaflet-Karten.
 * 
 * Verwendung:
 * - Seite erstellen und "Map Page" als Template auswählen
 * - Leaflet Custom Block im Gutenberg Editor einfügen
 * - Block wird automatisch über the_content() gerendert
 * 
 * Features:
 * - Clean Layout optimiert für Karten-Anzeige
 * - Responsive Content-Container
 * - Accessibility-optimiert
 * 
 * Dependencies: Leaflet Custom Block Plugin
 * 
 * @package d64
 */

/* Template Name: Map Page */

get_header();

// Konstanten für Layout
define('D64_MAP_MAX_WIDTH', 'max-w-screen-md');
?>

<main class="container mx-auto px-4 py-8" role="main">
    <?php
    if (have_posts()) :
        while (have_posts()) :
            the_post();
    ?>
    
    <!-- Seiten-Header -->
    <header class="<?php echo esc_attr(D64_MAP_MAX_WIDTH); ?> mx-auto mb-6">
        <h1 class="text-xl mx-auto sm:mx-auto md:text-2xl italic sm:text-center font-medium">
            <?php echo esc_html(get_the_title()); ?>
        </h1>
    </header>
    
    <!-- Haupt-Content mit Karten-Integration -->
    <div class="prose <?php echo esc_attr(D64_MAP_MAX_WIDTH); ?> mx-auto prose-strong:text-d64blue-900 prose-h1:font-serif prose-h1:font-bold prose-ul:list-inside mt-4" 
         role="region" 
         aria-label="<?php esc_attr_e('Karten-Inhalt', 'd64'); ?>">
        
        <!-- WordPress Content (Leaflet Shortcodes/Blocks werden hier gerendert) -->
        <div <?php d64_content_class('entry-content'); ?>>
            <?php 
            the_content();
            
            // Page Links für mehrseitige Inhalte
            wp_link_pages(array(
                'before' => '<div class="page-links">' . esc_html__('Seiten:', 'd64'),
                'after'  => '</div>',
            ));
            ?>
        </div>
    </div>
    
    <?php
        endwhile;
    else :
    ?>
    
    <!-- Fallback falls keine Posts vorhanden -->
    <div class="<?php echo esc_attr(D64_MAP_MAX_WIDTH); ?> mx-auto text-center py-8">
        <h1 class="text-xl font-medium mb-4">
            <?php esc_html_e('Seite nicht gefunden', 'd64'); ?>
        </h1>
        <p class="text-gray-600">
            <?php esc_html_e('Der angeforderte Karten-Inhalt konnte nicht geladen werden.', 'd64'); ?>
        </p>
        <a href="<?php echo esc_url(home_url('/')); ?>" 
           class="inline-block mt-4 bg-d64blue-500 text-white px-4 py-2 rounded hover:bg-d64blue-600 transition-colors">
            <?php esc_html_e('Zurück zur Startseite', 'd64'); ?>
        </a>
    </div>
    
    <?php
    endif;
    ?>
</main>

<?php
get_footer();