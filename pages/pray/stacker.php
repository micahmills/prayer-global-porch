<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.

class PG_Stacker {

    public static $show_all = false;

    public static function build_location_stack( $grid_id ) {
//        dt_write_log(__METHOD__ . ' BEGIN');
        // get queries
        $stack = self::_stack_query( $grid_id );

        $status = [];
        for ($i = 1; $i <= $stack['location']['percent_christian_adherents']; $i++) {
            $status[] = 'christian_adherents';
        }
        for ($i = 1; $i <= $stack['location']['percent_non_christians']; $i++) {
            $status[] = 'non_christians';
        }
        for ($i = 1; $i <= $stack['location']['percent_believers']; $i++) {
            $status[] = 'believers';
        }
        $stack['favor'] = $status[array_rand( $status )];

        // build full stack
        $stack['list'] = [];

        // adds and shuffles for variation
        self::_population_change( $stack );
        self::_least_reached( $stack );
        self::_key_city( $stack );
        shuffle( $stack['list'] );

        // inserts into shuffled array specific array positions
        self::_verses( $stack, 3 );

        // adds to top
        self::_demographics( $stack );
        self::_prayers( $stack, 1 );
        self::_photos( $stack, 2 );
        self::_faith_status( $stack, 3 );

        // adds to bottom
        self::_cities( $stack );
        self::_people_groups( $stack );

//        dt_write_log(__METHOD__ . ' END');

        $reduced_stack = [];
        $reduced_stack['list'] = $stack['list'];
        $reduced_stack['location'] = $stack['location'];
        $stack = $reduced_stack;

        return $stack;
    }

