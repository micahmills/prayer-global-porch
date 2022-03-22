<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.


/**
 * Class Prayer_Global_Laps_Post_Type_Link
 */
class Prayer_Global_Laps_Post_Type_Link extends DT_Magic_Url_Base {

    public $magic = false;
    public $parts = false;
    public $page_title = 'Laps';
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
            dt_write_log('here');
            wp_redirect( site_url() );
            exit;
        }

        // load if valid url
        add_action( 'dt_blank_body', [ $this, 'body' ] ); // body for no post key
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
        ?>
        <script src="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) ) ?>prayer.js?ver=<?php echo fileatime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'prayer.js' ) ?>"></script>
        <script>
            let jsObject = [<?php echo json_encode([
                'map_key' => DT_Mapbox_API::get_key(),
                'ipstack' => DT_Ipstack_API::get_key(),
                'root' => esc_url_raw( rest_url() ),
                'nonce' => wp_create_nonce( 'wp_rest' ),
                'parts' => $this->parts,
                'translations' => [
                    'add' => __( 'Add Magic', 'disciple-tools-plugin-starter-template' ),
                ],
                'start_content' => $this->get_new_location(),
                'next_content' => $this->get_new_location(),
            ]) ?>][0]
        </script>
        <?php
    }

    public function footer_javascript(){
        require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . 'assets/footer.php' );
    }

    public function body(){
        require_once( 'body.php' );
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

        if ( ! isset( $params['parts'], $params['action'] ) ) {
            return new WP_Error( __METHOD__, "Missing parameters", [ 'status' => 400 ] );
        }

        switch( $params['action'] ) {
            case 'log':
                return $this->save_log( $params['parts'], $params['data'] );
            case 'refresh':
                return $this->get_new_location();
            default:
                return new WP_Error( __METHOD__, "Incorrect action", [ 'status' => 400 ] );
        }
    }

    public function save_log( $parts, $data ) {

        $args = [
            'post_id' => $parts['post_id'],
            'post_type' => 'laps',
            'type' => $parts['root'],
            'subtype' => $parts['type'],
            'payload' => null,
            'value' => 1,
            'grid_id' => $data['grid_id'],
        ];
        if ( is_user_logged_in() ) {
            $args['user_id'] = get_current_user_id();
        }
        $id = dt_report_insert( $args, false );

        dt_write_log($id);

        return $this->get_new_location();
    }

    public function get_new_location() {
        // get current lap id
        $lap_id = $this->query_current_lap();

        // get 4770 list
        $list_4770 = $this->query_4770_locations();

        // subtract prayed places


        // subtract checked out places

        // exclude locations recently prayed for

        // select location

        // query for location data

        // build array

        $grid_id = $list_4770[0] + rand (10,1000);

        $content = [
            'grid_id' => $grid_id,
            'location' => $this->build_location_array( $grid_id ),
            'sections' => $this->build_sections_array( $grid_id )
        ];
        return $content;
    }

    public function query_current_lap() {
        $current_prayer_lap_post_id = get_option('pg_current_prayer_lap');
        if ( empty( $current_prayer_lap_post_id ) ) {
            $current_prayer_lap_post_id = $this->generate_new_global_prayer_lap();
        }
        return $current_prayer_lap_post_id;
    }
    public function generate_new_global_prayer_lap() {
        global $wpdb;
        // verify previous lap complete
        $current_prayer_lap_post_id = get_option('pg_current_prayer_lap');
        if ( ! empty( $current_prayer_lap_post_id ) ) {
            $total_locations = $wpdb->get_var(
                "SELECT COUNT( DISTINCT grid_id) as total_locations
                        FROM $wpdb->dt_reports
                        WHERE post_id = 11
                          AND type = 'prayer_app'
                          AND subtype = 'global';"
            );
            if ( $total_locations < 4770 ) {
                return $current_prayer_lap_post_id;
            }
        }

        // build new lap number
        $completed_prayer_lap_number = $wpdb->get_var(
            "SELECT COUNT(*) as laps
                    FROM $wpdb->posts p
                    JOIN $wpdb->postmeta pm ON p.ID=pm.post_id AND pm.meta_key = 'type' AND pm.meta_value = 'global'
                    JOIN $wpdb->postmeta pm2 ON p.ID=pm2.post_id AND pm2.meta_key = 'status' AND pm2.meta_value = 'complete'
                    WHERE p.post_type = 'laps';"
        );
        $next_global_lap_number = $completed_prayer_lap_number + 1;

        $fields = [];
        $fields['title'] = 'Global #' . $next_global_lap_number;
        $fields['global_lap_number'] = $next_global_lap_number;
        $fields['status'] = 'active';
        $fields['type'] = 'global';
        $fields['start_date'] = time();

        $new_post = DT_Posts::create_post('laps', $fields, true, false );
        if ( is_wp_error( $new_post ) ) {
            // @handle error
            dt_write_log($new_post);
            exit;
        }

        update_option('pg_current_prayer_lap', $new_post['ID'], true );

        // @todo set to complete all previous laps??

        return $new_post['ID'];
    }
    public function query_4770_locations() {

        // @todo add cache response

        global $wpdb;
        $list = $wpdb->get_col(
            "SELECT
                        lg1.grid_id
                    FROM $wpdb->dt_location_grid lg1
                    WHERE lg1.level = 0
                      AND lg1.grid_id NOT IN ( SELECT lg11.admin0_grid_id FROM $wpdb->dt_location_grid lg11 WHERE lg11.level = 1 AND lg11.admin0_grid_id = lg1.grid_id )
                      AND lg1.admin0_grid_id NOT IN (100050711,100219347,100089589,100074576,100259978,100018514)
                    UNION ALL
                    SELECT
                        lg2.grid_id
                    FROM $wpdb->dt_location_grid lg2
                    WHERE lg2.level = 1
                      AND lg2.admin0_grid_id NOT IN (100050711,100219347,100089589,100074576,100259978,100018514)
                    UNION ALL
                    SELECT
                        lg3.grid_id
                    FROM $wpdb->dt_location_grid lg3
                    WHERE lg3.level = 2
                      AND lg3.admin0_grid_id IN (100050711,100219347,100089589,100074576,100259978,100018514)"
        );

        return $list;
    }
    public function query_prayed_locations() {
        global $wpdb;
//        $list = $wpdb->get_col(
//            "SELECT"
//        );

    }
    public function query_checked_out_locations() {

    }
    public function build_location_array( $grid_id ) {
        global $wpdb;
        $row = $wpdb->get_row($wpdb->prepare( "SELECT * FROM $wpdb->dt_location_grid WHERE grid_id = %d", $grid_id ), ARRAY_A );
        return [
            'name' => $row['name'] . ', ' . $row['country_code'],
            'url' => 'https://via.placeholder.com/500x200',
            'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries"
        ];
    }
    public function build_sections_array( $grid_id ) {
        return [
            [
                'name' => 'Praise',
                'url' => 'https://via.placeholder.com/500x200',
                'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries"
            ],
            [
                'name' => 'Kingdom Come',
                'url' => 'https://via.placeholder.com/500x200',
                'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries"
            ],
            [
                'name' => 'Pray the Book of Acts',
                'url' => 'https://via.placeholder.com/500x200',
                'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries"
            ]
        ];
    }
}
Prayer_Global_Laps_Post_Type_Link::instance();
