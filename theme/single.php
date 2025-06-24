<?php
/**
 * Single Post Template
 *
 * Template für die Anzeige einzelner Blog-Posts und anderer Post-Types.
 * Lädt den content-single Template Part, der die eigentliche Darstellung übernimmt.
 * 
 * Verwendung:
 * - Automatisch von WordPress für alle einzelnen Posts verwendet
 * - URL-Schema: example.com/post-name/
 * - Unterstützt alle Post-Types (posts, custom post types)
 * 
 * 
 * Template Parts:
 * - template-parts/content/content-single.php (Haupt-Layout)
 * 
 * Features:
 * - WordPress Loop mit Post-Daten
 * - Automatische Schema.org Integration durch Template Part
 * - Support für Job-Posts mit Bewerbungsformular
 * - Author-Integration über ACF
 * - Related Posts Section
 * 
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 * @package d64
 */

get_header();
?>

<section id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <?php
        /* Start WordPress Loop */
        while (have_posts()) :
            the_post();
            
            // Template Part für einzelne Posts laden
            get_template_part('template-parts/content/content', 'single');
            
        endwhile; // End WordPress Loop
        ?>
    </main><!-- #main -->
</section><!-- #primary -->

<?php
get_footer();