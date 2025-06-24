<?php
/**
 * Template part for displaying event preview tile
 */

// Ensure we're working with an event post
if (!tribe_is_event($post)) {
    return;
}

// Get event details once
$event_title = get_the_title();
$event_link = get_permalink();
$event_date = tribe_get_start_date(null, false, 'j. F Y');
$event_time = tribe_get_start_time();
$event_venue = tribe_get_venue();
?>

<div class="@container h-full w-full">
    <div class="link-tile post-tile group h-full hover:bg-d64blue-50 rounded-xl overflow-hidden cursor-pointer flex flex-col relative transition @lg:grid @lg:grid-cols-2 @lg:!gap-4 @lg:!p-2 @3xl:!grid-cols-3">
        <!-- Registration check -->


        <!-- Image section -->
        <div class="post-tile__image relative rounded-xl overflow-hidden group-hover:rounded-none aspect-video transition-all @lg:group-hover:rounded-xl @lg:col-start-1 @3xl:col-start-2 @3xl:col-span-2">
            <?php
            if (has_post_thumbnail()) {
                $image_id = get_post_thumbnail_id();
                $image_post = get_post($image_id);
                $image_description = $image_post ? $image_post->post_content : '';
                echo get_the_post_thumbnail(null, 'medium-16-9', array(
                    'class' => 'aspect-video w-full object-cover rounded-xl',
                    'id' => 'your-id-name'
                ));
                if ($image_description) {
                    echo '<div class="absolute object-cover bottom-0 right-0 max-w-max rounded-tl bg-slate-300 bg-opacity-80 text-slate-800 text-xs font-medium text-end px-1 py-[1px]">' . $image_description . '</div>';
                }
            }
            ?>
        </div>

        <!-- Content section -->
        <div class="post-tile__content h-auto justify-between !px-2 !pt-4 flex flex-col @2xl:justify-end !pb-4 @lg:!pb-0 @xl:!pb-0 @3xl:!pt-12 col-span-1 row-start-1 @lg:col-start-2 @lg:!pt-0 @3xl:col-start-1">
            <div class="">
                <h3 class="post-tile__title !pb-3">
                    <a href="<?php echo esc_url($event_link); ?>" class="!text-lg md:!text-2xl !font-serif !font-bold group-hover:!underline !transition-all">
                        <?php echo esc_html($event_title); ?>
                    </a>
                </h3>
                <div class="post-tile__excerpt">
                    <?php
                    if (has_excerpt()) {
                        echo get_the_excerpt();
                    } else {
                        // If no excerpt, truncate the full description to 180 characters
                        $content = get_the_content();
                        $truncated_description = mb_substr(wp_strip_all_tags($content), 0, 180);
                        echo esc_html($truncated_description) . '...';
                    }
                    ?>
                </div>
            </div>

            <!-- Event details -->
            <div class="!flex @lg:!relative @xl:!relative !left-0 !bottom-2 !right-0 @lg:!px-0 !flex-wrap !pt-4 !gap-x-3 !gap-y-1">
                <span class="font-medium text-sm lg:text-base">
                    <?php echo esc_html($event_date); ?>
                </span>
                <span class="font-medium text-sm lg:text-base">
                    <?php echo esc_html($event_time); ?>
                </span>
                <span class="font-medium text-sm lg:text-base">
                    <?php echo esc_html($event_venue); ?>
                </span>
            </div>
        </div>
    </div>
</div>