<?php

class PG_DT_Dashboard {

    private static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    public function __construct() {
        $this->namespace = $this->context . "/v" . intval( $this->version );
        add_filter( 'dt_front_page', [ $this, 'front_page' ] );

        add_filter( 'desktop_navbar_menu_options', [ $this, 'nav_menu' ], 10, 1 );
        add_filter( 'off_canvas_menu_options', [ $this, 'nav_menu' ] );

        $url_path = dt_get_url_path();
        add_action( "template_redirect", [ $this, 'redirect' ] );
        if ( strpos( $url_path, 'porch_dashboard' ) !== false ) {
            add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ] );
        }
    }

    public function redirect() {
        $url = dt_get_url_path();
        if ( strpos( $url, "porch_dashboard" ) !== false ) {
            $plugin_dir = dirname( __FILE__ );
            $path = $plugin_dir . '/template-metrics-wide.php'; // @todo change this template
            status_header( 200 );
            include( $path );
            die();
        }
    }
    
    public function scripts() {
      
    }
    
    public function front_page( $page ) {
        ?>
        <iframe src="/" style="width: 100%; height: 90vh;" ></iframe>
        <?php
    }

    public function nav_menu( $tabs ) {
        $tabs['porch_dashboard'] = [
            "link"  => site_url( '/porch_dashboard/' ),
            "label" => __( "Porch", "prayer-global" )
        ];
        return $tabs;
    }
}
PG_DT_Dashboard::instance();
