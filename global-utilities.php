<?php

function pg_generate_key() {
    return substr( md5( mt_rand( 10000, 100000 ).time() ), 0, 3 ) . substr( md5( mt_rand( 10000, 100000 ).time() ), 10, 3 );
}
function pg_grid_images_json(){
    return get_option( 'pg_grid_images_json' );
}
function pg_jp_images_json(){
    return get_option( 'pg_jp_images_json' );
}
function pg_grid_images_version(){
    return get_option( 'pg_grid_images_version' );
}
function pg_jp_images_version(){
    return get_option( 'pg_jp_images_version' );
}
function pg_current_global_lap() : array {
    /**
     * Example:
     *  [lap_number] => 5
     *  [post_id] => 19
     *  [key] => d7dcd4
     *  [start_time] => 1651269768
     */
    $lap = get_option( 'pg_current_global_lap' );
    return $lap;
}

/**
 * @param $key
 * @return array|false
 */
function pg_get_global_lap_by_key( $key ) {

//    if ( wp_cache_get( __METHOD__. $key ) ) {
//        return wp_cache_get( __METHOD__. $key );
//    }

    global $wpdb;
    $result = $wpdb->get_row( $wpdb->prepare(
        "SELECT pm.meta_value as lap_key, pm1.meta_value as lap_number, pm.post_id, pm2.meta_value as start_time, pm3.meta_value as end_time, p.post_title as title
                    FROM $wpdb->postmeta pm
                    LEFT JOIN $wpdb->postmeta pm1 ON pm.post_id=pm1.post_id AND pm1.meta_key = 'global_lap_number'
                    LEFT JOIN $wpdb->postmeta pm2 ON pm.post_id=pm2.post_id AND pm2.meta_key = 'start_time'
                    LEFT JOIN $wpdb->postmeta pm3 ON pm.post_id=pm3.post_id AND pm3.meta_key = 'end_time'
                    LEFT JOIN $wpdb->posts p ON pm.post_id=p.ID
                    WHERE pm.meta_key = 'prayer_app_global_magic_key' AND pm.meta_value = %s",
        $key
    ), ARRAY_A);

    if ( empty( $result ) ) {
        $lap = false;
    } else {
        $ongoing = false;
        if ( empty( $result['end_time'] ) ) {
            $result['end_time'] = time();
            $ongoing = true;
        }
        $lap = [
            'title' => $result['title'],
            'lap_number' => (int) $result['lap_number'],
            'post_id' => (int) $result['post_id'],
            'key' => $result['lap_key'],
            'start_time' => (int) $result['start_time'],
            'end_time' => (int) $result['end_time'],
            'on_going' => $ongoing
        ];
    }

//    wp_cache_set( __METHOD__.$key, $lap );

    return $lap;
}
function pg_get_custom_lap_by_post_id( $post_id ) {

//    if ( wp_cache_get( __METHOD__. $post_id ) ) {
//        return wp_cache_get( __METHOD__. $post_id );
//    }

    global $wpdb;
    $result = $wpdb->get_row( $wpdb->prepare(
        "SELECT pm4.meta_value as lap_key, pm1.meta_value as lap_number, pm.post_id, pm2.meta_value as start_time, pm3.meta_value as end_time, p.post_title as title
                    FROM $wpdb->postmeta pm
                    LEFT JOIN $wpdb->postmeta pm1 ON pm.post_id=pm1.post_id AND pm1.meta_key = 'global_lap_number'
                    LEFT JOIN $wpdb->postmeta pm2 ON pm.post_id=pm2.post_id AND pm2.meta_key = 'start_time'
                    LEFT JOIN $wpdb->postmeta pm3 ON pm.post_id=pm3.post_id AND pm3.meta_key = 'end_time'
                    LEFT JOIN $wpdb->postmeta pm4 ON pm.post_id=pm4.post_id AND pm4.meta_key = 'prayer_app_custom_magic_key'
                    LEFT JOIN $wpdb->posts p ON pm.post_id=p.ID
                    WHERE pm.post_id = %d",
        $post_id
    ), ARRAY_A);

    if ( empty( $result ) ) {
        $lap = false;
    } else {
        $ongoing = false;
        if ( empty( $result['end_time'] ) ) {
            $result['end_time'] = time();
            $ongoing = true;
        }
        $lap = [
            'title' => $result['title'],
            'lap_number' => (int) $result['lap_number'],
            'post_id' => (int) $result['post_id'],
            'key' => $result['lap_key'],
            'start_time' => (int) $result['start_time'],
            'end_time' => (int) $result['end_time'],
            'on_going' => $ongoing
        ];
    }

//    wp_cache_set( __METHOD__.$post_id, $lap );

    return $lap;
}

