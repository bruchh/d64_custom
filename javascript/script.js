/**
 * The JavaScript code you place here will be processed by esbuild, and the
 * output file will be created at `../theme/js/script.min.js` and enqueued by
 * default in `../theme/functions.php`.
 *
 * For esbuild documentation, please see:
 * https://esbuild.github.io/
 */

// create a event listener on click
document.addEventListener('click', function (event) {
	// Navigation for link-tiles linktiles
	// when clicked, check if the target has the class of link-tile or is nested inside of a link-tile
	if (
		event.target.classList.contains('link-tile') ||
		event.target.closest('.link-tile')
	) {
		let linkTile = event.target.closest('.link-tile');
		// get the url of the first a tag inside of the link-tile
		const url = linkTile.querySelector('a').href;
		// navigate to the url
		if (url) {
			window.location = url;
		}
	}
	if (event.target.id === 'close-search-overlay') {
		closeSearchBar();
	}
});

// Toggle the filter menu on mobile for blog page
const filterMenuButton = document.querySelector('#toggle-filter-menu');
const filterMenu = document.querySelector('#filter-menu');
if (filterMenuButton && filterMenu) {
	filterMenuButton.addEventListener('click', () => {
		const isExpanded =
			filterMenuButton.getAttribute('aria-expanded') === 'true';

		// Toggle aria-expanded
		filterMenuButton.setAttribute('aria-expanded', !isExpanded);

		// Show or hide the menu
		filterMenu.classList.toggle('hidden');

		// Optional: manage focus
		if (!isExpanded) {
			filterMenu.querySelector('a').focus();
		} else {
			filterMenuButton.focus();
		}
	});
}

// MOBILE NAV MENU
// Toggle the navigation menu on mobile
const navMenuButton = document.querySelector('#toggle-nav-menu');
const navigationContainer = document.getElementById('mobile-nav-container');
let activeSubMenu = null;
let currentSubButton = null;

function setNavigationAttributes(isOpen) {
	const status = isOpen ? 'true' : 'false';
	navMenuButton.setAttribute('aria-pressed', status);
	setTimeout(() => {
		navigationContainer.setAttribute('aria-expanded', status);
	}, 50);
	if (isOpen) {
		navigationContainer.removeAttribute('aria-hidden');
	} else {
		navigationContainer.setAttribute('aria-hidden', 'true');
	}
	resetMobileNav();
}

// Handle the Escape key
function handleEscape(event) {
	if (event.key === 'Escape' || event.keyCode === 27) {
		if (navMenuButton.getAttribute('aria-pressed') === 'true') {
			setNavigationAttributes(false);
			document.removeEventListener('keydown', handleEscape);
		}
	}
}

// Toggle the navigation menu on mobile
if (navMenuButton) {
	navMenuButton.addEventListener('click', () => {
		const isOpen = navMenuButton.getAttribute('aria-pressed') === 'true';
		setNavigationAttributes(!isOpen);

		if (!isOpen) {
			// Menu is being opened, so add the Escape key listener
			document.addEventListener('keydown', handleEscape);
			document.body.classList.add('overflow-hidden');
			document
				.getElementById('header-header')
				.classList.add('bg-d64gray-50');
		} else {
			// Menu is being closed, so remove the Escape key listener
			document.removeEventListener('keydown', handleEscape);
			document.body.classList.remove('overflow-hidden');
			document
				.getElementById('header-header')
				.classList.remove('bg-d64gray-50');
		}
	});
}

// mobile navigation menu functionality
document.addEventListener('click', function (event) {
	if (
		event.target.classList.contains('mobile-nav-button') ||
		event.target.closest('.mobile-nav-button')
	) {
		activeSubMenu = event.target.closest('.mobile-nav-button').dataset.id;
		currentSubButton = event.target.closest('.mobile-nav-button');
		event.target.setAttribute('aria-expanded', 'true');
		changeNavHeadline(activeSubMenu);
		displaySubMenu(activeSubMenu);
		toggleTopLevelNav('hide');
	} else if (event.target.id === 'submenu-nav-button') {
		resetMobileNav();
	}
});

// reset Mobile Nav
function resetMobileNav() {
	activeSubMenu = null;
	changeNavHeadline('NAVIGATION');
	toggleTopLevelNav('display');
	hideAllSubMenus();
	if (currentSubButton) {
		currentSubButton.setAttribute('aria-expanded', 'false');
	}
}

// change the headline of the mobile nav
function changeNavHeadline(newHeadline) {
	let navHeadline = document.querySelector('#nav-headline');
	navHeadline.innerHTML = newHeadline.toUpperCase();
}

