<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.

/**
 * Class Prayer_Global_Porch_Public_Porch_Profile
 */
class Prayer_Global_Porch_User_Page extends DT_Magic_Url_Base {

    public $page_title = 'Private User Page';
    public $root = "user_app";
    public $type = 'private';
    public $post_type = 'user';

    private static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    } // End instance()

    public function __construct() {
        parent::__construct();

        add_action( 'rest_api_init', [ $this, 'add_endpoints' ] );

        /**
         * tests if other URL
         */
        $url = dt_get_url_path();
        if ( strpos( $url, $this->root . '/' . $this->type ) === false ) {
            return;
        }
        /**
         * tests magic link parts are registered and have valid elements
         */
        if ( !$this->check_parts_match( false ) ){
            return;
        }

        // require login access
//        if ( ! is_user_logged_in() ) {
//            wp_safe_redirect( dt_custom_login_url( 'login' ) );
//        }


        // load if valid url
        add_action( 'dt_blank_body', [ $this, 'body' ] ); // body for no post key

        add_filter( 'dt_magic_url_base_allowed_css', [ $this, 'dt_magic_url_base_allowed_css' ], 10, 1 );
        add_filter( 'dt_magic_url_base_allowed_js', [ $this, 'dt_magic_url_base_allowed_js' ], 10, 1 );
        add_action( 'wp_enqueue_scripts', [ $this, 'wp_enqueue_scripts' ], 99 );
    }

    public function dt_magic_url_base_allowed_css( $allowed_css ) {
        return [
            'porch-user-style-css',
            'jquery-ui-site-css',
            'foundations-css',
        ];
    }

    public function dt_magic_url_base_allowed_js( $allowed_js ) {
        return [
            'jquery',
            'jquery-ui',
            'foundations-js',
            'porch-user-site-js',
        ];
    }

    public function wp_enqueue_scripts() {

        // styles
//        wp_enqueue_style( 'foundations-css', 'https://cdn.jsdelivr.net/npm/foundation-sites@6.6.3/dist/css/foundation.min.css', array(), '6.6.3', 'all' );
//        add_filter( 'style_loader_tag', function( $html, $handle ) {
//            if ( 'foundations-css' === $handle ) {
//                return str_replace( "media='all'", "media='all' integrity='sha256-ogmFxjqiTMnZhxCqVmcqTvjfe1Y/ec4WaRj/aQPvn+I=' crossorigin='anonymous'", $html );
//            }
//            return $html;
//        }, 10, 2 );
//        wp_enqueue_style( 'porch-user-style-css', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'css/style.css', array( 'foundations-css' ), filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'css/style.css' ), 'all' );
//
//        // javascript
//        wp_register_script( 'foundations-js', 'https://cdn.jsdelivr.net/npm/foundation-sites@6.6.3/dist/js/foundation.min.js', [ 'jquery' ], '6.6.3' );
//        wp_enqueue_script( 'foundations-js' );
//        add_filter( 'style_loader_tag', function( $html, $handle ) {
//            if ( 'foundations-js' === $handle ) {
//                return str_replace( "media='all'", "media='all' integrity='sha256-pRF3zifJRA9jXGv++b06qwtSqX1byFQOLjqa2PTEb2o=' crossorigin='anonymous'", $html );
//            }
//            return $html;
//        }, 10, 2 );

//        wp_enqueue_script( 'porch-user-site-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/site.js', [ 'jquery' ], filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'js/site.js' ) );
    }

    public function header_javascript(){
        require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . 'assets/header.php' );
        ?>
        <script>
            let jsObject = [<?php echo json_encode([
                'map_key' => DT_Mapbox_API::get_key(),
                'root' => esc_url_raw( rest_url() ),
                'nonce' => wp_create_nonce( 'wp_rest' ),
                'parts' => $this->parts,
                'user' => wp_get_current_user(),
                'translations' => [
                    'add' => __( 'Add Magic', 'disciple-tools-porch-template' ),
                ],
            ]) ?>][0]

            window.user_status = <?php echo ( is_user_logged_in() ) ? 1 : 0; ?>

            jQuery(document).ready(function(){

                if ( window.user_status ) {
                   window.write_profile( jsObject.user.data )
                } else {
                    window.write_login()
                }

            })

            window.get_user_app = (action, data ) => {
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
                        jQuery('#error').html(e)
                    })
            }

            window.send_login = () => {
                let email = jQuery('#pg_input_email').val()
                let pass = jQuery('#pg_input_password').val()
                jQuery('.loading-spinner').addClass('active')

                window.get_user_app('login', { email: email, pass: pass } )
                    .done(function(data){
                        console.log(data)
                        jQuery('.loading-spinner').removeClass('active')
                        if ( data ) {
                            window.write_profile(data)
                        }
                    })
            }

            window.write_profile = (data) => {
                jQuery('#pg_content').html(`
                    <table class="table">
                        <tbody>
                        <tr>
                            <td>User ID</td>
                            <td id="pg_user_id"></td>
                        </tr>
                        <tr>
                            <td>User Display Name</td>
                            <td id="pg_user_display"></td>
                        </tr>
                        <tr>
                            <td>User Email</td>
                            <td id="pg_user_email"></td>
                        </tr>
                        </tbody>
                    </table>

                    <a href="<?php echo esc_url( wp_logout_url( '/' ) ); ?>">Logout</a>
                `)
                jQuery('#pg_user_id').html(data.ID)
                jQuery('#pg_user_display').html(data.display_name)
                jQuery('#pg_user_email').html(data.user_email)

            }
            window.write_login = () => {
                jQuery('#pg_content').html(`
                <form id="login_form">
                            <p>
                                Email<br>
                                <input type="text" id="pg_input_email"  />
                            </p>
                            <p>
                                Password<br>
                                <input type="password" id="pg_input_password" />
                            </p>
                            <p>
                                <button type="button" id="submit_button">Submit</button> <span class="loading-spinner"></span>
                            </p>
                        </form>
                `)
                jQuery('#submit_button').on('click', function(){
                    window.send_login()

                })
            }
        </script>
        <style>
            #login_form input {
                padding:.5em;
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
        <section class="pb_section" data-section="login" id="section-login">
            <div class="container">
                <div class="row justify-content-md-center text-center mb-5">
                    <div class="col-lg-7">
                        <h2 class="mt-0 heading-border-top font-weight-normal" id="pg_title">Login</h2>
                        <p></p>
                    </div>
                </div>
                <div class="row justify-content-md-center text-center mb-5">
                    <div class="col-lg-7" id="pg_content"></div>
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
            $namespace, '/'.$this->type, [
                [
                    'methods'  => "POST",
                    'callback' => [ $this, 'endpoint' ],
                    'permission_callback' => '__return_true',
                ],
            ]
        );
    }

    public function endpoint( WP_REST_Request $request ) {

        $params = $request->get_params();

        if ( ! isset( $params['parts'], $params['action'] ) ) {
            return new WP_Error( __METHOD__, "Missing parameters", [ 'status' => 400 ] );
        }

        $params = dt_recursive_sanitize_array( $params );

        switch ( $params['action'] ) {
            case 'login':
                $user = get_user_by( 'email', $params['data']['email'] );

                if ( $user ) {
                    if ( wp_check_password( $params['data']['pass'], $user->data->user_pass ) ) {
                        // password match
                        $logged_in = $this->programmatic_login( $user->data->user_login );
                        if ( $logged_in ) {
                            return $user;
                        }
                    }
                }
                return false;
            default:
                return $params;
        }


    }

    /**
     * Programmatically logs a user in
     *
     * @param string $username
     * @return bool True if the login was successful; false if it wasn't
     */
    public function programmatic_login( $username ): bool
    {
        if ( is_user_logged_in() ) {
            wp_logout();
        }

        add_filter( 'authenticate', [ $this, 'allow_programmatic_login' ], 10, 3 );    // hook in earlier than other callbacks to short-circuit them
        $user = wp_signon( array( 'user_login' => $username ) );
        remove_filter( 'authenticate', [ $this, 'allow_programmatic_login' ], 10, 3 );

        if ( is_a( $user, 'WP_User' ) ) {
            wp_set_current_user( $user->ID, $user->user_login );

            if ( is_user_logged_in() ) {
                return true;
            }
        }

        return false;
    }

    /**
     * An 'authenticate' filter callback that authenticates the user using only     the username.
     *
     * To avoid potential security vulnerabilities, this should only be used in     the context of a programmatic login,
     * and unhooked immediately after it fires.
     *
     * @param WP_User $user
     * @param string $username
     * @param string $password
     * @return bool|WP_User a WP_User object if the username matched an existing user, or false if it didn't
     */
    public function allow_programmatic_login( $user, $username, $password ) {
        return get_user_by( 'login', $username );
    }

}
Prayer_Global_Porch_User_Page::instance();
