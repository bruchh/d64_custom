<?php
/**
 * Template Part für Personen-Kacheln
 *
 * Zeigt eine Person mit Bild, Name, Funktion, Beschreibung und Social Media Links.
 * Wird verwendet für Custom Post Type 'personen' und 'arbeitsgruppe'.
 * 
 * ACF Felder:
 * - 'funktion' (Text) - Position/Rolle der Person
 * - 'desc' (Textarea) - Kurzbeschreibung
 * - 'links' (Group) - Social Media Links (linkedin, twitter, facebook, etc.)
 * - 'koordination' (Relationship) - Für Arbeitsgruppen
 * 
 * Features:
 * - Responsive Design (@container queries)
 * - Social Media Integration
 * - Bildbeschriftung-Support
 * - Link zu Autoren-Beiträgen (Blog-Integration)
 * - Koordination/Mitwirkende für Arbeitsgruppen
 * 
 * Global Variables:
 * - $linked_author_id - Verknüpfung zu Blog-Autor
 * - $mitarbeit_data - Mitwirkende Posts
 * 
 * @package d64
 */

// ACF Social Media Links laden und prüfen
$links = get_field('links');
$social_media_fields = ['linkedin', 'email', 'twitter', 'facebook', 'instagram', 'website', 'mastodon'];
$has_social_media = false;

if ($links && is_array($links)) {
    foreach ($social_media_fields as $field) {
        if (!empty($links[$field])) {
            $has_social_media = true;
            break;
        }
    }
}

// Thumbnail-Informationen laden
$thumbnail_id = get_post_thumbnail_id();
$thumbnail_desc = '';
if ($thumbnail_id) {
    $thumbnail_post = get_post($thumbnail_id);
    if ($thumbnail_post && !empty($thumbnail_post->post_content)) {
        $thumbnail_desc = esc_html($thumbnail_post->post_content);
    }
}

// Person ID für Links
$person_id = get_the_ID();
$person_title = get_the_title();
?>

