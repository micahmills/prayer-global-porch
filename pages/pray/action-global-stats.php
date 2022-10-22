<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.


/**
 * Class Prayer_Global_Prayer_App
 */
class PG_Global_Prayer_App_Stats extends PG_Global_Prayer_App {

    private static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    } // End instance()

    public function __construct() {
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
        if ( 'stats' !== $this->parts['action'] ) {
            return;
        }

        if ( dt_is_rest() ) {
            add_action( 'rest_api_init', [ $this, 'add_endpoints' ] );
        } else {
            add_action( 'dt_blank_body', [ $this, 'body' ] );
            add_filter( 'dt_magic_url_base_allowed_css', [ $this, 'dt_magic_url_base_allowed_css' ], 10, 1 );
            add_filter( 'dt_magic_url_base_allowed_js', [ $this, 'dt_magic_url_base_allowed_js' ], 10, 1 );
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
        <script src="https://cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js?ver=3"></script>
        <?php
    }

    public function footer_javascript(){
        require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . 'assets/footer.php' );
    }

    public function body(){
        $current_lap = pg_current_global_lap();
        $current_url = trailingslashit( site_url() ) . $this->parts['root'] . '/' . $this->parts['type'] . '/' . $this->parts['public_key'] . '/';

        ?>
        <style>
            .pb_cover_v1.completed-lap .container .row {
                height: 10vh;
                padding-top:10vh;
            }
            .pb_cover_v1 {
                min-height: 100vh;
                height: fit-content;
            }
        </style>

        <?php require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . 'assets/nav.php' );  ?>

        <section class="pb_cover_v1 completed-lap text-left cover-bg-black cover-bg-opacity-4" style="background-image: url(<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ) ?>assets/images/map_background.jpg)" id="section-home">
            <div class="container">
                <div class="row ">
                    <div class="col text-center">
                        <h2 class="heading mb-5">Lap <span class="lap_number"></span></h2>
                        <div class="mb-0 mt-0 participation_thank_you white"></div>
                        <hr style="border:1px solid white;margin-top:5vh;">
                    </div>
                    <div class="w-100"></div>
                    <div class="col-md-6 justify-content-end">
                        <h2 class="heading mb-3">Prayer</h2>
                        <div class="sub-heading pl-4">
                            <p class="mb-0"><span class="minutes_prayed"></span> of Prayer</p>
                            <p class="mb-0"><span class="completed_percent"></span>% of the World Covered in Prayer</p>
                        </div>
                    </div>
                    <div class="col-md-6 justify-content-end">
                        <h2 class="heading mb-3">Pace</h2>
                        <div class="sub-heading pl-4">
                            <p class="mb-0">Start: <span class="start_time_full"></span></p>
                            <p class="mb-0">End: <span class="end_time"></span></p>
                            <p class="mb-0"><span class="time_elapsed"></span></p>
                        </div>
                    </div>
                    <div class="w-100"></div>
                    <div class="col justify-content-end">
                        <h2 class="heading mb-3">Participants</h2>
                    </div>
                    <div class="w-100"></div>

                    <div class="col-md-6">
                        <div class="sub-heading pl-4">
                            <p class="mb-0">Prayer Warriors</p>
                            <p class="mb-0 pl-3"><span class="prayer_warriors"></span></p>
                            <p></p>
                            <p class="mb-2">Top Warrior Locations</p>
                            <ol class="top_locations"></ol>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="sub-heading pl-4"><!--bottom right section--></div>
                    </div>
                </div>
            </div>
        </section>
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
                'map_url' => $current_url . 'map'
            ]) ?>][0]

            window.api_post = ( action, data ) => {
                return jQuery.ajax({
                    type: "POST",
                    data: JSON.stringify({ action: action, parts: jsObject.parts, data: data }),
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    url: jsObject.root + jsObject.parts.root + '/v1/' + jsObject.parts.type + '/stats',
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-WP-Nonce', jsObject.nonce )
                    }
                })
                    .fail(function(e) {
                        console.log(e)
                    })
            }

            jQuery(document).ready(function(){
                let data = {
                    hash: Cookies.get('pg_user_hash')
                }

                window.api_post( 'stats', data )
                    .done( (x) => {
                         console.log( x )
                        jQuery('.lap_number').html(x.lap_stats.lap_number)
                        jQuery('.minutes_prayed').html(x.lap_stats.minutes_prayed_formatted)
                        jQuery('.completed_percent').html(x.lap_stats.completed_percent)
                        jQuery('.start_time_full').html(x.lap_stats.start_time_formatted)

                        if ( x.lap_stats.on_going ) {
                            jQuery('.end_time').html( 'On-going' )
                        } else {
                            jQuery('.end_time').html( x.lap_stats.end_time_formatted )
                        }

                        jQuery('.time_elapsed').html(x.lap_stats.time_elapsed)
                        jQuery('.prayer_warriors').html(x.lap_stats.participants)

                        let html = ''
                        jQuery.each( x.participant_locations, function(i,v) {
                            html += '<li class="mb-0">'+v.location+' ('+v.count+')</li>'
                        })
                        jQuery('.top_locations').html(html)

                        // if you participated in this lap
                        if ( x.user_stats.count ) {
                            jQuery('.participation_thank_you').html(`
                            <h3 class='center white'>Thank you for covering ${x.user_stats.count} locations in prayer for lap ${x.lap_stats.lap_number}!</h3>
                        `)
                        }

            })


            })
        </script>
        <!-- END section -->
