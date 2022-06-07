<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.

class PG_Stacker_Text {
    public static function photos_text( $stack ) : array {
        /**
         * Photos Block
         */
        return [
            [
                'section_summary' => 'What people, places, activities or culture do you see? <br>Do you see conditions of education, economy, religion, environment? <br>How could you pray for that?',
                'prayer' => 'Spirit, what do you want prayed for '.$stack['location']['full_name'].'?',
            ],
            [
                'section_summary' => 'What people, places, activities or culture do you see? <br>Do you see conditions of education, economy, religion, environment? <br>How could you pray for that?',
                'prayer' => 'Spirit, what do you want prayed for '.$stack['location']['full_name'].'?',
            ],
            [
                'section_summary' => 'What people, places, activities or culture do you see? <br>Do you see condition of education, economy, religion, environment? <br>How could you pray for that?',
                'prayer' => 'Spirit, what do you want prayed for '.$stack['location']['full_name'].'?',
            ],
            [
                'section_summary' => 'What people, places, activities or culture do you see? <br>Do you see conditions of education, economy, religion, environment? <br>How could you pray for that?',
                'prayer' => 'Spirit, what do you want prayed for the people of '.$stack['location']['full_name'].'?',
            ],
            [
                'section_summary' => 'What people, places, activities or culture do you see? <br>Do you see conditions of education, economy, religion, environment? <br>How could you pray for that?',
                'prayer' => 'Spirit, what do you want prayed for the people of '.$stack['location']['full_name'].'?',
            ],
            [
                'section_summary' => 'What people, places, activities or culture do you see? <br>Do you see conditions of education, economy, religion, environment? <br>How could you pray for that?',
                'prayer' => 'Spirit, what do you want prayed for '.$stack['location']['full_name'].'?',
            ],
            [
                'section_summary' => 'What people, places, activities or culture do you see? <br>Do you see conditions of education, economy, religion, environment? <br>How could you pray for that?',
                'prayer' => 'Spirit, what do you want prayed for '.$stack['location']['full_name'].'?',
            ],
        ];
    }

    public static function least_reached_text( $stack ) : array {
        /**
         * Least Reached Block
         */
        return [
            [
                'section_summary' => 'The '.$stack['least_reached']['name'].' people in ' . $stack['location']['full_name'] . ' are a least reached people group, according to Joshua Project. They are classified as '.$stack['least_reached']['AffinityBloc'].' and speak '.$stack['least_reached']['PrimaryLanguageName'].'. Primarily, they follow '.$stack['least_reached']['PrimaryReligion'].' and '. number_format( (float) $stack['least_reached']['PercentEvangelical'], 1 ).'% are suspected of being believers.',
                'prayer' => 'Pray that the ' . $stack['location']['believers'] . ' believers in ' . $stack['location']['full_name'] . ' to boldly witnesses to the '.$stack['least_reached']['name'].' near them.',
            ],
            [
                'section_summary' => 'The '.$stack['least_reached']['name'].' people in ' . $stack['location']['full_name'] . ' are a least reached people group, according to Joshua Project. They are classified as '.$stack['least_reached']['AffinityBloc'].' and speak '.$stack['least_reached']['PrimaryLanguageName'].'. Primarily, they follow '.$stack['least_reached']['PrimaryReligion'].' and '. number_format( (float) $stack['least_reached']['PercentEvangelical'], 1 ).'% are suspected of being believers.',
                'prayer' => 'Pray God send bold witnesses to the '.$stack['least_reached']['name'].'.',
            ],
        ];
    }

    public static function key_city_text( $stack, $key_city ) : array {
        /**
         * Key City Block
         */
        return [
            [
                'section_summary' => 'Pray that God raises up new churches in the city of '.$key_city['full_name'].'.',
            ],
        ];
    }

    public static function cities_text( $stack ) : array {
        /**
         * Key City Block
         */
        return [
            [
                'prayer' => 'Pray that God encourage his people in all these cities.',
            ],
            [
                'prayer' => 'Pray that new churches are planted in these cities.',
            ],
        ];
    }

