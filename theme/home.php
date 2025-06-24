<?php
/**
 * Template Name: Custom Home Page
 * 
 * Startseiten-Template für D64 - Zentrum für Digitalen Fortschritt
 * Zeigt Hero-Bereich, Events, Blog-Posts und About-Sektion
 * 
 * @package d64
 */

// Konstanten für wiederkehrende Werte
define('D64_EVENTS_PER_PAGE', 3);
define('D64_POSTS_PER_PAGE', 3);
define('D64_HERO_IMAGE_MAX_HEIGHT', 320);

get_header();
?>

<section id="primary" class="max-w-screen overflow-x-hidden relative overflow-clip">
    <!-- Hintergrundbild für Desktop -->
    <img class="absolute right-0 hidden max-xl:opacity-40 md:block lg:right-0 top-10 lg:-top-10 -z-10 h-[90vh] max-h-[900px] object-cover" 
         src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/d64-home-background.jpeg'); ?>" 
         alt="<?php esc_attr_e('D64 Hintergrundbild', 'd64'); ?>"
         loading="lazy">
    
    <main id="main" class="front-page max-w-[1200px] m-auto">
        
        <!-- Hero Section -->
        <?php 
        $hero_image_1 = get_field('bild-1');
        $hero_image_2 = get_field('bild-2');
        $fallback_image_1 = get_template_directory_uri() . '/assets/images/home-2.jpg';
        $fallback_image_2 = get_template_directory_uri() . '/assets/images/home-3.jpg';
        ?>
        
        <!-- Mobile Hero Image -->
        <div class="relative md:hidden">
            <div class="bg-gradient-to-b from-transparent to-d64blue-50 sm:to-white w-full h-20 absolute left-0 right-0 bottom-0"></div>
            <?php if ($hero_image_1) : ?>
                <?php 
                echo wp_get_attachment_image(
                    $hero_image_1['id'], 
                    'medium_large', // 768px WordPress size
                    false, 
                    [
                        'class' => 'w-full max-h-[' . D64_HERO_IMAGE_MAX_HEIGHT . 'px] object-cover aspect-video',
                        'loading' => 'eager',
                        'sizes' => '(max-width: 768px) 100vw, 768px',
                        'width' => '768',
                        'height' => '432'
                    ]
                );
                ?>
            <?php else : ?>
                <img class="w-full max-h-[<?php echo D64_HERO_IMAGE_MAX_HEIGHT; ?>px] object-cover aspect-video" 
                     src="<?php echo esc_url($fallback_image_1); ?>" 
                     alt="<?php esc_attr_e('D64 Hero Bild', 'd64'); ?>"
                     width="768" 
                     height="432"
                     loading="eager">
            <?php endif; ?>
        </div>
        
        <div class="py-0 sm:py-6 md:pb-12 sm:pt-0 lg:pb-16">
            <section class="pb-12 pt-8 sm:py-20 bg-d64blue-50 sm:bg-transparent" id="hero-content">
                <div class="flex flex-col gap-8 md:gap-10 px-4 max-w-[800px]">
                    <div class="prose prose-strong:text-d64blue-900 prose-h1:font-serif prose-img:my-0 lg:max-w-[880px] prose-h1:font-bold prose-ul:list-inside">
                        <h1 class="wp-block-heading"><?php esc_html_e('D64 ist das Zentrum für Digitalen Fortschritt', 'd64'); ?></h1>
                        
                        <!-- Hero Content mit Bildern -->
                        <div class="md:flex gap-8 items-center justify-center my-4">
                            <?php if ($hero_image_1) : ?>
                                <?php 
                                echo wp_get_attachment_image(
                                    $hero_image_1['id'], 
                                    'medium', // 300px WordPress size
                                    false, 
                                    [
                                        'class' => 'hidden md:flex max-w-[300px] aspect-video h-auto object-cover',
                                        'loading' => 'eager',
                                        'sizes' => '(min-width: 768px) 300px, 0px',
                                        'width' => '300',
                                        'height' => '169'
                                    ]
                                );
                                ?>
                            <?php else : ?>
                                <img class="hidden md:flex max-w-[300px] aspect-video h-auto object-cover" 
                                     src="<?php echo esc_url($fallback_image_1); ?>" 
                                     alt="<?php esc_attr_e('D64 Bild 1', 'd64'); ?>"
                                     width="300" 
                                     height="169"
                                     loading="eager">
                            <?php endif; ?>
                            <p><?php esc_html_e('D64 ist ein gemeinnütziger und unabhängiger Verein. Unsere über 800 Mitglieder begreifen die digitale Transformation als Chance, das Miteinander unserer modernen Gesellschaft zu verbessern. Wir gestalten in 13 Arbeitsgruppen die gesellschaftliche, ökologische, technologische und politische Entwicklung konstruktiv, kritisch und kreativ mit.', 'd64'); ?></p>
                        </div>
                        
                        <div class="md:flex gap-8 items-center justify-center">
                            <p><?php esc_html_e('Die Grundwerte Freiheit, Gerechtigkeit und Solidarität durch eine progressive Digitalpolitik zu verwirklichen, ist unser Ziel. Dafür wirken wir mit Hilfe der breitgefächerten Expertise unserer Mitglieder als unabhängiger Verein, der in allen Themenbereichen der Digitalisierung vordenkt und Impulse gibt.', 'd64'); ?></p>
                            <?php if ($hero_image_2) : ?>
                                <?php 
                                echo wp_get_attachment_image(
                                    $hero_image_2['id'], 
                                    'medium', // 300px WordPress size
                                    false, 
                                    [
                                        'class' => 'hidden md:flex max-w-[300px] aspect-video h-auto object-cover',
                                        'loading' => 'eager',
                                        'sizes' => '(min-width: 768px) 300px, 0px',
                                        'width' => '300',
                                        'height' => '169'
                                    ]
                                );
                                ?>
                            <?php else : ?>
                                <img class="hidden md:flex max-w-[300px] aspect-video h-auto object-cover" 
                                     src="<?php echo esc_url($fallback_image_2); ?>" 
                                     alt="<?php esc_attr_e('D64 Bild 2', 'd64'); ?>"
                                     width="300" 
                                     height="169"
                                     loading="eager">
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Hero Links (Button-ähnliche Links) -->
                    <?php if( have_rows('hero_links') ): ?>
                        <div class="flex flex-row flex-wrap gap-2">
                        <?php while( have_rows('hero_links') ): the_row(); 
                            $link = get_sub_field('hero_link');
                            if (!$link) continue;
                            
                            $url = esc_url($link['url']);
                            $title = esc_html($link['title']);
                            $target = $link['target'] ? esc_attr($link['target']) : '_self';
                            $color = get_sub_field('farbe');
                            
                            // CSS-Klassen basierend auf Farbe
                            $color_classes = '';
                            switch($color) {
                                case 'blau':
                                    $color_classes = 'bg-d64blue-900 text-white hover:text-d64blue-900 hover:bg-d64blue-500';
                                    break;
                                case 'hellblau':
                                    $color_classes = 'hover:bg-d64blue-900 hover:text-white text-d64blue-900 bg-d64blue-500';
                                    break;
                                case 'grau':
                                    $color_classes = 'hover:bg-d64blue-900 hover:text-white text-d64blue-900 bg-d64gray-500';
                                    break;
                                case 'hellgrau':
                                    $color_classes = 'hover:bg-d64blue-900 hover:text-white text-d64blue-900 bg-d64gray-100';
                                    break;
                                default:
                                    $color_classes = 'bg-d64blue-900 text-white hover:text-d64blue-900 hover:bg-d64blue-500';
                            }
                            ?>
                            <a href="<?php echo $url; ?>" 
                               target="<?php echo $target; ?>"
                               class="flex px-4 py-[10px] text-base rounded-full font-medium max-w-content transition-colors <?php echo esc_attr($color_classes); ?>">
                                <?php echo $title; ?>
                            </a>
                        <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>

        <!-- Events Section -->
        <?php
        // Prüfung ob The Events Calendar aktiv ist
        if (function_exists('tribe_get_events')) {
            // Featured Events laden (max. 3)
            $featured_events = tribe_get_events([
                'posts_per_page' => D64_EVENTS_PER_PAGE,
                'start_date' => date('Y-m-d H:i:s'),
                'orderby' => ['event_date' => 'ASC'],
                'post_status' => 'publish',
                'featured' => true
            ]);
            
            $featured_count = count($featured_events);
            
            // Restliche Slots mit regulären Events füllen
            $regular_events = [];
            if ($featured_count < D64_EVENTS_PER_PAGE) {
                $regular_events = tribe_get_events([
                    'posts_per_page' => D64_EVENTS_PER_PAGE - $featured_count,
                    'start_date' => date('Y-m-d H:i:s'),
                    'orderby' => ['event_date' => 'ASC'],
                    'post_status' => 'publish',
                    'featured' => false
                ]);
            }
            
            // Arrays kombinieren - Featured Events zuerst
            $events = array_merge($featured_events, $regular_events);
            
            // Events Section nur anzeigen wenn Events vorhanden
            if (!empty($events)) {
        ?>
        <section id="events-preview" class="pb-6 pt-10 px-4 sm:pb-6 md:pb-8 lg:py-10">
            <?php
            // Section Header für Events
            $args_header = [
                'tag' => 'h2',
                'text' => __('Veranstaltungen', 'd64'),
            ];
            get_template_part('template-parts/components/section-header', null, $args_header);
            ?>
            
            <!-- Event Grid -->
            <div id="events-grid">
            <?php
                global $post;
                foreach ($events as $post) {
                    setup_postdata($post);
                    get_template_part('template-parts/components/event-tile');
                }
                wp_reset_postdata();
            ?>
            </div>
            
            <?php
            // "Alle Events" Button nur anzeigen wenn mehr als 1 Event vorhanden
            $total_events = tribe_get_events([
                'posts_per_page' => -1,
                'start_date' => date('Y-m-d H:i:s'),
                'fields' => 'ids',
                'post_status' => 'publish'
            ]);
            
            if (count($total_events) > 1) :
            ?>
            <div class="py-6 sm:py-10 md:py-14">
                <a href="<?php echo esc_url(get_site_url() . '/veranstaltungen'); ?>"
                   class="text-lg font-medium sm:m-auto flex items-center gap-1 max-w-max px-4 py-2 rounded-xl hover:bg-d64gray-50 hover:gap-2 transition-all">
                    <span><?php esc_html_e('Alle Veranstaltungen anzeigen', 'd64'); ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right" aria-hidden="true">
                        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                    </svg>
                </a>
            </div>
            <?php else : ?>
                <div class="pb-8 md:p-12"></div>
            <?php endif; ?>
        </section>
        <?php
            } // Ende Events vorhanden Check
        } // Ende Events Calendar Check
        ?>

        <!-- Blog Section -->
        <section id="blog-preview" class="py-6 px-4 sm:py-6 md:py-8 lg:py-10">
            <?php
            // Aktuelle Blog Posts laden
            $recent_posts = get_posts([
                'posts_per_page' => D64_POSTS_PER_PAGE,
                'post_status' => 'publish'
            ]);
            wp_reset_postdata();
            
            // Section Header für Blog
            $args_header = [
                'tag' => 'h2',
                'text' => __('Inhalte', 'd64'),
            ];
            get_template_part('template-parts/components/section-header', null, $args_header);
            ?>

            <!-- Posts Grid -->
            <div class="grid gap-6 md:gap-4 md:grid-cols-3">
                <?php 
                foreach ($recent_posts as $post) : 
                    setup_postdata($post);
                    get_template_part('template-parts/components/post-tile');
                endforeach; 
                wp_reset_postdata(); 
                ?>
            </div>
            
            <!-- "Alle Posts" Button -->
            <div class="py-6 sm:py-10 md:py-14">
                <a href="<?php echo esc_url(get_site_url() . '/inhalte'); ?>"
                   class="text-lg font-medium sm:m-auto flex items-center gap-1 max-w-max px-4 py-2 rounded-xl hover:bg-d64gray-50 hover:gap-2 transition-all">
                    <span><?php esc_html_e('Alle Beiträge anzeigen', 'd64'); ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right" aria-hidden="true">
                        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </section>

        <!-- About Section -->
        <?php get_template_part('template-parts/components/scroll-cols'); ?>
        
    </main>
</section>

<?php get_footer(); ?>