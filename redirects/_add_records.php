<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.

class Prayer_Global_Add extends DT_Magic_Url_Base
{
    public $page_title = 'Prayer.Global';
    public $root = 'add';
    public $type = 'records';
    public $url_token = 'add/records';
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
        require_once( trailingslashit( plugin_dir_path(__DIR__) ) . 'pages/assets/utilities.php' );
        global $wpdb;
        $start=2;
        if ( isset(  $_GET['start'] ) ) {
            $start = $_GET['start'];
        }

        $raw_list = $wpdb->get_col(
            "SELECT
                        lg1.grid_id
                    FROM $wpdb->dt_location_grid lg1
                    WHERE lg1.level = 0
                      AND lg1.grid_id NOT IN ( SELECT lg11.admin0_grid_id FROM $wpdb->dt_location_grid lg11 WHERE lg11.level = 1 AND lg11.admin0_grid_id = lg1.grid_id )
                      AND lg1.admin0_grid_id NOT IN (100050711,100219347,100089589,100074576,100259978,100018514)
                    UNION ALL
                    SELECT
                        lg2.grid_id
                    FROM $wpdb->dt_location_grid lg2
                    WHERE lg2.level = 1
                      AND lg2.admin0_grid_id NOT IN (100050711,100219347,100089589,100074576,100259978,100018514)
                    UNION ALL
                    SELECT
                        lg3.grid_id
                    FROM $wpdb->dt_location_grid lg3
                    WHERE lg3.level = 2
                      AND lg3.admin0_grid_id IN (100050711,100219347,100089589,100074576,100259978,100018514)"
        );
        $inc = 0;
        $current_lap = pg_current_global_lap();
        $post_id = $current_lap['post_id'];
        if ( isset( $_GET['post_id'] ) ) {
            $post_id = $_GET['post_id'];
        }
        foreach( $raw_list as $grid_id ) {
            if ( $inc > $start ) {
                $timestamp = time();
                $wpdb->query( "INSERT INTO $wpdb->dt_reports(user_id, post_id, post_type, type, subtype, grid_id, timestamp)
                VALUES('2', $post_id, 'laps', 'prayer_app', 'custom', $grid_id, $timestamp);" );
            }
            $inc++;
        }
    }

}
Prayer_Global_Add::instance();
