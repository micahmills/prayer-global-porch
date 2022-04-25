<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.


/**
 * Class Prayer_Global_Laps_Post_Type_Link
 */
class Prayer_Global_Laps_Post_Type_Link extends DT_Magic_Url_Base {

    public $magic = false;
//    public $parts = false;
    public $page_title = 'Global Lap';
    public $page_description = 'Prayer Laps';
    public $root = "prayer_app";
    public $type = 'global';
    public $post_type = 'laps';
    private $meta_key = '';
    public $show_bulk_send = false;
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

        /**
         * tests if other URL
         */
        $url = dt_get_url_path();
        if ( strpos( $url, $this->root . '/' . $this->type ) === false ) {
            return;
        }
        /**
         * tests magic link parts are registered and have valid elements
         */
        if ( !$this->check_parts_match() ){
            if ( substr( $url, 0, 17 ) === $this->root . '/' . $this->type ) {
                wp_redirect( trailingslashit( site_url() ) . 'newest/lap/' ); // @todo change to redirect to most recent
                exit;
            }
            wp_redirect( site_url() );
            exit;
        }

        // load if valid url
        $current_lap = pg_current_global_lap();
        if ( (int) $current_lap['post_id'] === (int) $this->parts['post_id'] ) {
            add_action( 'dt_blank_body', [ $this, 'body' ] );
        } else {
            add_action( 'dt_blank_body', [ $this, 'completed_body' ] );
        }

