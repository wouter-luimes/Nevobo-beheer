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
	<span>Teamleden:</span>
	<ul>
		<?php foreach ($attributes['teamMembers'] as $team_member) {
			ob_start(); ?>
			<li><?= $team_member['number'] ?>: <?= $team_member['name'] ?> (<?= $team_member['type'] ?>)</li>
		<?php echo ob_get_clean();
		} ?>
	</ul>
</section>