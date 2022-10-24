<?php
/**
 * Plugin Name: Prayer.Global Porch
 * Plugin URI: https://github.com/Pray4Movement/prayer-global-porch
 * Description: Prayer.Global front porch app for Disciple.Tools
 * Text Domain: prayer-global-porch
 * Domain Path: /languages
 * Version:  1.6
 * Author URI: https://github.com/DiscipleTools
 * GitHub Plugin URI: https://github.com/Pray4Movement/prayer-global-porch
 * Requires at least: 4.7.0
 * (Requires 4.7+ because of the integration of the REST API at 4.7 and the security requirements of this milestone version.)
 * Tested up to: 5.9
 *
 * @package Disciple_Tools
 * @link    https://github.com/DiscipleTools
 * @license GPL-2.0 or later
 *          https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

define( 'PG_TOTAL_STATES', 4770 );

/**
 * Gets the instance of the `Prayer_Global_Porch` class.
 *
 * @since  0.1
 * @access public
 * @return object|bool
 */
function prayer_global_porch() {
    $prayer_global_porch_required_dt_theme_version = '1.8.1';
    $wp_theme = wp_get_theme();
    $version = $wp_theme->version;

    /*
     * Check if the Disciple.Tools theme is loaded and is the latest required version
     */
    $is_theme_dt = strpos( $wp_theme->get_template(), "disciple-tools-theme" ) !== false || $wp_theme->name === "Disciple Tools";
    if ( $is_theme_dt && version_compare( $version, $prayer_global_porch_required_dt_theme_version, "<" ) ) {
        add_action( 'admin_notices', 'prayer_global_porch_hook_admin_notice' );
        add_action( 'wp_ajax_dismissed_notice_handler', 'dt_hook_ajax_notice_handler' );
        return false;
    }
    if ( !$is_theme_dt ){
        return false;
    }

    /**
     * Load useful function from the theme
     */
    if ( !defined( 'DT_FUNCTIONS_READY' ) ){
        require_once get_template_directory() . '/dt-core/global-functions.php';
    }

    Prayer_Global_Porch::instance();

    /**
     * Use this action fires after the DT_Network_Dashboard plugin has loaded.
     * Use this to hook expansions to the metrics or snapshot collection.
     */
    do_action( 'prayer_global_porch_loaded' );

    return true;
}
add_action( 'after_setup_theme', 'prayer_global_porch', 20 );

/**
 * Singleton class for setting up the plugin.
 *
 * @since  0.1
 * @access public
 */
class Prayer_Global_Porch {

    private static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct() {
        global $wpdb;
        $wpdb->location_grid_facts = 'location_grid_facts';
        $wpdb->location_grid_cities = 'location_grid_cities';
        $wpdb->location_grid_people_groups = 'location_grid_people_groups';
        $wpdb->location_grid_names = 'location_grid_names';

        require_once( 'global-utilities.php' );
        require_once( 'pages/pray/stacker-text.php' );
        require_once( 'pages/pray/stacker.php' );

        if ( is_admin() ) {
            require_once( 'support/admin.php' );
        }

        require_once( 'redirects/loader.php' );
        require_once( 'post-type/loader.php' );

        // home
        require_once( 'pages/home/magic-home.php' );
        require_once( 'pages/media/magic-media.php' );
        require_once( 'pages/contact/loader.php' );
        require_once( 'pages/privacy/magic-privacy.php' );
        require_once( 'pages/data-sources/magic-data-sources.php' );

        // prayer_app
        require_once( 'pages/pray/magic-global.php' );
        require_once( 'pages/pray/magic-custom.php' );

        // race_app
        require_once( 'pages/race/big-list.php' );
        require_once( 'pages/race/big-map.php' );

        // challenges
        require_once( 'pages/challenges/active-list.php' );


        // user
        require_once( 'pages/user/user-link.php' );

        // admin
//        require_once( 'charts/charts-loader.php' );
        require_once( 'support/build/show-all-content.php' );
//        require_once('support/build/add-records.php');

        // 404
        require_once( 'pages/404/404.php' );


//        require_once( 'support/cron.php' );
//        require_once( 'support/config-required-plugins.php' );

        $this->i18n();
    }

    /**
     * Filters the array of row meta for each/specific plugin in the Plugins list table.
     * Appends additional links below each/specific plugin on the plugins page.
     */
    public static function plugin_description_links( $links_array, $plugin_file_name, $plugin_data, $status ) {
        if ( strpos( $plugin_file_name, basename( __FILE__ ) ) ) {

            // You can still use `array_unshift()` to add links at the beginning.
            $links_array[] = '<a href="https://pray4movement.org">Pray4Movement</a>';
        }

        return $links_array;
    }

    /**
     * Method that runs only when the plugin is activated.
     *
     * @since  0.1
     * @access public
     * @return void
     */
    public static function activation() {
    }

