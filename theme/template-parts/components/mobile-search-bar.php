<div class="pt-4"></div>
<div class="w-full relative">
    <!-- Wrap input and submit button in a form -->
    <form action="<?php echo home_url( '/' ); ?>" method="get">
        <input 
            type="text" 
            name="s" 
            id="search" 
            placeholder="Suche" 
            class="w-full !h-10 rounded-full bg-white font-medium font-sm pl-4 pr-9 -mt-[3px]"
            aria-label="Search"
            autocomplete="off"
        >
        <button 
            type="submit"
            class="p-1 m-1 hover:bg-d64gray-100 rounded-full -ml-2 transition-colors absolute right-2 top-0"
            aria-label="search page"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        </button>
    </form>
    <!-- Close button remains outside the form -->
    <!-- <button 
        type="button"
        class="p-1 m-1 hover:bg-d64gray-100 rounded-full -ml-2 transition-colors absolute right-9 top-0"
        id="close-search-bar"
        aria-label="close search bar"
    >
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
    </button> -->
</div>
