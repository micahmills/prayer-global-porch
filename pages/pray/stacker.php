<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.

class PG_Stacker {

    public static $show_all = false;

    /**
     * More raw data
     * @param $grid_id
     * @return array
     */
    public static function build_location_stack( $grid_id ) {

        // get queries
        $stack = self::_stack_query( $grid_id );

        // build full stack
        $stack['list'] = [];

        // adds and shuffles for variation
        self::_population_change( $stack );
        self::_least_reached( $stack );
        self::_key_city( $stack );
        self::_verses( $stack );
        shuffle( $stack['list'] );

        // adds to top
        self::_demographics( $stack );
        self::_prayers( $stack, 1 );
        self::_photos( $stack, 2 );
        self::_faith_status( $stack, 3 );

        // adds to bottom
        self::_cities( $stack );
        self::_people_groups( $stack );

        $reduced_stack = [];
        $reduced_stack['list'] = $stack['list'];
        $reduced_stack['location'] = $stack['location'];
        $stack = $reduced_stack;

        return $stack;
    }

    /**
     * More guided
     * @param $grid_id
     * @return array
     */
    public static function build_location_stack_v2( $grid_id ) {

        // get queries
        $stack = self::_stack_query( $grid_id );

        $stack['list'] = [];
        $lists = [];

        // PRAYER SHUFFLE
        PG_Stacker_Text_V2::_for_movement( $lists, $stack );
        PG_Stacker_Text_V2::_population_prayers( $lists, $stack );
        PG_Stacker_Text_V2::_language_prayers( $lists, $stack );
        PG_Stacker_Text_V2::_religion_prayers( $lists, $stack );

        PG_Stacker_Text_V2::_for_prayer_movement( $lists, $stack );
        PG_Stacker_Text_V2::_for_abundant_gospel_sowing( $lists, $stack );
        PG_Stacker_Text_V2::_for_new_churches( $lists, $stack );
        PG_Stacker_Text_V2::_for_obedience( $lists, $stack );
        PG_Stacker_Text_V2::_for_biblical_authority( $lists, $stack );
        PG_Stacker_Text_V2::_for_leadership( $lists, $stack );
        PG_Stacker_Text_V2::_for_house_churches( $lists, $stack );
        PG_Stacker_Text_V2::_for_multiplication( $lists, $stack );
        PG_Stacker_Text_V2::_for_urgency( $lists, $stack );
        PG_Stacker_Text_V2::_for_church_health( $lists, $stack );
//        PG_Stacker_Text_V2::_cities($lists, $stack );

        switch ( $stack['location']['favor'] ) {
            case 'non_christians':
                PG_Stacker_Text_V2::_non_christians( $lists, $stack );
                break;
            case 'christian_adherents':
                PG_Stacker_Text_V2::_christian_adherents( $lists, $stack );
                break;
            case 'believers':
                PG_Stacker_Text_V2::_believers( $lists, $stack );
                break;
            default:
                break;
        }
        foreach ( $lists as $content ) { // kill duplication
            $content['id'] = hash( 'sha256', serialize( $content ) . microtime() );
            $stack['list'][$content['id']] = [
                'type' => 'basic_block',
                'data' => $content
            ];
        }
        shuffle( $stack['list'] );
        $stack['list'] = array_slice( $stack['list'], 0, 8 );

        // FACT SHUFFLE
        self::_photos( $stack, 1 );
        self::_faith_status( $stack, 3 );
        self::_least_reached( $stack, 5 );
        self::_people_groups( $stack, 7 );
        self::_key_city( $stack, 9 );

        // APPEND TO END
//        self::_cities( $stack );

        // REDUCE STACK
        $reduced_stack = [];
        $reduced_stack['list'] = $stack['list'];
        $reduced_stack['location'] = $stack['location'];
        $stack = $reduced_stack;

        return $stack;
    }

