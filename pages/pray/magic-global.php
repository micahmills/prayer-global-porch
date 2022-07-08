<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.


/**
 * Class PG_Global_Prayer_App
 */
class PG_Global_Prayer_App extends DT_Magic_Url_Base {

    public $magic = false;
    public $page_title = 'Global Lap';
    public $page_description = 'Prayer Laps';
    public $root = "prayer_app";
    public $type = 'global';
    public $type_actions = [
        '' => "Pray",
        'map' => "Map",
        'stats' => "Stats",
        'completed' => "Completed",
    ];
    public $show_bulk_send = false;
    public $post_type = 'laps';
    private $meta_key = '';
    public $show_app_tile = true;

    private static $_instance = null;
    public $meta = []; // Allows for instance specific data.

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    } // End instance()

    public function __construct() {

        $this->meta_key = $this->root . '_' . $this->type . '_magic_key';
        parent::__construct();

        /**
         * post type and module section
         */
        add_filter( 'dt_settings_apps_list', [ $this, 'dt_settings_apps_list' ], 10, 1 );

        if ( dt_is_rest() ) {
            add_action( 'rest_api_init', [ $this, 'add_endpoints' ] );
        }

        // must be valid url
        $url = dt_get_url_path();
        if ( strpos( $url, $this->root . '/' . $this->type ) === false ) {
            return;
        }

        // must be valid parts
        if ( !$this->check_parts_match( true ) ){
            return;
        }

        add_filter( "dt_override_header_meta", function (){ return true;
        }, 1000, 1 );

        // load different actions
        if ( empty( $this->parts['action'] ) ) {
            $current_lap = pg_current_global_lap();
            if ( (int) $current_lap['post_id'] === (int) $this->parts['post_id'] ) {
                require_once( 'action-global-lap.php' );
            } else {
                wp_redirect( trailingslashit( site_url() ) . $this->root . '/' . $this->type . '/' . $this->parts['public_key'] . '/completed' );
                exit;
            }
        } else if ( 'completed' === $this->parts['action'] ) {
            require_once( 'action-global-completed.php' );
        } else if ( 'map' === $this->parts['action'] ) {
            require_once( 'action-global-map.php' );
        } else if ( 'stats' === $this->parts['action'] ) {
            require_once( 'action-global-stats.php' );
        } else {
            wp_redirect( trailingslashit( site_url() ) );
        }

    }

    public function dt_settings_apps_list( $apps_list ) {
        $apps_list[$this->meta_key] = [
            'key' => $this->meta_key,
            'url_base' => $this->root . '/' . $this->type,
            'label' => $this->page_title,
            'description' => $this->page_description,
            'settings_display' => false
        ];

        return $apps_list;
    }

    public function add_endpoints() {
        $namespace = $this->root . '/v1';
        foreach ( $this->type_actions as $action => $label ) {
            register_rest_route(
                $namespace,
                '/'.$this->type . '/' . $action,
                [
                    [
                        'methods'  => WP_REST_Server::CREATABLE,
                        'callback' => [ $this, 'endpoint' ],
                    ],
                ]
            );
        }
    }

    /**
     * Routes endpoints to sub action class
     *
     * @param WP_REST_Request $request
     * @return array|bool|void|WP_Error
     */
    public function endpoint( WP_REST_Request $request ) {
        $params = $request->get_params();

        if ( ! isset( $params['parts'], $params['action'] ) ) {
            return new WP_Error( __METHOD__, "Missing parameters", [ 'status' => 400 ] );
        }

        $params = dt_recursive_sanitize_array( $params );

        switch ( $params['parts']['action'] ) {
            case 'completed':
                return true;
            case 'map':
                require_once( 'action-global-map.php' );
                if ( class_exists( 'PG_Global_Prayer_App_Map' ) ) {
                    return PG_Global_Prayer_App_Map::instance()->endpoint( $request );
                }
                return new WP_Error( __METHOD__, "Class not loaded: PG_Global_Prayer_App_Map", [ 'status' => 400 ] );
            case 'stats':
                require_once( 'action-global-stats.php' );
                if ( class_exists( 'PG_Global_Prayer_App_Stats' ) ) {
                    return PG_Global_Prayer_App_Stats::instance()->endpoint( $request );
                }
                return new WP_Error( __METHOD__, "Class not loaded: PG_Global_Prayer_App_Stats", [ 'status' => 400 ] );
            default:
                require_once( 'action-global-lap.php' );
                if ( class_exists( 'PG_Global_Prayer_App_Lap' ) ) {
                    return PG_Global_Prayer_App_Lap::instance()->endpoint( $request );
                }
                return new WP_Error( __METHOD__, "Class not loaded: PG_Global_Prayer_App_Lap", [ 'status' => 400 ] );
        }
    }

    public function _header() {
        $this->header_style();
        $this->header_javascript();
    }
    public function _footer(){
        $this->footer_javascript();
    }

}
PG_Global_Prayer_App::instance();
