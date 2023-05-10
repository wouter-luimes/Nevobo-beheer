/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { select, dispatch } from '@wordpress/data';
import { useEntityProp } from '@wordpress/core-data';
import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { __experimentalNumberControl as NumberControl, SelectControl } from '@wordpress/components';
import { decodeEntities } from '@wordpress/html-entities';

/**
 * Registers the team meta panel to the editor
 */
const NevoboBeheerTeamMetaPanel = () => {
    // Get current post type
    const postType = select('core/editor').getCurrentPostType();

    // Check if the the current post type is a team
    if (postType !== 'nevobo-team') {
        return null;
    }

    // Get the meta values and a function for updating the meta values from useEntityProp
    const [meta, setMeta] = useEntityProp('postType', postType, 'meta');

    // Get all the team category terms
    const teamCategoryTerms = select('core').getEntityRecords(
        'taxonomy',
        'nevobo-team-category',
        {
            per_page: 100,
            orderby: 'id',
            order: 'asc',
            _fields: 'id,slug,name,parent,meta'
        }
    );

    // Set the team type options array for the select control element
    const teamTypeOptions = (() => {
        let options = [];
        // Check if the category terms array has already been loaded
        if (teamCategoryTerms !== null) {
            // Add option when no team type has been selected yet
            options.push({ value: '', label: __('Selecteer het teamtype', 'nevobo-beheer') });
            // Filter the team catogory terms array who dont have a team type set
            let terms = teamCategoryTerms.filter(term => term.meta['nevobo-team-type'] !== '');
            // Maps the terms array with the correct value and label
            terms = terms.map(term => ({ value: term.meta['nevobo-team-type'], label: `${term.meta['nevobo-team-type']} (${decodeEntities(term.name)})` }));
            // Merge the options array with the terms array
            options = [...options, ...terms];
        } else {
            // Add the is loading option when the category terms array has not been loaded yet
            options.push({ value: '', label: __('Teamtype laden...', 'nevobo-beheer'), disabled: true });
        }
        return options;
    })();

    /**
    * A helper function for getting the team type string
    *
    * @return {string} - If the team category terms array is still null then return an empty string, otherwise the team type string
    */
    const getTeamType = () => (teamCategoryTerms === null ? '' : meta['nevobo-team-type']);

    /**
    * A helper function for updating the  team type and team categories
    *
    * @param {*}  newTeamType - The new team type to update
    */
    const setTeamType = (newTeamType) => {
        // Check if the provided team type is one of the options
        if (teamTypeOptions.some((option) => option.value === newTeamType)) {
            // Set the team type to the new team type
            setMeta({
                ...meta,
                ['nevobo-team-type']: newTeamType,
            });

            let teamCategories = [];
            // Check if the selected team type is not empty
            if (newTeamType !== '') {
                // Get the term of the provided team type
                let term = teamCategoryTerms.find(item => item.meta && item.meta['nevobo-team-type'] === newTeamType);
                // Add the ID of the term to the array
                teamCategories.push(term.id);
                // Check while the term has a parent term
                while (term && term.parent !== 0) {
                    // Add the parent term to the array
                    teamCategories.push(term.parent);
                    // Change the current term to the parent term and repeat
                    term = teamCategoryTerms.find(item => item.id === term.parent);
                }
            }
            // Set the team categories
            dispatch('core/editor').editPost({ 'nevobo-team-category': teamCategories });
        }
    }

    /**
    * A helper function for getting the team serial number
    *
    * @return {*} - If the serial number is 0 then return an empty string, otherwise the serial number integer
    */
    const getTeamSerialNumber = () => (meta['nevobo-team-serial-number'] === 0) ? '' : meta['nevobo-team-serial-number'];

    /**
     * A helper function for updating the team serial number
     *
     * @param {*}      newSerialNumber - The serial number to update
     */
    const setTeamSerialNumber = (newSerialNumber) => {
        // set the team serial number to 0 if the provided serial number is an empty string
        newSerialNumber = newSerialNumber === '' ? 0 : newSerialNumber;
        // check if the provided serial number is an integer and if it is 0 or higher
        if (Number.isInteger(newSerialNumber) && newSerialNumber >= 0) {
            // update the team serial number
            setMeta({
                ...meta,
                ['nevobo-team-serial-number']: newSerialNumber,
            });
        }
    };

    /**
     * Returning the team settings panel
     */
    return (
        <PluginDocumentSettingPanel
            name='nevobo-team-meta-panel'
            title={__('Teamgegevens', 'nevobo-beheer')}
            className='nevobo-team-meta-panel'
        >
            <SelectControl
                label={__('Teamtype', 'textdomain')}
                value={getTeamType()}
                options={teamTypeOptions}
                onChange={(value) => setTeamType(value)}
                labelPosition={'top'}
                multiple={false}
            />
            <NumberControl
                label={__('Volgnummer', 'textdomain')}
                value={getTeamSerialNumber()}
                onChange={(value) => setTeamSerialNumber(value)}
                labelPosition={'side'}
                help={__('Bijvoorbeeld \'1\' voor Dames 1.')}
                min={0}
                spinControl={'native'}
                isDragEnabled={false}
                isShiftStepEnabled={false}
            />
        </PluginDocumentSettingPanel>
    );
};
registerPlugin('nevobo-beheer', {
    render: NevoboBeheerTeamMetaPanel,
});

/**
 * Check if the team meta panel is set to closed
 */
if (!select('core/edit-post').isEditorPanelOpened('nevobo-beheer/nevobo-team-meta-panel')) {
    // Set the team meta panel to open
    dispatch('core/edit-post').toggleEditorPanelOpened('nevobo-beheer/nevobo-team-meta-panel');
}

/**
 * Remove the team category select panel
 */
dispatch('core/edit-post').removeEditorPanel('taxonomy-panel-nevobo-team-category');