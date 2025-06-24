<?php
/**
 * Desktop Navigation Component
 * 
 * Lädt die Hauptnavigation für Desktop-Geräte aus ACF Options.
 * Unterstützt zwei Arten von Navigation-Items:
 * - Submenu: Dropdown mit 4-Spalten-Grid und Unterkategorien
 * - Direct Link: Direkter Link ohne Dropdown
 * 
 * @package d64
 */

// Konstanten für wiederkehrende Werte
define('D64_NAV_MAX_WIDTH', '1200px');
define('D64_NAV_GRID_COLS', 'grid-cols-4');
?>

<div id="desktop-nav-container" class="w-max relative px-2">   
    <?php 
    // ACF Navigation Items aus Options laden
    $main_navigation_items = get_field('main_nav_group', 'option');
    if ($main_navigation_items): 
    ?>

    <!-- Hauptnavigation für Desktop -->
    <nav id="desktop-top-level-nav" role="navigation" aria-label="<?php esc_attr_e('Hauptnavigation', 'd64'); ?>">
        <ul class="flex flex-row w-full gap-8 items-center font-medium">
            <?php foreach ($main_navigation_items as $item): 
                
                // Navigation-Item Typ prüfen und entsprechendes Markup laden
                if ($item['main_nav_item_type'] === 'Submenu') : 
                    $submenu_id = sanitize_title($item['main_nav_item_name']) . '-desktop';
                ?>
                    <!-- Submenu Navigation Item -->
                    <li data-name="<?php echo esc_attr($item['main_nav_item_name']); ?>" class="relative">
                        <button 
                            data-id="<?php echo esc_attr($submenu_id); ?>"
                            aria-haspopup="true"
                            aria-expanded="false"
                            aria-controls="<?php echo esc_attr($submenu_id); ?>"
                            class="flex z-50 gap-1 items-center desktop-nav-btn group hover:bg-d64blue-50 rounded-lg -mx-2 m-max py-1 px-2 aria-expanded:bg-d64blue-50 transition-colors">
                            <span class="pointer-events-none">
                                <?php echo esc_html($item['main_nav_item_name']); ?>
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down group-aria-expanded:rotate-180 transition-transform" aria-hidden="true">
                                <path d="m6 9 6 6 6-6"/>
                            </svg>
                        </button>    
                        
                        <!-- Submenu Dropdown Container -->
                        <div 
                            id="<?php echo esc_attr($submenu_id); ?>" 
                            class="desktop-nav-sub-menu fixed hidden w-screen left-0 top-0 right-0 -z-10 group"
                            aria-hidden="true"
                            aria-labelledby="<?php echo esc_attr($submenu_id); ?>-button"
                            role="menu">
                            <div class="bg-d64gray-50 bg-opacity-0 group-aria-[hidden=false]:bg-opacity-100 pt-24 px-4 pb-16 w-full transition-all">
                                <div class="max-w-[<?php echo D64_NAV_MAX_WIDTH; ?>] m-auto px-4 grid <?php echo esc_attr(D64_NAV_GRID_COLS); ?> gap-4 lg:gap-6 xl:gap-8">
                                    
                                    <?php if (!empty($item['submenus_group'])) : ?>
                                        <?php foreach ($item['submenus_group'] as $subitem): ?>
                                            <div class="pt-12">
                                                <!-- Submenu Kategorie-Überschrift -->
                                                <h3 class="font-serif pb-3 opacity-0 group-aria-[hidden=false]:opacity-100 transition-opacity delay-75">
                                                    <?php echo esc_html($subitem['submenu_headline']); ?>
                                                </h3>
                                                
                                                <!-- Submenu Links -->
                                                <nav role="menu">
                                                    <ul class="flex flex-col gap-2">
                                                        <?php if (!empty($subitem['links_group'])) : ?>
                                                            <?php foreach ($subitem['links_group'] as $sub_item_link): ?>
                                                                <li class="rounded-xl flex items-center hover:bg-white transition-colors" role="menuitem">
                                                                    <a 
                                                                        href="<?php echo esc_url($sub_item_link['link']['url']); ?>"
                                                                        <?php if ($sub_item_link['link']['target'] === '_blank') : ?>
                                                                            target="_blank" 
                                                                            rel="noopener noreferrer"
                                                                            aria-label="<?php echo esc_attr($sub_item_link['link']['title'] . ' ' . __('(öffnet in neuem Tab)', 'd64')); ?>"
                                                                        <?php endif; ?>
                                                                        class="text-base px-2 py-1 flex flex-col opacity-0 group-aria-[hidden=false]:opacity-100 transition-all delay-100">
                                                                        
                                                                        <span class="flex gap-1 items-center">
                                                                            <span class="font-medium">
                                                                                <?php echo esc_html($sub_item_link['link']['title']); ?>
                                                                            </span>
                                                                            
                                                                            <!-- Externes Link Icon -->
                                                                            <?php if ($sub_item_link['link']['target'] === '_blank') : ?>
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-move-up-right" aria-hidden="true">
                                                                                    <path d="M13 5H19V11"/><path d="M19 5L5 19"/>
                                                                                </svg>
                                                                            <?php endif; ?>
                                                                        </span>
                                                                        
                                                                        <!-- Link Beschreibung -->
                                                                        <?php if (!empty($sub_item_link['link_description'])) : ?>
                                                                            <span class="font-thin">
                                                                                <?php echo esc_html($sub_item_link['link_description']); ?>
                                                                            </span>
                                                                        <?php endif; ?>
                                                                    </a>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </ul>
                                                </nav>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </li>

                <?php elseif ($item['main_nav_item_type'] === 'Direct Link') : ?>
                    <!-- Direkter Navigation Link -->
                    <li class="z-50">
                        <a 
                            href="<?php echo esc_url($item['main_nav_direct_link_url']['url']); ?>"
                            <?php if ($item['main_nav_direct_link_url']['target'] === '_blank') : ?>
                                target="_blank" 
                                rel="noopener noreferrer"
                                aria-label="<?php echo esc_attr($item['main_nav_item_name'] . ' ' . __('(öffnet in neuem Tab)', 'd64')); ?>"
                            <?php endif; ?>
                            class="hover:bg-d64blue-50 rounded-lg -mx-2 flex m-max py-1 px-2 items-center gap-1 group">
                            <span>
                                <?php echo esc_html($item['main_nav_item_name']); ?>
                            </span>
                            
                            <!-- Externes Link Icon -->
                            <?php if ($item['main_nav_direct_link_url']['target'] === '_blank') : ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-move-up-right" aria-hidden="true">
                                    <path d="M13 5H19V11"/><path d="M19 5L5 19"/>
                                </svg>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
            
            <!-- English Link nur auf Homepage anzeigen -->
            <?php if (is_front_page()) : ?>
                <li class="z-50">
                    <a 
                        href="<?php echo esc_url(home_url('/english')); ?>"
                        class="hover:bg-d64blue-50 rounded-lg -mx-2 flex m-max py-1 px-2 items-center gap-1 group"
                        aria-label="<?php esc_attr_e('Zur englischen Version wechseln', 'd64'); ?>">
                        <span>
                            <?php esc_html_e('EN', 'd64'); ?>
                        </span>
                    </a>
                </li>
            <?php endif; ?>
            
            <!-- Such-Button -->
            <li>
                <button 
                    class="px-2 py-2 hover:bg-d64blue-50 rounded-lg -ml-2 transition-colors"
                    aria-expanded="false"
                    aria-haspopup="true"
                    aria-label="<?php esc_attr_e('Suche öffnen', 'd64'); ?>"
                    id="toggle-search-bar-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                    </svg>
                </button>
            </li>
        </ul>
    </nav>
    <?php endif; ?>
</div>