<div class="@container h-full w-full">
    <article class="person-tile group h-full bg-d64blue-50 rounded-xl pt-4 px-4 overflow-hidden flex flex-col relative transition @md:grid @md:grid-cols-3 @md:gap-4"
             itemscope 
             itemtype="https://schema.org/Person">
        
        <!-- Person Image -->
        <div class="person-tile__image rounded-lg relative overflow-hidden aspect-square @md:mb-4 transition-all"> 
            <?php 
            if (has_post_thumbnail()) {
                the_post_thumbnail('medium-1-1', array(
                    'alt' => esc_attr(sprintf(__('Foto von %s', 'd64'), $person_title)),
                    'itemprop' => 'image'
                ));
                
                // Bildbeschreibung falls vorhanden
                if (!empty($thumbnail_desc)) {
                    echo '<div class="absolute bottom-0 right-0 max-w-max rounded bg-slate-300 bg-opacity-40 text-slate-800 text-xs font-medium text-end px-1 py-[1px]">';
                    echo $thumbnail_desc;
                    echo '</div>';
                }
            }
            ?>
        </div>
        
        <!-- Person Content -->
        <div class="person-tile__content <?php echo $has_social_media ? 'pb-20' : 'pb-4'; ?> pt-4 @md:pt-0 @md:col-span-2">
            <div>
                
                <!-- Person Name & Title (ggf. mit Link zu Blog-Posts) -->
                <?php 
                global $linked_author_id;
                $show_author_link = $linked_author_id || get_post_type() === 'arbeitsgruppe';
                
                if ($show_author_link) : 
                ?>
                    <a href="<?php echo esc_url(home_url('/inhalte/?author_id=' . $person_id)); ?>" 
                       class="text-sm font-medium hover:underline"
                       aria-label="<?php echo esc_attr(sprintf(__('Alle Beiträge von %s anzeigen', 'd64'), $person_title)); ?>">
                <?php endif; ?>
                
                <h3 class="person-tile__title pb-3 flex flex-col" itemprop="name">
                    <span class="text-lg md:text-2xl font-serif font-bold transition-all">
                        <?php echo esc_html($person_title); ?>
                    </span>
                    
                    <?php 
                    $funktion = get_field('funktion');
                    if ($funktion) : 
                    ?>
                        <span class="text-sm md:text-base font-medium italic" itemprop="jobTitle">
                            <?php echo esc_html($funktion); ?>
                        </span>
                    <?php endif; ?>
                </h3>
                
                <?php if ($show_author_link) : ?>
                    </a>
                <?php endif; ?>
                
                <!-- Person Description -->
                <?php 
                $description = get_field('desc');
                if ($description) : 
                ?>
                <div class="person-tile__excerpt text-sm" itemprop="description">
                    <?php echo esc_html($description); ?>
                </div>
                <?php endif; ?>

                <!-- Mitwirkende (für Post Authors) -->
                <?php
                global $mitarbeit_data;
                if ($mitarbeit_data && !empty($mitarbeit_data)) : 
                ?> 
                <div class="flex flex-col flex-wrap pt-4">
                    <h4 class="text-sm font-semibold">
                        <?php esc_html_e('Mitwirkende', 'd64'); ?>
                    </h4>
                    <div class="flex flex-wrap gap-1 leading-tight">
                        <?php 
                        $count = 0;
                        $total = count($mitarbeit_data);
                        foreach ($mitarbeit_data as $post) : 
                            setup_postdata($post); 
                        ?>
                            <span class="text-sm font-regular">
                                <?php echo esc_html($post->post_title); ?>
                                <?php if (++$count < $total) : ?>
                                    <span class="font-medium">, </span>
                                <?php endif; ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                    <?php wp_reset_postdata(); ?>
                </div>
                <?php endif; ?>

                <!-- Koordination (nur für Arbeitsgruppen) -->
                <?php
                $koordination = get_field('koordination');
                if ($koordination && !empty($koordination) && !$mitarbeit_data) : 
                ?>
                <div class="flex flex-col flex-wrap pt-4">
                    <h4 class="text-sm font-semibold">
                        <?php esc_html_e('Koordination:', 'd64'); ?>
                    </h4>
                    <div class="flex gap-1 leading-tight">
                        <?php 
                        $count = 0;
                        $total = count($koordination);
                        foreach ($koordination as $post) : 
                            setup_postdata($post); 
                        ?>
                            <span class="text-sm font-regular">
                                <?php echo esc_html(get_the_title($post->ID)); ?>
                                <?php if (++$count < $total) : ?>
                                    <span class="font-medium">, </span>
                                <?php endif; ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                    <?php wp_reset_postdata(); ?>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Social Media Links -->
            <?php if ($has_social_media) : ?>
            <div class="absolute bottom-4 left-4 @md:left-[34.2%] flex flex-row gap-2 items-center transition-all">
                
                <?php 
                // Social Media Link-Konfiguration
                $social_config = [
                    'twitter' => ['icon' => 'twitter.svg', 'label' => 'Twitter'],
                    'facebook' => ['icon' => 'facebook.svg', 'label' => 'Facebook'],
                    'instagram' => ['icon' => 'instagram.svg', 'label' => 'Instagram'],
                    'website' => ['icon' => 'globe.svg', 'label' => 'Website'],
                    'linkedin' => ['icon' => 'linkedin.svg', 'label' => 'LinkedIn'],
                    'mastodon' => ['icon' => 'mdi_mastodon.svg', 'label' => 'Mastodon'],
                    'email' => ['icon' => 'mail.svg', 'label' => 'E-Mail']
                ];
                
                foreach ($social_config as $platform => $config) :
                    if (!empty($links[$platform])) :
                        $url = $platform === 'email' ? 'mailto:' . $links[$platform] : $links[$platform];
                        $is_external = $platform !== 'email';
                ?>
                        <a href="<?php echo esc_url($url); ?>" 
                           <?php if ($is_external) : ?>
                               target="_blank" 
                               rel="noopener noreferrer"
                           <?php endif; ?>
                           class="w-5 h-5 flex"
                           aria-label="<?php echo esc_attr(sprintf(__('%s Profil von %s', 'd64'), $config['label'], $person_title)); ?>">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/icons/' . $config['icon']); ?>" 
                                 alt="<?php echo esc_attr($config['label'] . ' ' . __('Icon', 'd64')); ?>"
                                 width="20" 
                                 height="20">
                        </a>
                <?php 
                    endif;
                endforeach; 
                ?>
            </div>
            <?php endif; ?>
        </div>
    </article>
</div>