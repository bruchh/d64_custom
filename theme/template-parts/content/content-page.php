<?php
/**
 * Template Part für Page-Anzeige
 *
 * Modulares Template für verschiedene Seiten-Layouts mit bedingten Komponenten.
 * Lädt verschiedene Template Parts basierend auf Seiten-Kontext und ACF-Feldern.
 * 
 * Verwendung:
 * - Automatisch für alle WordPress Pages (außer Custom Page Templates)
 * - Modularer Aufbau ermöglicht flexible Seiten-Gestaltung
 * - Spezielle Layouts für bestimmte Seiten (Jobs, Arbeitsgruppen, etc.)
 * 
 * Seiten-spezifische Features:
 * - "Arbeitsgruppen" → working-groups Component
 * - "Jobs" → jobs Component mit Stellenausschreibungen
 * - "Mitglied werden" → Mitgliedsantrag Contact Form
 * - "Gremien" → gremien Component
 * - Alle Seiten → Hero, Tabs, Timeline (conditional), Scroll-Cols
 * 
 * ACF-Felder (conditional):
 * - 'timeline' (Repeater) - Chronik-Einträge für Timeline-Component
 * - Hero-Felder (im hero.php Component definiert)
 * - Tabs-Felder (im tabs.php Component definiert)  
 * - Scroll-Cols-Felder (im scroll-cols.php Component definiert)
 * 
 * Template Parts:
 * - components/hero.php - Hero-Section mit Titel/Bild/Text
 * - components/tabs.php - Tab-Navigation mit Content
 * - components/timeline.php - Chronik mit ACF Repeater
 * - components/working-groups.php - Arbeitsgruppen-Übersicht
 * - components/jobs.php - Stellenausschreibungen-Liste
 * - components/scroll-cols.php - Horizontal scrollbare Spalten
 * - components/gremien.php - Gremien & Bündnisse Übersicht
 * 
 * Dependencies:
 * - ACF Plugin (für conditional fields)
 * - Contact Form 7 Plugin (für Mitgliedsantrag)
 * 
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @package d64
 */

// Page-Daten und Flags einmal laden (Performance)
$page_id = get_the_ID();
$page_slug = get_post_field('post_name', $page_id);
$page_title = get_the_title();

// Page-spezifische Checks (sicherer als Titel-Vergleiche)
$is_jobs_page = ($page_slug === 'jobs' || is_page('jobs'));
$is_membership_page = ($page_slug === 'mitglied-werden' || is_page('mitglied-werden'));
$is_working_groups_page = ($page_slug === 'arbeitsgruppen' || $page_title === 'Arbeitsgruppen');
$is_gremien_page = ($page_slug === 'gremien' || is_page('gremien') || is_page('gremien-buendnisse'));

