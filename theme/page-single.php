<?php
/**
 * Page Single Template
 *
 * Custom Page Template das das Single-Post-Layout für Seiten wiederverwendet.
 * Erstellt für Kunden-Anforderung: Blog-Template-Design auch für spezielle Seiten.
 * 
 * Verwendung:
 * - Seite erstellen und "Page Single" als Template auswählen
 * - Seite wird mit dem gleichen Layout wie Blog-Posts dargestellt
 * - Nutzt content-single.php Template Part für konsistentes Design
 * 
 * Features:
 * - Gleiche Darstellung wie Blog-Posts (Hero, Meta, Content)
 * - Responsive Layout und Typography
 * - Share-Buttons und Kommentare (falls aktiviert)
 * - SEO-optimierte Struktur
 * 
 * Template Part: template-parts/content/content-single.php
 * Basis: single.php Layout-Struktur
 * 
 * @package d64
 */

/* Template Name: Page Single */

get_header();
?>

<section id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <?php
        // Start WordPress Loop
        if (have_posts()) :
            while (have_posts()) :
                the_post();
                
                // Single-Post Template Part laden (wiederverwendet für Seiten)
                get_template_part('template-parts/content/content', 'single');
                
            endwhile;
        else :
        ?>
            <!-- Fallback falls Seite nicht existiert -->
            <article class="page no-results not-found">
                <header class="page-header">
                    <h1 class="page-title">
                        <?php esc_html_e('Seite nicht gefunden', 'd64'); ?>
                    </h1>
                </header><!-- .page-header -->
                
                <div class="page-content">
                    <p><?php esc_html_e('Die angeforderte Seite konnte nicht gefunden werden.', 'd64'); ?></p>
                    <a href="<?php echo esc_url(home_url('/')); ?>" 
                       class="button">
                        <?php esc_html_e('Zurück zur Startseite', 'd64'); ?>
                    </a>
                </div><!-- .page-content -->
            </article><!-- .page -->
        <?php
        endif;
        ?>
    </main><!-- #main -->
</section><!-- #primary -->

<?php
get_footer();