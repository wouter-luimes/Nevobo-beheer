<?php

/**
 * The Gutenberg-specific functionality of the plugin.
 *
 * @link       https://www.wouterluimes.nl
 * @since      1.0.0
 *
 * @package    Nevobo_Beheer
 * @subpackage Nevobo_Beheer/gutenberg
 */

/**
 * The Gutenberg-specific functionality of the plugin.
 *
 * Defines the plugin slug, version, and hooks.
 *
 * @package    Nevobo_Beheer
 * @subpackage Nevobo_Beheer/gutenberg
 * @author     Wouter Luimes <contact@wouterluimes.nl>
 */
class Nevobo_Beheer_Gutenberg
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

    // $file = plugin_dir_path( __FILE__ ) . '/log.txt';
    // file_put_contents( $file, print_r($path, true) . "\n", FILE_APPEND );

    public function enqueue_nevobo_team_block_editor_assets()
    {
        // check if the loaded post-type is the nevobo-team post-type, if not return
        if (get_post_type() !== 'nevobo-team') {
            return;
        }

        // check if there are dependencies that need to be loaded
        $path = plugin_dir_path(__FILE__) . 'build/index.asset.php';
        if (file_exists($path)) {
            $asset = require $path;
        } else {
            $asset = array('dependencies'=>array(), 'version'=>$this->version);
        }
        
        // enqueue the script
        wp_enqueue_script(
            'nevobo-beheer-block-editor-assets',
            plugin_dir_url(__FILE__) . '/build/index.js',
            $asset['dependencies'],
            $asset['version'],
            true,
        );
    }
}