<!--        --><?php //require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . '/assets/working-footer.php' ) ?>
        <?php // end html
    }

    public function add_endpoints() {
        $namespace = $this->root . '/v1';
        register_rest_route(
            $namespace,
            '/'.$this->type . '/stats',
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
        global $wpdb;
        $params = $request->get_params();

        if ( ! isset( $params['parts'], $params['action'], $params['data'] ) ) {
            return new WP_Error( __METHOD__, "Missing parameters", [ 'status' => 400 ] );
        }

        $params = dt_recursive_sanitize_array( $params );

        $current_language = 'en';
        $lap_stats = pg_global_stats_by_key( $params['parts']['public_key'] );
        $global_race = pg_global_race_stats();

        // Locations
        $participant_locations = $wpdb->get_results( $wpdb->prepare( "
           SELECT r.label as location, COUNT(r.label) as count
           FROM $wpdb->dt_reports r
            WHERE r.post_type = 'laps'
                AND r.type = 'prayer_app'
            AND r.timestamp >= %d AND r.timestamp <= %d
			AND r.label IS NOT NULL
            GROUP BY r.label
			ORDER BY count DESC
			LIMIT 10
        ", $lap_stats['start_time'], $lap_stats['end_time'] ), ARRAY_A );
        if ( empty( $participant_locations ) ) {
            $participant_locations = [];
        }

        // current user stats
        $user_stats = [
            'list' => [],
            'count' => 0
        ];

        // @todo create a query for logged in users is_user_logged_in()

        // Query based on hash
        $hash = $params['data']['hash'];
        $user_stats['list']  = $wpdb->get_results( $wpdb->prepare( "
               SELECT lgn.full_name
               FROM $wpdb->dt_reports r
               LEFT JOIN $wpdb->location_grid_names lgn ON lgn.grid_id=r.grid_id AND lgn.language_code = %s
                WHERE r.post_type = 'laps'
                    AND r.type = 'prayer_app'
                    AND r.hash = %s
                AND r.timestamp >= %d AND r.timestamp <= %d
                AND r.label IS NOT NULL
            ", $current_language, $hash, $lap_stats['start_time'], $lap_stats['end_time'] ), ARRAY_A );

        if ( ! empty( $user_stats['list'] ) ) {
            $user_stats['count'] = count( $user_stats['list'] );
        }

        return [
            'participant_locations' => $participant_locations,
            'lap_stats' => $lap_stats,
            'race_stats' => $global_race,
            'user_stats' => $user_stats,
        ];
    }

}
PG_Global_Prayer_App_Stats::instance();
