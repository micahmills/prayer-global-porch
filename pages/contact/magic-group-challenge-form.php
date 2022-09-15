<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.

class Prayer_Global_Porch_Group_Challenge extends DT_Magic_Url_Base
{
    public $magic = false;
    public $parts = false;
    public $page_title = 'Global Prayer - Group Challenge Request';
    public $root = 'prayer_app';
    public $type = 'group_challenge_request';
    public $type_name = 'Global Prayer - Group Challenge';
    public static $token = 'prayer_app';
    public $post_type = 'contacts';

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
        require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . '/assets/nav.php' ) ?>

        <section class="pb_section" data-section="about" id="section-about">
            <div class="container">
                <div class="row justify-content-md-center text-center mb-5">
                    <div class="col-lg-7">
                        <h2 class="mt-0 pb-3 heading-border-top font-weight-normal">Request a Group Challenge</h2>
                        <p>
                            First, thank you for reaching out.
                        </p>
                        <p>
                            Second, we are willing to create free "group challenge maps" for communities that want to rally and measure a world prayer lap effort. This map allows your community a dedicated prayer app and map. All your prayers as a group contribute to the global lap as well.
                        </p>
                    </div>
                </div>
                <div class="row justify-content-md-center text-center mb-5">
                    <div class="col-lg-7">
                        <p>
                            <iframe src="https://prayer.global/wp-content/plugins/disciple-tools-webform/public/form.php?token=50b7512d977ec6e2e0e3ace650592c9a" style="width:100%;height:800px;" frameborder="0"></iframe>
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <!-- END section -->
        <?php require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . '/assets/working-footer.php' ) ?>
        <?php
    }
}
Prayer_Global_Porch_Group_Challenge::instance();
