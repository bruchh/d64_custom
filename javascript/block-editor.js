(() => {
	// javascript/block-editor.js
	wp.domReady(() => {
		wp.blocks.registerBlockType('d64/location-map', {
			title: 'Location Map',
			icon: 'location-alt',
			category: 'common',
			attributes: {
				geojsonFile: {
					type: 'string',
					default: '',
				},
				zoom: {
					type: 'number',
					default: 12,
				},
				address: {
					type: 'string',
					default: 'Berlin',
				},
			},
			edit: function (props) {
				const { attributes, setAttributes } = props;
				const inspectorControls = wp.element.createElement(
					wp.blockEditor.InspectorControls,
					{},
					wp.element.createElement(
						wp.components.PanelBody,
						{ title: 'Map Settings' },
						[
							wp.element.createElement(
								wp.components.SelectControl,
								{
									label: 'GeoJSON File',
									value: attributes.geojsonFile,
									options: [
										{ label: 'Select a file', value: '' },
										{
											label: 'Locations',
											value: 'locations',
										},
									],
									onChange: (value) =>
										setAttributes({ geojsonFile: value }),
								}
							),
							wp.element.createElement(
								wp.components.RangeControl,
								{
									label: 'Zoom Level',
									value: attributes.zoom,
									onChange: (value) =>
										setAttributes({ zoom: value }),
									min: 1,
									max: 18,
								}
							),
							wp.element.createElement(
								wp.components.TextControl,
								{
									label: 'Center Address',
									value: attributes.address,
									onChange: (value) =>
										setAttributes({ address: value }),
								}
							),
						]
					)
				);
				const blockPreview = wp.element.createElement(
					'div',
					{ className: 'p-4 bg-gray-100 border rounded' },
					[
						wp.element.createElement(
							'div',
							{ className: 'font-medium mb-2' },
							'Location Map'
						),
						wp.element.createElement(
							'div',
							{ className: 'text-sm text-gray-500' },
							attributes.geojsonFile
								? `Map centered at ${attributes.address} with zoom level ${attributes.zoom}`
								: 'Please select a GeoJSON file in the sidebar'
						),
					]
				);
				return wp.element.createElement('div', {}, [
					inspectorControls,
					blockPreview,
				]);
			},
			save: function () {
				return null;
			},
		});

		wp.blocks.registerBlockType('d64/accordion', {
			title: 'D64 Accordion',
			icon: 'arrow-down-alt2',
			category: 'common',
			attributes: {
				title: {
					type: 'string',
					default: 'Accordion Title',
				},
			},

			edit: function (props) {
				var attributes = props.attributes;
				var setAttributes = props.setAttributes;

				// Inspector controls for settings
				var inspectorControls = wp.element.createElement(
					wp.blockEditor.InspectorControls,
					{},
					wp.element.createElement(
						wp.components.PanelBody,
						{ title: 'Accordion Settings' },
						wp.element.createElement(wp.components.TextControl, {
							label: 'Title',
							value: attributes.title,
							onChange: function (value) {
								setAttributes({ title: value });
							},
						})
					)
				);

				// Block preview
				var blockPreview = wp.element.createElement(
					'div',
					{
						className: 'd64-accordion-preview',
						style: {
							border: '1px solid #021D4C',
							borderRadius: '16px',
							overflow: 'hidden',
						},
					},
					[
						wp.element.createElement(
							'div',
							{
								className: 'd64-accordion-header',
								style: {
									background: '#EDF2F4',
									padding: '16px',
									display: 'flex',
									justifyContent: 'space-between',
									alignItems: 'center',
								},
							},
							[
								wp.element.createElement(
									'strong',
									{},
									attributes.title
								),
								wp.element.createElement(
									'svg',
									{
										width: 20,
										height: 20,
										viewBox: '0 0 24 24',
										fill: 'none',
										stroke: 'currentColor',
										strokeWidth: 2,
										strokeLinecap: 'round',
										strokeLinejoin: 'round',
									},
									wp.element.createElement('polyline', {
										points: '6 9 12 15 18 9',
									})
								),
							]
						),
						wp.element.createElement(
							'div',
							{
								className:
									'd64-accordion-content prose prose-sm prose-headings:mt-4',
								style: { padding: '12px' },
							},
							wp.element.createElement(wp.blockEditor.InnerBlocks)
						),
					]
				);

				return wp.element.createElement('div', {}, [
					inspectorControls,
					blockPreview,
				]);
			},

			save: function () {
				return wp.element.createElement(
					wp.blockEditor.InnerBlocks.Content
				);
			},
		});
	});
})();
