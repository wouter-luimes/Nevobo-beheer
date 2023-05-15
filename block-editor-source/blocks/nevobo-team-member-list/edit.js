/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { select } from '@wordpress/data';
import { useBlockProps, isBlockHighlighted, isBlockSelected } from '@wordpress/block-editor';
import { Placeholder, Flex, FlexBlock, FlexItem, __experimentalNumberControl as NumberControl, TextControl, SelectControl, Button } from '@wordpress/components';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {
	/**
	 * A helper function for setting the team member number.
	 * 
	 * @param {int} value - The team member numer.
	 * @param {int} index - The index of the team member.
	 */
	const setTeamMemberNumber = (value, index) => {
		// Todo: validation and error handling
		let newTeamMembers = [...attributes.teamMembers];
		newTeamMembers[index].number = value === '' ? 0 : parseInt(value);
		setAttributes({ teamMembers: newTeamMembers });
	};

	/**
	 * A helper function for setting the team member name.
	 * 
	 * @param {string} value - The team member name.
	 * @param {index} index - The index of the team member.
	 */
	const setTeamMemberName = (value, index) => {
		// Todo: validation and error handling
		let newTeamMembers = [...attributes.teamMembers];
		newTeamMembers[index].name = String(value);
		setAttributes({ teamMembers: newTeamMembers });
	};

	/**
	 * An array of team member type options for the select control element.
	*/
	const teamMemberTypeOptions = [
		{ value: 'speler', label: 'Speler' },
		{ value: 'aanvoerder', label: 'Aanvoerder' },
		{ value: 'libero', label: 'Libero' },
		{ value: 'coach-trainer', label: 'Coach en trainer' }, // niet officiëel
		{ value: 'coach', label: 'Coach' },
		// { value: 'assistent-coach', label: 'Assistent coach' },
		{ value: 'trainer', label: 'Trainer' },
		// 	{ value: 'arts', label: 'Arts' },
		// 	{ value: 'verzorger', label: 'Verzorger' },
		// 	{ value: 'fysiotherapeut', label: 'Fysiotherapeut' },
		// 	{ value: 'scheidsrechterbeoordelaar', label: 'Scheidsrechterbeoordelaar' },
		// 	{ value: 'manager', label: 'Manager' },
	];

	/**
	 * A helper function for setting the team member type.
	 * 
	 * @param {string} value - The team member type.
	 * @param {index} index - The index of the team member.
	 */
	const setTeamMemberType = (value, index) => {
		// Todo: validation and error handling
		let newTeamMembers = [...attributes.teamMembers];
		newTeamMembers[index].type = String(value);
		// check if the team member is set to a field player
		if (['speler', 'aanvoerder', 'libero'].includes(String(value))) {
			// check if the current number is 0
			if (newTeamMembers[index].number === 0) {
				// set the number to the next number or 1 if there is no previous team member
				newTeamMembers[index].number = newTeamMembers.length > 1 ? newTeamMembers[index - 1].number + 1 : 1;
			}
		} else {
			// set the number to 0 if the team member is not a field player
			newTeamMembers[index].number = 0;
		}
		setAttributes({ teamMembers: newTeamMembers });
	};

	/**
	 * A helper function for removing a team member.
	 * 
	 * @param {index} index - The index of the team member.
	 */
	const removeTeamMember = (index) => {
		let newTeamMembers = attributes.teamMembers.filter((e, i) => index !== i);
		setAttributes({ teamMembers: newTeamMembers });
	};

	/**
	 * A helper function to add a team member.
	 */
	const addTeamMember = () => {
		let newNumber = attributes.teamMembers.length > 0 ? attributes.teamMembers.at(-1).number + 1 : 1;
		let newTeamMembers = attributes.teamMembers.concat([{ number: newNumber, name: '', type: 'speler' }]);
		setAttributes({ teamMembers: newTeamMembers });
	};

	// console.log('getSelectedBlock: ');
	// console.log(select('core/block-editor').getSelectedBlock());
	// console.log('isBlockSelected: ' + isBlockSelected);
	// console.log('isBlockHighlighted: ' + select('core/block-editor').isBlockHighlighted);

	/**
	 * Returning the custom block
	 */
	return (
		<div {...useBlockProps()}>
			<Placeholder
				label={__('Teamleden', 'nevobo-beheer')}
				icon='list-view'
				instructions={__('Voer hier (indien van toepassing) het nummer, de naam en het type van alle teamleden in.', 'nevobo-beheer')}
				isColumnLayout={true}
			>
				{attributes.teamMembers.map((member, index) => {
					return (
						<Flex direction='row' align='flex-start' justify='space-between'>
							<FlexItem>
								<NumberControl
									label={index === 0 && __('Nummer', 'nevobo-beheer')}
									value={member.number === 0 ? '' : member.number}
									onChange={(value) => setTeamMemberNumber(value, index)}
									disabled={!['speler', 'aanvoerder', 'libero'].includes(member.type)}
									min={0}
									spinControl={'native'}
									isDragEnabled={false}
									isShiftStepEnabled={false}
									style={{ width: '5em' }}
								>
								</NumberControl>
							</FlexItem>
							<FlexBlock>
								<TextControl
									label={index === 0 && __('Naam', 'nevobo-beheer')}
									value={member.name}
									type='text'
									placeholder={__('Voor-en achternaam', 'nevobo-beheer')}
									onChange={(value) => setTeamMemberName(value, index)}
									// autoFocus={member.name === ''}
								>
								</TextControl>
							</FlexBlock>
							<FlexItem>
								<SelectControl
									label={index === 0 && __('Type', 'nevobo-beheer')}
									value={member.type}
									options={teamMemberTypeOptions}
									onChange={(value) => setTeamMemberType(value, index)}
									multiple={false}
								>
								</SelectControl>
							</FlexItem>
							<FlexItem>
								<Button
									text={__('Verwijder', 'nevobo-beheer')}
									onClick={() => removeTeamMember(index)}
									variant="secondary"
									isDestructive={true}
									style={index === 0 ? { height: '30px', marginTop: '23.39px' } : { height: '30px' }}
								>
								</Button>
							</FlexItem>
						</Flex>
					)
				})}
				<FlexItem>
					<Button
						text={__('Voeg teamlid toe', 'nevobo-beheer')}
						onClick={() => addTeamMember()}
						variant="primary"
						style={{ marginTop: '1em' }}
					>
					</Button>
				</FlexItem>
			</Placeholder>
		</div>
	);
}