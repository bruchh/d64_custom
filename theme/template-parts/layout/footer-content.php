<?php
/**
 * Template part für Footer Content
 *
 * Footer mit zwei Hauptbereichen:
 * - Links: Footer Navigation Links + D64 Logo
 * - Rechts: Newsletter Abonnement Karten (D64 Ticker & D64 Quarterly)
 * 
 * Features:
 * - ACF Options für Footer Links und Newsletter Beschreibungen
 * - Responsive Grid Layout (mobil: 1 Spalte, desktop: 2 Spalten)
 * - Newsletter Cards mit externen Links
 * 
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @package d64
 */

// Konstanten für wiederkehrende Werte
define('D64_FOOTER_MAX_WIDTH', '1200px');
define('D64_FOOTER_LOGO_WIDTH_MOBILE', 'w-24');
define('D64_FOOTER_LOGO_WIDTH_DESKTOP', 'md:w-36');
define('D64_NEWSLETTER_TICKER_URL', 'https://kontakt.d-64.org/ticker/');
define('D64_NEWSLETTER_QUARTERLY_URL', 'https://kontakt.d-64.org/newsletter/');
?>

<footer id="colophon" class="bg-d64blue-900 sm:mt-20 lg:mt-28">
	<div class="py-10 max-w-[<?php echo D64_FOOTER_MAX_WIDTH; ?>] m-auto flex flex-col-reverse px-4 lg:grid lg:grid-cols-2 gap-12 md:gap-8">
		
		<!-- Linke Spalte: Navigation + Logo -->
		<div class="flex flex-col md:flex-col-reverse gap-12 md:gap-48">
			
			<!-- Footer Navigation Links -->
			<?php
			$footer_navigation_items = get_field('footer_links', 'option');
			if ($footer_navigation_items && !empty($footer_navigation_items)): 
			?>
			<nav class="text-white text-sm font-medium" role="navigation" aria-label="<?php esc_attr_e('Footer Navigation', 'd64'); ?>">
				<ul class="style-none flex flex-col md:flex-row gap-2 md:gap-4">
					<?php foreach ($footer_navigation_items as $item) : 
						if (!empty($item['link']['url']) && !empty($item['link']['title'])) : ?>
						<li>
							<a href="<?php echo esc_url($item['link']['url']); ?>" 
							   <?php if (!empty($item['link']['target']) && $item['link']['target'] === '_blank') : ?>
							   	target="_blank" 
							   	rel="noopener noreferrer"
							   	aria-label="<?php echo esc_attr($item['link']['title'] . ' ' . __('(öffnet in neuem Tab)', 'd64')); ?>"
							   <?php endif; ?>
							   class="md:hover:bg-d64blue-50 md:hover:text-d64blue-900 md:py-1 md:px-2 md:rounded-lg transition-colors">
								<?php echo esc_html($item['link']['title']); ?>
							</a>
						</li>
					<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			</nav>
			<?php endif; ?>
			
			<!-- Footer Logo -->
			<div id="footer-logo" class="logo z-50">
				<a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="max-w-content">
					<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/icons/d64_logo_white.png'); ?>" 
					     alt="<?php esc_attr_e('D64 Logo weiß', 'd64'); ?>" 
					     class="<?php echo esc_attr(D64_FOOTER_LOGO_WIDTH_MOBILE . ' ' . D64_FOOTER_LOGO_WIDTH_DESKTOP); ?>" 
					     width="600" 
					     height="338">
				</a>
			</div>
		</div>
		
		<!-- Rechte Spalte: Newsletter Abonnement -->
		<div class="block max-w-2xl">
			<div class="text-white">
				<h2 class="text-lg italic">
					<?php esc_html_e('Abonniere unsere Newsletter!', 'd64'); ?>
				</h2>
				
				<!-- Newsletter Cards Grid -->
				<div class="pt-6 grid grid-cols-2 gap-2">
					
					<!-- D64 Ticker Newsletter Card -->
					<div class="p-4 rounded-xl border-2 border-d64blue-500 flex flex-col justify-between">
						<div>
							<h3 class="font-medium text-base sm:text-lg">
								<?php esc_html_e('D64 Ticker', 'd64'); ?>
							</h3>
							<p class="text-xs sm:text-sm">
								<?php 
								$ticker_description = get_field('ticker', 'option');
								echo $ticker_description ? esc_html($ticker_description) : esc_html__('Regelmäßige Updates zu digitalpolitischen Themen.', 'd64'); 
								?>
							</p>
						</div>
						<a href="<?php echo esc_url(D64_NEWSLETTER_TICKER_URL); ?>" 
						   target="_blank" 
						   rel="noopener noreferrer"
						   class="bg-d64blue-500 hover:bg-white transition-colors text-center rounded-full py-2 px-4 text-d64blue-900 font-medium mt-4"
						   aria-label="<?php esc_attr_e('D64 Ticker Newsletter abonnieren (öffnet in neuem Tab)', 'd64'); ?>">
							<?php esc_html_e('Abonnieren', 'd64'); ?>
						</a>
					</div>
					
					<!-- D64 Quarterly Newsletter Card -->
					<div class="p-4 rounded-xl border-2 border-d64blue-500 flex flex-col justify-between">
						<div>
							<h3 class="font-medium text-base sm:text-lg">
								<?php esc_html_e('D64 Quarterly', 'd64'); ?>
							</h3>
							<p class="text-xs sm:text-sm">
								<?php 
								$quarterly_description = get_field('quarterly', 'option');
								echo $quarterly_description ? esc_html($quarterly_description) : esc_html__('Vierteljährlicher Newsletter mit ausführlichen Analysen.', 'd64'); 
								?>
							</p>
						</div>
						<a href="<?php echo esc_url(D64_NEWSLETTER_QUARTERLY_URL); ?>" 
						   target="_blank" 
						   rel="noopener noreferrer"
						   class="bg-d64blue-500 hover:bg-white transition-colors text-center rounded-full py-2 px-4 text-d64blue-900 font-medium mt-4"
						   aria-label="<?php esc_attr_e('D64 Quarterly Newsletter abonnieren (öffnet in neuem Tab)', 'd64'); ?>">
							<?php esc_html_e('Abonnieren', 'd64'); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</footer><!-- #colophon -->