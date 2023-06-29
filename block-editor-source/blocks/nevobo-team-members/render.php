<?php

/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 * 
 * The following variables are exposed to the file:
 * - $attributes (array): The block attributes.
 * - $content (string): The block default content.
 * - $block (WP_Block): The block instance.
 */

// Filter the team members who play competition and who have a name
$players = array_filter($attributes['teamMembers'], function ($team_member) {
	$roles = array('player', 'captain', 'libero');
	return (in_array($team_member['role'], $roles) && !empty($team_member['name']));
});
// Sort the players by their number first, then by their name
usort($players, function ($a, $b) {
	if ($a['number'] != $b['number']) {
		return $a['number'] - $b['number'];
	}
	return strcmp($a['name'], $b['name']);
});

// Filter the team members who don't play competition and who have a name
$trainees = array_filter($attributes['teamMembers'], function ($team_member) {
	return ($team_member['role'] === 'trainee' && !empty($team_member['name']));
});
// Sort the trainees by their name
usort($trainees, function ($a, $b) {
	return strcmp($a['name'], $b['name']);
});

// Filter the team members who are supervisors and who have a name
$supervisors = array_filter($attributes['teamMembers'], function ($team_member) {
	$roles = array('coach-trainer', 'coach', 'assistant-coach', 'trainer', 'assistent-trainer');
	return (in_array($team_member['role'], $roles) && !empty($team_member['name']));
});
// Sort the supervisors by their role first, then by their name
usort($supervisors, function ($a, $b) {
	$role_order = array(
		'coach-trainer' => 1,
		'coach' => 2,
		'assistant-coach' => 3,
		'trainer' => 4,
		'assistent-trainer' => 5,
	);
	if ($role_order[$a['role']] - $role_order[$b['role']] !== 0) {
		return $role_order[$a['role']] - $role_order[$b['role']];
	}
	return strcmp($a['name'], $b['name']);
});

// The associative array of team member roles (slugs and names)
$teamMemberRoles = array(
	'player' => __('Speler', 'nevobo-beheer'),
	'captain' => __('Aanvoerder', 'nevobo-beheer'),
	'libero' => __('Libero', 'nevobo-beheer'),
	'trainee' => __('Mee-trainer', 'nevobo-beheer'),
	'coach-trainer' => __('Coach & trainer', 'nevobo-beheer'),
	'coach' => __('Coach', 'nevobo-beheer'),
	'assistant-coach' => __('Assistent-coach', 'nevobo-beheer'),
	'trainer' => __('Trainer', 'nevobo-beheer'),
	'assistant-trainer' => __('Assistent-trainer', 'nevobo-beheer'),
	// 'doctor' => __('Arts', 'nevobo-beheer'),
	// 'caretaker' => __('Verzorger', 'nevobo-beheer'),
	// 'physiotherapist' => __('Fysiotherapeut', 'nevobo-beheer'),
	// 'manager' => __('Manager', 'nevobo-beheer'),
);
?>

