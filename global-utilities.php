<?php

function pg_generate_key(){
    return substr( md5( mt_rand( 10000, 100000 ).time() ), 0, 3 ) . substr( md5( mt_rand( 10000, 100000 ).time() ), 10, 3 );
}

function pg_current_global_lap() : array {
    /**
     * Example:
     *  [lap_number] => 5
     *  [post_id] => 19
     *  [key] => d7dcd4
     *  [start_time] => 1651269768
     */
    $lap = get_option('pg_current_global_lap');
    return $lap;
}

function pg_lap_stats( $lap_number ) : array {

    $data = [
        'time_elapsed' => '',
        'start_timestamp' => '',
        'end_timestamp' => '',
        'participants' => '',
        'completed' => '',
        'remaining' => '',
        'lap_number' => 0,
        'key' => '',
        'post_id' => ''
    ];

    if ( ! empty( $lap_number ) ) {

        // lap number
        $data['lap_number'] = (int) $lap_number;

        // lap elements
        global $wpdb;
        $result = $wpdb->get_row( $wpdb->prepare(
            "SELECT pm.meta_value as lap_number, pm1.meta_value as lap_key, pm.post_id, pm2.meta_value as start_time, pm3.meta_value as end_time
                    FROM $wpdb->postmeta pm
                    LEFT JOIN $wpdb->postmeta pm1 ON pm.post_id=pm1.post_id AND pm1.meta_key = 'prayer_app_global_magic_key'
                    LEFT JOIN $wpdb->postmeta pm2 ON pm.post_id=pm2.post_id AND pm2.meta_key = 'start_time'
                    LEFT JOIN $wpdb->postmeta pm3 ON pm.post_id=pm3.post_id AND pm3.meta_key = 'end_time'
                    WHERE pm.meta_key = 'global_lap_number' AND pm.meta_value = %d",
            $lap_number
        ), ARRAY_A);
        $data['start_timestamp'] = (int) $result['start_time'];
        $data['end_timestamp'] = (int) $result['end_time'];
        $data['key'] = $result['lap_key'];
        $data['post_id'] = (int) $result['post_id'];

        // start and end
        $end_time = $data['end_timestamp'];
        if ( empty( $end_time ) ) {
            $end_time = time();
        }
        $time_difference = $end_time - $data['start_timestamp'];

        // time calculations
        $days = floor( $time_difference / 60 / 60 / 24 );
        $hours = floor( ( $time_difference / 60 / 60 ) - ( $days * 24 ) );
        $minutes =  floor( ( $time_difference / 60 ) - ( $hours * 60 ) - ( $days * 24 * 60 )  );
        if ( empty( $days ) && empty( $hours ) ){
            $data['time_elapsed'] = "$minutes minutes";
            $data['time_elapsed_small'] = $minutes."m";
        }
        else if ( empty( $days ) ) {
            $data['time_elapsed'] = "$hours hours, $minutes minutes";
            $data['time_elapsed_small'] = $hours."h, ".$minutes."m";
        }
        else {
            $data['time_elapsed'] = "$days days, $hours hours, $minutes minutes";
            $data['time_elapsed_small'] = $days."d, ".$hours."h, ".$minutes."m";
        }

        // get completed and remaining counts
        $completed = $wpdb->get_var( $wpdb->prepare( "
           SELECT COUNT( DISTINCT( r.grid_id ) )
           FROM $wpdb->dt_reports r
            WHERE r.post_type = 'laps'
                AND r.type = 'prayer_app'
           AND r.timestamp >= %d AND r.timestamp <= %d
        ", $data['start_timestamp'], $end_time ) );

        $completed = (int) $completed;

        $data['completed'] = number_format( $completed );
        $data['remaining'] = number_format( 4770 - $completed );


        // participants
        $data['participants'] = '234';

    }

    return $data;
}

function pg_query_4770_locations() {

    if ( get_transient( __METHOD__ ) ) {
        return get_transient( __METHOD__ );
    }

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

function pg_fields() {
    $defaults = [
        'image_asset_url' => [
            'label' => 'Image Asset URL',
            'description' => 'Add root site URl. {root_site_url}/location-grid-images/v1/...',
            'value' => 'https://storage.googleapis.com/',
            'type' => 'text',
        ],
        'scripture_api_bible' => [
            'label' => 'Scripture API Bible API',
            'description' => 'API token to access https://scripture.api.bible',
            'value' => '',
            'type' => 'text',
        ],

    ];

    $defaults = apply_filters( 'pg_fields', $defaults );

    $saved_fields = get_option( 'pg_fields', [] );

    return pg_recursive_parse_args( $saved_fields, $defaults );
}

function pg_image_url() {
    $fields = pg_fields();
    return trailingslashit( $fields['image_asset_url']['value'] ) . 'location-grid-images/v1/';
}
function pg_jp_image_url( $type, $id ) {
    $image_list = get_option('location_grid_images_json' );
    $base_url = pg_image_url() . 'jp/';
    switch( $type ) {
        case 'pid3':
            if ( isset( $image_list['jp']['pid3'][$id] ) ) {
                return $base_url . 'pid3/' . $image_list['jp']['pid3'][$id];
            } else {
                return false;
            }
        case 'progress':
            if ( isset( $image_list['jp']['progress'][$id] ) ) {
                return $base_url . 'progress/' . $image_list['jp']['progress'][$id];
            } else {
                return false;
            }
        default:
            return false;
    }
//    $fields = pg_fields();
//    return trailingslashit( $fields['image_asset_url']['value'] ) . 'location-grid-images/v1/';
}
function pg_image_json_url() {
    $fields = pg_fields();
    return trailingslashit( $fields['image_asset_url']['value'] ) . 'location-grid-images/v1/v1.json';
}

/**
 * Returns the full array db of images in the location-grid-images file store.
 * @param $grid_id
 * @param $full_urls
 * @return array|false|mixed|void
 */
function pg_images( $grid_id = null, $full_urls = false ) {
    $image_list = get_option('location_grid_images_json' );

    // full list
    if ( is_null( $grid_id ) ) {
        if ( $full_urls ) {
            $base_url = pg_image_url();
            unset($image_list['version'] );
            foreach( $image_list as $i0 => $v0 ) {
                foreach( $v0 as $i1 => $v1 ) {
                    foreach( $v1 as $i2 => $v2 ) {
                        $image_list[$i0][$i1][$i2] = $base_url . $i0 .'/'. $i1 . '/' . $v2;
                    }
                }
            }
        }
        return $image_list;
    }

    // single grid_id
    if ( $full_urls ) {
        $base_url = pg_image_url();
        foreach( $image_list[$grid_id] as $i1 => $v1 ) {
            foreach( $v1 as $i2 => $v2 ) {
                $image_list[$grid_id][$i1][$i2] = $base_url . $grid_id .'/'. $i1 . '/' . $v2;
            }
        }
    }
    return $image_list[$grid_id] ?? [];
}


function pg_recursive_parse_args( $args, $defaults ) {
    $new_args = (array) $defaults;

    foreach ( $args ?: [] as $key => $value ) {
        if ( is_array( $value ) && isset( $new_args[ $key ] ) ) {
            $new_args[ $key ] = pg_recursive_parse_args( $value, $new_args[ $key ] );
        }
        elseif ( $key !== "default" ){
            $new_args[ $key ] = $value;
        }
    }

    return $new_args;
}
