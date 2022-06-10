<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.


/**
 * Class PG_Custom_Prayer_App_Lap
 */
class PG_Custom_Prayer_App_Lap extends PG_Custom_Prayer_App {

    private static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    } // End instance()

    public function __construct() {
        parent::__construct();

        /**
         * post type and module section
         */
        if ( dt_is_rest() ) {
            add_action( 'rest_api_init', [ $this, 'add_endpoints' ] );
        }

        // must be valid url
        $url = dt_get_url_path();
        if ( strpos( $url, $this->root . '/' . $this->type ) === false ) {
            return;
        }

        // must be valid parts
        if ( !$this->check_parts_match() ){
            return;
        }

        // has empty action, of stop
        if ( ! empty( $this->parts['action'] ) ) {
            return;
        }

        // redirect to completed if not current global lap
        add_action( 'dt_blank_body', [ $this, 'body' ] );
        add_filter( 'dt_magic_url_base_allowed_css', [ $this, 'dt_magic_url_base_allowed_css' ], 10, 1 );
        add_filter( 'dt_magic_url_base_allowed_js', [ $this, 'dt_magic_url_base_allowed_js' ], 10, 1 );

    }

    public function dt_magic_url_base_allowed_js( $allowed_js ) {
        return [];
    }

    public function dt_magic_url_base_allowed_css( $allowed_css ) {
        return [];
    }

    public function header_javascript(){
        require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . 'assets/header.php' );

        ?>
        <!-- Resources -->
        <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
        <script src="https://cdn.amcharts.com/lib/5/map.js"></script>
        <script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
        <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js?ver=3"></script>
        <script src="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) ) ?>lap.js?ver=<?php echo esc_attr( fileatime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'lap.js' ) ) ?>"></script>
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
                'nope' => plugin_dir_url( __DIR__ ) . 'assets/images/nope.jpg',
                'images_url' => pg_grid_image_url(),
                'image_folder' => plugin_dir_url( __DIR__ ) . 'assets/images/',
                'start_content' => $this->get_new_location( $this->parts['post_id'] ),
                'next_content' => $this->get_new_location( $this->parts['post_id'] ),
            ]) ?>][0]
        </script>
        <script type="text/javascript" src="<?php echo esc_url( DT_Mapbox_API::$mapbox_gl_js ) ?>"></script>
        <link rel="stylesheet" href="<?php echo esc_url( DT_Mapbox_API::$mapbox_gl_css ) ?>" type="text/css" media="all">
        <link rel="stylesheet" href="<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ) ?>assets/basic.css?ver=<?php echo esc_attr( fileatime( trailingslashit( plugin_dir_path( __DIR__ ) ) . 'assets/basic.css' ) ) ?>" type="text/css" media="all">
        <link rel="stylesheet" href="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) ) ?>lap.css?ver=<?php echo esc_attr( fileatime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'lap.css' ) ) ?>" type="text/css" media="all">
        <?php
    }

    public function footer_javascript(){
        require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . 'assets/footer.php' );
    }

    public function body(){
        DT_Mapbox_API::geocoder_scripts();
        ?>

        <!-- navigation & widget -->
        <nav class="navbar prayer_navbar fixed-top" id="pb-pray-navbar">
            <div class="container praying" id="praying-panel">
                <div class="btn-group praying_button_group" role="group" aria-label="Praying Button">
                    <button type="button" class="btn praying" id="praying_button" data-percent="0" data-seconds="0">
                        <div class="praying__progress"></div>
                        <span class="praying__text"></span>
                    </button>
                    <button type="button" class="btn btn-secondary praying" id="praying__close_button"><i class="ion-close-circled"></i></button>
                    <button type="button" class="btn btn-secondary settings" id="praying__open_options" data-toggle="modal" data-target="#option_filter"><i class="ion-android-options"></i></button>
                </div>
            </div>
            <div class="container question" id="question-panel">
                Did you pray for this location?
                <div class="btn-group question_button_group" role="group" aria-label="Praying Button">
                    <button type="button" class="btn btn-secondary question" id="question__no">No</button>
                    <button type="button" class="btn btn-secondary question question__yes" id="question__yes_done">Yes & Done</button>
                    <button type="button" class="btn btn-secondary question question__yes" id="question__yes_next">Yes & Next</button>
                </div>
            </div>
            <div class="container celebrate " id="celebrate-panel">
                <div class="text-center">
                    <img src="https://via.placeholder.com/600x400?text=Celebrate+Animation" class="img-fluid celebrate-image" alt="photo" />
                </div>
            </div>
            <div class="w-100" ></div>
            <div class="container decision" id="decision-panel">
                <div class="btn-group decision_button_group" role="group" aria-label="Decision Button">
                    <button type="button" class="btn btn-secondary decision" id="decision__home">Home</button>
                    <button type="button" class="btn btn-secondary decision" id="decision__continue">Continue</button>
                    <button type="button" class="btn btn-secondary decision" id="decision__next">Next</button>
                </div>
            </div>
            <div class="w-100" ></div>
            <div class="container">
                <h3 class="mt-3 font-weight-normal text-center" id="location-name"></h3>
            </div>
        </nav>

        <!-- Modal -->
        <div class="modal fade" id="option_filter" tabindex="-1" role="dialog" aria-labelledby="option_filter_label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Options</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Adjust your prayer pace. Spend longer on each location.<br>
                        <div class="btn-group-vertical pace-wrapper">
                            <button type="button" class="btn btn-secondary pace" id="pace__1" value="1">1 Minute</button>
                            <button type="button" class="btn btn-outline-secondary pace" id="pace__2" value="2">2 Minutes</button>
                            <button type="button" class="btn btn-outline-secondary pace" id="pace__3" value="3">3 Minutes</button>
                            <button type="button" class="btn btn-outline-secondary pace" id="pace__5" value="5">5 Minutes</button>
                            <button type="button" class="btn btn-outline-secondary pace" id="pace__10" value="10">10 Minutes</button>
                            <button type="button" class="btn btn-outline-secondary pace" id="pace__15" value="15">15 Minutes</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- content section -->
        <section>
            <div class="container" id="map">
                <div class="row">
                    <div class="col">
                        <p class="text-md-center" id="location-map"></p>
                        <p class="text-md-center"><button type="button" class="btn btn-link btn-sm" id="show_borders">show detail map</button></p>
                    </div>
                </div>
                <div class="w-100"><hr></div>
            </div>
            <div class="container" id="content"></div>
        </section>

        <?php
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

        switch ( $params['action'] ) {
            case 'log':
                $result = $this->save_log( $params['parts'], $params['data'] );
                return $result;
            case 'refresh':
                return $this->get_new_location( $params['parts']['post_id'] );
            case 'ip_location':
                return $this->get_ip_location();
            default:
                return new WP_Error( __METHOD__, "Incorrect action", [ 'status' => 400 ] );
        }
    }

    /**
     * Log Data Model
     *
     * Lap information includes (post_id, post_type, type, subtype)
     *
     * Prayer information includes (value = number of minutes in prayer, grid_id = location_grid prayed for)
     *
     * User information includes (lng, lat, level, label = location information of the visitors ip address, hash = the unique user id stored by cookie on their client)
     *
     * @param $parts
     * @param $data
     * @return array|false|void|WP_Error
     */
    public function save_log( $parts, $data ) {

        if ( !isset( $parts['post_id'], $parts['root'], $parts['type'], $data['grid_id'] ) ) {
            return new WP_Error( __METHOD__, "Missing parameters", [ 'status' => 400 ] );
        }

        // prayer location log
        $args = [

            // lap information
            'post_id' => $parts['post_id'],
            'post_type' => 'laps',
            'type' => $parts['root'],
            'subtype' => $parts['type'],

            // prayer information
            'value' => $data['pace'] ?? 1,
            'grid_id' => $data['grid_id'],

            // user information
            'payload' => [
                'user_location' => $data['user']['label'],
                'user_language' => 'en' // @todo expand for other languages
            ],
            'lng' => $data['user']['lng'],
            'lat' => $data['user']['lat'],
            'level' => $data['user']['level'],
            'label' => $data['user']['country'],
            'hash' => $data['user']['hash'],
        ];
        if ( is_user_logged_in() ) {
            $args['user_id'] = get_current_user_id();
        }
        $id = dt_report_insert( $args, true, false );

        return $this->get_new_location( $parts['post_id'] );
    }

    /**
     * Global query
     * @return array|false|void
     */
    public function get_new_location( $post_id ) {
        // get 4770 list
        $list_4770 = pg_query_4770_locations();

        // subtract prayed places
        $list_prayed = $this->_query_prayed_list( $post_id );
        if ( ! empty( $list_prayed ) ) {
            foreach ( $list_prayed as $grid_id ) {
                if ( isset( $list_4770[$grid_id] ) ) {
                    unset( $list_4770[$grid_id] );
                }
            }
        }

        if ( empty( $list_4770 ) ) {
            if ( dt_is_rest() ) { // signal new lap to rest request
                return false;
            } else { // if first load on finished lap, redirect to new lap
                wp_redirect( '/prayer_app/custom/'.$this->parts['public_key'].'/completed' );
                exit;
            }
        }

        shuffle( $list_4770 );
        $grid_id = $list_4770[0];

        $content = PG_Stacker::build_location_stack( $grid_id );
        return $content;
    }

    public static function _query_prayed_list( $post_id ) {
        global $wpdb;
        $raw_list = $wpdb->get_col( $wpdb->prepare(
            "SELECT DISTINCT grid_id
                    FROM $wpdb->dt_reports
                    WHERE
                      post_id = %d
                      AND type = 'prayer_app'
                      AND subtype = 'custom'
                      ",
        $post_id ) );

        $list = [];
        if ( ! empty( $raw_list ) ) {
            foreach ( $raw_list as $item ) {
                $list[$item] = $item;
            }
        }

        return $list;
    }

    public function get_ip_location() {
        $response = DT_Ipstack_API::get_location_grid_meta_from_current_visitor();
        if ( $response ) {
            $response['hash'] = hash( 'sha256', serialize( $response ). mt_rand( 1000000, 10000000000000000 ) );
            $array = array_reverse( explode( ', ', $response['label'] ) );
            $response['country'] = $array[0] ?? '';
        }
        return $response;
    }
}
PG_Custom_Prayer_App_Lap::instance();
