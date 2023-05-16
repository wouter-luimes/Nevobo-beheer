/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { Placeholder, Flex, FlexBlock, FlexItem, __experimentalNumberControl as NumberControl, TextControl, SelectControl, FormToggle, Button } from '@wordpress/components';

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
	 * An array of team member type options for the select control element. 
	 * Todo: positie? (midden, buiten, dia, spel, libero, etc.)
	*/
	const teamMemberTypeOptions = [
		{ value: 'speler', label: 'Speler' },
		{ value: 'aanvoerder', label: 'Aanvoerder' },
		{ value: 'libero', label: 'Libero' },
		{ value: 'mee-trainer', label: 'Mee-trainer' },
		{ value: 'coach-en-trainer', label: 'Coach & Trainer' },
		{ value: 'coach', label: 'Coach' },
		// { value: 'assistent-coach', label: 'Assistent Coach' },
		{ value: 'trainer', label: 'Trainer' },
		// { value: 'assistent-trainer', label: 'Assistent Trainer' },
		// 	{ value: 'arts', label: 'Arts' },
		// 	{ value: 'verzorger', label: 'Verzorger' },
		// 	{ value: 'fysiotherapeut', label: 'Fysiotherapeut' },
		// 	{ value: 'scheidsrechterbeoordelaar', label: 'Scheidsrechterbeoordelaar' },
		// 	{ value: 'manager', label: 'Manager' },
	];

	/**
	 * A helper function for checking if a team member is playing competition.
	 * In other words, should a number be assigned to this player or not?
	 * 
	 * @param {string} type - The team member type to check.
	 * @return {boolean} - If the type is a competition player.
	 */
	const isCompetitionPlayer = (type) => {
		return ['speler', 'aanvoerder', 'libero'].includes(String(type));
	};

	/**
	 * A helper function for returning the highest team member number.
	 * If there are no items in the array it returns 0.
	 * 
	 * @returns {int} - The highest team member number.
	 */
	const getHighestTeamMemberNumber = () => {
		return attributes.teamMembers.reduce((prev, cur) => {
			return cur.number > prev ? cur.number : prev;
		}, 0);
	};

	/**
	 * A helper function for setting the team member number.
	 * 
	 * @param {int} nummber - The team member numer.
	 * @param {int} index - The index of the team member.
	 */
	const setTeamMemberNumber = (number, index) => {
		// Todo: validation and error handling
		let newTeamMembers = [...attributes.teamMembers];
		newTeamMembers[index].number = number === '' ? 0 : parseInt(number);
		setAttributes({ teamMembers: newTeamMembers });
	};

	/**
	 * A helper function for setting the team member name.
	 * 
	 * @param {string} name - The team member name.
	 * @param {index} index - The index of the team member.
	 */
	const setTeamMemberName = (name, index) => {
		// Todo: validation and error handling
		let newTeamMembers = [...attributes.teamMembers];
		newTeamMembers[index].name = String(name);
		setAttributes({ teamMembers: newTeamMembers });
	};

	/**
	 * A helper function for setting the team member type.
	 * 
	 * @param {string} type - The team member type.
	 * @param {index} index - The index of the team member.
	 */
	const setTeamMemberType = (type, index) => {
		// Todo: validation and error handling
		let newTeamMembers = [...attributes.teamMembers];
		newTeamMembers[index].type = String(type);
		// check if the team member is set to a competition player and if the current number is 0 
		if (isCompetitionPlayer(String(type)) && newTeamMembers[index].number === 0) {
			newTeamMembers[index].number = getHighestTeamMemberNumber() + 1;
		}
		// check if the team member is not a competition player but the number has been set already
		if (!isCompetitionPlayer(String(type))) {
			newTeamMembers[index].number = 0;
		}
		setAttributes({ teamMembers: newTeamMembers });
	};

	/**
	 * A helper function to add a team member.
	 */
	const addTeamMember = () => {
		let newTeamMembers = attributes.teamMembers.concat([{ number: getHighestTeamMemberNumber() + 1, name: null, type: 'speler' }]);
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
									disabled={!isCompetitionPlayer(member.type)}
									min={0}
									spinControls={'native'}
									isDragEnabled={false}
									isShiftStepEnabled={false}
									style={{ width: '5em' }}
								/>
							</FlexItem>
							<FlexBlock>
								<TextControl
									label={index === 0 && __('Naam', 'nevobo-beheer')}
									value={member.name}
									type='text'
									placeholder={__('Voor-en achternaam', 'nevobo-beheer')}
									onChange={(value) => setTeamMemberName(value, index)}
									autoFocus={member.name === null}
								/>
							</FlexBlock>
							<FlexItem>
								<SelectControl
									label={index === 0 && __('Type', 'nevobo-beheer')}
									value={member.type}
									options={teamMemberTypeOptions}
									onChange={(value) => setTeamMemberType(value, index)}
									multiple={false}
								/>
							</FlexItem>
							{/* <FlexItem>
								<Flex direction='column' align='flex-start'>
									<label
										data-wp-component="Text"
										for="inspector-form-toggle-0"
										class="components-truncate components-text components-form-toggle__label css-1imalal"
										style={{ marginBottom: '6px' }}>
										{__('Aanvoerder', 'nevobo-beheer')}
									</label>
									<FormToggle
										onChange={function noRefCheck() { }}
										checked={true}
										disabled={false}										
									/>
								</Flex>
							</FlexItem> */}
							<FlexItem>
								<Button
									text={__('Verwijder', 'nevobo-beheer')}
									onClick={() => removeTeamMember(index)}
									variant="secondary"
									isDestructive={true}
									style={index === 0 ? { height: '30px', marginTop: '23.39px' } : { height: '30px' }}
								/>
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
					/>
				</FlexItem>
			</Placeholder>
		</div>
	);
}