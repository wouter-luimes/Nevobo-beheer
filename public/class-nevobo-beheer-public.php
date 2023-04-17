<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.wouterluimes.nl
 * @since      1.0.0
 *
 * @package    Nevobo_Beheer
 * @subpackage Nevobo_Beheer/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin slug, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Nevobo_Beheer
 * @subpackage Nevobo_Beheer/public
 * @author     Wouter Luimes <contact@wouterluimes.nl>
 */
class Nevobo_Beheer_Public
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
	 * @param      string    $plugin_slug       The slug of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_slug, $version)
	{
		$this->plugin_slug = $plugin_slug;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Nevobo_Beheer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nevobo_Beheer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_slug, plugin_dir_url(__FILE__) . 'css/nevobo-beheer-public.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Nevobo_Beheer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nevobo_Beheer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_slug, plugin_dir_url(__FILE__) . 'js/nevobo-beheer-public.js', array('jquery'), $this->version, false);
	}
}
