module.exports = {
	theme: {
		extend: {
			typography: () => ({
				/**
				 * Tailwind Typography’s default styles are opinionated, and
				 * you may need to override them if you have mockups to
				 * replicate. You can view the default modifiers here:
				 *
				 * https://github.com/tailwindlabs/tailwindcss-typography/blob/master/src/styles.js
				 */

				DEFAULT: {
					css: [
						{
							/**
							 * By default, max-width is set to 65 characters.
							 * This is a good default for readability, but
							 * often in conflict with client-supplied designs.
							 * A value of false removes the max-width property.
							 */
							maxWidth: false,

							/**
							 * Without Preflight, Tailwind doesn't apply a
							 * default border style of `solid` to all elements,
							 * so the border doesn't appear in the editor
							 * without this addition.
							 */
							blockquote: {
								borderLeftStyle: 'solid',
								padding: 'none',
								fontSize: '1.125rem',
								textAlign: 'left',
								color: '#021D4C',
							},

							/**
							 * Styles for the `cite` element within `blockquote`
							 * elements.
							 */
							'blockquote > cite': {
								color: 'var(--tw-prose-body)',
								fontStyle: 'normal',
								fontWeight: '400',
							},
							'blockquote > cite::before': {
								content: '"\\2014"',
							},

							/**
							 * Block editor styles use 1px borders for the top
							 * and bottom of the `hr` element. The rule below
							 * removes the bottom border, as Tailwind
							 * Typography only uses the top border.
							 */
							hr: {
								borderBottom: 'none',
							},
							h1: {
								color: '#021D4C',
								fontWeight: '500',
							},
							h2: {
								color: '#021D4C',
								fontWeight: '500',
							},
							h3: {
								color: '#021D4C',
								fontWeight: '500',
							},
							h4: {
								color: '#021D4C',
								fontWeight: '500',
							},
							h5: {
								color: '#021D4C',
								fontWeight: '500',
							},
							p: {
								color: '#021D4C',
							},
							li: {
								color: '#021D4C',
							},
							cite: {
								color: '#021D4C',
								fontWeight: '600',
								fontFamily: 'SourceSerifPro',
								fontSize: '1rem',
							},
							strong: {
								color: '#021D4C',
							},
							a: {
								color: '#021D4C',
							},
						},
					],
				},
			}),
		},
	},
};
