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
        $current_lap = pg_current_global_lap();
        $link = '/prayer_app/global/' . $current_lap['key'];
        wp_redirect( $link );
        exit;
    }
}
Prayer_Global_Porch_Newest_Lap::instance();


class Prayer_Global_Porch_Newest_Lap_Stats extends DT_Magic_Url_Base
{
    public $page_title = 'Prayer.Global';
    public $root = 'newest';
    public $type = 'stats';
    public $url_token = 'newest/stats';
    public $type_name = 'Newest Lap Stats';
    public $post_type = 'laps';

    private static $_instance = null;

    public static function instance() {
        if (is_null( self::$_instance )) {
            self::$_instance = new self();
        }
        return self::$_instance;
    } // End instance()

    public function __construct() {
        parent::__construct();

        $url = dt_get_url_path();

        if (substr( $url, 0, strlen( $this->url_token ) ) !== $this->root . '/' . $this->type) {
            return;
        }

        $this->redirect();
    }

    public function redirect() {
        $current_lap = pg_current_global_lap();
        $link = '/prayer_app/global/' . $current_lap['key'] . '/stats';
        wp_redirect( $link );
        exit;
    }
}
Prayer_Global_Porch_Newest_Lap_Stats::instance();


class Prayer_Global_Porch_Newest_Lap_Map extends DT_Magic_Url_Base
{
    public $page_title = 'Prayer.Global';
    public $root = 'newest';
    public $type = 'map';
    public $url_token = 'newest/map';
    public $type_name = 'Newest Lap Map';
    public $post_type = 'laps';

    private static $_instance = null;

    public static function instance() {
        if (is_null( self::$_instance )) {
            self::$_instance = new self();
        }
        return self::$_instance;
    } // End instance()

    public function __construct() {
        parent::__construct();

        $url = dt_get_url_path();

        if (substr( $url, 0, strlen( $this->url_token ) ) === $this->root . '/' . $this->type) {
            $this->redirect();
        }

        if ( $url === 'map' ) {
            $this->redirect();
        }


    }

    public function redirect() {
        $current_lap = pg_current_global_lap();
        $link = '/prayer_app/global/' . $current_lap['key'] . '/map';
        wp_redirect( $link );
        exit;
    }
}
Prayer_Global_Porch_Newest_Lap_Map::instance();


class Prayer_Global_Porch_App_Store_Redirect extends DT_Magic_Url_Base
{
    public $page_title = 'Prayer.Global -  QR Redirect';
    public $root = 'qr';
    public $type = 'app_stores';
    public $url_token = 'qr/app_stores';
    public $type_name = 'App Store Redirect';
    public $post_type = 'laps';

    private static $_instance = null;

    public static function instance() {
        if (is_null( self::$_instance )) {
            self::$_instance = new self();
        }
        return self::$_instance;
    } // End instance()

    public function __construct() {
        parent::__construct();

        $url = dt_get_url_path();

        if (substr( $url, 0, strlen( $this->url_token ) ) === $this->root . '/' . $this->type) {
            $this->redirect();
        }
    }

    public function redirect() {
        $current_lap = pg_current_global_lap();
        $link = '/prayer_app/global/' . $current_lap['key'] . '/map';
        wp_redirect( $link );
        exit;
    }
}
Prayer_Global_Porch_App_Store_Redirect::instance();

