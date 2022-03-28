<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.

class PG_Utilities {

    public static function generate_key(){
        return substr( md5( rand( 10000, 100000 ).time() ), 0, 3 ) . substr( md5( rand( 10000, 100000 ).time() ), 10, 3 );
    }

    public static function get_current_global_lap() : array {
        $lap = get_option('pg_current_global_lap');
        return $lap;
    }

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

    public static function build_location_array( $grid_id ) {
        global $wpdb;

        // get record and level
        $grid_record = $wpdb->get_row( $wpdb->prepare( "
            SELECT
              g.grid_id as id,
              g.grid_id,
              g.alt_name as name,
              g.alt_population as population,
              g.latitude,
              g.longitude,
              g.country_code,
              g.admin0_code,
              g.parent_id,
              g.admin0_grid_id,
              gc.alt_name as admin0_name,
              g.admin1_grid_id,
              ga1.alt_name as admin1_name,
              g.admin2_grid_id,
              ga2.alt_name as admin2_name,
              g.admin3_grid_id,
              ga3.alt_name as admin3_name,
              g.admin4_grid_id,
              ga4.alt_name as admin4_name,
              g.admin5_grid_id,
              ga5.alt_name as admin5_name,
              g.level,
              g.level_name,
              g.is_custom_location,
              g.north_latitude,
              g.south_latitude,
              g.east_longitude,
              g.west_longitude,
              gc.north_latitude as c_north_latitude,
              gc.south_latitude as c_south_latitude,
              gc.east_longitude as c_east_longitude,
              gc.west_longitude as c_west_longitude
            FROM $wpdb->dt_location_grid as g
            LEFT JOIN $wpdb->dt_location_grid as gc ON g.admin0_grid_id=gc.grid_id
            LEFT JOIN $wpdb->dt_location_grid as ga1 ON g.admin1_grid_id=ga1.grid_id
            LEFT JOIN $wpdb->dt_location_grid as ga2 ON g.admin2_grid_id=ga2.grid_id
            LEFT JOIN $wpdb->dt_location_grid as ga3 ON g.admin3_grid_id=ga3.grid_id
            LEFT JOIN $wpdb->dt_location_grid as ga4 ON g.admin4_grid_id=ga4.grid_id
            LEFT JOIN $wpdb->dt_location_grid as ga5 ON g.admin5_grid_id=ga5.grid_id
            WHERE g.grid_id = %s
        ", $grid_id ), ARRAY_A );
        switch ( $grid_record['level_name'] ) {
            case 'admin1':
                $full_name = $grid_record['name'] . ', ' . $grid_record['admin0_name'];
                break;
            case 'admin2':
                $full_name = $grid_record['name'] . ', ' . $grid_record['admin1_name'] . ', ' . $grid_record['admin0_name'];
                break;
            case 'admin3':
                $full_name = $grid_record['name'] . ', ' . $grid_record['admin2_name'] . ', ' . $grid_record['admin1_name'] . ', ' . $grid_record['admin0_name'];
                break;
            case 'admin4':
                $full_name = $grid_record['name'] . ', ' . $grid_record['admin3_name'] . ', ' . $grid_record['admin2_name'] . ', ' . $grid_record['admin1_name'] . ', ' . $grid_record['admin0_name'];
                break;
            case 'admin5':
                $full_name = $grid_record['name'] . ', ' . $grid_record['admin4_name'] . ', ' . $grid_record['admin3_name'] . ', ' . $grid_record['admin2_name'] . ', ' . $grid_record['admin1_name'] . ', ' . $grid_record['admin0_name'];
                break;
            case 'admin0':
            default:
                $full_name = $grid_record['name'];
                break;
        }

        // create the description
        if ( 'admin1' === $grid_record['level_name'] ) {
            $admin_title = 'state';
        } else if ( 'admin0' === $grid_record['level_name'] ) {
            $admin_title = 'country';
        } else {
            $admin_title = 'county';
        }
        $description = "The ".$admin_title." of ".$full_name. " has a population of " . number_format( $grid_record['population'] ) . '.';

        // build array
        $content = [
            'grid_id' => $grid_id,
            'location' => [
                'full_name' => $full_name,
                'bounds' => [
                  'north_latitude' => (float) $grid_record['north_latitude'],
                  'south_latitude' => (float) $grid_record['south_latitude'],
                  'east_longitude' => (float) $grid_record['east_longitude'],
                  'west_longitude' => (float) $grid_record['west_longitude'],
                ],
                'c_bounds' => [
                  'north_latitude' => (float) $grid_record['c_north_latitude'],
                  'south_latitude' => (float) $grid_record['c_south_latitude'],
                  'east_longitude' => (float) $grid_record['c_east_longitude'],
                  'west_longitude' => (float) $grid_record['c_west_longitude'],
                ],
                'url' => 'https://via.placeholder.com/600x400?text='.$grid_id,
                'description' => $description
            ],
            'sections' => [
                [
                    'title' => 'Praise',
                    'url' => 'https://via.placeholder.com/600x400?text='.$grid_id,
                    'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries"
                ],
                [
                    'title' => 'Kingdom Come',
                    'url' => 'https://via.placeholder.com/600x400?text='.$grid_id,
                    'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries"
                ],
                [
                    'title' => 'Pray the Book of Acts',
                    'url' => 'https://via.placeholder.com/600x400?text='.$grid_id,
                    'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries"
                ]
            ],
            'people_groups' => [
                [
                    'name' => 'Afrikaner',
                    'rop3' => '100093',
                    'meta' => [
                        'people_cluster' => 'Germanic',
                        'population_all_countries' => '4,683,100',
                        'number_of_countries' => '15',
                        'largest_religion' => 'Christian',
                        'progress' => '5',
                    ]
                ],
                [
                    'name' => 'Abai Sungai',
                    'rop3' => '10120',
                    'meta' => [
                        'people_cluster' => 'Borneo-Kalimantan',
                        'population_all_countries' => '1,500',
                        'percent_christian' => '0.00',
                        'percent_evangelical' => '0.00',
                        'largest_religion' => 'Muslim',
                        'main_language' => 'Abai Sungai',
                        'progress' => '0',
                    ]
                ],
                [
                    'name' => 'Amat',
                    'rop3' => '10120',
                    'meta' => [
                        'people_cluster' => 'South Asia Hindu - other',
                        'population_all_countries' => '316,000',
                        'percent_christian' => '0.00',
                        'percent_evangelical' => '0.00',
                        'largest_religion' => 'Hinduism',
                        'main_language' => 'Abai Sungai',
                        'progress' => '0',
                    ]
                ],
            ]
        ];

        return $content;
    }

    public static function save_log( $parts, $data, $is_global = true ) {

        if ( !isset( $parts['post_id'], $parts['root'], $parts['type'], $data['grid_id'] ) ) {
            return new WP_Error( __METHOD__, "Missing parameters", [ 'status' => 400 ] );
        }

        $args = [
            'post_id' => $parts['post_id'],
            'post_type' => 'laps',
            'type' => $parts['root'],
            'subtype' => $parts['type'],
            'payload' => null,
            'value' => 1,
            'grid_id' => $data['grid_id'],
        ];
        if ( is_user_logged_in() ) {
            $args['user_id'] = get_current_user_id();
        }
        $id = dt_report_insert( $args, false );

        if ( $is_global )  {
            return PG_Utilities::get_new_global_location();
        } else {
            return PG_Utilities::get_new_custom_location( $parts );
        }
    }

    /**
     * Global query
     * @return array|false|void
     */
    public static function get_new_global_location() {
        // get 4770 list
        $list_4770 = PG_Utilities::query_4770_locations();

        // subtract prayed places
        $list_prayed = PG_Utilities::query_global_prayed_list();
        if ( ! empty( $list_prayed ) ) {
            foreach( $list_prayed as $grid_id ) {
                if ( isset( $list_4770[$grid_id] ) ) {
                    unset( $list_4770[$grid_id] );
                }
            }
        }

        if ( empty( $list_4770 ) ) {
            if ( dt_is_rest() ) { // signal new lap to rest request
                return false;
            } else { // if first load on finished lap, redirect to new lap
                $current_lap = PG_Utilities::get_current_global_lap();
                PG_Utilities::generate_new_global_prayer_lap();
                wp_redirect( '/prayer_app/global/'.$current_lap['key'] );
                exit;
            }
        }

        if ( count( $list_4770 ) > 20 ) { // turn off shuffle for the last few records
            shuffle( $list_4770 );
        } else {
            sort( $list_4770 );
        }
        $grid_id = $list_4770[0];

        $content = PG_Utilities::build_location_array( $grid_id );
        return $content;
    }

    public static function query_global_prayed_list() {

        global $wpdb;
        $current_lap = PG_Utilities::get_current_global_lap();

        $raw_list = $wpdb->get_col( $wpdb->prepare(
            "SELECT DISTINCT grid_id
                    FROM $wpdb->dt_reports
                    WHERE
                          timestamp >= %d
                      AND type = 'prayer_app'"
            , $current_lap['start_time']  ) );

        $list = [];
        if ( ! empty( $raw_list) ) {
            foreach( $raw_list as $item ) {
                $list[$item] = $item;
            }
        }

        return $list;
    }

    public static function generate_new_global_prayer_lap() {
        global $wpdb;

        // build new lap number
        $completed_prayer_lap_number = $wpdb->get_var(
            "SELECT COUNT(*) as laps
                    FROM $wpdb->posts p
                    JOIN $wpdb->postmeta pm ON p.ID=pm.post_id AND pm.meta_key = 'type' AND pm.meta_value = 'global'
                    JOIN $wpdb->postmeta pm2 ON p.ID=pm2.post_id AND pm2.meta_key = 'status' AND pm2.meta_value IN ('complete', 'active')
                    WHERE p.post_type = 'laps';"
        );
        $next_global_lap_number = $completed_prayer_lap_number + 1;

        // create key
        $key = self::generate_key();

        $time = time();
        $date = date( 'Y-m-d H:m:s', time() );

        $fields = [];
        $fields['title'] = 'Global #' . $next_global_lap_number;
        $fields['status'] = 'active';
        $fields['type'] = 'global';
        $fields['start_date'] = $date;
        $fields['start_time'] = $time;
        $fields['global_lap_number'] = $next_global_lap_number;
        $fields['prayer_app_global_magic_key'] = $key;
        $new_post = DT_Posts::create_post('laps', $fields, false, false );
        if ( is_wp_error( $new_post ) ) {
            // @handle error
            dt_write_log('failed to create');
            dt_write_log($new_post);
            exit;
        }

        // update current_lap
        $previous_lap = PG_Utilities::get_current_global_lap();
        $lap = [
            'lap_number' => $next_global_lap_number,
            'post_id' => $new_post['ID'],
            'key' => $key,
            'start_time' => $time,
        ];
        update_option('pg_current_global_lap', $lap, true );

        // close previous lap
        DT_Posts::update_post('laps', $previous_lap['post_id'], [ 'status' => 'complete', 'end_date' => $date, 'end_time' => $time ], false, false );

        return $new_post['ID'];
    }

    /**
     * Custom query
     * @param $post_id
     * @return array|false|void
     */
    public static function get_new_custom_location( $parts ) {
        $post_id = $parts['post_id'];
        $public_key = $parts['public_key'];

        // get 4770 list
        $global_list_4770 = $list_4770 = PG_Utilities::query_4770_locations();

        // subtract prayed places
        $list_prayed = PG_Utilities::query_custom_prayed_list( $post_id );
        if ( ! empty( $list_prayed ) ) {
            foreach( $list_prayed as $grid_id ) {
                if ( isset( $list_4770[$grid_id] ) ) {
                    unset( $list_4770[$grid_id] );
                }
            }
        }

        if ( empty( $list_4770 ) ) {
            $time = time();
            $date = date( 'Y-m-d H:m:s', time() );
            DT_Posts::update_post('laps', $post_id, [ 'status' => 'complete', 'end_date' => $date, 'end_time' => $time ], false, false );
            if ( dt_is_rest() ) { // signal new lap to rest request
                return false;
            } else { // if first load on finished lap, redirect to new lap
                wp_redirect( '/prayer_app/custom/'.$public_key );
                exit;
            }
        }

        if ( count( $list_4770 ) > 20 ) { // turn off shuffle for the last few records
            shuffle( $list_4770 );
        } else {
            sort( $list_4770 );
        }
        $grid_id = $list_4770[0];

        // checks global list and finds an id that has not been prayer for by either custom or global.
        // else it goes with the custom selected grid_id above
        $global_list_prayed = PG_Utilities::query_global_prayed_list(); // positive list of global locations prayed for
        if ( ! empty( $global_list_prayed ) && in_array( $grid_id, $global_list_prayed ) /* in_array means the global list has already prayed for this location */ ) {
            foreach( $list_4770 as $index => $custom_grid_id ) {
                if ( ! isset( $global_list_prayed[$custom_grid_id] ) ) {
                    $grid_id = $list_4770[$index];
                }
            }
        }

        $content = PG_Utilities::build_location_array( $grid_id );
        return $content;
    }

    public static function query_custom_prayed_list( $post_id ) {

        global $wpdb;
        $raw_list = $wpdb->get_col( $wpdb->prepare(
            "SELECT DISTINCT grid_id
                    FROM $wpdb->dt_reports
                    WHERE post_id = %d
                      AND type = 'prayer_app'
                      AND subtype = 'custom';"
            , $post_id ) );

        $list = [];
        if ( ! empty( $raw_list) ) {
            foreach( $raw_list as $item ) {
                $list[$item] = $item;
            }
        }

        return $list;
    }
}
