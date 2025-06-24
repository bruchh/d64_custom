<?php
/**
 * Template Part für einzelne Post-Anzeige
 *
 * Zeigt vollständige Beiträge mit Header, Content, Autor und verwandten Artikeln.
 * Unterstützt verschiedene Layouts für normale Posts vs Job-Posts.
 * 
 * Layout-Komponenten:
 * - Header: Titel, Datum, Kategorie, Excerpt
 * - Featured Image: Nur bei normalen Posts (nicht bei Jobs)
 * - Content: Hauptinhalt mit WordPress-Editor
 * - Autor: Person-Tile wenn ACF 'author' verknüpft
 * - Job-Formular: Contact Form 7 bei Job-Kategorie  
 * - Related Posts: 2 weitere Artikel (außer bei Jobs)
 * 
 * ACF Felder:
 * - 'author' (Relationship) - Verknüpfung zu Personen-Post
 * - 'mitarbeit' (im Personen-Post) - Arbeitsbereich-Info
 * 
 * Post Categories:
 * - 'Job' - Spezial-Layout mit Bewerbungsformular
 * - Alle anderen - Standard-Layout mit Related Posts
 * 
 * Template Parts:
 * - components/person-tile.php (für Autor)
 * - components/section-header.php (für "Auch Interessant")
 * - components/post-tile.php (für Related Posts)
 * 
 * Dependencies:
 * - Contact Form 7 Plugin (für Job-Bewerbungen)
 * - ACF (für Author-Verknüpfung)
 * - Custom d64_post_thumbnail() und d64_content_class() Funktionen
 * 
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @package d64
 */

// Post-Daten und ACF-Felder laden
$thumbnail_id = get_post_thumbnail_id();
$thumbnail_desc = '';
$linked_author = get_field('author');
$is_job_post = has_category('Job');
$has_thumbnail = has_post_thumbnail();

// Featured Image Beschreibung sicher laden
if ($thumbnail_id) {
    $thumbnail_post = get_post($thumbnail_id);
    if ($thumbnail_post && !empty($thumbnail_post->post_content)) {
        $thumbnail_desc = wp_strip_all_tags($thumbnail_post->post_content);
    }
}

