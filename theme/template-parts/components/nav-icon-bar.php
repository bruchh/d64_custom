<div class="h-min items-center">
    <ul class="flex items-center gap-3">
        <!-- mastodon -->
        <?php
        $mastodon = get_field('mastodon', 'option');
        if ($mastodon): ?>
            <li class="flex items-center">
                <a href="<?php echo esc_url($mastodon); ?>" target="_blank" rel="noopener noreferrer">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/mdi_mastodon.svg" alt="Mastodon Icon">
                </a><br>
            </li>
        <?php endif; ?>
        <!-- twitter -->
        <?php
        $twitter = get_field('twitter', 'option');
        if ($twitter): ?>
            <li class="flex items-center">
                <a href="<?php echo esc_url($twitter); ?>" target="_blank" rel="noopener noreferrer">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/twitter.svg" alt="LinkedIn Icon">
                </a><br>
            </li>
        <?php endif; ?>    
        <!-- bluesky -->
        <?php
        $bluesky = get_field('bluesky', 'option');
        if ($bluesky): ?>
            <li class="flex items-center">
                <a href="<?php echo esc_url($bluesky); ?>" target="_blank" rel="noopener noreferrer">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/bluesky.svg" alt="Bluesky Icon">
                </a><br>
            </li>
        <?php endif; ?>    
        <!-- linkedin -->
        <?php
        $linkedin = get_field('linkedin', 'option');
        if ($linkedin): ?>
        <li class="flex items-center">
            <a href="<?php echo esc_url($linkedin); ?>" target="_blank" rel="noopener noreferrer">
                <img class="!rounded-none" src="<?php echo get_template_directory_uri(); ?>/assets/icons/linkedin.svg" alt="LinkedIn Icon">
            </a><br>
        </li>
        <?php endif; ?>    
    </ul>
</div>

