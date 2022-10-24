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
        ?>
        <script src="<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ) ?>assets/js/clipboard.min.js?ver=<?php echo esc_attr( fileatime( trailingslashit( plugin_dir_path( __DIR__ ) ) . 'assets/js/clipboard.min.js' ) ) ?>"></script>
        <?php
    }

    public function footer_javascript(){
        require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . 'assets/footer.php' );
    }

    public function body(){
        $parts = $this->parts;
        $lap_stats = pg_custom_lap_stats_by_post_id( $parts['post_id'] );
        require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . '/assets/nav.php' );
        ?>
        <style>
            section {
                margin-top: 110px;
            }
        </style>
        <section>
            <div class="container pb-4">
                <div class="row">
                    <div class="col-md text-center">
                        <span class="two-em lap-title"><?php echo esc_html( $lap_stats['title'] ) ?> Challenge Tools</span>
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
                        <h2><?php echo esc_html( $lap_stats['title'] ) ?> Challenge Map</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col center">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=1000x1000&amp;data=<?php echo esc_url( get_site_url() ) ?>/prayer_app/custom/<?php echo esc_html( $lap_stats['key'] ) ?>/map" style="width: 100%;max-width:400px;"><br><br>
                    </div>
                </div>
                <div class="input-group">
                    <input type="text" class="form-control copy-input"
                           value="<?php echo esc_url( get_site_url() ) ?>/prayer_app/custom/<?php echo esc_html( $lap_stats['key'] ) ?>/map" placeholder="Some path" id="copy-input">
                    <span class="input-group-btn">
                      <button class="btn btn-default copy-button" type="button" id="copy-button"
                              data-toggle="tooltip" data-placement="button"
                              title="Copy to Clipboard" data-clipboard-action="copy" data-clipboard-target="#copy-input">
                        Copy
                      </button>
                    </span>
                </div>


                <div class="row ">
                    <div class="col center">
                        <hr>
                    </div>
                </div>
                <div class="row ">
                    <div class="col center p-3">
                        <h2>Display Map (60 Second Refresh)</h2>
                    </div>
                </div>
                <div class="input-group">
                    <input type="text" class="form-control copy-display"
                           value="<?php echo esc_url( get_site_url() ) ?>/prayer_app/custom/<?php echo esc_html( $lap_stats['key'] ) ?>/display" placeholder="Some path" id="copy-display-input">
                    <span class="input-group-btn">
                      <button class="btn btn-default copy-button" type="button" id="copy-button-display"
                              data-toggle="tooltip" data-placement="button"
                              title="Copy to Clipboard" data-clipboard-action="copy" data-clipboard-target="#copy-display-input">
                        Copy
                      </button>
                    </span>
                </div>


                <div class="row ">
                    <div class="col center">
                        <hr>
                    </div>
                </div>
                <div class="row ">
                    <div class="col center p-3">
                        <h2>App Stores: Prayer.Global App</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col center">
                        <img src="<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) . 'assets/images/prayer.global.app.png' ) ?>" style="width: 100%;max-width:400px;"><br><br>
                        <div class="input-group">
                            <input type="text" class="form-control copy-input"
                                   value="<?php echo esc_url( get_site_url() ) ?>/qr/app" placeholder="Some path" id="copy-input-qrapp">
                            <span class="input-group-btn">
                          <button class="btn btn-default copy-button" type="button" id="copy-button-qrapp"
                                  data-toggle="tooltip" data-placement="button"
                                  title="Copy to Clipboard" data-clipboard-action="copy" data-clipboard-target="#copy-input-qrapp">
                            Copy
                          </button>
                        </span>
                        </div>
                    </div>
                </div>

            </div>

        </section>
        <div style="height:300px;"></div>
        <script>
            jQuery(document).ready(function() {
                var clipboard = new ClipboardJS('#copy-button');
                clipboard.on('success', function(e) {
                    console.info('Action:', e.action);
                    console.info('Text:', e.text);
                    console.info('Trigger:', e.trigger);
                    jQuery('#copy-button').text('Copied')
                    e.clearSelection();
                });


                var clipboard_qrapp = new ClipboardJS('#copy-button-qrapp');
                clipboard_qrapp.on('success', function(e) {
                    console.info('Action:', e.action);
                    console.info('Text:', e.text);
                    console.info('Trigger:', e.trigger);
                    jQuery('#copy-button-qrapp').text('Copied')
                    e.clearSelection();
                });



                var clipboard_display = new ClipboardJS('#copy-button-display');
                clipboard_display.on('success', function(e) {
                    console.info('Action:', e.action);
                    console.info('Text:', e.text);
                    console.info('Trigger:', e.trigger);
                    jQuery('#copy-button-display').text('Copied')
                    e.clearSelection();
                });


            });
        </script>
        <!-- END section -->
        <?php require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . '/assets/working-footer.php' ) ?>
        <?php // end html
    }

}
PG_Custom_Prayer_App_Tools::instance();
