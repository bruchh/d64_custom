
<?php
/**
 * Template part for displaying skip link
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package d64
 */
?>

<!-- Wordpress Component for skip link -->

<?php
    $url = $args['url']; 
    $text = $args['text'];
?>

    <a href="<?php echo $url?>" class="absolute top-[-40px] left-0 bg-black text-white p-1 z-50 transition-transform transform translate-y-0 focus:top-0">
        <?php echo $text; ?>
    </a>


