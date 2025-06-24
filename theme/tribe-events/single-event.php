<?php
if (!defined('ABSPATH')) {
    die('-1');
}

$event_id = get_the_ID();
$currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>

<div id="" class="">
    <article id="">
        <header class="pt-4 sm:pt-8 md:pt-12 lg:pt-16 xl:pt-20 md:pb-2 lg:pb-4 xl:pb-6">
            <div class="max-w-2xl px-4 m-auto">
                <h1 class="font-serif !font-bold max-sm:!text-3xl sm:text-5xl mb-2" ><?php the_title(); ?></h1>

                <!-- excerpt -->
                <div class="flex">
                    <div class="text-md leading-loose font-medium sm:text-lg sm:col-span-2">
                        <?php echo get_the_excerpt(); ?>
                    </div>
                </div>
            </div>
        </header>

        <!-- post thumbnail and dates -->
        <div class="m-auto bg-d64blue-50 max-sm:mt-6 sm:mt-4 max-sm:pb-4">
            <div class="flex flex-col sm:py-8 sm:grid sm:grid-cols-2 max-w-4xl md:gap-8 m-auto">
                <!-- Event Image -->
                <?php if (has_post_thumbnail()) : ?>
                    <div class="aspect-video sm:aspect-auto sm:mx-4 sm:overflow-hidden sm:object-fill relative">    
                        <?php 
                        $image_id = get_post_thumbnail_id();
                        $image_post = get_post($image_id);
                        $image_description = $image_post ? $image_post->post_content : '';
                        
                        echo get_the_post_thumbnail(null, 'medium-16-9', array(
                            'class' => 'h-auto aspect-video @lg:max-h-[320px] @3xl:max-h-[400px] w-full object-cover rounded-xl',
                            'id' => 'your-id-name'
                        ));

                        if ($image_description) {
                            echo '<div class="absolute bottom-0 right-0 max-w-max rounded-tl bg-slate-300 bg-opacity-80 text-slate-800 text-xs font-medium text-end px-1 py-[1px]">' . $image_description . '</div>';
                        }
                        ?>
                    </div>
                <?php endif; ?>

                <!-- Event Details -->
                <div class="px-4  bg-d64blue-50 flex flex-col gap-8 sm:gap-10 md:gap-12 text-d64blue-900">
                    <!-- Date and Time -->
                    
                        <div class="flex flex-col gap-1">
                            <span class="font-medium text-xl"> 
                                <?php tribe_get_template_part( 'modules/meta' ); ?>
           
                                <!-- <?php echo get_post_meta($event_id, '_EventStartDate', true); ?> -->
                            </span>
                    
                    </div>
                </div>  
            </div>
        </div>

        <!-- Event Content -->
        <div class="desc px-4 pt-4 sm:pt-8 md:pt-10 lg:pt-12 xl:pt-14 max-w-2xl m-auto max-sm:pb-6">
         <!-- .tribe-events-single-event-description -->
            <div class="mx-auto max-w-2xl pb-6">
                <?php do_action( 'tribe_events_single_event_after_the_meta' ) ?>
                <?php do_action( 'tribe_events_single_event_before_the_meta' ) ?>
                <?php do_action( 'tribe_events_single_event_before_the_content' ) ?>
            </div>
            <div <?php post_class('entry-content prose prose-neutral max-w-none prose-a:text-primary prose-figure:my-4 prose-figure:py-0 prose-figure:text-d64blue-900 prose-figure:!w-[min(100%,640px)] prose-img:max-w-full'); ?>>
                <?php the_content(); ?>
            </div>
        </div>


        <!-- .tribe-events-single-event-description -->
            <div class="mx-auto max-w-2xl">
                <?php do_action( 'tribe_events_single_event_after_the_content' ) ?>
            </div>

            
            </div>
        </article>

    </div>