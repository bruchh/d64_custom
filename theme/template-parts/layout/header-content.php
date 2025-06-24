<?php
/**
 * Template part für Header Content
 * 
 * Zeigt je nach Template verschiedene Header-Varianten:
 * - Projektseiten: Einfacher Zurück-Link
 * - Normale Seiten: Vollständiger Header mit Navigation und Suche
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @package d64
 */

// Konstanten für wiederkehrende Werte
define('D64_HEADER_MAX_WIDTH', '1200px');
define('D64_HEADER_LOGO_WIDTH_MOBILE', 'w-24');
define('D64_HEADER_LOGO_WIDTH_DESKTOP', 'md:w-28');
?>

<?php if (is_page_template('projekt.php')) : ?>
	<!-- Vereinfachter Header für Projektseiten -->
	<div class="fixed items-center md:px-8 top-0 right-0 left-0 w-screen z-200 bg-white bg-opacity-80 backdrop-blur-sm px-4 py-6 md:py-6 max-w-[<?php echo D64_HEADER_MAX_WIDTH; ?>] xl:px-4 m-auto">
		<a class="group font-medium flex items-center gap-2" href="<?php echo esc_url(home_url('/')); ?>">
			<svg class="group-hover:-translate-x-1 transition-all" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left" aria-hidden="true">
				<path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>
			</svg>
			<?php esc_html_e('Zurück zu D64', 'd64'); ?>
		</a>
	</div>

<?php else : ?>
	<!-- Standard Header für alle anderen Seiten -->
	<header id="masthead" class="relative pb-[86px] lg:pb-[95px]">
		<div id="header-content" data-expanded="false" class="fixed top-0 left-0 right-0 z-20 bg-white bg-opacity-80 backdrop-blur-sm">
			
			<!-- Haupt-Header mit Logo und Navigation -->
			<div id="header-header" class="inner w-full transition-color bg-transparent flex justify-between items-center px-4 py-4 max-w-[<?php echo D64_HEADER_MAX_WIDTH; ?>] m-auto relative z-50">
				
				<!-- Logo -->
				<div id="main-logo" class="logo z-50">
					<a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
						<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/icons/d64_logo.png'); ?>" 
						     alt="<?php esc_attr_e('D64 Logo', 'd64'); ?>" 
						     class="<?php echo esc_attr(D64_HEADER_LOGO_WIDTH_MOBILE . ' ' . D64_HEADER_LOGO_WIDTH_DESKTOP); ?>" 
						     width="600" 
						     height="338">
					</a>
				</div>

				<!-- Zurück-Button für Mobile Sub-Navigation (versteckt per Default) -->
				<div id="sub-nav-btn-container" class="hidden absolute h-[54px] w-24 bg-d64gray-50 left-4 top-4 z-[100]">
					<button id="submenu-nav-button" 
					        class="rounded-full text-d64blue-500 w-max"
					        aria-label="<?php esc_attr_e('Zurück zur Hauptnavigation', 'd64'); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left pointer-events-none" aria-hidden="true">
							<path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>
						</svg>
					</button>
				</div>

				<!-- Mobile Navigation Button -->
				<div class="lg:hidden">
					<?php get_template_part('template-parts/components/mobile-nav-button'); ?>
				</div>

				<!-- Desktop Navigation und Suche -->
				<div class="z-10 hidden lg:block relative">
					<?php get_template_part('template-parts/components/desktop-navigation'); ?>
					<?php get_template_part('template-parts/components/search-bar'); ?>
					
					<!-- Search Results Overlay -->
					<div id="search-result-overlay" class="hidden max-h-[80vh] overflow-y-scroll top-20 border bg-white p-2 pt-4 rounded-b-lg w-auto z-10 shadow">
						<div class="px-2 m-auto">
							<div id="search-results" class="space-y-4 pt-4">
								<div>
									<h3 class="font-medium italic text-lg pb-2"><?php esc_html_e('Themen', 'd64'); ?></h3>
									<div id="post-results" class="pl-2 results-section"></div>
									<div id="post-pagination" class="mt-2 flex gap-1"></div>
								</div>
								<div class="border-d64blue-900 border-t pt-2">
									<h3 class="font-medium italic text-lg pb-2"><?php esc_html_e('Seiten', 'd64'); ?></h3>
									<div id="page-results" class="pl-2 results-section"></div>
									<div id="page-pagination" class="mt-2 flex gap-1"></div>
								</div>
								<div id="no-results" class="hidden pb-1 -translate-y-2">
									<p><?php esc_html_e('Keine Ergebnisse', 'd64'); ?></p>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Desktop Icon Bar -->
				<div class="hidden lg:block">
					<?php get_template_part('template-parts/components/nav-icon-bar'); ?>
				</div>
			</div>

			<!-- Mobile Navigation (versteckt per Default) -->
			<?php get_template_part('template-parts/components/mobile-navigation'); ?>
		</div>
	</header><!-- #masthead -->

<?php endif; ?>