// display sub menu
function displaySubMenu(subMenu) {
	// select element with data-id subMenu
	let subMenuElement = document.getElementById(subMenu);
	subMenuElement.classList.remove('hidden');
	subMenuElement.classList.add('flex');
	document.getElementById('sub-nav-btn-container').classList.remove('hidden');
	document.getElementById('sub-nav-btn-container').classList.add('flex');
	document.getElementById('main-logo').classList.add('opacity-0');
	document.getElementById('main-logo').classList.add('pointer-events-none');
}

// hide all sub menus
function hideAllSubMenus() {
	console.log('hide all sub menus');
	let subMenus = document.querySelectorAll('.mobile-nav-sub-menu');
	if (subMenus.length === 0) return;
	subMenus.forEach((subMenu) => {
		subMenu.classList.remove('flex');
		subMenu.classList.add('hidden');
	});
	document.getElementById('sub-nav-btn-container').classList.remove('flex');
	document.getElementById('sub-nav-btn-container').classList.add('hidden');
	document.getElementById('main-logo').classList.remove('opacity-0');
	document
		.getElementById('main-logo')
		.classList.remove('pointer-events-none');
}

// hide the top level navigation when button is pressed
function toggleTopLevelNav(status) {
	let topLevelNav = document.querySelector('#top-level-nav');
	if (status === 'hide') {
		topLevelNav.classList.add('hidden');
		topLevelNav.classList.remove('flex');
	} else if (status === 'display') {
		topLevelNav.classList.add('flex');
		topLevelNav.classList.remove('hidden');
	}
}

// DESKTOP NAV MENU
let navButtons = document.querySelectorAll('.desktop-nav-btn');
if (navButtons) {
	navButtons.forEach((button) => {
		button.addEventListener('click', function () {
			let expanded = this.getAttribute('aria-expanded') === 'true';

			// If the current submenu is expanded, collapse it and return
			if (expanded) {
				this.setAttribute('aria-expanded', 'false');
				let navMenu = document.getElementById(button.dataset.id);
				navMenu.classList.remove('flex');
				navMenu.classList.add('hidden');
				navMenu.setAttribute('aria-hidden', 'true');
				return;
			}

			// First, close all open submenus except the current one
			closeAllSubMenus(this);

			// Then, open the intended submenu
			this.setAttribute('aria-expanded', 'true');
			let navMenu = document.getElementById(button.dataset.id);
			navMenu.classList.remove('hidden');
			navMenu.classList.add('flex');
			setTimeout(() => {
				navMenu.setAttribute('aria-hidden', 'false');
			}, 20);
		});
	});
}

function closeAllSubMenus(exceptButton) {
	navButtons.forEach((button) => {
		if (button === exceptButton) return; // Skip the button that's currently being clicked

		let navMenu = document.getElementById(button.dataset.id);
		navMenu.classList.remove('flex');
		navMenu.classList.add('hidden');
		button.setAttribute('aria-expanded', 'false');
	});
}

// Add padding to the #header-content to account for the fixed header
document.addEventListener('DOMContentLoaded', function () {
	if (
		document.getElementById('header-content') &&
		document.getElementById('wpadminbar')
	) {
		const padding = document.getElementById('wpadminbar').offsetHeight;
		document.getElementById('header-content').style.paddingTop =
			padding + 'px';
		if (navigationContainer) {
			navigationContainer.style.marginTop = padding + 'px';
		}
	}
});

// Full page search

// Get references to the elements
const toggleSearchBtn = document.getElementById('toggle-search-bar-btn');
const fullPageSearch = document.getElementById('full-page-search');

if (toggleSearchBtn && fullPageSearch) {
	// Add event listener to the button
	toggleSearchBtn.addEventListener('click', function () {
		// Check current state
		let isExpanded = this.getAttribute('aria-expanded') === 'true';

		// Toggle visibility of the search bar
		if (isExpanded) {
			fullPageSearch.style.display = 'none';
		} else {
			fullPageSearch.style.display = 'block';

			// (Optional) Focus the search input when it's shown
			const searchInput = fullPageSearch.querySelector('#search');
			if (searchInput) {
				searchInput.focus();
			}
		}

		// Update aria-expanded attribute
		this.setAttribute('aria-expanded', !isExpanded);

		// Listen for escape key
		document.addEventListener('keydown', function (event) {
			if (event.key === 'Escape' || event.keyCode === 27) {
				closeSearchBar();
			}
		});
	});
}

// Close search bar logic
const closeSearchBarBtn = document.getElementById('close-search-bar');
if (closeSearchBarBtn) {
	closeSearchBarBtn.addEventListener('click', () => closeSearchBar());
}

