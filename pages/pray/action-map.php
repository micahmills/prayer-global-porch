<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.

class Prayer_Global_Prayer_App_Map extends Prayer_Global_Prayer_App {

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

        // must be valid url
        $url = dt_get_url_path();
        if ( strpos( $url, $this->root . '/' . $this->type ) === false ) {
            return;
        }

        // must be valid parts
        if ( !$this->check_parts_match() ){
            return;
        }

        // must be specific action
        if ( 'map' !== $this->parts['action'] ) {
            return;
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



    public function header_javascript(){
        ?>
        <script>
            let jsObject = [<?php echo json_encode([
                'map_key' => DT_Mapbox_API::get_key(),
                'ipstack' => DT_Ipstack_API::get_key(),
                'mirror_url' => dt_get_location_grid_mirror( true ),
                'root' => esc_url_raw( rest_url() ),
                'nonce' => wp_create_nonce( 'wp_rest' ),
                'parts' => $this->parts,
                'grid_data' => ['data' => [], 'highest_value' => 1 ],
                'current_lap' => pg_current_global_lap(),
                'custom_marks' => [],
                'translations' => [
                    'add' => __( 'Add Magic', 'prayer-global' ),
                ],
            ]) ?>][0]
        </script>
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

    public function endpoint( WP_REST_Request $request ) {
        $params = $request->get_params();

        if ( ! isset( $params['parts'], $params['action'] ) ) {
            return new WP_Error( __METHOD__, "Missing parameters", [ 'status' => 400 ] );
        }

        return self::current_lap();
    }

    public static function current_lap() {
        global $wpdb;
        $current_lap = pg_current_global_lap();

        // map grid
        $data_raw = $wpdb->get_results( $wpdb->prepare( "
            SELECT
                lg1.grid_id, r1.value
            FROM $wpdb->dt_location_grid lg1
			LEFT JOIN $wpdb->dt_reports r1 ON r1.grid_id=lg1.grid_id AND r1.type = 'prayer_app' AND r1.timestamp >= %d
            WHERE lg1.level = 0
              AND lg1.grid_id NOT IN ( SELECT lg11.admin0_grid_id FROM $wpdb->dt_location_grid lg11 WHERE lg11.level = 1 AND lg11.admin0_grid_id = lg1.grid_id )
              AND lg1.admin0_grid_id NOT IN (100050711,100219347,100089589,100074576,100259978,100018514)
            UNION ALL
            SELECT
                lg2.grid_id, r2.value
            FROM $wpdb->dt_location_grid lg2
			LEFT JOIN $wpdb->dt_reports r2 ON r2.grid_id=lg2.grid_id AND r2.type = 'prayer_app' AND r2.timestamp >= %d
            WHERE lg2.level = 1
              AND lg2.admin0_grid_id NOT IN (100050711,100219347,100089589,100074576,100259978,100018514)
            UNION ALL
            SELECT
                lg3.grid_id, r3.value
            FROM $wpdb->dt_location_grid lg3
			LEFT JOIN $wpdb->dt_reports r3 ON r3.grid_id=lg3.grid_id AND r3.type = 'prayer_app' AND r3.timestamp >= %d
            WHERE lg3.level = 2
              AND lg3.admin0_grid_id IN (100050711,100219347,100089589,100074576,100259978,100018514)
        ", $current_lap['start_time'], $current_lap['start_time'], $current_lap['start_time'] ), ARRAY_A );

        $data = [];
        $completed = 0;
        $remaining = 0;
        foreach ( $data_raw as $row ) {
            if ( ! isset( $data[$row['grid_id']] ) ) {
                $data[$row['grid_id']] = (int) $row['value'] ?? 0;
                if ( $row['value'] ) {
                    $completed++;
                } else {
                    $remaining++;
                }
            }
        }

        // last ten
        $last_ten_raw = $wpdb->get_results( $wpdb->prepare( "
           SELECT DISTINCT( r.grid_id ), r.*, lg.*
           FROM $wpdb->dt_reports r
           LEFT JOIN $wpdb->dt_location_grid lg ON lg.grid_id=r.grid_id
            WHERE r.post_type = 'laps'
                AND r.type = 'prayer_app'
           AND r.timestamp >= %d
            ORDER BY timestamp DESC
            LIMIT 10
        ", $current_lap['start_time'] ), ARRAY_A );

        $features = [];
        if ( ! empty( $last_ten_raw ) ) {
            foreach( $last_ten_raw as $item ) {
                $lng = (float) $item['longitude'];
                $lat = (float) $item['latitude'];
                $features[] = array(
                    'type' => 'Feature',
                    'properties' => [
                        'grid_id' => $item['grid_id'],
                        'name' => $item['name'],
                    ],
                    'geometry' => array(
                        'type' => 'Point',
                        'coordinates' => array(
                            $lng,
                            $lat,
                            1
                        ),
                    ),
                );
            }
        }

        $last_ten = array(
            'type' => 'FeatureCollection',
            'features' => $features,
        );


        return [
            'data' => $data,
            'last_ten' => $last_ten,
            'completed' => $completed,
            'remaining' => $remaining,
            'start' => date( 'm-d-Y', $current_lap['start_time'] ),
            'current_lap' => $current_lap,
        ];
    }

}
Prayer_Global_Prayer_App_Map::instance();
