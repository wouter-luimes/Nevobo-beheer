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
 * Registers the custom Nevobo-team panels to the editor
 */
const RenderNevoboTeamPanels = () => {
    // Get current post type
    const postType = select('core/editor').getCurrentPostType();

    // Check if the the current post type is a Nevobo-team
    if (postType !== 'nevobo-team') {
        return null;
    }

    // Get the meta values and a function for updating the meta values
    const [meta, setMeta] = useEntityProp('postType', postType, 'meta');

    // Get all the Nevobo-team category terms
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

    // Set the Nevobo-team type options array for the select control element
    const teamTypeOptions = (() => {
        let options = [];
        // Check if the category terms array has already been loaded
        if (teamCategoryTerms !== null) {
            // Add option when no Nevobo-team type has been selected yet
            options.push({ value: '', label: __('Selecteer het teamtype', 'nevobo-beheer') });
            // Filter the Nevobo-team catogory terms array which dont have a Nevobo-team type set
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
    * A helper function for updating the Nevobo-team type and Nevobo-team categories
    *
    * @param {*}  newTeamType - The new Nevobo-team type to update
    */
    const setTeamType = (newTeamType) => {
        // Check if the provided Nevobo-team type is one of the options
        if (!teamTypeOptions.some((option) => option.value === newTeamType)) {
            // Show a warning
            dispatch('core/notices').createWarningNotice(
                __('Het geselecteerde teamtype is ongeldig. Selecteer een geldig teamtype en probeer het opnieuw.', 'nevobo-beheer')
            );
            return;
        }

        // Set the Nevobo-team type to the new Nevobo-team type
        setMeta({
            ...meta,
            ['nevobo-team-type']: newTeamType,
        });

        // Set the Nevobo-team categories
        let teamCategories = [];
        // Check if the selected team type is not empty
        if (newTeamType !== '') {
            // Get the term of the provided Nevobo-team type
            let term = teamCategoryTerms.find(item => item.meta && item.meta['nevobo-team-type'] === newTeamType);
            // Add the ID of the term to the array
            teamCategories.push(term.id);
            // Check while the term has a parent term
            while (term && term.parent !== 0) {
                // Add the parent term to the array
                teamCategories.push(term.parent);
                // Change the current term to the parent term and repeat
                term = teamCategoryTerms.find(item => item.id && item.id === term.parent);
            }
        }
        dispatch('core/editor').editPost({ 'nevobo-team-category': teamCategories });
    }

    /**
     * A helper function for updating the Nevobo-team serial number
     *
     * @param {*}      newSerialNumber - The serial number to update
     */
    const setTeamSerialNumber = (newSerialNumber) => {
        // Set the serial number to 0 if the provided serial number is an empty string, otherwise parse it to an int
        let serialNumber = newSerialNumber === '' ? 0 : parseInt(newSerialNumber);

        // Check if the serial number is not a number or if it is negative
        if (isNaN(serialNumber) || serialNumber < 0) {
            // Show a warning
            dispatch('core/notices').createWarningNotice(
                __('Het ingevoerde volgnummer is ongeldig. Vul een geldig volgnummer in en probeer het opnieuw.', 'nevobo-beheer')
            );
            return;
        }

        // Update the Nevobo-team serial number
        setMeta({
            ...meta,
            ['nevobo-team-serial-number']: serialNumber,
        });
    };

    /**
     * Returning the custom Nevobo-team panels
     */
    return (
        <>
            <PluginDocumentSettingPanel
                name={nevobo-team-meta-panel}
                title={__('Teamgegevens', 'nevobo-beheer')}
                className={nevobo-team-meta-panel}
            >
                <SelectControl
                    label={__('Teamtype', 'nevobo-beheer')}
                    value={teamCategoryTerms === null ? '' : meta['nevobo-team-type']}
                    options={teamTypeOptions}
                    onChange={(value) => setTeamType(value)}
                    labelPosition={'top'}
                    multiple={false}
                />
                <NumberControl
                    label={__('Volgnummer', 'nevobo-beheer')}
                    value={meta['nevobo-team-serial-number'] === 0 ? '' : meta['nevobo-team-serial-number']}
                    onChange={(value) => setTeamSerialNumber(value)}
                    labelPosition={'side'}
                    help={__('Bijvoorbeeld \'1\' voor het team Dames 1.', 'nevobo-beheer')}
                    min={0}
                    spinControl={'native'}
                    isDragEnabled={false}
                    isShiftStepEnabled={false}
                />
            </PluginDocumentSettingPanel>
            <PluginDocumentSettingPanel
                name={'nevobo-team-pool-panel'}
                title={__('Poulegegevens', 'nevobo-beheer')}
                className={'nevobo-team-pool-panel'}
            >
                <p>To-do</p>
            </PluginDocumentSettingPanel>

        </>
    );
};
registerPlugin('nevobo-beheer', {
    render: RenderNevoboTeamPanels,
});

/**
 * Remove the Nevobo-team category select panel
 */
dispatch('core/edit-post').removeEditorPanel('taxonomy-panel-nevobo-team-category');

/**
 * Set the the Nevobo-team meta panel open by default if it is closed
 */
select('core/edit-post').isEditorPanelOpened('nevobo-beheer/nevobo-team-meta-panel') || dispatch('core/edit-post').toggleEditorPanelOpened('nevobo-beheer/nevobo-team-meta-panel');

/**
 * Set the the Nevobo-team pool panel open by default if it is closed
 */
select('core/edit-post').isEditorPanelOpened('nevobo-bheer/nevobo-team-pool-panel') || dispatch('core/edit-post').toggleEditorPanelOpened('nevobo-bheer/nevobo-team-pool-panel');