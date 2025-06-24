<?php
/**
 * Fallback Template
 * 
 * Dieses Template wird nur verwendet, wenn kein spezifischeres Template gefunden wird.
 * Da für alle Inhalte spezifische Templates existieren, sollte diese Datei in der 
 * Praxis nicht zum Einsatz kommen.
 *
 * @package d64
 */

get_header(); ?>

<main class="container mx-auto px-4 py-8">
	<?php if ( have_posts() ) : ?>
		
		<?php if ( is_home() && ! is_front_page() ) : ?>
			<header class="mb-8">
				<h1 class="text-3xl font-bold"><?php single_post_title(); ?></h1>
			</header>
		<?php endif; ?>

		<div class="space-y-8">
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'bg-white rounded-lg shadow-md p-6' ); ?>>
					<header class="mb-4">
						<?php
						if ( is_singular() ) :
							the_title( '<h1 class="text-2xl font-bold mb-2">', '</h1>' );
						else :
							the_title( sprintf( 
								'<h2 class="text-xl font-semibold"><a href="%s" class="text-blue-600 hover:text-blue-800" rel="bookmark">', 
								esc_url( get_permalink() ) 
							), '</a></h2>' );
						endif;
						?>
					</header>

					<div class="prose max-w-none">
						<?php
						if ( is_singular() ) :
							the_content();
						else :
							the_excerpt();
						endif;
						?>
					</div>
				</article>
			<?php endwhile; ?>
		</div>

	<?php else : ?>
		
		<div class="text-center py-12">
			<h1 class="text-2xl font-bold mb-4">Keine Inhalte gefunden</h1>
			<p class="text-gray-600">Es wurden keine Inhalte gefunden, die deiner Anfrage entsprechen.</p>
		</div>

	<?php endif; ?>
</main>

<?php get_footer();