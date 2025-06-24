// Set the Preflight flag based on the build target.
const includePreflight = 'editor' === process.env._TW_TARGET ? false : true;
// const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
	presets: [
		// Manage Tailwind Typography's configuration in a separate file.
		require('./tailwind-typography.config.js'),
	],
	content: [
		// Ensure changes to PHP files and `theme.json` trigger a rebuild.
		'./theme/**/*.php',
		'./theme/theme.json',
	],
	theme: {
		// Extend the default Tailwind theme.
		// See: https://tailwindcss.com/docs/theme
		extend: {
			colors: {
				d64blue: {
					50: '#EDF2F4',
					100: '#9FD2F4',
					500: '#00B7FF',
					700: '#016AA6',
					900: '#021D4C',
				},
				d64gray: {
					50: '#F2F2F3',
					100: '#D3D5D6',
					500: '#A2A5A8',
					700: '#6B6E72',
					900: '#232526',
				},
			},
			fontFamily: {
				sans: ['Barlow'],
				serif: ['SourceSerifPro'],
			},
		},
	},
	corePlugins: {
		// Disable Preflight base styles in builds targeting the editor.
		preflight: includePreflight,
	},
	plugins: [
		// function ({ addBase, config }) {
		// 	addBase({
		// 		img: {
		// 			borderRadius: config('theme.borderRadius.lg'), // Adds a large border-radius to all images
		// 		},
		// 	});
		// },
		// Extract colors and widths from `theme.json`.
		require('@_tw/themejson')(require('../theme/theme.json')),

		// Add Tailwind Typography.
		require('@tailwindcss/typography'),

		// Uncomment below to add additional first-party Tailwind plugins.
		require('@tailwindcss/forms'),
		// require('@tailwindcss/aspect-ratio'),
		require('@tailwindcss/container-queries'),
	],
};
