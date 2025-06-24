<?php
/**
 * Template für 404 Fehlerseiten (Seite nicht gefunden)
 *
 * Wird angezeigt wenn eine URL nicht existiert oder gelöscht wurde.
 * Zeigt eine benutzerfreundliche Nachricht mit Link zur Startseite.
 * 
 * Features:
 * - Zentriertes, responsives Layout
 * - Klare 404 Fehlermeldung
 * - Call-to-Action zurück zur Startseite
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 * @package d64
 */

get_header();
?>

<section id="primary" class="min-h-[40vh]">
	<main id="main" role="main">
		<div>
			<div class="max-w-2xl m-auto pt-24">
				<header class="page-header text-center">
					<!-- HTTP Status Code -->
					<span class="font-medium text-d64blue-500 text-lg">
						<?php esc_html_e('404', 'd64'); ?>
					</span>
					
					<!-- Hauptüberschrift -->
					<h1 class="text-2xl font-semibold">
						<?php esc_html_e('Seite nicht gefunden', 'd64'); ?>
					</h1>
					
					<!-- Beschreibung -->
					<p class="text-lg pt-4 pb-12">
						<?php esc_html_e('Die Seite, die du suchst, existiert nicht.', 'd64'); ?>
					</p>
					
					<!-- Call-to-Action Link -->
					<a href="<?php echo esc_url(home_url('/')); ?>" 
					   class="text-lg font-medium text-d64blue-900 hover:text-d64blue-500 transition-colors"
					   aria-label="<?php esc_attr_e('Zurück zur D64 Startseite', 'd64'); ?>">
						<?php esc_html_e('Zurück zur Startseite', 'd64'); ?>
					</a>
				</header><!-- .page-header -->
			</div>
		</div>
	</main><!-- #main -->
</section><!-- #primary -->

<?php
get_footer();