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
				'page_title' => __('Nevobo team- en competitiebeheer', $this->plugin_slug),
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
			 * Nevobo beheer settings
			 */
			array(
				'option_group' => 'nevobo-beheer-association-settings-group',
				'option_name' => 'nevobo-beheer-association-settings',
				'args' => array(
					'type' => 'array',
					'description' => __('Instellingengroep betreffende de vereniging.', $this->plugin_slug),
					'sanitize_callback' => array($this->callbacks, 'association_settings_sanitize_callback'),
					'show_in_rest' => array(
						'schema' => array(
							'type'  => 'array',
							'items' => array(
								'type' => 'string',
							),
						),
					),
					'default' => array(
						'association-code' => '',
						'association-name' => '',
						'association-region' => '',
						'association-province' => '',
						'association-commune' => '',
						'association-location' => '',
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
			 * Association settings section
			 */
			array(
				'id' => 'nevobo-beheer-association-settings-section',
				'title' => __('Verenigingsinstellingen', $this->plugin_slug),
				'callback' => function () {
					$description = esc_html(__('Vul hieronder de Nevobo verenigingscode in. Bij het opslaan van de wijzigingen zullen de overige velden automatisch worden ingevuld.', $this->plugin_slug));
					printf('<p>%s</p>', $description);
				},
				'page' => 'nevobo-beheer-association-settings',
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
				'id' => 'nevobo-beheer-association-code',
				'title' => __('Verenigingscode', $this->plugin_slug),
				'callback' => array($this->callbacks, 'association_settings_fields_callback'),
				'page' => 'nevobo-beheer-association-settings',
				'section' => 'nevobo-beheer-association-settings-section',
				'args' => array(
					'label-for' => 'association-code',
					'class' => 'nevobo-beheer-association-code regular-text',
					'option' => 'nevobo-beheer-association-settings',
					'type' => 'text',
					'readonly' => false,
					'description' => __('De Nevobo verenigingscode, welke gebruikt wordt om de competitiegegevens op te halen.', $this->plugin_slug),
				),
			),
			/**
			 * Verenigingsnaam
			 */
			array(
				'id' => 'nevobo-beheer-association-name',
				'title' => __('Verenigingsnaam', $this->plugin_slug),
				'callback' => array($this->callbacks, 'association_settings_fields_callback'),
				'page' => 'nevobo-beheer-association-settings',
				'section' => 'nevobo-beheer-association-settings-section',
				'args' => array(
					'label-for' => 'association-name',
					'class' => 'nevobo-beheer-association-name regular-text',
					'option' => 'nevobo-beheer-association-settings',
					'type' => 'text',
					'readonly' => true,
					'description' => __('De verenigingsnaam wordt o.a. gebruikt om vast te stellen of een team bij de betreffende vereniging hoort.', $this->plugin_slug),
				),
			),
			/**
			 * Verenigingsplaats
			 */
			array(
				'id' => 'nevobo-beheer-association-location',
				'title' => __('Verenigingsplaats', $this->plugin_slug),
				'callback' => array($this->callbacks, 'association_settings_fields_callback'),
				'page' => 'nevobo-beheer-association-settings',
				'section' => 'nevobo-beheer-association-settings-section',
				'args' => array(
					'label-for' => 'association-location',
					'class' => 'nevobo-beheer-association-location regular-text',
					'option' => 'nevobo-beheer-association-settings',
					'type' => 'text',
					'readonly' => true,
					'description' => __('De verenigingsplaats wordt o.a. gebruikt om vast te stellen of een wedstrijd thuis of uit gespeeld wordt.', $this->plugin_slug),
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
	 * Sets the column headings displayed in the Nevobo Teams post-type admin list table.
	 * 
	 * @since      1.0.0
	 * @param      array     $post_columns      The associative array of columns.
	 * 
	 */
	public function set_nevobo_teams_column_headings(array $post_columns)
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
			'meta-nevobo-team-pool' => __('Poule', $this->plugin_slug),
			'nevobo-team-link' => 'Link',
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
		switch ($column_name) {
			case 'meta-nevobo-team-type':
				$team_type = get_post_meta($post_id, 'nevobo-team-type', true);
				// check if the team type has been set
				if (!empty($team_type)) {
					echo $team_type;
					return;
				}
				echo '—';
				return;
			case 'meta-nevobo-team-serial-number':
				$serial_number = (int)get_post_meta($post_id, 'nevobo-team-serial-number', true);
				// check if the team serial number has been set
				if ($serial_number > 0) {
					echo $serial_number;
					return;
				}
				echo '—';
				return;
			case 'meta-nevobo-team-pool':
				$pool = get_post_meta($post_id, 'meta-nevobo-team-pool', true);
				// check if the team pool has been set
				if (!empty($pool)) {
					echo $pool;
					return;
				}
				echo '—';
				return;
			case 'nevobo-team-link':
				$option = get_option('nevobo-beheer-association-settings');
				$team_meta = get_post_meta($post_id);
				$team_type = current($team_meta['nevobo-team-type']);
				$team_serial_number = (int)current($team_meta['nevobo-team-serial-number']);
				// check if the team type has been set and if the team serial number is larger than 0
				if (!empty($team_type) && $team_serial_number > 0) {
					$link = sprintf('https://www.volleybal.nl/competitie/team/%s-%s-%s', strtolower($option['association-code']), strtolower($team_type), $team_serial_number);
					$title = sprintf('Team %s %s %s - Volleybal competitie - Volleybal.nl', $option['association-name'], $team_type, $team_serial_number);
					printf('<a href="%s" title="%s" target="_blank"><span class="dashicons dashicons-external"></span></a>', esc_html($link), esc_html($title));
					return;
				}
				// check if the team type has been set and if the team serial number is equal to 0
				if (!empty($team_type) && $team_serial_number === 0) {
					$link = sprintf('https://www.volleybal.nl/competitie/team/%s-%s', strtolower($option['association-code']), strtolower($team_type));
					$title = sprintf('Team %s %s - Volleybal competitie - Volleybal.nl', $option['association-name'], $team_type);
					printf('<a href="%s" title="%s" target="_blank"><span class="dashicons dashicons-external"></span></a>', esc_html($link), esc_html($title));
					return;
				}
				echo '—';
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
		$sortable_columns['meta-nevobo-team-pool'] = 'meta-nevobo-team-pool';

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