function closeSearchBar() {
	// remove the value from the search input
	fullPageSearch.querySelector('#search').value = '';
	fullPageSearch.style.display = 'none';
	toggleSearchBtn.setAttribute('aria-expanded', 'false');
	let searchResultOverlay = document.getElementById('search-result-overlay');
	if (searchResultOverlay) {
		searchResultOverlay.classList.add('hidden');
		searchResultOverlay.classList.remove('fixed');
	}
}

// AJAX BLOG SCRIPTS

document.addEventListener('DOMContentLoaded', function () {
	let filterButtons = document.querySelectorAll('.filter-button');
	let searchText = document.getElementById('text-search-input');
	let paginationContainer = document.getElementById('pagination-container');

	// Check for author parameter in the URL
	const urlParams = new URLSearchParams(window.location.search);
	const authorParam = urlParams.get('author_id');
	const categoryParam = urlParams.get('category_id');

	// Global State
	let currentState = {
		categories: [],
		authors: [],
		search: '',
		paged: 1,
	};

	// Update Global State Function
	function updateState(newData) {
		currentState = { ...currentState, ...newData };
	}

	// NEW FUNCTION: Update URL parameters without page refresh
	function updateUrlParams() {
		// Create URL object with current URL
		let url = new URL(window.location);

		// Clear existing parameters
		url.searchParams.delete('author_id');
		url.searchParams.delete('category_id');
		url.searchParams.delete('search');
		url.searchParams.delete('paged');

		// Add current filter parameters
		currentState.authors.forEach((author) => {
			url.searchParams.append('author_id', author);
		});

		currentState.categories.forEach((category) => {
			url.searchParams.append('category_id', category);
		});

		// Add search parameter if not empty
		if (currentState.search) {
			url.searchParams.set('search', currentState.search);
		}

		// Add page parameter if not on page 1
		if (currentState.paged > 1) {
			url.searchParams.set('paged', currentState.paged);
		}

		// Update URL without refreshing page
		window.history.pushState({ state: currentState }, '', url);
	}

	// NEW FUNCTION: Reset UI to match current state
	function resetUIFromState() {
		// Reset all buttons first
		filterButtons.forEach((button) => {
			button.classList.remove('active');
			button.classList.remove('border-d64blue-900');
			button.classList.add('border-white');
			button.setAttribute('aria-pressed', 'false');
		});

		// Highlight buttons based on current state
		filterButtons.forEach((button) => {
			const filterType = button.getAttribute('data-filter-type');
			const dataId = button.getAttribute('data-id');

			if (
				filterType === 'category' &&
				currentState.categories.includes(dataId)
			) {
				button.classList.add('active');
				button.classList.add('border-d64blue-900');
				button.classList.remove('border-white');
				button.setAttribute('aria-pressed', 'true');
			} else if (
				filterType === 'author' &&
				currentState.authors.includes(dataId)
			) {
				button.classList.add('active');
				button.classList.add('border-d64blue-900');
				button.classList.remove('border-white');
				button.setAttribute('aria-pressed', 'true');
			}
		});

		// Update search input if it exists
		if (searchText) {
			searchText.value = currentState.search;
		}
	}

	function fetchFilteredPosts() {
		// Use AJAX to send current state data to our PHP function
		jQuery.ajax({
			type: 'POST',
			url: my_ajax_object.ajax_url,
			data: {
				action: 'fetch_filtered_posts',
				...currentState,
			},
			dataType: 'json',
			success: function (response) {
				let postsContainer = document.getElementById('posts-container');

				if (postsContainer) {
					postsContainer.innerHTML = response.content;
				}
				if (paginationContainer) {
					paginationContainer.innerHTML = response.pagination;
				}

				// Re-attach click event to new pagination links after each AJAX call
				attachPaginationEvents();
			},
		});
	}

	function attachPaginationEvents() {
		if (!paginationContainer) return;
		let paginationLinks = paginationContainer.querySelectorAll('a');
		paginationLinks.forEach((link) => {
			link.addEventListener('click', function (event) {
				event.preventDefault();
				let pageNumber = parseInt(
					new URL(link.href).searchParams.get('paged')
				);
				updateState({ paged: pageNumber });
				fetchFilteredPosts();
				// NEW: Update URL after pagination change
				updateUrlParams();
			});
		});
	}

	filterButtons.forEach((button) => {
		button.addEventListener('click', function () {
			if (this.classList.contains('active')) {
				this.classList.remove('active');
				this.classList.remove('border-d64blue-900');
				this.classList.add('border-white');
				this.setAttribute('aria-pressed', 'false');
			} else {
				this.classList.add('active');
				this.classList.add('border-d64blue-900');
				this.classList.remove('border-white');
				this.setAttribute('aria-pressed', 'true');
			}

			// Update global state and reset pagination
			let selectedCategories = currentState.categories;
			let selectedAuthors = currentState.authors;
			if (button.getAttribute('data-filter-type') === 'category') {
				let catIndex = selectedCategories.indexOf(
					button.getAttribute('data-id')
				);
				if (catIndex > -1) {
					selectedCategories.splice(catIndex, 1);
				} else {
					selectedCategories.push(button.getAttribute('data-id'));
				}
			} else if (button.getAttribute('data-filter-type') === 'author') {
				let authIndex = selectedAuthors.indexOf(
					button.getAttribute('data-id')
				);
				if (authIndex > -1) {
					selectedAuthors.splice(authIndex, 1);
				} else {
					selectedAuthors.push(button.getAttribute('data-id'));
				}
			}
			updateState({
				categories: selectedCategories,
				authors: selectedAuthors,
				paged: 1, // Reset to the first page whenever filters change
			});
			fetchFilteredPosts();
			// NEW: Update URL after filter change
			updateUrlParams();
		});
	});

	// Handle search input
	if (searchText) {
		searchText.addEventListener('input', () => {
			setTimeout(() => {
				updateState({ search: searchText.value, paged: 1 });
				fetchFilteredPosts();
				// NEW: Update URL after search
				updateUrlParams();
			}, 1000);
		});
	}

	if (authorParam || categoryParam) {
		// Update the state with the URL parameters
		updateState({
			authors: authorParam ? [authorParam] : [],
			categories: categoryParam ? [categoryParam] : [],
			paged: 1,
		});
		fetchFilteredPosts();

		let activeButtonIds = [];
		activeButtonIds.push(
			authorParam && authorParam,
			categoryParam && categoryParam
		);
		highlightButton(activeButtonIds);
	} else {
		// Load posts normally if no author parameter is present
		fetchFilteredPosts();
	}
	attachPaginationEvents();
});

