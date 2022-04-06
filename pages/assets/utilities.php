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
              p.name as parent_name,
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
              gc.west_longitude as c_west_longitude,
              (SELECT count(pl.grid_id) FROM $wpdb->dt_location_grid pl WHERE pl.parent_id = g.parent_id) as peer_locations,
              lgf.*
            FROM $wpdb->dt_location_grid as g
            LEFT JOIN $wpdb->dt_location_grid as gc ON g.admin0_grid_id=gc.grid_id
            LEFT JOIN $wpdb->dt_location_grid as ga1 ON g.admin1_grid_id=ga1.grid_id
            LEFT JOIN $wpdb->dt_location_grid as ga2 ON g.admin2_grid_id=ga2.grid_id
            LEFT JOIN $wpdb->dt_location_grid as ga3 ON g.admin3_grid_id=ga3.grid_id
            LEFT JOIN $wpdb->dt_location_grid as ga4 ON g.admin4_grid_id=ga4.grid_id
            LEFT JOIN $wpdb->dt_location_grid as ga5 ON g.admin5_grid_id=ga5.grid_id
            LEFT JOIN $wpdb->dt_location_grid as p ON g.parent_id=p.grid_id
            LEFT JOIN $wpdb->location_grid_facts as lgf ON g.grid_id=lgf.grid_id
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
            $admin_level_name = 'state';
            $admin_level_name_plural = 'states';
        } else if ( 'admin0' === $grid_record['level_name'] ) {
            $admin_level_name = 'country';
            $admin_level_name_plural = 'countries';
        } else {
            $admin_level_name = 'county';
            $admin_level_name_plural = 'counties';
        }

        $locations_url = prayer_global_image_library_url() . 'locations/' . rand(0,1) . '/';
        $population = $grid_record['population'];

        // build array
        $content = [
            'grid_id' => $grid_id,
            'location' => [
                'name' => $grid_record['name'],
                'full_name' => $full_name,
                'admin0_name' => $grid_record['admin0_name'],
                'parent_name' => $grid_record['parent_name'],
                'admin_level_name' => $admin_level_name,
                'admin_level_name_plural' => $admin_level_name_plural,
                'peer_locations' => $grid_record['peer_locations'],
                'longitude' => (float) $grid_record['longitude'],
                'latitude' => (float) $grid_record['latitude'],
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
                'url' => $locations_url.$grid_id.'.png',
                'stats' => [ // all numbers are estimated
                    'population' => number_format( intval( $population ) ),

                    'birth_rate' => (float) $grid_record['birth_rate'],
                    'death_rate' => (float) $grid_record['death_rate'],
                    'growth_rate' => (float) $grid_record['growth_rate'],

                    'population_growth_status' => self::pace_calculator( 'population_growth_status', $grid_record ),
                    'primary_language' => $grid_record['primary_language'],
                    'primary_religion' => $grid_record['primary_religion'],

                    'believers' => number_format( intval( $grid_record['believers'] ) ),
                    'christian_adherants' => number_format( intval( $grid_record['christian_adherants'] ) ),
                    'non_christians' => number_format( intval( $grid_record['non_christians'] ) ),

                    'percent_believers' => round( (float) $grid_record['percent_believers'], 2),
                    'percent_christian_adherants' => round( (float) $grid_record['percent_christian_adherants'], 2 ),
                    'percent_non_christians' => round( (float) $grid_record['percent_non_christians'], 2 ),

                    'births_without_jesus_last_hour' => self::pace_calculator( 'births_without_jesus_last_hour', $grid_record ),
                    'births_without_jesus_last_100' => self::pace_calculator( 'births_without_jesus_last_100', $grid_record ),
                    'births_without_jesus_last_week' => self::pace_calculator( 'births_without_jesus_last_week', $grid_record ),
                    'births_without_jesus_last_month' => self::pace_calculator( 'births_without_jesus_last_month', $grid_record ),

                    'deaths_without_jesus_last_hour' => self::pace_calculator( 'deaths_without_jesus_last_hour', $grid_record ),
                    'deaths_without_jesus_last_100' => self::pace_calculator( 'deaths_without_jesus_last_100', $grid_record ),
                    'deaths_without_jesus_last_week' => self::pace_calculator( 'deaths_without_jesus_last_week', $grid_record ),
                    'deaths_without_jesus_last_month' => self::pace_calculator( 'deaths_without_jesus_last_month', $grid_record ),

                    'births_without_jesus_per_second' => self::pace_calculator( 'births_without_jesus_per_second', $grid_record ),
                    'deaths_without_jesus_per_second' => self::pace_calculator( 'deaths_without_jesus_per_second', $grid_record ),

                ]
            ],
            'sections' => [
                [
                    'title' => 'Praise',
                    'url' => 'https://via.placeholder.com/600x400?text='.$grid_id,
                    'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries"
                ],
//                [
//                    'title' => 'Kingdom Come',
//                    'url' => 'https://via.placeholder.com/600x400?text='.$grid_id,
//                    'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries"
//                ],
//                [
//                    'title' => 'Pray the Book of Acts',
//                    'url' => 'https://via.placeholder.com/600x400?text='.$grid_id,
//                    'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries"
//                ]
            ],
            'cities' => [
                [
                    'name' => 'City name',
                    'population' => number_format( intval( '123456' ) )
                ],
                [
                    'name' => 'City name',
                    'population' => number_format( intval( '123456' ) )
                ],
                [
                    'name' => 'City name',
                    'population' => number_format( intval( '123456' ) )
                ],
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
            ],
        ];

        return $content;
    }

    public static function pace_calculator( $type, $grid_record ) {
        $return_value = 0;
        $population = $grid_record['population'];
        $birth_rate = $grid_record['birth_rate'];
        $death_rate = $grid_record['death_rate'];
        $believers = $grid_record['believers'];
        $christian_adherants = $grid_record['christian_adherants'];
        $non_christians = $grid_record['non_christians'];
        $not_believers = $non_christians + $christian_adherants;


        switch( $type ) {
            case 'births_without_jesus_last_hour':
                $return_value = ( $birth_rate * ( $not_believers / 1000 ) ) / 365 / 24 ;
                break;
            case 'births_without_jesus_last_100':
                $return_value = ( $birth_rate * ( $not_believers / 1000 ) ) / 365 / 24 * 100 ;
                break;
            case 'births_without_jesus_last_week':
                $return_value = ( $birth_rate * ( $not_believers / 1000 ) ) / 365 * 7 ;
                break;
            case 'births_without_jesus_last_month':
                $return_value = ( $birth_rate * ( $not_believers / 1000 ) ) / 365 * 30 ;
                break;

            case 'deaths_without_jesus_last_hour':
                $return_value = ( $death_rate * ( $not_believers / 1000 ) ) / 365 / 24 ;
                break;
            case 'deaths_without_jesus_last_100':
                $return_value = ( $death_rate * ( $not_believers / 1000 ) ) / 365 / 24 * 100 ;
                break;
            case 'deaths_without_jesus_last_week':
                $return_value = ( $death_rate * ( $not_believers / 1000 ) ) / 365 * 7;
                break;
            case 'deaths_without_jesus_last_month':
                $return_value =  ( $death_rate * ( $not_believers / 1000 ) ) / 365 * 30 ;
                break;

            case 'births_without_jesus_per_second':
                $return_value =  ( $birth_rate * ( $not_believers / 1000 ) ) / 365 / 24 / 60 / 60; // per second
                break;
            case 'deaths_without_jesus_per_second':
                $return_value =  ( $death_rate * ( $not_believers / 1000 ) ) / 365 / 24 / 60 / 60; // per second
                break;
            case 'population_growth_status':
                if ( $grid_record['growth_rate'] >= 1.3 ) {
                    $return_value = 'Fastest Growth in the World';
                } else if ( $grid_record['growth_rate'] >= 1.2 ) {
                    $return_value = 'Extreme Growth';
                } else if ( $grid_record['growth_rate'] >= 1.1 ) {
                    $return_value = 'Significant Growth';
                } else if ( $grid_record['growth_rate'] >= 1.0 ) {
                    $return_value = 'Stable, but with slight growth';
                } else if ( $grid_record['growth_rate'] >= .99 ) {
                    $return_value = 'Stable, but in slight decline';
                } else if ( $grid_record['growth_rate'] >= .96 ) {
                    $return_value = 'Extreme Decline';
                } else {
                    $return_value = 'Fastest Decline in the World';
                }
                return $return_value;
            default:
                break;
        }

        return number_format( intval( $return_value ) );
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
            'value' => $data['grid_id'] ?? 1,
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
