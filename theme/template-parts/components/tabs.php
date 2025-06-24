<?php
/**
 * Template Part für Tab-Navigation und Inhalte
 *
 * Wiederverwendbare Komponente für tabbasierte Inhalte.
 * Unterstützt ACF Repeater Field 'tab' mit Label und Content.
 * 
 * ACF Struktur:
 * - 'tab' (Repeater)
 *   |- 'label' (Text) - Tab-Bezeichnung
 *   |- 'content' (WYSIWYG) - Tab-Inhalt
 * 
 * Features:
 * - Responsive Tab-Navigation (horizontal scrollbar auf mobile)
 * - Auto-versteckt Tab-Navigation bei nur einem Tab
 * - JavaScript-powered Tab-Switching
 * - Unterstützt HTML-Content in Tabs
 * 
 * Verwendung:
 * get_template_part('template-parts/components/tabs');
 * 
 * Dependencies: JavaScript für Tab-Funktionalität (siehe Script-Block)
 * 
 * @package d64
 */

// ACF Tab-Daten laden
$tabs = get_field('tab');

// Früh beenden falls keine Tabs vorhanden
if (!$tabs) {
    return;
}
?>

<!-- Tabs Component -->
<section class="max-w-3xl m-auto py-10">
    <!-- check if $tabs has more than one tab -->
    <div class="max-w-2xl flex flex-col gap-8">
        <div class="tabs-container px-4 w-full overflow-x-scroll">
            <div class="tab-selector flex gap-1 <?php if (count($tabs) === 1) echo 'hidden'; ?>">
                <?php foreach ($tabs as $key => $tab) : ?>
                <button class="tab-selector__button bg-white border-2 border-d64blue-50 hover:bg-d64blue-50 rounded-full px-4 font-medium py-1 transition-colors" 
                        id="tab-button-<?php echo esc_attr($key); ?>" 
                        data-tab-id="<?php echo esc_attr($key); ?>">
                    <?php echo esc_html($tab['label']); ?>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="tab-content px-4">
            <?php foreach ($tabs as $key => $tab) : ?>
            <div class="tab-content__item hidden ml-0 prose prose-neutral prose-a:text-primary prose-h2:text-lg sm:pose-h2:text-xl prose-h2:italic" 
                 id="tab-content-<?php echo esc_attr($key); ?>">
                <h2 class="font-serif font-bold"><?php echo esc_html($tab['label']); ?></h2>
                <?php echo $tab['content']; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<style>
.tab-selector::-webkit-scrollbar,
.tabs-container::-webkit-scrollbar {
    display: none;
}
.tab-selector,
.tabs-container {
    -ms-overflow-style: none; /* IE and Edge */
    scrollbar-width: none; /* Firefox */
}
</style>

<!-- Original Tab JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tabs
    const tabButtons = document.querySelectorAll('.tab-selector__button');
    const tabContents = document.querySelectorAll('.tab-content__item');
    
    if (tabButtons.length && tabContents) {
        // Initialize
        tabButtons[0].setAttribute('aria-pressed', 'true');
        tabButtons[0].classList.remove('border-d64blue-50');
        tabButtons[0].classList.add('border-d64blue-900');
        tabContents[0].classList.remove('hidden');
        tabContents[0].classList.add('block');
        
        tabButtons.forEach((button) => {
            button.addEventListener('click', (event) => {
                const tabId = event.target.getAttribute('data-tab-id');
                
                // Update aria-pressed and hide all tabs
                tabButtons.forEach((button) =>
                    button.setAttribute('aria-pressed', 'false')
                );
                tabButtons.forEach((button) => {
                    if (button.classList.contains('border-d64blue-900')) {
                        button.classList.remove('border-d64blue-900');
                        button.classList.add('border-d64blue-50');
                    }
                });
                tabContents.forEach((content) => content.classList.remove('block'));
                tabContents.forEach((content) => {
                    if (!content.classList.contains('hidden')) {
                        content.classList.add('hidden');
                    }
                });
                
                // Show the clicked tab and set aria-pressed to true
                document
                    .getElementById(`tab-content-${tabId}`)
                    .classList.remove('hidden');
                document
                    .getElementById(`tab-content-${tabId}`)
                    .classList.add('block');
                document
                    .getElementById(`tab-button-${tabId}`)
                    .classList.remove('border-d64blue-50');
                document
                    .getElementById(`tab-button-${tabId}`)
                    .classList.add('border-d64blue-900');
                document
                    .getElementById(`tab-button-${tabId}`)
                    .setAttribute('aria-pressed', 'true');
            });
        });
    }
});
</script>