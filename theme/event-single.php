<?php
/**
 * Template für einzelne Event-Seiten
 *
 * Zeigt detaillierte Informationen zu einem Event mit:
 * - Event-Header mit Titel und Excerpt
 * - Event-Bild mit Bildbeschreibung
 * - Event-Details (Datum, Zeit, Ort, Anmeldung)
 * - Event-Beschreibung (Content)
 * - RSVP/Ticket-Integration
 * 
 * Requires: The Events Calendar Plugin
 * 
 * Features:
 * - Schema.org Structured Data für Events
 * - Responsive Layout (Mobile-first)
 * - Accessibility-optimiert
 * - RSVP/Ticket Integration
 * - Venue/Location Details
 * 
 * @package d64
 */

/* Template Name: Single Event Page */

get_header();

// Event ID für weitere Verwendung
$event_id = get_the_ID();

// Konstanten für Layout
define('D64_EVENT_MAX_WIDTH_CONTENT', 'max-w-2xl');
define('D64_EVENT_MAX_WIDTH_HERO', 'max-w-4xl');
?>

<div class="tribe-events-single" itemscope itemtype="https://schema.org/Event">
    <article id="event-article">
        <!-- Event Header -->
        <header class="entry-header pt-4 sm:pt-8 md:pt-12 lg:pt-16 xl:pt-20 md:pb-2 lg:pb-4 xl:pb-6">
            <div class="<?php echo esc_attr(D64_EVENT_MAX_WIDTH_CONTENT); ?> px-4 m-auto">
                <!-- Event Title -->
                <h1 class="font-serif !font-bold !text-xl" itemprop="name">
                    <?php echo esc_html(get_the_title()); ?>
                </h1>

                <!-- Event Excerpt/Summary -->
                <div class="flex">
                    <div class="text-md leading-loose font-medium sm:text-lg sm:col-span-2" itemprop="description">
                        <?php 
                        $excerpt = get_the_excerpt();
                        echo $excerpt ? esc_html($excerpt) : ''; 
                        ?>
                    </div>
                </div>
            </div>
        </header>

        <!-- Hero Section: Event Image + Details -->
        <div class="m-auto bg-d64blue-50">
            <div class="flex flex-col-reverse sm:py-8 sm:grid sm:grid-cols-2 <?php echo esc_attr(D64_EVENT_MAX_WIDTH_HERO); ?> md:gap-8 m-auto">

                <!-- Event Featured Image -->
                <?php if (has_post_thumbnail()) : ?>
                    <div class="aspect-video sm:aspect-auto sm:mx-4 sm:overflow-hidden sm:object-fill relative">    
                        <?php 
                        $image_id = get_post_thumbnail_id();
                        $image_post = get_post($image_id);
                        $image_description = $image_post ? $image_post->post_content : '';
                        
                        echo get_the_post_thumbnail($event_id, 'medium-16-9', array(
                            'class' => 'h-auto aspect-video @lg:max-h-[320px] @3xl:max-h-[400px] w-full object-cover rounded-xl',
                            'alt' => esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', true) ?: get_the_title()),
                            'itemprop' => 'image'
                        ));

                        // Bildbeschreibung falls vorhanden
                        if (!empty($image_description)) {
                            echo '<div class="absolute bottom-0 right-0 max-w-max rounded-tl bg-slate-300 bg-opacity-80 text-slate-800 text-xs font-medium text-end px-1 py-[1px]">';
                            echo esc_html($image_description);
                            echo '</div>';
                        }
                        ?>
                    </div>
                <?php endif; ?>

                <!-- Event Details Sidebar -->
                <aside class="py-8 px-4 sm:py-4 bg-d64blue-50 flex flex-col gap-8 sm:gap-10 md:gap-12 text-d64blue-900" 
                       role="complementary" 
                       aria-label="<?php esc_attr_e('Event Details', 'd64'); ?>">
                    
                    <!-- Datum und Zeit -->
                    <?php if (tribe_get_start_date()) : ?>
                    <div class="grid grid-cols-2" itemprop="startDate" content="<?php echo esc_attr(tribe_get_start_date(null, false, 'Y-m-d\TH:i')); ?>">
                        <span class="text-sm font-semibold pt-[1px]">
                            <?php esc_html_e('wann', 'd64'); ?>
                        </span>
                        <div class="flex flex-col gap-1">
                            <!-- Event Datum -->
                            <time class="font-medium text-xl" datetime="<?php echo esc_attr(tribe_get_start_date(null, false, 'Y-m-d')); ?>">            
                                <?php echo esc_html(tribe_get_start_date(null, false, 'j. F Y')); ?>
                            </time>
                            <!-- Event Uhrzeit -->
                            <?php if (tribe_get_start_time()) : ?>
                            <time class="font-medium text-xl" datetime="<?php echo esc_attr(tribe_get_start_date(null, false, 'H:i')); ?>">
                                <?php echo esc_html(tribe_get_start_time() . ' ' . __('Uhr', 'd64')); ?>
                            </time>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Event Location/Venue -->
                    <?php if (tribe_get_venue() || tribe_get_address()) : ?>
                    <div class="grid grid-cols-2" itemprop="location" itemscope itemtype="https://schema.org/Place">
                        <span class="text-sm font-semibold pt-[1px]">
                            <?php esc_html_e('wo', 'd64'); ?>
                        </span>
                        <div class="flex flex-col">
                            <!-- Venue Name -->
                            <?php if (tribe_get_venue()) : ?>
                            <span class="font-bold text-xl pb-1" itemprop="name">
                                <?php echo esc_html(tribe_get_venue()); ?>
                            </span>
                            <?php endif; ?>
                            
                            <!-- Venue Address -->
                            <div itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                                <?php if (tribe_get_address()) : ?>
                                <span class="font-medium text-base" itemprop="streetAddress">    
                                    <?php echo esc_html(tribe_get_address()); ?>
                                </span>
                                <?php endif; ?>
                                
                                <?php if (tribe_get_city()) : ?>
                                <span class="font-medium text-base" itemprop="addressLocality">
                                    <?php echo esc_html(tribe_get_city()); ?>
                                </span>
                                <?php endif; ?>
                                
                                <?php if (tribe_get_region()) : ?>
                                <span class="font-medium text-base" itemprop="addressRegion">
                                    <?php echo esc_html(tribe_get_region()); ?> 
                                </span>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Venue Website -->
                            <?php if (tribe_get_venue_website_url()) : ?>
                                <a href="<?php echo esc_url(tribe_get_venue_website_url()); ?>" 
                                   class="font-medium pt-0 !text-d64blue-900 text-base hover:underline"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   itemprop="url"
                                   aria-label="<?php echo esc_attr(sprintf(__('Website von %s (öffnet in neuem Tab)', 'd64'), tribe_get_venue())); ?>">
                                    <?php echo esc_html(tribe_get_venue_website_link_label() ?: __('Website', 'd64')); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- RSVP/Anmeldung -->
                    <?php if (tribe_get_cost() || tribe_is_event_rsvp() || tribe_events_get_ticket_event()) : ?>
                        <div class="grid grid-cols-2">
                            <span class="text-sm font-semibold pt-[1px]">
                                <?php esc_html_e('anmelden', 'd64'); ?>
                            </span>
                            <div class="flex flex-col">
                                <!-- Preis anzeigen falls vorhanden -->
                                <?php if (tribe_get_cost()) : ?>
                                <div class="font-medium text-base pb-2" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                                    <span itemprop="price"><?php echo esc_html(tribe_get_cost()); ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <a href="#rsvp" 
                                   id="veranstaltung-anmeldung-button"
                                   class="bg-d64blue-500 hover:bg-d64blue-600 text-white font-medium py-2 px-4 rounded-full text-center transition-colors"
                                   aria-describedby="rsvp-section">
                                    <?php esc_html_e('Jetzt anmelden', 'd64'); ?>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </aside>  
            </div>
        </div>

        <!-- Event Description/Content -->
        <div class="desc px-4 pt-4 sm:pt-8 md:pt-10 lg:pt-12 xl:pt-14 <?php echo esc_attr(D64_EVENT_MAX_WIDTH_CONTENT); ?> m-auto">
            <div <?php d64_content_class('entry-content'); ?> itemprop="description">
                <?php 
                if (have_posts()) :
                    while (have_posts()) : the_post();
                        the_content();
                    endwhile;
                endif;
                ?>
            </div>
        </div>
    </article>
                    
    <!-- Event RSVP/Tickets Section -->
    <?php if (tribe_get_cost() || tribe_is_event_rsvp() || tribe_events_get_ticket_event()) : ?>
        <section class="sm:px-4 <?php echo esc_attr(D64_EVENT_MAX_WIDTH_CONTENT); ?> m-auto" aria-labelledby="rsvp-heading">
            <div id="rsvp" 
                 class="prose !prose-h3:text-lg font-serif px-4 pb-8 sm:mx-auto pt-8 mt-16 prose-button:bg-red-500 bg-d64blue-50 booking sm:rounded-xl"
                 role="region"
                 aria-describedby="rsvp-section">
                <h2 id="rsvp-heading" class="sr-only">
                    <?php esc_html_e('Event Anmeldung', 'd64'); ?>
                </h2>
                <div id="rsvp-section">
                    <?php tribe_get_template_part('modules/meta/details'); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
</div>

<?php get_footer(); ?>