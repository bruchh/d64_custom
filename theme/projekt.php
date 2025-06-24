<?php
/**
 * Projekt Page Template
 *
 * Spezielle Template für Projekt-Seiten mit individueller Farbgestaltung.
 * Zeigt Projektinformationen, Ansprechpartner, Links und Förderlogos.
 * 
 * Verwendung:
 * - Seite erstellen und "Projekt Page" als Template auswählen
 * - Hero-Inhalte direkt in der Seite eingeben (Titel, Content, Featured Image)
 * - Projekt-spezifische ACF-Felder konfigurieren
 * 
 * Layout-Struktur:
 * - Desktop: 2-spaltig (Content + Sidebar mit Logo/Ansprechpartner)
 * - Mobile: Gestapelt (Logo → Hero → Ansprechpartner → Content)
 * 
 * ACF Felder:
 * - 'main_color' (Color Picker) - Individuelle Projektfarbe
 * - 'logo' (Image) - Projekt-Logo
 * - 'ansprechpartner:in' (Relationship) - Verknüpfung zu Personen
 * - 'hero_links' (Repeater) - Call-to-Action Links
 *   |- 'hero_link' (Link) - Einzelner Link mit URL, Titel, Target
 * - 'forderer' (Gallery) - Förderlogos
 * - 'ueberschrift' (Text) - Custom Überschrift für Förderer-Sektion
 * 
 * Features:
 * - Dynamisches Color System (CSS-Custom-Properties)
 * - Responsive Logo-Platzierung
 * - Separate Ansprechpartner-Templates für Mobile/Desktop
 * - Förderlogos mit flexiblem Layout
 * - Custom Button-Links
 * 
 * Template Parts:
 * - components/ansprechpartner.php (mit $args)
 * 
 * @package d64
 */

/* Template Name: Projekt Page */

get_header();

// ACF-Daten laden und validieren
$main_color = get_field('main_color');
$main_color = $main_color ? $main_color : '#6B46C1'; // Fallback-Farbe
$ueberschrift = get_field('ueberschrift');
$ansprechpartner = get_field('ansprechpartner:in');
$logo = get_field('logo');
$foerderer = get_field('forderer');
$hero_links = get_field('hero_links');

// Validierung der Hauptfarbe (Security: nur Hex-Farben erlaubt)
if (!preg_match('/^#[a-fA-F0-9]{6}$/', $main_color)) {
    $main_color = '#6B46C1'; // Fallback bei ungültiger Farbe
}
?>

