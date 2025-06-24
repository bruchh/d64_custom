<?php
/**
 * Template part for displaying ansprechpartner
 *
 */

$ansprechpartner = isset($args['ansprechpartner']) ? $args['ansprechpartner'] : null;
$main_color = isset($args['main_color']) ? $args['main_color'] : '#6B46C1';
$is_desktop = isset($args['is_desktop']) ? $args['is_desktop'] : false;

if ($ansprechpartner) : 
?>
<section class="ansprechperson-section @container pt-6 pb-2 bg-transparent border-2 custom-border relative">
    <div class="chopped-corner absolute top-0 right-0 w-12 h-12 bg-white rotate-45 translate-x-[24.5px] -translate-y-[24.5px] border-b-2 custom-border"></div>
    <h2 class="text-lg lg:text-xl m-auto font-medium pl-4 custom-color">Ansprechperson</h2>
    <div class="ansprechperson-content">
        <?php foreach ( $ansprechpartner as $post ) : setup_postdata( $post );
            $links = get_field('links');
            $social_media_fields = ['linkedin', 'email', 'twitter', 'facebook', 'instagram', 'website', 'mastodon'];
            $has_social_media = false;

            foreach ($social_media_fields as $field) {
                if (!empty($links[$field])) {
                    $has_social_media = true;
                    break;
                }
            }
        ?>
        <div class="person-tile bg-transparent rounded-xl p-4 flex flex-col @md:flex-row @md:gap-8">
            <div class="person-tile__image overflow-hidden aspect-square mb-4">
                <?php the_post_thumbnail('medium-1-1', ['class' => '!rounded-none']); ?>
            </div>
            
            <div class="">
            <div class="person-tile__content">
                <h3 class="person-tile__title pb-3 flex flex-col">
                    <span class="text-2xl md:text-[28px] font-medium custom-color"><?php the_title(); ?></span>
                    <?php if (get_field('funktion')) : ?>
                        <span class="text-lg md:text-xl pt-2 font-normal custom-color "><?php the_field('funktion'); ?></span>
                    <?php endif; ?>
                </h3>
                
                <div class="person-tile__excerpt text-base">
                    <?php if (get_field('desc')) the_field('desc'); ?>
                </div>
            </div>
            
            <?php if ($has_social_media) : ?>
            <div class="person-tile__social mt-4 md:mt-8 flex gap-2">
                <?php
                $social_media_icons = [
                    'twitter' => 'twitter.svg',
                    'facebook' => 'facebook.svg',
                    'instagram' => 'instagram.svg',
                    'website' => 'globe.svg',
                    'linkedin' => 'linkedin.svg',
                    'email' => 'mail.svg',
                    'mastodon' => 'mdi_mastodon.svg',
                ];
                
                foreach ($social_media_icons as $key => $icon) {
                    if (!empty($links[$key])) {
                        $url = $key === 'email' ? 'mailto:' . $links[$key] : $links[$key];
                        echo "<a href='" . esc_url($url) . "' target='_blank' rel='noopener noreferrer' class='w-5 h-5'>";
                        echo "<img src='" . get_template_directory_uri() . "/assets/icons/$icon' alt='" . ucfirst($key) . " Icon'>";
                        echo "</a>";
                    }
                }?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php 
wp_reset_postdata();
endif; 
?>