    private static function _demographics( &$stack, $position = false ) {

        $section_label = 'Demographics';
        $icon = $stack['location']['icon_color'];

        $templates = [];

        $types = [ 'content_block', '4_fact_blocks' ]; // @todo maybe add knowledge about economy or average wealth
        $type = $types[array_rand( $types )];
        if ( 'content_block' === $type ) {
            $text_list = PG_Stacker_Text::demographics_content_text( $stack );
            $text = $text_list[$stack['location']['favor']][array_rand( $text_list[$stack['location']['favor']] ) ];

            $templates[] = [
                'type' => 'content_block',
                'data' => [
                    'section_label' => $section_label,
                    'focus_label' => $stack['location']['full_name'],
                    'icon' => 'ion-map',
                    'color' => $icon,
                    'section_summary' => $text['section_summary'],
                    'prayer' => ''
                ]
            ];
        }
        else {
            $text_list = PG_Stacker_Text::demogrphics_4_fact_text( $stack );
            $text = $text_list[$stack['location']['favor']][array_rand( $text_list[$stack['location']['favor']] ) ];

            $templates[] = [
                'type' => '4_fact_blocks',
                'data' => [
                    'section_label' => $section_label,
                    'focus_label' => $stack['location']['full_name'],
                    'label_1' => 'Population',
                    'value_1' => $stack['location']['population'],
                    'size_1' => 'two-em',
                    'label_2' => 'Believers',
                    'value_2' => $stack['location']['believers'],
                    'size_2' => 'two-em',
                    'label_3' => 'Dominant Religion',
                    'value_3' => $stack['location']['primary_religion'],
                    'size_3' => 'two-em',
                    'label_4' => 'Language',
                    'value_4' => $stack['location']['primary_language'],
                    'size_4' => 'two-em',
                    'section_summary' => '',
                    'prayer' => $text['prayer']
                ]
            ];
            $templates[] = [
                'type' => '4_fact_blocks',
                'data' => [
                    'section_label' => $section_label,
                    'focus_label' => $stack['location']['full_name'],
                    'label_1' => 'Non-Christians',
                    'value_1' => $stack['location']['non_christians'],
                    'size_1' => 'two-em',
                    'label_2' => 'Cultural Christians',
                    'value_2' => $stack['location']['christian_adherents'],
                    'size_2' => 'two-em',
                    'label_3' => 'Believers',
                    'value_3' => $stack['location']['believers'],
                    'size_3' => 'two-em',
                    'label_4' => 'Language',
                    'value_4' => $stack['location']['primary_language'],
                    'size_4' => 'two-em',
                    'section_summary' => '',
                    'prayer' => $text['prayer']
                ]
            ];
        }

        if ( empty( $position ) ) {
            $stack['list'] = array_merge( [ $templates[array_rand( $templates )] ], $stack['list'] );
        } else {
            $stack['list'] = array_merge( array_slice( $stack['list'], 0, $position ), array( $templates[array_rand( $templates )] ), array_slice( $stack['list'], $position ) );
        }
        return $stack;
    }

    private static function _faith_status( &$stack, $position = false ) {

        $section_label = 'Faith Status';

        $text_list = PG_Stacker_Text::faith_status_text( $stack );
        $text = $text_list[$stack['location']['favor']][array_rand( $text_list[$stack['location']['favor']] ) ];

        $templates = [];

        $templates[] = [
            'type' => 'percent_3_circles',
            'data' => [
                'section_label' => $section_label,
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
                'prayer' => $text['prayer'],
            ]
        ];
        $templates[] = [
            'type' => '100_bodies_chart',
            'data' => [
                'section_label' => $section_label,
                'percent_1' => $stack['location']['percent_non_christians'],
                'percent_2' => $stack['location']['percent_christian_adherents'],
                'percent_3' => $stack['location']['percent_believers'],
                'pop_1' => $stack['location']['percent_non_christians'],
                'pop_2' => $stack['location']['percent_christian_adherents'],
                'pop_3' => $stack['location']['percent_believers'],
                'pop_1_label' => 'Non-Christians',
                'pop_2_label' => 'Cultural Christians',
                'pop_3_label' => 'Believers',
                'section_summary' => 'Non-Christians - '.$stack['location']['non_christians'].' | Cultural Christians - '.$stack['location']['christian_adherents'].' | Believers - '.$stack['location']['believers'].'',
                'prayer' => $text['prayer'],
            ]
        ];
        $templates[] = [
            'type' => '100_bodies_3_chart',
            'data' => [
                'section_label' => $section_label,
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
                'prayer' => $text['prayer'],
            ]
        ];
        $templates[] = [
            'type' => 'lost_per_believer',
            'data' => [
                'section_label' => $section_label,
                'label_1' => "One disciple of Jesus for every " . $stack['location']['lost_per_believer_int'] . " lost neighbors",
                'lost_per_believer' => $stack['location']['lost_per_believer_int'],
                'prayer' => $text['prayer'],
            ]
        ];

        if ( $stack['location']['percent_non_christians'] < 85 ) {
            $templates[] = [
                'type' => 'percent_3_bar',
                'data' => [
                    'section_label' => $section_label,
                    'label_1' => "Don't",
                    'percent_1' => $stack['location']['percent_non_christians'],
                    'population_1' => $stack['location']['non_christians'],
                    'label_2' => 'Know About',
                    'percent_2' => $stack['location']['percent_christian_adherents'],
                    'population_2' => $stack['location']['christian_adherents'],
                    'label_3' => 'Know',
                    'percent_3' => $stack['location']['percent_believers'],
                    'population_3' => $stack['location']['believers'],
                    'section_summary' => 'Non-Christians - '.$stack['location']['non_christians'].' | Cultural Christians - '.$stack['location']['christian_adherents'].' | Believers - '.$stack['location']['believers'].'',
                    'prayer' => $text['prayer'],
                ]
            ];
        }

        if ( empty( $position ) ) {
            $stack['list'] = array_merge( [ $templates[array_rand( $templates )] ], $stack['list'] );
        } else {
            $stack['list'] = array_merge( array_slice( $stack['list'], 0, $position ), array( $templates[array_rand( $templates )] ), array_slice( $stack['list'], $position ) );
        }

        return $stack;
    }

