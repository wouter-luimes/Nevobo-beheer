<?php

/**
 * The admin-specific callback functionality of the plugin.
 *
 * @link       https://www.wouterluimes.nl
 * @since      1.0.0
 *
 * @package    Nevobo_Beheer
 * @subpackage Nevobo_Beheer/admin
 */

/**
 * The admin-specific callback functionality of the plugin.
 * 
 * @package    Nevobo_Beheer
 * @subpackage Nevobo_Beheer/admin
 * @author     Wouter Luimes <contact@wouterluimes.nl>
 */
class Nevobo_Beheer_Admin_Callbacks
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
     * Callback function that sanitizes the association input settings values.
     * 
     * @since      1.0.0
     * @param      array	$input	    The array input values to sanitize.
     */
    public function association_settings_sanitize_callback(array $input)
    {
        // the sanitized output to return
        static $output;

        // the settings errors settings to display to the user in case of an error
        $error_slug = 'nevobo-beheer-association-settings';
        $error_id = 'nevobo-beheer-association-settings-error';

        // detect multiple sanitizing passes bug: https://core.trac.wordpress.org/ticket/21989
        static $passes = 0;
        $passes++;
        if ($passes >= 2) {
            return $output;
        }

        // get the current options and set them as the default sanitized output
        $options = get_option('nevobo-beheer-association-settings');
        foreach ($options as $key => $value) {
            $output[$key] = $value;
        }

        // check if the association code is empty, if so then clear all output setting fields
        if (empty($input['association-code'])) {
            foreach ($output as $key => $value) {
                $output[$key] = '';
            }
            return $output;
        }

        // trim the association code from the input and make it uppercase only
        $association_code = strtoupper(trim($input['association-code']));
        // check if the association code matches the regex
        if (!preg_match('/^[A-Z0-9]{1,8}$/', $association_code)) {
            $message = esc_html(__('De ingevoerde verenigingscode is ongeldig omdat deze gebruik maakt van één of meerdere ongeldige tekens. De verenigingscode mag alleen uit (hoofd)letters en cijfers bestaan en is maximaal 8 karakters lang. Geef een geldige verenigingscode op en probeer het opnieuw.', $this->plugin_slug));
            add_settings_error($error_slug, $error_id, $message, 'error');
            return $output;
        }
        
        try {
            // try to obtain association information by performing an API request
            $association = $this->get_association_information($association_code);

            // setting the output fields accroding to API response
            $output['association-code'] = $association['organisatiecode'];
            $output['association-name'] = $association['naam'];
            $output['association-region'] = $association['regio'];
            $output['association-province'] = $association['provincie'];
            $output['association-commune'] = $association['gemeente'];
            $output['association-location'] = $association['vestigingsplaats'];
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            add_settings_error($error_slug, $error_id, $message, 'error');
        }
        return $output;
    }

    /**
     * Function that requests association information by accessing the Nevobo API
     * 
     * @since      1.0.0
     * @param      string	    $association_code	    The association code.
     */
    private function get_association_information(string $association_code)
    {
        // building and performing the request
        $url = sprintf('http://api.nevobo.nl/relatiebeheer/verenigingen/%s', $association_code);
        $args = array(
            'headers' => array(
                'accept' => 'application/json'
            )
        );
        $response = wp_remote_get(filter_var($url, FILTER_SANITIZE_URL), $args);

        // check the response code
        switch (wp_remote_retrieve_response_code($response)) {
            case 200:
                $json = wp_remote_retrieve_body($response);
                break;
            case 404:
                $json = null;
                throw new Exception(__('Er kon geen vereniging gevonden worden voor de ingevulde verenigingscode. Vul een geldige verenigscode in en probeer het opnieuw.', $this->plugin_slug));
            default:
                $json = null;
                throw new Exception(__('Er is een onverwachte HTTP-code ontvangen bij het ophalen van de verenigingsinformatie. Vul een geldige verenigscode in en probeer het opnieuw.', $this->plugin_slug));
        }

        // return docoded json associative array
        return json_decode($json, true);
    }

    /**
     * Callback function that fills the field with the desired form inputs.
     * 
     * @since      1.0.0
     * @param      array	$args	The array of extra setting arguments.
     */
    public function association_settings_fields_callback(array $args)
    {
        // get the association settings option array
        $option = get_option($args['option']);

        // setting strings
        $name = sprintf('%s[%s]', $args['option'], $args['label-for']);
        $described_by = sprintf('%s-description', ($args['label-for']));

        // start printing input form field
        ob_start(); ?>

        <input name="<?= esc_attr($name) ?>" type="<?= esc_attr($args['type']) ?>" id="<?= esc_attr($args['label-for']) ?>" class='<?= $args['class'] ?>' <?= $args['readonly'] ? 'readonly="readonly"' : ''; ?> value="<?= esc_attr($option[$args['label-for']]) ?>" aria-describedby='<?= esc_attr($described_by) ?>'></input>
        <p class="description" id="<?= esc_attr($described_by) ?>"><?= esc_html($args['description']); ?></p>

<?= ob_get_clean();
    }
}
