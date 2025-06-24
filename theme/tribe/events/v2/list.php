<?php
$header_classes = ['tribe-events-header'];
if (empty($disable_event_search)) {
    $header_classes[] = 'tribe-events-header--has-event-search';
}
?>
<div
    <?php tribe_classes($container_classes); ?>
    data-js="tribe-events-view"
    data-view-rest-url="<?php echo esc_url($rest_url); ?>"
    data-view-rest-method="<?php echo esc_attr($rest_method); ?>"
    data-view-manage-url="<?php echo esc_attr($should_manage_url); ?>"
    <?php foreach ($container_data as $key => $value) : ?>
        data-view-<?php echo esc_attr($key) ?>="<?php echo esc_attr($value) ?>"
    <?php endforeach; ?>
    <?php if (!empty($breakpoint_pointer)) : ?>
        data-view-breakpoint-pointer="<?php echo esc_attr($breakpoint_pointer); ?>"
    <?php endif; ?>
>
    <div class="">
        <?php $this->template('components/json-ld-data'); ?>
        <?php $this->template('components/data'); ?>
        <?php $this->template('components/before'); ?>

        <!-- Your custom layout starts here -->
        <section id="primary">
            <main id="main" class="!max-w-4xl !m-auto">
                <div class="!flex !flex-row !items-center lg:!justify-center xl:!pb-0 !pt-4 !px-4 md:!pb-4 md:!pt-12 lg:!pt-20">
                    <h1 class="!italic !font-medium !text-lg sm:!text-xl md:!text-2xl xl:!text-center">Veranstaltungen</h1>
                </div>
                <section id="events-preview" class="!py-6 !px-4 sm:!py-6 md:!py-8 lg:!py-10">
                    <?php if (!empty($events)) { ?>
                        <div class="flex flex-col gap-4">
                            <?php foreach ($events as $event) : ?>
                                <?php $this->setup_postdata($event); ?>
                                <?php get_template_part('template-parts/components/event-tile'); ?>
                            <?php endforeach; ?>
                        </div>
                    <?php } else { ?>
                        <p class="!text-lg !font-medium">Aktuell sind keine Veranstaltungen geplant.</p>
                    <?php } ?>
                </section>
            </main>
        </section>
        <!-- Your custom layout ends here -->

        <?php $this->template('components/after'); ?>
    </div>
</div>
<?php $this->template('components/breakpoints'); ?>