<section <?= get_block_wrapper_attributes(); ?>>

	<!-- Competitiespelers -->
	<h2 class="text-lg text-neutral-900 font-semibold pb-4">Spelers</h2>
	<div class="grid sm:grid-cols-2 gap-6 pb-8">
		<?php foreach ($players as $player) {
			ob_start(); ?>
			<dd class="flex gap-2 justify-start items-center">
				<!-- Shirt icon -->
				<div class="relative">
					<?php switch ($player['role']) {
						case 'player':
							ob_start(); ?>
							<svg viewBox="0 0 32 30" xmlns="http://www.w3.org/2000/svg" class="w-10 inline-block">
								<path xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" d="M20.267.4c-.22 2.148-2.062 3.867-4.267 3.867-2.2 0-4.043-1.723-4.267-3.867a.38.38 0 01.359-.4h.004c.12 0 .24.02.353.06.011.007 1.407.54 3.55.54 2.145 0 3.538-.535 3.551-.54.101-.04.208-.06.316-.06.2 0 .42.2.4.4zm2.4.443l8.619 3.017a1.067 1.067 0 01.695 1.207l-1.109 5.866a1.066 1.066 0 01-.927.86l-3.26.368a.534.534 0 00-.473.546l.49 16.06a1.067 1.067 0 01-.608.996c-.155.07-.324.105-.494.103H6.4c-.17.002-.34-.033-.495-.103a1.067 1.067 0 01-.606-.996l.488-16.06a.533.533 0 00-.473-.546l-3.258-.368a1.067 1.067 0 01-.928-.86L.018 5.067A1.067 1.067 0 01.715 3.86L9.334.843a.267.267 0 01.356.21 6.4 6.4 0 0012.625 0 .268.268 0 01.236-.224.267.267 0 01.116.014z" fill="#3D3D3D"></path>
							</svg>
						<?= ob_get_clean();
							break;
						case 'captain':
							ob_start(); ?>
							<svg viewBox="0 0 32 30" xmlns="http://www.w3.org/2000/svg" class="w-10 inline-block">
								<path xmlns="http://www.w3.org/2000/svg" d="M16 4.267c2.205 0 4.047-1.719 4.267-3.867.02-.2-.2-.4-.4-.4a.867.867 0 00-.316.06C19.537.065 18.144.6 16 .6c-2.145 0-3.54-.533-3.552-.54a1.08 1.08 0 00-.353-.06h-.004a.379.379 0 00-.359.4C11.957 2.544 13.8 4.267 16 4.267z" fill="#FF8200"></path>
								<path xmlns="http://www.w3.org/2000/svg" d="M31.286 3.86L22.666.843a.267.267 0 00-.35.21 6.4 6.4 0 01-12.626 0 .266.266 0 00-.357-.21L.714 3.86A1.067 1.067 0 00.02 5.067l1.109 5.866a1.067 1.067 0 00.928.86l3.258.368a.534.534 0 01.473.546L5.3 28.767a1.067 1.067 0 00.606.996c.156.07.325.105.495.103h19.2c.17.002.34-.033.494-.103a1.067 1.067 0 00.607-.996l-.489-16.06a.534.534 0 01.474-.546l3.259-.368a1.067 1.067 0 00.927-.86l1.11-5.866a1.066 1.066 0 00-.696-1.207z" fill="#3D3D3D"></path>
								<path xmlns="http://www.w3.org/2000/svg" fill="#fff" d="M10 25h12v-1H10z"></path>
							</svg>
						<?= ob_get_clean();
							break;
						case 'libero':
							ob_start(); ?>
							<svg viewBox="0 0 32 30" xmlns="http://www.w3.org/2000/svg" class="w-10 inline-block">
								<path xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" d="M20.267.4c-.22 2.148-2.062 3.867-4.267 3.867-2.2 0-4.043-1.723-4.267-3.867a.38.38 0 01.359-.4h.004c.12 0 .24.02.353.06.01.007 1.407.54 3.55.54 2.145 0 3.538-.535 3.551-.54.101-.04.208-.06.316-.06.2 0 .42.2.4.4zm2.4.443l8.619 3.017a1.067 1.067 0 01.695 1.207l-1.109 5.866a1.066 1.066 0 01-.927.86l-3.26.368a.533.533 0 00-.473.546l.49 16.06a1.066 1.066 0 01-.608.996c-.155.07-.324.105-.494.103H6.4c-.17.002-.34-.033-.495-.103a1.066 1.066 0 01-.606-.996l.488-16.06a.533.533 0 00-.473-.546l-3.258-.368a1.066 1.066 0 01-.928-.86L.018 5.067A1.067 1.067 0 01.715 3.86L9.334.843a.267.267 0 01.356.21 6.4 6.4 0 0012.625 0 .268.268 0 01.236-.224.267.267 0 01.116.014z" fill="#FF8200"></path>
							</svg>
					<?= ob_get_clean();
							break;
					} ?>
					<!-- Shirt number -->
					<?php if ($player['number'] > 0) {
						ob_start(); ?>
						<span class="text-base font-semibold text-white pt-1 absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"><?= $player['number'] ?></span>
					<?= ob_get_clean();
					} ?>
				</div>
				<!-- Naam en role -->
				<div class="flex flex-col">
					<span class="text-base text-neutral-900"><?= $player['name'] ?></span>
					<span class="text-sm text-neutral-500"><?= $teamMemberRoles[$player['role']] ?></span>
				</div>
			</dd>
		<?= ob_get_clean();
		} ?>
	</div>

	<!-- Mee-trainers -->
	<h2 class="text-lg text-neutral-900 font-semibold pb-4">Mee-trainers</h2>
	<div class="grid sm:grid-cols-2 gap-6 pb-8">
		<?php if (!empty($trainees)) {
			foreach ($trainees as $trainee) {
				ob_start(); ?>
				<dd class="flex gap-2 justify-start items-center">
					<!-- User icon -->
					<svg viewBox="0 0 32 30" xmlns="http://www.w3.org/2000/svg" class="w-10 inline-block">
						<path xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" d="M20.267.4c-.22 2.148-2.062 3.867-4.267 3.867-2.2 0-4.043-1.723-4.267-3.867a.38.38 0 01.359-.4h.004c.12 0 .24.02.353.06.011.007 1.407.54 3.55.54 2.145 0 3.538-.535 3.551-.54.101-.04.208-.06.316-.06.2 0 .42.2.4.4zm2.4.443l8.619 3.017a1.067 1.067 0 01.695 1.207l-1.109 5.866a1.066 1.066 0 01-.927.86l-3.26.368a.534.534 0 00-.473.546l.49 16.06a1.067 1.067 0 01-.608.996c-.155.07-.324.105-.494.103H6.4c-.17.002-.34-.033-.495-.103a1.067 1.067 0 01-.606-.996l.488-16.06a.533.533 0 00-.473-.546l-3.258-.368a1.067 1.067 0 01-.928-.86L.018 5.067A1.067 1.067 0 01.715 3.86L9.334.843a.267.267 0 01.356.21 6.4 6.4 0 0012.625 0 .268.268 0 01.236-.224.267.267 0 01.116.014z" fill="#3D3D3D"></path>
					</svg>
					<!-- Naam en role -->
					<div class="flex flex-col">
						<span class="text-base text-neutral-900"><?= $trainee['name'] ?></span>
						<span class="text-sm text-neutral-500"><?= $teamMemberRoles[$trainee['role']] ?></span>
					</div>
				</dd>
			<?= ob_get_clean();
			}
		} else {
			ob_start(); ?>
			<span class="text-base text-neutral-500"><?= __('Dit team heeft geen mee-trainers') ?></span>
		<?= ob_get_clean();
		} ?>
	</div>

	<!-- Begeleiders -->
	<h2 class="text-lg text-neutral-900 font-semibold pb-4">Begeleiders</h2>
	<div class="grid sm:grid-cols-2 gap-6 pb-8">
		<?php foreach ($supervisors as $supervisor) {
			ob_start(); ?>
			<dd class="flex gap-2 justify-start items-center">
				<!-- User icon -->
				<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="w-10 inline-block">
					<path xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" fill="#3D3D3D"></path>
				</svg>
				<!-- Naam en role -->
				<div class="flex flex-col">
					<span class="text-base text-neutral-900"><?= $supervisor['name'] ?></span>
					<span class="text-sm text-neutral-500"><?= $teamMemberRoles[$supervisor['role']] ?></span>
				</div>
			</dd>
		<?= ob_get_clean();
		} ?>
	</div>

</section>