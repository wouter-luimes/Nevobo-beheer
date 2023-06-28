/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { Placeholder, Flex, FlexItem, FlexBlock, __experimentalNumberControl as NumberControl, TextControl, SelectControl, Button } from '@wordpress/components';

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
	 * An array of team member role options for the select control element.
	 */
	const teamMemberRoleOptions = [
		{ value: 'player', label: __('Speler', 'nevobo-beheer') },
		{ value: 'captain', label: __('Aanvoerder', 'nevobo-beheer') },
		{ value: 'libero', label: __('Libero', 'nevobo-beheer') },
		{ value: 'trainee', label: __('Mee-trainer', 'nevobo-beheer') },
		{ value: 'coach-trainer', label: __('Coach & trainer', 'nevobo-beheer') },
		{ value: 'coach', label: __('Coach', 'nevobo-beheer') },
		{ value: 'assistant-coach', label: __('Assistent-coach', 'nevobo-beheer') },
		{ value: 'trainer', label: __('Trainer', 'nevobo-beheer') },
		{ value: 'assistant-trainer', label: __('Assistent-trainer', 'nevobo-beheer') },
		// { value: 'doctor', label: __('Arts', 'nevobo-beheer') },
		// { value: 'caretaker', label: __('Verzorger', 'nevobo-beheer') },
		// { value: 'physiotherapist', label: __('Fysiotherapeut', 'nevobo-beheer') },
		// { value: 'manager', label: __('Manager', 'nevobo-beheer') },
	];

	/**
	 * An array of team member role options
	 */
	// const teamMemberRoleOptions = [
	// 	{ value: 'speler', label: 'Speler' },
	// 	{ value: 'aanvoerder', label: 'Aanvoerder' }, // niet officieel maar wel makkelijk hier
	// 	{ value: 'staf', label: 'Staf' },
	// ];

	/**
	 * An array of team member player type options for the select control element.
	 */
	// const teamMemberPlayerTypeOptions = [
	// 	{ value: 'onbepaald', label: 'Onbepaald' }, // on/niet-gespecificeerd? on/niet-bepaald? on/niet-bekend? on/niet-gedefinieerd?
	// 	{ value: 'spelverdeler', label: 'Spelverdeler' },
	// 	{ value: 'midden', label: 'Midden' },
	// 	{ value: 'buiten', label: 'Buiten' },
	// 	{ value: 'diagonaal', label: 'Diagonaal' },
	// 	{ value: 'libero', label: 'Libero' },
	// 	{ value: 'gevarieerd', label: 'Gevarieerd' }, // afwisselend? veelzijdig? gevarieerd? afwisselend? verscheiden? algemeen?
	// 	{ value: 'mee-trainer', label: 'Mee-trainer' },
	// ];

	/**
	 * An array of team member staf options for the select control element.
	 */
	// const teamMemberStafTypeOptions = [ // begeleiding? staf?
	// 	{ value: 'onbepaald', label: 'Onbepaald' },
	// 	{ value: 'coach-en-trainer', label: 'Coach & Trainer' },
	// 	{ value: 'coach', label: 'Coach' },
	// 	{ value: 'assistent-coach', label: 'Assistent Coach' },
	// 	{ value: 'trainer', label: 'Trainer' },
	// 	{ value: 'assistent-trainer', label: 'Assistent Trainer' },
	// 	{ value: 'arts', label: 'Arts' },
	// 	{ value: 'verzorger', label: 'Verzorger' },
	// 	{ value: 'fysiotherapeut', label: 'Fysiotherapeut' },
	// 	{ value: 'manager', label: 'Manager' },
	// ];

	/**
	 * A helper function for checking if a team member is playing competition.
	 * In other words, check if a number should be assigned to this player or not.
	 * 
	 * @param {string} role - The team member role to check.
	 * @return {boolean} - If the role is a competition player.
	 */
	const isCompetitionPlayer = (role) => {
		return ['player', 'captain', 'libero'].includes(String(role));
	};

	/**
	 * A helper function that returns the highest team member number.
	 * If there are no team members it returns 0.
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
	 * A helper function for setting the team member role.
	 * 
	 * @param {string} role - The team member role.
	 * @param {index} index - The index of the team member.
	 */
	const setTeamMemberRole = (role, index) => {
		// Todo: validation and error handling
		let newTeamMembers = [...attributes.teamMembers];
		newTeamMembers[index].role = String(role);
		// check if the team member is set to a competition player and if the current number is 0 
		if (isCompetitionPlayer(String(role)) && newTeamMembers[index].number === 0) {
			newTeamMembers[index].number = getHighestTeamMemberNumber() + 1;
		}
		// check if the team member is not a competition player but the number has been set already
		if (!isCompetitionPlayer(String(role))) {
			newTeamMembers[index].number = 0;
		}
		setAttributes({ teamMembers: newTeamMembers });
	};

	/**
	 * A helper function to add a team member.
	 */
	const addTeamMember = () => {
		let newTeamMembers = attributes.teamMembers.concat([{ number: getHighestTeamMemberNumber() + 1, name: '', role: 'player' }]);
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
	 * A helper function to sort the team members.
	 */
	const sortTeamMember = () => {
		let newTeamMembers = [
			// sort competition players on their number first, then on their name
			...attributes.teamMembers.filter((member) => isCompetitionPlayer(member.role)).sort((a, b) => a.number !== b.number ? a.number - b.number : a.name.localeCompare(b.name)),
			// sort the other members based on their name
			...attributes.teamMembers.filter((member) => member.role === 'trainee').sort((a, b) => a.name > b.name ? 1 : -1),
			...attributes.teamMembers.filter((member) => member.role === 'coach-trainer').sort((a, b) => a.name > b.name ? 1 : -1),
			...attributes.teamMembers.filter((member) => member.role === 'coach').sort((a, b) => a.name > b.name ? 1 : -1),
			...attributes.teamMembers.filter((member) => member.role === 'assistant-coach').sort((a, b) => a.name > b.name ? 1 : -1),
			...attributes.teamMembers.filter((member) => member.role === 'trainer').sort((a, b) => a.name > b.name ? 1 : -1),
			...attributes.teamMembers.filter((member) => member.role === 'assistant-trainer').sort((a, b) => a.name > b.name ? 1 : -1),
		];
		setAttributes({ teamMembers: newTeamMembers });
	};

	/**
	 * Returning the custom block
	 */
	return (
		<div {...useBlockProps()}>
			<Placeholder
				label={__('Teamleden', 'nevobo-beheer')}
				icon='editor-ul'
				instructions={__('Voer hier (indien van toepassing) het nummer, de naam en de rol van alle teamleden in.', 'nevobo-beheer')}
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
									disabled={!isCompetitionPlayer(member.role)}
									min={0}
									spinControls={'native'}
									isDragEnabled={false}
									isShiftStepEnabled={false}
									style={{ width: '60px' }}
								/>
							</FlexItem>
							<FlexBlock>
								<TextControl
									label={index === 0 && __('Naam', 'nevobo-beheer')}
									value={member.name}
									type='text'
									placeholder={__('Voor-en achternaam', 'nevobo-beheer')}
									onChange={(value) => setTeamMemberName(value, index)}
									autoFocus={member.name === ''}
								/>
							</FlexBlock>
							<FlexItem>
								<SelectControl
									label={index === 0 && __('Rol', 'nevobo-beheer')}
									value={member.role}
									options={teamMemberRoleOptions}
									onChange={(value) => setTeamMemberRole(value, index)}
									multiple={false}
								/>
							</FlexItem>
							{/* <FlexItem>
								<SelectControl
									label={index === 0 && __('Positie', 'nevobo-beheer')}
									value={member.position}
									options={teamMemberPositionOptions}
									onChange={(value) => setTeamMemberPosition(value, index)}
									multiple={false}
								/>
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
				<FlexItem style={{ marginTop: '1em' }}>
					<Button
						text={__('Voeg teamlid toe', 'nevobo-beheer')}
						onClick={() => addTeamMember()}
						variant="primary"
					/>
					<Button
						text={__('Sorteer teamleden', 'nevobo-beheer')}
						onClick={() => sortTeamMember()}
						variant="secondary"
					/>
				</FlexItem>
			</Placeholder>
		</div>
	);
}