<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.wouterluimes.nl
 * @since             1.0.0
 * @package           Nevobo_Beheer
 *
 * @wordpress-plugin
 * Plugin Name:       Nevobo team- en competitiebeheer
 * Plugin URI:        https://www.wouterluimes.nl/nevobo-beheer
 * Description:       Een Wordpress plug-in waarmee team- en competitiegegevens, waaronder standen, uitslagen en het programma van Nederlandse volleybalteams beheerd kunnen worden.
 * Version:           0.1.0
 * Author:            Wouter Luimes
 * Author URI:        https://www.wouterluimes.nl
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nevobo-beheer
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('NEVOBO_BEHEER_VERSION', '1.0.0');

/**
 * Plugin file basename.
 * Used in the loader when the plugin action links are added.
 */
define( 'NEVOBO_BEHEER_PLUGIN_BASENAME', plugin_basename(__FILE__) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-nevobo-beheer-activator.php
 */
function activate_nevobo_beheer()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-nevobo-beheer-activator.php';
	Nevobo_Beheer_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-nevobo-beheer-deactivator.php
 */
function deactivate_nevobo_beheer()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-nevobo-beheer-deactivator.php';
	Nevobo_Beheer_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_nevobo_beheer');
register_deactivation_hook(__FILE__, 'deactivate_nevobo_beheer');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-nevobo-beheer.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_nevobo_beheer()
{
	$plugin = new Nevobo_Beheer();
	$plugin->run();
}
run_nevobo_beheer();
