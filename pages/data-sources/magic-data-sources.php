<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.

class Prayer_Global_Porch_Data_Source extends DT_Magic_Url_Base
{
    public $magic = false;
    public $parts = false;
    public $page_title = 'Global Prayer - Data Sources';
    public $root = 'content_app';
    public $type = 'data_sources';
    public $type_name = 'Global Prayer - Data Sources';
    public static $token = 'content_app_data_sources';
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
                        <h2 class="mt-0 heading-border-top font-weight-normal">Data Sources</h2>
                    </div>
                </div>
                <div class="grid-x grid-margin-x grid-padding-y">

                    <!-- Item -->
                    <div class="cell">
                        <hr>
                        <p>
                            We acknowledge that there is no way to possess 100% accurate knowledge of the faith status or location status of
                            every person in the world.
                        </p>
                        <p>
                            No government has this exact number, no business, ... nobody but God has the facts of a person's true faith or whereabouts. Therefore, every demographic fact
                            is a mathematical deduction. (Sorry friends who like exact numbers.)
                        </p>
                        <p>
                            But leveraging the best data sources we can use, we have created a prayer tool to offer informative prayer guidance combined with a more radically specific location breakdown of the world.
                        </p>
                    </div>

                    <!-- Item -->
                    <div class="cell">
                        <hr>
                        <h3 class="secondary">Location Grid - Boundaries</h3>
                        <ul>
                            <li>Location Grid Project - Disciple Tools</li>
                            <li>GADM Data</li>
                            <li>GeoNames</li>
                        </ul>
                    </div>

                    <!-- Item -->
                    <div class="cell">
                        <hr>
                        <h3 class="secondary">Location Grid - Population Data</h3>
                        <ul>
                            <li></li>
                        </ul>
                    </div>

                    <!-- Item -->
                    <div class="cell">
                        <hr>
                        <h3 class="secondary">Joshua Project - People Groups</h3>
                        <ul>
                            <li>Joshua Project</li>
                        </ul>
                    </div>

                    <!-- Item -->
                    <div class="cell">
                        <hr>
                        <h3 class="secondary">Joshua Project - Faith Status</h3>
                        <ul>
                            <li></li>
                        </ul>
                    </div>

                </div>
            </div>
        </section>
        <!-- END section -->

        <?php require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . '/assets/working-footer.php' ) ?>
        <?php
    }

}
Prayer_Global_Porch_Data_Source::instance();
