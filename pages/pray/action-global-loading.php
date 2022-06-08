<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.


/**
 * Class Prayer_Global_Prayer_App
 */
class PG_Global_Prayer_App_Loading extends PG_Global_Prayer_App {

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
        if ( 'loading' !== $this->parts['action'] ) {
            return;
        }

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
    }

    public function footer_javascript(){
        require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . 'assets/footer.php' );
    }

    public function body(){
        $parts = $this->parts;
        $lap_stats = pg_global_stats_by_key( $parts['public_key'] );

        global $wpdb;
        if ( empty( $lap_stats['end_time'] ) ) {
            $lap_stats['end_time'] = time();
        }
        $participant_locations = $wpdb->get_results( $wpdb->prepare( "
           SELECT r.label as location, COUNT(r.label) as count
           FROM wp_dt_reports r
            WHERE r.post_type = 'laps'
                AND r.type = 'prayer_app'
            AND r.timestamp >= %d AND r.timestamp <= %d
			AND r.label IS NOT NULL
            GROUP BY r.label
			ORDER BY count DESC
			LIMIT 10
        ", $lap_stats['start_time'], $lap_stats['end_time'] ), ARRAY_A );

        ?>

        <section class=""  id="">
            <div class="container mt-4 text-center">
                <div class="row justify-content-center" style="border: 1px solid grey;padding:1.5em;">
                    <div class="col-6">
                        <h2 class="mb-4">RACE TIP</h2>
                        <p class="h5" id="tip"></p>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-6 text-center mt-4" id="timer">
                        <span class="loading-spinner active"></span><br>
                        Finding the next open prayer location...
                    </div>
                </div>


            </div>
        </section>
        <!-- END section -->

        <script>
            jQuery(document).ready(function(){
                // redirect timer
                let tips = [
                    `Always click "Yes & Done" or "Yes & Next" to record your prayer.`,
                    `Set your prayer times longer with the settings icon`,
                    `Get more prayer fuel with the button at the bottom.`
                ]
                jQuery('#tip').html(tips[Math.floor(Math.random() * 3)])
                setTimeout(function() {
                    jQuery('#timer').html('Ready!')
                }, 4000);
                setTimeout(function() {
                    window.location.href = 'https://prayer.global/<?php echo $parts['root'] . '/' . $parts['type'] . '/' . $parts['public_key'] ?>'
                }, 5000);
            })
        </script>

        <?php // end html
    }

}
PG_Global_Prayer_App_Loading::instance();
