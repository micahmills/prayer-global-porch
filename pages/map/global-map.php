<?php

class Prayer_Global_Current_Global_Map extends Prayer_Global_Laps_Post_Type_Link {

    private static $_instance = null;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        parent::__construct();

        if ( dt_is_rest() ) {
            add_action( 'rest_api_init', [ $this, 'add_endpoints' ] );
        }

        /**
         * tests if other URL
         */
        $url = dt_get_url_path();
        if ( strpos( $url, $this->root . '/' . $this->type ) === false ) {
            return;
        }

        if ( !$this->check_parts_match() ){
            return;
        }

        if ( empty( $this->parts['action'] ) ) {
            return;
        } else if ( 'map' !== $this->parts['action'] ) {
            wp_redirect( trailingslashit( site_url() ) . '/' . $this->root . '/' . $this->type );
            exit;
        }

        // load if valid url
        add_action( 'dt_blank_head', [ $this, '_header' ] );
        add_action( 'dt_blank_body', [ $this, 'body' ] );

        add_filter( 'dt_magic_url_base_allowed_css', [ $this, 'dt_magic_url_base_allowed_css' ], 10, 1 );
        add_filter( 'dt_magic_url_base_allowed_js', [ $this, 'dt_magic_url_base_allowed_js' ], 10, 1 );

        add_action( 'wp_enqueue_scripts', [ $this, '_wp_enqueue_scripts' ], 100 );
    }

    public function dt_magic_url_base_allowed_js( $allowed_js ) {
        $allowed_js[] = 'jquery-touch-punch';
        $allowed_js[] = 'mapbox-gl';
        $allowed_js[] = 'jquery-cookie';
        $allowed_js[] = 'mapbox-cookie';
        $allowed_js[] = 'heatmap-js';
        return $allowed_js;
    }

    public function dt_magic_url_base_allowed_css( $allowed_css ) {
        $allowed_css[] = 'mapbox-gl-css';
        $allowed_css[] = 'introjs-css';
        $allowed_css[] = 'heatmap-css';
        $allowed_css[] = 'site-css';
        return $allowed_css;
    }

    public function _header(){
        wp_head();
        $this->header_style();
        $this->header_javascript();
    }

    public function header_javascript(){
        ?>
        <style>
            body {
                background: white !important;
            }
            #initialize-screen {
                width: 100%;
                height: 2000px;
                z-index: 100;
                background-color: white;
                position: absolute;
            }
            #initialize-spinner-wrapper{
                position:relative;
                top:45%;
            }
            progress {
                top: 50%;
                margin: 0 auto;
                height:50px;
                width:300px;
            }
        </style>
        <?php
    }

    public function footer_javascript(){
    }

    public function body(){
        DT_Mapbox_API::geocoder_scripts();
        ?>
        <style id="custom-style"></style>
        <div id="map-content"></div>
        <?php
    }

    public static function _wp_enqueue_scripts(){
        DT_Mapbox_API::load_mapbox_header_scripts();

        wp_enqueue_script( 'heatmap-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'heatmap.js', [
            'jquery',
            'mapbox-gl'
        ], filemtime( plugin_dir_path( __FILE__ ) .'heatmap.js' ), true );
    }

    /**
     * Register REST Endpoints
     * @link https://github.com/DiscipleTools/disciple-tools-theme/wiki/Site-to-Site-Link for outside of wordpress authentication
     */
    public function add_endpoints() {
        $namespace = $this->root . '/v1';
        register_rest_route(
            $namespace,
            '/'.$this->type . '/' . $this->parts['action'],
            [
                [
                    'methods'  => WP_REST_Server::CREATABLE,
                    'callback' => [ $this, 'endpoint' ],
                ],
            ]
        );
    }

    public function endpoint( WP_REST_Request $request ) {
        $params = $request->get_params();

        if ( ! isset( $params['parts'], $params['action'] ) ) {
            return new WP_Error( __METHOD__, "Missing parameters", [ 'status' => 400 ] );
        }

        return self::state_grid_populations();
    }

    public static function state_grid_populations() {
        global $wpdb;
        $data_raw = $wpdb->get_results("
            SELECT
                lg1.grid_id, lg1.name, lg1.population, lg1.country_code, lg1.level
            FROM $wpdb->dt_location_grid lg1
            WHERE lg1.level = 0
              AND lg1.grid_id NOT IN ( SELECT lg11.admin0_grid_id FROM $wpdb->dt_location_grid lg11 WHERE lg11.level = 1 AND lg11.admin0_grid_id = lg1.grid_id )
              AND lg1.admin0_grid_id NOT IN (100050711,100219347,100089589,100074576,100259978,100018514)
            UNION ALL
            SELECT
                lg2.grid_id, lg2.name, lg2.population, lg2.country_code, lg2.level
            FROM $wpdb->dt_location_grid lg2
            WHERE lg2.level = 1
              AND lg2.admin0_grid_id NOT IN (100050711,100219347,100089589,100074576,100259978,100018514)
            UNION ALL
            SELECT
                lg3.grid_id, lg3.name, lg3.population, lg3.country_code, lg3.level
            FROM $wpdb->dt_location_grid lg3
            WHERE lg3.level = 2
              AND lg3.admin0_grid_id IN (100050711,100219347,100089589,100074576,100259978,100018514)
        ", ARRAY_A );

        $data = [];
        $highest_value = 1;
        foreach ( $data_raw as $row ) {
            $data[$row['grid_id']] = $row['population'];

            if ( $highest_value < $row['population'] ){
                $highest_value = $row['population'];
            }
        }

        return [
            'highest_value' => (int) $highest_value,
            'data' => $data
        ];
    }
}
Prayer_Global_Current_Global_Map::instance();
