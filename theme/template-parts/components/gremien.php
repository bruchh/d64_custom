<?php
/**
 * Template part for displaying a group of people with a title, desc and person tiles
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package d64
 */
?>
<!-- tile with person's name, title, short bio and image, Personen is a custom post type -->

<?php 
    if (have_rows('gremium')) : ?>
        <section class="mdm:px-0 px-4 max-w-4xl py-10 m-auto space-y-16 md:space-y-24">
            <?php
        while (have_rows('gremium')): the_row();

        ?> <div class="space-y-4"> <?php
        $name = get_sub_field('name');
        $desc = get_sub_field('beschreibung');
        $mitlgieder = get_sub_field('mitglieder');
        ?>
        <h2 class="text-lg sm:text-xl italic m-auto font-medium"><?php echo $name ?></h2>
        <p class="pb-4 max-w-3xl text-lg"><?php echo $desc; ?></p>
        <!-- Personen Tiles -->
        <div class="grid gap-6">
            <!-- Display the post using post-tile template part -->
            <?php foreach ( $mitlgieder as $post ) : setup_postdata( $post );
                $text_gremium = get_field('text_gremium');
              
            ?>
                <div class="md:grid md:grid-cols-[320px,1fr] gap-8">
                <div class="flex h-min">
                            <?php get_template_part('template-parts/components/person-tile') ?>
                    </div>
                    <div class="prose max-md:hidden">
                        <?php echo !empty($text_gremium) ? $text_gremium : ''; ?>
                    </div>
                </div>
            <?php endforeach; wp_reset_postdata(); ?>
        </div>
    </div>
                
    <?php endwhile;?>
        </section>
    <?php endif; ?>