    private static function _population_change( &$stack ) {

        $types = [ 'births', 'deaths' ];
        $type = $types[array_rand( $types )];

        // deaths non christians
        if ( 'christian_adherents' === $stack['location']['favor'] && 'deaths' === $type ) {

            // deaths christian adherents
            $deaths_christian_adherents = [];
            $added = false;
            if ($stack['location']['deaths_christian_adherents_next_hour']) {
                $deaths_christian_adherents[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Next hour',
                        'count' => $stack['location']['deaths_christian_adherents_next_hour'],
                        'group' => 'christian_adherents',
                        'type' => 'deaths',
                        'size' => ( $stack['location']['deaths_christian_adherents_next_hour'] > 400 ) ? 2 : 3,
                        'section_summary' => 'In ' . $stack['location']['name'] . ', ' . $stack['location']['deaths_christian_adherents_next_hour'] . ' people are dying without a personal relationship with Jesus in the next hour.',
                        'prayer' => ''
                    ]
                ];
                $added = true;
            }
            if ($stack['location']['deaths_christian_adherents_next_100']) {
                $deaths_christian_adherents[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Next 100 hours',
                        'count' => $stack['location']['deaths_christian_adherents_next_100'],
                        'group' => 'christian_adherents',
                        'type' => 'deaths',
                        'size' => ( $stack['location']['deaths_christian_adherents_next_100'] > 400 ) ? 2 : 3,
                        'section_summary' => 'In ' . $stack['location']['name'] . ', ' . $stack['location']['deaths_christian_adherents_next_100'] . ' people are dying without a personal relationship with Jesus in the next 100 hours.',
                        'prayer' => ''
                    ]
                ];
                $added = true;
            }
            if ($stack['location']['deaths_christian_adherents_next_week']) {
                $deaths_christian_adherents[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Next week',
                        'count' => $stack['location']['deaths_christian_adherents_next_week'],
                        'group' => 'christian_adherents',
                        'type' => 'deaths',
                        'size' => ( $stack['location']['deaths_christian_adherents_next_week'] > 400 ) ? 2 : 3,
                        'section_summary' => 'In ' . $stack['location']['name'] . ', ' . $stack['location']['deaths_christian_adherents_next_week'] . ' people are dying without a personal relationship with Jesus in the next week.',
                        'prayer' => ''
                    ]
                ];
                $added = true;
            }
            if ($stack['location']['deaths_christian_adherents_next_month']) {
                $deaths_christian_adherents[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Next month',
                        'count' => $stack['location']['deaths_christian_adherents_next_month'],
                        'group' => 'christian_adherents',
                        'type' => 'deaths',
                        'size' => ( $stack['location']['deaths_christian_adherents_next_month'] > 400 ) ? 2 : 3,
                        'section_summary' => 'In ' . $stack['location']['name'] . ', ' . $stack['location']['deaths_christian_adherents_next_month'] . ' people are dying without a personal relationship with Jesus in the next month.',
                        'prayer' => ''
                    ]
                ];
                $added = true;
            }

            if ( $added ) {
                $stack['list'][] = $deaths_christian_adherents[array_rand( $deaths_christian_adherents )];
            }
        }

        // births christian adherents
        else if ( 'christian_adherents' === $stack['location']['favor'] && 'births' === $type ) {
            $births_christian_adherents = [];
            $added = false;
            if ( $stack['location']['births_christian_adherents_last_hour'] ) {
                $births_christian_adherents[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Next hour',
                        'count' => $stack['location']['births_christian_adherents_last_hour'],
                        'group' => 'christian_adherents',
                        'type' => 'births',
                        'size' => ( $stack['location']['births_christian_adherents_last_hour'] > 400 ) ? 2 : 3,
                        'section_summary' => 'In ' . $stack['location']['name'] . ', ' . $stack['location']['births_christian_adherents_last_hour'] . ' babies will be born in the next hour to families who might now about God culturally, but likely have no relationship with Jesus.',
                        'prayer' => ''
                    ]
                ];
                $added = true;
            }
            if ( $stack['location']['births_christian_adherents_last_100'] ) {
                $births_christian_adherents[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Next 100 hours',
                        'count' => $stack['location']['births_christian_adherents_last_100'],
                        'group' => 'christian_adherents',
                        'type' => 'births',
                        'size' => ( $stack['location']['births_christian_adherents_last_100'] > 400 ) ? 2 : 3,
                        'section_summary' => 'In ' . $stack['location']['name'] . ', ' . $stack['location']['births_christian_adherents_last_100'] . ' babies will be born in the next 100 hours to families who might now about God culturally, but likely have no relationship with Jesus.',
                        'prayer' => ''
                    ]
                ];
                $added = true;
            }
            if ( $stack['location']['births_christian_adherents_last_week'] ) {
                $births_christian_adherents[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Next week',
                        'count' => $stack['location']['births_christian_adherents_last_week'],
                        'group' => 'christian_adherents',
                        'type' => 'births',
                        'size' => ( $stack['location']['births_christian_adherents_last_week'] > 400 ) ? 2 : 3,
                        'section_summary' => 'In ' . $stack['location']['name'] . ', ' . $stack['location']['births_christian_adherents_last_week'] . ' babies will be born in the next week to families who might now about God culturally, but likely have no relationship with Jesus.',
                        'prayer' => ''
                    ]
                ];
                $added = true;
            }
            if ( $stack['location']['births_christian_adherents_last_month'] ) {
                $births_christian_adherents[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Next month',
                        'count' => $stack['location']['births_christian_adherents_last_month'],
                        'group' => 'christian_adherents',
                        'type' => 'births',
                        'size' => ( $stack['location']['births_christian_adherents_last_month'] > 400 ) ? 2 : 3,
                        'section_summary' => 'In ' . $stack['location']['name'] . ', ' . $stack['location']['births_christian_adherents_last_month'] . ' babies will be born in the next month to families who might now about God culturally, but likely have no relationship with Jesus.',
                        'prayer' => ''
                    ]
                ];
                $added = true;
            }
            if ( $added ) {
                $stack['list'][] = $births_christian_adherents[array_rand( $births_christian_adherents )];
            }
        }

        else if ( 'non_christians' === $stack['location']['favor'] && 'deaths' === $type ) {
            $deaths_non_christians = [];
            $added = false;

            if ( $stack['location']['deaths_non_christians_next_hour'] > 1 ) {
                $deaths_non_christians[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Next hour',
                        'count' => $stack['location']['deaths_non_christians_next_hour'],
                        'group' => 'non_christians',
                        'type' => 'deaths',
                        'size' => ( $stack['location']['deaths_non_christians_next_hour'] > 400 ) ? 2 : 3,
                        'section_summary' => 'In ' . $stack['location']['name'] . ', ' . $stack['location']['deaths_non_christians_next_hour'] . ' people will die without Jesus in the next hour.',
                        'prayer' => ''
                    ]
                ];
                $added = true;
            }
            if ($stack['location']['deaths_non_christians_next_100']) {
                $deaths_non_christians[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Next 100 hours',
                        'count' => $stack['location']['deaths_non_christians_next_100'],
                        'group' => 'non_christians',
                        'type' => 'deaths',
                        'size' => ( $stack['location']['deaths_non_christians_next_100'] > 400 ) ? 2 : 3,
                        'section_summary' => 'In ' . $stack['location']['name'] . ', ' . $stack['location']['deaths_non_christians_next_100'] . ' people will die without Jesus in the next 100 hours.',
                        'prayer' => ''
                    ]
                ];
                $added = true;
            }
            if ($stack['location']['deaths_non_christians_next_week']) {
                $deaths_non_christians[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Next week',
                        'count' => $stack['location']['deaths_non_christians_next_week'],
                        'group' => 'non_christians',
                        'type' => 'deaths',
                        'size' => ( $stack['location']['deaths_non_christians_next_week'] > 400 ) ? 2 : 3,
                        'section_summary' => 'In ' . $stack['location']['name'] . ', ' . $stack['location']['deaths_non_christians_next_week'] . ' people will die without Jesus in the next week.',
                        'prayer' => ''
                    ]
                ];
                $added = true;
            }
            if ($stack['location']['deaths_non_christians_next_month']) {
                $deaths_non_christians[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Next month',
                        'count' => $stack['location']['deaths_non_christians_next_month'],
                        'group' => 'non_christians',
                        'type' => 'deaths',
                        'size' => ( $stack['location']['deaths_non_christians_next_month'] > 400 ) ? 2 : 3,
                        'section_summary' => 'In ' . $stack['location']['name'] . ', ' . $stack['location']['deaths_non_christians_next_month'] . ' will die without Jesus in the next month.',
                        'prayer' => ''
                    ]
                ];
                $added = true;
            }

            if ( $added ) {
                $stack['list'][] = $deaths_non_christians[array_rand( $deaths_non_christians )];
            }
        }

        // births non christians
        else if ( 'non_christians' === $stack['location']['favor'] && 'births' === $type ) {
            $births_non_christians = [];
            $added = false;
            if ( $stack['location']['births_non_christians_last_hour'] ) {
                $births_non_christians[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Next hour',
                        'count' => $stack['location']['births_non_christians_last_hour'],
                        'group' => 'non_christians',
                        'type' => 'births',
                        'size' => ( $stack['location']['births_non_christians_last_hour'] > 400 ) ? 2 : 3,
                        'section_summary' => 'In ' . $stack['location']['name'] . ', ' . $stack['location']['births_non_christians_last_hour'] . ' babies will be born in the next hour to families who are far from God.',
                        'prayer' => ''
                    ]
                ];
                $added = true;
            }
            if ( $stack['location']['births_non_christians_last_100'] ) {
                $births_non_christians[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Next 100 hours',
                        'count' => $stack['location']['births_non_christians_last_100'],
                        'group' => 'non_christians',
                        'type' => 'births',
                        'size' => ( $stack['location']['births_non_christians_last_100'] > 400 ) ? 2 : 3,
                        'section_summary' => 'In ' . $stack['location']['name'] . ', ' . $stack['location']['births_non_christians_last_100'] . ' babies will be born in the next 100 hours to families who are far from God.',
                        'prayer' => ''
                    ]
                ];
                $added = true;
            }
            if ( $stack['location']['births_non_christians_last_week'] ) {
                $births_non_christians[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Next week',
                        'count' => $stack['location']['births_non_christians_last_week'],
                        'group' => 'non_christians',
                        'type' => 'births',
                        'size' => ( $stack['location']['births_non_christians_last_week'] > 400 ) ? 2 : 3,
                        'section_summary' => 'In ' . $stack['location']['name'] . ', ' . $stack['location']['births_non_christians_last_week'] . ' babies will be born in the next week to families who are far from God.',
                        'prayer' => ''
                    ]
                ];
                $added = true;
            }
            if ( $stack['location']['births_non_christians_last_month'] ) {
                $births_non_christians[] = [
                    'type' => 'population_change_icon_block',
                    'data' => [
                        'section_label' => 'Next month',
                        'count' => $stack['location']['births_non_christians_last_month'],
                        'group' => 'non_christians',
                        'type' => 'births',
                        'size' => ( $stack['location']['births_non_christians_last_month'] > 400 ) ? 2 : 3,
                        'section_summary' => 'In ' . $stack['location']['name'] . ', ' . $stack['location']['births_non_christians_last_month'] . ' babies will be born in the next month to families who are far from God.',
                        'prayer' => ''
                    ]
                ];
                $added = true;
            }

            if ( $added ) {
                $stack['list'][] = $births_non_christians[array_rand( $births_non_christians )];
            }
        }

        return $stack;
    }

    private static function _cities( &$stack ) {

        if ( ! empty( $stack['cities'] ) ) {

            // cities list
            $values = [];
            foreach ($stack['cities'] as $city) {
                $values[] = $city['name'] . ' - (pop ' . $city['population'] . ')';
            }
            if ( ! empty( $values )) {

                $text_list = PG_Stacker_Text::cities_text( $stack );
                $text = $text_list[array_rand( $text_list ) ];

                $stack['list'][] = [
                    'type' => 'bullet_list_2_column',
                    'data' => [
                        'section_label' => 'Cities in ' . $stack['location']['name'],
                        'values' => $values,
                        'section_summary' => '',
                        'prayer' => $text['prayer'],
                    ]
                ];
            }
        }

        return $stack;
    }

    private static function _key_city( &$stack, $position = false ) {

        if ( ! empty( $stack['cities'] ) ) {

            $cities = $stack['cities'];
            shuffle( $cities );

            if ( isset( $cities[0] ) && ! empty( $cities[0] ) ) {

                $text_list = PG_Stacker_Text::key_city_text( $stack, $cities[0] );
                $text = $text_list[array_rand( $text_list ) ];

                $template = [
                    'type' => 'content_block',
                    'data' => [
                        'section_label' => 'Focus City',
                        'focus_label' => 'Pray for the city of ' . $cities[0]['name'],
                        'icon' => 'ion-map', // ion icons from /pages/fonts/ionicons/
                        'color' => 'green',
                        'section_summary' => '',
                        'prayer' => $text['section_summary'],
                    ]
                ];

                if ( empty( $position ) ) {
                    $stack['list'] = array_merge( [ $template ], $stack['list'] );
                } else {
                    $stack['list'] = array_merge( array_slice( $stack['list'], 0, $position ), [ $template ], array_slice( $stack['list'], $position ) );
                }
            }
        }

        return $stack;
    }

    private static function _least_reached( &$stack, $position = false ) {

        if ( ! empty( $stack['least_reached'] ) ) {

            $text_list = PG_Stacker_Text::least_reached_text( $stack );
            $text = $text_list[array_rand( $text_list ) ];

            $template = [
                'type' => 'least_reached_block',
                'data' => [
                    'section_label' => 'Least Reached',
                    'focus_label' => $stack['least_reached']['name'],
                    'image_url' => pg_jp_image( 'pid3', $stack['least_reached']['PeopleID3'] ), // ion icons from /pages/fonts/ionicons/
                    'section_summary' => $text['section_summary'],
                    'prayer' => $text['prayer'],
                ]
            ];

            if ( empty( $position ) ) {
                $stack['list'] = array_merge( [ $template ], $stack['list'] );
            } else {
                $stack['list'] = array_merge( array_slice( $stack['list'], 0, $position ), [ $template ], array_slice( $stack['list'], $position ) );
            }
        }

        return $stack;
    }

    private static function _photos( &$stack, $position = false ) {

        $images = pg_images( $stack['location']['grid_id'], true );

        if ( ! empty( $images['photos'] ) ) {

            $text_list = PG_Stacker_Text::photos_text( $stack );
            $text = $text_list[array_rand( $text_list ) ];

            $image_url = $images['photos'][array_rand( $images['photos'] )];
            $template = [
                'type' => 'photo_block',
                'data' => [
                    'section_label' => 'One Shot Prayer Walk',
                    'location_label' => 'Photo from the ' . $stack['location']['admin_level_name'] . ' of ' . $stack['location']['full_name'],
                    'url' => $image_url,
                    'section_summary' => $text['section_summary'],
                    'prayer' => $text['prayer'],
                ]
            ];

            if ( empty( $position ) ) {
                $stack['list'] = array_merge( [ $template ], $stack['list'] );
            } else {
                $stack['list'] = array_merge( array_slice( $stack['list'], 0, $position ), [ $template ], array_slice( $stack['list'], $position ) );
            }
        }

        return $stack;
    }

    private static function _prayers( &$stack, $position = false ) {

        $section_label = 'Movement Prayer';

        $text_list = PG_Stacker_Text::prayer_text( $stack );
        $text = $text_list[$stack['location']['favor']][array_rand( $text_list[$stack['location']['favor']] ) ];
        $icon = $stack['location']['icon_color'];

        $template = [
            'type' => 'prayer_block',
            'data' => [
                'section_label' => $section_label,
                'icon_color' => $icon,
                'prayer' => $text['prayer'],
            ]
        ];

        if ( empty( $position ) ) {
            $stack['list'] = array_merge( [ $template ], $stack['list'] );
        } else {
            $stack['list'] = array_merge( array_slice( $stack['list'], 0, $position ), [ $template ], array_slice( $stack['list'], $position ) );
        }

        return $stack;
    }

    private static function _verses( &$stack, $position = false ) {

        $section_label = 'Scripture';

        $text_list = PG_Stacker_Text::verse_text( $stack );
        $text = $text_list[$stack['location']['favor']][array_rand( $text_list[$stack['location']['favor']] ) ];
        $icon = $stack['location']['icon_color'];

        $template = [
            'type' => 'verse_block',
            'data' => [
                'section_label' => $section_label,
                'icon_color' => $icon,
                'verse' => 'And this gospel of the kingdom will be preached in the whole world as a testimony to all nations, and then the end will come.',
                'reference' => 'Matthew 24:14',
                'prayer' => $text['prayer'],
            ]
        ];

        if ( empty( $position ) ) {
            $stack['list'] = array_merge( [ $template ], $stack['list'] );
        } else {
            $stack['list'] = array_merge( array_slice( $stack['list'], 0, $position ), [ $template ], array_slice( $stack['list'], $position ) );
        }

        return $stack;

    }

    private static function _people_groups( &$stack, $position = false ) {
        if ( ! empty( $stack['people_groups'] ) ) {
            $image_list = pg_jp_images_json();
            $base_url = pg_jp_image_url();

            // people group list
            $values = [];
            foreach ( $stack['people_groups'] as $group ) {
                if ( isset( $image_list['pid3'][$group['PeopleID3']] ) ) {
                    $image = $base_url . 'pid3/' . $image_list['pid3'][$group['PeopleID3']];
                } else {
                    continue;
                }

                $values[$group['PeopleID3']] = [
                    'name' => $group['name'],
                    'image_url' => $image,
                    'description' => $group['name'] . '<br>(' . $group['PrimaryReligion'].')',
                    'progress' => $group['JPScale'],
                    'progress_image_url' => $base_url . 'progress/' . $image_list['progress'][$group['JPScale']],
                    'least_reached' => $group['LeastReached']
                ];
            }
            if ( ! empty( $values ) ) {
                $template = [
                    'type' => 'people_groups_list',
                    'data' => [
                        'section_label' => 'People Groups In The Area',
                        'values' => $values,
                        'section_summary' => '',
                        'prayer' => ''
                    ]
                ];
                if ( empty( $position ) ) {
                    $stack['list'] = array_merge( [ $template ], $stack['list'] );
                } else {
                    $stack['list'] = array_merge( array_slice( $stack['list'], 0, $position ), [ $template ], array_slice( $stack['list'], $position ) );
                }
            }
        }

        return $stack;
    }

    public static function _stack_query( $grid_id ) {
        global $wpdb;

        // get record and level
        $grid_record = $wpdb->get_row( $wpdb->prepare( "
            SELECT
              g.grid_id,
              lgn.name,
              lgn.admin0_name,
              lgn.full_name,
              g.population,
              g.latitude,
              g.longitude,
              g.country_code,
              g.admin0_code,
              g.parent_id,
              p.name as parent_name,
              g.admin0_grid_id,
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
              p.longitude as p_longitude,
              p.latitude as p_latitude,
              p.north_latitude as p_north_latitude,
              p.south_latitude as p_south_latitude,
              p.east_longitude as p_east_longitude,
              p.west_longitude as p_west_longitude,
              gc.longitude as c_longitude,
              gc.latitude as c_latitude,
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
            LEFT JOIN $wpdb->location_grid_names as lgn ON g.grid_id=lgn.grid_id AND lgn.language_code = 'en'
            WHERE g.grid_id = %s
        ", $grid_id ), ARRAY_A );


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
        $grid_record = array_merge( $grid_record, [ 'admin_level_name' => $admin_level_name, 'admin_level_name_cap' => ucwords( $admin_level_name ), 'admin_level_name_plural' => $admin_level_name_plural ] );


        // format
        $grid_record['level'] = (int) $grid_record['level'];
        $grid_record['longitude'] = (float) $grid_record['longitude'];
        $grid_record['latitude'] = (float) $grid_record['latitude'];
        $grid_record['north_latitude'] = (float) $grid_record['north_latitude'];
        $grid_record['south_latitude'] = (float) $grid_record['south_latitude'];
        $grid_record['east_longitude'] = (float) $grid_record['east_longitude'];
        $grid_record['west_longitude'] = (float) $grid_record['west_longitude'];
        $grid_record['p_latitude'] = (float) $grid_record['p_latitude'];
        $grid_record['p_longitude'] = (float) $grid_record['p_longitude'];
        $grid_record['p_north_latitude'] = (float) $grid_record['p_north_latitude'];
        $grid_record['p_south_latitude'] = (float) $grid_record['p_south_latitude'];
        $grid_record['p_east_longitude'] = (float) $grid_record['p_east_longitude'];
        $grid_record['p_west_longitude'] = (float) $grid_record['p_west_longitude'];
        $grid_record['c_latitude'] = (float) $grid_record['c_latitude'];
        $grid_record['c_longitude'] = (float) $grid_record['c_longitude'];
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
        $grid_record['percent_believers'] = round( (float) $grid_record['percent_believers'], 2 );
        $grid_record['percent_christian_adherents_full'] = (float) $grid_record['percent_christian_adherents'];
        $grid_record['percent_christian_adherents'] = round( (float) $grid_record['percent_christian_adherents'], 2 );
        $grid_record['percent_non_christians_full'] = (float) $grid_record['percent_non_christians'];
        $grid_record['percent_non_christians'] = round( (float) $grid_record['percent_non_christians'], 2 );

        // lost
        $grid_record['all_lost_int'] = $grid_record['christian_adherents_int'] + $grid_record['non_christians_int'];
        $grid_record['all_lost'] = number_format( $grid_record['all_lost_int'] );
        if ( $grid_record['believers_int'] > 0 ) {
            $grid_record['lost_per_believer_int'] = (int) ceil( ( $grid_record['christian_adherents_int'] + $grid_record['non_christians_int'] ) / $grid_record['believers_int'] );
        } else {
            $grid_record['lost_per_believer_int'] = $grid_record['christian_adherents_int'] + $grid_record['non_christians_int'];
        }
        $grid_record['lost_per_believer'] = number_format( $grid_record['lost_per_believer_int'] );

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

        $grid_record['deaths_among_lost'] = self::_get_pace( 'deaths_among_lost', $grid_record );
        $grid_record['new_churches_needed'] = self::_get_pace( 'new_churches_needed', $grid_record );

        $status = [];
        for ($i = 1; $i <= $grid_record['percent_christian_adherents']; $i++) {
            $status[] = 'christian_adherents';
        }
        for ($i = 1; $i <= $grid_record['percent_non_christians']; $i++) {
            $status[] = 'non_christians';
        }
        for ($i = 1; $i <= $grid_record['percent_believers']; $i++) {
            $status[] = 'believers';
        }
        $grid_record['favor'] = $status[array_rand( $status )];

        if ( 'christian_adherents' === $grid_record['favor'] ) {
            $grid_record['icon_color'] = 'red';
        } else if ( 'non_christians' === $grid_record['favor'] ) {
            $grid_record['icon_color'] = 'orange';
        } else { // believers
            $grid_record['icon_color'] = 'green';
        }

        // build people groups list
        $people_groups = $wpdb->get_results($wpdb->prepare( "
            SELECT DISTINCT lgpg.*, FORMAT(lgpg.population, 0) as population, 'current' as query_level
                FROM $wpdb->location_grid_people_groups lgpg
                WHERE
                    lgpg.longitude < %d AND /* east */
                    lgpg.longitude >  %d AND /* west */
                    lgpg.latitude < %d AND /* north */
                    lgpg.latitude > %d AND /* south */
                    lgpg.admin0_grid_id = %d AND
                    lgpg.PrimaryReligion != 'Christianity'
                ORDER BY lgpg.LeastReached DESC
                LIMIT 20
        ", $grid_record['east_longitude'], $grid_record['west_longitude'], $grid_record['north_latitude'], $grid_record['south_latitude'], $grid_record['admin0_grid_id'] ), ARRAY_A );
        if ( empty( $people_groups ) ) {
            $people_groups = $wpdb->get_results($wpdb->prepare( "
                SELECT DISTINCT lgpg.*, FORMAT(lgpg.population, 0) as population, 'parent' as query_level
                    FROM $wpdb->location_grid_people_groups lgpg
                    WHERE
                        lgpg.longitude < %d AND /* east */
                        lgpg.longitude >  %d AND /* west */
                        lgpg.latitude < %d AND /* north */
                        lgpg.latitude > %d AND /* south */
                        lgpg.admin0_grid_id = %d AND
                        lgpg.PrimaryReligion != 'Christianity'
                    ORDER BY lgpg.LeastReached DESC
                    LIMIT 20
            ", $grid_record['p_east_longitude'], $grid_record['p_west_longitude'], $grid_record['p_north_latitude'], $grid_record['p_south_latitude'], $grid_record['admin0_grid_id'] ), ARRAY_A );
        }
        if ( empty( $people_groups ) ) {
            $people_groups = [];
        }
        shuffle( $people_groups ); // randomize results

        $least_reached = [];
        if ( ! empty( $people_groups ) ) {

            foreach ( $people_groups as $i => $pg ) {
                if ( 'Y' === $pg['LeastReached'] ) {
                    $least_reached = $pg; // get first least reached group
                    unset( $people_groups[$i] );
                    break;
                }
            }

            $people_groups = array_slice( $people_groups, 0, 5, true ); // trim to first 5 shuffled results

            $people_groups_list = [ 'names' => [], 'names_pop' => [] ];
            foreach ( $people_groups as $i => $pg ) {
                $people_groups_list['names'][] = $pg['name'];
                $pop = empty( $pg['population'] ) ? '' : ' ('.$pg['population'].')';
                $people_groups_list['names_pop'][] = $pg['name'] . $pop;
            }
            $grid_record['people_groups_list'] = implode( ', ', $people_groups_list['names'] );
            $grid_record['people_groups_list_w_pop'] = implode( ', ', $people_groups_list['names_pop'] );
        }

        // cities
        $cities = [];
        $where = '';
        if ( 0 === $grid_record['level'] ) {
            $where = ' WHERE lgpg.admin0_grid_id = '.$grid_record['grid_id'].' ';
        } else if ( 1 === $grid_record['level'] ) {
            $where = ' WHERE lgpg.admin1_grid_id = '.$grid_record['grid_id'].' ';
        } else if ( 2 === $grid_record['level'] ) {
            $where = ' WHERE lgpg.admin2_grid_id = '.$grid_record['grid_id'].' ';
        }
        if ( ! empty( $where ) ) {
            // @phpcs:disable
            $cities = $wpdb->get_results( "
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
                $where
                ORDER BY lgpg.population DESC
                LIMIT 5
        ", ARRAY_A );
            // @phpcs:enable
        }
        if ( ! empty( $cities ) ) {
            $cities_list = [ 'names' => [], 'names_pop' => [] ];
            foreach ( $cities as $city_value ) {
                $cities_list['names'][] = $city_value['name'];
                $pop = empty( $city_value['population'] ) ? '' : ' ('.$city_value['population'].')';
                $cities_list['names_pop'][] = $city_value['name'] . $pop;
            }
            $grid_record['cities_list'] = implode( ', ', $cities_list['names'] );
            $grid_record['cities_list_w_pop'] = implode( ', ', $cities_list['names_pop'] );
        }


        return [
            'location' => $grid_record,
            'cities' => $cities,
            'people_groups' => $people_groups,
            'least_reached' => $least_reached
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

        switch ( $type ) {
            case 'births_non_christians_last_hour':
                $return_value = ( $birth_rate * ( $not_believers / 1000 ) ) / 365 / 24;
                break;
            case 'births_non_christians_last_100':
                $return_value = ( $birth_rate * ( $not_believers / 1000 ) ) / 365 / 24 * 100;
                break;
            case 'births_non_christians_last_week':
                $return_value = ( $birth_rate * ( $not_believers / 1000 ) ) / 365 * 7;
                break;
            case 'births_non_christians_last_month':
                $return_value = ( $birth_rate * ( $not_believers / 1000 ) ) / 365 * 30;
                break;
            case 'births_non_christians_last_year':
                $return_value = ( $birth_rate * ( $not_believers / 1000 ) );
                break;

            case 'deaths_non_christians_next_hour':
                $return_value = ( $death_rate * ( $not_believers / 1000 ) ) / 365 / 24;
                break;
            case 'deaths_non_christians_next_100':
                $return_value = ( $death_rate * ( $not_believers / 1000 ) ) / 365 / 24 * 100;
                break;
            case 'deaths_non_christians_next_week':
                $return_value = ( $death_rate * ( $not_believers / 1000 ) ) / 365 * 7;
                break;
            case 'deaths_non_christians_next_month':
                $return_value = ( $death_rate * ( $not_believers / 1000 ) ) / 365 * 30;
                break;
            case 'deaths_non_christians_next_year':
                $return_value = ( $death_rate * ( $not_believers / 1000 ) );
                break;
            case 'deaths_among_lost':
//                $number = [
//                    $grid_record['deaths_christian_adherents_next_100']
//                ];
//                $in_the_next = [
//
//                ];
                $return_value = '';
                break;

            case 'births_christian_adherents_last_hour':
                $return_value = ( $birth_rate * ( $christian_adherents / 1000 ) ) / 365 / 24;
                break;
            case 'births_christian_adherents_last_100':
                $return_value = ( $birth_rate * ( $christian_adherents / 1000 ) ) / 365 / 24 * 100;
                break;
            case 'births_christian_adherents_last_week':
                $return_value = ( $birth_rate * ( $christian_adherents / 1000 ) ) / 365 * 7;
                break;
            case 'births_christian_adherents_last_month':
                $return_value = ( $birth_rate * ( $christian_adherents / 1000 ) ) / 365 * 30;
                break;
            case 'births_christian_adherents_last_year':
                $return_value = ( $birth_rate * ( $christian_adherents / 1000 ) );
                break;

            case 'deaths_christian_adherents_next_hour':
                $return_value = ( $death_rate * ( $christian_adherents / 1000 ) ) / 365 / 24;
                break;
            case 'deaths_christian_adherents_next_100':
                $return_value = ( $death_rate * ( $christian_adherents / 1000 ) ) / 365 / 24 * 100;
                break;
            case 'deaths_christian_adherents_next_week':
                $return_value = ( $death_rate * ( $christian_adherents / 1000 ) ) / 365 * 7;
                break;
            case 'deaths_christian_adherents_next_month':
                $return_value = ( $death_rate * ( $christian_adherents / 1000 ) ) / 365 * 30;
                break;
            case 'deaths_christian_adherents_next_year':
                $return_value = ( $death_rate * ( $christian_adherents / 1000 ) );
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

            case 'new_churches_needed':
                $return_value = $grid_record['population_int'] / 5000;
                if ( $return_value < 1 ) {
                    $return_value = $grid_record['population_int'] / 500;
                    if ( $return_value < 1 ) {
                        $return_value = $grid_record['population_int'] / 50;
                    }
                }
                break;
            default:
                break;
        }

        return number_format( intval( $return_value ) );
    }

}
