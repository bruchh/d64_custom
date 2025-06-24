<?php
/**
 * Theme Header
 * 
 * Lädt den HTML Head-Bereich und die Seiten-Navigation.
 * Stellt die Grundstruktur für alle Seiten bereit.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 * @package d64
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="relative">
	<!-- Sprunglink für Barrierefreiheit -->
	<a href="#content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-d64blue-900 focus:text-white focus:rounded">
		<?php esc_html_e( 'Zum Hauptinhalt springen', 'd64' ); ?>
	</a>
	
	<!-- Header Navigation -->
	<div class="z-20">
		<?php get_template_part( 'template-parts/layout/header', 'content' ); ?>
	</div>
	
	<!-- Hauptinhalt Container -->
	<div id="content" class="z-10">