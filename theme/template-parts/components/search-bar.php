<?php
/**
 * Search Bar Component
 * 
 * Vollseiten-Suchleiste die über JavaScript ein-/ausgeblendet wird.
 * Wird durch Button in der Desktop Navigation aktiviert (#toggle-search-bar-btn).
 * 
 * JavaScript Dependencies:
 * - Toggle-Funktionalität über #toggle-search-bar-btn
 * - Schließen über #close-search-bar und Escape-Taste
 * - AJAX Search Results (optional)
 * 
 * @package d64
 */
?>

<div id="full-page-search" class="absolute hidden w-full left-0 top-0 right-0 z-50">
    <div id="ajax-search-form" class="absolute w-full left-0 top-0 right-0 z-50">
        
        <!-- Search Form -->
        <form action="<?php echo esc_url(home_url('/')); ?>" method="get" role="search">
            <input
                type="text"
                name="s"
                id="search"
                placeholder="<?php esc_attr_e('Suche', 'd64'); ?>"
                class="w-full !h-10 rounded-full bg-d64blue-50 font-medium font-sm pl-4 pr-9 -mt-[3px]"
                aria-label="<?php esc_attr_e('Suchbegriff eingeben', 'd64'); ?>"
                autocomplete="off"
                value="<?php echo esc_attr(get_search_query()); ?>">
            
            <!-- Search Submit Button -->
            <button
                type="submit"
                class="p-1 m-1 hover:bg-d64gray-100 rounded-full -ml-2 transition-colors absolute right-2 top-0"
                aria-label="<?php esc_attr_e('Suche starten', 'd64'); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search" aria-hidden="true">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.3-4.3"/>
                </svg>
            </button>
        </form>
        
        <!-- Close Search Button (außerhalb des Forms) -->
        <button
            type="button"
            class="p-1 m-1 hover:bg-d64gray-100 rounded-full -ml-2 transition-colors absolute right-9 top-0"
            id="close-search-bar"
            aria-label="<?php esc_attr_e('Suche schließen', 'd64'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x" aria-hidden="true">
                <path d="M18 6 6 18"/>
                <path d="m6 6 12 12"/>
            </svg>
        </button>
    </div>
</div>