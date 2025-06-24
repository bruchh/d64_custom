<?php
/**
 * Template Part für Section-Überschriften
 *
 * Wiederverwendbare Komponente für konsistente Section-Überschriften.
 * Unterstützt verschiedene Heading-Level und optionale IDs für Accessibility.
 * 
 * Verwendung:
 * $args = [
 *     'tag' => 'h2',                    // h1, h2, h3, h4, h5, h6
 *     'text' => 'Meine Überschrift',   // Überschrift-Text
 *     'id' => 'section-id'             // Optional: ID für aria-labelledby
 * ];
 * get_template_part('components/section-header', null, $args);
 * 
 * Features:
 * - Sichere Tag-Validierung (nur h1-h6 erlaubt)
 * - XSS-Schutz durch Escaping
 * - Accessibility-optimiert
 * - Responsive Typography
 * 
 * @package d64
 */

// Args validieren und Defaults setzen
$tag = isset($args['tag']) ? $args['tag'] : 'h2';
$text = isset($args['text']) ? $args['text'] : '';
$id = isset($args['id']) ? $args['id'] : '';

// Erlaubte Heading-Tags (Security: nur h1-h6)
$allowed_tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
if (!in_array($tag, $allowed_tags)) {
    $tag = 'h2'; // Fallback auf h2
}

// Leeren Text verhindern
if (empty($text)) {
    return; // Nichts anzeigen wenn kein Text vorhanden
}
?>

<div class="section-header w-full pb-8 sm:pb-8 md:pb-12 lg:pb-16">
    <<?php echo esc_html($tag); ?> 
        class="section-header__title text-lg sm:text-xl italic text-center m-auto font-medium"
        <?php if (!empty($id)) : ?>
            id="<?php echo esc_attr($id); ?>"
        <?php endif; ?>>
        <?php echo esc_html($text); ?>
    </<?php echo esc_html($tag); ?>>
</div>