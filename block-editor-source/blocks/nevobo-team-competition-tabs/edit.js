/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { Placeholder } from '@wordpress/components';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit() {
	/**
	 * Returning the custom block
	 */
	return (
		<div {...useBlockProps()}>
			<Placeholder
				label={__('Competitietabbladen', 'nevobo-beheer')}
				icon='list-view'
				instructions={__('Tabbladen voor het weergeven van competitiegegevens, waaronder het programma, uitslagen en poule-standen.', 'nevobo-beheer')}
				isColumnLayout={true}
			/>
		</div>
	);
}
