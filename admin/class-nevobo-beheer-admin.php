<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.wouterluimes.nl
 * @since      1.0.0
 *
 * @package    Nevobo_Beheer
 * @subpackage Nevobo_Beheer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin slug, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Nevobo_Beheer
 * @subpackage Nevobo_Beheer/admin
 * @author     Wouter Luimes <contact@wouterluimes.nl>
 */
class Nevobo_Beheer_Admin
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
	 * The admin-specific callbacks of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      Nevobo_Beheer_Admin_Callbacks    $callbacks    The callbacks of this plugin.
	 */
	private $callbacks;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since      1.0.0
	 * @param      string    $plugin_slug       The slug of this plugin.
	 * @param      string    $version    		The version of this plugin.
	 */
	public function __construct($plugin_slug, $version)
	{
		$this->plugin_slug = $plugin_slug;
		$this->version = $version;

		// Admin settings callback functions
		require_once plugin_dir_path(__FILE__) . 'class-nevobo-beheer-admin-callbacks.php';
		$this->callbacks = new Nevobo_Beheer_Admin_Callbacks($plugin_slug, $version);
	}

	/**
	 * Add all the custom admin menu pages.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function add_custom_menu_pages()
	{
		/**
		 * The array containing all the admin menu pages to register
		 */
		$menu_pages = array(
			/**
			 * Competition menu page
			 */
			array(
				'page_title' => __('Competitie', $this->plugin_slug),
				'menu_title' => __('Competitie', $this->plugin_slug),
				'capability' => 'manage_options',
				'menu_slug' => 'nevobo-competition',
				'callback' => '', // empty
				'icon_url' => 'dashicons-list-view',
				'position' => 35,
			),
		);

		/**
		 * Loop to add all the admin menu pages in the array
		 */
		foreach ($menu_pages as $menu_page) {
			add_menu_page(
				$menu_page['page_title'],
				$menu_page['menu_title'],
				$menu_page['capability'],
				$menu_page['menu_slug'],
				$menu_page['callback'],
				$menu_page['icon_url'],
				$menu_page['position'],
			);
		}
	}

	/**
	 * Add all the custom admin submenu pages.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function add_custom_submenu_pages()
	{
		/**
		 * The array containing all the submenu pages to register
		 */
		$submenu_pages = array(
			/**
			 * The Nevobo beheer settings submenu page
			 */
			array(
				'parent_slug' => 'options-general.php',
				'page_title' => __('Nevobo team- en competitiebeheerinstellingen', $this->plugin_slug),
				'menu_title' => __('Nevobo-beheer', $this->plugin_slug),
				'capability' => 'manage_options',
				'menu_slug' => 'nevobo-beheer-settings',
				'callback' => function () {
					require_once plugin_dir_path(__FILE__) . 'partials/nevobo-beheer-admin-settings-display.php';
				},
				'position' => 7,
			),
		);

		/**
		 * Loop to add all the admin submenu pages in the array
		 */
		foreach ($submenu_pages as $submenu_page) {
			add_submenu_page(
				$submenu_page['parent_slug'],
				$submenu_page['page_title'],
				$submenu_page['menu_title'],
				$submenu_page['capability'],
				$submenu_page['menu_slug'],
				$submenu_page['callback'],
				$submenu_page['position'],
			);
		}
	}

	/**
	 * Filters the list of action links displayed in the plugins list table.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @param    array    $actions    The array of plugin action links.
	 */
	public function set_custom_plugin_action_links(array $actions)
	{
		$custom_actions = array(
			sprintf('<a href="%s">%s</a>', menu_page_url('nevobo-beheer-settings', false), esc_html(__('Instellingen', $this->plugin_slug))),
		);
		return array_merge($custom_actions, $actions);
	}

	/**
	 * Register all the custom settings and its data.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function register_custom_settings()
	{
		/**
		 * The array containing all the custom settings
		 */
		$settings = array(
			/**
			 * Association options
			 */
			array(
				'option_group' => 'nevobo-beheer-options-group',
				'option_name' => 'nevobo-beheer-association-data',
				'args' => array(
					'type' => 'array',
					'description' => __('De gegevens van de vereniging.', $this->plugin_slug),
					'sanitize_callback' => array($this->callbacks, 'association_data_sanitize_callback'),
					'show_in_rest' => array(
						'schema' => array(
							'type'  => 'array',
							'items' => array(
								'type' => 'array',
								'items' => array(
									'type' => 'string',
								),
							),
						),
					),
					'default' => array(
						'association-code' => '',
						'association-name' => '',
						'association-location' => '',
						'association-commune' => '',
						'association-province' => '',
						'association-region' => '',
						'association-website' => '',
						'association-email' => '',
					),
				),
			),
			/**
			 * Sports hall options
			 */
			array(
				'option_group' => 'nevobo-beheer-options-group',
				'option_name' => 'nevobo-beheer-sports-hall-data',
				'args' => array(
					'type' => 'array',
					'description' => __('De gegevens van de sporthal.', $this->plugin_slug),
					'sanitize_callback' => array($this->callbacks, 'sports_hall_data_sanitize_callback'),
					'show_in_rest' => array(
						'schema' => array(
							'type'  => 'array',
							'items' => array(
								'type' => array('string', 'integer'),
							),
						),
					),
					'default' => array(
						'sports-hall-code' => '',
						'sports-hall-name' => '',
						'sports-hall-street' => '',
						'sports-hall-house-number' => 0,
						'sports-hall-postal-code' => '',
						'sports-hall-location' => '',
						'sports-hall-country' => '',
						'sports-hall-phone-number' => '',
					),
				),
			),
		);

		/**
		 * Loop to register all the custom settings in the array
		 */
		foreach ($settings as $setting) {
			register_setting(
				$setting['option_group'],
				$setting['option_name'],
				$setting['args'],
			);
		}
	}

	/**
	 * Add all the custom settings sections to the setting pages.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function add_custom_settings_sections()
	{
		/**
		 * The array containing all the custom settings sections
		 */
		$settings_sections = array(
			/**
			 * Nevobo association and sports hall section
			 */
			array(
				'id' => 'nevobo-beheer-nevobo-codes-section',
				'title' => __('Verenigings- en sporthalcodes', $this->plugin_slug),
				'callback' => function () {
					$description = __('Vul hieronder de Nevobo verenigings- en sporthalcode in, welke worden gebruikt bij het ophalen van wedstrijd- en poelegegevens en bij het vaststellen van thuis- of uitwedstrijden.', $this->plugin_slug);
					printf('<p>%s</p>', esc_html($description));
				},
				'page' => 'nevobo-beheer-settings',
				'args' => array(
					// 'before_section' => '',
					// 'after_section' => '',
					// 'section_class' => '',
				),
			),
		);

		/**
		 * Loop to register all the custom settings sections in the array
		 */
		foreach ($settings_sections as $section) {
			add_settings_section(
				$section['id'],
				$section['title'],
				$section['callback'],
				$section['page'],
				$section['args'],
			);
		}
	}

	/**
	 * Add all the custom setting fields to a section of a settings page.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function add_custom_settings_fields()
	{
		/**
		 * The array containing all the custom settings fields
		 */
		$fields = array(
			/**
			 * Verenigingscode
			 */
			array(
				'id' => 'nevobo-beheer-association-data-field',
				'title' => __('Verenigingscode', $this->plugin_slug),
				'callback' => array($this->callbacks, 'nevobo_codes_field_callback'),
				'page' => 'nevobo-beheer-settings',
				'section' => 'nevobo-beheer-nevobo-codes-section',
				'args' => array(
					'label-for' => 'association-code',
					'class' => 'nevobo-beheer-association-code regular-text',
					'type' => 'text',
					'option-name' => 'nevobo-beheer-association-data',
					'description' => __('De verenigingscode mag alleen uit (hoofd)letters en cijfers bestaan en is maximaal 8 karakters lang.', $this->plugin_slug),
				),
			),
			/**
			 * Sporthalcode
			 */
			array(
				'id' => 'nevobo-beheer-sports-hall-data-field',
				'title' => __('Sporthalcode', $this->plugin_slug),
				'callback' => array($this->callbacks, 'nevobo_codes_field_callback'),
				'page' => 'nevobo-beheer-settings',
				'section' => 'nevobo-beheer-nevobo-codes-section',
				'args' => array(
					'label-for' => 'sports-hall-code',
					'class' => 'nevobo-beheer-sports-hall-code regular-text',
					'type' => 'text',
					'option-name' => 'nevobo-beheer-sports-hall-data',
					'description' => __('De sporthalcode mag alleen uit (kleine) letters, cijfers en streepjes bestaan en is gelijk aan de naam van de sporthal waarbij een spatie vervangen dient te worden door een streepje en eventueel gevolgd dient te worden door een streepje en een getal als de naam van de sporthal niet uniek is.', $this->plugin_slug),
				),
			),
		);

		/**
		 * Loop to register all the custom settings sections in the array
		 */
		foreach ($fields as $field) {
			add_settings_field(
				$field['id'],
				$field['title'],
				$field['callback'],
				$field['page'],
				$field['section'],
				$field['args'],
			);
		}
	}

	/**
	 * Sets the column headings displayed in the Nevobo team post-type admin list table.
	 * 
	 * @since      1.0.0
	 * @param      array     $post_columns      The associative array of columns.
	 * 
	 */
	public function set_nevobo_team_column_headings(array $post_columns)
	{
		// log default post columns
		// $file = plugin_dir_path( __FILE__ ) . '/log.txt';
		// file_put_contents( $file, print_r($post_columns, true) . "\n", FILE_APPEND );

		$post_columns = array(
			'cb' => '<input type="checkbox"/>',
			'title' => __('Team', $this->plugin_slug),
			'meta-nevobo-team-type' => __('Teamtype', $this->plugin_slug),
			'meta-nevobo-team-serial-number' => __('Volgnummer', $this->plugin_slug),
			'taxonomy-nevobo-team-category' => __('Teamcatgorieën', $this->plugin_slug),
			// 'meta-nevobo-team-pool' => __('Poule\'s', $this->plugin_slug),
			'nevobo-team-link' => __('Link', $this->plugin_slug),
		);
		return $post_columns;
	}

	/**
	 * Sets the column headings displayed in the Nevobo match post-type admin list table.
	 * 
	 * @since      1.0.0
	 * @param      array     $post_columns      The associative array of columns.
	 * 
	 */
	public function set_nevobo_match_column_headings(array $post_columns)
	{
		$post_columns = array(
			'meta-nevobo-match-team-home' => __('Thuisteam', $this->plugin_slug),
			'meta-nevobo-match-team-away' => __('Uitteam', $this->plugin_slug),
			'meta-nevobo-match-team-type' => __('Teamtype', $this->plugin_slug),
			'taxonomy-nevobo-match-status' => __('Status', $this->plugin_slug),
			'meta-nevobo-match-location' => __('Locatie', $this->plugin_slug),
			'meta-nevobo-match-date' => __('Datum', $this->plugin_slug),
			'meta-nevobo-match-link' => __('Link', $this->plugin_slug),
		);
		return $post_columns;
	}

	/**
	 * Sets each column cell displayed in a specific custom post-type admin list table.
	 * 
	 * @since      1.0.0
	 * @param      string    $column_name       The name of the column to display.
	 * @param      int	     $post_id	        The current post ID.
	 * 
	 */
	public function set_custom_post_column_cells(string $column_name, int $post_id)
	{
		$empty_string = '—';

		// chech which column cell to print
		switch ($column_name) {
			case 'meta-nevobo-team-type':
				$team_type = get_post_meta($post_id, 'nevobo-team-type', true);
				// check if the team type is empty
				if (empty($team_type)) {
					echo $empty_string;
					return;
				}
				// default when the team type is not empty
				echo $team_type;
				return;
			case 'meta-nevobo-team-serial-number':
				$team_serial_number = intval(get_post_meta($post_id, 'nevobo-team-serial-number', true));
				// check if the team serial is empty
				if ($team_serial_number <= 0) {
					echo $empty_string;
					return;
				}
				// default when the team serial number is higher than 0
				echo $team_serial_number;
				return;
			case 'meta-nevobo-team-pool':
				$team_pool = get_post_meta($post_id, 'meta-nevobo-team-pool', true);
				// check if the team pool is empty
				if (empty($team_pool)) {
					echo $empty_string;
					return;
				}
				// default when the team pool is not empty
				echo $team_pool;
				return;
			case 'nevobo-team-link':
				$option = get_option('nevobo-beheer-association-data');
				// check if the association code is empty
				if (empty($option['association-code'])) {
					echo $empty_string;
					return;
				}
				$team_type = get_post_meta($post_id, 'nevobo-team-type', true);
				// check if the team type is empty
				if (empty($team_type)) {
					echo $empty_string;
					return;
				}
				$team_serial_number = intval(get_post_meta($post_id, 'nevobo-team-serial-number', true));
				// check if the serial number is empty
				if ($team_serial_number <= 0) {
					$link = sprintf('https://www.volleybal.nl/competitie/team/%s-%s', strtolower($option['association-code']), strtolower($team_type));
					$title = sprintf('Team %s %s - Volleybal competitie - Volleybal.nl', $option['association-name'], $team_type);
					printf('<a href="%s" title="%s" target="_blank"><span class="dashicons dashicons-external"></span></a>', esc_html($link), esc_html($title));
					return;
				}
				// default when serial number is higher than 0
				$link = sprintf('https://www.volleybal.nl/competitie/team/%s-%s-%s', strtolower($option['association-code']), strtolower($team_type), $team_serial_number);
				$title = sprintf('Team %s %s %s - Volleybal competitie - Volleybal.nl', $option['association-name'], $team_type, $team_serial_number);
				printf('<a href="%s" title="%s" target="_blank"><span class="dashicons dashicons-external"></span></a>', esc_html($link), esc_html($title));
				return;
		}
	}

	/**
	 * Sets the sortable columns list table.
	 * 
	 * @since      1.0.0
	 * @param      array	$sortable_columns	The array of sortable columns.
	 */
	public function set_custom_post_sortable_columns(array $sortable_columns)
	{
		$sortable_columns['meta-nevobo-team-type'] = 'meta-nevobo-team-type';
		$sortable_columns['meta-nevobo-team-serial-number'] = 'meta-nevobo-team-serial-number';
		$sortable_columns['taxonomy-nevobo-team-category'] = 'taxonomy-nevobo-team-category';
		// $sortable_columns['meta-nevobo-team-pool'] = 'meta-nevobo-team-pool';

		return $sortable_columns;
	}

	/**
	 * Removes the ‘Months’ drop-down from the post list table.
	 * 
	 * @since      1.0.0
	 * @param      bool		$disable	Whether to disable the drop-down. Default false.
	 * @param	   string	$post_type	The post type.
	 */
	public function disable_custom_post_months_dropdown(bool $disable, string $post_type)
	{
		switch ($post_type) {
			case 'nevobo-team':
			case 'nevobo-match':
				$disable = true;
				break;
		}
		return $disable;
	}

	/**
	 * Adds custom filter dropdowns to the admin post list table.
	 * 
	 * @since      1.0.0
	 * @param      string	$post_type		The post type slug.
	 * @param	   string	$which			The location of the extra table nav markup: 'top' or 'bottom'.
	 */
	public function add_custom_post_filter_dropdown(string $post_type, string $which)
	{
		switch ($post_type) {
			case 'nevobo-team':
				/**
				 * To do: Add filters based on meta values (team type and team searial number)
				 */
				$slug = 'nevobo-team-category';
				$wp_list_table = _get_list_table('WP_Posts_List_Table');
				$taxonomy = get_taxonomy($slug);
				if (!$wp_list_table->has_items()) {
					wp_dropdown_categories(
						array(
							'show_option_none' => $taxonomy->labels->all_items,
							'taxonomy' => $slug,
						)
					);
				} else {
					$selected = isset($_REQUEST[$slug]) ? $_REQUEST[$slug] : '';
					wp_dropdown_categories(
						array(
							'show_option_all' => $taxonomy->labels->all_items,
							'orderby' => 'term_id',
							'hierarchical' => $taxonomy->hierarchical ? 1 : 0,
							'name' => $slug,
							'selected' => $selected,
							'value_field' => 'slug',
							'taxonomy' => $slug,
						)
					);
				}
				return;
		}
	}

	/**
	 * Sets custom orderby query parameters when ordering based on custom data.
	 * 
	 * @since      1.0.0
	 * @param      WP_Query 	$query		The WP_Query instance (passed by reference).
	 */
	public function set_custom_post_columns_order(WP_Query $query)
	{
		// check if the request is for the admin page and if the query is not the main query
		if (!is_admin() || !$query->is_main_query()) {
			return;
		}
		// get the post type of the query
		$post_type = $query->get('post_type');
		// check if the post type is a Nevobo team
		if (!in_array($post_type, array('nevobo-team'))) {
			return;
		}
		// get the order by of the query
		$orderby = $query->get('orderby');

		// $file = plugin_dir_path(__FILE__) . '/log.txt';
		// file_put_contents($file, print_r($query, true) . "\n", FILE_APPEND);

		switch ($orderby) {
			case '': // default order by
				$query->set('orderby', 'title');
				$query->set('order', 'ASC');
				return;
			case 'meta-nevobo-team-type':
				$query->set('meta_key', 'nevobo-team-type');
				$query->set('orderby', 'meta_value');
				return;
			case 'meta-nevobo-team-serial-number':
				$query->set('meta_key', 'nevobo-team-serial-number');
				$query->set('orderby', 'meta_value_num');
				return;
				// case 'meta-nevobo-team-pool':
				// 	$query->set('meta_key', 'nevobo-team-pool');
				// 	$query->set('orderby', 'meta_value_num');
				// 	return;
		}
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{
		// enqueue the stylesheets for the admin area
		wp_enqueue_style($this->plugin_slug, plugin_dir_url(__FILE__) . 'css/nevobo-beheer-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the Nevobo-team block editor assets.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_nevobo_team_block_editor_assets()
	{
		// check if the loaded post-type is the Nevobo-team post-type, if not return
		if (get_post_type() !== 'nevobo-team') {
			return;
		}

		// check if there are dependencies that need to be loaded
		$path = plugin_dir_path(__DIR__) . 'block-editor/assets/index.asset.php';

		if (file_exists($path)) {
			$asset = require $path;
		} else {
			$asset = array('dependencies' => array(), 'version' => $this->version);
		}

		// enqueue the script
		wp_enqueue_script(
			'nevobo-beheer-team-block-editor-assets',
			plugin_dir_url(__DIR__) . 'block-editor/assets/index.js',
			$asset['dependencies'],
			$asset['version'],
			true,
		);
	}

	/**
	 * Register the Nevobo-team members block type using the metadata loaded from the `block.json` file.
	 *
	 * @since    1.0.0
	 */
	public function register_nevobo_team_members_block_type()
	{
		// register the block type
		register_block_type(plugin_dir_path(__DIR__) . '/block-editor/blocks/nevobo-team-members');
	}

	/**
	 * Register the Nevobo-team competition tabs block type using the metadata loaded from the `block.json` file.
	 *
	 * @since    1.0.0
	 */
	public function register_nevobo_team_competition_tabs_block_type()
	{
		// register the block type
		register_block_type(plugin_dir_path(__DIR__) . '/block-editor/blocks/nevobo-team-competition-tabs');
	}

	// /**
	//  * Register the JavaScript for the admin area.
	//  *
	//  * @since    1.0.0
	//  */
	// public function enqueue_scripts()
	// {
	// 	// enqueue the javascript for the admin area
	// 	wp_enqueue_script($this->plugin_slug, plugin_dir_url(__FILE__) . 'js/nevobo-beheer-admin.js', array('jquery'), $this->version, false);
	// }
}