    public static function prayer_text( $stack ) : array {

        return [
            /**
             * PRAYERS TARGETING BELIEVERS
             */
            'believers' => [
                [
                    'prayer' => 'Father, even as we pray for people of '.$stack['location']['name'].' and long to see disciples made and multiplied, we cry out for a prayer movement to stir inside and outside of '.$stack['location']['full_name'].'.',
                ],
                [
                    'prayer' => 'Lord, stir the hearts of Your people to agree with You and with one another in strong faith, passion, and perseverance to see You build Your Church in '.$stack['location']['full_name'].'.',
                ],
                [
                    'prayer' => 'Lord we pray you unite believers to pray at all times in the Spirit, with all prayer and supplication, for spiritual breakthrough and protection and transformation throughout '.$stack['location']['full_name'].' in this generation.',
                ],
                [
                    'prayer' => 'Father, we pray that the people of '.$stack['location']['full_name'].' that they will learn to study the Bible, understand it, obey it, and share it.',
                ],
                [
                    'prayer' => 'God, we pray both the men and women of '.$stack['location']['full_name'].' that they will find ways to meet in groups of two or three to encourage and correct one another from your Word.',
                ],
                [
                    'prayer' => 'Lord, we pray for the believers in '.$stack['location']['full_name'].' to be more like Jesus, which is the greatest blessing we can offer them.',
                ],
                [
                    'prayer' => 'God, we pray for the believers in '.$stack['location']['full_name'].' that they will know how easy it is to spend an hour in prayer with you, and will do it.',
                ],
                [
                    'prayer' => 'Father, we pray for the believers in '.$stack['location']['full_name'].' to be good stewards of their relationships.',
                ],
                [
                    'prayer' => 'God, we pray for the believers in '.$stack['location']['full_name'].' to be generous so that they would be worthy of greater investment by you.',
                ],
            ],
            /**
             * PRAYERS TARGETING CULTURAL CHRISTIANS
             */
            'christian_adherents' => [
                [
                    'prayer' => 'Lord, help the people of '.$stack['location']['full_name'].' to discover the essence of being a disciple, making disciples, and how to plant churches that multiply.',
                ],
                [
                    'prayer' => 'Father, we pray that the people of '.$stack['location']['full_name'].' that they will learn to study the Bible, understand it, obey it, and share it.',
                ],
            ],
            /**
             * PRAYERS TARGETING NON CHRISTIANS
             */
            'non_christians' => [
                [
                    'prayer' => 'God, please help the people of '.$stack['location']['full_name'].' to become disciples who hear from you and then obey you.',
                ],
                [
                    'prayer' => 'God, we pray both the men and women of '.$stack['location']['full_name'].' that they will find ways to meet in groups of two or three to encourage and correct one another from your Word.',
                ],
            ]
        ];

    }


    public static function faith_status_text( $stack ) : array {

        return [
            /**
             * PRAYERS TARGETING BELIEVERS
             */
            'believers' => [
                [
                    'prayer' => 'Pray that the '.$stack['location']['believers'].' believers in '.$stack['location']['full_name'].' to be bold witnesses to the '.$stack['location']['all_lost'].' neighbors around them.',
                ],
                [
                    'prayer' => 'The '.$stack['location']['admin_level_name'].' of <strong>'.$stack['location']['full_name'].'</strong> has a population of <strong>'.$stack['location']['population'].'</strong>. We estimate there is <strong>1</strong> believer for every <strong>'. $stack['location']['lost_per_believer'] .'</strong> neighbors who need Jesus.',
                ],
            ],
            /**
             * PRAYERS TARGETING CULTURAL CHRISTIANS
             */
            'christian_adherents' => [
                [
                    'prayer' => 'Lord, help the people of '.$stack['location']['full_name'].' to discover the essence of being a disciple, making disciples, and how to plant churches that multiply.',
                ],
            ],
            /**
             * PRAYERS TARGETING NON CHRISTIANS
             */
            'non_christians' => [
                [
                    'prayer' => 'God, please help the people of '.$stack['location']['full_name'].' to become disciples who hear from you and then obey you.',
                ],
            ]
        ];

    }


}
