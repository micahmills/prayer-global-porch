<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.

class Prayer_Global_Porch_Home extends DT_Magic_Url_Base
{
    public $magic = false;
    public $parts = false;
    public $page_title = 'Prayer.Global';
    public $root = 'prayer_global';
    public $type = 'porch';
    public static $token = 'prayer_global_porch';

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
        if ( empty( $url ) && ! dt_is_rest() ) {

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
            add_action( 'dt_blank_body', [ $this, 'body' ] );

            add_filter( 'dt_magic_url_base_allowed_css', [ $this, 'dt_magic_url_base_allowed_css' ], 10, 1 );
            add_filter( 'dt_magic_url_base_allowed_js', [ $this, 'dt_magic_url_base_allowed_js' ], 10, 1 );

        }
        else if ( dt_is_rest() ) {
            add_action( 'rest_api_init', [ $this, 'add_endpoints' ] );
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
                'parts' => [
                    'type' => $this->type,
                    'root' => $this->root,
                ],
                'current_lap' => pg_current_global_lap(),
                'translations' => [
                    'add' => __( 'Add Magic', 'prayer-global' ),
                ],
                'image_folder' => plugin_dir_url( __DIR__ ) . 'assets/images/',
            ]) ?>][0]

            console.log(jsObject)

            jQuery(document).ready(function($){

                /* video modal */
                $('#video-link-icon').on('click', function(){
                    let body = $('#demo_video .modal-body')
                    let modal = $('#demo_video')
                    body.html('<iframe style="width:100%;max-width:600px;height:300px;" src="https://player.vimeo.com/video/715752828?h=d39d43cea8&amp;badge=0&amp;autoplay=1&amp;loop=1&amp;autopause=0&amp;player_id=0&amp;app_id=58479" title="Moravian challenge" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>')

                    modal.modal('show')
                    modal.on('hide.bs.modal', function () {
                        body.empty()
                    })
                })

                window.api_post = ( action, data ) => {
                    return jQuery.ajax({
                        type: "POST",
                        data: JSON.stringify({ action: action, parts: jsObject.parts, data: data }),
                        contentType: "application/json; charset=utf-8",
                        dataType: "json",
                        url: jsObject.root + jsObject.parts.root + '/v1/' + jsObject.parts.type,
                        beforeSend: function (xhr) {
                            xhr.setRequestHeader('X-WP-Nonce', jsObject.nonce )
                        }
                    })
                        .fail(function(e) {
                            console.log(e)
                        })
                }

                window.api_post( 'get_stats', {} )
                    .done(function(stats) {
                        console.log(stats)
                        jQuery('#current_time_elapsed').html(stats.current_time_elapsed )
                        jQuery('#current_participants').html(stats.current_participants )
                        jQuery('#current_completed').html(stats.current_completed )
                        jQuery('#current_remaining').html(stats.current_remaining )
                        jQuery('#global_time_elapsed').html(stats.global_time_elapsed )
                        jQuery('#global_participants').html(stats.global_participants )
                        jQuery('#global_minutes_prayed').html(stats.global_minutes_prayed )
                        jQuery('#global_lap_number').html(stats.global_lap_number )
                    })

            })
        </script>
        <?php
    }

    public function footer_javascript(){
        require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . 'assets/footer.php' );
    }

    public function body(){
        require_once( 'body.php' );
    }

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

//        $params = dt_recursive_sanitize_array( $params );

        $current_global_lap = pg_current_global_lap();
        $current_global_stats = pg_global_stats_by_lap_number( $current_global_lap['lap_number'] );
        $global_race = pg_global_race_stats();

        return [
            'current_time_elapsed' => $current_global_stats['time_elapsed'],
            'current_participants' => $current_global_stats['participants'],
            'current_completed' => $current_global_stats['completed'],
            'current_remaining' => $current_global_stats['remaining'],
            'global_time_elapsed' => $global_race['time_elapsed'],
            'global_participants' => $global_race['participants'],
            'global_minutes_prayed' => $global_race['minutes_prayed'],
            'global_lap_number' => $global_race['number_of_laps'],
        ];
    }

}
Prayer_Global_Porch_Home::instance();
