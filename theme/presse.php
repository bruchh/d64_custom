<?php
/**
 * Presse Page Template
 *
 * Spezielle Seiten-Template für Presse-/Medien-Informationen.
 * Kombiniert Hero-Section, Ansprechpartner und Tab-basierte Inhalte.
 * 
 * Verwendung:
 * - Seite erstellen und "Presse Page" als Template auswählen
 * - Hero-Section über ACF konfigurieren
 * - Ansprechpartner über ACF Relationship Field 'ansprechpartner' auswählen
 * - Tab-Inhalte über ACF Repeater Field 'tab' erstellen
 * 
 * Layout-Struktur:
 * - Desktop: 2-spaltig (Content + Sidebar mit Ansprechpartner)
 * - Mobile: Gestapelt (Hero → Ansprechpartner → Tabs)
 * 
 * ACF Felder:
 * - 'ansprechpartner' (Relationship) - Verknüpfung zu Personen-Posts
 * - 'tab' (Repeater) - Tab-basierte Inhalte mit Label + Content
 * 
 * Template Parts:
 * - components/hero.php (Hero Section)
 * - components/person-tile.php (Ansprechpartner)
 * - components/tabs.php (Tab-Navigation und Inhalte)
 * 
 * @package d64
 */

/* Template Name: Presse Page */

get_header();

// ACF Daten laden
$ansprechpartner = get_field('ansprechpartner');
?>

<main id="main" class="max-w-[1200px] m-auto pb-20">
    
    <!-- Hero Section -->
    <div class="lg:grid lg:grid-cols-[1fr,320px] gap-4 lg:px-4 xl:px-0">
        <div class="">
            <div class="">
                <?php get_template_part('template-parts/components/hero'); ?>
            </div>
            
            <!-- Ansprechpartner Section -->
            <?php if ($ansprechpartner) : ?>
            <section id="ansprechperson mobile" class="block pb-10 pt-10 sm:pt-0 lg:max-w-[320px] w-full m-auto max-w-3xl px-4 lg:pt-20">
                <h2 class="text-lg sm:text-xl italic m-auto font-medium pb-8">
                    <?php esc_html_e('Ansprechperson', 'd64'); ?>
                </h2>
                <div>
                    <?php 
                    foreach ($ansprechpartner as $post) : 
                        setup_postdata($post); 
                    ?>
                        <?php get_template_part('template-parts/components/person-tile'); ?>
                    <?php 
                    endforeach; 
                    wp_reset_postdata(); 
                    ?>
                </div>
            </section>
            <?php endif; ?>
            
            <!-- Tabs -->
            <!-- <div class="bg-transparent lg:bg-d64blue-50 lg:rounded-xl">
                <?php if (get_field('tab')) : ?>
                    <?php get_template_part('template-parts/components/tabs'); ?>
                <?php endif; ?>
            </div> -->
        </div>
    </div>
</main>

<?php 
get_footer();