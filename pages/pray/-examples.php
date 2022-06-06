<?php


function example_templates( $stack ) {
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
            'size' => ( 1000 > 400 ) ? 2 : 3, // 2 or 3
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
