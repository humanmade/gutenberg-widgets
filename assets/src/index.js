import { registerBlockType } from '@wordpress/blocks';

import * as widgetArea from './widget-area';

const blocks = [
	widgetArea,
];

blocks.forEach( ( { name, settings } ) => registerBlockType( name, settings ) );