// Handle browser back/forward navigation
// This goes OUTSIDE the DOMContentLoaded event listener
window.addEventListener('popstate', function (event) {
	// Re-read URL parameters
	const urlParams = new URLSearchParams(window.location.search);
	const authorParams = urlParams.getAll('author_id');
	const categoryParams = urlParams.getAll('category_id');
	const searchParam = urlParams.get('search') || '';
	const pagedParam = parseInt(urlParams.get('paged') || '1');

	// Get references to DOM elements since we're outside DOMContentLoaded
	let filterButtons = document.querySelectorAll('.filter-button');
	let searchText = document.getElementById('text-search-input');

	// Create a state object from URL params
	let newState = {
		authors: authorParams,
		categories: categoryParams,
		search: searchParam,
		paged: pagedParam,
	};

	// Update global state - calling the function via window
	// since we're outside the DOMContentLoaded closure
	if (window.updateState) {
		window.updateState(newState);
	} else {
		console.warn('updateState function not available globally');
	}

	// Reset UI to match state - simplified version since we're outside DOMContentLoaded
	// Reset buttons
	filterButtons.forEach((button) => {
		const filterType = button.getAttribute('data-filter-type');
		const dataId = button.getAttribute('data-id');

		const isActive =
			(filterType === 'category' && categoryParams.includes(dataId)) ||
			(filterType === 'author' && authorParams.includes(dataId));

		button.classList.toggle('active', isActive);
		button.classList.toggle('border-d64blue-900', isActive);
		button.classList.toggle('border-white', !isActive);
		button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
	});

	// Update search box
	if (searchText) {
		searchText.value = searchParam;
	}

	// Trigger post fetch
	if (window.fetchFilteredPosts) {
		window.fetchFilteredPosts();
	} else {
		console.warn('fetchFilteredPosts function not available globally');
		// Fallback - reload the page
		window.location.reload();
	}
});

function highlightButton(ids) {
	for (let id of ids) {
		let button = document.querySelector(`[data-id="${id}"]`);
		if (button) {
			button.classList.add('active');
			button.classList.add('border-d64blue-900');
			button.classList.remove('border-white');
			button.setAttribute('aria-pressed', 'true');
		}
	}
}

// AJAX PAGE SEARCH

const searchInput = document.getElementById('search');

let totalResults;

