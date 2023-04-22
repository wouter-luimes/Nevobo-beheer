<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.wouterluimes.nl
 * @since      1.0.0
 *
 * @package    Nevobo_Beheer
 * @subpackage Nevobo_Beheer/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Nevobo_Beheer
 * @subpackage Nevobo_Beheer/includes
 * @author     Wouter Luimes <contact@wouterluimes.nl>
 */
class Nevobo_Beheer
{
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Nevobo_Beheer_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_slug    The string used to uniquely identify this plugin.
	 */
	protected $plugin_slug;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin slug and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		if (defined('NEVOBO_BEHEER_VERSION')) {
			$this->version = NEVOBO_BEHEER_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_slug = 'nevobo-beheer';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_object_hooks();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Nevobo_Beheer_Loader. Orchestrates the hooks of the plugin.
	 * - Nevobo_Beheer_i18n. Defines internationalization functionality.
	 * - Nevobo_Beheer_Admin. Defines all hooks for the admin area.
	 * - Nevobo_Beheer_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies()
	{
		// The class responsible for orchestrating the actions and filters of the core plugin.
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-nevobo-beheer-loader.php';

		// The class responsible for defining internationalization functionality of the plugin.
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-nevobo-beheer-i18n.php';

		// The class responsible for defining all object type actions that occur.
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-nevobo-beheer-objects.php';

		// The class responsible for defining all actions that occur in the admin area.
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-nevobo-beheer-admin.php';

		// The class responsible for defining all actions that occur in the Gutenberg editor.
		require_once plugin_dir_path(dirname(__FILE__)) . 'gutenberg/class-nevobo-beheer-gutenberg.php';

		// The class responsible for defining all actions that occur in the public-facing side of the site.
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-nevobo-beheer-public.php';

		$this->loader = new Nevobo_Beheer_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Nevobo_Beheer_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale()
	{
		$plugin_i18n = new Nevobo_Beheer_i18n($this->get_plugin_slug(), $this->get_version());

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the object types of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_object_hooks()
	{
		$plugin_objects = new Nevobo_Beheer_Objects($this->get_plugin_slug(), $this->get_version());

		// register custom post types and custom post meta keys
		$this->loader->add_action('init', $plugin_objects, 'register_custom_post_types');
		$this->loader->add_action('init', $plugin_objects, 'register_custom_post_meta_keys');

		// register custom taxonomies and custom term meta keys
		$this->loader->add_action('init', $plugin_objects, 'register_custom_taxonomies');
		$this->loader->add_action('init', $plugin_objects, 'register_custom_term_meta_keys');

		// insert the default custom terms
		$this->loader->add_action('init', $plugin_objects, 'insert_custom_terms');
	}

	/**
	 * Register all of the hooks related to the admin area functionality of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{
		$plugin_admin = new Nevobo_Beheer_Admin($this->get_plugin_slug(), $this->get_version());

		// register custom menu pages
		$this->loader->add_action('admin_menu', $plugin_admin, 'add_custom_menu_pages');
		// register custom submenu pages
		$this->loader->add_action('admin_menu', $plugin_admin, 'add_custom_submenu_pages');

		// set custom action links displayed in the plugins list table
		$this->loader->add_action(sprintf('plugin_action_links_%s', NEVOBO_BEHEER_PLUGIN_BASENAME), $plugin_admin, 'set_custom_plugin_action_links');
 
		// register custom settings
		$this->loader->add_action('admin_menu', $plugin_admin, 'register_custom_settings');
		// add custom settings sections
		$this->loader->add_action('admin_menu', $plugin_admin, 'add_custom_settings_sections');
		// add custom settings fields
		$this->loader->add_action('admin_menu', $plugin_admin, 'add_custom_settings_fields');
		
		// sets the column headings displayed in the Nevobo Teams post-type admin list table
		$this->loader->add_filter('manage_nevobo-team_posts_columns', $plugin_admin, 'set_nevobo_teams_column_headings');
		// sets the column cells displayed in the Nevobo Teams post-type admin list table
		$this->loader->add_action('manage_nevobo-team_posts_custom_column', $plugin_admin, 'set_custom_post_column_cells', 10, 2);
		// makes the column headings displayed in a specific custom post-type admin list table sortable
		$this->loader->add_filter('manage_edit-nevobo-team_sortable_columns', $plugin_admin, 'set_custom_post_sortable_columns');
		// removes the ‘Months’ drop-down from the admin post list table
		$this->loader->add_filter('disable_months_dropdown', $plugin_admin, 'disable_custom_post_months_dropdown', 10, 2);
		// adds custom filter dropdowns to the admin post list table.
		$this->loader->add_action('restrict_manage_posts', $plugin_admin, 'add_custom_post_filter_dropdown', 10, 2);
		// sets custom orderby query parameters when ordering based on custom meta-data in the admin list table
		$this->loader->add_action('pre_get_posts', $plugin_admin, 'set_custom_post_columns_order');

		// enqueue the stylesheets for the admin area
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');

		// enqueue the block editor assets
		$this->loader->add_action('enqueue_block_editor_assets', $plugin_admin, 'enqueue_nevobo_team_block_editor_assets');

		// enqueue the javascript for the admin area
		// $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks()
	{
		$plugin_public = new Nevobo_Beheer_Public($this->get_plugin_slug(), $this->get_version());

		// $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		// $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Nevobo_Beheer_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * The slug of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The slug of the plugin.
	 */
	public function get_plugin_slug()
	{
		return $this->plugin_slug;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}
}
