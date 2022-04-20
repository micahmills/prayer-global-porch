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

    public static function build_location_stack( $grid_id ) {
        // get queries
        $stack = self::_stack_query( $grid_id );
        /**
         * $stack = [
         *  'location' => [], (array) grid_record
         *  'cities' => [], (array) list of cities
         *  'people_groups' => [], (array) list of people_groups
         * ]
         */

        // build full stack
        $stack['list'] = [];

        self::_faith_status( $stack );
        self::_photos( $stack );
        self::_population_change( $stack );
        self::_least_reached( $stack );


        $list = $stack['list'];
        shuffle($list);
        $stack['list'] = $list;

        self::_demographics( $stack );

        self::_cities( $stack );
        self::_people_groups( $stack );



        // @todo  prioritize limit number of items

        return $stack;
    }

    public static function _demographics( &$stack ) {

        $demographics = [];

        $demographics[] = [
            'type' => '4_fact_blocks',
            'data' => [
                'section_label' => 'Demographics',
                'focus_label' => $stack['location']['full_name'],
                'label_1' => 'Population',
                'value_1' => $stack['location']['population'],
                'size_1' => 'two-em',
                'label_2' => 'Population Growth',
                'value_2' => $stack['location']['population_growth_status'],
                'size_2' => 'two-em',
                'label_3' => 'Dominant Religion',
                'value_3' => $stack['location']['primary_religion'],
                'size_3' => 'two-em',
                'label_4' => 'Language',
                'value_4' => $stack['location']['primary_language'],
                'size_4' => 'two-em',
                'section_summary' => '',
                'prayer' => ''
            ]
        ];
//        $demographics[] = [
//            'type' => 'demographics',
//            'data' => [
//                'section_label' => $stack['location']['full_name'],
//                'focus_label' => '',
//                'label_1' => 'Population',
//                'value_1' => $stack['location']['population'],
//                'size_1' => 'two-em',
//                'label_2' => 'Population Growth',
//                'value_2' => $stack['location']['population_growth_status'],
//                'size_2' => 'two-em',
//                'label_3' => 'Dominant Religion',
//                'value_3' => $stack['location']['primary_religion'],
//                'size_3' => 'two-em',
//                'label_4' => 'Language',
//                'value_4' => $stack['location']['primary_language'],
//                'size_4' => 'two-em',
//                'label_circle_1' => "Don't Know Jesus",
//                'percent_circle_1' => $stack['location']['percent_non_christians'],
//                'population_circle_1' => $stack['location']['non_christians'],
//                'label_circle_2' => 'Know About Jesus',
//                'percent_circle_2' => $stack['location']['percent_christian_adherents'],
//                'population_circle_2' => $stack['location']['christian_adherents'],
//                'label_circle_3' => 'Know Jesus',
//                'percent_circle_3' => $stack['location']['percent_believers'],
//                'population_circle_3' => $stack['location']['believers'],
//                'section_summary' => '',
//                'prayer' => ''
//            ]
//        ];

        $demographics[] = [
            'type' => 'content_block',
            'data' => [
                'section_label' => 'Demographics',
                'focus_label' => $stack['location']['full_name'],
                'icon' => 'ion-map',
                'color' => 'green',
                'section_summary' => 'The '.$stack['location']['admin_level_name'].' of <strong>'.$stack['location']['full_name'].'</strong> has a population of <strong>'.$stack['location']['population'].'</strong> and is 1 of '.$stack['location']['peer_locations'].' '.$stack['location']['admin_level_name_plural'].' in '.$stack['location']['parent_name'].'. We estimate '.$stack['location']['name'].' has <strong>'.$stack['location']['believers'].'</strong> people who might know Jesus, <strong>'.$stack['location']['christian_adherents'].'</strong> people who might know about Jesus culturally, and <strong>'.$stack['location']['non_christians'].'</strong> people who do not know Jesus.',
            ]
        ];
        $d = $demographics[rand(0,count($demographics)-1)];
        $stack['list'] = array_merge( [ $d ], $stack['list'] );

        return $stack;
    }

    public static function _faith_status( &$stack ) {

        $faith_status = [];

        $faith_status[] = [
            'type' => 'percent_3_circles',
            'data' => [
                'section_label' => 'Faith Status',
                'label_1' => "Don't Know Jesus",
                'percent_1' => $stack['location']['percent_non_christians'],
                'population_1' => $stack['location']['non_christians'],
                'label_2' => 'Know About Jesus',
                'percent_2' => $stack['location']['percent_christian_adherents'],
                'population_2' => $stack['location']['christian_adherents'],
                'label_3' => 'Know Jesus',
                'percent_3' => $stack['location']['percent_believers'],
                'population_3' => $stack['location']['believers'],
                'section_summary' => '',
                'prayer' => 'Pray that the '.$stack['location']['believers'].' believers in '.$stack['location']['full_name'].' to be bold witnesses to the '.$stack['location']['non_christians'].' lost neighbors around them.',
            ]
        ];
        if ( $stack['location']['percent_non_christians'] > $stack['location']['percent_christian_adherents']) {
            $faith_status[] = [
                'type' => 'percent_2_circles',
                'data' => [
                    'section_label' => 'Faith Status',
                    'label_1' => "Don't Know Jesus",
                    'percent_1' => $stack['location']['percent_non_christians'],
                    'population_1' => $stack['location']['non_christians'],
                    'color_1' => 'red',
                    'label_2' => 'Know Jesus',
                    'percent_2' => $stack['location']['percent_believers'],
                    'population_2' => $stack['location']['believers'],
                    'section_summary' => '',
                    'prayer' => 'Pray that the '.$stack['location']['believers'].' believers in '.$stack['location']['full_name'].' to be bold witnesses to the '.$stack['location']['non_christians'].' lost neighbors around them.',
                ]
            ];
        }
       else {
           $faith_status[] = [
               'type' => 'percent_2_circles',
               'data' => [
                   'section_label' => 'Faith Status',
                   'label_1' => "Know About Jesus",
                   'percent_1' => $stack['location']['percent_christian_adherents'],
                   'population_1' => $stack['location']['christian_adherents'],
                   'color_1' => 'orange',
                   'label_2' => 'Know Jesus',
                   'percent_2' => $stack['location']['percent_believers'],
                   'population_2' => $stack['location']['believers'],
                   'section_summary' => '',
                   'prayer' => 'Pray that the '.$stack['location']['believers'].' believers in '.$stack['location']['full_name'].' to be bold witnesses to the '.$stack['location']['non_christians'].' lost neighbors around them.',
               ]
           ];
       }

        $faith_status[] = [
            'type' => 'percent_3_bar',
            'data' => [
                'section_label' => 'Faith Status',
                'label_1' => "Don't",
                'percent_1' => $stack['location']['percent_non_christians'],
                'population_1' => $stack['location']['non_christians'],
                'label_2' => 'Know About',
                'percent_2' => $stack['location']['percent_christian_adherents'],
                'population_2' => $stack['location']['christian_adherents'],
                'label_3' => 'Know',
                'percent_3' => $stack['location']['percent_believers'],
                'population_3' => $stack['location']['believers'],
                'section_summary' => '',
                'prayer' => 'Pray that the '.$stack['location']['believers'].' believers in '.$stack['location']['full_name'].' to be bold witnesses to the '.$stack['location']['non_christians'].' lost neighbors around them.',
            ]
        ];
        $faith_status[] = [
            'type' => '100_bodies_chart',
            'data' => [
                'section_label' => 'Faith Status',
                'percent_1' => $stack['location']['percent_non_christians'],
                'percent_2' => $stack['location']['percent_christian_adherents'],
                'percent_3' => $stack['location']['percent_believers'],
                'section_summary' => '',
                'prayer' => 'Pray that the '.$stack['location']['believers'].' believers in '.$stack['location']['full_name'].' to be bold witnesses to the '.$stack['location']['non_christians'].' lost neighbors around them.',
            ]
        ];
        $faith_status[] = [
            'type' => '100_bodies_3_chart',
            'data' => [
                'section_label' => 'Faith Status',
                'label_1' => "Don't know Jesus",
                'percent_1' => $stack['location']['percent_non_christians'],
                'population_1' => $stack['location']['non_christians'],
                'label_2' => "Know about Jesus",
                'percent_2' => $stack['location']['percent_christian_adherents'],
                'population_2' => $stack['location']['christian_adherents'],
                'label_3' => "Know Jesus",
                'percent_3' => $stack['location']['percent_believers'],
                'population_3' => $stack['location']['believers'],
                'section_summary' => '',
                'prayer' => 'Pray that the '.$stack['location']['believers'].' believers in '.$stack['location']['full_name'].' to be bold witnesses to the '.$stack['location']['non_christians'].' lost neighbors around them.',
            ]
        ];

        $stack['list'][] = $faith_status[rand(0,count($faith_status)-1)];

        return $stack;
    }

    public static function _population_change( &$stack ) {

        // @todo evalute this priority strategy.
        $status = [];
        for ($i = 1; $i <= $stack['location']['percent_christian_adherents']; $i++) {
            $status[] = 'christian_adherents';
        }
        for ($i = 1; $i <= $stack['location']['percent_non_christians']; $i++) {
            $status[] = 'non_christians';
        }
//        for ($i = 1; $i <= $stack['location']['percent_believers']; $i++) {
//            $status[] = 'believers';
//        }
        $favor_status = $status[rand(0,count($status)-1)];

        $types = ['births', 'deaths' ];
        $type = $types[rand(0,1)];

        // deaths non christians
        if ( 'non_christians' === $favor_status && 'deaths' === $type ) {
            $deaths_non_christians = [];
            if ($stack['location']['deaths_non_christians_next_hour']) {
                $deaths_non_christians[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Dying without Jesus in the next hour',
                        'count' => $stack['location']['deaths_non_christians_next_hour'],
                        'group' => 'non_christians',
                        'type' => 'deaths',
                        'size' => ($stack['location']['deaths_non_christians_next_hour'] > 400) ? 2 : 3,
                        'section_summary' => '',
                        'prayer' => 'Pray for the ' . $stack['location']['deaths_non_christians_next_hour'] . ' people who are dying without Jesus in the next hour. Pray they get one chance to hear the gospel, or if they have heard it that the Spirit would bring it to mind one more time.'
                    ]
                ];
            }
            if ($stack['location']['deaths_non_christians_next_100']) {
                $deaths_non_christians[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Dying without Jesus in the next 100 hours',
                        'count' => $stack['location']['deaths_non_christians_next_100'],
                        'group' => 'non_christians',
                        'type' => 'deaths',
                        'size' => ($stack['location']['deaths_non_christians_next_100'] > 400) ? 2 : 3,
                        'section_summary' => '',
                        'prayer' => 'Pray for the ' . $stack['location']['deaths_non_christians_next_100'] . ' people who are dying without Jesus in the next 100 hours. Pray they get one chance to hear the gospel, or if they have heard it that the Spirit would bring it to mind one more time.'
                    ]
                ];
            }
            if ($stack['location']['deaths_non_christians_next_week']) {
                $deaths_non_christians[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Dying without Jesus in the next week',
                        'count' => $stack['location']['deaths_non_christians_next_week'],
                        'group' => 'non_christians',
                        'type' => 'deaths',
                        'size' => ($stack['location']['deaths_non_christians_next_week'] > 400) ? 2 : 3,
                        'section_summary' => '',
                        'prayer' => 'Pray for the ' . $stack['location']['deaths_non_christians_next_week'] . ' people who are dying without Jesus in the next week. Pray they get one chance to hear the gospel, or if they have heard it that the Spirit would bring it to mind one more time.'
                    ]
                ];
            }
            if ($stack['location']['deaths_non_christians_next_month']) {
                $deaths_non_christians[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Dying without Jesus in the next month',
                        'count' => $stack['location']['deaths_non_christians_next_month'],
                        'group' => 'non_christians',
                        'type' => 'deaths',
                        'size' => ($stack['location']['deaths_non_christians_next_month'] > 400) ? 2 : 3,
                        'section_summary' => '',
                        'prayer' => 'Pray for the ' . $stack['location']['deaths_non_christians_next_month'] . ' people who are dying without Jesus in the next month. Pray they get one chance to hear the gospel, or if they have heard it that the Spirit would bring it to mind one more time.'
                    ]
                ];
            }
            $stack['list'][] = $deaths_non_christians[rand(0, count($deaths_non_christians) - 1)];
        }

            // births non christians
        if ( 'non_christians' === $favor_status && 'births' === $type ) {
            $births_non_christians = [];
            if ( $stack['location']['births_non_christians_last_hour'] ) {
                $births_non_christians[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Born to a family without Jesus in the next hour',
                        'count' => $stack['location']['births_non_christians_last_hour'],
                        'group' => 'non_christians',
                        'type' => 'births',
                        'size' => ( $stack['location']['births_non_christians_last_hour'] > 400 ) ? 2 : 3,
                        'section_summary' => '',
                        'prayer' => 'Pray for the ' . $stack['location']['births_non_christians_last_hour'] . ' babies born in the next hour to families who are far from God.'
                    ]
                ];
            }
            if ( $stack['location']['births_non_christians_last_100'] ) {
                $births_non_christians[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Born to a family without Jesus in the next 100 hours',
                        'count' => $stack['location']['births_non_christians_last_100'],
                        'group' => 'non_christians',
                        'type' => 'births',
                        'size' => ( $stack['location']['births_non_christians_last_100'] > 400 ) ? 2 : 3,
                        'section_summary' => '',
                        'prayer' => 'Pray for the ' . $stack['location']['births_non_christians_last_100'] . ' babies born in the next hour to families who are far from God.'
                    ]
                ];
            }
            if ( $stack['location']['births_non_christians_last_week'] ) {
                $births_non_christians[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Born to a family without Jesus in the next week',
                        'count' => $stack['location']['births_non_christians_last_week'],
                        'group' => 'non_christians',
                        'type' => 'births',
                        'size' => ( $stack['location']['births_non_christians_last_week'] > 400 ) ? 2 : 3,
                        'section_summary' => '',
                        'prayer' => 'Pray for the ' . $stack['location']['births_non_christians_last_week'] . ' babies born in the next hour to families who are far from God.'
                    ]
                ];
            }
            if ( $stack['location']['births_non_christians_last_month'] ) {
                $births_non_christians[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Born to a family without Jesus in the next month',
                        'count' => $stack['location']['births_non_christians_last_month'],
                        'group' => 'non_christians',
                        'type' => 'births',
                        'size' => ( $stack['location']['births_non_christians_last_month'] > 400 ) ? 2 : 3,
                        'section_summary' => '',
                        'prayer' => 'Pray for the ' . $stack['location']['births_non_christians_last_month'] . ' babies born in the next hour to families who are far from God.'
                    ]
                ];
            }
            $stack['list'][] = $births_non_christians[rand(0,count($births_non_christians)-1)];
        }


        if ( 'christian_adherents' === $favor_status && 'deaths' === $type ) {

            // deaths christian adherents
            $deaths_christian_adherents = [];
            if ($stack['location']['deaths_christian_adherents_next_hour']) {
                $deaths_christian_adherents[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Dying in the next hour',
                        'count' => $stack['location']['deaths_christian_adherents_next_hour'],
                        'group' => 'christian_adherents',
                        'type' => 'deaths',
                        'size' => ($stack['location']['deaths_christian_adherents_next_hour'] > 400) ? 2 : 3,
                        'section_summary' => '',
                        'prayer' => 'Pray for the ' . $stack['location']['deaths_christian_adherents_next_hour'] . ' people who are dying without a personal relationship with Jesus in the next hour.'
                    ]
                ];
            }
            if ($stack['location']['deaths_christian_adherents_next_100']) {
                $deaths_christian_adherents[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Dying in the next 100 hours',
                        'count' => $stack['location']['deaths_christian_adherents_next_100'],
                        'group' => 'christian_adherents',
                        'type' => 'deaths',
                        'size' => ($stack['location']['deaths_christian_adherents_next_100'] > 400) ? 2 : 3,
                        'section_summary' => '',
                        'prayer' => 'Pray for the ' . $stack['location']['deaths_christian_adherents_next_100'] . ' people who are dying without a personal relationship with Jesus in the next hour.'
                    ]
                ];
            }
            if ($stack['location']['deaths_christian_adherents_next_week']) {
                $deaths_christian_adherents[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Dying in the next week',
                        'count' => $stack['location']['deaths_christian_adherents_next_week'],
                        'group' => 'christian_adherents',
                        'type' => 'deaths',
                        'size' => ($stack['location']['deaths_christian_adherents_next_week'] > 400) ? 2 : 3,
                        'section_summary' => '',
                        'prayer' => 'Pray for the ' . $stack['location']['deaths_christian_adherents_next_week'] . ' people who are dying without a personal relationship with Jesus in the next hour.'
                    ]
                ];
            }
            if ($stack['location']['deaths_christian_adherents_next_month']) {
                $deaths_christian_adherents[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Dying in the next month',
                        'count' => $stack['location']['deaths_christian_adherents_next_month'],
                        'group' => 'christian_adherents',
                        'type' => 'deaths',
                        'size' => ($stack['location']['deaths_christian_adherents_next_month'] > 400) ? 2 : 3,
                        'section_summary' => '',
                        'prayer' => 'Pray for the ' . $stack['location']['deaths_christian_adherents_next_month'] . ' people who are dying without a personal relationship with Jesus in the next hour.'
                    ]
                ];
            }
            $stack['list'][] = $deaths_christian_adherents[rand(0, count($deaths_christian_adherents) - 1)];
        }

            // births christian adherents
        if ( 'christian_adherents' === $favor_status && 'births' === $type ) {
            $births_christian_adherents = [];
            if ( $stack['location']['births_christian_adherents_last_hour'] ) {
                $births_christian_adherents[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Born in the next hour',
                        'count' => $stack['location']['births_christian_adherents_last_hour'],
                        'group' => 'christian_adherents',
                        'type' => 'births',
                        'size' => ( $stack['location']['births_christian_adherents_last_hour'] > 400 ) ? 2 : 3,
                        'section_summary' => '',
                        'prayer' => 'Pray for the ' . $stack['location']['births_christian_adherents_last_hour'] . ' babies born in the next hour to families who might now about God culturally, but likely have no relationship with Jesus.'
                    ]
                ];
            }
            if ( $stack['location']['births_christian_adherents_last_100'] ) {
                $births_christian_adherents[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Born in the next 100 hours',
                        'count' => $stack['location']['births_christian_adherents_last_100'],
                        'group' => 'christian_adherents',
                        'type' => 'births',
                        'size' => ( $stack['location']['births_christian_adherents_last_100'] > 400 ) ? 2 : 3,
                        'section_summary' => '',
                        'prayer' => 'Pray for the ' . $stack['location']['births_christian_adherents_last_100'] . ' babies born in the next hour to families who might now about God culturally, but likely have no relationship with Jesus.'
                    ]
                ];
            }
            if ( $stack['location']['births_christian_adherents_last_week'] ) {
                $births_christian_adherents[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Born in the next week',
                        'count' => $stack['location']['births_christian_adherents_last_week'],
                        'group' => 'christian_adherents',
                        'type' => 'births',
                        'size' => ( $stack['location']['births_christian_adherents_last_week'] > 400 ) ? 2 : 3,
                        'section_summary' => '',
                        'prayer' => 'Pray for the ' . $stack['location']['births_christian_adherents_last_week'] . ' babies born in the next hour to families who might now about God culturally, but likely have no relationship with Jesus.'
                    ]
                ];
            }
            if ( $stack['location']['births_christian_adherents_last_month'] ) {
                $births_christian_adherents[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Born in the next month',
                        'count' => $stack['location']['births_christian_adherents_last_month'],
                        'group' => 'christian_adherents',
                        'type' => 'births',
                        'size' => ( $stack['location']['births_christian_adherents_last_month'] > 400 ) ? 2 : 3,
                        'section_summary' => '',
                        'prayer' => 'Pray for the ' . $stack['location']['births_christian_adherents_last_month'] . ' babies born in the next hour to families who might now about God culturally, but likely have no relationship with Jesus.'
                    ]
                ];
            }
            $stack['list'][] = $births_christian_adherents[rand(0,count($births_christian_adherents)-1)];
        }

        return $stack;
    }

    public static function _cities( &$stack ) {
        if ( ! empty( $stack['cities'] ) ) {

            // cities list
            $values = [];
            foreach ($stack['cities'] as $city) {
                $values[] = $city['name'] . ' - (pop ' . $city['population'] . ')';
            }
            if (!empty($values)) {
                $stack['list'][] = [
                    'type' => 'bullet_list_2_column',
                    'data' => [
                        'section_label' => 'Top Cities',
                        'values' => $values,
                        'section_summary' => '',
                        'prayer' => 'Pray that the ' . $stack['location']['believers'] . ' believers in ' . $stack['location']['full_name'] . ' to be bold witnesses to the ' . $stack['location']['non_christians'] . ' nominal neighbors around them.'
                    ]
                ];
            }

            // focus block
            $cities = $stack['cities'];
            shuffle($cities);
            if ( isset( $cities[0] ) && ! empty( $cities[0] ) ) {
                if ( $cities[0]['population_int'] > 0 ) {
                    $per_person = 0;
                    if ( $cities[0]['population_int'] > 10000 ) {
                        $per_person = 1000;
                    }
                    else if ( $cities[0]['population_int'] > 100 ) {
                        $per_person = 100;
                    }
                    if ( $per_person ) {
                        $churches_needed = number_format( $cities[0]['population_int'] / $per_person );
                        $content = 'Pray that God raises up '.$churches_needed.' churches in '.$cities[0]['full_name'].' to reach its '.$cities[0]['population'].' citizens.';
                    }
                }
                if ( ! $content ) {
                    $content = 'Pray that God raises up churches in '.$cities[0]['full_name'].' to reach the city of '.$cities[0]['full_name'].'.';
                }
                $stack['list'][] = [
                    'type' => 'content_block',
                    'data' => [
                        'section_label' => 'Key City',
                        'focus_label' => 'Pray for the city of ' . $cities[0]['name'],
                        'icon' => 'ion-map', // ion icons from /pages/fonts/ionicons/
                        'color' => 'green',
                        'section_summary' => $content,
                    ]
                ];
            }
        }
        return $stack;
    }

    public static function _people_groups( &$stack ) {
        if ( ! empty( $stack['people_groups'] ) ) {

            // people group list
            $values = [];
            foreach( $stack['people_groups'] as $city ) {
                $values[] = $city['name'] . ' - (world pop ' . $city['population'] . ')';
            }
            $stack['list'][] = [
                'type' => 'bullet_list_2_column',
                'data' => [
                    'section_label' => 'People Groups In The Area',
                    'values' => $values,
                    'section_summary' => '',
                    'prayer' => 'Pray that the ' . $stack['location']['believers'] . ' believers in ' . $stack['location']['full_name'] . ' to be bold witnesses to the ' . $stack['location']['non_christians'] . ' nominal neighbors around them.'
                ]
            ];

        }
        return $stack;
    }

    public static function _least_reached( &$stack ) {
        if ( ! empty( $stack['people_groups'] ) ) {

            // least reached block
            $name = $description = '';
            $people_groups = $stack['people_groups'];
            shuffle($people_groups);
            foreach( $people_groups as $group ) {
                if ( 'Y' === $group['LeastReached'] ) {
                    $name = $group['name'];
                    $description = 'Joshua Project identifies the '.$group['name'].' people in ' . $stack['location']['full_name'] . ' as a least reached people group. They are classified as '.$group['AffinityBloc'].' and speak '.$group['PrimaryLanguageName'].'. Primarily, they follow '.$group['PrimaryReligion'].' and only '.$group['PercentEvangelical'].'% are suspected of being believers.';
                    break;
                }
            }
            if ( ! empty( $name ) ) {
                $stack['list'][] = [
                    'type' => 'fact_block',
                    'data' => [
                        'section_label' => 'Least Reached',
                        'focus_label' => $name,
                        'icon' => 'ion-android-warning', // ion icons from /pages/fonts/ionicons/
                        'color' => false,
                        'section_summary' => $description,
                        'prayer' => 'Pray that the ' . $stack['location']['believers'] . ' believers in ' . $stack['location']['full_name'] . ' to be bold witnesses to the '.$name.' neighbors near them.'
                    ]
                ];
            }

        }
        return $stack;
    }

    public static function _photos( &$stack ) {
        $images = prayer_global_images( $stack['location']['grid_id'], true );

        if ( ! empty( $images['photos'] ) ) {
            $photo_template = [];

            $rand_index = rand( 0, count( $images['photos'] ) - 1 );
            $photo_template[] = [
                'type' => 'photo_block',
                'data' => [
                    'section_label' => 'Photo from '.$stack['location']['full_name'],
                    'url' => $images['photos'][$rand_index],
                    'section_summary' => '',
                    'prayer' => 'Lord, your eyes have been on '.$stack['location']['full_name'] .' since the first person settled there. Please, make your name known now! Please, wait no longer',
                ]
            ];

            $rand_index = rand( 0, count( $images['photos'] ) - 1 );
            $photo_template[] = [
                'type' => 'photo_block',
                'data' => [
                    'section_label' => 'Photo from '.$stack['location']['full_name'],
                    'url' => $images['photos'][$rand_index],
                    'section_summary' => '',
                    'prayer' => 'What does the Spirit prompt you to pray?',
                ]
            ];
            $stack['list'][] = $photo_template[rand(0,count($photo_template)-1)];
        }

        return $stack;
    }

    public static function _stack_query( $grid_id ) {
        global $wpdb;

        // get record and level
        $grid_record = $wpdb->get_row( $wpdb->prepare( "
            SELECT
              g.grid_id,
              g.name,
              g.population,
              g.latitude,
              g.longitude,
              g.country_code,
              g.admin0_code,
              g.parent_id,
              p.name as parent_name,
              g.admin0_grid_id,
              gc.name as admin0_name,
              g.admin1_grid_id,
              ga1.name as admin1_name,
              g.admin2_grid_id,
              ga2.name as admin2_name,
              g.admin3_grid_id,
              ga3.name as admin3_name,
              g.admin4_grid_id,
              ga4.name as admin4_name,
              g.admin5_grid_id,
              ga5.name as admin5_name,
              g.level,
              g.level_name,
              g.north_latitude,
              g.south_latitude,
              g.east_longitude,
              g.west_longitude,
              p.north_latitude as p_north_latitude,
              p.south_latitude as p_south_latitude,
              p.east_longitude as p_east_longitude,
              p.west_longitude as p_west_longitude,
              gc.north_latitude as c_north_latitude,
              gc.south_latitude as c_south_latitude,
              gc.east_longitude as c_east_longitude,
              gc.west_longitude as c_west_longitude,
              (SELECT count(pl.grid_id) FROM $wpdb->dt_location_grid pl WHERE pl.parent_id = g.parent_id) as peer_locations,
              lgf.birth_rate,
              lgf.death_rate,
              lgf.growth_rate,
              lgf.believers,
              lgf.christian_adherents,
              lgf.non_christians,
              lgf.primary_language,
              lgf.primary_religion,
              lgf.percent_believers,
              lgf.percent_christian_adherents,
              lgf.percent_non_christians
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

        // build full name
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
        $grid_record = array_merge($grid_record, [ 'full_name' => $full_name ] );

        // build the description
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
        $grid_record = array_merge($grid_record, [ 'admin_level_name' => $admin_level_name, 'admin_level_name_plural' => $admin_level_name_plural ] );


        // format
        $grid_record['longitude'] = (float) $grid_record['longitude'];
        $grid_record['latitude'] = (float) $grid_record['latitude'];
        $grid_record['north_latitude'] = (float) $grid_record['north_latitude'];
        $grid_record['south_latitude'] = (float) $grid_record['south_latitude'];
        $grid_record['east_longitude'] = (float) $grid_record['east_longitude'];
        $grid_record['west_longitude'] = (float) $grid_record['west_longitude'];
        $grid_record['p_north_latitude'] = (float) $grid_record['p_north_latitude'];
        $grid_record['p_south_latitude'] = (float) $grid_record['p_south_latitude'];
        $grid_record['p_east_longitude'] = (float) $grid_record['p_east_longitude'];
        $grid_record['p_west_longitude'] = (float) $grid_record['p_west_longitude'];
        $grid_record['c_north_latitude'] = (float) $grid_record['c_north_latitude'];
        $grid_record['c_south_latitude'] = (float) $grid_record['c_south_latitude'];
        $grid_record['c_east_longitude'] = (float) $grid_record['c_east_longitude'];
        $grid_record['c_west_longitude'] = (float) $grid_record['c_west_longitude'];
        $grid_record['birth_rate'] = (float) $grid_record['birth_rate'];
        $grid_record['death_rate'] = (float) $grid_record['death_rate'];
        $grid_record['growth_rate'] = (float) $grid_record['growth_rate'];
        $grid_record['population_int'] = (int) $grid_record['population'];
        $grid_record['population'] = number_format( intval( $grid_record['population'] ) );
        $grid_record['believers_int'] = (int) $grid_record['believers'];
        $grid_record['believers'] = number_format( intval( $grid_record['believers'] ) );
        $grid_record['christian_adherents_int'] = (int) $grid_record['christian_adherents'];
        $grid_record['christian_adherents'] = number_format( intval( $grid_record['christian_adherents'] ) );
        $grid_record['non_christians_int'] = (int) $grid_record['non_christians'];
        $grid_record['non_christians'] = number_format( intval( $grid_record['non_christians'] ) );
        $grid_record['percent_believers_full'] = (float) $grid_record['percent_believers'];
        $grid_record['percent_believers'] = round( (float) $grid_record['percent_believers'], 2);
        $grid_record['percent_christian_adherents_full'] = (float) $grid_record['percent_christian_adherents'];
        $grid_record['percent_christian_adherents'] = round( (float) $grid_record['percent_christian_adherents'], 2 );
        $grid_record['percent_non_christians_full'] = (float) $grid_record['percent_non_christians'];
        $grid_record['percent_non_christians'] = round( (float) $grid_record['percent_non_christians'], 2 );

        // process pace
        $grid_record['population_growth_status'] = self::_get_pace( 'population_growth_status', $grid_record );

        $grid_record['deaths_non_christians_next_hour'] = self::_get_pace( 'deaths_non_christians_next_hour', $grid_record );
        $grid_record['deaths_non_christians_next_100'] = self::_get_pace( 'deaths_non_christians_next_100', $grid_record );
        $grid_record['deaths_non_christians_next_week'] = self::_get_pace( 'deaths_non_christians_next_week', $grid_record );
        $grid_record['deaths_non_christians_next_month'] = self::_get_pace( 'deaths_non_christians_next_month', $grid_record );
        $grid_record['deaths_non_christians_next_year'] = self::_get_pace( 'deaths_non_christians_next_year', $grid_record );

        $grid_record['births_non_christians_last_hour'] = self::_get_pace( 'births_non_christians_last_hour', $grid_record );
        $grid_record['births_non_christians_last_100'] = self::_get_pace( 'births_non_christians_last_100', $grid_record );
        $grid_record['births_non_christians_last_week'] = self::_get_pace( 'births_non_christians_last_week', $grid_record );
        $grid_record['births_non_christians_last_month'] = self::_get_pace( 'births_non_christians_last_month', $grid_record );
        $grid_record['births_non_christians_last_year'] = self::_get_pace( 'births_non_christians_last_year', $grid_record );

        $grid_record['deaths_christian_adherents_next_hour'] = self::_get_pace( 'deaths_christian_adherents_next_hour', $grid_record );
        $grid_record['deaths_christian_adherents_next_100'] = self::_get_pace( 'deaths_christian_adherents_next_100', $grid_record );
        $grid_record['deaths_christian_adherents_next_week'] = self::_get_pace( 'deaths_christian_adherents_next_week', $grid_record );
        $grid_record['deaths_christian_adherents_next_month'] = self::_get_pace( 'deaths_christian_adherents_next_month', $grid_record );
        $grid_record['deaths_christian_adherents_next_year'] = self::_get_pace( 'deaths_christian_adherents_next_year', $grid_record );

        $grid_record['births_christian_adherents_last_hour'] = self::_get_pace( 'births_christian_adherents_last_hour', $grid_record );
        $grid_record['births_christian_adherents_last_100'] = self::_get_pace( 'births_christian_adherents_last_100', $grid_record );
        $grid_record['births_christian_adherents_last_week'] = self::_get_pace( 'births_christian_adherents_last_week', $grid_record );
        $grid_record['births_christian_adherents_last_month'] = self::_get_pace( 'births_christian_adherents_last_month', $grid_record );
        $grid_record['births_christian_adherents_last_year'] = self::_get_pace( 'births_christian_adherents_last_year', $grid_record );


        // build people groups list
        $people_groups = $wpdb->get_results($wpdb->prepare( "
            SELECT lgpg.*, FORMAT(lgpg.population, 0) as population, 'current' as query_level
                FROM $wpdb->location_grid_people_groups lgpg
                WHERE
                    lgpg.longitude < %d AND /* east */
                    lgpg.longitude >  %d AND /* west */
                    lgpg.latitude < %d AND /* north */
                    lgpg.latitude > %d AND /* south */
                    lgpg.admin0_grid_id = %d
                ORDER BY lgpg.LeastReached DESC, lgpg.population DESC
                LIMIT 5
        ", $grid_record['east_longitude'], $grid_record['west_longitude'], $grid_record['north_latitude'], $grid_record['south_latitude'], $grid_record['admin0_grid_id'] ), ARRAY_A );
        if ( empty( $people_groups ) ) {
            $people_groups = $wpdb->get_results($wpdb->prepare( "
                SELECT lgpg.*, FORMAT(lgpg.population, 0) as population, 'parent' as query_level
                    FROM $wpdb->location_grid_people_groups lgpg
                    WHERE
                        lgpg.longitude < %d AND /* east */
                        lgpg.longitude >  %d AND /* west */
                        lgpg.latitude < %d AND /* north */
                        lgpg.latitude > %d AND /* south */
                        lgpg.admin0_grid_id = %d
                    ORDER BY lgpg.LeastReached DESC, lgpg.population DESC
                    LIMIT 5
            ", $grid_record['p_east_longitude'], $grid_record['p_west_longitude'], $grid_record['p_north_latitude'], $grid_record['p_south_latitude'], $grid_record['admin0_grid_id'] ), ARRAY_A );
        }

        // cities
        $cities = $wpdb->get_results($wpdb->prepare( "
            SELECT
                   lgpg.id,
                   lgpg.geonameid,
                   lgpg.name,
                   lgpg.full_name,
                   lgpg.admin0_name,
                   lgpg.latitude,
                   lgpg.longitude,
                   lgpg.timezone,
                   lgpg.population as population_int,
                   FORMAT(lgpg.population, 0) as population
                FROM $wpdb->location_grid_cities lgpg
                WHERE
                    lgpg.longitude < %d AND /* east */
                    lgpg.longitude >  %d AND /* west */
                    lgpg.latitude < %d AND /* north */
                    lgpg.latitude > %d AND /* south */
                    lgpg.admin0_grid_id = %d
                ORDER BY lgpg.population DESC
                LIMIT 5
        ", $grid_record['east_longitude'], $grid_record['west_longitude'], $grid_record['north_latitude'], $grid_record['south_latitude'], $grid_record['admin0_grid_id'] ), ARRAY_A );


        return [
            'location' => $grid_record,
            'cities' => $cities,
            'people_groups' => $people_groups
        ];
    }

    public static function _get_pace( $type, $grid_record ) {
        $return_value = 0;
        $birth_rate = $grid_record['birth_rate'];
        $death_rate = $grid_record['death_rate'];
        $believers = $grid_record['believers_int'];
        $christian_adherents = $grid_record['christian_adherents_int'];
        $non_christians = $grid_record['non_christians_int'];
        $not_believers = $non_christians + $christian_adherents;

        switch( $type ) {
            case 'births_non_christians_last_hour':
                $return_value = ( $birth_rate * ( $not_believers / 1000 ) ) / 365 / 24 ;
                break;
            case 'births_non_christians_last_100':
                $return_value = ( $birth_rate * ( $not_believers / 1000 ) ) / 365 / 24 * 100 ;
                break;
            case 'births_non_christians_last_week':
                $return_value = ( $birth_rate * ( $not_believers / 1000 ) ) / 365 * 7 ;
                break;
            case 'births_non_christians_last_month':
                $return_value = ( $birth_rate * ( $not_believers / 1000 ) ) / 365 * 30 ;
                break;
            case 'births_non_christians_last_year':
                $return_value = ( $birth_rate * ( $not_believers / 1000 ) );
                break;

            case 'deaths_non_christians_next_hour':
                $return_value = ( $death_rate * ( $not_believers / 1000 ) ) / 365 / 24 ;
                break;
            case 'deaths_non_christians_next_100':
                $return_value = ( $death_rate * ( $not_believers / 1000 ) ) / 365 / 24 * 100 ;
                break;
            case 'deaths_non_christians_next_week':
                $return_value = ( $death_rate * ( $not_believers / 1000 ) ) / 365 * 7;
                break;
            case 'deaths_non_christians_next_month':
                $return_value =  ( $death_rate * ( $not_believers / 1000 ) ) / 365 * 30 ;
                break;
            case 'deaths_non_christians_next_year':
                $return_value =  ( $death_rate * ( $not_believers / 1000 ) );
                break;

            case 'births_christian_adherents_last_hour':
                $return_value = ( $birth_rate * ( $christian_adherents / 1000 ) ) / 365 / 24 ;
                break;
            case 'births_christian_adherents_last_100':
                $return_value = ( $birth_rate * ( $christian_adherents / 1000 ) ) / 365 / 24 * 100 ;
                break;
            case 'births_christian_adherents_last_week':
                $return_value = ( $birth_rate * ( $christian_adherents / 1000 ) ) / 365 * 7 ;
                break;
            case 'births_christian_adherents_last_month':
                $return_value = ( $birth_rate * ( $christian_adherents / 1000 ) ) / 365 * 30 ;
                break;
            case 'births_christian_adherents_last_year':
                $return_value = ( $birth_rate * ( $christian_adherents / 1000 ) );
                break;

            case 'deaths_christian_adherents_next_hour':
                $return_value = ( $death_rate * ( $christian_adherents / 1000 ) ) / 365 / 24 ;
                break;
            case 'deaths_christian_adherents_next_100':
                $return_value = ( $death_rate * ( $christian_adherents / 1000 ) ) / 365 / 24 * 100 ;
                break;
            case 'deaths_christian_adherents_next_week':
                $return_value = ( $death_rate * ( $christian_adherents / 1000 ) ) / 365 * 7;
                break;
            case 'deaths_christian_adherents_next_month':
                $return_value =  ( $death_rate * ( $christian_adherents / 1000 ) ) / 365 * 30 ;
                break;
            case 'deaths_christian_adherents_next_year':
                $return_value =  ( $death_rate * ( $christian_adherents / 1000 ) );
                break;

            case 'population_growth_status':
                if ( $grid_record['growth_rate'] >= 1.3 ) {
                    $return_value = 'Fastest Growing in the World';
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
                    $return_value = 'Fastest Declining in the World';
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
            'value' => $data['pace'] ?? 1,
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

    public static function example_templates( $stack ) {
        $stack = [
            'list' => []
        ];
        $stack['list'][] = [
            'type' => '4_fact_blocks',
            'data' => [
                'section_label' => 'Demographics',
                'focus_label' => $stack['location']['full_name'],
                'label_1' => 'Population',
                'value_1' => $stack['location']['population'],
                'size_1' => 'three-em',
                'label_2' => 'Population Growth',
                'value_2' => $stack['location']['population_growth_status'],
                'size_2' => 'two-em',
                'label_3' => 'Dominant Religion',
                'value_3' => $stack['location']['primary_religion'],
                'size_3' => 'two-em',
                'label_4' => 'Language',
                'value_4' => $stack['location']['primary_language'],
                'size_4' => 'two-em',
                'section_summary' => '',
                'prayer' => ''
            ]
        ];
        $stack['list'][] = [
            'type' => 'percent_3_circles',
            'data' => [
                'section_label' => 'Faith Status',
                'label_1' => "Don't Know Jesus",
                'percent_1' => $stack['location']['percent_non_christians'],
                'population_1' => $stack['location']['non_christians'],
                'label_2' => 'Know About Jesus',
                'percent_2' => $stack['location']['percent_christian_adherents'],
                'population_2' => $stack['location']['christian_adherents'],
                'label_3' => 'Know Jesus',
                'percent_3' => $stack['location']['percent_believers'],
                'population_3' => $stack['location']['believers'],
                'section_summary' => '',
                'prayer' => 'Pray that the '.$stack['location']['believers'].' believers in '.$stack['location']['full_name'].' to be bold witnesses to the '.$stack['location']['non_christians'].' lost neighbors around them.',
            ]
        ];
        $stack['list'][] = [
            'type' => 'percent_2_circles',
            'data' => [
                'section_label' => 'Faith Status',
                'label_1' => "Don't Know Jesus",
                'percent_1' => $stack['location']['percent_non_christians'],
                'population_1' => $stack['location']['non_christians'],
                'color_1' => 'red',
                'label_2' => 'Know Jesus',
                'percent_2' => $stack['location']['percent_believers'],
                'population_2' => $stack['location']['believers'],
                'section_summary' => '',
                'prayer' => 'Pray that the '.$stack['location']['believers'].' believers in '.$stack['location']['full_name'].' to be bold witnesses to the '.$stack['location']['non_christians'].' lost neighbors around them.',
            ]
        ];
        $stack['list'][] = [
            'type' => 'percent_3_bar',
            'data' => [
                'section_label' => 'Faith Status',
                'label_1' => "Don't",
                'percent_1' => $stack['location']['percent_non_christians'],
                'population_1' => $stack['location']['non_christians'],
                'label_2' => 'Know About',
                'percent_2' => $stack['location']['percent_christian_adherents'],
                'population_2' => $stack['location']['christian_adherents'],
                'label_3' => 'Know',
                'percent_3' => $stack['location']['percent_believers'],
                'population_3' => $stack['location']['believers'],
                'section_summary' => '',
                'prayer' => 'Pray that the '.$stack['location']['believers'].' believers in '.$stack['location']['full_name'].' to be bold witnesses to the '.$stack['location']['non_christians'].' lost neighbors around them.',
            ]
        ];
        $stack['list'][] = [
            'type' => '100_bodies_chart',
            'data' => [
                'section_label' => 'Faith Status',
                'percent_1' => $stack['location']['percent_non_christians'],
                'percent_2' => $stack['location']['percent_christian_adherents'],
                'percent_3' => $stack['location']['percent_believers'],
                'section_summary' => '',
                'prayer' => 'Pray that the '.$stack['location']['believers'].' believers in '.$stack['location']['full_name'].' to be bold witnesses to the '.$stack['location']['non_christians'].' lost neighbors around them.',
            ]
        ];
        $stack['list'][] = [
            'type' => '100_bodies_3_chart',
            'data' => [
                'section_label' => 'Faith Status',
                'label_1' => "Don't know Jesus",
                'percent_1' => $stack['location']['percent_non_christians'],
                'population_1' => $stack['location']['non_christians'],
                'label_2' => "Know about Jesus",
                'percent_2' => $stack['location']['percent_christian_adherents'],
                'population_2' => $stack['location']['christian_adherents'],
                'label_3' => "Know Jesus",
                'percent_3' => $stack['location']['percent_believers'],
                'population_3' => $stack['location']['believers'],
                'section_summary' => '',
                'prayer' => 'Pray that the '.$stack['location']['believers'].' believers in '.$stack['location']['full_name'].' to be bold witnesses to the '.$stack['location']['non_christians'].' lost neighbors around them.',
            ]
        ];
        $stack['list'][] = [
            'type' => 'population_change_icon_block',
            'data' => [
                'section_label' => 'Dying without Jesus in the next hour',
                'count' => $stack['location']['deaths_non_christians_next_hour'],
                'group' => 'non_christians',
                'type' => 'deaths',
                'size' => (1000 > 400) ? 2 : 3, // 2 or 3
                'section_summary' => '',
                'prayer' => ''
            ]
        ];
        $stack['list'][] = [
            'type' => 'bullet_list_2_column',
            'data' => [
                'section_label' => 'Top Cities',
                'values' => [],
                'section_summary' => '',
                'prayer' => 'Pray that the ' . $stack['location']['believers'] . ' believers in ' . $stack['location']['full_name'] . ' to be bold witnesses to the ' . $stack['location']['non_christians'] . ' nominal neighbors around them.'
            ]
        ];
        $stack['list'][] = [
            'type' => 'content_block',
            'data' => [
                'section_label' => 'Key City',
                'focus_label' => '',
                'icon' => 'ion-map', // ion icons from /pages/fonts/ionicons/
                'color' => 'green',
                'section_summary' => '',
            ]
        ];
        $stack['list'][] = [
            'type' => 'fact_block',
            'data' => [
                'section_label' => 'Least Reached',
                'focus_label' => '',
                'icon' => 'ion-android-warning', // ion icons from /pages/fonts/ionicons/
                'color' => false,
                'section_summary' => '',
                'prayer' => 'Pray that the ' . $stack['location']['believers'] . ' believers in ' . $stack['location']['full_name'] . ' to be bold witnesses to the '.$name.' neighbors near them.'
            ]
        ];
        $stack['list'][] = [
            'type' => 'photo_block',
            'data' => [
                'section_label' => 'Photo from '.$stack['location']['full_name'],
                'url' => '',
                'section_summary' => '',
                'prayer' => '',
            ]
        ];




        return $stack;
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

        $content = PG_Utilities::build_location_stack( $grid_id );
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

        $content = PG_Utilities::build_location_stack( $grid_id );
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
