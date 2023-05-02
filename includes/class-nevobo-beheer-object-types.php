<?php

/**
 * The global object types of the plugin.
 *
 * @link       https://www.wouterluimes.nl
 * @since      1.0.0
 *
 * @package    Nevobo_Beheer
 * @subpackage Nevobo_Beheer/includes
 */

/**
 * The global object types of the plugin.
 *
 * Defines the plugin slug, version, and hooks to enqueue the custom object types.
 *
 * @package    Nevobo_Beheer
 * @subpackage Nevobo_Beheer/includes
 * @author     Wouter Luimes <contact@wouterluimes.nl>
 */
class Nevobo_Beheer_Objects
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
     * @var      string    $version        The current version of this plugin.
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
     * Register all the custom post-types.
     *
     * @since    1.0.0
     * @access   public
     */
    public function register_custom_post_types()
    {
        /**
         * The array containing all the custom post-types to register
         */
        $post_types = array(
            /**
             * The Nevobo team custom post-type
             */
            array(
                'post_type' => 'nevobo-team',
                'args' => array(
                    'label' => __('Teams', $this->plugin_slug),
                    'labels' => array(
                        'name' => __('Teams', $this->plugin_slug),
                        'singular_name' => __('Team', $this->plugin_slug),
                        'add_new' => __('Nieuw team', $this->plugin_slug),
                        'add_new_item' => __('Nieuw team toevoegen', $this->plugin_slug),
                        'edit_item' => __('Team bewerken', $this->plugin_slug),
                        'new_item' => __('Nieuw team', $this->plugin_slug),
                        'view_item' => __('Team bekijken', $this->plugin_slug),
                        'view_items' => __('Teams bekijken', $this->plugin_slug),
                        'search_items' => __('Teams zoeken', $this->plugin_slug),
                        'not_found' => __('Geen teams gevonden.', $this->plugin_slug),
                        'not_found_in_trash' => __('Geen teams gevonden in de prullenbak.', $this->plugin_slug),
                        'parent_item_colon' => __('Hoofdteam', $this->plugin_slug), // not used
                        'all_items' => __('Alle teams', $this->plugin_slug),
                        'archives' => __('Teamsarchief', $this->plugin_slug),
                        'attributes' => __('Teamattributen', $this->plugin_slug),
                        'insert_into_item' => __('In team invoegen', $this->plugin_slug),
                        'uploaded_to_this_item' => __('Geüpload naar dit team', $this->plugin_slug),
                        // 'featured_image' => __('Uitgelichte afbeelding', $this->plugin_slug),
                        // 'set_featured_image' => __('Uitgelichte afbeelding kiezen', $this->plugin_slug),
                        // 'remove_featured_image' => __('Uitgelichte afbeelding verwijderen', $this->plugin_slug),
                        // 'use_featured_image' => __('Als uitgelichte afbeelding gebruiken', $this->plugin_slug),
                        // 'menu_name' => __('Teams', $this->plugin_slug), // same as name
                        'filter_items_list' => __('Teamlijst filteren', $this->plugin_slug),
                        // 'filter_by_date' => __('Op datum filteren', $this->plugin_slug),
                        'items_list_navigation' => __('Teamlijst-navigatie', $this->plugin_slug),
                        'items_list' => __('Teamlijst', $this->plugin_slug),
                        'item_published' => __('Team gepubliceerd.', $this->plugin_slug),
                        'item_published_privately' => __('Team privé gepubliceerd.', $this->plugin_slug),
                        'item_reverted_to_draft' => __('Team teruggezet naar concept.', $this->plugin_slug),
                        'item_scheduled' => __('Team gepland.', $this->plugin_slug),
                        'item_updated' => __('Team geüpdatet.', $this->plugin_slug),
                        'item_link' => __('Teamlink', $this->plugin_slug),
                        'item_link_description' => __('Een link naar een team.', $this->plugin_slug),
                    ),
                    'description' => __('Het Nevobo-team custom post-type', $this->plugin_slug),
                    'public' => true,
                    'hierarchical' => false,
                    'exclude_from_search' => false,
                    'publicly_queryable' => true,
                    'show_ui' => true,
                    // 'show_in_menu ' => true,
                    // 'show_in_nav_menus' => true,
                    // 'show_in_admin_bar' => true,
                    'show_in_rest' => true,
                    // 'rest_base' => '',
                    // 'rest_namespace' => 'wp/v2',
                    // 'rest_controller_class' => 'WP_REST_Posts_Controller',
                    'menu_position' => 30,
                    'menu_icon' => 'dashicons-groups',
                    // 'capability_type' => 'post',
                    // 'capabilities' => array(),
                    // 'map_meta_cap' => false,
                    'supports' => array('title', 'editor', 'custom-fields'), // 'post-formats'), // 'thumbnail', 'revisions',
                    // 'register_meta_box_cb' => function(){},
                    'taxonomies' => array('nevobo-team-category'),
                    'has_archive' => false,
                    'rewrite' => array(
                        'slug' => __('teams', $this->plugin_slug),
                        'feeds' => true,
                    ),
                    // 'query_var' => '',
                    'can_export' => true,
                    'delete_with_user' => false,
                    // 'template' => [],
                    // 'template_lock' => false,
                )
            ),
            /**
             * The Nevobo match custom post-type
             */
            array(
                'post_type' => 'nevobo-match',
                'args' => array(
                    'label' => __('Wedstrijden', $this->plugin_slug),
                    'labels' => array(
                        'name' => __('Wedstrijden', $this->plugin_slug),
                        'singular_name' => __('Wedstrijd', $this->plugin_slug),
                        'add_new' => __('Nieuwe wedstrijd', $this->plugin_slug),
                        'add_new_item' => __('Nieuwe wedstrijd toevoegen', $this->plugin_slug),
                        'edit_item' => __('Wedstrijd bewerken', $this->plugin_slug),
                        'new_item' => __('Nieuwe wedstrijd', $this->plugin_slug),
                        'view_item' => __('Wedstrijd bekijken', $this->plugin_slug),
                        'view_items' => __('Wedstrijden bekijken', $this->plugin_slug),
                        'search_items' => __('Wedstrijden zoeken', $this->plugin_slug),
                        'not_found' => __('Geen wedstrijden gevonden.', $this->plugin_slug),
                        'not_found_in_trash' => __('Geen wedstrijden gevonden in de prullenbak.', $this->plugin_slug),
                        'parent_item_colon' => __('Hoofdwedstrijd', $this->plugin_slug), // not used
                        'all_items' => __('Alle wedstrijden', $this->plugin_slug), // Alle wedstrijden
                        'archives' => __('Wedstrijdenarchief', $this->plugin_slug),
                        'attributes' => __('Wedstrijdattributen', $this->plugin_slug),
                        'insert_into_item' => __('In wedstrijd invoegen', $this->plugin_slug),
                        'uploaded_to_this_item' => __('Geüpload naar deze wedstrijd', $this->plugin_slug),
                        // 'featured_image' => __('Uitgelichte afbeelding', $this->plugin_slug),
                        // 'set_featured_image' => __('Uitgelichte afbeelding kiezen', $this->plugin_slug),
                        // 'remove_featured_image' => __('Uitgelichte afbeelding verwijderen', $this->plugin_slug),
                        // 'use_featured_image' => __('Als uitgelichte afbeelding gebruiken', $this->plugin_slug),
                        // 'menu_name' => __('Teams', $this->plugin_slug), // same as name
                        'filter_items_list' => __('Wedstrijdlijst filteren', $this->plugin_slug),
                        // 'filter_by_date' => __('Op datum filteren', $this->plugin_slug),
                        'items_list_navigation' => __('Wedstrijdlijst-navigatie', $this->plugin_slug),
                        'items_list' => __('Wedstrijdlijst', $this->plugin_slug),
                        'item_published' => __('Wedstrijd gepubliceerd.', $this->plugin_slug),
                        'item_published_privately' => __('Wedstrijd privé gepubliceerd.', $this->plugin_slug),
                        'item_reverted_to_draft' => __('Wedstrijd teruggezet naar concept.', $this->plugin_slug),
                        'item_scheduled' => __('Wedstrijd gepland.', $this->plugin_slug),
                        'item_updated' => __('Wedstrijd geüpdatet.', $this->plugin_slug),
                        'item_link' => __('Wedstrijdlink', $this->plugin_slug),
                        'item_link_description' => __('Een link naar een wedstrijd.', $this->plugin_slug),
                    ),
                    'description' => __('De Nevobo-wedstrijd custom post-type', $this->plugin_slug),
                    'public' => true,
                    'hierarchical' => false,
                    'exclude_from_search' => false,
                    'publicly_queryable' => true,
                    'show_ui' => true,
                    'show_in_menu' => 'nevobo-competition',
                    // 'show_in_nav_menus' => true,
                    // 'show_in_admin_bar' => true,
                    'show_in_rest' => true,
                    // 'rest_base' => '',
                    // 'rest_namespace' => 'wp/v2',
                    // 'rest_controller_class' => 'WP_REST_Posts_Controller',
                    // 'menu_position' => null,
                    // 'menu_icon' => '',
                    // 'capability_type' => 'post',
                    // 'capabilities' => array(),
                    // 'capabilities' => array(
                    //     'edit_post' => 'do_not_allow',
                    //     'read_post' => 'edit_posts',
                    //     'delete_post' => 'do_not_allow',
                    //     'edit_posts' => 'edit_posts',
                    //     'edit_others_posts' => 'do_not_allow',
                    //     'delete_posts' => 'do_not_allow',
                    //     'publish_posts' => 'do_not_allow',
                    //     'read_private_posts' => 'do_not_allow',
                    //     'create_posts' => 'do_not_allow',
                    // ),
                    // 'map_meta_cap' => false,
                    'supports' => array('title', 'custom-fields'),
                    // 'register_meta_box_cb' => function(){},
                    'taxonomies' => array('nevobo-match-type'),
                    'has_archive' => false,
                    'rewrite' => array(
                        'slug' => __('wedstrijd', $this->plugin_slug),
                        'feeds' => true,
                    ),
                    // 'query_var' => '',
                    'can_export' => true,
                    'delete_with_user' => false,
                    // 'template' => [],
                    // 'template_lock' => false,
                )
            ),
            // Todo: Nevobo competition pool custom post types
        );

        /**
         * Loop to register all the custom post-types in the array.
         */
        foreach ($post_types as $post_type) {
            register_post_type(
                $post_type['post_type'],
                $post_type['args'],
            );
        }
    }

    /**
     * Register all the custom post-type meta keys.
     *
     * @since    1.0.0
     * @access   public
     */
    public function register_custom_post_meta_keys()
    {
        /**
         * The array containing all the custom post-type meta keys to register
         */
        $post_meta_keys = array(
            /**
             * Team - teamtype
             */
            array(
                'post_type' => 'nevobo-team',
                'meta_key' => 'nevobo-team-type',
                'args' => array(
                    // 'object_subtype' => '',
                    'type' => 'string',
                    'description' => __('Het type van het team.', $this->plugin_slug),
                    'single' => true,
                    'default' => '',
                    // 'sanitize_callback' => function(){},
                    // 'auth_callback' => function(){},
                    'show_in_rest' => true,
                ),
            ),
            /**
             * Team - serial number
             */
            array(
                'post_type' => 'nevobo-team',
                'meta_key' => 'nevobo-team-serial-number',
                'args' => array(
                    // 'object_subtype' => '',
                    'type' => 'integer',
                    'description' => __('Het volgnummer van het team.', $this->plugin_slug),
                    'single' => true,
                    'default' => 0,
                    // 'sanitize_callback' => function(){},
                    // 'auth_callback' => function(){},
                    'show_in_rest' => true,
                ),
            ),
            /**
             * 
             * Match - id
             */
            array(
                'post_type' => 'nevobo-match',
                'meta_key' => 'nevobo-match-id',
                'args' => array(
                    // 'object_subtype' => '',
                    'type' => 'integer',
                    'description' => __('Het ID-nummer van de wedstrijd.', $this->plugin_slug),
                    'single' => true,
                    'default' => 0,
                    // 'sanitize_callback' => function(){},
                    // 'auth_callback' => function(){},
                    'show_in_rest' => true,
                ),
            ),
            /**
             * Match - timestamp
             */
            array(
                'post_type' => 'nevobo-match',
                'meta_key' => 'nevobo-match-timestamp',
                'args' => array(
                    // 'object_subtype' => '',
                    'type' => 'integer',
                    'description' => __('De Unix-tijd van de wedstrijd.', $this->plugin_slug),
                    'single' => true,
                    'default' => 0,
                    // 'sanitize_callback' => function(){},
                    // 'auth_callback' => function(){},
                    'show_in_rest' => true,
                ),
            ),
            /**
             * Match - status
             */
            array(
                'post_type' => 'nevobo-match',
                'meta_key' => 'nevobo-match-status',
                'args' => array(
                    // 'object_subtype' => '',
                    'type' => 'string',
                    'description' => __('De status van de wedstrijd.', $this->plugin_slug),
                    'single' => true,
                    'default' => '',
                    // 'sanitize_callback' => function(){},
                    // 'auth_callback' => function(){},
                    'show_in_rest' => true,
                ),
            ),
            /**
             * Match - match code
             */
            array(
                'post_type' => 'nevobo-match',
                'meta_key' => 'nevobo-match-code',
                'args' => array(
                    // 'object_subtype' => '',
                    'type' => 'string',
                    'description' => __('De wedstrijdcode van de wedstrijd.', $this->plugin_slug),
                    'single' => true,
                    'default' => '',
                    // 'sanitize_callback' => function(){},
                    // 'auth_callback' => function(){},
                    'show_in_rest' => true,
                ),
            ),
            /** 
             * Match - team home
             */
            array(
                'post_type' => 'nevobo-match',
                'meta_key' => 'nevobo-match-team-home',
                'args' => array(
                    // 'object_subtype' => '',
                    'type' => 'string',
                    'description' => __('Het thuisteam van de wedstrijd.', $this->plugin_slug),
                    'single' => true,
                    'default' => '',
                    // 'sanitize_callback' => function(){},
                    // 'auth_callback' => function(){},
                    'show_in_rest' => true,
                ),
            ),
            /**
             * Match - team away
             */
            array(
                'post_type' => 'nevobo-match',
                'meta_key' => 'nevobo-match-team-away',
                'args' => array(
                    // 'object_subtype' => '',
                    'type' => 'string',
                    'description' => __('Het uitteam van de wedstrijd.', $this->plugin_slug),
                    'single' => true,
                    'default' => '',
                    // 'sanitize_callback' => function(){},
                    // 'auth_callback' => function(){},
                    'show_in_rest' => true,
                ),
            ),
            /**
             * Match - team type
             */
            array(
                'post_type' => 'nevobo-match',
                'meta_key' => 'nevobo-match-team-type',
                'args' => array(
                    // 'object_subtype' => '',
                    'type' => 'string',
                    'description' => __('Het type van de teams van de wedstrijd.', $this->plugin_slug),
                    'single' => true,
                    'default' => '',
                    // 'sanitize_callback' => function(){},
                    // 'auth_callback' => function(){},
                    'show_in_rest' => true,
                ),
            ),
            /**
            * To do: add meta keys for the Nevobo programme, results and competition pool post types
            */
        );

        /**
         * Loop to register all the custom post-type meta keys in the array.
         */
        foreach ($post_meta_keys as $post_meta) {
            register_post_meta(
                $post_meta['post_type'],
                $post_meta['meta_key'],
                $post_meta['args'],
            );
        }
    }

    /**
     * Register all the custom taxonomies.
     *
     * @since    1.0.0
     * @access   public
     */
    public function register_custom_taxonomies()
    {
        /**
         * The array containing all the custom taxonomies to register
         */
        $taxonomies = array(
            /**
             * The Nevobo team category custom taxonomy
             */
            array(
                'taxonomy' => 'nevobo-team-category',
                'object_type' => array('nevobo-team'),
                'args' => array(
                    'labels' => array(
                        'name' => __('Teamcategorieën', $this->plugin_slug),
                        'singular_name' => __('Teamcategorie', $this->plugin_slug),
                        'search_items' => __('Teamcategorieën zoeken', $this->plugin_slug),
                        'popular_items' => __('Populaire teamcategorieën', $this->plugin_slug),
                        'all_items' => __('Alle teamcategorieën', $this->plugin_slug),
                        'parent_item' => __('Hoofdteamcategorie', $this->plugin_slug),
                        'parent_item_colon' => __('Hoofdteamcategorie:', $this->plugin_slug),
                        // 'name_field_description' => __('De naam is zoals deze op je site verschijnt.', $this->plugin_slug),
                        // 'slug_field_description' => __('De \"slug\" is de URL vriendelijke versie van de naam. Het is meestal allemaal kleine letters en bevat alleen letters, cijfers en koppeltekens.', $this->plugin_slug),
                        // 'parent_field_description' => __('Een hoofd term toewijzen om een hiërarchie te maken. De term Jazz zou bijvoorbeeld het hoofd zijn van Bebop en Big Band.', $this->plugin_slug),
                        // 'desc_field_description' => __('De beschrijving is standaard niet prominent aanwezig; maar sommige thema\'s kunnen ze echter wel tonen.', $this->plugin_slug),
                        'edit_item' => __('Teamcategorie bewerken', $this->plugin_slug),
                        'view_item' => __('Teamcategorie bekijken', $this->plugin_slug),
                        'update_item' => __('Teamcategorie updaten', $this->plugin_slug),
                        'add_new_item' => __('Nieuwe teamcategorie toevoegen', $this->plugin_slug),
                        'new_item_name' => __('Nieuwe teamcategorienaam', $this->plugin_slug),
                        'separate_items_with_commas' => __('Teamcategorieën scheiden door komma\'s', $this->plugin_slug),
                        'add_or_remove_items' => __('Teamcategorieën toevoegen of verwijderen', $this->plugin_slug),
                        'choose_from_most_used' => __('Kies uit de meest gebruikte teamcategorieën', $this->plugin_slug),
                        'not_found' => __('Geen teamcategorieën gevonden.', $this->plugin_slug),
                        'no_terms' => __('Geen teamcategorieën', $this->plugin_slug),
                        'filter_by_item' => __('Op teamcategorie filteren', $this->plugin_slug),
                        'items_list_navigation' => __('Navigatie door teamcategorielijst', $this->plugin_slug),
                        'items_list' => __('Teamcategorielijst', $this->plugin_slug),
                        // 'most_used' => __('Meest gebruikt', $this->plugin_slug),
                        'back_to_items' => __('&larr; Ga naar teamcategorieën', $this->plugin_slug),
                        'item_link' => __('Teamcategorie link', $this->plugin_slug),
                        'item_link_description' => __('Een link naar een teamcategorie.', $this->plugin_slug),
                    ),
                    'description' => __('De Nevobo-teamcategorie custom taxonomy', $this->plugin_slug),
                    'public' => true,
                    'publicly_queryable' => true,
                    'hierarchical' => true,
                    'show_ui' => false, // true, // false
                    // 'show_in_menu' => true,
                    // 'show_in_nav_menus' => true,
                    'show_in_rest' => true,
                    // 'rest_base' => '',
                    // 'rest_namespace' => 'wp/v2',
                    // 'rest_controller_class' => 'WP_REST_Terms_Controller',
                    'show_tagcloud' => false,
                    'show_in_quick_edit' => false,
                    'show_admin_column' => false, // true,
                    // 'meta_box_cb' => function(){},
                    // 'meta_box_sanitize_cb' => function(){},
                    // 'capabilities' => array( // 
                    //     'manage_terms' => 'do_not_allow',
                    //     'edit_terms' => 'do_not_allow',
                    //     'delete_terms' => 'do_not_allow',
                    //     'assign_terms' => 'edit_posts',
                    // ),
                    // 'capabilities' => array(
                    //     'manage_terms' => 'manage_categories',
                    //     'edit_terms' => 'manage_categories',
                    //     'delete_terms' => 'manage_categories',
                    //     'assign_terms' => 'edit_posts',
                    // ),
                    'rewrite' => array(
                        'slug' => __('teamcategorie', $this->plugin_slug),
                    ),
                    // 'query_var' => '',
                    // 'update_count_callback' => function(){},
                    // 'default_term' => array(),
                    'sort' => true,
                    // 'args' => '',
                ),
            ),
            /**
             * The Nevobo match status custom taxonomy
             */
            array(
                'taxonomy' => 'nevobo-match-status',
                'object_type' => array('nevobo-match'),
                'args' => array(
                    'labels' => array(
                        'name' => __('Wedstrijdstatussen', $this->plugin_slug),
                        'singular_name' => __('Wedstrijdstatus', $this->plugin_slug),
                        'search_items' => __('Wedstrijdstatussen zoeken', $this->plugin_slug),
                        'popular_items' => __('Populaire wedstrijdstatussen', $this->plugin_slug),
                        'all_items' => __('Alle wedstrijdstatussen', $this->plugin_slug),
                        'parent_item' => __('Hoofdwedstrijdstatus', $this->plugin_slug), // not used
                        'parent_item_colon' => __('Hoofdwedstrijdstatus:', $this->plugin_slug), // not used
                        // 'name_field_description' => __('De naam is zoals deze op je site verschijnt.', $this->plugin_slug),
                        // 'slug_field_description' => __('De \"slug\" is de URL vriendelijke versie van de naam. Het is meestal allemaal kleine letters en bevat alleen letters, cijfers en koppeltekens.', $this->plugin_slug),
                        // 'parent_field_description' => __('Een hoofd term toewijzen om een hiërarchie te maken. De term Jazz zou bijvoorbeeld het hoofd zijn van Bebop en Big Band.', $this->plugin_slug),
                        // 'desc_field_description' => __('De beschrijving is standaard niet prominent aanwezig; maar sommige thema\'s kunnen ze echter wel tonen.', $this->plugin_slug),
                        'edit_item' => __('Wedstrijdstatus bewerken', $this->plugin_slug),
                        'view_item' => __('Wedstrijdstatus bekijken', $this->plugin_slug),
                        'update_item' => __('Wedstrijdstatus updaten', $this->plugin_slug),
                        'add_new_item' => __('Nieuwe wedstrijdstatus toevoegen', $this->plugin_slug),
                        'new_item_name' => __('Nieuwe wedstrijdstatusnaam', $this->plugin_slug),
                        'separate_items_with_commas' => __('Wedstrijdstatussen scheiden door komma\'s', $this->plugin_slug),
                        'add_or_remove_items' => __('Wedstrijdstatussen toevoegen of verwijderen', $this->plugin_slug),
                        'choose_from_most_used' => __('Kies uit de meest gebruikte wedstrijdstatussen', $this->plugin_slug),
                        'not_found' => __('Geen wedstrijdstatussen gevonden.', $this->plugin_slug),
                        'no_terms' => __('Geen wedstrijdstatussen', $this->plugin_slug),
                        'filter_by_item' => __('Op wedstrijdstatus filteren', $this->plugin_slug),
                        'items_list_navigation' => __('Navigatie door wedstrijdstatuslijst', $this->plugin_slug),
                        'items_list' => __('Wedstrijdstatuslijst', $this->plugin_slug),
                        // 'most_used' => __('Meest gebruikt', $this->plugin_slug),
                        'back_to_items' => __('&larr; Ga naar wedstrijdstatussen', $this->plugin_slug),
                        'item_link' => __('Wedstrijdstatus link', $this->plugin_slug),
                        'item_link_description' => __('Een link naar een wedstrijdstatus.', $this->plugin_slug),
                    ),
                    'description' => __('De Nevobo-wedstrijdstatus custom taxonomy', $this->plugin_slug),
                    'public' => true,
                    'publicly_queryable' => true,
                    'hierarchical' => false,
                    'show_ui' => false,
                    // 'show_in_menu' => true,
                    // 'show_in_nav_menus' => true,
                    'show_in_rest' => true,
                    // 'rest_base' => '',
                    // 'rest_namespace' => 'wp/v2',
                    // 'rest_controller_class' => 'WP_REST_Terms_Controller',
                    'show_tagcloud' => false,
                    'show_in_quick_edit' => false,
                    'show_admin_column' => false,
                    // 'meta_box_cb' => function(){},
                    // 'meta_box_sanitize_cb' => function(){},
                    // 'capabilities' => array(),
                    'rewrite' => array(
                        'slug' => __('wedstrijdstatus', $this->plugin_slug),
                    ),
                    // 'query_var' => '',
                    // 'update_count_callback' => function(){},
                    // 'default_term' => array(),
                    'sort' => true,
                    // 'args' => '',
                )
            ),
        );

        /**
         * Loop to register all custom taxonomies in the array
         */
        foreach ($taxonomies as $taxonomy) {
            register_taxonomy(
                $taxonomy['taxonomy'],
                $taxonomy['object_type'],
                $taxonomy['args'],
            );
        }
    }

    /**
     * Register all the custom terms meta keys.
     *
     * @since    1.0.0
     * @access   public
     */
    public function register_custom_term_meta_keys()
    {
        /**
         * The array containing all the custom term meta keys to register.
         */
        $term_meta_keys = array(
            /**
             * Team type abbreviation
             */
            array(
                'taxonomy' => 'nevobo-team-category',
                'meta_key' => 'nevobo-team-type',
                'args' => array(
                    // 'object_subtype' => '',
                    'type' => 'string',
                    'description' => __('Het type van het team.', $this->plugin_slug),
                    'single' => true,
                    'default' => '',
                    // 'sanitize_callback' => function(){},
                    // 'auth_callback' => function(){},
                    'show_in_rest' => true,
                ),
            ),
        );

        /**
         * Loop to register all the custom term meta keys in the array.
         */
        foreach ($term_meta_keys as $term_meta) {
            register_term_meta(
                $term_meta['taxonomy'],
                $term_meta['meta_key'],
                $term_meta['args'],
            );
        }
    }

    /**
     * Insert all the default custom terms.
     *
     * @since    1.0.0
     * @access   public
     */
    public function insert_custom_terms()
    {
        /**
         * The array containing all the custom terms to insert
         */
        $terms = array(
            /**
             * The Nevobo team category custom terms
             */
            array(
                'taxonomy' => 'nevobo-team-category',
                'terms' => array(
                    /**
                     * Senioren
                     */
                    array(
                        'slug' => 'senioren',
                        'name' => __('Senioren', $this->plugin_slug),
                        'children' => array(
                            array(
                                'slug' => 'dames',
                                'name' => __('Dames Senioren', $this->plugin_slug), // Dames
                                'meta' => array(
                                    'nevobo-team-type' => 'DS',
                                )
                            ),
                            array(
                                'slug' => 'heren',
                                'name' => __('Heren Senioren', $this->plugin_slug), // Heren
                                'meta' => array(
                                    'nevobo-team-type' => 'HS',
                                ),
                            ),
                        )
                    ),
                    /**
                     * Jeugd
                     */
                    array(
                        'slug' => 'jeugd',
                        'name' => __('Jeugd', $this->plugin_slug),
                        'children' => array(
                            /**
                             * Jeugd A
                             */
                            array(
                                'slug' => 'jeugd-a',
                                'name' => __('Jeugd A', $this->plugin_slug),
                                'children' => array(
                                    array(
                                        'slug' => 'meisjes-a',
                                        'name' => __('Meisjes A', $this->plugin_slug),
                                        'meta' => array(
                                            'nevobo-team-type' => 'MA',
                                        ),
                                    ),
                                    array(
                                        'slug' => 'jongens-a',
                                        'name' => __('Jongens A', $this->plugin_slug),
                                        'meta' => array(
                                            'nevobo-team-type' => 'JA',
                                        ),
                                    ),
                                    array(
                                        'slug' => 'mix-a',
                                        'name' => __('Mix A', $this->plugin_slug),
                                        'meta' => array(
                                            'nevobo-team-type' => 'XA',
                                        ),
                                    ),
                                )
                            ),
                            /**
                             * Jeugd B
                             */
                            array(
                                'slug' => 'jeugd-b',
                                'name' => __('Jeugd B', $this->plugin_slug),
                                'children' => array(
                                    array(
                                        'slug' => 'meisjes-b',
                                        'name' => __('Meisjes B', $this->plugin_slug),
                                        'meta' => array(
                                            'nevobo-team-type' => 'MB',
                                        ),
                                    ),
                                    array(
                                        'slug' => 'jongens-b',
                                        'name' => __('Jongens B', $this->plugin_slug),
                                        'meta' => array(
                                            'nevobo-team-type' => 'JB',
                                        ),
                                    ),
                                    array(
                                        'slug' => 'mix-b',
                                        'name' => __('Mix B', $this->plugin_slug),
                                        'meta' => array(
                                            'nevobo-team-type' => 'XB',
                                        ),
                                    ),
                                )
                            ),
                            /**
                             * Jeugd C
                             */
                            array(
                                'slug' => 'jeugd-c',
                                'name' => __('Jeugd C', $this->plugin_slug),
                                'children' => array(
                                    array(
                                        'slug' => 'meisjes-c',
                                        'name' => __('Meisjes C', $this->plugin_slug),
                                        'meta' => array(
                                            'nevobo-team-type' => 'MC',
                                        ),
                                    ),
                                    array(
                                        'slug' => 'jongens-c',
                                        'name' => __('Jongens C', $this->plugin_slug),
                                        'meta' => array(
                                            'nevobo-team-type' => 'JC',
                                        ),
                                    ),
                                    array(
                                        'slug' => 'mix-c',
                                        'name' => __('Mix C', $this->plugin_slug),
                                        'meta' => array(
                                            'nevobo-team-type' => 'XC',
                                        ),
                                    ),
                                )
                            ),
                        ),
                    ),
                    /**
                     * CMV (Mini's)
                     */
                    array(
                        'slug' => 'cmv',
                        'name' => __('CMV', $this->plugin_slug),
                        'children' => array(
                            array(
                                'slug' => 'cmv-niveau-6',
                                'name' => __('CMV Niveau 6', $this->plugin_slug),
                                'meta' => array(
                                    'nevobo-team-type' => 'N6',
                                ),
                                'children' => array(
                                    array(
                                        'slug' => 'cmv-meisjes-niveau-6',
                                        'name' => __('CMV Meisjes Niveau 6', $this->plugin_slug),
                                        'meta' => array(
                                            'nevobo-team-type' => 'M6',
                                        ),
                                    ),
                                    array(
                                        'slug' => 'cmv-jongens-niveau-6',
                                        'name' => __('CMV Jongens Niveau 6', $this->plugin_slug),
                                        'meta' => array(
                                            'nevobo-team-type' => 'J6',
                                        ),
                                    ),
                                ),
                            ),
                            array(
                                'slug' => 'cmv-niveau-5',
                                'name' => __('CMV Niveau 5', $this->plugin_slug),
                                'meta' => array(
                                    'nevobo-team-type' => 'N5',
                                ),
                            ),
                            array(
                                'slug' => 'cmv-niveau-4',
                                'name' => __('CMV Niveau 4', $this->plugin_slug),
                                'meta' => array(
                                    'nevobo-team-type' => 'N4',
                                ),
                            ),
                            array(
                                'slug' => 'cmv-niveau-3',
                                'name' => __('CMV Niveau 3', $this->plugin_slug),
                                'meta' => array(
                                    'nevobo-team-type' => 'N3',
                                ),
                            ),
                            array(
                                'slug' => 'cmv-niveau-2',
                                'name' => __('CMV Niveau 2', $this->plugin_slug),
                                'meta' => array(
                                    'nevobo-team-type' => 'N2',
                                ),
                            ),
                            array(
                                'slug' => 'cmv-niveau-1',
                                'name' => __('CMV Niveau 1', $this->plugin_slug),
                                'meta' => array(
                                    'nevobo-team-type' => 'N1',
                                ),
                            ),
                        ),
                    ),
                    /**
                     * Recreatief
                     */
                    array(
                        'slug' => 'recreatief',
                        'name' => __('Recreatief', $this->plugin_slug),
                        'children' => array(
                            array(
                                'slug' => 'dames-recreatief',
                                'name' => __('Dames Recreatief', $this->plugin_slug),
                                'meta' => array(
                                    'nevobo-team-type' => 'DR',
                                ),
                            ),
                            array(
                                'slug' => 'heren-recreatief',
                                'name' => __('Heren Recreatief', $this->plugin_slug),
                                'meta' => array(
                                    'nevobo-team-type' => 'HR',
                                ),
                            ),
                            array(
                                'slug' => 'mix-recreatief',
                                'name' => __('Mix Recreatief', $this->plugin_slug),
                                'meta' => array(
                                    'nevobo-team-type' => 'XR',
                                ),
                            ),
                        )
                    ),
                    // /**
                    //  * Masters
                    //  */
                    // array(
                    //     'slug' => 'master',
                    //     'name' => __('Master', $this->plugin_slug),
                    //     'children' => array(
                    //         array(
                    //             'slug' => 'dames-master',
                    //             'name' => __('Dames Master', $this->plugin_slug),
                    //             'meta' => array(
                    //                 'nevobo-team-type' => 'DM',
                    //             ),
                    //         ),
                    //         array(
                    //             'slug' => 'heren-master',
                    //             'name' => __('Heren Master', $this->plugin_slug),
                    //             'meta' => array(
                    //                 'nevobo-team-type' => 'HM',
                    //             ),
                    //         ),
                    //         array(
                    //             'slug' => 'mix-master',
                    //             'name' => __('Mix Master', $this->plugin_slug),
                    //             'meta' => array(
                    //                 'nevobo-team-type' => 'XM',
                    //             ),
                    //         ),
                    //     )
                    // ),
                    // /** 
                    //  * Zitvolleybal
                    //  */
                    // array(
                    //     'slug' => 'zitvolleybal',
                    //     'name' => __('Zitvolleybal', $this->plugin_slug),
                    //     'children' => array(
                    //         array(
                    //             'slug' => 'dames-zitvolleybal',
                    //             'name' => __('Dames Zitvolleybal', $this->plugin_slug),
                    //             'meta' => array(
                    //                 'nevobo-team-type' => 'DZ',
                    //             ),
                    //         ),
                    //         array(
                    //             'slug' => 'heren-zitvolleybal',
                    //             'name' => __('Heren Zitvolleybal', $this->plugin_slug),
                    //             'meta' => array(
                    //                 'nevobo-team-type' => 'HZ',
                    //             ),
                    //         ),
                    //         array(
                    //             'slug' => 'mix-zitvolleybal',
                    //             'name' => __('Mix Zitvolleybal', $this->plugin_slug),
                    //             'meta' => array(
                    //                 'nevobo-team-type' => 'XZ',
                    //             ),
                    //         ),
                    //     )
                    // ),
                    // /**
                    //  * Onder 21
                    //  */
                    // array(
                    //     'slug' => 'onder-21',
                    //     'name' => __('Onder 21', $this->plugin_slug),
                    //     'children' => array(
                    //         array(
                    //             'slug' => 'dames-21',
                    //             'name' => __('Dames < 21', $this->plugin_slug),
                    //             'meta' => array(
                    //                 'nevobo-team-type' => 'DJ',
                    //             ),
                    //         ),
                    //         array(
                    //             'slug' => 'heren-21',
                    //             'name' => __('Heren < 21', $this->plugin_slug),
                    //             'meta' => array(
                    //                 'nevobo-team-type' => 'HJ',
                    //             ),
                    //         ),
                    //     ),
                    // ),
                )
            ),
            /**
             * The Nevobo match type custom terms
             */
            array(
                'taxonomy' => 'nevobo-match-status',
                'terms' => array(
                    array(
                        'slug' => 'concept',
                        'name' => __('Concept', $this->plugin_slug),
                    ),
                    array(
                        'slug' => 'gepland',
                        'name' => __('Gepland', $this->plugin_slug),
                    ),
                    array(
                        'slug' => 'uitgesteld',
                        'name' => __('Uitgesteld', $this->plugin_slug),
                    ),
                    array(
                        'slug' => 'vervallen',
                        'name' => __('Vervallen', $this->plugin_slug),
                    ),
                    array(
                        'slug' => 'afgebroken',
                        'name' => __('Afgebroken', $this->plugin_slug),
                    ),
                    array(
                        'slug' => 'gespeeld',
                        'name' => __('Gespeeld', $this->plugin_slug),
                    ),
                ),
            )
        );

        /**
         * Loop to register all the custom terms in the array.
         */
        foreach ($terms as $term) {
            $this->insert_custom_terms_recursively(
                $term['taxonomy'],
                $term['terms'],
            );
        }
    }

    /**
     * Helper function to recursivly insert all the custom terms into the custom taxonomies.
     *
     * @since    1.0.0
     * @access   private
     * @param    string     $taxonomy       The slug of the taxonomy to which to add the term.
     * @param    array      $terms          The associative array of terms to add, where the key is the slug and the value is the name of the term.
     * @param    int        $parent         The id of the parent term. Default is 0 when there is no parent term.
     */
    private function insert_custom_terms_recursively(string $taxonomy, array $terms, int $parent = 0)
    {
        // check if the terms array is empty, if so then return
        if (empty($terms)) {
            return;
        }

        // if not, insert the current term and store its return data
        $term = wp_insert_term(
            current($terms)['name'],
            $taxonomy,
            array(
                'parent' => $parent,
                'slug' => current($terms)['slug']
            )
        );
        // store the id of the inserted term depending on the return object
        $term_id = !is_wp_error($term) ? $term['term_id'] : $term->get_error_data();

        // check if the current term has meta values
        if (array_key_exists('meta', current($terms))) {
            // loop to add each meta key and meta value
            foreach (current($terms)['meta'] as $meta_key => $meta_value) {
                add_term_meta($term_id, $meta_key, $meta_value, true);
            }
        }

        // check if the current term has children 
        if (array_key_exists('children', current($terms))) {
            // recursively insert the children terms array with the just inserted term id as its parent
            $this->insert_custom_terms_recursively($taxonomy, current($terms)['children'], $term_id);
        }

        // remove the inserted term off the terms array
        array_shift($terms);
        // recursively insert the remaining terms with the old parent id
        $this->insert_custom_terms_recursively($taxonomy, $terms, $parent);
        return;
    }
}