        add_filter( 'dt_magic_url_base_allowed_css', [ $this, 'dt_magic_url_base_allowed_css' ], 10, 1 );
        add_filter( 'dt_magic_url_base_allowed_js', [ $this, 'dt_magic_url_base_allowed_js' ], 10, 1 );

    }

    public function dt_magic_url_base_allowed_js( $allowed_js ) {
        return [];
    }

    public function dt_magic_url_base_allowed_css( $allowed_css ) {
        return [];
    }

    public function dt_settings_apps_list( $apps_list )
    {
        $apps_list[$this->meta_key] = [
            'key' => $this->meta_key,
            'url_base' => $this->root . '/' . $this->type,
            'label' => $this->page_title,
            'description' => $this->page_description,
            'settings_display' => false
        ];

        return $apps_list;
    }

    public function header_javascript(){
        require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . 'assets/header.php' );

        $current_lap = pg_current_global_lap();
        if ( (int) $current_lap['post_id'] === (int) $this->parts['post_id'] ) {
            ?>
            <!-- Resources -->
            <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
            <script src="https://cdn.amcharts.com/lib/5/map.js"></script>
            <script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
            <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
            <script src="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) ) ?>prayer.js?ver=<?php echo fileatime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'prayer.js' ) ?>"></script>
            <script>
                let jsObject = [<?php echo json_encode([
                    'map_key' => DT_Mapbox_API::get_key(),
                    'mirror_url' => dt_get_location_grid_mirror( true ),
                    'ipstack' => DT_Ipstack_API::get_key(),
                    'root' => esc_url_raw( rest_url() ),
                    'nonce' => wp_create_nonce( 'wp_rest' ),
                    'parts' => $this->parts,
                    'current_lap' => pg_current_global_lap(),
                    'translations' => [
                        'add' => __( 'Add Magic', 'prayer-global' ),
                    ],
                    'nope' => plugin_dir_url(__DIR__) . 'assets/images/nope.jpg',
                    'images_url' => pg_image_url(),
                    'image_folder' => plugin_dir_url(__DIR__) . 'assets/images/',
                    'start_content' => $this->get_new_location(),
                    'next_content' => $this->get_new_location(),
                ]) ?>][0]
            </script>
            <script type="text/javascript" src="<?php echo DT_Mapbox_API::$mapbox_gl_js ?>"></script>
            <link rel="stylesheet" href="<?php echo DT_Mapbox_API::$mapbox_gl_css ?>" type="text/css" media="all">
            <link rel="stylesheet" href="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) ) ?>prayer.css?ver=<?php echo fileatime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'prayer.css' ) ?>" type="text/css" media="all">
            <?php
        }
    }

    public function footer_javascript(){
        require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . 'assets/footer.php' );
    }

    public function body(){
        DT_Mapbox_API::geocoder_scripts();
        require_once( 'body.php' );
    }
    public function completed_body(){
        require_once( 'global-completed-body.php' );
    }

    /**
     * Register REST Endpoints
     * @link https://github.com/DiscipleTools/disciple-tools-theme/wiki/Site-to-Site-Link for outside of wordpress authentication
     */
    public function add_endpoints() {
        $namespace = $this->root . '/v1';
        register_rest_route(
            $namespace,
            '/'.$this->type,
            [
                [
                    'methods'  => WP_REST_Server::CREATABLE,
                    'callback' => [ $this, 'endpoint' ],
                    'permission_callback' => '__return_true'
                ],
            ]
        );
    }

    public function endpoint( WP_REST_Request $request ) {
        $params = $request->get_params();

        if ( ! isset( $params['parts'], $params['action'], $params['data'] ) ) {
            return new WP_Error( __METHOD__, "Missing parameters", [ 'status' => 400 ] );
        }

        $params = dt_recursive_sanitize_array( $params );

        switch( $params['action'] ) {
            case 'log':
                $result = $this->save_log( $params['parts'], $params['data'] );
                return $result;
            case 'refresh':
                return $this->get_new_location();
            default:
                return new WP_Error( __METHOD__, "Incorrect action", [ 'status' => 400 ] );
        }
    }

    public function save_log( $parts, $data ) {

        if ( !isset( $parts['post_id'], $parts['root'], $parts['type'], $data['grid_id'] ) ) {
            return new WP_Error( __METHOD__, "Missing parameters", [ 'status' => 400 ] );
        }

        $args = [
            'post_id' => $parts['post_id'],
            'post_type' => 'laps',
            'type' => $parts['root'],
            'subtype' => $parts['type'],
            'payload' => null,
            'value' => $data['pace'] ?? 1,
            'grid_id' => $data['grid_id'],
        ];
        if ( is_user_logged_in() ) {
            $args['user_id'] = get_current_user_id();
        }
        $id = dt_report_insert( $args, false );

        return $this->get_new_location();
    }

    /**
     * Global query
     * @return array|false|void
     */
    public function get_new_location() {
        // get 4770 list
        $list_4770 = pg_query_4770_locations();

        // subtract prayed places
        $list_prayed = $this->_query_prayed_list();
        if ( ! empty( $list_prayed ) ) {
            foreach( $list_prayed as $grid_id ) {
                if ( isset( $list_4770[$grid_id] ) ) {
                    unset( $list_4770[$grid_id] );
                }
            }
        }

        if ( empty( $list_4770 ) ) {
            if ( dt_is_rest() ) { // signal new lap to rest request
                return false;
            } else { // if first load on finished lap, redirect to new lap
                $current_lap = pg_current_global_lap();
                $this->_generate_new_prayer_lap();
                wp_redirect( '/prayer_app/global/'.$current_lap['key'] );
                exit;
            }
        }

        shuffle( $list_4770 );
        $grid_id = $list_4770[0];

        $content = PG_Stacker::build_location_stack( $grid_id );
        return $content;
    }

    public static function _query_prayed_list() {
        // global

        global $wpdb;
        $current_lap = pg_current_global_lap();

        $raw_list = $wpdb->get_col( $wpdb->prepare(
            "SELECT DISTINCT grid_id
                    FROM $wpdb->dt_reports
                    WHERE
                          timestamp >= %d
                      AND type = 'prayer_app'"
            , $current_lap['start_time']  ) );

        $list = [];
        if ( ! empty( $raw_list) ) {
            foreach( $raw_list as $item ) {
                $list[$item] = $item;
            }
        }

        return $list;
    }


    public static function _generate_new_prayer_lap() {
        global $wpdb;

        // build new lap number
        $completed_prayer_lap_number = $wpdb->get_var(
            "SELECT COUNT(*) as laps
                    FROM $wpdb->posts p
                    JOIN $wpdb->postmeta pm ON p.ID=pm.post_id AND pm.meta_key = 'type' AND pm.meta_value = 'global'
                    JOIN $wpdb->postmeta pm2 ON p.ID=pm2.post_id AND pm2.meta_key = 'status' AND pm2.meta_value IN ('complete', 'active')
                    WHERE p.post_type = 'laps';"
        );
        $next_global_lap_number = $completed_prayer_lap_number + 1;

        // create key
        $key = pg_generate_key();

        $time = time();
        $date = date( 'Y-m-d H:m:s', time() );

        $fields = [];
        $fields['title'] = 'Global #' . $next_global_lap_number;
        $fields['status'] = 'active';
        $fields['type'] = 'global';
        $fields['start_date'] = $date;
        $fields['start_time'] = $time;
        $fields['global_lap_number'] = $next_global_lap_number;
        $fields['prayer_app_global_magic_key'] = $key;
        $new_post = DT_Posts::create_post('laps', $fields, false, false );
        if ( is_wp_error( $new_post ) ) {
            // @handle error
            dt_write_log('failed to create');
            dt_write_log($new_post);
            exit;
        }

        // update current_lap
        $previous_lap = pg_current_global_lap();
        $lap = [
            'lap_number' => $next_global_lap_number,
            'post_id' => $new_post['ID'],
            'key' => $key,
            'start_time' => $time,
        ];
        update_option('pg_current_global_lap', $lap, true );

        // close previous lap
        DT_Posts::update_post('laps', $previous_lap['post_id'], [ 'status' => 'complete', 'end_date' => $date, 'end_time' => $time ], false, false );

        return $new_post['ID'];
    }
}
Prayer_Global_Laps_Post_Type_Link::instance();
