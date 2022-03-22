<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.

class PG_Utilities {
    public static function query_4770_locations() {

        if ( get_transient( __METHOD__ ) ) {
            return get_transient( __METHOD__ );
        }

        // @todo add cache response
        global $wpdb;
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

        $list = [];
        if ( ! empty( $raw_list) ) {
            foreach( $raw_list as $item ) {
                $list[$item] = $item;
            }
        }

        set_transient( __METHOD__, $list, 60*60*12 );

        return $list;
    }

    public static function query_prayed_list( $post_id ) {

        global $wpdb;
        $raw_list = $wpdb->get_col( $wpdb->prepare(
            "SELECT DISTINCT grid_id
                    FROM $wpdb->dt_reports
                    WHERE post_id = %d
                      AND type = 'prayer_app'
                      AND subtype = 'global';"
            , $post_id ) );

        $list = [];
        if ( ! empty( $raw_list) ) {
            foreach( $raw_list as $item ) {
                $list[$item] = $item;
            }
        }

        return $list;
    }

    public static function get_current_lap() : array {
        $lap = [
            'post_id' => 11,
            'key' => 'a219a36b38e3e7dfc43589330b18bde924d234ed41860d20aa838303732d2d35'
        ];
        return $lap;
    }

    public static function generate_new_global_prayer_lap() {
        global $wpdb;
        // verify previous lap complete
        $current_prayer_lap_post_id = get_option('pg_current_prayer_lap');
        if ( ! empty( $current_prayer_lap_post_id ) ) {
            $total_locations = $wpdb->get_var( $wpdb->prepare(
                "SELECT COUNT( DISTINCT grid_id) as total_locations
                        FROM $wpdb->dt_reports
                        WHERE post_id = %d
                          AND type = 'prayer_app'
                          AND subtype = 'global';"
                , $current_prayer_lap_post_id )
            );
            if ( $total_locations < 4770 ) {
                return $current_prayer_lap_post_id;
            }
        }

        // build new lap number
        $completed_prayer_lap_number = $wpdb->get_var(
            "SELECT COUNT(*) as laps
                    FROM $wpdb->posts p
                    JOIN $wpdb->postmeta pm ON p.ID=pm.post_id AND pm.meta_key = 'type' AND pm.meta_value = 'global'
                    JOIN $wpdb->postmeta pm2 ON p.ID=pm2.post_id AND pm2.meta_key = 'status' AND pm2.meta_value = 'complete'
                    WHERE p.post_type = 'laps';"
        );
        $next_global_lap_number = $completed_prayer_lap_number + 1;

        $fields = [];
        $fields['title'] = 'Global #' . $next_global_lap_number;
        $fields['global_lap_number'] = $next_global_lap_number;
        $fields['status'] = 'active';
        $fields['type'] = 'global';
        $fields['start_date'] = date( 'Y-m-d H:m:s', time() );

        $new_post = DT_Posts::create_post('laps', $fields, false, false );
        if ( is_wp_error( $new_post ) ) {
            // @handle error
            dt_write_log('failed to create');
            dt_write_log($new_post);
            exit;
        }

        update_option('pg_current_prayer_lap', $new_post['ID'], true );

        // @todo set to complete all previous laps??

        return $new_post['ID'];
    }
}
