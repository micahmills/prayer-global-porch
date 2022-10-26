<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.


/**
 * Class Prayer_Global_Prayer_App
 */
class PG_Global_Prayer_App_Lap extends PG_Global_Prayer_App {

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
        $current_lap = pg_current_global_lap();
        if ( (int) $current_lap['post_id'] === (int) $this->parts['post_id'] ) {
            add_action( 'dt_blank_body', [ $this, 'body' ] );
        } else {
            wp_redirect( trailingslashit( site_url() ) . $this->root . '/' . $this->type . '/' . $this->parts['public_key'] . '/completed' );
            exit;
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

    public function header_javascript(){
        require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . 'assets/header.php' );

        $current_lap = pg_current_global_lap();
        $current_url = trailingslashit( site_url() ) . $this->parts['root'] . '/' . $this->parts['type'] . '/' . $this->parts['public_key'] . '/';
        if ( (int) $current_lap['post_id'] === (int) $this->parts['post_id'] ) {
            ?>
            <!-- Resources -->
            <script src="https://cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js?ver=3"></script>
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
                    'current_url' => $current_url,
                    'stats_url' => $current_url . 'stats',
                    'map_url' => $current_url . 'map',
                    'is_custom' => ( 'custom' === $this->parts['type'] )
                ]) ?>][0]
            </script>
            <script type="text/javascript" src="<?php echo esc_url( DT_Mapbox_API::$mapbox_gl_js ) ?>"></script>
            <link rel="stylesheet" href="<?php echo esc_url( DT_Mapbox_API::$mapbox_gl_css ) ?>" type="text/css" media="all">
            <link rel="stylesheet" href="<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ) ?>assets/basic.css?ver=<?php echo esc_attr( fileatime( trailingslashit( plugin_dir_path( __DIR__ ) ) . 'assets/basic.css' ) ) ?>" type="text/css" media="all">
            <link rel="stylesheet" href="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) ) ?>lap.css?ver=<?php echo esc_attr( fileatime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'lap.css' ) ) ?>" type="text/css" media="all">
            <link rel="prefetch" href="<?php echo esc_url( plugin_dir_url( __DIR__ ) . 'assets/images/celebrate1.gif' ) ?>" >
            <link rel="prefetch" href="<?php echo esc_url( plugin_dir_url( __DIR__ ) . 'assets/images/celebrate2.gif' ) ?>" >
            <link rel="prefetch" href="<?php echo esc_url( plugin_dir_url( __DIR__ ) . 'assets/images/celebrate3.gif' ) ?>" >
            <script src="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) ) ?>lap.js?ver=<?php echo esc_attr( fileatime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'lap.js' ) ) ?>"></script>
            <script src="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) ) ?>report.js?ver=<?php echo esc_attr( fileatime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'report.js' ) ) ?>"></script>
            <?php
        }
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
                    <button type="button" class="btn btn-secondary praying" id="praying__continue_button"><i class="ion-android-arrow-dropright-circle"></i></button>
                    <button type="button" class="btn btn-secondary settings" id="praying__open_options" data-toggle="modal" data-target="#option_filter"><i class="ion-android-options"></i></button>
                </div>
            </div>
            <div class="container question" id="question-panel">
                <div class="btn-group question_button_group" role="group" aria-label="Praying Button">
                    <button type="button" class="btn btn-secondary question" id="question__yes_done">Done</button>
                    <button type="button" class="btn btn-secondary question question__yes" id="question__yes_next">Next</button>
                </div>
            </div>
            <div class="container celebrate text-center" id="celebrate-panel"></div>
            <div class="w-100" ></div>
            <div class="container decision" id="decision-panel">
                <div class="btn-group decision_button_group" role="group" aria-label="Decision Button">
                    <button type="button" class="btn btn-secondary decision" id="decision__home">Home</button>
                    <button type="button" class="btn btn-secondary decision" id="decision__map">Map</button>
                    <button type="button" class="btn btn-secondary decision" id="decision__next">Next</button>
                </div>
            </div>
            <div class="w-100" ></div>
            <div class="container justify-content-center mt-3">
                <h3 class="mt-0 font-weight-normal text-center tutorial" id="tutorial-location">Start praying for</h3>
                <h3 class="mt-0 font-weight-normal text-center" id="location-name"></h3>
            </div>
        </nav>

        <!-- Modal -->
        <div class="modal fade" id="option_filter" tabindex="-1" role="dialog" aria-labelledby="option_filter_label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Set Your Prayer Experience</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <p>Prayer pace per place</p>
                        </div>
                        <div class="btn-group-vertical pace-wrapper">
                            <button type="button" class="btn btn-secondary pace" id="pace__1" value="1">1 Minute</button>
                            <button type="button" class="btn btn-outline-secondary pace" id="pace__2" value="2">2 Minutes</button>
                            <button type="button" class="btn btn-outline-secondary pace" id="pace__3" value="3">3 Minutes</button>
                            <button type="button" class="btn btn-outline-secondary pace" id="pace__5" value="5">5 Minutes</button>
                            <button type="button" class="btn btn-outline-secondary pace" id="pace__10" value="10">10 Minutes</button>
                            <button type="button" class="btn btn-outline-secondary pace" id="pace__15" value="15">15 Minutes</button>
                        </div>
                        <div>
                            <p>Prayer guidance</p>
                        </div>
                        <div class="btn-group-vertical pace-wrapper">
                            <button type="button" class="btn btn-secondary favor favor__guided" data-item-id="favor__guided" value="guided">More Guided</button>
                            <button type="button" class="btn btn-outline-secondary favor favor__facts" data-item-id="favor__facts" value="facts">More Facts</button>
                        </div>
                    </div>
                    <div class="modal-footer center">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Let's Go!</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="welcome_screen" tabindex="-1" role="dialog" aria-labelledby="welcome_screen_label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <p class="center">How Prayer.Global works</p>
                        <h2 class="center">Step 1</h2>
                        <p>
                            <strong>Pray over the location</strong> provided using the maps, photos, prayers, people group info, and facts.
                        </p>

                        <h2 class="center">Step 2</h2>
                        <p>
                            <strong>Pray for one minute</strong> (or longer) as the Spirit leads.
                        </p>
                        <p>
                            <img src="<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ) ?>assets/images/welcome-keep.png" style="opacity:0.5;" class="img-fluid" />
                        </p>
                        <h2 class="center">Step 3</h2>
                        <p>
                            Once the timer transforms, select either "Done" and see your impact, or select "Next" and cover another location in prayer.
                        </p>
                        <p>
                            <img src="<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ) ?>assets/images/welcome-next.png" style="opacity:0.5;" class="img-fluid" />
                        </p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Let's Go!</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- content section -->
        <section>
            <div class="container" id="map">
                <div class="row">
                    <div class="col">
                        <p class="text-md-center" id="location-map"><span class="loading-spinner active"></span></p>
                    </div>
                </div>
            </div>
            <div class="w-100"><hr></div>
            <div id="content"></div>
            <div class="container">
                <div class="row text-center pb-4"><div class="col"><button type="button" class="btn btn-outline-primary" id="more_prayer_fuel">Show More Prayer Fuel</button></div></div>
                <div class="row">
                    <div class="col text-center" style="padding-bottom:2em;">
                        <button class="btn btn-link" id="correction_button">Correction Needed?</button>
                    </div>
                    <div class="modal fade" id="correction_modal"  role="dialog" aria-labelledby="correction_modal_label" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Thank you! Leave us a correction below.</h5>
                                    <button type="button" id="correction_close" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p><span id="correction_title" class="correction_field"></span></p>
                                    <p>
                                        Section:<br>
                                        <select class="form-control correction_field" id="correction_select"></select>
                                    </p>
                                    <p>
                                        Correction Requested:<br>
                                        <textarea class="form-control correction_field" id="correction_response" rows="3"></textarea>
                                    </p>
                                    <p>
                                        <button type="button" class="btn btn-primary" id="correction_submit_button">Submit</button> <span class="loading-spinner correction_modal_spinner"></span>
                                    </p>
                                    <p id="correction_error" class="correction_field"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                return $this->save_log( $params['parts'], $params['data'] );
            case 'correction':
                return $this->save_correction( $params['parts'], $params['data'] );
            case 'refresh':
                return $this->get_new_location( $params['data']['favor'] );
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

        return $this->get_new_location();
    }

    /**
     * @param $parts
     * @param $data
     * @return array|false|int|WP_Error
     */
    public function save_correction( $parts, $data ) {

        if ( !isset( $parts['post_id'], $parts['root'], $parts['type'], $data['grid_id'] ) ) {
            return new WP_Error( __METHOD__, "Missing parameters", [ 'status' => 400 ] );
        }

        if ( empty( $data['section_label'] ) ) {
            $title = $data['current_content']['location']['full_name'];
        } else {
            $title = $data['current_content']['location']['full_name'] . ' (' . $data['section_label'] . ')';
        }

        $current_location_list = 'SECTIONS AVAILABLE DURING REPORT' . PHP_EOL . PHP_EOL;
        foreach ( $data['current_content']['list'] as $list ) {
            $current_location_list .= strtoupper( $list['type'] ) . PHP_EOL;
            foreach ( $list['data'] as $k => $v ){
                if ( is_array( $v ) ) {
                    $v = serialize( $v );
                }
                $current_location_list .= $k . ': ' . $v . PHP_EOL;
            }
            $current_location_list .= PHP_EOL;
        }

        $user_location = 'USER LOCATION' . PHP_EOL . PHP_EOL;
        foreach ( $data['user'] as $uk => $uv ) {
            $user_location .= $uk . ': ' . $uv . PHP_EOL;
        }
        $user_location .= PHP_EOL . 'https://maps.google.com/maps?q='.$data['user']['lat'].','.$data['user']['lng'] .'&ll='.$data['user']['lat'].','.$data['user']['lng'] .'&z=7' .  PHP_EOL;

        $fields = [
            // lap information
            'title' => $title,
            'type' => 'location',
            'status' => 'new',
            'payload' => maybe_serialize( $data ),
            'response' => $data['response'],
            'location_grid_meta' => [
                'values' => [
                    [
                        'grid_id' => $data['grid_id']
                    ]
                ]
            ],
            'user_hash' => $data['user']['hash'],
            'notes' => [
                'Review Link' => get_site_url() . '/show_app/all_content/?grid_id='.$data['grid_id'],
                'Current_Location' => $current_location_list,
                'User_Location' => $user_location,
            ]
        ];

        if ( is_user_logged_in() ) {
            $contact_id = Disciple_Tools_users::get_contact_for_user( get_current_user_id() );
            if ( ! empty( $contact_id ) && ! is_wp_error( $contact_id ) ) {
                $fields['contacts'] = [
                    'values' => [
                        [ 'value' => $contact_id ],
                    ]
                ];
            }
        }

        $result = DT_Posts::create_post( 'feedback', $fields, true, false );

        return $result;
    }

    /**
     * Global query
     * @return array|false|void
     */
    public function get_new_location( $favor = 'guided' ) {
        // get 4770 list
        $list_4770 = pg_query_4770_locations();

        // subtract prayed places
        $list_prayed = $this->_query_prayed_list();
        if ( ! empty( $list_prayed ) ) {
            foreach ( $list_prayed as $grid_id ) {
                if ( isset( $list_4770[$grid_id] ) ) {
                    unset( $list_4770[$grid_id] );
                }
            }
        }

        if ( empty( $list_4770 ) ) {
                $this->_generate_new_prayer_lap();
                return false;
        }

        shuffle( $list_4770 );
        $grid_id = $list_4770[0];

        if ( 'guided' === $favor ) {
            return PG_Stacker::build_location_stack_v2( $grid_id );
        } else if ( 'facts' === $favor ) {
            return PG_Stacker::build_location_stack( $grid_id );
        } else {
            return PG_Stacker::build_location_stack_v2( $grid_id );
        }
    }

    public static function _query_prayed_list() {
        global $wpdb;
        $current_lap = pg_current_global_lap();

        $raw_list = $wpdb->get_col( $wpdb->prepare(
            "SELECT DISTINCT grid_id
                    FROM $wpdb->dt_reports
                    WHERE
                          timestamp >= %d
                      AND type = 'prayer_app'",
        $current_lap['start_time'] ) );

        $list = [];
        if ( ! empty( $raw_list ) ) {
            foreach ( $raw_list as $item ) {
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
        $date = gmdate( 'Y-m-d H:m:s', time() );

        $fields = [];
        $fields['title'] = 'Global #' . $next_global_lap_number;
        $fields['status'] = 'active';
        $fields['type'] = 'global';
        $fields['start_date'] = $date;
        $fields['start_time'] = $time;
        $fields['global_lap_number'] = $next_global_lap_number;
        $fields['prayer_app_global_magic_key'] = $key;
        $new_post = DT_Posts::create_post( 'laps', $fields, false, false );
        if ( is_wp_error( $new_post ) ) {
            // @handle error
            dt_write_log( 'failed to create' );
            dt_write_log( $new_post );
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
        update_option( 'pg_current_global_lap', $lap, true );

        // close previous lap
        DT_Posts::update_post( 'laps', $previous_lap['post_id'], [ 'status' => 'complete', 'end_date' => $date, 'end_time' => $time ], false, false );

        return $new_post['ID'];
    }

    public function get_ip_location() {
        $response = DT_Ipstack_API::get_location_grid_meta_from_current_visitor();
        if ( $response ) {
            $response['hash'] = hash( 'sha256', serialize( $response ) . mt_rand( 1000000, 10000000000000000 ) );
            $array = array_reverse( explode( ', ', $response['label'] ) );
            $response['country'] = $array[0] ?? '';
        }
        return $response;
    }
}
PG_Global_Prayer_App_Lap::instance();