// ACF Timeline-Daten prüfen
$has_timeline = have_rows('timeline');
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="main">

    <!-- Hero Section (Standard für alle Pages) -->
    <?php 
    get_template_part('template-parts/components/hero', null, [
        'context' => 'page',
        'page_id' => $page_id
    ]); 
    ?>

    <!-- Tab Navigation (falls ACF-Felder vorhanden) -->
    <?php 
    get_template_part('template-parts/components/tabs', null, [
        'context' => 'page',
        'page_id' => $page_id
    ]); 
    ?>

    <!-- Timeline/Chronik Section (conditional via ACF) -->
    <?php if ($has_timeline) : ?>
        <section class="timeline-section" aria-labelledby="timeline-heading">
            <?php 
            get_template_part('template-parts/components/timeline', null, [
                'context' => 'page-chronik',
                'page_id' => $page_id
            ]); 
            ?>
        </section>
    <?php endif; ?>

    <!-- Arbeitsgruppen Section (nur auf Arbeitsgruppen-Seite) -->
    <?php if ($is_working_groups_page) : ?>
        <section class="working-groups-section" aria-labelledby="working-groups-heading">
            <!-- Hidden heading for screen readers -->
            <h2 id="working-groups-heading" class="sr-only">
                <?php esc_html_e('Arbeitsgruppen Übersicht', 'd64'); ?>
            </h2>
            <?php 
            get_template_part('template-parts/components/working-groups', null, [
                'context' => 'main-page',
                'page_id' => $page_id
            ]); 
            ?>
        </section>
    <?php endif; ?>

    <!-- Jobs Section (nur auf Jobs-Seite) -->
    <?php if ($is_jobs_page) : ?>
        <section class="jobs-section" aria-labelledby="jobs-heading">
            <!-- Hidden heading for screen readers -->
            <h2 id="jobs-heading" class="sr-only">
                <?php esc_html_e('Stellenausschreibungen', 'd64'); ?>
            </h2>
            <?php 
            get_template_part('template-parts/components/jobs', null, [
                'context' => 'main-page',
                'page_id' => $page_id
            ]); 
            ?>
        </section>
    <?php endif; ?>

    <!-- Mitgliedsantrag Section (nur auf Mitglied-werden-Seite) -->
    <?php if ($is_membership_page) : ?>
        <section class="membership-application-section" aria-labelledby="membership-heading">
            <div class="max-w-[640px] px-4 sm:px-0 m-auto pt-10 sm:pt-6">
                <div class="sm:p-4 rounded-xl sm:bg-d64blue-50 flex flex-col gap-6">
                    <h3 id="membership-heading" class="text-lg font-bold font-serif">
                        <?php esc_html_e('Mitgliedsantrag', 'd64'); ?>
                    </h3>
                    <div class="w-full">
                        <?php
                        // Contact Form 7 für Mitgliedsantrag
                        $membership_form_shortcode = '[contact-form-7 id="64be5e3" title="Mitglied werden"]';
                        
                        // Prüfen ob Contact Form 7 Plugin aktiv ist
                        if (function_exists('wpcf7_contact_form')) {
                            echo do_shortcode($membership_form_shortcode);
                        } else {
                            // Fallback wenn Plugin nicht aktiv
                            echo '<p class="text-red-600">' . esc_html__('Kontaktformular kann nicht geladen werden. Bitte wenden Sie sich direkt an uns.', 'd64') . '</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Scroll Columns Section (Standard für alle Pages) -->
    <?php 
    get_template_part('template-parts/components/scroll-cols', null, [
        'context' => 'page',
        'page_id' => $page_id
    ]); 
    ?>

    <!-- Gremien Section (nur auf Gremien-Seiten) -->
    <?php if ($is_gremien_page) : ?>
        <section class="gremien-section" aria-labelledby="gremien-heading">
            <!-- Hidden heading for screen readers -->
            <h2 id="gremien-heading" class="sr-only">
                <?php esc_html_e('Gremien und Bündnisse', 'd64'); ?>
            </h2>
            <?php 
            get_template_part('template-parts/components/gremien', null, [
                'context' => 'main-page',
                'page_id' => $page_id
            ]); 
            ?>
        </section>
    <?php endif; ?>

</div><!-- #post-<?php the_ID(); ?> -->

<?php
/**
 * Template Usage Notes:
 * 
 * Dieses Template ist hochmodular aufgebaut. Jede Seite kann verschiedene
 * Kombinationen von Komponenten haben:
 * 
 * Standard-Layout (alle Seiten):
 * 1. Hero Section
 * 2. Tabs (falls ACF-Daten vorhanden)
 * 3. Scroll Columns
 * 
 * Erweiterte Layouts:
 * - Seiten mit Timeline: + Timeline Component
 * - Arbeitsgruppen-Seite: + Working Groups Component
 * - Jobs-Seite: + Jobs Component  
 * - Mitglied-werden-Seite: + Contact Form
 * - Gremien-Seite: + Gremien Component
 * 
 * Für neue spezielle Seiten:
 * 1. Page-Check hinzufügen ($is_new_page_page)
 * 2. Section mit aria-labelledby hinzufügen
 * 3. Template Part mit Context-Args laden
 * 4. Dokumentation hier erweitern
 */