    /**
     * Method that runs only when the plugin is deactivated.
     *
     * @since  0.1
     * @access public
     * @return void
     */
    public static function deactivation() {
        // add functions here that need to happen on deactivation
    }

    /**
     * Loads the translation files.
     *
     * @since  0.1
     * @access public
     * @return void
     */
    public function i18n() {
        $domain = 'prayer-global-porch';
        load_plugin_textdomain( $domain, false, trailingslashit( dirname( plugin_basename( __FILE__ ) ) ). 'support/languages' );
    }

    /**
     * Magic method to output a string if trying to use the object as a string.
     *
     * @since  0.1
     * @access public
     * @return string
     */
    public function __toString() {
        return 'prayer-global-porch';
    }

    /**
     * Magic method to keep the object from being cloned.
     *
     * @since  0.1
     * @access public
     * @return void
     */
    public function __clone() {
        _doing_it_wrong( __FUNCTION__, 'Whoah, partner!', '0.1' );
    }

    /**
     * Magic method to keep the object from being unserialized.
     *
     * @since  0.1
     * @access public
     * @return void
     */
    public function __wakeup() {
        _doing_it_wrong( __FUNCTION__, 'Whoah, partner!', '0.1' );
    }

    /**
     * Magic method to prevent a fatal error when calling a method that doesn't exist.
     *
     * @param string $method
     * @param array $args
     * @return null
     * @since  0.1
     * @access public
     */
    public function __call( $method = '', $args = array() ) {
        _doing_it_wrong( "prayer_global_porch::" . esc_html( $method ), 'Method does not exist.', '0.1' );
        unset( $method, $args );
        return null;
    }
}

if ( is_admin() ){
    add_filter( 'plugin_row_meta', [ 'Prayer_Global_Porch', 'plugin_description_links' ], 10, 4 ); // admin plugin page description
}


// Register activation hook.
//register_activation_hook( __FILE__, [ 'Prayer_Global_Porch', 'activation' ] );
//register_deactivation_hook( __FILE__, [ 'Prayer_Global_Porch', 'deactivation' ] );


if ( ! function_exists( 'prayer_global_porch_hook_admin_notice' ) ) {
    function prayer_global_porch_hook_admin_notice() {
        global $prayer_global_porch_required_dt_theme_version;
        $wp_theme = wp_get_theme();
        $current_version = $wp_theme->version;
        $message = "'Prayer Global Porch' plugin requires 'Disciple Tools' theme to work. Please activate 'Disciple Tools' theme or make sure it is latest version.";
        if ( $wp_theme->get_template() === "disciple-tools-theme" ){
            $message .= ' ' . sprintf( esc_html( 'Current Disciple Tools version: %1$s, required version: %2$s' ), esc_html( $current_version ), esc_html( $prayer_global_porch_required_dt_theme_version ) );
        }
        // Check if it's been dismissed...
        if ( ! get_option( 'dismissed-prayer-global-porch', false ) ) { ?>
            <div class="notice notice-error notice-prayer-global-porch is-dismissible" data-notice="prayer-global-porch">
                <p><?php echo esc_html( $message );?></p>
            </div>
            <script>
                jQuery(function($) {
                    $( document ).on( 'click', '.notice-prayer-global-porch .notice-dismiss', function () {
                        $.ajax( ajaxurl, {
                            type: 'POST',
                            data: {
                                action: 'dismissed_notice_handler',
                                type: 'prayer-global-porch',
                                security: '<?php echo esc_html( wp_create_nonce( 'wp_rest_dismiss' ) ) ?>'
                            }
                        })
                    });
                });
            </script>
        <?php }
    }
}

/**
 * AJAX handler to store the state of dismissible notices.
 */
if ( ! function_exists( "dt_hook_ajax_notice_handler" )){
    function dt_hook_ajax_notice_handler(){
        check_ajax_referer( 'wp_rest_dismiss', 'security' );
        if ( isset( $_POST["type"] ) ){
            $type = sanitize_text_field( wp_unslash( $_POST["type"] ) );
            update_option( 'dismissed-' . $type, true );
        }
    }
}

add_action( 'plugins_loaded', function (){
    if ( is_admin() ){
        // Check for plugin updates
        if ( ! class_exists( 'Puc_v4_Factory' ) ) {
            if ( file_exists( get_template_directory() . '/dt-core/libraries/plugin-update-checker/plugin-update-checker.php' )){
                require( get_template_directory() . '/dt-core/libraries/plugin-update-checker/plugin-update-checker.php' );
            }
        }
        if ( class_exists( 'Puc_v4_Factory' ) ){
            Puc_v4_Factory::buildUpdateChecker(
                'https://raw.githubusercontent.com/Pray4Movement/prayer-global-porch/master/version-control.json',
                __FILE__,
                'prayer-global-porch'
            );

        }
    }
} );





