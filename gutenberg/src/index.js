/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { dispatch } from '@wordpress/data';
import { useEntityProp } from '@wordpress/core-data';
import { __experimentalNumberControl as NumberControl, SelectControl } from '@wordpress/components';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { registerPlugin } from '@wordpress/plugins';

// Registers the panel to the editor.
const NevoboBeheerTeamMetaPanel = () => {
    // Get current post type.
    const postType = wp.data.select('core/editor').getCurrentPostType();

    // Check if the the current post type is a Nevobo team.
    if (postType !== 'nevobo-team') {
        return null;
    }

    // Get the meta values and a function for updating the meta values from useEntityProp.
    const [meta, setMeta] = useEntityProp('postType', postType, 'meta');

    // Get the Nevobo team category values and a function for updating the Nevobo team category values from useEntityProp.
    const [teamCategories, setTeamCategories] = useEntityProp('postType', postType, 'nevobo-team-category');

    // Get all the Nevobo team category terms
    const teamCategoryTerms = wp.data.select('core').getEntityRecords(
        'taxonomy',
        'nevobo-team-category',
        {
            per_page: 100,
            orderby: 'id',
            order: 'asc',
            _fields: 'id,slug,name,parent,meta'
        });

    /**
    * A helper function for getting the Nevobo team type string.
    *
    * @return {string} - If the team category terms array is still null then return an empty string, otherwise the team type string.
    */
    const getTeamType = () => (teamCategoryTerms === null ? '' : meta['nevobo-team-type']);

    /**
    * A helper function for getting the Nevobo team type options array.
    *
    * @return {array} - The array of Nevobo team type options.
    */
    const getTeamTypeOptions = () => {
        let options = [];
        // Check if the category terms array has already been loaded 
        if (teamCategoryTerms) {
            // Add option when no team type has been selected yet
            options.push({ value: '', label: __('Selecteer het teamtype', 'nevobo-beheer') });
            // Loop over every category term
            teamCategoryTerms.forEach((term) => {
                let teamtype = term.meta['nevobo-team-type'];
                // Check if the term type has been set
                if (teamtype !== '') {
                    // Add term type to the option array
                    options.push({ value: teamtype, label: `${teamtype} (${term.name})` });
                    // To do: fix bug to display '<'
                }
            });
        } else {
            // Add option when the category terms array has not been loaded yet
            options.push({ value: '', label: __('Teamtype laden...', 'nevobo-beheer'), disabled: true });
        }
        return options;
    }

    /**
    * A helper function for updating the Nevobo team type.
    *
    * @param {*}      newTeamType - The new team type to update.
    */
    const setTeamType = (newTeamType) => {
        // Set the team type to the new team type
        setMeta({
            ...meta,
            ['nevobo-team-type']: newTeamType,
        });
        // Set the team catagories according to the team type
        let teamCategories = [];
        // Check if selected team type is not the empty 'no team has been selected yet' option
        if (newTeamType !== '') {
            // Get the term which has the provided newTeamType as its team type and add it to the array
            let term = teamCategoryTerms.find(item => item.meta && item.meta['nevobo-team-type'] === newTeamType);
            teamCategories.push(term.id);
            // Check if the term has a parent term
            while (term && term.parent !== 0) {
                // Add the parent term to the array
                teamCategories.push(term.parent);
                // Change the current term to the parent and repeat
                term = teamCategoryTerms.find(item => item.id === term.parent);
            }
        }
        setTeamCategories(teamCategories);
    }

    /**
    * A helper function for getting the Nevobo team serial number.
    *
    * @return {*} - If the serial number is 0 then return an empty string, otherwise the serial number integer.
    */
    const getTeamSerialNumber = () => (meta['nevobo-team-serial-number'] === 0) ? '' : meta['nevobo-team-serial-number'];

    /**
     * A helper function for updating the Nevobo team serial number
     *
     * @param {*}      newSerialNumber - The serial number to update.
     */
    const setTeamSerialNumber = (newSerialNumber) =>
        // set the team serial number to the new serial number
        setMeta({
            ...meta,
            // if the provided serial number is an empty string then save it as the 0 integer, otherwise the serial number integer
            ['nevobo-team-serial-number']: (newSerialNumber === '') ? 0 : newSerialNumber,
        });

    // Returning the team document settings panel.
    return (
        <PluginDocumentSettingPanel
            name={__('Nevobo-team meta-panel', 'nevobo-beheer')}
            title={__('Teamgegevens', 'nevobo-beheer')}
            className='nevobo-team-meta-panel'
        >
            <SelectControl
                label={__('Teamtype', 'textdomain')}
                value={getTeamType()}
                options={getTeamTypeOptions()}
                onChange={(value) => setTeamType(value)}
                labelPosition={'top'}
                multiple={false}
            />
            <NumberControl
                label={__('Teamvolgnummer', 'textdomain')}
                value={getTeamSerialNumber()}
                onChange={(value) => setTeamSerialNumber(value)}
                labelPosition={'side'}
                help={'Bijvoorbeeld 1 voor Dames 1.'}
                min={1}
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

// Remove the Nevobo Team category select panel.
dispatch('core/edit-post').removeEditorPanel('taxonomy-panel-nevobo-team-category');