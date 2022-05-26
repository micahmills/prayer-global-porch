<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.


class PG_Charts_Review extends PG_Metrics_Chart_Base
{
    public $base_slug = 'content'; // lowercase
    public $base_title = "Content Review";

    public $title = 'Locations';
    public $slug = 'locations'; // lowercase
    public $js_object_name = 'wp_js_object'; // This object will be loaded into the metrics.js file by the wp_localize_script.
    public $js_file_name = 'pg-review.js'; // should be full file name plus extension
    public $permissions = [ 'dt_all_access_contacts', 'view_project_metrics' ];

    public function __construct() {
        parent::__construct();

        add_action( 'rest_api_init', [ $this, 'add_api_routes' ] );

        if ( !$this->has_permission() ){
            return;
        }
        $url_path = dt_get_url_path();

        add_filter( 'dt_metrics_menu', [ $this, 'base_menu' ], 20 ); //load menu links

        if ( strpos( $url_path, "review/$this->base_slug/$this->slug" ) === 0 ) {
            add_filter( 'dt_templates_for_urls', [ $this, 'base_add_url' ] ); // add custom URLs
            add_action( 'wp_enqueue_scripts', [ $this, 'base_scripts' ], 99 );
        }

        // only load scripts if exact url
        if ( "review/$this->base_slug/$this->slug" === $url_path ) {
            add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ], 99 );
        }
    }


    /**
     * Load scripts for the plugin
     */
    public function scripts() {

        wp_register_script( 'amcharts-core', 'https://www.amcharts.com/lib/4/core.js', false, '4' );
        wp_register_script( 'amcharts-charts', 'https://www.amcharts.com/lib/4/charts.js', false, '4' );

        wp_enqueue_script( 'dt_'.$this->slug.'_script', trailingslashit( plugin_dir_url( __FILE__ ) ) . $this->js_file_name, [
            'jquery',
            'amcharts-core',
            'amcharts-charts'
        ], filemtime( plugin_dir_path( __FILE__ ) .$this->js_file_name ), true );

        // Localize script with array data
        wp_localize_script(
            'dt_'.$this->slug.'_script', $this->js_object_name, [
                'rest_endpoints_base' => esc_url_raw( rest_url() ) . "$this->base_slug/$this->slug",
                'base_slug' => $this->base_slug,
                'slug' => $this->slug,
                'root' => esc_url_raw( rest_url() ),
                'plugin_uri' => plugin_dir_url( __DIR__ ),
                'nonce' => wp_create_nonce( 'wp_rest' ),
                'current_user_login' => wp_get_current_user()->user_login,
                'current_user_id' => get_current_user_id(),
                'stats' => [
                    // add preload stats data into arrays here
                ],
                'translations' => [
                    "title" => $this->title,
                    "Sample API Call" => __( "Sample API Call", 'disciple-tools-plugin-starter-template' )
                ]
            ]
        );
    }

    public function add_api_routes() {
        $namespace = "$this->base_slug/$this->slug";
        register_rest_route(
            $namespace, '/sample', [
                'methods'  => 'POST',
                'callback' => [ $this, 'sample' ],
                'permission_callback' => function( WP_REST_Request $request ) {
                    return $this->has_permission();
                },
            ]
        );
    }

    public function sample( WP_REST_Request $request ) {
        $params = $request->get_params();
        if ( isset( $params['button_data'] ) ) {
            // Do something
            $results = $params['button_data'];
            return $results;
        } else {
            return new WP_Error( __METHOD__, 'Missing parameters.' );
        }
    }

}
