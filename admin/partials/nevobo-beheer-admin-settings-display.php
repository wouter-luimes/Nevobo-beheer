<?php

/**
 * Provides the admin area settings view for the plugin.
 *
 * @link       https://www.wouterluimes.nl
 * @since      1.0.0
 *
 * @package    Nevobo_Beheer
 * @subpackage Nevobo_Beheer/admin/partials
 */
?>

<div class="wrap">
    <h1><?= esc_html(get_admin_page_title()); ?></h1>
    <form action="options.php" method="post">
        <?php settings_fields('nevobo-beheer-association-settings-group'); ?>
        <?php do_settings_sections('nevobo-beheer-association-settings'); ?>
        <?php submit_button(null, 'primary', 'submit', true, null); ?>
    </form>
</div>
<h2>Debug</h2>
<?php print("<pre>nevobo-beheer-association-settings: " . print_r(get_option('nevobo-beheer-association-settings'), true) . "</pre>") ?>