searchInput.addEventListener('input', function (e) {
	e.preventDefault();

	const query = e.target.value;

	if (query.length > 2) {
		searchPosts(query);
		// searchPersonen(query);
		searchPages(query);

		let searchResultOverlay = document.getElementById(
			'search-result-overlay'
		);

		if (searchResultOverlay) {
			// show search result overlay
			// delay search result overlay to prevent flickering
			setTimeout(function () {
				searchResultOverlay.classList.remove('hidden');
				searchResultOverlay.classList.add('fixed');
			}, 500);
			// get width of search input
			let searchInputWidth = searchInput.offsetWidth;
			// set width of search result overlay
			searchResultOverlay.style.width = searchInputWidth + 'px';
		}
	}
});

const postsPerPage = 10; // Set as per your requirements

function searchPosts(query, page = 1) {
	fetch(
		`/wp-json/wp/v2/posts?search=${query}&per_page=${postsPerPage}&page=${page}`
	)
		.then((response) => {
			const totalPages = response.headers.get('X-WP-TotalPages');
			displayPagination(totalPages, 'post-pagination', page, (newPage) =>
				searchPosts(query, newPage)
			);
			return response.json();
		})
		.then((data) => {
			displayResults(data, 'post-results');
		});
}

function searchPages(query, page = 1) {
	fetch(
		`/wp-json/wp/v2/pages?search=${query}&per_page=${postsPerPage}&page=${page}`
	)
		.then((response) => {
			const totalPages = response.headers.get('X-WP-TotalPages');
			displayPagination(totalPages, 'post-pagination', page, (newPage) =>
				searchPosts(query, newPage)
			);
			return response.json();
		})
		.then((data) => {
			displayResults(data, 'page-results');
		});
}

function displayResults(data, containerId) {
	const container = document.getElementById(containerId);
	container.innerHTML = ''; // Clear any previous results

	if (data.length > 0) {
		container.parentElement.classList.remove('hidden');
		data.forEach((item) => {
			container.innerHTML += `<div class="result-item pb-2">
                <a class="font-serif hover:underline" href="${item.link}">${item.title.rendered}</a>
            </div>`;
		});
	} else {
		// hide parent of container if no results
		container.parentElement.classList.add('hidden');
	}

	// Show no-results div if total results is 0
	const noResults = document.getElementById('no-results');
	if (
		document.getElementById('post-results').childElementCount === 0 &&
		document.getElementById('page-results').childElementCount === 0
	) {
		noResults.classList.remove('hidden');
	} else {
		noResults.classList.add('hidden');
	}
}

function displayPagination(totalPages, containerId, currentPage, callback) {
	const container = document.getElementById(containerId);
	container.innerHTML = '';

	if (totalPages <= 1) {
		document.getElementById(containerId).classList.add('hidden');
	}

	for (let i = 1; i <= totalPages; i++) {
		const pageLink = document.createElement('a');
		pageLink.href = '#';
		pageLink.innerHTML = i;

		if (i === currentPage) {
			pageLink.classList.add('font-bold', 'underline');
		}

		pageLink.addEventListener('click', function (e) {
			e.preventDefault();
			callback(i);
		});
		container.appendChild(pageLink);
	}
}

// ACCORDION
// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function () {
	// console.log('DOM loaded, looking for accordions');

	// Get all accordion headers
	const accordionHeaders = document.querySelectorAll('.d64-accordion-header');
	// console.log('Found ' + accordionHeaders.length + ' accordion headers');

	// Log each header found for debugging
	accordionHeaders.forEach(function (header, index) {
		// console.log('Header ' + index + ' ID: ' + header.id);
		// console.log(
		// 	'Header ' +
		// 		index +
		// 		' controls: ' +
		// 		header.getAttribute('aria-controls')
		// );

		// Log if we can find the content element
		const contentId = header.getAttribute('aria-controls');
		const content = document.getElementById(contentId);
		// console.log('Content element found: ' + (content ? 'Yes' : 'No'));

		// Add a data attribute for easier debugging in browser
		header.setAttribute('data-debug-id', 'header-' + index);

		// Direct onclick handler - simpler version
		header.onclick = function () {
			// console.log(
			// 	'Header clicked: ' + this.getAttribute('data-debug-id')
			// );

			const contentId = this.getAttribute('aria-controls');
			const content = document.getElementById(contentId);

			if (!content) {
				console.error('Content element not found for ID: ' + contentId);
				return;
			}

			//console.log('Current display style: ' + content.style.display);

			// Simple toggle
			if (
				content.style.display === 'none' ||
				content.style.display === ''
			) {
				content.style.display = 'block';
				//console.log('Setting display to block');
			} else {
				content.style.display = 'none';
				//console.log('Setting display to none');
			}

			// Log the new state
			//console.log('New display style: ' + content.style.display);
		};
	});
});
