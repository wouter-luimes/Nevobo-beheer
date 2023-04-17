<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.wouterluimes.nl
 * @since      1.0.0
 *
 * @package    Nevobo_Beheer
 * @subpackage Nevobo_Beheer/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Nevobo_Beheer
 * @subpackage Nevobo_Beheer/includes
 * @author     Wouter Luimes <contact@wouterluimes.nl>
 */
class Nevobo_Beheer_i18n
{
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_slug    The ID of this plugin.
	 */
	private $plugin_slug;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param    string    $plugin_slug       The slug of this plugin.
     * @param    string    $version           The version of this plugin.
     */
    public function __construct($plugin_slug, $version)
    {
        $this->plugin_slug = $plugin_slug;
        $this->version = $version;
    }

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain()
	{
		load_plugin_textdomain(
			$this->plugin_slug,
			false,
			dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
		);
	}
}
