<?php
/**
 * Mobile Navigation Component
 * 
 * Vollseiten Mobile Navigation mit zweistufigem System:
 * 1. Haupt-Navigation: Zeigt alle Navigation Items als Liste
 * 2. Sub-Navigation: Zeigt Untermenüs für Submenu-Items
 * 
 * Features:
 * - Animierte Ein-/Ausblendung über aria-expanded
 * - Mobile Search Bar Integration
 * - JavaScript-gesteuerter Wechsel zwischen Haupt- und Sub-Navigation
 * - ACF Options für Navigation Items
 * 
 * JavaScript Dependencies:
 * - Mobile Navigation Toggle (mobile-nav-button)
 * - Sub-Menu Navigation (submenu-nav-button)
 * - Navigation Headlines Wechsel
 * 
 * @package d64
 */
?>

<div 
    id="mobile-nav-container" 
    aria-hidden="true" 
    aria-expanded="false" 
    class="w-screen lg:hidden h-screen overflow-y-scroll flex-col fixed pt-20 px-4 inset-0 bg-black flex bg-transparent group transition-all aria-expanded:bg-d64gray-50 aria-hidden:hidden">
    
    <!-- Fixer Header-Hintergrund -->
    <div class="fixed top-0 left-0 w-full h-20 z-10 bg-d64gray-50"></div>

    <!-- Mobile Search Bar -->
    <div class="opacity-0 scale-105 group-aria-expanded:scale-100 group-aria-expanded:opacity-100 transition-all delay-100">
        <?php get_template_part('template-parts/components/mobile-search-bar'); ?>
    </div>
    
    <!-- Navigation Headline (wird per JavaScript geändert) -->
    <h2 id="nav-headline" class="text-2xl pt-4 pb-4 font-semibold scale-105 opacity-0 group-aria-expanded:scale-100 group-aria-expanded:opacity-100 transition-all delay-100 italic">
        <?php esc_html_e('NAVIGATION', 'd64'); ?>
    </h2>    
    
    <?php 
    // ACF Navigation Items aus Options laden
    $main_navigation_items = get_field('main_nav_group', 'option');
    if ($main_navigation_items): 
    ?>

    <!-- Haupt-Navigation (Top Level) -->
    <nav id="top-level-nav" 
         class="h-full flex flex-col justify-between scale-105 opacity-0 group-aria-expanded:scale-100 group-aria-expanded:opacity-100 transition-all delay-100"
         role="navigation" 
         aria-label="<?php esc_attr_e('Mobile Hauptnavigation', 'd64'); ?>">
        
        <ul class="flex flex-col w-full pb-32">
            <?php foreach ($main_navigation_items as $item): 
                
                // Navigation Item Typ prüfen und entsprechendes Markup laden
                if ($item['main_nav_item_type'] === 'Submenu') : ?>
                    <!-- Submenu Navigation Item -->
                    <li data-name="<?php echo esc_attr($item['main_nav_item_name']); ?>" 
                        class="flex flex-col justify-center py-2">
                        <button 
                            data-id="<?php echo esc_attr($item['main_nav_item_name']); ?>"
                            aria-haspopup="true"
                            aria-expanded="false"
                            class="mobile-nav-button text-xl font-regular flex justify-between items-center w-full"
                            aria-label="<?php echo esc_attr(sprintf(__('%s Untermenü öffnen', 'd64'), $item['main_nav_item_name'])); ?>">
                            <span class="pointer-events-none">
                                <?php echo esc_html($item['main_nav_item_name']); ?>
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right" aria-hidden="true">
                                <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                            </svg>
                        </button>
                    </li>
                    
                <?php elseif ($item['main_nav_item_type'] === 'Direct Link') : ?>
                    <!-- Direkter Navigation Link -->
                    <li class="py-1 border-bottom border-d64blue-900">
                        <a 
                            href="<?php echo esc_url($item['main_nav_direct_link_url']['url']); ?>"
                            <?php if ($item['main_nav_direct_link_url']['target'] === '_blank') : ?>
                                target="_blank" 
                                rel="noopener noreferrer"
                                aria-label="<?php echo esc_attr($item['main_nav_item_name'] . ' ' . __('(öffnet in neuem Tab)', 'd64')); ?>"
                            <?php endif; ?>
                            class="text-xl font-regular flex w-full justify-between items-center">
                            <?php echo esc_html($item['main_nav_item_name']); ?>
                            
                            <!-- Externes Link Icon -->
                            <?php if ($item['main_nav_direct_link_url']['target'] === '_blank') : ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-move-up-right" aria-hidden="true">
                                    <path d="M13 5H19V11"/><path d="M19 5L5 19"/>
                                </svg>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
        
        <!-- Icon Bar am Ende der Navigation -->
        <div class="pt-16 pb-32">
            <?php get_template_part('template-parts/components/nav-icon-bar'); ?>
        </div>
    </nav>

    <!-- Sub-Navigation Menüs (versteckt per Default) -->
    <?php foreach ($main_navigation_items as $item): 
        
        // Nur Submenu Items verarbeiten
        if ($item['main_nav_item_type'] === 'Submenu') : ?>
            
            <!-- Sub-Navigation Container -->
            <div 
                id="<?php echo esc_attr($item['main_nav_item_name']); ?>" 
                class="mobile-nav-sub-menu gap-4 hidden h-full flex-col scale-105 opacity-0 group-aria-expanded:scale-100 group-aria-expanded:opacity-100 transition-all delay-100"
                role="navigation"
                aria-label="<?php echo esc_attr(sprintf(__('%s Unternavigation', 'd64'), $item['main_nav_item_name'])); ?>">
                
                <div class="pb-32">
                    <?php if (!empty($item['submenus_group'])) : ?>
                        <?php foreach ($item['submenus_group'] as $subitem): ?>
                            <div class="p-2 rounded">
                                <!-- Submenu Kategorie-Überschrift -->
                                <h3 class="font-medium underline pb-1">
                                    <?php echo esc_html($subitem['submenu_headline']); ?>
                                </h3>
                                
                                <!-- Submenu Links -->
                                <ul>
                                    <?php if (!empty($subitem['links_group'])) : ?>
                                        <?php foreach ($subitem['links_group'] as $sub_item_link): ?>
                                            <li class="ml-2 flex items-center w-full justify-between">
                                                <a 
                                                    href="<?php echo esc_url($sub_item_link['link']['url']); ?>"
                                                    <?php if ($sub_item_link['link']['target'] === '_blank') : ?>
                                                        target="_blank" 
                                                        rel="noopener noreferrer"
                                                        aria-label="<?php echo esc_attr($sub_item_link['link']['title'] . ' ' . __('(öffnet in neuem Tab)', 'd64')); ?>"
                                                    <?php endif; ?>
                                                    class="py-1 text-xl font-regular w-full">
                                                    <?php echo esc_html($sub_item_link['link']['title']); ?>
                                                    <!-- Beschreibung ist auskommentiert: <?php echo esc_html($sub_item_link['link_description'] ?? ''); ?> -->
                                                </a>
                                                
                                                <!-- Externes Link Icon -->
                                                <?php if ($sub_item_link['link']['target'] === '_blank') : ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-move-up-right" aria-hidden="true">
                                                        <path d="M13 5H19V11"/><path d="M19 5L5 19"/>
                                                    </svg>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
    <?php endif; ?>
</div>