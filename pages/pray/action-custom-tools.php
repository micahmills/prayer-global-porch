<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.


/**
 * Class PG_Custom_Prayer_App_Stats
 */
class PG_Custom_Prayer_App_Tools extends PG_Custom_Prayer_App {

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
        if ( 'tools' !== $this->parts['action'] ) {
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
        $lap_stats = pg_custom_lap_stats_by_post_id( $parts['post_id'] );
        dt_write_log($lap_stats);
        require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . '/assets/nav.php' );
        ?>
        <style>
            section {
                margin-top: 110px;
            }
        </style>
        <section style="height: 95vh;">
            <div class="container pb-4">
                <div class="row">
                    <div class="col-md text-center">
                        <span class="two-em lap-title"><?php echo esc_html( $lap_stats['title']) ?> Challenge Tools</span>
                    </div>
                </div>
            </div>
            <div class="container" id="content">
                <div class="row ">
                    <div class="col center">
                        <hr>
                    </div>
                </div>
                <div class="row ">
                    <div class="col center p-3">
                        <h2>Prayer.Global Mobile App</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 center">

                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=400x400&amp;data=https://apps.apple.com/us/app/prayer-global/id1636889534?uo=4" style="width: 100%;max-width:400px;"><br>Apple App Store
                    </div>
                    <div class="col-6 center">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=400x400&amp;data=https://play.google.com/store/apps/details?id=app.global.prayer" style="width: 100%;max-width:400px;"><br>Android Play Store
                    </div>
                </div>
                <div class="row ">
                    <div class="col center">
                        <hr>
                    </div>
                </div>
                <div class="row ">
                    <div class="col center p-3">
                        <h2>Restoration Challenge Map</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col center">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=400x400&amp;data=https://prayer.global/prayer_app/custom/<?php echo esc_html( $lap_stats['key'] ) ?>/map" style="width: 100%;max-width:400px;">
                    </div>
                </div>
            </div>

        </section>
        <!-- END section -->
        <?php require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . '/assets/working-footer.php' ) ?>
        <?php // end html
    }

}
PG_Custom_Prayer_App_Tools::instance();
