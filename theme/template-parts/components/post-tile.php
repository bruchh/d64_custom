<?php
/**
 * Template Part für Post Preview Kacheln
 *
 * Zeigt eine Post-Vorschau mit Bild, Titel, Excerpt und Metadaten.
 * Wird verwendet in Blog-Übersichten, Widgets und Archive-Seiten.
 * 
 * Features:
 * - Responsive Design mit Container Queries (@md breakpoints)
 * - Job-Posts erhalten "Jetzt Bewerben" Badge
 * - Verknüpfte Autoren aus ACF 'author' Field
 * - Hover-Effekte und Transitions
 * - Accessibility-optimiert
 * - SEO-freundlich mit Schema.org
 * 
 * ACF Felder:
 * - 'author' (Relationship) - Verknüpfung zu Personen-Posts
 * 
 * Layout:
 * - Mobile: Bild oben, Content unten
 * - Desktop: Grid 1:2 (Bild links, Content rechts)
 * 
 * @package d64
 */

// Original Post sichern für ACF Author-Abfrage
$original_post = $post;
$post_id = get_the_ID();
$post_title = get_the_title();
$post_permalink = get_permalink();
$post_excerpt = get_the_excerpt();

// Prüfen ob Post in Jobs-Kategorie ist
$is_job_post = has_category('Jobs');
?>

<div class="@container h-full">
    <article class="link-tile post-tile group h-full hover:bg-d64blue-50 rounded-xl overflow-hidden cursor-pointer flex flex-col relative transition @md:grid @md:grid-cols-3 @md:gap-4 @md:p-2"
             itemscope 
             itemtype="https://schema.org/BlogPosting">
        
        <!-- Job Badge für Job-Posts -->
        <?php if ($is_job_post) : ?>
        <div class="absolute top-2 left-2 @md:top-3 @md:left-3 bg-d64blue-900 text-white px-2 py-1 rounded-xl text-xs font-medium z-10"
             aria-label="<?php esc_attr_e('Stellenausschreibung', 'd64'); ?>">
            <?php esc_html_e('Jetzt Bewerben', 'd64'); ?>
        </div>
        <?php endif; ?>
        
        <!-- Post Featured Image -->
        <div class="post-tile__image relative rounded-xl overflow-hidden group-hover:rounded-none aspect-video transition-all @md:group-hover:rounded-xl">
            <?php 
            if (has_post_thumbnail()) {
                the_post_thumbnail('small-16-9', array(
                    'alt' => esc_attr(sprintf(__('Beitragsbild für: %s', 'd64'), $post_title)),
                    'itemprop' => 'image'
                ));
            } else {
                // Placeholder falls kein Bild vorhanden
                echo '<div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-500 text-sm">';
                echo esc_html__('Kein Bild verfügbar', 'd64');
                echo '</div>';
            }
            ?>
        </div>
        
        <!-- Post Content -->
        <div class="post-tile__content px-2 pt-4 pb-14 @md:pt-0 @md:col-span-2">
            <div>
                
                <!-- Post Meta (Datum & Autor) -->
                <div class="post-tile__data hidden @md:flex @md:gap-2 @md:pb-2">
                    <!-- Publikationsdatum -->
                    <time class="post-tile__date text-xs font-medium text-d64blue-900" 
                          datetime="<?php echo esc_attr(get_the_date('c')); ?>"
                          itemprop="datePublished">
                        <?php echo esc_html(get_the_date()); ?>
                    </time>
                    
                    <!-- Verknüpfter Autor aus ACF -->
                    <?php
                    $linked_author = get_field('author');
                    if ($linked_author && !empty($linked_author)) :
                        $author_post = $linked_author[0]; // Erstes Element aus Relationship Array
                        if ($author_post && isset($author_post->ID)) :
                    ?>
                    <span class="text-xs text-d64blue-900 font-medium" itemprop="author" itemscope itemtype="https://schema.org/Person">
                        <span itemprop="name"><?php echo esc_html(get_the_title($author_post->ID)); ?></span>
                    </span>
                    <?php 
                        endif;
                    endif; 
                    ?>
                </div>
                
                <!-- Post Title -->
                <h3 class="post-tile__title pb-3">
                    <a href="<?php echo esc_url($post_permalink); ?>" 
                       class="text-lg md:text-2xl font-serif font-bold group-hover:underline transition-all"
                       aria-label="<?php echo esc_attr(sprintf(__('Beitrag lesen: %s', 'd64'), $post_title)); ?>"
                       itemprop="url">
                        <span itemprop="headline"><?php echo esc_html($post_title); ?></span>
                    </a>
                </h3>
                
                <!-- Post Excerpt -->
                <div class="post-tile__excerpt" itemprop="description">
                    <?php 
                    $excerpt = wp_trim_words($post_excerpt, 36, '...');
                    echo esc_html($excerpt);
                    ?>
                </div>
            </div>
            
            <!-- Read More Call-to-Action -->
            <div class="absolute bottom-4 right-4 flex gap-1 group-hover:gap-2 items-center transition-all">
                <span class="post-tile__read-more font-medium">
                    <?php esc_html_e('weiterlesen', 'd64'); ?>
                </span>
                <svg xmlns="http://www.w3.org/2000/svg" 
                     width="20" 
                     height="20" 
                     viewBox="0 0 24 24" 
                     fill="none" 
                     stroke="currentColor" 
                     stroke-width="2" 
                     stroke-linecap="round" 
                     stroke-linejoin="round" 
                     class="lucide lucide-arrow-right"
                     aria-hidden="true"
                     role="img">
                    <title><?php esc_html_e('Pfeil nach rechts', 'd64'); ?></title>
                    <path d="M5 12h14"/>
                    <path d="m12 5 7 7-7 7"/>
                </svg>
            </div>
        </div>
        
        <!-- Schema.org zusätzliche Daten (versteckt) -->
        <div style="display: none;">
            <span itemprop="mainEntityOfPage" content="<?php echo esc_url($post_permalink); ?>"></span>
            <span itemprop="dateModified" content="<?php echo esc_attr(get_the_modified_date('c')); ?>"></span>
            <div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
                <span itemprop="name"><?php echo esc_html(get_bloginfo('name')); ?></span>
            </div>
        </div>
    </article>
</div>