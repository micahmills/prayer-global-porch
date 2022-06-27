<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.

class PG_Charts_Loader
{
    private static $_instance = null;
    public static function instance(){
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    } // End instance()

    public function __construct(){

        add_filter( 'desktop_navbar_menu_options', [ $this, 'add_navigation_links' ], 99 );

        $url_path = dt_get_url_path( true );
        if ( 'review' === substr( $url_path, 0, 6 ) ) {
            add_filter( 'dt_templates_for_urls', [ $this, 'add_url' ] ); // add custom URL
        }

        require_once( 'charts-abstract.php' );


//        require_once( 'pg-review.php' );
//        new PG_Charts_Review();
    } // End __construct

    public function add_navigation_links( $tabs ) {
        if ( current_user_can( 'access_contacts' ) ) {
            $tabs['review'] = [
                "link" => site_url( "/review/" ),
                "label" => 'Review',
                'icon' => '',
                'hidden' => false,
                'submenu' => []
            ];
        }

        return $tabs;
    }

    public function add_url( $template_for_url ) {
        $template_for_url['review'] = 'template-metrics.php';
        return $template_for_url;
    }

}
PG_Charts_Loader::instance();
