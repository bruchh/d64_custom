<?php
/**
 * Person Register Page Template
 *
 * Zeigt eine Übersichtsseite mit ausgewählten Personen an.
 * Personen werden über ACF Relationship Field 'personen' ausgewählt.
 * 
 * Verwendung:
 * - Seite erstellen und "Person Register Page" als Template auswählen
 * - Hero-Section über ACF konfigurieren (optional)
 * - Personen über ACF Relationship Field 'personen' auswählen
 * - Überschrift über ACF 'uberschrift' anpassen (optional)
 * 
 * Features:
 * - Hero Section mit ACF-Inhalten
 * - Grid-Layout für Personen-Kacheln (responsive)
 * - Flexible Überschrift (ACF oder Fallback)
 * - Wiederverwendbare Template Parts
 * 
 * Template Parts:
 * - components/hero.php (Hero Section)
 * - components/section-header.php (Überschriften)
 * - components/person-tile.php (Personen-Kacheln)
 * 
 * @package d64
 */

/* Template Name: Person Register Page */

get_header();

// Konstanten für Layout
define('D64_PERSON_REGISTER_MAX_WIDTH', 'max-w-[1200px]');
?>

<section id="primary" class="<?php echo esc_attr(D64_PERSON_REGISTER_MAX_WIDTH); ?> mx-auto">
    <main id="main" role="main">
        
        <!-- Hero Section -->
        <?php get_template_part('template-parts/components/hero'); ?>
        
        <!-- Personen Section -->
        <?php
        // Überschrift aus ACF oder Fallback
        $uberschrift = get_field('uberschrift');
        $headline = $uberschrift ? $uberschrift : __('Personen', 'd64');
        
        // Personen aus ACF Relationship Field laden
        $personen = get_field('personen');
        wp_reset_postdata();
        
        if ($personen && !empty($personen)) :
        ?>
        
        <section id="personen-preview" 
                 class="py-10 px-4 mx-auto <?php echo esc_attr(D64_PERSON_REGISTER_MAX_WIDTH); ?>"
                 aria-labelledby="personen-heading">
            
            <!-- Section Header -->
            <?php
            $args_header = [
                'tag' => 'h2',
                'text' => $headline,
                'id' => 'personen-heading'
            ];
            get_template_part('template-parts/components/section-header', null, $args_header);
            ?>
            
            <!-- Personen Grid -->
            <div class="grid gap-4 md:grid-cols-3" role="list" aria-label="<?php esc_attr_e('Personen-Übersicht', 'd64'); ?>">
                <?php 
                foreach ($personen as $post) : 
                    setup_postdata($post);
                ?>
                    <div role="listitem">
                        <?php get_template_part('template-parts/components/person-tile'); ?>
                    </div>
                <?php 
                endforeach; 
                wp_reset_postdata(); 
                ?>
            </div>
        </section>
        
        <?php 
        else : 
        ?>
        
        <!-- Fallback falls keine Personen ausgewählt -->
        <section class="py-10 px-4 text-center">
            <h2 class="text-xl font-medium mb-4">
                <?php esc_html_e('Keine Personen gefunden', 'd64'); ?>
            </h2>
            <p class="text-gray-600">
                <?php esc_html_e('Für diese Seite wurden noch keine Personen ausgewählt.', 'd64'); ?>
            </p>
        </section>
        
        <?php 
        endif; 
        ?>
        
    </main><!-- #main -->
</section><!-- #primary -->

<?php 
get_footer();