<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.

class Prayer_Global_Porch_Newest_Lap extends DT_Magic_Url_Base
{
    public $page_title = 'Prayer.Global';
    public $root = 'newest';
    public $type = 'lap';
    public $url_token = 'newest/lap';
    public $type_name = 'Newest Lap';
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

        if ( substr( $url, 0, strlen( $this->url_token ) ) !== $this->root . '/' . $this->type ) {
            return;
        }

        $this->redirect();
    }

    public function redirect() {
        global $wpdb;
        $newest_key = $wpdb->get_var("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = 'prayer_app_global_magic_key' ORDER BY post_id DESC");
        $link = '/prayer_app/global/' . $newest_key;
        wp_redirect( $link );
        exit;
    }

}
Prayer_Global_Porch_Newest_Lap::instance();