// Header-Styling basierend auf Post-Type
$header_classes = 'entry-header md:pt-12 sm:pt-8 lg:pt-16 xl:pt-20 sm:pb-8 xl:pb-14 ';
if (!$has_thumbnail || $is_job_post) {
    $header_classes .= 'bg-d64blue-50 pt-4 pb-6 md:pb-12 lg:pb-16 xl:pb-20';
} else {
    $header_classes .= 'pb-4 md:pb-10 lg:pb-12';
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">

    <!-- Post Header -->
    <header class="<?php echo esc_attr($header_classes); ?>">
        <div class="max-w-3xl px-4 m-auto">
            
            <!-- Post Title -->
            <?php the_title('<h1 class="entry-title font-serif font-bold text-2xl sm:text-3xl md:text-4xl md:max-w-full lg:text-5xl">', '</h1>'); ?>

            <!-- Horizontal Divider -->
            <div class="hidden sm:block w-full h-[1px] md:h-[2px] bg-d64blue-900 sm:my-8 md:my-10 lg:my-12 xl:my-14" role="separator"></div>

            <!-- Meta Information and Excerpt -->
            <div class="flex flex-col-reverse pt-2 gap-2 sm:grid sm:grid-cols-3">

                <!-- Date and Category Meta -->
                <aside class="flex sm:flex-col gap-1 text-xs pt-2 sm:pt-1 text-gray-500 md:text-sm">
                    <div class="flex flex-wrap">
                        <!-- Post Date -->
                        <div class="mr-2">
                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                <?php echo esc_html(get_the_date()); ?>
                            </time>
                        </div>
                        
                        <!-- Post Category -->
                        <div class="mr-2 sm_hidden">
                            <?php 
                            $categories = get_the_category();
                            if (!empty($categories)) {
                                $category = $categories[0];
                                $category_name = esc_html($category->name);   
                                $category_id = esc_html($category->term_id);
                                $category_url = esc_url(home_url('/?category_id=' . $category_id));
                            ?>
                                <a class="font-medium break-words hover:underline" 
                                   href="<?php echo $category_url; ?>"
                                   aria-label="<?php echo esc_attr(sprintf(__('Alle Beiträge in %s anzeigen', 'd64'), $category_name)); ?>">
                                    <?php echo $category_name; ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                </aside>

                <!-- Post Excerpt -->
                <div class="text-md leading-loose font-medium sm:text-lg sm:col-span-2">
                    <?php 
                    $excerpt = wp_trim_words(get_the_excerpt(), 37, '...');
                    echo esc_html($excerpt); 
                    ?>
                </div>
            </div>
        </div>
    </header><!-- .entry-header -->

    <!-- Featured Image (nur bei normalen Posts) -->
    <div class="max-w-4xl m-auto">
        <?php if ($has_thumbnail && !$is_job_post) : ?>
            <div class="aspect-video relative rounded-xl sm:mx-4 sm:overflow-hidden">	
                <?php d64_post_thumbnail(); ?>
            </div>
            <?php if (!empty($thumbnail_desc)) : ?>
                <div class="bottom-0 right-0 left-0 w-full text-[#737373] text-sm font-regular text-end p-2 pt-1 pr-4">
                    <?php echo esc_html($thumbnail_desc); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Post Content -->
        <div class="px-4 <?php echo ($has_thumbnail && !$is_job_post) ? 'pt-4' : 'pt-6'; ?> sm:pt-8 md:pt-10 lg:pt-12 xl:pt-14 max-w-[1200px] m-auto">
            <div <?php d64_content_class('entry-content'); ?>>
                <?php
                the_content(
                    sprintf(
                        wp_kses(
                            /* translators: %s: Name of current post. Only visible to screen readers. */
                            __('Continue reading<span class="sr-only"> "%s"</span>', 'd64'),
                            array(
                                'span' => array(
                                    'class' => array(),
                                ),
                            )
                        ),
                        get_the_title()
                    )
                );

                wp_link_pages(
                    array(
                        'before' => '<div>' . esc_html__('Pages:', 'd64'),
                        'after'  => '</div>',
                    )
                );
                ?>
            </div><!-- .entry-content -->
        </div>
    </div>

    <!-- Author Section (falls ACF Author verknüpft) -->
    <?php if ($linked_author && !empty($linked_author)) : ?>
        <div id="author" 
             class="max-w-[640px] px-4 sm:px-0 m-auto pt-10 sm:pt-12 md:pt-14 lg:pt-16 xl:pt-20"
             role="complementary"
             aria-labelledby="author-heading">
            
            <!-- Hidden heading for screen readers -->
            <h2 id="author-heading" class="sr-only">
                <?php esc_html_e('Über den Autor', 'd64'); ?>
            </h2>
            
            <?php
            // Autor-Post-Daten für person-tile verfügbar machen
            $author_post = $linked_author[0];
            $original_post = $post; // Current post backup
            $post = $author_post; // Switch to author post
            setup_postdata($post);
            
            // Mitarbeit-Daten für Template Part verfügbar machen
            $author_mitarbeit = get_field('mitarbeit');
            
            // Person-Tile Template laden
            get_template_part('template-parts/components/person-tile', null, [
                'context' => 'post-author',
                'mitarbeit' => $author_mitarbeit,
                'author_id' => get_the_ID()
            ]);
            
            // Original post wiederherstellen
            $post = $original_post;
            wp_reset_postdata();
            ?>
        </div>
    <?php endif; ?>

    <!-- Job Application Form (nur bei Job-Posts) -->
    <?php if ($is_job_post) : ?>
        <div class="max-w-[640px] m-auto pt-10 sm:pt-12 md:pt-14 lg:pt-16 xl:pt-20">
            <div class="p-4 sm:rounded-xl bg-d64blue-50 flex flex-col gap-6">
                <h3 class="text-lg font-bold font-serif">
                    <?php esc_html_e('Bewirb dich direkt hier bei D-64', 'd64'); ?>
                </h3>
                <div class="w-full">
                    <?php
                    // Contact Form 7 Shortcode (sicher ausgeben)
                    $contact_form_shortcode = '[contact-form-7 id="b2f67e1" title="Job Bewerbung"]';
                    echo do_shortcode($contact_form_shortcode);
                    ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Related Posts Section (nicht bei Job-Posts) -->
    <?php if (!$is_job_post) : ?>
        <footer class="max-w-3xl m-auto" role="contentinfo">
            
            <!-- Horizontal Divider -->
            <div class="block w-full h-[1px] md:h-[2px] bg-d64blue-900 my-16 lg:my-20 xl:my-24" role="separator"></div>

            <!-- Section Header -->
            <?php 
            $header_args = [
                'tag' => 'h3',
                'text' => __('Auch interessant', 'd64'),
            ];
            get_template_part('template-parts/components/section-header', null, $header_args);
            ?>

            <!-- Related Posts Query -->
            <?php
            $related_args = array(
                'posts_per_page' => 2,
                'post__not_in' => array(get_the_ID()),
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC'
            );
            $related_posts = get_posts($related_args);
            
            if ($related_posts) :
            ?>
                <!-- Related Posts Grid -->
                <div class="grid gap-4 px-4 sm:px-0" 
                     role="list" 
                     aria-label="<?php esc_attr_e('Verwandte Artikel', 'd64'); ?>">
                    <?php 
                    foreach ($related_posts as $post) : 
                        setup_postdata($post); 
                    ?>
                        <div role="listitem">
                            <?php get_template_part('template-parts/components/post-tile'); ?>
                        </div>
                    <?php 
                    endforeach; 
                    wp_reset_postdata(); 
                    ?>
                </div>
            <?php 
            else : 
            ?>
                <p class="px-4 sm:px-0 text-gray-600">
                    <?php esc_html_e('Keine verwandten Artikel gefunden.', 'd64'); ?>
                </p>
            <?php 
            endif; 
            ?>
        </footer>
    <?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->

<!-- Custom Styles -->
<style>
blockquote {
    padding-left: 1rem !important;
}
</style>