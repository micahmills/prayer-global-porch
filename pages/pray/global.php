<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.


/**
 * Class Prayer_Global_Laps_Post_Type_Link
 */
class Prayer_Global_Laps_Post_Type_Link extends DT_Magic_Url_Base {

    public $magic = false;
//    public $parts = false;
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
            if ( substr( $url, 0, 17 ) === $this->root . '/' . $this->type ) {
                wp_redirect( trailingslashit( site_url() ) . 'newest/lap/' ); // @todo change to redirect to most recent
                exit;
            }
            wp_redirect( site_url() );
            exit;
        }

        // load if valid url
        $current_lap = PG_Utilities::get_current_global_lap();
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

        $current_lap = PG_Utilities::get_current_global_lap();
        if ( (int) $current_lap['post_id'] === (int) $this->parts['post_id'] ) {
            ?>
            <script src="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) ) ?>prayer.js?ver=<?php echo fileatime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'prayer.js' ) ?>"></script>
            <script>
                let jsObject = [<?php echo json_encode([
                    'map_key' => DT_Mapbox_API::get_key(),
                    'ipstack' => DT_Ipstack_API::get_key(),
                    'root' => esc_url_raw( rest_url() ),
                    'nonce' => wp_create_nonce( 'wp_rest' ),
                    'parts' => $this->parts,
                    'current_lap' => PG_Utilities::get_current_global_lap(),
                    'translations' => [
                        'add' => __( 'Add Magic', 'prayer-global' ),
                    ],
                    'start_content' => $this->get_new_location(),
                    'next_content' => $this->get_new_location(),
                ]) ?>][0]
            </script>
            <?php
        } else {
            dt_write_log(__METHOD__);
        }
    }

    public function footer_javascript(){
        require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . 'assets/footer.php' );
    }

    public function body(){
        require_once( 'body.php' );
    }
    public function completed_body(){
        require_once( 'completed-body.php' );
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
            'value' => 1,
            'grid_id' => $data['grid_id'],
        ];
        if ( is_user_logged_in() ) {
            $args['user_id'] = get_current_user_id();
        }
        $id = dt_report_insert( $args, false );

        return $this->get_new_location();
    }

    public function get_new_location( $post_id = null ) {
        // get current lap id
        $current_lap = PG_Utilities::get_current_global_lap();
        $post_id = $current_lap['post_id'];

        // get 4770 list
        $list_4770 = PG_Utilities::query_4770_locations();

        // subtract prayed places
        $list_prayed = PG_Utilities::query_prayed_list( $post_id );
        if ( ! empty( $list_prayed ) ) {
            foreach( $list_prayed as $grid_id ) {
                if ( isset( $list_4770[$grid_id] ) ) {
                    unset( $list_4770[$grid_id] );
                }
            }
        }
//        dt_write_log($current_lap);
//        dt_write_log($post_id);
//        dt_write_log($list_4770);
//        dt_write_log($list_prayed);

        if ( empty( $list_4770 ) ) {
            if ( dt_is_rest() ) { // signal new lap to rest request
                return false;
            } else { // if first load on finished lap, redirect to new lap
                PG_Utilities::generate_new_global_prayer_lap();
                wp_redirect( '/prayer_app/global/'.$current_lap['key'] );
                exit;
            }
        }

        if ( count( $list_4770 ) > 20 ) { // turn off shuffle for the last few records
            shuffle( $list_4770 );
        } else {
            sort( $list_4770 );
        }
        $grid_id = $list_4770[0];

        $content = [
            'grid_id' => $grid_id,
            'location' => $this->build_location_array( $grid_id ),
            'sections' => $this->build_sections_array( $grid_id )
        ];
        return $content;
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
