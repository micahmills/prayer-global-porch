<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.

class Prayer_Global_Porch_Stats_Big_List extends DT_Magic_Url_Base
{
    public $magic = false;
    public $parts = false;
    public $page_title = 'Global Prayer Map';
    public $root = 'stats_app';
    public $type = 'big_list';
    public $type_name = 'Global Prayer Stats';
    public static $token = 'stats_app_big_list';
    public $post_type = 'laps';

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
        if ( ( $this->root . '/' . $this->type ) === $url ) {

            $this->magic = new DT_Magic_URL( $this->root );
            $this->parts = $this->magic->parse_url_parts();

            // register url and access
            add_action( "template_redirect", [ $this, 'theme_redirect' ] );
            add_filter( 'dt_blank_access', function (){ return true;
            }, 100, 1 );
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
        }

        if ( dt_is_rest() ) {
            add_action( 'rest_api_init', [ $this, 'add_endpoints' ] );
            add_filter( 'dt_allow_rest_access', [ $this, 'authorize_url' ], 10, 1 );
        }
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
        <script>
            let jsObject = [<?php echo json_encode([
                'map_key' => DT_Mapbox_API::get_key(),
                'mirror_url' => dt_get_location_grid_mirror( true ),
                'ipstack' => DT_Ipstack_API::get_key(),
                'root' => esc_url_raw( rest_url() ),
                'nonce' => wp_create_nonce( 'wp_rest' ),
                'parts' => $this->parts,
                'current_lap' => pg_current_global_lap(),
                'global_race' => pg_global_race_stats(),
                'translations' => [
                    'add' => __( 'Add Magic', 'prayer-global' ),
                ],
                'nope' => plugin_dir_url(__DIR__) . 'assets/images/nope.jpg',
                'images_url' => pg_grid_image_url(),
                'image_folder' => plugin_dir_url(__DIR__) . 'assets/images/',
            ]) ?>][0]
        </script>
        <script src="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) ) ?>big-list.js?ver=<?php echo fileatime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'big-list.js' ) ?>"></script>
        <link rel="stylesheet" href="<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ) ?>assets/basic.css?ver=<?php echo fileatime( trailingslashit( plugin_dir_path( __DIR__ ) ) . 'assets/basic.css' ) ?>" type="text/css" media="all">
        <style>
            section {
                margin-top: 110px;
            }
        </style>
        <?php
    }

    public function footer_javascript(){
        require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . 'assets/footer.php' );
    }

    public function body(){
        require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . '/assets/nav.php' );
        ?>
        <!-- content section -->
        <section>
            <div class="container pb-4">
                <div class="row">
                    <div class="col-md text-center">
                        <span class="two-em lap-title">Big List</span>
                    </div>
                </div>
            </div>
            <div class="container" id="content"><span class="loading-spinner active"></span></div>
            <div class="container center">
                <div class="row">
                    <div class="col center">
                        <a href="/stats_app/big_map/" role="button" class="btn smoothscroll pb_outline-dark btn-xl pb_font-13 p-4 rounded-0 pb_letter-spacing-2">Big Map</a>
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
            case 'get_global_list':
                return $this->get_global_list();
        }

        return false;
    }

    public function get_global_list() {
         global $wpdb;

         $data = [];

         $results = $wpdb->get_results(
             "
                SELECT pm.post_id, p.post_title, pm2.meta_value as lap_number, pm3.meta_value as lap_key, pm4.meta_value as start_time, pm5.meta_value as end_time
                FROM $wpdb->posts p
                JOIN $wpdb->postmeta pm ON pm.post_id=p.ID AND pm.meta_key = 'type' AND pm.meta_value = 'global'
                JOIN $wpdb->postmeta pm2 ON pm2.post_id=p.ID AND pm2.meta_key = 'global_lap_number'
                LEFT JOIN $wpdb->postmeta pm3 ON pm3.post_id=p.ID AND pm3.meta_key = 'prayer_app_global_magic_key'
                LEFT JOIN $wpdb->postmeta pm4 ON pm4.post_id=p.ID AND pm4.meta_key = 'start_time'
                LEFT JOIN $wpdb->postmeta pm5 ON pm5.post_id=p.ID AND pm5.meta_key = 'end_time'
                WHERE p.post_type = 'laps'
                ORDER BY pm2.meta_value DESC
             ", ARRAY_A );

         foreach( $results as $row ) {
             $row['stats'] = pg_global_stats_by_lap_number($row['lap_number']);
             $data[] = $row;
         }

         return $data;
    }

}
Prayer_Global_Porch_Stats_Big_List::instance();