<section id="primary" class="bg-white">
    <main id="main" class="max-w-screen overflow-x-clip lg:max-w-[1200px] m-auto -mb-28 sm:-mt-[95px] pt-[95px] max-sm:pb-12 md:pt-[110px]" role="main">          
        
        <!-- Custom Projekt Page -->
        <div id="projekt" class="lg:grid lg:grid-cols-[1fr_352px] gap-4 lg:px-4 xl:px-0">
            
            <!-- Haupt-Content -->
            <div class="">
                <div class="">
                    
                    <!-- Hero Section -->
                    <section class="pb-12 pt-8 sm:py-20 bg-transparent" id="hero-content">
                        <div class="flex flex-col gap-8 md:gap-10 px-4 max-w-3xl">
                            
                            <!-- Mobile Logo -->
                            <?php if ($logo) : ?>
                                <div class="flex justify-start lg:hidden">
                                    <img src="<?php echo esc_url($logo['url']); ?>" 
                                         alt="<?php echo esc_attr($logo['alt'] ?: __('Projekt-Logo', 'd64')); ?>" 
                                         class="w-auto max-h-[120px] h-auto object-contain" 
                                         width="<?php echo esc_attr($logo['width']); ?>"
                                         height="<?php echo esc_attr($logo['height']); ?>" />
                                </div>
                            <?php endif; ?>

                            <!-- Projekt-Titel -->
                            <h1 class="text-xl md:text-2xl italic font-medium custom-color">
                                <?php echo esc_html(get_the_title()); ?>
                            </h1>

                            <!-- Featured Image -->
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="flex justify-center">
                                    <?php the_post_thumbnail('medium_large', [
                                        'class' => 'w-full h-auto ratio-video !rounded-none',
                                        'alt' => esc_attr(sprintf(__('Beitragsbild für %s', 'd64'), get_the_title()))
                                    ]); ?>
                                </div>
                            <?php endif; ?>

                            <!-- Mobile Ansprechpartner -->
                            <?php if ($ansprechpartner) : ?>
                                <div class="lg:hidden">
                                    <?php get_template_part('template-parts/components/ansprechpartner', null, [
                                        'ansprechpartner' => $ansprechpartner, 
                                        'main_color' => $main_color,
                                        'is_desktop' => false
                                    ]); ?>
                                </div>
                            <?php endif; ?>

                            <!-- Projekt-Content -->
                            <div class="prose prose-strong:text-d64blue-900 prose-h1:font-serif prose-h1:font-bold prose-ul:list-inside prose-headings:custom-color prose-img:!rounded-none">
                                <?php the_content(); ?>
                            </div>

                            <!-- Hero Links (Button-Style) -->
                            <?php if ($hero_links && have_rows('hero_links')) : ?>
                                <div class="flex flex-row flex-wrap gap-2" role="group" aria-label="<?php esc_attr_e('Projekt-Links', 'd64'); ?>">
                                <?php while (have_rows('hero_links')) : the_row(); 
                                    $link = get_sub_field('hero_link');
                                    if ($link && !empty($link['url'])) :
                                        $url = $link['url'];
                                        $title = $link['title'] ?: __('Link', 'd64');
                                        $target = $link['target'] ?: '_self';
                                ?>
                                    <a href="<?php echo esc_url($url); ?>" 
                                       target="<?php echo esc_attr($target); ?>"
                                       <?php if ($target === '_blank') : ?>
                                           rel="noopener noreferrer"
                                           aria-label="<?php echo esc_attr(sprintf(__('%s (öffnet in neuem Tab)', 'd64'), $title)); ?>"
                                       <?php endif; ?>
                                       class="flex px-4 py-[10px] text-base rounded-full font-medium max-w-content bg-d64blue-900 text-white hover:text-d64blue-900 hover:bg-d64blue-500 transition-colors">
                                        <?php echo esc_html($title); ?>
                                    </a>
                                <?php 
                                    endif;
                                endwhile; 
                                ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Förderlogos Section -->
                        <?php if ($foerderer) : ?>
                        <div class="py-8 px-4 sm:py-12 sm:bg-transparent max-w-screen overflow-clip">
                            <div class="flex flex-col gap-4 max-w-3xl">
                                <h2 class="text-xl md:text-2xl font-medium text-d64blue-900">
                                    <?php 
                                    if ($ueberschrift) {
                                        echo esc_html($ueberschrift);
                                    } else {
                                        esc_html_e('Gefördert von:', 'd64');
                                    } 
                                    ?>
                                </h2>
                                <div class="flex flex-wrap items-center gap-8 mt-4 max-w-[100vw-32px] overflow-clip" role="list" aria-label="<?php esc_attr_e('Förder-Organisationen', 'd64'); ?>">
                                    <?php foreach ($foerderer as $foerderer_logo) : ?>
                                        <div role="listitem">
                                            <img src="<?php echo esc_url($foerderer_logo['url']); ?>" 
                                                 alt="<?php echo esc_attr($foerderer_logo['alt'] ?: __('Förder-Logo', 'd64')); ?>" 
                                                 class="w-auto max-sm:max-w-[100vw-32px] flex max-w-[500px] max-h-[250px] object-contain"
                                                 width="<?php echo esc_attr($foerderer_logo['width']); ?>"
                                                 height="<?php echo esc_attr($foerderer_logo['height']); ?>" />
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </section>
                </div>
            </div>
            
            <!-- Desktop Sidebar -->
            <div class="mt-10 sm:mt-20 sm:max-w-[320px] flex flex-col gap-8">
            
                <!-- Desktop Logo -->
                <?php if ($logo) : ?>
                    <div class="flex justify-start max-lg:hidden">
                        <img src="<?php echo esc_url($logo['url']); ?>" 
                             alt="<?php echo esc_attr($logo['alt'] ?: __('Projekt-Logo', 'd64')); ?>" 
                             class="w-auto max-h-[160px] h-auto object-contain"
                             width="<?php echo esc_attr($logo['width']); ?>"
                             height="<?php echo esc_attr($logo['height']); ?>" />
                    </div>
                <?php endif; ?>
                
                <!-- Desktop Ansprechpartner -->
                <?php if ($ansprechpartner) : ?>     
                    <div class="ansprechperson-desktop hidden lg:block">
                        <?php get_template_part('template-parts/components/ansprechpartner', null, [
                            'ansprechpartner' => $ansprechpartner, 
                            'main_color' => $main_color,
                            'is_desktop' => true
                        ]); ?>
                    </div>               
                <?php endif; ?>
            </div>
        </div>
    </main>
</section>

<?php get_footer(); ?>

<!-- Custom Color System (sicher mit escapten Werten) -->
<style>
    #projekt h1,
    #projekt h2,
    #projekt h3,
    #projekt h4,
    #projekt h5 {
        font-family: 'Saira', sans-serif;
    }

    .custom-color {
        color: <?php echo esc_attr($main_color); ?>;
    }
    .custom-border {
        border-color: <?php echo esc_attr($main_color); ?>;
    }

    .prose h1, 
    .prose h2,
    .prose h3,
    .prose h4,
    .prose h5 {
        color: <?php echo esc_attr($main_color); ?> !important;
    }

    #projekt .wp-element-button {
        background-color: <?php echo esc_attr($main_color); ?> !important;
    }
</style>