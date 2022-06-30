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
        <?php
    }

    public function footer_javascript(){
        require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . 'assets/footer.php' );
    }

    public function body(){
        require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . '/assets/nav.php' ) ?>

        <section class="pb_section" >
            <div class="container">
                <div class="row justify-content-md-center text-center mb-5">
                    <div class="col-lg-7">
                        <h2 class="mt-0 heading-border-top font-weight-normal">Media & Promotion</h2>
                        <p>
                        </p>
                    </div>
                </div>
                <div class="row justify-content-md-center text-center mb-5">
                    <div class="col-12 mb-3">
                       <hr>
                    </div>
                    <div class="col-12">
                        <h2>Moravian Challenge Video</h2>
                    </div>
                    <div class="col-lg-3 p-3">
                        Video<br>
                        <div style="padding:56.3% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video/715752828?h=d39d43cea8&amp;badge=0&amp;autopause=1&amp;player_id=0&amp;app_id=58479" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;" title="moravian-prayer-challenge"></iframe></div><script src="https://player.vimeo.com/api/player.js"></script>
                    </div>
                    <div class="col-lg-3 p-3">
                        <p>
                            Download Video
                            <br><a href="https://player.vimeo.com/progressive_redirect/download/715752828/container/44c88492-73ba-4789-9e48-c88316194653/c2189175/moravian-prayer-challenge%20%28360p%29.mp4?expires=1656520543&loc=external&signature=35931e268af8c01f4c82c771b47d2b3393fca9f000dc62ff89afe0f6bb6ac1a5">Low Quality (360p - 10mg)</a>
                            <br><a href="https://player.vimeo.com/progressive_redirect/download/715752828/container/44c88492-73ba-4789-9e48-c88316194653/19ce9b8c-bc019d05/moravian-prayer-challenge%20%281080p%29.mp4?expires=1656520543&loc=external&signature=5ee3781d3107607bbb25b556a0c87cacfa4126412d3c2e9412b2db2f9366c18d">HD Download (1080p - 31mg)</a>
                            <br><a href="https://player.vimeo.com/progressive_redirect/download/715752828/container/44c88492-73ba-4789-9e48-c88316194653/538db78d/moravian-prayer-challenge%20%282160p%29.mp4?expires=1656520543&loc=external&signature=2e0906096aec000bd78276f5bbde85f9e9c9ad7d627c4c92bfa14e58335faea9">4k Download (2160p - 74mg)</a>
                        </p>
                    </div>
                    <div class="col-lg-3 p-3">
                        <p>
                            Embed Code<br>
                            <textarea style="font-size: .6em; height: 150px; width: 100%;" readonly><div style="padding:56.3% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video/715752828?h=d39d43cea8&amp;badge=0&amp;autopause=1&amp;player_id=0&amp;app_id=58479" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;" title="moravian-prayer-challenge"></iframe></div><script src="https://player.vimeo.com/api/player.js"></script></textarea>
                        </p>
                    </div>
                </div>
                <div class="row justify-content-md-center text-center mb-5">
                    <div class="col-12 mb-3">
                        <hr>
                    </div>
                    <div class="col-12">
                        <h2>Social Promotion</h2>
                    </div>
                    <div class="col-lg-6 p-3">
                        <p>
                            <img src="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) )  ?>firestarters-wanted.jpg" class="img-thumbnail" />
                            <br><a href="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) )  ?>firestarters-wanted.jpg">Download</a>
                        </p>
                    </div>
                    <div class="col-lg-6 p-3">
                        <p>
                            <img src="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) )  ?>plain-prayer-global.jpg" class="img-thumbnail" />
                            <br><a href="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) )  ?>plain-prayer-global.jpg">Download</a>
                        </p>
                    </div>
                    <div class="col-lg-3 p-3">
                        <p>
                            <img src="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) )  ?>walking-girl.jpg" class="img-thumbnail" />
                            <br><a href="<?php echo esc_url( trailingslashit( plugin_dir_url( __FILE__ ) ) )  ?>walking-girl.jpg">Download</a>
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
