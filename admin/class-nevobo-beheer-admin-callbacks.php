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
     * Callback function that fills the Nevobo codes input field and unordered list with the desired inputs.
     *
     * @since   1.0.0
     * @param   array     $args       The array of extra setting arguments.
     */
    public function nevobo_codes_field_callback(array $args)
    {
        // get the data option array
        switch ($args['option-name']) {
            case 'nevobo-beheer-association-data':
                $option = get_option('nevobo-beheer-association-data');
                $option_list = array(
                    __('Naam', $this->plugin_slug) => $option['association-name'],
                    __('Plaats', $this->plugin_slug) => $option['association-location'],
                );
                break;
            case 'nevobo-beheer-sports-hall-data':
                $option = get_option('nevobo-beheer-sports-hall-data');
                $option_list = array(
                    __('Naam', $this->plugin_slug) => $option['sports-hall-name'],
                    __('Plaats', $this->plugin_slug) => $option['sports-hall-location'],
                );
                break;
            default:
                return;
        }

        // input field attributes
        $input_name = esc_attr(sprintf('%s[%s]', $args['option-name'], $args['label-for']));
        $input_id = esc_attr($args['label-for']);
        $input_type = esc_attr($args['type']);
        $input_class = esc_attr($args['class']);
        $input_value = esc_attr($option[$args['label-for']]);
        $input_description = esc_attr($args['description']);
        $input_describedby = esc_attr(sprintf('%s-description', ($args['label-for'])));

        // print the input form field and unordered list
        ob_start(); ?>

        <input name="<?= $input_name ?>" type="<?= $input_type ?>" id="<?= $input_id ?>" class="<?= $input_class ?>" value="<?= $input_value ?>" aria-describedby="<?= $input_describedby ?>">
        <p class="description" id="<?= $input_describedby ?>"><?= $input_description ?></p>
        <ul style="margin-top:o.5rem; margin-bottom:0rem">
            <?php foreach ($option_list as $key => $value) { ?>
                <li><strong><?= $key ?>:&nbsp;</strong><?= empty($value) ? '–' : $value ?></li>
            <?php } ?>
        </ul>
        <?php // print("<pre><strong>Debug:</strong>\n" . print_r($option, true) . "</pre>") ?>
<?php
        echo ob_get_clean();
    }

    /**
     * Callback function that sanitizes the association input filed.
     * 
     * @since      1.0.0
     * @param      array	$input	    The array input values to sanitize.
     */
    public function association_data_sanitize_callback(array $input)
    {
        // the sanitized output to return
        static $output;

        // detect multiple sanitizing passes bug: https://core.trac.wordpress.org/ticket/21989
        static $passes = 0;
        $passes++;
        if ($passes >= 2) {
            return $output;
        }

        // get the current data options and set them as the default sanitized output
        $options = get_option('nevobo-beheer-association-data');
        $output = $options;

        // check if the association code is empty
        if (empty($input['association-code'])) {
            delete_option('nevobo-beheer-association-data');
            $output = get_option('nevobo-beheer-association-data');
            return $output;
        }

        // trim the association code from the input and make it uppercase only
        $association_code = strtoupper(trim($input['association-code']));

        // the settings errors settings to display to the user in case of an error
        $error_slug = 'nevobo-beheer-nevobo-codes';
        $error_id = sprintf('%s-error', $error_slug);

        // check if the association code matches the regex
        if (!preg_match('/^[A-Z0-9]{1,8}$/', $association_code)) {
            $message = esc_html(__('De ingevoerde verenigingscode is ongeldig omdat deze gebruik maakt van één of meerdere ongeldige tekens. De verenigingscode mag alleen uit (hoofd)letters en cijfers bestaan en is maximaal 8 karakters lang. Geef een geldige verenigingscode op en probeer het opnieuw.', $this->plugin_slug));
            add_settings_error($error_slug, $error_id, $message, 'error');
            return $output;
        }

        // try to obtain additional data
        try {
            // performm an API request
            $association_data = $this->get_nevobo_api_data('association-code', $association_code);

            // set the output to the data obtained by the API request
            $output['association-code'] = $association_data['organisatiecode'];
            $output['association-name'] = $association_data['naam'];
            $output['association-location'] = $association_data['vestigingsplaats'];
            $output['association-commune'] = $association_data['gemeente'];
            $output['association-province'] = $association_data['provincie'];
            $output['association-region'] = $association_data['regio'];
            $output['association-website'] = $association_data['website'];
            $output['association-email'] = $association_data['email'];
        } catch (Exception $exception) {
            // catch exception and register a settings error
            $message = $exception->getMessage();
            add_settings_error($error_slug, $error_id, $message, 'error');
        }

        // return the sanitized callback
        return $output;
    }

    /**
     * Callback function that sanitizes the sports hall input filed.
     * 
     * @since      1.0.0
     * @param      array	$input	    The array input values to sanitize.
     */
    public function sports_hall_data_sanitize_callback(array $input)
    {
        // the sanitized output to return
        static $output;

        // detect multiple sanitizing passes bug: https://core.trac.wordpress.org/ticket/21989
        static $passes = 0;
        $passes++;
        if ($passes >= 2) {
            return $output;
        }

        // get the current data options and set them as the default sanitized output
        $options = get_option('nevobo-beheer-sports-hall-data');
        $output = $options;

        // check if the sports hall code is empty
        if (empty($input['sports-hall-code'])) {
            delete_option('nevobo-beheer-sports-hall-data');
            $output = get_option('nevobo-beheer-sports-hall-data');
            return $output;
        }

        // trim the sports hall code from the input and make it uppercase only
        $sports_hall_code = sanitize_title_with_dashes(trim($input['sports-hall-code']));

        // the settings errors settings to display to the user in case of an error
        $error_slug = 'nevobo-beheer-nevobo-codes';
        $error_id = sprintf('%s-error', $error_slug);

        // check if the sports hall code matches the regex
        if (!preg_match('/^[a-z0-9-]+$/', $sports_hall_code)) {
            $message = esc_html(__('De ingevoerde sporthalcode is ongeldig omdat deze gebruik maakt van één of meerdere ongeldige tekens. De verenigingscode mag alleen uit (hoofd)letters en cijfers bestaan en is maximaal 8 karakters lang. Geef een geldige verenigingscode op en probeer het opnieuw.', $this->plugin_slug));
            add_settings_error($error_slug, $error_id, $message, 'error');
            return $output;
        }

        // try to obtain additional data
        try {
            // performm an API request
            $sports_hall_data = $this->get_nevobo_api_data('sports-hall-code', $sports_hall_code);

            // set the output to the data obtained by the API request
            $output['sports-hall-code'] = $sports_hall_code;
            $output['sports-hall-name'] = $sports_hall_data['naam'];
            $output['sports-hall-street'] = $sports_hall_data['adres']['straat'];
            $output['sports-hall-house-number'] = $sports_hall_data['adres']['huisnummer'];
            $output['sports-hall-postal-code'] = $sports_hall_data['adres']['postcode'];
            $output['sports-hall-location'] = $sports_hall_data['adres']['plaats'];
            $output['sports-hall-country'] = $sports_hall_data['adres']['land'];
            $output['sports-hall-phone-number'] = $sports_hall_data['telefoon'];
        } catch (Exception $exception) {
            // catch exception and register a settings error
            $message = $exception->getMessage();
            add_settings_error($error_slug, $error_id, $message, 'error');
        }

        // return the sanitized callback
        return $output;
    }

    /**
     * Private function that requests additional data by accessing the Nevobo API.
     * 
     * @since      1.0.0
     * @param      string	    $type	    The type of request to perform.
     * @param      string	    $code	    The unique code to use in the request.
     */
    private function get_nevobo_api_data(string $type, string $code)
    {
        // building the request
        switch ($type) {
            case 'association-code':
                $url = sprintf('http://api.nevobo.nl/relatiebeheer/verenigingen/%s', $code);
                $type_name = __('vereniging', $this->plugin_slug);
                $type_code = __('verenigingscode', $this->plugin_slug);
                $type_data = __('verenigingsgegevens', $this->plugin_slug);
                break;
            case 'sports-hall-code':
                $url = sprintf('http://api.nevobo.nl/accommodatie/sporthallen/%s', $code);
                $type_name = __('sporthal', $this->plugin_slug);
                $type_code = __('sporthalcode', $this->plugin_slug);
                $type_data = __('sporthalgegevens', $this->plugin_slug);
                break;
            default:
                return array();
        }
        $args = array(
            'headers' => array(
                'accept' => 'application/json'
            )
        );

        // performing the request
        $response = wp_remote_get(filter_var($url, FILTER_SANITIZE_URL), $args);

        // check the response code
        switch (wp_remote_retrieve_response_code($response)) {
            case 200:
                $json = wp_remote_retrieve_body($response);
                break;
            case 404:
                $message = __('Er kon geen %s gevonden worden voor de ingevulde %s. Vul een geldige %s in en probeer het opnieuw.', $this->plugin_slug);
                throw new Exception(sprintf($message, $type_name, $type_code, $type_code));
            default:
                $message = __('Er is een onverwachte HTTP-code ontvangen bij het ophalen van de %s. Vul een geldige %s in en probeer het opnieuw.', $this->plugin_slug);
                throw new Exception(sprintf($message, $type_data, $type_code));
        }

        // return decoded json associative array
        return json_decode($json, true);
    }
}
