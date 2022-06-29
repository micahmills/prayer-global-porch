<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.

class Prayer_Global_Porch_Media extends DT_Magic_Url_Base
{
    public $magic = false;
    public $parts = false;
    public $page_title = 'Global Prayer - Media';
    public $root = 'download_app';
    public $type = 'media';
    public $type_name = 'Global Prayer - Media';
    public static $token = 'download_app';
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
        <link href="https://fonts.googleapis.com/css?family=Crimson+Text:400,400i,600|Montserrat:200,300,400" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ) ?>assets/fonts/ionicons/css/ionicons.min.css">
        <link rel="stylesheet" href="<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ) ?>assets/basic.css?ver=<?php echo esc_attr( fileatime( trailingslashit( plugin_dir_path( __DIR__ ) ) . 'assets/basic.css' ) ) ?>" type="text/css" media="all">
        <link rel="stylesheet" href="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) ) ?>heatmap.css?ver=<?php echo esc_attr( fileatime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'heatmap.css' ) ) ?>" type="text/css" media="all">
        <?php
    }

    public function footer_javascript(){
        require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . 'assets/footer.php' );
    }

    public function body(){
        require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . '/assets/nav.php' ) ?>

        <section class="pb_section" style="height: 95vh;">
            <div class="container">
                <div class="row justify-content-md-center text-center mb-5">
                    <div class="col-lg-7">
                        <h2 class="mt-0 heading-border-top font-weight-normal">Media & Promotion</h2>
                        <p>
                        </p>
                    </div>
                </div>
                <div class="row justify-content-md-center text-center mb-5">
                    <div class="col-12">
                        <h2>Social Promotion</h2>
                    </div>
                    <div class="col-lg-3 p-3">
                        <p>
                            <img src="https://place-hold.it/300" class="img-thumbnail" />
                            <br><a href="#">Download</a>
                        </p>
                    </div>
                    <div class="col-lg-3 p-3">
                        <p>
                            <img src="https://place-hold.it/300" class="img-thumbnail" />
                            <br><a href="#">Download</a>
                        </p>
                    </div>
                    <div class="col-lg-3 p-3">
                        <p>
                            <img src="https://place-hold.it/300" class="img-thumbnail" />
                            <br><a href="#">Download</a>
                        </p>
                    </div>
                    <div class="col-lg-3 p-3">
                        <p>
                            <img src="https://place-hold.it/300" class="img-thumbnail" />
                            <br><a href="#">Download</a>
                        </p>
                    </div>
                </div>
                <div class="row justify-content-md-center text-center mb-5">
                    <div class="col-12">
                        <h2>Slides</h2>
                    </div>
                    <div class="col-lg-3 p-3">
                        <p>
                            <img src="https://place-hold.it/300" class="img-thumbnail" />
                            <br><a href="#">Download</a>
                        </p>
                    </div>
                    <div class="col-lg-3 p-3">
                        <p>
                            <img src="https://place-hold.it/300" class="img-thumbnail" />
                            <br><a href="#">Download</a>
                        </p>
                    </div>
                    <div class="col-lg-3 p-3">
                        <p>
                            <img src="https://place-hold.it/300" class="img-thumbnail" />
                            <br><a href="#">Download</a>
                        </p>
                    </div>
                    <div class="col-lg-3 p-3">
                        <p>
                            <img src="https://place-hold.it/300" class="img-thumbnail" />
                            <br><a href="#">Download</a>
                        </p>
                    </div>
                </div>
                <div class="row justify-content-md-center text-center mb-5">
                    <div class="col-12">
                        <h2>Handouts</h2>
                    </div>
                    <div class="col-lg-3 p-3">
                        <p>
                            <img src="https://place-hold.it/300" class="img-thumbnail" />
                            <br><a href="#">Download</a>
                        </p>
                    </div>
                    <div class="col-lg-3 p-3">
                        <p>
                            <img src="https://place-hold.it/300" class="img-thumbnail" />
                            <br><a href="#">Download</a>
                        </p>
                    </div>
                    <div class="col-lg-3 p-3">
                        <p>
                            <img src="https://place-hold.it/300" class="img-thumbnail" />
                            <br><a href="#">Download</a>
                        </p>
                    </div>
                    <div class="col-lg-3 p-3">
                        <p>
                            <img src="https://place-hold.it/300" class="img-thumbnail" />
                            <br><a href="#">Download</a>
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
Prayer_Global_Porch_Media::instance();