    private static function _demographics( &$stack ) {

        $templates = [];

        $section_label = 'Demographics';

        // all locations

        if ( $stack['location']['percent_non_christians'] > 50 ) {

            $templates[] = [
                'type' => 'content_block',
                'data' => [
                    'section_label' => $section_label,
                    'focus_label' => $stack['location']['full_name'],
                    'icon' => 'ion-map',
                    'color' => 'red',
                    'section_summary' => 'The '.$stack['location']['admin_level_name'].' of <strong>'.$stack['location']['full_name'].'</strong> has a population of <strong>'.$stack['location']['population'].'</strong>.<br><br> We estimate '.$stack['location']['name'].' has <strong>'.$stack['location']['believers'].'</strong> people who might know Jesus, <strong>'.$stack['location']['christian_adherents'].'</strong> people who might know about Jesus culturally, and <strong>'.$stack['location']['non_christians'].'</strong> people who do not know Jesus.<br><br>This is <strong>1</strong> believer for every <strong>'. $stack['location']['lost_per_believer'] .'</strong> neighbors who need Jesus.',
                ]
            ];
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
                    'prayer' => 'We estimate there is <strong>1</strong> believer for every <strong>'. $stack['location']['lost_per_believer'] .'</strong> neighbors who need Jesus.'
                ]
            ];

        }
        if ( $stack['location']['percent_christian_adherents'] > 50 ) {
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
                    'prayer' => 'We estimate there is <strong>1</strong> believer for every <strong>'. $stack['location']['lost_per_believer'] .'</strong> neighbors who need Jesus.'
                ]
            ];
            $templates[] = [
                'type' => 'content_block',
                'data' => [
                    'section_label' => $section_label,
                    'focus_label' => $stack['location']['full_name'],
                    'icon' => 'ion-map',
                    'color' => 'orange',
                    'section_summary' => 'The ' . $stack['location']['admin_level_name'] . ' of <strong>' . $stack['location']['full_name'] . '</strong> has a population of <strong>' . $stack['location']['population'] . '</strong>.<br><br> We estimate ' . $stack['location']['name'] . ' has <strong>' . $stack['location']['believers'] . '</strong> people who might know Jesus, <strong>' . $stack['location']['christian_adherents'] . '</strong> people who might know about Jesus culturally, and <strong>' . $stack['location']['non_christians'] . '</strong> people who do not know Jesus.<br><br>This is <strong>1</strong> believer for every <strong>' .  $stack['location']['lost_per_believer'] . '</strong> neighbors who need Jesus.',
                ]
            ];
        }


        $template = $templates[array_rand( $templates )];
        $stack['list'] = array_merge( [ $template ], $stack['list'] );

        return $stack;
    }

    private static function _faith_status( &$stack, int $position ) {

        if ( empty( $position ) ) {
            $position = 3;
        }

        $templates = [];

        $templates[] = [
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
                'prayer' => 'Pray that the '.$stack['location']['believers'].' believers in '.$stack['location']['full_name'].' to be bold witnesses to the '.$stack['location']['all_lost'].' lost neighbors around them.',
            ]
        ];
        $templates[] = [
            'type' => 'percent_3_circles',
            'data' => [
                'section_label' => 'Demographics',
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
                'prayer' => 'The '.$stack['location']['admin_level_name'].' of <strong>'.$stack['location']['full_name'].'</strong> has a population of <strong>'.$stack['location']['population'].'</strong>. We estimate there is <strong>1</strong> believer for every <strong>'. $stack['location']['lost_per_believer'] .'</strong> neighbors who need Jesus.',
            ]
        ];
        if ( $stack['location']['percent_non_christians'] > $stack['location']['percent_christian_adherents']) {
            $templates[] = [
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
            $templates[] = [
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
            $templates[] = [
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
                   'prayer' => 'Pray that the '.$stack['location']['believers'].' believers in '.$stack['location']['full_name'].' encourage the '.$stack['location']['christian_adherents'].' cultural Christians around them to read and obey the Word.',
               ]
            ];
            $templates[] = [
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
                   'prayer' => 'Pray that the '.$stack['location']['believers'].' believers in '.$stack['location']['full_name'].' encourage the '.$stack['location']['christian_adherents'].' cultural Christians around them to read and obey the Word.',
               ]
            ];
        }

        if ( $stack['location']['percent_non_christians'] < 85 ) {
            $templates[] = [
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
                    'prayer' => 'Pray that the '.$stack['location']['believers'].' believers in '.$stack['location']['full_name'].' to be bold witnesses to the '.$stack['location']['all_lost'].' lost neighbors around them.',
                ]
            ];
        }

        $templates[] = [
            'type' => '100_bodies_chart',
            'data' => [
                'section_label' => 'Faith Status',
                'percent_1' => $stack['location']['percent_non_christians'],
                'percent_2' => $stack['location']['percent_christian_adherents'],
                'percent_3' => $stack['location']['percent_believers'],
                'section_summary' => '',
                'prayer' => 'Pray that the '.$stack['location']['believers'].' believers in '.$stack['location']['full_name'].' to be bold witnesses to the '.$stack['location']['all_lost'].' lost neighbors around them.',
            ]
        ];
        $templates[] = [
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
                'prayer' => 'Pray that the '.$stack['location']['believers'].' believers in '.$stack['location']['full_name'].' to be bold witnesses to the '.$stack['location']['all_lost'].' lost neighbors around them.',
            ]
        ];

        $stack['list'] = array_merge( array_slice( $stack['list'], 0, $position ), array( $templates[array_rand( $templates )] ), array_slice( $stack['list'], $position ) );

        return $stack;
    }

    private static function _population_change( &$stack ) {

        $types = [ 'births', 'deaths' ];
        $type = $types[array_rand( $types )];

        // deaths non christians
        if ( 'christian_adherents' === $stack['favor'] && 'deaths' === $type ) {

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
        else if ( 'christian_adherents' === $stack['favor'] && 'births' === $type ) {
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

        else if ( 'non_christians' === $stack['favor'] && 'deaths' === $type ) {
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
        else if ( 'non_christians' === $stack['favor'] && 'births' === $type ) {
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
            if ( !empty( $values )) {
                $stack['list'][] = [
                    'type' => 'bullet_list_2_column',
                    'data' => [
                        'section_label' => 'Top Cities',
                        'values' => $values,
                        'section_summary' => '',
                        'prayer' => ''
                    ]
                ];
            }
        }
        return $stack;
    }

    private static function _key_city( &$stack ) {
        if ( ! empty( $stack['cities'] ) ) {
            // focus block
            $cities = $stack['cities'];
            shuffle( $cities );
            $content = 'Pray that God raises up new churches in the city of '.$cities[0]['full_name'].'.';
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

    private static function _least_reached( &$stack ) {
        if ( ! empty( $stack['least_reached'] ) ) {
            $stack['list'][] = [
                'type' => 'least_reached_block',
                'data' => [
                    'section_label' => 'Least Reached',
                    'focus_label' => $stack['least_reached']['name'],
                    'image_url' => pg_jp_image( 'pid3', $stack['least_reached']['PeopleID3'] ), // ion icons from /pages/fonts/ionicons/
                    'section_summary' => 'The '.$stack['least_reached']['name'].' people in ' . $stack['location']['full_name'] . ' are a least reached people group, according to Joshua Project. They are classified as '.$stack['least_reached']['AffinityBloc'].' and speak '.$stack['least_reached']['PrimaryLanguageName'].'. Primarily, they follow '.$stack['least_reached']['PrimaryReligion'].' and '.$stack['least_reached']['PercentEvangelical'].'% are suspected of being believers.',
                    'prayer' => 'Pray that the ' . $stack['location']['believers'] . ' believers in ' . $stack['location']['full_name'] . ' to boldly witnesses to the '.$stack['least_reached']['name'].' near them.'
                ]
            ];
        }
        return $stack;
    }

    private static function _photos( &$stack, int $position ) {
        if ( empty( $position ) ) {
            $position = 2;
        }
        $images = pg_images( $stack['location']['grid_id'], true );

        if ( ! empty( $images['photos'] ) ) {

            $text = [
                [
                    'section_label' => 'One Shot Prayer Walk',
                    'section_summary' => 'What people, places, activities or culture do you see? <br>Do you see conditions of education, economy, religion, environment? <br>How could you pray for that?',
                    'prayer' => 'Spirit, what do you want prayed for '.$stack['location']['full_name'].'?',
                ],
                [
                    'section_label' => 'One Shot Prayer Walk',
                    'section_summary' => 'What people, places, activities or culture do you see? <br>Do you see conditions of education, economy, religion, environment? <br>How could you pray for that?',
                    'prayer' => 'Spirit, what do you want prayed for '.$stack['location']['full_name'].'?',
                ],
                [
                    'section_label' => 'One Shot Prayer Walk',
                    'section_summary' => 'What people, places, activities or culture do you see? <br>Do you see condition of education, economy, religion, environment? <br>How could you pray for that?',
                    'prayer' => 'Spirit, what do you want prayed for '.$stack['location']['full_name'].'?',
                ],
                [
                    'section_label' => 'One Shot Prayer Walk',
                    'section_summary' => 'What people, places, activities or culture do you see? <br>Do you see conditions of education, economy, religion, environment? <br>How could you pray for that?',
                    'prayer' => 'Spirit, what do you want prayed for the people of '.$stack['location']['full_name'].'?',
                ],
                [
                    'section_label' => 'One Shot Prayer Walk',
                    'section_summary' => 'What people, places, activities or culture do you see? <br>Do you see conditions of education, economy, religion, environment? <br>How could you pray for that?',
                    'prayer' => 'Spirit, what do you want prayed for the people of '.$stack['location']['full_name'].'?',
                ],
                [
                    'section_label' => 'One Shot Prayer Walk',
                    'section_summary' => 'What people, places, activities or culture do you see? <br>Do you see conditions of education, economy, religion, environment? <br>How could you pray for that?',
                    'prayer' => 'Spirit, what do you want prayed for '.$stack['location']['full_name'].'?',
                ],
                [
                    'section_label' => 'One Shot Prayer Walk',
                    'section_summary' => 'What people, places, activities or culture do you see? <br>Do you see conditions of education, economy, religion, environment? <br>How could you pray for that?',
                    'prayer' => 'Spirit, what do you want prayed for '.$stack['location']['full_name'].'?',
                ],
            ];

            $image_url = $images['photos'][array_rand( $images['photos'] )];
            $text_index = array_rand( $text );
            $template = [
                'type' => 'photo_block',
                'data' => [
                    'section_label' => $text[$text_index]['section_label'],
                    'location_label' => 'Photo from the ' . $stack['location']['admin_level_name'] . ' of ' . $stack['location']['full_name'],
                    'url' => $image_url,
                    'section_summary' => $text[$text_index]['section_summary'],
                    'prayer' => $text[$text_index]['prayer'],
                ]
            ];

            $stack['list'] = array_merge( array_slice( $stack['list'], 0, $position ), [ $template ], array_slice( $stack['list'], $position ) );
        }

        return $stack;
    }
    private static function _prayers( &$stack, int $position ) {

        if ( empty( $position ) ) {
            $position = 4;
        }

        $blocks = [];

        if ( 'non_believers' === $stack['favor'] ) {
            $blocks[] = [
                'type' => 'prayer_block',
                'data' => [
                    'section_label' => 'Movement Prayer',
                    'icon_color' => 'red',
                    'verse' => 'Don’t you have a saying, ‘It’s still four months until harvest’? I tell you, open your eyes and look at the fields! They are ripe for harvest.',
                    'reference' => 'John 4:35',
                    'prayer' => 'Pray for consistent and clear Kingdom vision casting and modeling by movement catalysts and leaders. Ask that all in movements love God and others, worship in Spirit and truth, and share the Good News with those who have not yet heard.',
                ]
            ];
        }

        else if ( 'christian_adherents' === $stack['favor'] ) {
            $blocks[] = [
                'type' => 'prayer_block',
                'data' => [
                    'section_label' => 'Movement Prayer',
                    'icon_color' => 'orange',
                    'verse' => 'Don’t you have a saying, ‘It’s still four months until harvest’? I tell you, open your eyes and look at the fields! They are ripe for harvest.',
                    'reference' => 'John 4:35',
                    'prayer' => 'Pray for consistent and clear Kingdom vision casting and modeling by movement catalysts and leaders. Ask that all in movements love God and others, worship in Spirit and truth, and share the Good News with those who have not yet heard.',
                ]
            ];
        }

        else if ( 'believers' === $stack['favor'] ) {
            $blocks[] = [
                'type' => 'prayer_block',
                'data' => [
                    'section_label' => 'Movement Prayer',
                    'icon_color' => 'green',
                    'verse' => 'I am the vine; you are the branches. If you remain in me and I in you, you will bear much fruit; apart from me you can do nothing.',
                    'reference' => 'John 15:5',
                    'prayer' => 'Pray that every disciple and leader in movements would remain rooted in abiding with Jesus. Ask that ministry activities not distract from this.',
                ]
            ];
        }

        $blocks[] = [
            'type' => 'prayer_block',
            'data' => [
                'section_label' => 'Movement Prayer',
                'icon_color' => 'green',
                'verse' => 'Father, even as we pray for people of '.$stack['location']['name'].' and long to see disciples made and multiplied, we cry out for a prayer movement to stir inside and outside of '.$stack['location']['full_name'].'.',
                'reference' => '',
                'prayer' => '',
            ]
        ];
        $blocks[] = [
            'type' => 'prayer_block',
            'data' => [
                'section_label' => 'Movement Prayer',
                'icon_color' => 'green',
                'verse' => 'Lord, stir the hearts of Your people to agree with You and with one another in strong faith, passion, and perseverance to see You build Your Church in '.$stack['location']['full_name'].'.',
                'reference' => '',
                'prayer' => '',
            ]
        ];
        $blocks[] = [
            'type' => 'prayer_block',
            'data' => [
                'section_label' => 'Movement Prayer',
                'icon_color' => 'green',
                'verse' => 'Lord we pray you unite believers to pray at all times in the Spirit, with all prayer and supplication, for spiritual breakthrough and protection and transformation throughout '.$stack['location']['full_name'].' in this generation.',
                'reference' => '',
                'prayer' => '',
            ]
        ];
        $blocks[] = [
            'type' => 'prayer_block',
            'data' => [
                'section_label' => 'Movement Prayer',
                'icon_color' => 'orange',
                'verse' => 'Lord, help the people of '.$stack['location']['full_name'].' to discover the essence of being a disciple, making disciples, and how to plant churches that multiply.',
                'reference' => '',
                'prayer' => '',
            ]
        ];
        $blocks[] = [
            'type' => 'prayer_block',
            'data' => [
                'section_label' => 'Movement Prayer',
                'icon_color' => 'red',
                'verse' => 'God, please help the people of '.$stack['location']['full_name'].' to become disciples who hear from you and then obey you.',
                'reference' => '',
                'prayer' => '',
            ]
        ];
        $blocks[] = [
            'type' => 'prayer_block',
            'data' => [
                'section_label' => 'Movement Prayer',
                'icon_color' => 'green',
                'verse' => 'Father, we pray that the people of '.$stack['location']['full_name'].' that they will learn to study the Bible, understand it, obey it, and share it.',
                'reference' => '',
                'prayer' => '',
            ]
        ];
        $blocks[] = [
            'type' => 'prayer_block',
            'data' => [
                'section_label' => 'Movement Prayer',
                'icon_color' => 'green',
                'verse' => 'God, we pray both the men and women of '.$stack['location']['full_name'].' that they will find ways to meet in groups of two or three to encourage and correct one another from your Word.',
                'reference' => '',
                'prayer' => '',
            ]
        ];
        $blocks[] = [
            'type' => 'prayer_block',
            'data' => [
                'section_label' => 'Movement Prayer',
                'icon_color' => 'green',
                'verse' => 'Lord, we pray for the believers in '.$stack['location']['full_name'].' to be more like Jesus, which is the greatest blessing we can offer them.',
                'reference' => '',
                'prayer' => '',
            ]
        ];
        $blocks[] = [
            'type' => 'prayer_block',
            'data' => [
                'section_label' => 'Movement Prayer',
                'icon_color' => 'green',
                'verse' => 'God, we pray for the believers in '.$stack['location']['full_name'].' that they will know how easy it is to spend an hour in prayer with you, and will do it.',
                'reference' => '',
                'prayer' => '',
            ]
        ];
        $blocks[] = [
            'type' => 'prayer_block',
            'data' => [
                'section_label' => 'Movement Prayer',
                'icon_color' => 'green',
                'verse' => 'Father, we pray for the believers in '.$stack['location']['full_name'].' to be good stewards of their relationships.',
                'reference' => '',
                'prayer' => '',
            ]
        ];
        $blocks[] = [
            'type' => 'prayer_block',
            'data' => [
                'section_label' => 'Movement Prayer',
                'icon_color' => 'green',
                'verse' => 'God, we pray for the believers in '.$stack['location']['full_name'].' to be generous so that they would be worthy of greater investment by you.',
                'reference' => 'Matthew 25:28',
                'prayer' => '',
            ]
        ];

        $stack['list'] = array_merge( array_slice( $stack['list'], 0, $position ), array( $blocks[array_rand( $blocks )] ), array_slice( $stack['list'], $position ) );

        return $stack;
    }

    private static function _verses( &$stack, int $position ) {

        if ( empty( $position ) ) {
            $position = 4;
        }

        $blocks = [];

        $blocks[] = [
            'type' => 'verse_block',
            'data' => [
                'section_label' => 'Scripture',
                'icon_color' => 'red',
                'verse' => 'And this gospel of the kingdom will be preached in the whole world as a testimony to all nations, and then the end will come.',
                'reference' => 'Matthew 24:14',
                'prayer' => 'Pray the gospel is preached in ' . $stack['location']['name'] . '.',
            ]
        ];
        $blocks[] = [
            'type' => 'verse_block',
            'data' => [
                'section_label' => 'Scripture',
                'icon_color' => 'red',
                'verse' => '"Go therefore and make disciples of all nations, baptizing them in the name of the Father and of the Son and of the Holy Spirit, teaching them to observe all that I have commanded you; and lo, I am with you always, to the close of the age."',
                'reference' => 'Matthew 28:19-20',
                'prayer' => 'Pray the gospel is preached in to all nations including ' . $stack['location']['name'] . '.',
            ]
        ];
        $blocks[] = [
            'type' => 'verse_block',
            'data' => [
                'section_label' => 'Scripture',
                'icon_color' => 'green',
                'verse' => '"For the earth will be filled with the knowledge of the glory of the LORD as the waters cover the sea."',
                'reference' => 'Habakkuk 2:14',
                'prayer' => 'Pray knowledge of the glory of the Lord fills ' . $stack['location']['full_name'] . '.',
            ]
        ];
        $blocks[] = [
            'type' => 'verse_block',
            'data' => [
                'section_label' => 'Scripture',
                'icon_color' => 'red',
                'verse' => '"How then will they call on him in whom they have not believed? And how are they to believe in him of whom they have never heard? And how are they to hear without someone preaching? ... So faith comes from hearing, and hearing through the word of Christ"',
                'reference' => 'Rom. 10:14,17',
                'prayer' => 'Open the hearts and lips of Your '.$stack['location']['believers'].' people in '.$stack['location']['name'].' to humbly and boldly and broadly share Your Good News for the glory of Your name.',
            ]
        ];
        $blocks[] = [
            'type' => 'verse_block',
            'data' => [
                'section_label' => 'Scripture',
                'icon_color' => 'green',
                'verse' => '"All Scripture is breathed out by God and profitable for teaching, for reproof, for correction, and for training in righteousness, that the man of God may be complete, equipped for every good work"',
                'reference' => '2 Timothy 3:16-17',
                'prayer' => 'May Your Word, O God, be the foundational source of truth and discernment for all matters of faith and practice and shepherding among the people of '.$stack['location']['full_name'].'.',
            ]
        ];
        $blocks[] = [
            'type' => 'verse_block',
            'data' => [
                'section_label' => 'Scripture',
                'icon_color' => 'green',
                'verse' => '"All Scripture is breathed out by God and profitable for teaching, for reproof, for correction, and for training in righteousness, that the man of God may be complete, equipped for every good work"',
                'reference' => '2 Timothy 3:16-17',
                'prayer' => 'We pray against competing spiritual authorities '.$stack['location']['name'].' and ask that as biblical understanding increases that love, dependence upon, and obedience to You would correspondingly increase.',
            ]
        ];
        $blocks[] = [
            'type' => 'verse_block',
            'data' => [
                'section_label' => 'Scripture',
                'icon_color' => 'green',
                'verse' => '"And when they had appointed elders for them in every church, with prayer and fasting they committed them to the Lord in whom they had believed."',
                'reference' => 'Acts 14:23',
                'prayer' => 'Father, just as the earliest church-planting efforts included the appointment of local leaders over those young congregations, we pray for qualified locals to humbly serve and lead Your Church in '.$stack['location']['full_name'].'.',
            ]
        ];
        $blocks[] = [
            'type' => 'verse_block',
            'data' => [
                'section_label' => 'Scripture',
                'icon_color' => 'green',
                'verse' => '"And when they had appointed elders for them in every church, with prayer and fasting they committed them to the Lord in whom they had believed."',
                'reference' => 'Acts 14:23',
                'prayer' => 'Lord we ask for the appointment of local leaders in '.$stack['location']['name'].' who would passionately give themselves to prayer and the ministry of Your Word.',
            ]
        ];
        $blocks[] = [
            'type' => 'verse_block',
            'data' => [
                'section_label' => 'Scripture',
                'icon_color' => 'green',
                'verse' => '"Now when they saw the boldness of Peter and John, and perceived that they were uneducated, common men, they were astonished. And they recognized that they had been with Jesus"',
                'reference' => 'Acts 4:13',
                'prayer' => 'Lord, while we acknowledge the value of education and training, we affirm the superior value of abiding in and being with You. We pray for the '.$stack['location']['believers'].' believers in '.$stack['location']['name'].' that they abide in You.',
            ]
        ];
        $blocks[] = [
            'type' => 'verse_block',
            'data' => [
                'section_label' => 'Scripture',
                'icon_color' => 'green',
                'verse' => '"Now when they saw the boldness of Peter and John, and perceived that they were uneducated, common men, they were astonished. And they recognized that they had been with Jesus"',
                'reference' => 'Acts 4:13',
                'prayer' => 'Lord, we ask You to raise up men and women of godly character as lay leaders to serve and shepherd Your people. Let not formal training, diplomas, or titles be the ultimate criteria for influence or a bottleneck to spiritual maturity or church growth.',
            ]
        ];
        $blocks[] = [
            'type' => 'verse_block',
            'data' => [
                'section_label' => 'Scripture',
                'icon_color' => 'green',
                'verse' => '"Day after day, in the temple courts and from house to house, they never stopped teaching and proclaiming the good news"',
                'reference' => 'Acts 5:42',
                'prayer' => 'Father, give to the '.$stack['location']['believers'].' believers in '.$stack['location']['name'].' the same fire and passion for the truth of your Son, as the early church. May the message jump from one house to another house throughout the entire '.$stack['location']['admin_level_name'].'.',
            ]
        ];
        $blocks[] = [
            'type' => 'verse_block',
            'data' => [
                'section_label' => 'Scripture',
                'icon_color' => 'green',
                'verse' => '"And there arose on that day a great persecution against the church in Jerusalem, and they were all scattered throughout the regions of Judea and Samaria, except the apostles. ... Now those who were scattered went about preaching the word"',
                'reference' => 'Acts 8:1b,4',
                'prayer' => 'Father, we ask You to give the '.$stack['location']['believers'].' believers in '.$stack['location']['full_name'].' a collective vision to preach Your word and plant Your church, even in the face of persecution.',
            ]
        ];
        $blocks[] = [
            'type' => 'verse_block',
            'data' => [
                'section_label' => 'Scripture',
                'icon_color' => 'green',
                'verse' => '"And there arose on that day a great persecution against the church in Jerusalem, and they were all scattered throughout the regions of Judea and Samaria, except the apostles. ... Now those who were scattered went about preaching the word"',
                'reference' => 'Acts 8:1b,4',
                'prayer' => 'Father, we know the expansion of your church is not a job reserved for foreign missionaries or paid staff or specifically gifted individuals. We affirm that you gave the Great Commission to your Bride and we pray that the church of '.$stack['location']['full_name'].' powerfully proclaim you even in persecution.',
            ]
        ];
        $blocks[] = [
            'type' => 'verse_block',
            'data' => [
                'section_label' => 'Scripture',
                'icon_color' => 'green',
                'verse' => '"And the Lord added to their number day by day those who were being saved. ... And more than ever believers were added to the Lord, multitudes of both men and women, ... And the word of God continued to increase, and the number of disciples multiplied greatly ..."',
                'reference' => 'Acts 2:47b; 5:14; 6:7',
                'prayer' => 'O Lord, in Jesus’ name, we pray for this kind of rapid reproduction in '.$stack['location']['full_name'].'. May Your word increase and may disciples multiply.',
            ]
        ];
        $blocks[] = [
            'type' => 'verse_block',
            'data' => [
                'section_label' => 'Scripture',
                'icon_color' => 'green',
                'verse' => '"And the Lord added to their number day by day those who were being saved. ... And more than ever believers were added to the Lord, multitudes of both men and women, ... And the word of God continued to increase, and the number of disciples multiplied greatly ..."',
                'reference' => 'Acts 2:47b; 5:14; 6:7',
                'prayer' => 'O Lord, in Jesus’ name, we pray for this kind of rapid reproduction in '.$stack['location']['full_name'].'. May Your word increase and may disciples multiply.',
            ]
        ];
        $blocks[] = [
            'type' => 'verse_block',
            'data' => [
                'section_label' => 'Scripture',
                'icon_color' => 'green',
                'verse' => '"And the Lord added to their number day by day those who were being saved. ... And the word of God continued to increase, and the number of disciples multiplied greatly ..."',
                'reference' => 'Acts 2:47b; 5:14; 6:7',
                'prayer' => 'Bless Your church in '.$stack['location']['full_name'].' with spiritual gifts, godly leaders, unity in the faith and in the knowledge of Your Son, integrity, and an interdependence that nurtures the church in love.',
            ]
        ];
        $blocks[] = [
            'type' => 'verse_block',
            'data' => [
                'section_label' => 'Scripture',
                'icon_color' => 'green',
                'verse' => '"And the Lord added to their number day by day those who were being saved. ... And the word of God continued to increase, and the number of disciples multiplied greatly ..."',
                'reference' => 'Acts 2:47b; 5:14; 6:7',
                'prayer' => 'For the good of '.$stack['location']['full_name'].' and the glory of Your name, we pray for healthy churches here that are characterized by worship in spirit and truth, love-motivated gospel-sharing, intentional discipleship, and genuine life-on-life community.',
            ]
        ];
        $blocks[] = [
            'type' => 'verse_block',
            'data' => [
                'section_label' => 'Scripture',
                'icon_color' => 'green',
                'verse' => '"Pray also for me, that whenever I speak, words may be given to me so that I will fearlessly make known the mystery of the gospel."',
                'reference' => 'Ephesians 6:18',
                'prayer' => 'Father, we pray every disciple in '.$stack['location']['name'].' boldly proclaim the mystery of the gospel.',
            ]
        ];
        $blocks[] = [
            'type' => 'verse_block',
            'data' => [
                'section_label' => 'Scripture',
                'icon_color' => 'green',
                'verse' => '"By this everyone will know that you are my disciples, if you love one another."',
                'reference' => 'John 13:35',
                'prayer' => 'Lord, stir the hearts of Your people in '.$stack['location']['full_name'].' to agree with You and with one another in strong love that their '. number_format( $stack['location']['non_christians_int'] + $stack['location']['christian_adherents_int'] ) .' neighbors might know that they are yours.',
            ]
        ];


        $stack['list'] = array_merge( array_slice( $stack['list'], 0, $position ), array( $blocks[array_rand( $blocks, 1 )] ), array_slice( $stack['list'], $position ) );
        return $stack;
    }

    private static function _people_groups( &$stack ) {
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

                $values[] = [
                    'name' => $group['name'],
                    'image_url' => $image,
                    'description' => $group['name'] . '<br>(' . $group['PrimaryReligion'].')',
                    'progress' => $group['JPScale'],
                    'progress_image_url' => $base_url . 'progress/' . $image_list['progress'][$group['JPScale']],
                    'least_reached' => $group['LeastReached']
                ];
            }
            if ( ! empty( $values ) ) {
                $stack['list'][] = [
                    'type' => 'people_groups_list',
                    'data' => [
                        'section_label' => 'People Groups In The Area',
                        'values' => $values,
                        'section_summary' => '',
                        'prayer' => 'Pray that God call his worshippers out of these groups.'
                    ]
                ];
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
        $grid_record = array_merge( $grid_record, [ 'admin_level_name' => $admin_level_name, 'admin_level_name_plural' => $admin_level_name_plural ] );


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


        // build people groups list
        $people_groups = $wpdb->get_results($wpdb->prepare( "
            SELECT lgpg.*, FORMAT(lgpg.population, 0) as population, 'current' as query_level
                FROM $wpdb->location_grid_people_groups lgpg
                WHERE
                    lgpg.longitude < %d AND /* east */
                    lgpg.longitude >  %d AND /* west */
                    lgpg.latitude < %d AND /* north */
                    lgpg.latitude > %d AND /* south */
                    lgpg.admin0_grid_id = %d AND
                    lgpg.PrimaryReligion != 'Christianity'
                ORDER BY lgpg.LeastReached DESC, lgpg.population DESC
                LIMIT 10
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
                        lgpg.admin0_grid_id = %d AND
                        lgpg.PrimaryReligion != 'Christianity'
                    ORDER BY lgpg.LeastReached DESC, lgpg.population DESC
                    LIMIT 10
            ", $grid_record['p_east_longitude'], $grid_record['p_west_longitude'], $grid_record['p_north_latitude'], $grid_record['p_south_latitude'], $grid_record['admin0_grid_id'] ), ARRAY_A );
        }
        if ( empty( $people_groups ) ) {
            $people_groups = [];
        }
        shuffle( $people_groups ); // randomize results

        $least_reached = [];
        if ( ! empty( $people_groups ) ) {
            foreach ( $people_groups as $pg ) {
                if ( 'Y' === $pg['LeastReached'] ) {
                    $least_reached = $pg; // get first least reached group
                    break;
                }
            }
        }

        $people_groups = array_slice( $people_groups, 0, 5, true ); // trim to first 5 shuffled results

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
            default:
                break;
        }

        return number_format( intval( $return_value ) );
    }

}
