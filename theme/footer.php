<?php
/**
 * Footer Template
 *
 * Schließt die Haupt-Layout-Container und lädt den Footer-Inhalt.
 * Diese Datei wird am Ende jeder Seite geladen und schließt das 
 * von header.php geöffnete HTML-Grundgerüst.
 * 
 * Struktur:
 * - Schließt #content Container (geöffnet in header.php)
 * - Lädt footer-content Template Part mit Navigation und Newsletter
 * - Schließt #page Container (geöffnet in header.php)  
 * - Lädt WordPress Footer Scripts und schließt HTML
 * 
 * Partner-Template: header.php
 * Lädt Template Part: template-parts/layout/footer-content.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 * @package d64
 */

?>
</div><!-- #content -->

<?php 
// Footer-Inhalt laden (Navigation, Newsletter, etc.)
get_template_part('template-parts/layout/footer', 'content'); 
?>

</div><!-- #page -->

<?php 
// WordPress Footer Hook - Lädt Scripts, Admin Bar, etc.
wp_footer(); 
?>

</body>
</html>