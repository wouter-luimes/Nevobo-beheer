<?php

/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 * 
 * The following variables are exposed to the file:
 * - $attributes (array): The block attributes.
 * - $content (string): The block default content.
 * - $block (WP_Block): The block instance.
 */
?>
<section <?php echo get_block_wrapper_attributes(); ?>>
	<span>Spelers:</span>
	<ul style="list-style-type: none">
		<?php foreach ($attributes['teamMembers'] as $team_member) {
			if (in_array($team_member['role'], array('speler', 'aanvoerder', 'libero', 'mee-trainer'))) {
				ob_start(); ?>
				<li><?= $team_member['number'] ?>. <?= $team_member['name'] ?> (<?= $team_member['role'] ?>)</li>
		<?php echo ob_get_clean();
			}
		} ?>
	</ul>
	<span>Staf:</span> <!-- Begeleiders -->
	<ul style="list-style-type: none">
		<?php foreach ($attributes['teamMembers'] as $team_member) {
			if (in_array($team_member['role'], array('coach-en-trainer', 'coach', 'trainer'))) {
				ob_start(); ?>
				<li><?= $team_member['name'] ?> (<?= $team_member['role'] ?>)</li>
		<?php echo ob_get_clean();
			}
		} ?>
	</ul>
</section>