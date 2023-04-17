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
				if (empty($team_type)) {
					echo '—';
				} else {
					echo $team_type;
				}
				return;
			case 'meta-nevobo-team-serial-number':
				$serial_number = (int)get_post_meta($post_id, 'nevobo-team-serial-number', true);
				if ($serial_number === 0) {
					echo '—';
				} else {
					echo $serial_number;
				}
				return;
			case 'meta-nevobo-team-pool':
				$pool = get_post_meta($post_id, 'meta-nevobo-team-pool', true);
				if (empty($pool)) {
					echo '—';
				} else {
					echo $pool;
				}
				return;
			case 'nevobo-team-link':
				$association_name = 'Morgana DVO'; 	// to do: get association name
				$association_code = 'CKL8C6M'; 		// to do: get association code
				$team_type = get_post_meta($post_id, 'nevobo-team-type', true);
				$team_serial_number = get_post_meta($post_id, 'nevobo-team-serial-number', true);
				if ($team_type === '' || $team_serial_number === '') {
					echo '—';
				} else {
					$link = sprintf('https://www.volleybal.nl/competitie/team/%s-%s-%s', strtolower($association_code), strtolower($team_type), strtolower($team_serial_number));
					$title = sprintf('Team %s %s %s - Volleybal competitie - Volleybal.nl', $association_name, $team_type, $team_serial_number);
					printf('<a href="%s" title="%s" target="_blank"><span class="dashicons dashicons-external"></span></a>', esc_html($link), esc_html($title));
				}
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
			case '':
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
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		// enqueue the javascript for the admin area
		wp_enqueue_script($this->plugin_slug, plugin_dir_url(__FILE__) . 'js/nevobo-beheer-admin.js', array('jquery'), $this->version, false);
	}
}