function pg_get_global_lap_by_lap_number( $lap_number ) {

//    if ( wp_cache_get( __METHOD__.$lap_number ) ) {
//        return wp_cache_get( __METHOD__.$lap_number );
//    }

    global $wpdb;
    $result = $wpdb->get_row( $wpdb->prepare(
        "SELECT pm.meta_value as lap_number, pm1.meta_value as lap_key, pm.post_id, pm2.meta_value as start_time, pm3.meta_value as end_time, p.post_title as title
                    FROM $wpdb->postmeta pm
                    LEFT JOIN $wpdb->postmeta pm1 ON pm.post_id=pm1.post_id AND pm1.meta_key = 'prayer_app_global_magic_key'
                    LEFT JOIN $wpdb->postmeta pm2 ON pm.post_id=pm2.post_id AND pm2.meta_key = 'start_time'
                    LEFT JOIN $wpdb->postmeta pm3 ON pm.post_id=pm3.post_id AND pm3.meta_key = 'end_time'
                    LEFT JOIN $wpdb->posts p ON pm.post_id=p.ID
                    WHERE pm.meta_key = 'global_lap_number' AND pm.meta_value = %d",
        $lap_number
    ), ARRAY_A);

    if ( empty( $result ) ) {
        $lap = false;
    } else {
        $ongoing = false;
        if ( empty( $result['end_time'] ) ) {
            $result['end_time'] = time();
            $ongoing = true;
        }
        $lap = [
            'title' => $result['title'],
            'lap_number' => (int) $result['lap_number'],
            'post_id' => (int) $result['post_id'],
            'key' => $result['lap_key'],
            'start_time' => (int) $result['start_time'],
            'end_time' => (int) $result['end_time'],
            'on_going' => $ongoing
        ];
    }

//    wp_cache_set( __METHOD__.$lap_number, $lap );

    return $lap;
}
function pg_global_stats_by_lap_number( $lap_number ) {
    $data = pg_get_global_lap_by_lap_number( $lap_number );
    _pg_global_stats_builder_query( $data );
    return _pg_stats_builder( $data );
}
function pg_global_stats_by_key( $key ) {
    $data = pg_get_global_lap_by_key( $key );
    _pg_global_stats_builder_query( $data );
    return _pg_stats_builder( $data );
}
function pg_get_global_race(){

//    if ( wp_cache_get( __METHOD__ ) ) {
//        return wp_cache_get( __METHOD__ );
//    }

    global $wpdb;
    $result = $wpdb->get_row(
        "SELECT pm.meta_value as lap_number, pm1.meta_value as lap_key, pm.post_id, pm2.meta_value as start_time, pm3.meta_value as end_time, p.post_title as title
            FROM $wpdb->postmeta pm
            LEFT JOIN $wpdb->postmeta pm1 ON pm.post_id=pm1.post_id AND pm1.meta_key = 'prayer_app_global_magic_key'
            LEFT JOIN $wpdb->postmeta pm2 ON pm.post_id=pm2.post_id AND pm2.meta_key = 'start_time'
            LEFT JOIN $wpdb->postmeta pm3 ON pm.post_id=pm3.post_id AND pm3.meta_key = 'end_time'
            LEFT JOIN $wpdb->posts p ON pm.post_id=p.ID
            WHERE pm.meta_key = 'global_lap_number' AND pm.meta_value = '1'", ARRAY_A); // queries the first global lap

    $lap = [
        'title' => $result['title'],
        'lap_number' => (int) $result['lap_number'],
        'post_id' => (int) $result['post_id'],
        'key' => $result['lap_key'],
        'start_time' => (int) $result['start_time'],
        'end_time' => time(), // current time is the end of the query
        'on_going' => true,
    ];

//    wp_cache_set( __METHOD__, $lap );

    return $lap;
}

function pg_global_race_stats() {
    $current_lap = pg_current_global_lap();
    $data = pg_get_global_race();
    $data['number_of_laps'] = $current_lap['lap_number'];
    _pg_global_stats_builder_query( $data );
    return _pg_stats_builder( $data );
}
function _pg_global_stats_builder_query( &$data ) {
    global $wpdb;
    $counts = $wpdb->get_row( $wpdb->prepare( "
       SELECT SUM(r.value) as minutes_prayed, COUNT( DISTINCT( r.grid_id ) ) as locations_completed, COUNT( DISTINCT( r.hash ) ) as participants, COUNT(DISTINCT(r.label)) as participant_country_count
       FROM $wpdb->dt_reports r
        WHERE r.post_type = 'laps'
            AND r.type = 'prayer_app'
       AND r.timestamp >= %d AND r.timestamp <= %d
    ", $data['start_time'], $data['end_time'] ), ARRAY_A );
    $data['locations_completed'] = (int) $counts['locations_completed'];
    $data['participants'] = (int) $counts['participants'];
    $data['minutes_prayed'] = (int) $counts['minutes_prayed'];
    $data['participant_country_count'] = (int) $counts['participant_country_count'];

    return $data;
}
function pg_custom_lap_stats_by_post_id( $post_id ) {
    $data = pg_get_custom_lap_by_post_id( $post_id );
    _pg_custom_stats_builder_query( $data );
    return _pg_stats_builder( $data );
}
function _pg_custom_stats_builder_query( &$data ) {
    global $wpdb;
    $counts = $wpdb->get_row( $wpdb->prepare( "
       SELECT SUM(r.value) as minutes_prayed, COUNT( DISTINCT( r.grid_id ) ) as locations_completed, COUNT( DISTINCT( r.hash ) ) as participants, COUNT(DISTINCT(r.label)) as participant_country_count
       FROM $wpdb->dt_reports r
        WHERE r.post_type = 'laps'
            AND r.type = 'prayer_app'
       AND r.subtype = 'custom' AND r.post_id = %d
    ", $data['post_id'] ), ARRAY_A );

    $data['locations_completed'] = (int) $counts['locations_completed'];
    $data['participants'] = (int) $counts['participants'];
    $data['minutes_prayed'] = (int) $counts['minutes_prayed'];
    $data['participant_country_count'] = (int) $counts['participant_country_count'];

    return $data;
}

function _pg_stats_builder( $data ) : array {
//    dt_write_log(__METHOD__);
    /**
     * TIME CALCULATIONS
     */
    $now = $data['end_time'];
    $time_difference = $now - $data['start_time'];
    _pg_format_duration( $data, $time_difference, 'time_elapsed', 'time_elapsed_small' );

    $prayer_speed = (int) $time_difference !== 0 ? (int) $data['locations_completed'] / $time_difference : 0;
    $locations_per_hour = $prayer_speed * 60 * 60;
    $locations_per_day = $locations_per_hour * 24;
    $data['locations_per_hour'] = $locations_per_hour < 1 && $locations_per_hour !== 0 ? number_format( $locations_per_hour, 2 ) : number_format( $locations_per_hour );
    $data['locations_per_day'] = $locations_per_day < 1 && $locations_per_day !== 0 ? number_format( $locations_per_day, 2 ) : number_format( $locations_per_day );

    if ( $data['on_going'] === false ) {
        $time_remaining = $data['end_time'] - $now;
        _pg_format_duration( $data, $time_remaining, 'time_remaining', 'time_remaining_small' );

        $locations_remaining = PG_TOTAL_STATES - (int) $data['locations_completed'];
        $needed_prayer_speed = $time_remaining !== 0 ? $locations_remaining / $time_remaining : 0;
        $locations_per_hour = $needed_prayer_speed * 60 * 60;
        $locations_per_day = $locations_per_hour * 24;
        $data['needed_locations_per_hour'] = $locations_per_hour < 1 && $locations_per_hour !== 0 ? number_format( $locations_per_hour, 2 ) : number_format( $locations_per_hour );
        $data['needed_locations_per_day'] = $locations_per_day < 1 && $locations_per_day !== 0 ? number_format( $locations_per_day, 2 ) : number_format( $locations_per_day );
    }
    /**
     * QUANTITY OF PRAYER
     */
    $minutes_prayed = (int) $data['minutes_prayed'];
    $data['minutes_prayed'] = number_format( $minutes_prayed );
    $data['minutes_prayed_int'] = $minutes_prayed;
    $seconds_prayed = $minutes_prayed * 60;
    _pg_format_duration( $data, $seconds_prayed, 'minutes_prayed_formatted', 'minutes_prayer_formatted_small' );

    /**
     * COMPLETED & REMAINING
     */
    $completed = (int) $data['locations_completed'];
    $data['completed'] = number_format( $completed );
    $data['completed_int'] = $completed;
    $completed_percent = ROUND( $completed / PG_TOTAL_STATES * 100, 0 );
    if ( 100 < $completed_percent ) {
        $completed_percent = 100;
    }
    $data['completed_percent'] = $completed_percent;
    $data['remaining'] = number_format( PG_TOTAL_STATES - $completed );
    $data['remaining_int'] = PG_TOTAL_STATES - $completed;
    $data['remaining_percent'] = 100 - $data['completed_percent'];

    /**
     * PARTICIPANTS
     */
    $participants = (int) $data['participants'];
    $data['participants'] = number_format( $participants );
    $data['participants_int'] = $participants;

    $data['start_time_formatted'] = gmdate( 'M d, Y', $data['start_time'] );
    $data['end_time_formatted'] = gmdate( 'M d, Y', $data['end_time'] );

//    dt_write_log(__METHOD__);
//    dt_write_log($data);
    return $data;
}

function _pg_format_duration( &$data, $time, $key_long, $key_short ) {

    if ( $time === 0 ) {
        $data[$key_long] = "--";
        $data[$key_short] = "--";
        return;
    }
    $days = floor( $time / 60 / 60 / 24 );
    $hours = floor( ( $time / 60 / 60 ) - ( $days * 24 ) );
    $minutes = floor( ( $time / 60 ) - ( $hours * 60 ) - ( $days * 24 * 60 ) );
    if ( empty( $days ) && empty( $hours ) ){
        $data[$key_long] = "$minutes minutes";
        $data[$key_short] = $minutes." min";
    }
    else if ( empty( $days ) ) {
        $data[$key_long] = "$hours hours, $minutes minutes";
        $data[$key_short] = $hours."h, ".$minutes."m";
    }
    else if ( $days > 365 ) {
        $years = floor( $time / 60 / 60 / 24 / 365 );
        $data[$key_long] = "$years years, $days days, $hours hours, $minutes minutes";
        $data[$key_short] = $years."y, ".$days."d, ".$hours."h, ".$minutes."m";
    }
    else {
        $data[$key_long] = "$days days, $hours hours, $minutes minutes";
        $data[$key_short] = $days."d, ".$hours."h, ".$minutes."m";
    }
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
    if ( ! empty( $raw_list ) ) {
        foreach ( $raw_list as $item ) {
            $list[$item] = $item;
        }
    }

    set_transient( __METHOD__, $list, 60 *60 *12 );

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

function pg_grid_image_url() {
    $fields = pg_fields();
    return trailingslashit( $fields['image_asset_url']['value'] ) . 'location-grid-images/v1/grid/';
}
function pg_jp_image_url() {
    $fields = pg_fields();
    return trailingslashit( $fields['image_asset_url']['value'] ) . 'location-grid-images/v1/jp/';
}
function pg_jp_image( $type, $id ) {
    $base_url = pg_jp_image_url();
    $image_list = pg_jp_images_json();

    switch ( $type ) {
        case 'pid3':
            if ( isset( $image_list['pid3'][$id] ) ) {
                return $base_url . 'pid3/' . $image_list['pid3'][$id];
            } else {
                return false;
            }
        case 'progress':
            if ( isset( $image_list['progress'][$id] ) ) {
                return $base_url . 'progress/' . $image_list['progress'][$id];
            } else {
                return false;
            }
        default:
            return false;
    }
}
function pg_grid_json_url() {
    $fields = pg_fields();
    return trailingslashit( $fields['image_asset_url']['value'] ) . 'location-grid-images/v1/grid.json';
}
function pg_jp_json_url() {
    $fields = pg_fields();
    return trailingslashit( $fields['image_asset_url']['value'] ) . 'location-grid-images/v1/jp.json';
}

/**
 * Returns the full array db of images in the location-grid-images file store.
 * @param $grid_id
 * @param $full_urls
 * @return array|false|mixed|void
 */
function pg_images( $grid_id = null, $full_urls = false ) {
    $image_list = pg_grid_images_json();

    // full list
    if ( is_null( $grid_id ) ) {
        if ( $full_urls ) {
            $base_url = pg_grid_image_url();
            unset( $image_list['version'] );
            foreach ( $image_list as $i0 => $v0 ) {
                foreach ( $v0 as $i1 => $v1 ) {
                    foreach ( $v1 as $i2 => $v2 ) {
                        $image_list[$i0][$i1][$i2] = $base_url . $i0 .'/'. $i1 . '/' . $v2;
                    }
                }
            }
        }
        return $image_list;
    }

    // single grid_id
    if ( $full_urls ) {
        $base_url = pg_grid_image_url();
        foreach ( $image_list[$grid_id] as $i1 => $v1 ) {
            foreach ( $v1 as $i2 => $v2 ) {
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

function pg_is_lap_complete( $post_id ) {
    $complete = get_post_meta( $post_id, 'lap_completed', true );
    if ( ! $complete ) {
        global $wpdb;
        $count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT( DISTINCT( grid_id ) ) FROM $wpdb->dt_reports WHERE post_id = %d AND type = 'prayer_app' AND subtype = 'custom'", $post_id ) );
        if ( $count >= PG_TOTAL_STATES ){
            update_post_meta( $post_id, 'lap_completed', time() );
            return true;
        } else {
            return false;
        }
    } else {
        return true;
    }
}
