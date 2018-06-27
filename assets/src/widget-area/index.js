import React from 'react';
import { __ } from '@wordpress/i18n';
import { Placeholder, SelectControl } from '@wordpress/components';

export const name = 'hm/widget-area';

export const settings = {

	title: __( 'Widget Area', 'gutenberg-widgets' ),

	description: __( 'Display a widget area', 'gutenberg-widgets' ),

	icon: 'widget',

	category: 'widgets',

	keywords: [
		__( 'widget', 'gutenberg-widgets' ),
		__( 'widget area', 'gutenberg-widgets' ),
		__( 'sidebar', 'gutenberg-widgets' ),
	],

	attributes: {
		widgetArea: {
			type: 'string',
		},
	},

	edit( { attributes, setAttributes } ) {
		const { widgetArea } = attributes;
		const { widgetAreas, canEdit } = window.HMGutenbergWidgetAreas;

		const options = [
			{
				value: '',
				label: __( 'Choose...' ),
			}
		].concat( widgetAreas.map( settings => ( {
			value: settings.id,
			label: settings.name,
		} ) ) );

		// @todo preview mode.

		return (
			<Placeholder
				label={ __( 'Widget Area', 'gutenberg-widgets' ) }
				instructions={ __( 'Choose a widget area from the dropdown below.', 'gutenberg-widgets' ) }
			>
				<SelectControl
					value={ widgetArea }
					options={ options }
					onChange={ value => setAttributes( { widgetArea: value } ) }
				/>
				{ widgetArea && canEdit && (
					<div className="components-base-control" style={ { marginLeft: '1rem' } }>
						<a href="widgets.php">{ __( 'Edit widgets', 'gutenberg-widgets' ) }</a>
					</div>
				) }
			</Placeholder>
		);
	},

	save() {
		return null;
	},

};
