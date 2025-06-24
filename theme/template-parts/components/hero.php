<?php
/**
 * Hero Section Component
 *
 * Flexible Hero-Section mit Titel, Content und Call-to-Action Buttons.
 * Unterstützt verschiedene Layouts für Homepage vs andere Seiten.
 * 
 * Features:
 * - Responsive Layout (Mobile vs Desktop)
 * - Conditional Centering für bestimmte Seiten
 * - ACF Hero-Links mit verschiedenen Button-Farben
 * - Spezielle Icon-Bar für Kontakt-Seite
 * - Homepage-spezifisches Layout ohne Titel
 * 
 * ACF Felder:
 * - 'hero_links' (Repeater)
 *   - 'hero_link' (Link) - URL, Titel, Target
 *   - 'farbe' (Select) - Button-Farbe: blau, hellblau, grau, hellgrau
 * 
 * Seiten-spezifische Layouts:
 * - Homepage: Kein H1-Titel, spezielle Container-Breite
 * - Kontakt: Zusätzliche Icon-Navigation
 * - Presse/Impressum/etc.: Links-ausgerichteter Content
 * - Standard: Zentrierter Content mit Titel
 * 
 * Dependencies:
 * - ACF Plugin (für hero_links)
 * - template-parts/components/nav-icon-bar.php (für Kontakt-Seite)
 * 
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @package d64
 */

// Page-Kontext und Layout-Flags bestimmen
$page_slug = get_post_field('post_name', get_the_ID());
$is_front_page = is_front_page();
$is_contact_page = ($page_slug === 'kontakt' || is_page('kontakt'));

// Seiten mit links-ausgerichtetem Content (nicht zentriert)
$left_aligned_pages = [
    'presse',
    'impressum', 
    'datenschutz',
    'barrierefreiheit',
    'datenschutzerklaerung'
];
$content_not_centered = $is_front_page || in_array($page_slug, $left_aligned_pages, true);

// Button-Farbschema Mapping
$button_color_classes = [
    'blau' => 'bg-d64blue-900 text-white hover:text-d64blue-900 hover:bg-d64blue-500',
    'hellblau' => 'hover:bg-d64blue-900 hover:text-white text-d64blue-900 bg-d64blue-500',
    'grau' => 'hover:bg-d64blue-900 hover:text-white text-d64blue-900 bg-d64gray-500',
    'hellgrau' => 'hover:bg-d64blue-900 hover:text-white text-d64blue-900 bg-d64gray-100'
];
?>

<!-- Hero Section -->
<section class="pb-12 pt-8 sm:py-20 bg-d64blue-50 sm:bg-transparent" 
         id="hero-content" 
         role="banner"
         aria-label="<?php esc_attr_e('Hero-Bereich', 'd64'); ?>">
    
    <div class="flex flex-col gap-8 md:gap-10 px-4 max-w-3xl <?php echo !$is_front_page ? 'm-auto' : ''; ?>">
        
        <!-- Page Title (außer Homepage) -->
        <?php if (!$is_front_page) : ?>
            <h1 class="text-xl md:text-2xl italic font-medium <?php echo !$content_not_centered ? 'sm:text-center align-middle' : ''; ?>">
                <?php the_title(); ?>
            </h1>
        <?php endif; ?>

        <!-- Main Content -->
        <div class="prose prose-strong:text-d64blue-900 prose-h1:font-serif prose-h1:font-bold prose-ul:list-inside">
            <?php the_content(); ?>
        </div>

        <!-- Contact Page Icon Navigation -->
        <?php if ($is_contact_page) : ?>
            <div class="flex sm:justify-center" role="navigation" aria-label="<?php esc_attr_e('Social Media Links', 'd64'); ?>">
                <?php get_template_part('template-parts/components/nav-icon-bar'); ?>
            </div>
        <?php endif; ?>

        <!-- Hero Action Buttons (ACF) -->
        <?php if (have_rows('hero_links')) : ?>
            <div class="flex flex-row flex-wrap gap-2" role="group" aria-label="<?php esc_attr_e('Haupt-Aktionen', 'd64'); ?>">
                <?php 
                while (have_rows('hero_links')) : 
                    the_row();
                    
                    // ACF Link-Feld sicher laden
                    $link = get_sub_field('hero_link');
                    $color = get_sub_field('farbe');
                    
                    // Validation: Link muss existieren
                    if (!$link || empty($link['url']) || empty($link['title'])) {
                        continue;
                    }
                    
                    // Link-Daten sicher extrahieren
                    $url = esc_url($link['url']);
                    $title = esc_html($link['title']);
                    $target = !empty($link['target']) ? esc_attr($link['target']) : '_self';
                    
                    // Button-Farbe bestimmen (mit Fallback)
                    $color_classes = isset($button_color_classes[$color]) 
                        ? $button_color_classes[$color] 
                        : $button_color_classes['blau']; // Default fallback
                    
                    // Button-spezifische ARIA-Attribute
                    $aria_label = sprintf(
                        __('%s (öffnet %s)', 'd64'), 
                        $title,
                        $target === '_blank' ? __('in neuem Fenster', 'd64') : __('in aktuellem Fenster', 'd64')
                    );
                ?>
                    <a href="<?php echo $url; ?>"
                       target="<?php echo $target; ?>"
                       <?php echo $target === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>
                       class="flex px-4 py-[10px] text-base rounded-full font-medium max-w-content transition-colors <?php echo esc_attr($color_classes); ?>"
                       aria-label="<?php echo esc_attr($aria_label); ?>">
                        <?php echo $title; ?>
                    </a>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
        
    </div>
</section>

<?php
