<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default. Please note that
 * this is the WordPress construct of pages: specifically, posts with a post
 * type of `page`.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package d64
 */

get_header();
?>

	<section id="primary">
		<main id="main">
			<?php

			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				if ( get_post_type() === 'event' ) {
					get_template_part( 'template-parts/content/content', 'event' );
					
				} else {
					get_template_part( 'template-parts/content/content', 'page' );
				}

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
