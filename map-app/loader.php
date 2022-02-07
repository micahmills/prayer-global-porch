<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.

class Prayer_Global_Porch_Map_App extends DT_Magic_Url_Base
{
    public $magic = false;
    public $parts = false;
    public $page_title = 'Global Prayer Map';
    public $root = 'map';
    public $type = 'lap';
    public $type_name = 'Global Prayer Map';
    public static $token = 'map_lap';
    public $post_type = 'groups';
    public $us_div = 2500; // this is 2 for every 5000
    public $global_div = 2500; // this equals 2 for every 50000

    private static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    } // End instance()

    public function __construct() {
        parent::__construct();

        $url = dt_get_url_path();
        if ( ($this->root . '/' . $this->type) === $url ) {

            $this->magic = new DT_Magic_URL( $this->root );
            $this->parts = $this->magic->parse_url_parts();

            require_once( 'queries.php' );

            // register url and access
            add_action( "template_redirect", [ $this, 'theme_redirect' ] );
            add_filter( 'dt_blank_access', function (){ return true;
            }, 100, 1 ); // allows non-logged in visit
            add_filter( 'dt_allow_non_login_access', function (){ return true;
            }, 100, 1 );
            add_filter( 'dt_override_header_meta', function (){ return true;
            }, 100, 1 );

            // header content
            add_filter( "dt_blank_title", [ $this, "page_tab_title" ] ); // adds basic title to browser tab
            add_action( 'wp_print_scripts', [ $this, 'print_scripts' ], 1500 ); // authorizes scripts
            add_action( 'wp_print_styles', [ $this, 'print_styles' ], 1500 ); // authorizes styles


            // page content
            add_action( 'dt_blank_head', [ $this, '_header' ] );
            add_action( 'dt_blank_footer', [ $this, '_footer' ] );
            add_action( 'dt_blank_body', [ $this, 'body' ] ); // body for no post key

            add_filter( 'dt_magic_url_base_allowed_css', [ $this, 'dt_magic_url_base_allowed_css' ], 10, 1 );
            add_filter( 'dt_magic_url_base_allowed_js', [ $this, 'dt_magic_url_base_allowed_js' ], 10, 1 );
            add_action( 'wp_enqueue_scripts', [ $this, '_wp_enqueue_scripts' ], 100 );
        }

        if ( dt_is_rest() ) {
            require_once( 'queries.php' );
            add_action( 'rest_api_init', [ $this, 'add_endpoints' ] );
            add_filter( 'dt_allow_rest_access', [ $this, 'authorize_url' ], 10, 1 );
        }
    }

    public function dt_magic_url_base_allowed_js( $allowed_js ) {
        return [
            'jquery',
            'jquery-ui',
            'jquery-touch-punch',
            'lodash',
            'site-js',
            'shared-functions',
            'mapbox-gl',
            'mapbox-cookie',
            self::$token
        ];
    }

    public function dt_magic_url_base_allowed_css( $allowed_css ) {
        return [
            'foundation-css',
            'jquery-ui-site-css',
            'site-css',
            'mapbox-gl-css',
            self::$token
        ];
    }

    public function _wp_enqueue_scripts(){
        $url = dt_get_url_path();
        if ( strpos( $url, $this->root . '/' . $this->type ) !== false ) {
            wp_enqueue_script( 'lodash' );
            wp_enqueue_script( 'jquery-ui' );
            wp_enqueue_script( 'jquery-touch-punch' );

            wp_enqueue_script( self::$token, trailingslashit( plugin_dir_url( __FILE__ ) ) . 'heatmap.js', [
                'jquery',
                'jquery-touch-punch'
            ], filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) .'heatmap.js' ) );

            wp_enqueue_style( self::$token, trailingslashit( plugin_dir_url( __FILE__ ) ) . 'heatmap.css', [ 'site-css' ], filemtime( plugin_dir_path( __FILE__ ) .'heatmap.css' ) );
        }
    }

    public function header_javascript(){
        require_once( 'header.php' );
    }

    public function footer_javascript(){
        require_once( 'footer.php' );
    }

    public function body(){
        DT_Mapbox_API::geocoder_scripts();
        require_once( 'body.php' );
    }

    /**
     * Grid list build initial map list of elements and drives sidebar
     * @return array
     */
    public function _initial_polygon_value_list(){
        $flat_grid = Prayer_Global_Porch_Map_App_Queries::query_saturation_list();
        $grid_totals = $this->get_grid_totals();

        $data = [];
        $highest_value = 1;
        foreach ( $flat_grid as $i => $v ){
            $data[$i] = [
                'grid_id' => $i,
                'population' => number_format_i18n( $v['population'] ),
                'needed' => 1,
                'reported' => 0,
                'percent' => 0,
            ];

            $population_division = $this->get_population_division( $v['country_code'] );

            $needed = round( $v['population'] / $population_division );
            if ( $needed < 1 ){
                $needed = 1;
            }

            if ( isset( $grid_totals[$v['grid_id']] ) && ! empty( $grid_totals[$v['grid_id']] ) ){
                $reported = $grid_totals[$v['grid_id']];
                if ( ! empty( $reported ) && ! empty( $needed ) ){
                    $data[$v['grid_id']]['needed'] = $needed;

                    if ( $reported > $needed ) {
                        $reported = $needed;
                    }
                    $data[$v['grid_id']]['reported'] = $reported;

                    $percent = round( $reported / $needed * 100 );
                    if ( 100 < $percent ) {
                        $percent = 100;
                    } else {
                        $percent = number_format_i18n( $percent, 2 );
                    }
                    $data[$v['grid_id']]['percent'] = $percent;
                }
            }
            else {
                $data[$v['grid_id']]['percent'] = 0;
                $data[$v['grid_id']]['reported'] = 0;
                $data[$v['grid_id']]['needed'] = $needed;
            }

            if ( $highest_value < $data[$v['grid_id']]['reported'] ){
                $highest_value = $data[$v['grid_id']]['reported'];
            }
        }

        return [
            'highest_value' => (int) $highest_value,
            'data' => $data
        ];
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
                ],
            ]
        );
    }

    /**
     * @param WP_REST_Request $request
     * @return array|false|int|WP_Error|null
     */
    public function endpoint( WP_REST_Request $request ) {
        $params = $request->get_params();

        if ( ! isset( $params['parts'], $params['action'] ) ) {
            return new WP_Error( __METHOD__, "Missing parameters", [ 'status' => 400 ] );
        }

        $params = dt_recursive_sanitize_array( $params );
        $action = sanitize_text_field( wp_unslash( $params['action'] ) );

        switch ( $action ) {
            case 'self':
                return $this->get_self( $params['grid_id'] );
            case 'a3':
            case 'a2':
            case 'a1':
            case 'a0':
            case 'world':
                return $this->endpoint_get_level( $params['grid_id'], $action );
            case 'activity_data':
                $grid_id = sanitize_text_field( wp_unslash( $params['grid_id'] ) );
                $offset = sanitize_text_field( wp_unslash( $params['offset'] ) );
                return $this->query_activity_data( $grid_id, $offset );
            case 'grid_data':
                return $this->_initial_polygon_value_list();
            default:
                return new WP_Error( __METHOD__, "Missing valid action", [ 'status' => 400 ] );
        }
    }

    public function get_self( $grid_id ) {
        global $wpdb;

        // get grid elements for design
        $grid = $wpdb->get_row( $wpdb->prepare( "
            SELECT
              g.grid_id,
              g.level,
              g.alt_name as name,
              gn.alt_name as parent_name,
              g.country_code,
              g.population,
              IF(ROUND(g.population / IF(g.country_code = 'US', %d, %d)) < 1, 1,
                 ROUND(g.population / IF(g.country_code = 'US', %d, %d))) as needed,
              (SELECT COUNT(prs.grid_id) FROM $wpdb->dt_location_grid as prs WHERE prs.parent_id = g.parent_id ) as peers
            FROM $wpdb->dt_location_grid as g
            LEFT JOIN $wpdb->dt_location_grid as gn ON g.parent_id=gn.grid_id
            WHERE g.grid_id = %s
        ", $this->us_div, $this->global_div, $this->us_div, $this->global_div, $grid_id ), ARRAY_A );

        // set array
        $population_division = $this->get_population_division( $grid['country_code'] );
        $data = [
            'level' => $grid['level'],
            'parent_level' => $grid['level'] - 1, // one level higher than current
            'population_division' => number_format_i18n( $population_division ), // label for content not calculation
            'name' => $grid['name'],
            'parent_name' => $grid['parent_name'],
            'peers' => number_format_i18n( $grid['peers'] ),
            'population' => number_format_i18n( $grid['population'] ),
            'needed' => number_format_i18n( $grid['needed'] ),
        ];

        return $data;
    }

    public function endpoint_get_level( $grid_id, $administrative_level ) {
        // add levels
        $list = $this->get_grid_totals_by_level( $administrative_level ); // get list of training counts
        $flat_grid = Prayer_Global_Porch_Map_App_Queries::query_flat_grid_by_level( $administrative_level, $this->us_div, $this->global_div );
        $flat_grid_limited = $this->_limit_counts( $flat_grid, $list ); // limit counts to no larger than needed per location.

        $grid = Prayer_Global_Porch_Map_App_Queries::query_grid_elements( $grid_id ); // get level ids for grid_id

        if ( isset( $flat_grid_limited[$grid[$administrative_level]] ) && ! empty( $flat_grid_limited[$grid[$administrative_level]] ) ) {
            $level = $flat_grid_limited[$grid[$administrative_level]];
        }
        else {
            return false;
        }

        $percent = ceil( $level['reported'] / $level['needed'] * 100 );
        if ( 100 < $percent ) {
            $percent = 100;
        } else {
            $percent = number_format_i18n( $percent, 2 );
        }

        /**
         * @todo temp cover for populations
         */
        if ( isset( $grid[$administrative_level . '_population'] )
            && ! empty( $grid[$administrative_level . '_population'] )
            && in_array( $administrative_level, [ 'a0', 'world' ] ) ) {
            $level['population'] = $grid[$administrative_level . '_population'];

            $population_division = $this->get_population_division( $grid['country_code'] );
            $needed = round( $level['population'] / ( $population_division / 2 ) );
            if ( $needed < 1 ){
                $needed = 1;
            }
            $level['needed'] = $needed;
            if ( $administrative_level === 'world' ) {
                $world_population = 7860000000;
                $us_population = 331000000;
                $global_pop_block = $this->global_div;
                $us_pop_block = $this->us_div;
                $world_population_without_us = $world_population - $us_population;
                $needed_without_us = $world_population_without_us / $global_pop_block;
                $needed_in_the_us = $us_population / $us_pop_block;
                $level['needed'] = $needed_without_us + $needed_in_the_us;
            }
        }
        // @todo end temp cover for populations

        if ( empty( $level['name'] ) ) {
            return false;
        }

        $data = [
            'name' => $level['name'],
            'grid_id' => (int) $level['grid_id'],
            'population' => number_format_i18n( $level['population'] ),
            'needed' => number_format_i18n( $level['needed'] ),
            'reported' => number_format_i18n( $level['reported'] ),
            'percent' => $percent,
        ];

        return $data;
    }

    /**
     * Function limits counts to no higher than the location need. This keeps from inflating the counts up the levels.
     * @param $flat_grid
     * @param $list
     * @return array
     */
    public function _limit_counts( $flat_grid, $list ) {
        $flat_grid_limited = [];
        foreach ( $flat_grid as $value ) {
            $flat_grid_limited[$value['grid_id']] = $value;

            if ( isset( $list[$value['grid_id']] ) && ! empty( $list[$value['grid_id']] ) ) {
                if ( $list[$value['grid_id']] <= $value['needed'] ) {
                    $flat_grid_limited[$value['grid_id']]['reported'] = $list[$value['grid_id']];
                } else {
                    $flat_grid_limited[$value['grid_id']]['reported'] = $value['needed'];
                }
            }
        }
        return $flat_grid_limited;
    }

    /****************************************************************************************
     * ACTIVITY SECTION
     ****************************************************************************************/

    public function activity_data( WP_REST_Request $request ){
        $params = $request->get_json_params() ?? $request->get_body_params();

        if ( ! isset( $params['grid_id'] ) ) {
            return new WP_Error( __METHOD__, 'no grid id' );
        }
        if ( ! isset( $params['offset'] ) ) {
            return new WP_Error( __METHOD__, 'no grid id' );
        }
        $grid_id = sanitize_text_field( wp_unslash( $params['grid_id'] ) );
        $offset = sanitize_text_field( wp_unslash( $params['offset'] ) );

        return $this->query_activity_data( $grid_id, $offset );
    }

    public function query_activity_data( $grid_id, $offset ) {
        global $wpdb;
        $ids = [];
        $ids[] = $grid_id;
        $children = Disciple_Tools_Mapping_Queries::get_children_by_grid_id( $grid_id );
        if ( ! empty( $children ) ) {
            foreach ( $children as $child ){
                $ids[] = $child['grid_id'];
            }
        }
        $prepared_list = dt_array_to_sql( $ids );
        // phpcs:disable
        $list = $wpdb->get_results("
                SELECT
                       id,
                       action,
                       category,
                       lng,
                       lat,
                       label,
                       grid_id,
                       payload,
                       timestamp,
                       'A Zúme partner' as site_name
                FROM $wpdb->dt_movement_log
                WHERE grid_id IN ($prepared_list)
                ORDER BY timestamp DESC", ARRAY_A);
        // phpcs:enable
        if ( empty( $list ) ){
            return [];
        }

        foreach ( $list as $index => $item ){
            $list[$index]['payload'] = maybe_unserialize( $item['payload'] );
            $list[$index]['formatted_time'] = gmdate( 'M, d Y, g:i a', $item['timestamp'] + $offset );
        }

        if ( function_exists( 'zume_log_actions' ) ) {
            $list = zume_log_actions( $list );
        }
        if ( function_exists( 'dt_network_dashboard_translate_log_generations' ) ) {
            $list = dt_network_dashboard_translate_log_generations( $list );
        }
        if ( function_exists( 'dt_network_dashboard_translate_log_new_posts' ) ) {
            $list = dt_network_dashboard_translate_log_new_posts( $list );
        }

        foreach ( $list as $index => $item ){
            if ( ! isset( $item['message'] ) ) {
                $list[$index]['message'] = 'Non-public movement event reported.';
            }
        }

        return $list;
    }

    /****************************************************************************************
     * NEW REPORTS SECTION
     ****************************************************************************************/

//    public function endpoint_new_report( $form_data ) {
//        global $wpdb;
//        if ( ! isset( $form_data['grid_id'], $form_data['name'], $form_data['email'], $form_data['phone'], $form_data['list'] ) ) {
//            return new WP_Error( __METHOD__, 'Missing params.', [ 'status' => 400 ] );
//        }
//        if ( ! is_array( $form_data['list'] ) || empty( $form_data['list'] ) ) {
//            return new WP_Error( __METHOD__, 'List missing.', [ 'status' => 400 ] );
//        }
//
//        $contact_id = false;
//
//        // try to get contact_id and contact
//        if ( isset( $form_data['contact_id'] ) && ! empty( $form_data['contact_id'] ) ) {
//            $contact_id = (int) $form_data['contact_id'];
//            $contact = DT_Posts::get_post( 'contacts', $contact_id, false, false );
//            if ( is_wp_error( $contact ) ){
//                return $contact;
//            }
//        }
//        else if ( isset( $form_data['return_reporter'] ) && $form_data['return_reporter'] ) {
//            $email = sanitize_email( wp_unslash( $form_data['email'] ) );
//            // phpcs:disable
//            $contact_ids = $wpdb->get_results($wpdb->prepare( "
//                SELECT DISTINCT pm.post_id
//                FROM $wpdb->postmeta as pm
//                JOIN $wpdb->postmeta as pm1 ON pm.post_id=pm1.post_id AND pm1.meta_key LIKE 'contact_email%' AND pm1.meta_key NOT LIKE '%details'
//                WHERE pm.meta_key = 'overall_status' AND pm.meta_value = 'active' AND pm1.meta_value = %s
//            ", $email ), ARRAY_A );
//            // phpcs:enable
//            if ( ! empty( $contact_ids ) ){
//                $contact_id = $contact_ids[0]['post_id'];
//                $contact = DT_Posts::get_post( 'contacts', $contact_id, false, false );
//                if ( is_wp_error( $contact ) ){
//                    return $contact;
//                }
//            }
//        }
//
//        // create contact if not able to be found
//        if ( ! $contact_id ) {
//            // create contact
//            $fields = [
//                'title' => $form_data['name'],
//                "overall_status" => "new",
//                "type" => "access",
//                "contact_email" => [
//                    [ "value" => $form_data['email'] ],
//                ],
//                "contact_phone" => [
//                    [ "value" => $form_data['phone'] ],
//                ],
//                'notes' => [
//                    'source_note' => 'Submitted from public heatmap.'
//                ]
//
//            ];
//            if ( DT_Mapbox_API::get_key() ) {
//                $fields["location_grid_meta"] = [
//                    "values" => [
//                        [ "grid_id" => $form_data['grid_id'] ]
//                    ]
//                ];
//            } else {
//                $fields["location_grid"] = [
//                    "values" => [
//                        [ "value" => $form_data['grid_id'] ]
//                    ]
//                ];
//            }
//
//            $contact = DT_Posts::create_post( 'contacts', $fields, true, false );
//            if ( is_wp_error( $contact ) ){
//                return $contact;
//            }
//            $contact_id = $contact['ID'];
//        }
//
//        // create groups
//        $group_ids = [];
//        $groups = [];
//        foreach ( $form_data['list'] as $group ) {
//            $fields = [
//                'title' => $group['name'],
//                'member_count' => $group['members'],
//                'start_date' => $group['start'],
//                'church_start_date' => $group['start'],
//                'group_status' => 'active',
//                'leader_count' => 1,
//                'group_type' => 'church',
//                'members' => [
//                    "values" => [
//                        [ "value" => $contact_id ],
//                    ],
//                ],
//                'leaders' => [
//                    "values" => [
//                        [ "value" => $contact_id ],
//                    ],
//                ],
//                'notes' => [
//                    'source_note' => 'Submitted from public heatmap.'
//                ]
//            ];
//            if ( DT_Mapbox_API::get_key() ) {
//                $fields["location_grid_meta"] = [
//                    "values" => [
//                        [ "grid_id" => $form_data['grid_id'] ]
//                    ]
//                ];
//            } else {
//                $fields["location_grid"] = [
//                    "values" => [
//                        [ "value" => $form_data['grid_id'] ]
//                    ]
//                ];
//            }
//
//            $g = DT_Posts::create_post( 'groups', $fields, true, false );
//            if ( is_wp_error( $g ) ){
//                $groups[] = $g;
//                continue;
//            }
//            $group_id = $g['ID'];
//            $group_ids[] = $group_id;
//            $groups[$group_id] = $g;
//        }
//
//        // create connections
//        $connection_ids = [];
//        if ( ! empty( $group_ids ) ) {
//            foreach ( $group_ids as $gid ) {
//                $fields = [
//                    "peer_groups" => [
//                        "values" => [],
//                    ]
//                ];
//                foreach ( $group_ids as $subid ) {
//                    if ( $gid === $subid ) {
//                        continue;
//                    }
//                    $fields['peer_groups']['values'][] = [ "value" => $subid ];
//                }
//
//                $c = DT_Posts::update_post( 'groups', $gid, $fields, true, false );
//                $connection_ids[] = $c;
//            }
//        }
//
//        $data = [
//            'contact' => $contact,
//            'groups' => $groups,
//            'connections' => $connection_ids
//        ];
//
//        return $data;
//    }

    public function get_reported( $grid_id, $grid_totals = [] ) : int {
        if ( isset( $grid_totals[$grid_id] )){
            return (int) $grid_totals[$grid_id]['count'];
        }
        else {
            return 0;
        }
    }

    public function get_grid_totals(){
        return $this->query_church_grid_totals();
    }

    public function get_grid_totals_by_level( $administrative_level ) {
        return $this->query_church_grid_totals( $administrative_level );
    }

    /**
     * Can be customized with class extension
     * @param $country_code
     * @return float|int
     */
    public function get_population_division( $country_code ){
        return 5000;
    }


    /**
     * Can be customized with class extension
     */
    public function customized_welcome_script(){
        ?>
        <script>
            jQuery(document).ready(function($){
                let asset_url = '<?php echo esc_url( plugin_dir_url( __FILE__ ) ) ?>'
                $('.training-content').append(`
                <div class="grid-x grid-padding-x" >
                    <div class="cell center">
                        <img class="training-screen-image" src="${asset_url + 'image/search.svg'}" alt="search icon" />
                        <h2>Search</h2>
                        <p>Search for any city or place with the search input.</p>
                    </div>
                    <div class="cell center">
                        <img class="training-screen-image" src="${asset_url + 'image/zoom.svg'}" alt="zoom icon"  />
                        <h2>Zoom</h2>
                        <p>Scroll zoom with your mouse or pinch zoom with track pads and phones to focus on sections of the map.</p>
                    </div>
                    <div class="cell center">
                        <img class="training-screen-image" src="${asset_url + 'image/drag.svg'}" alt="drag icon"  />
                        <h2>Drag</h2>
                        <p>Click and drag the map any direction to look at a different part of the map.</p>
                    </div>
                    <div class="cell center">
                        <img class="training-screen-image" src="${asset_url + 'image/click.svg'}" alt="click icon" />
                        <h2>Click</h2>
                        <p>Click a single section and reveal a details panel with more information about the location.</p>
                    </div>
                </div>
                `)

            })
        </script>
        <?php
    }

    public static function query_church_grid_totals( $administrative_level = null ) {
        global $wpdb;

        switch ( $administrative_level ) {
            case 'a0':
                $results = $wpdb->get_results( "
                    SELECT t0.admin0_grid_id as grid_id, count(t0.admin0_grid_id) as count
                    FROM (
                     SELECT lg.admin0_grid_id, lg.admin1_grid_id, lg.admin2_grid_id, lg.admin3_grid_id, lg.admin4_grid_id, lg.admin5_grid_id
                        FROM $wpdb->postmeta as pm
                        JOIN $wpdb->posts as p ON p.ID=pm.post_id AND p.post_type = 'groups'
                        JOIN $wpdb->postmeta as pm2 ON pm2.post_id=pm.post_id AND pm2.meta_key = 'group_type' AND pm2.meta_value = 'church'
                        LEFT JOIN $wpdb->dt_location_grid as lg ON pm.meta_value=lg.grid_id
                        WHERE pm.meta_key = 'location_grid'
                    ) as t0
                    GROUP BY t0.admin0_grid_id
                    ", ARRAY_A );
                break;
            case 'a1':
                $results = $wpdb->get_results( "
                    SELECT t1.admin1_grid_id as grid_id, count(t1.admin1_grid_id) as count
                    FROM (
                        SELECT lg.admin0_grid_id, lg.admin1_grid_id, lg.admin2_grid_id, lg.admin3_grid_id, lg.admin4_grid_id, lg.admin5_grid_id
                        FROM $wpdb->postmeta as pm
                        JOIN $wpdb->posts as p ON p.ID=pm.post_id AND p.post_type = 'groups'
                        JOIN $wpdb->postmeta as pm2 ON pm2.post_id=pm.post_id AND pm2.meta_key = 'group_type' AND pm2.meta_value = 'church'
                        LEFT JOIN $wpdb->dt_location_grid as lg ON pm.meta_value=lg.grid_id
                        WHERE pm.meta_key = 'location_grid'
                    ) as t1
                    GROUP BY t1.admin1_grid_id
                    ", ARRAY_A );
                break;
            case 'a2':
                $results = $wpdb->get_results( "
                    SELECT t2.admin2_grid_id as grid_id, count(t2.admin2_grid_id) as count
                    FROM (
                        SELECT lg.admin0_grid_id, lg.admin1_grid_id, lg.admin2_grid_id, lg.admin3_grid_id, lg.admin4_grid_id, lg.admin5_grid_id
                        FROM $wpdb->postmeta as pm
                        JOIN $wpdb->posts as p ON p.ID=pm.post_id AND p.post_type = 'groups'
                        JOIN $wpdb->postmeta as pm2 ON pm2.post_id=pm.post_id AND pm2.meta_key = 'group_type' AND pm2.meta_value = 'church'
                        LEFT JOIN $wpdb->dt_location_grid as lg ON pm.meta_value=lg.grid_id
                        WHERE pm.meta_key = 'location_grid'
                    ) as t2
                    GROUP BY t2.admin2_grid_id
                    ", ARRAY_A );
                break;
            case 'a3':
                $results = $wpdb->get_results( "
                    SELECT t3.admin3_grid_id as grid_id, count(t3.admin3_grid_id) as count
                    FROM (
                        SELECT lg.admin0_grid_id, lg.admin1_grid_id, lg.admin2_grid_id, lg.admin3_grid_id, lg.admin4_grid_id, lg.admin5_grid_id
                        FROM $wpdb->postmeta as pm
                        JOIN $wpdb->posts as p ON p.ID=pm.post_id AND p.post_type = 'groups'
                        JOIN $wpdb->postmeta as pm2 ON pm2.post_id=pm.post_id AND pm2.meta_key = 'group_type' AND pm2.meta_value = 'church'
                        LEFT JOIN $wpdb->dt_location_grid as lg ON pm.meta_value=lg.grid_id
                        WHERE pm.meta_key = 'location_grid'
                    ) as t3
                    GROUP BY t3.admin3_grid_id

                    ", ARRAY_A );
                break;
            case 'world':
                $results = $wpdb->get_results( "
                    SELECT 1 as grid_id, count('World') as count
                    FROM (
                             SELECT 'World'
                             FROM $wpdb->postmeta as pm
                                      JOIN $wpdb->posts as p ON p.ID=pm.post_id AND p.post_type = 'groups'
                                      JOIN $wpdb->postmeta as pm2 ON pm2.post_id=pm.post_id AND pm2.meta_key = 'group_type' AND pm2.meta_value = 'church'
                                      LEFT JOIN $wpdb->dt_location_grid as lg ON pm.meta_value=lg.grid_id
                             WHERE pm.meta_key = 'location_grid'
                         ) as tw
                    GROUP BY 'World'
                    ", ARRAY_A );
                break;
            case 'full': // full query including world
                $results = $wpdb->get_results( "
                    SELECT t0.admin0_grid_id as grid_id, count(t0.admin0_grid_id) as count
                    FROM (
                     SELECT lg.admin0_grid_id, lg.admin1_grid_id, lg.admin2_grid_id, lg.admin3_grid_id, lg.admin4_grid_id, lg.admin5_grid_id
                        FROM $wpdb->postmeta as pm
                        JOIN $wpdb->posts as p ON p.ID=pm.post_id AND p.post_type = 'groups'
                        JOIN $wpdb->postmeta as pm2 ON pm2.post_id=pm.post_id AND pm2.meta_key = 'group_type' AND pm2.meta_value = 'church'
                        LEFT JOIN $wpdb->dt_location_grid as lg ON pm.meta_value=lg.grid_id
                        WHERE pm.meta_key = 'location_grid'
                    ) as t0
                    GROUP BY t0.admin0_grid_id
                    UNION ALL
                    SELECT t1.admin1_grid_id as grid_id, count(t1.admin1_grid_id) as count
                    FROM (
                        SELECT lg.admin0_grid_id, lg.admin1_grid_id, lg.admin2_grid_id, lg.admin3_grid_id, lg.admin4_grid_id, lg.admin5_grid_id
                        FROM $wpdb->postmeta as pm
                        JOIN $wpdb->posts as p ON p.ID=pm.post_id AND p.post_type = 'groups'
                        JOIN $wpdb->postmeta as pm2 ON pm2.post_id=pm.post_id AND pm2.meta_key = 'group_type' AND pm2.meta_value = 'church'
                        LEFT JOIN $wpdb->dt_location_grid as lg ON pm.meta_value=lg.grid_id
                        WHERE pm.meta_key = 'location_grid'
                    ) as t1
                    GROUP BY t1.admin1_grid_id
                    UNION ALL
                    SELECT t2.admin2_grid_id as grid_id, count(t2.admin2_grid_id) as count
                    FROM (
                        SELECT lg.admin0_grid_id, lg.admin1_grid_id, lg.admin2_grid_id, lg.admin3_grid_id, lg.admin4_grid_id, lg.admin5_grid_id
                        FROM $wpdb->postmeta as pm
                        JOIN $wpdb->posts as p ON p.ID=pm.post_id AND p.post_type = 'groups'
                        JOIN $wpdb->postmeta as pm2 ON pm2.post_id=pm.post_id AND pm2.meta_key = 'group_type' AND pm2.meta_value = 'church'
                        LEFT JOIN $wpdb->dt_location_grid as lg ON pm.meta_value=lg.grid_id
                        WHERE pm.meta_key = 'location_grid'
                    ) as t2
                    GROUP BY t2.admin2_grid_id
                    UNION ALL
                    SELECT t3.admin3_grid_id as grid_id, count(t3.admin3_grid_id) as count
                    FROM (
                        SELECT lg.admin0_grid_id, lg.admin1_grid_id, lg.admin2_grid_id, lg.admin3_grid_id, lg.admin4_grid_id, lg.admin5_grid_id
                        FROM $wpdb->postmeta as pm
                        JOIN $wpdb->posts as p ON p.ID=pm.post_id AND p.post_type = 'groups'
                        JOIN $wpdb->postmeta as pm2 ON pm2.post_id=pm.post_id AND pm2.meta_key = 'group_type' AND pm2.meta_value = 'church'
                        LEFT JOIN $wpdb->dt_location_grid as lg ON pm.meta_value=lg.grid_id
                        WHERE pm.meta_key = 'location_grid'
                    ) as t3
                    GROUP BY t3.admin3_grid_id
                    UNION ALL
                    SELECT 1 as grid_id, count('World') as count
                    FROM (
                             SELECT 'World'
                             FROM $wpdb->postmeta as pm
                                      JOIN $wpdb->posts as p ON p.ID=pm.post_id AND p.post_type = 'groups'
                                      JOIN $wpdb->postmeta as pm2 ON pm2.post_id=pm.post_id AND pm2.meta_key = 'group_type' AND pm2.meta_value = 'church'
                                      LEFT JOIN $wpdb->dt_location_grid as lg ON pm.meta_value=lg.grid_id
                             WHERE pm.meta_key = 'location_grid'
                         ) as tw
                    GROUP BY 'World'
                    ", ARRAY_A );
                break;
            default:
                $results = $wpdb->get_results( "
                        SELECT t0.admin0_grid_id as grid_id, count(t0.admin0_grid_id) as count
                        FROM (
                         SELECT lg.admin0_grid_id, lg.admin1_grid_id, lg.admin2_grid_id, lg.admin3_grid_id, lg.admin4_grid_id, lg.admin5_grid_id
                            FROM $wpdb->postmeta as pm
                            JOIN $wpdb->posts as p ON p.ID=pm.post_id AND p.post_type = 'groups'
                            JOIN $wpdb->postmeta as pm2 ON pm2.post_id=pm.post_id AND pm2.meta_key = 'group_type' AND pm2.meta_value = 'church'
                            LEFT JOIN $wpdb->dt_location_grid as lg ON pm.meta_value=lg.grid_id
                            WHERE pm.meta_key = 'location_grid'
                        ) as t0
                        GROUP BY t0.admin0_grid_id
                        UNION ALL
                        SELECT t1.admin1_grid_id as grid_id, count(t1.admin1_grid_id) as count
                        FROM (
                            SELECT lg.admin0_grid_id, lg.admin1_grid_id, lg.admin2_grid_id, lg.admin3_grid_id, lg.admin4_grid_id, lg.admin5_grid_id
                            FROM $wpdb->postmeta as pm
                            JOIN $wpdb->posts as p ON p.ID=pm.post_id AND p.post_type = 'groups'
                            JOIN $wpdb->postmeta as pm2 ON pm2.post_id=pm.post_id AND pm2.meta_key = 'group_type' AND pm2.meta_value = 'church'
                            LEFT JOIN $wpdb->dt_location_grid as lg ON pm.meta_value=lg.grid_id
                            WHERE pm.meta_key = 'location_grid'
                        ) as t1
                        GROUP BY t1.admin1_grid_id
                        UNION ALL
                        SELECT t2.admin2_grid_id as grid_id, count(t2.admin2_grid_id) as count
                        FROM (
                            SELECT lg.admin0_grid_id, lg.admin1_grid_id, lg.admin2_grid_id, lg.admin3_grid_id, lg.admin4_grid_id, lg.admin5_grid_id
                            FROM $wpdb->postmeta as pm
                            JOIN $wpdb->posts as p ON p.ID=pm.post_id AND p.post_type = 'groups'
                            JOIN $wpdb->postmeta as pm2 ON pm2.post_id=pm.post_id AND pm2.meta_key = 'group_type' AND pm2.meta_value = 'church'
                            LEFT JOIN $wpdb->dt_location_grid as lg ON pm.meta_value=lg.grid_id
                            WHERE pm.meta_key = 'location_grid'
                        ) as t2
                        GROUP BY t2.admin2_grid_id
                        UNION ALL
                        SELECT t3.admin3_grid_id as grid_id, count(t3.admin3_grid_id) as count
                        FROM (
                            SELECT lg.admin0_grid_id, lg.admin1_grid_id, lg.admin2_grid_id, lg.admin3_grid_id, lg.admin4_grid_id, lg.admin5_grid_id
                            FROM $wpdb->postmeta as pm
                            JOIN $wpdb->posts as p ON p.ID=pm.post_id AND p.post_type = 'groups'
                            JOIN $wpdb->postmeta as pm2 ON pm2.post_id=pm.post_id AND pm2.meta_key = 'group_type' AND pm2.meta_value = 'church'
                            LEFT JOIN $wpdb->dt_location_grid as lg ON pm.meta_value=lg.grid_id
                            WHERE pm.meta_key = 'location_grid'
                        ) as t3
                        GROUP BY t3.admin3_grid_id
                        ", ARRAY_A );
                break;
        }

        $list = [];
        if ( is_array( $results ) ) {
            foreach ( $results as $result ) {
                if ( empty( $result['grid_id'] ) ) {
                    continue;
                }
                if ( empty( $result['count'] ) ) {
                    continue;
                }
                $list[$result['grid_id']] = $result['count'];
            }
        }

        return $list;
    }

}
Prayer_Global_Porch_Map_App::instance();
