<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.

class PG_Stacker_Text
{
    public static function photos_text( $stack): array
    {
        /**
         * Photos Block
         */
        return [
            [
                'section_summary' => 'What people, places, activities or culture could you pray for in this photo?',
                'prayer' => '',
            ],
            [
                'section_summary' => 'What conditions of education, economy, religion, or environment could you pray for here?',
                'prayer' => '',
            ],
        ];
    }

    public static function least_reached_text( $stack): array
    {
        /**
         * Least Reached Block
         */
        return [
            [
                'section_summary' => '',
                'prayer' => 'Lord we ask you on behalf of the ' . $stack['least_reached']['name'] . ' people. ' . number_format( (float) $stack['least_reached']['PercentEvangelical'], 1 ) . '% are known to be believers. Oh God, share with them the great gift of your son Jesus and your kingdom.',
            ],
            [
                'section_summary' => '',
                'prayer' => 'Lord, please remember the ' . $stack['least_reached']['name'] . ' people. You said you wanted worshippers of every tongue and tribe and nation, yet we know of no worshippers among them.',
            ],
        ];
    }

    public static function key_city_text( $stack, $key_city): array
    {
        /**
         * Key City Block
         */
        return [
            [
                'section_summary' => 'Pray that God raises up new churches in the city of ' . $key_city['full_name'] . '.',
            ],
        ];
    }

    public static function cities_text( $stack): array
    {
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

    public static function prayer_text( $stack): array
    {

        return [
            /**
             * PRAYERS TARGETING BELIEVERS
             */
            'believers' => [
                [
                    'prayer' => 'Father, even as we pray for people of ' . $stack['location']['name'] . ' and long to see disciples made and multiplied, we cry out for a prayer movement to stir inside and outside of ' . $stack['location']['full_name'] . '.',
                ],
                [
                    'prayer' => 'Lord, stir the hearts of Your people to agree with You and with one another in strong faith, passion, and perseverance to see You build Your Church in ' . $stack['location']['full_name'] . '.',
                ],
                [
                    'prayer' => 'Lord we pray you unite believers to pray at all times in the Spirit, with all prayer and supplication, for spiritual breakthrough and protection and transformation throughout ' . $stack['location']['full_name'] . ' in this generation.',
                ],
                [
                    'prayer' => 'Father, we pray that the people of ' . $stack['location']['full_name'] . ' that they will learn to study the Bible, understand it, obey it, and share it.',
                ],
                [
                    'prayer' => 'God, we pray both the men and women of ' . $stack['location']['full_name'] . ' that they will find ways to meet in groups of two or three to encourage and correct one another from your Word.',
                ],
                [
                    'prayer' => 'Lord, we pray for the believers in ' . $stack['location']['full_name'] . ' to be more like Jesus, which is the greatest blessing we can offer them.',
                ],
                [
                    'prayer' => 'God, we pray for the believers in ' . $stack['location']['full_name'] . ' that they will know how easy it is to spend an hour in prayer with you, and will do it.',
                ],
                [
                    'prayer' => 'Father, we pray for the believers in ' . $stack['location']['full_name'] . ' to be good stewards of their relationships.',
                ],
                [
                    'prayer' => 'God, we pray for the believers in ' . $stack['location']['full_name'] . ' to be generous so that they would be worthy of greater investment by you.',
                ],
            ],
            /**
             * PRAYERS TARGETING CULTURAL CHRISTIANS
             */
            'christian_adherents' => [
                [
                    'prayer' => 'Lord, help the people of ' . $stack['location']['full_name'] . ' to discover the essence of being a disciple, making disciples, and how to plant churches that multiply.',
                ],
                [
                    'prayer' => 'Father, we pray that the people of ' . $stack['location']['full_name'] . ' that they will learn to study the Bible, understand it, obey it, and share it.',
                ],
            ],
            /**
             * PRAYERS TARGETING NON CHRISTIANS
             */
            'non_christians' => [
                [
                    'prayer' => 'God, please help the people of ' . $stack['location']['full_name'] . ' to become disciples who hear from you and then obey you.',
                ],
                [
                    'prayer' => 'God, we pray both the men and women of ' . $stack['location']['full_name'] . ' that they will find ways to meet in groups of two or three to encourage and correct one another from your Word.',
                ],
            ]
        ];

    }

    public static function faith_status_text( $stack): array
    {

        return [
            /**
             * PRAYERS TARGETING BELIEVERS
             */
            'believers' => [
                [
//                    'prayer' => 'Pray that the '.$stack['location']['believers'].' believers in '.$stack['location']['full_name'].' to be bold witnesses to the '.$stack['location']['all_lost'].' neighbors around them.',
                    'prayer' => '',
                ],
                [
//                    'prayer' => 'The ' . $stack['location']['admin_level_name'] . ' of <strong>' . $stack['location']['full_name'] . '</strong> has a population of <strong>' . $stack['location']['population'] . '</strong>. We estimate there is <strong>1</strong> believer for every <strong>' . $stack['location']['lost_per_believer'] . '</strong> neighbors who need Jesus.',
                    'prayer' => '',
                ],
            ],
            /**
             * PRAYERS TARGETING CULTURAL CHRISTIANS
             */
            'christian_adherents' => [
                [
//                    'prayer' => 'Lord, help the people of ' . $stack['location']['full_name'] . ' to discover the essence of being a disciple, making disciples, and how to plant churches that multiply.',
                    'prayer' => '',
                ],
            ],
            /**
             * PRAYERS TARGETING NON CHRISTIANS
             */
            'non_christians' => [
                [
//                    'prayer' => 'God, please help the people of '.$stack['location']['full_name'].' to become disciples who hear from you and then obey you.',
                    'prayer' => '',
                ],
            ]
        ];

    }

    public static function verse_text( $stack): array
    {

        return [
            /**
             * PRAYERS TARGETING BELIEVERS
             */
            'believers' => [
                [
                    'verse' => 'And this gospel of the kingdom will be preached in the whole world as a testimony to all nations, and then the end will come.',
                    'reference' => 'Matthew 24:14',
                    'prayer' => 'Pray the gospel is preached in ' . $stack['location']['name'] . '.',
                ],
                [
                    'verse' => '"Go therefore and make disciples of all nations, baptizing them in the name of the Father and of the Son and of the Holy Spirit, teaching them to observe all that I have commanded you; and lo, I am with you always, to the close of the age."',
                    'reference' => 'Matthew 28:19-20',
                    'prayer' => 'Pray the gospel is preached in to all nations including ' . $stack['location']['name'] . '.',
                ],
                [
                    'verse' => '"For the earth will be filled with the knowledge of the glory of the LORD as the waters cover the sea."',
                    'reference' => 'Habakkuk 2:14',
                    'prayer' => 'Pray knowledge of the glory of the Lord fills ' . $stack['location']['full_name'] . '.',
                ],
                [
                    'verse' => '"How then will they call on him in whom they have not believed? And how are they to believe in him of whom they have never heard? And how are they to hear without someone preaching? ... So faith comes from hearing, and hearing through the word of Christ"',
                    'reference' => 'Rom. 10:14,17',
                    'prayer' => 'Open the hearts and lips of Your ' . $stack['location']['believers'] . ' people in ' . $stack['location']['name'] . ' to humbly and boldly and broadly share Your Good News for the glory of Your name.',
                ],
                [
                    'verse' => '"All Scripture is breathed out by God and profitable for teaching, for reproof, for correction, and for training in righteousness, that the man of God may be complete, equipped for every good work"',
                    'reference' => '2 Timothy 3:16-17',
                    'prayer' => 'May Your Word, O God, be the foundational source of truth and discernment for all matters of faith and practice and shepherding among the people of ' . $stack['location']['full_name'] . '.',
                ],
                [
                    'verse' => '"All Scripture is breathed out by God and profitable for teaching, for reproof, for correction, and for training in righteousness, that the man of God may be complete, equipped for every good work"',
                    'reference' => '2 Timothy 3:16-17',
                    'prayer' => 'We pray against competing spiritual authorities ' . $stack['location']['name'] . ' and ask that as biblical understanding increases that love, dependence upon, and obedience to You would correspondingly increase.',
                ],
                [
                    'verse' => '"And when they had appointed elders for them in every church, with prayer and fasting they committed them to the Lord in whom they had believed."',
                    'reference' => 'Acts 14:23',
                    'prayer' => 'Father, just as the earliest church-planting efforts included the appointment of local leaders over those young congregations, we pray for qualified locals to humbly serve and lead Your Church in ' . $stack['location']['full_name'] . '.',
                ],
                [
                    'verse' => '"Now when they saw the boldness of Peter and John, and perceived that they were uneducated, common men, they were astonished. And they recognized that they had been with Jesus"',
                    'reference' => 'Acts 4:13',
                    'prayer' => 'Lord, while we acknowledge the value of education and training, we affirm the superior value of abiding in and being with You. We pray for the ' . $stack['location']['believers'] . ' believers in ' . $stack['location']['name'] . ' that they abide in You.',
                ],
                [
                    'verse' => '"Now when they saw the boldness of Peter and John, and perceived that they were uneducated, common men, they were astonished. And they recognized that they had been with Jesus"',
                    'reference' => 'Acts 4:13',
                    'prayer' => 'Lord, we ask You to raise up men and women of godly character as lay leaders to serve and shepherd Your people. Let not formal training, diplomas, or titles be the ultimate criteria for influence or a bottleneck to spiritual maturity or church growth.',
                ],
                [
                    'verse' => '"And the Lord added to their number day by day those who were being saved. ... And more than ever believers were added to the Lord, multitudes of both men and women, ... And the word of God continued to increase, and the number of disciples multiplied greatly ..."',
                    'reference' => 'Acts 2:47b; 5:14; 6:7',
                    'prayer' => 'O Lord, in Jesus’ name, we pray for this kind of rapid reproduction in ' . $stack['location']['full_name'] . '. May Your word increase and may disciples multiply.',
                ],
                [
                    'verse' => '"And the Lord added to their number day by day those who were being saved. ... And the word of God continued to increase, and the number of disciples multiplied greatly ..."',
                    'reference' => 'Acts 2:47b; 5:14; 6:7',
                    'prayer' => 'Bless Your church in ' . $stack['location']['full_name'] . ' with spiritual gifts, godly leaders, unity in the faith and in the knowledge of Your Son, integrity, and an interdependence that nurtures the church in love.',
                ],
                [
                    'verse' => '"And the Lord added to their number day by day those who were being saved. ... And the word of God continued to increase, and the number of disciples multiplied greatly ..."',
                    'reference' => 'Acts 2:47b; 5:14; 6:7',
                    'prayer' => 'For the good of ' . $stack['location']['full_name'] . ' and the glory of Your name, we pray for healthy churches here that are characterized by worship in spirit and truth, love-motivated gospel-sharing, intentional discipleship, and genuine life-on-life community.',
                ],
                [
                    'verse' => '"Pray also for me, that whenever I speak, words may be given to me so that I will fearlessly make known the mystery of the gospel."',
                    'reference' => 'Ephesians 6:18',
                    'prayer' => 'Father, we pray every disciple in ' . $stack['location']['name'] . ' boldly proclaim the mystery of the gospel.',
                ],
                [
                    'verse' => '"By this everyone will know that you are my disciples, if you love one another."',
                    'reference' => 'John 13:35',
                    'prayer' => 'Lord, stir the hearts of Your people in ' . $stack['location']['full_name'] . ' to agree with You and with one another in strong love that their ' . number_format( $stack['location']['non_christians_int'] + $stack['location']['christian_adherents_int'] ) . ' neighbors might know that they are yours.',
                ],
            ],
            /**
             * PRAYERS TARGETING CULTURAL CHRISTIANS
             */
            'christian_adherents' => [
                [
                    'verse' => '"Day after day, in the temple courts and from house to house, they never stopped teaching and proclaiming the good news"',
                    'reference' => 'Acts 5:42',
                    'prayer' => 'Father, give to the ' . $stack['location']['believers'] . ' believers in ' . $stack['location']['name'] . ' the same fire and passion for the truth of your Son, as the early church. May the message jump from one house to another house throughout the entire ' . $stack['location']['admin_level_name'] . '.',
                ],
                [
                    'verse' => '"And there arose on that day a great persecution against the church in Jerusalem, and they were all scattered throughout the regions of Judea and Samaria, except the apostles. ... Now those who were scattered went about preaching the word"',
                    'reference' => 'Acts 8:1b,4',
                    'prayer' => 'Father, we ask You to give the ' . $stack['location']['believers'] . ' believers in ' . $stack['location']['full_name'] . ' a collective vision to preach Your word and plant Your church, even in the face of persecution.',
                ],
            ],
            /**
             * PRAYERS TARGETING NON CHRISTIANS
             */
            'non_christians' => [
                [
                    'verse' => '"And there arose on that day a great persecution against the church in Jerusalem, and they were all scattered throughout the regions of Judea and Samaria, except the apostles. ... Now those who were scattered went about preaching the word"',
                    'reference' => 'Acts 8:1b,4',
                    'prayer' => 'Father, we know the expansion of your church is not a job reserved for foreign missionaries or paid staff or specifically gifted individuals. We affirm that you gave the Great Commission to your Bride and we pray that the church of ' . $stack['location']['full_name'] . ' powerfully proclaim you even in persecution.',
                ],
                [
                    'verse' => '"And the Lord added to their number day by day those who were being saved. ... And more than ever believers were added to the Lord, multitudes of both men and women, ... And the word of God continued to increase, and the number of disciples multiplied greatly ..."',
                    'reference' => 'Acts 2:47b; 5:14; 6:7',
                    'prayer' => 'O Lord, in Jesus’ name, we pray for this kind of rapid reproduction in ' . $stack['location']['full_name'] . '. May Your word increase and may disciples multiply.',
                ],
            ]
        ];

    }

    public static function demographics_content_text( $stack): array
    {

        return [
            /**
             * PRAYERS TARGETING BELIEVERS
             */
            'believers' => [
                [
                    'section_summary' => 'The ' . $stack['location']['admin_level_name'] . ' of <strong>' . $stack['location']['full_name'] . '</strong> has a population of <strong>' . $stack['location']['population'] . '</strong>.<br><br> We estimate ' . $stack['location']['name'] . ' has <strong>' . $stack['location']['believers'] . '</strong> people who might know Jesus, <strong>' . $stack['location']['christian_adherents'] . '</strong> people who might know about Jesus culturally, and <strong>' . $stack['location']['non_christians'] . '</strong> people who do not know Jesus.<br><br>This is <strong>1</strong> believer for every <strong>' . $stack['location']['lost_per_believer'] . '</strong> neighbors who need Jesus.',
                ],
            ],
            /**
             * PRAYERS TARGETING CULTURAL CHRISTIANS
             */
            'christian_adherents' => [
                [
                    'section_summary' => 'The ' . $stack['location']['admin_level_name'] . ' of <strong>' . $stack['location']['full_name'] . '</strong> has a population of <strong>' . $stack['location']['population'] . '</strong>.<br><br> We estimate ' . $stack['location']['name'] . ' has <strong>' . $stack['location']['believers'] . '</strong> people who might know Jesus, <strong>' . $stack['location']['christian_adherents'] . '</strong> people who might know about Jesus culturally, and <strong>' . $stack['location']['non_christians'] . '</strong> people who do not know Jesus.<br><br>This is <strong>1</strong> believer for every <strong>' . $stack['location']['lost_per_believer'] . '</strong> neighbors who need Jesus.',
                ],
            ],
            /**
             * PRAYERS TARGETING NON CHRISTIANS
             */
            'non_christians' => [
                [
                    'section_summary' => 'The ' . $stack['location']['admin_level_name'] . ' of <strong>' . $stack['location']['full_name'] . '</strong> has a population of <strong>' . $stack['location']['population'] . '</strong>.<br><br> We estimate ' . $stack['location']['name'] . ' has <strong>' . $stack['location']['believers'] . '</strong> people who might know Jesus, <strong>' . $stack['location']['christian_adherents'] . '</strong> people who might know about Jesus culturally, and <strong>' . $stack['location']['non_christians'] . '</strong> people who do not know Jesus.<br><br>This is <strong>1</strong> believer for every <strong>' . $stack['location']['lost_per_believer'] . '</strong> neighbors who need Jesus.',
                ],
            ]
        ];

    }

    public static function demogrphics_4_fact_text( $stack): array
    {

        return [
            /**
             * PRAYERS TARGETING BELIEVERS
             */
            'believers' => [
                [
                    'prayer' => '',
                ],
            ],
            /**
             * PRAYERS TARGETING CULTURAL CHRISTIANS
             */
            'christian_adherents' => [
                [
                    'prayer' => '',
                ],
            ],
            /**
             * PRAYERS TARGETING NON CHRISTIANS
             */
            'non_christians' => [
                [
                    'prayer' => '',
                ],
            ]
        ];

    }

}

class PG_Stacker_Text_V2 {
    /*********************************************************************
     *
     * V2 TEXT STACK ELEMENTS
     *
     *********************************************************************/

    public static function _population_prayers( &$lists, $stack, $all = false ) {
        $section_label = 'Population';
        $templates = [
            [
                'section_label' => $section_label,
                'prayer' => 'There are '.$stack['location']['population'].' people living in '.$stack['location']['name'].'. About '.$stack['location']['believers'].' might know Jesus personally.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => $section_label,
                'prayer' => $stack['location']['name']. ' might have '.$stack['location']['believers'].' who know Jesus, '.$stack['location']['christian_adherents'].' who know about him culturally, and '.$stack['location']['non_christians'].' who are far from the saving knowledge of Jesus.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Pour your Spirit out on the '.$stack['location']['population'].' citizens of '.$stack['location']['name'].', so that they might know your name and the name of your Son.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Lord, we suspect there is 1 believer for every ' . $stack['location']['lost_per_believer'] . ' neighbors who need your son, Jesus. Please, give courage and opportunity to your children to speak boldly.',
                'reference' => 'Ephesians 6:19',
                'verse' => 'Pray also for me, that whenever I speak, words may be given me so that I will fearlessly make known the mystery of the gospel.',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Lord, help the people of ' . $stack['location']['full_name'] . ' to discover the essence of being a disciple, making disciples, and how to plant churches that multiply.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Father, you know every soul, and you know who are yours and who are yet to be yours out of the '.$stack['location']['population'] .' people living in '. $stack['location']['full_name'] . '. Please, call your lost to yourself.',
                'reference' => 'Ezekiel 36:24',
                'verse' => 'For I will take you out of the nations; I will gather you from all the countries and bring you back into your own land.',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Father, bring yourself glory in '.$stack['location']['name'].'. Through your servants plant '.$stack['location']['new_churches_needed'].' new churches that love you, love one another, and make disciples this year.',
                'reference' => 'Habakkuk 2:14',
                'verse' => 'For the earth will be filled with the knowledge of the glory of the LORD as the waters cover the sea.',
            ],
        ];

        if ( $all ) {
            return array_merge( $templates, $lists );
        }

        $lists = array_merge( [ $templates[array_rand( $templates ) ] ], $lists );
        return $lists;
    }


    public static function _language_prayers( &$lists, $stack, $all = false ) {
        if ( 'English' === $stack['location']['primary_language'] ) {
            return $lists;
        }

        $section_label = 'Language';
        $templates = [
            [
                'section_label' => $section_label,
                'prayer' => 'Father, please provide access to your Word in the ' . $stack['location']['primary_language'] . ' language.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Father, please send out gospel messengers who can create video and radio media for the ' . $stack['location']['primary_language'] . ' language.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Father, please provide Bibles in '.$stack['location']['name'].' in the ' . $stack['location']['primary_language'] . ' language. Give success to those who print them and distribute them.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Father, please provide fresh translations in the ' . $stack['location']['primary_language'] . ' language, so the people of '.$stack['location']['full_name'].' would hear your Word in their heart language.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Father, raise up spiritual truth about Jesus through YouTube in the ' . $stack['location']['primary_language'] . ' language.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Lord, raise up workers in the ' . $stack['location']['primary_language'] . ' language, who can communicate accurately the word of truth.',
                'reference' => '2 Timothy 2:15',
                'verse' => '...a worker who does not need to be ashamed and who correctly handles the word of truth.',
            ],
        ];

        if ( $all ) {
            return array_merge( $templates, $lists );
        }

        $lists = array_merge( [ $templates[array_rand( $templates ) ] ], $lists );
        return $lists;
    }

    public static function _religion_prayers( &$lists, $stack, $all = false ) {
        if ( 'Christianity' === $stack['location']['primary_religion'] ) {
            return $lists;
        }


        $section_label = 'Primary Religion';
        $templates = [
            [
                'section_label' => $section_label,
                'prayer' => 'The primary religion in '.$stack['location']['full_name'].' is '.$stack['location']['primary_religion'].'.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Father, give the '.$stack['location']['believers'].' believers in '.$stack['location']['name'].' the skill to communicate your gospel to those who follow '.$stack['location']['primary_religion'].' around them.',
                'reference' => 'Ephesians 6:19',
                'verse' => 'Pray also for me, that whenever I speak, words may be given me so that I will fearlessly make known the mystery of the gospel.',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Father, many people in '.$stack['location']['full_name'].' follow '.$stack['location']['primary_religion'].'. Please give them accurate knowledge of Jesus and take their current faith and devotion, and turn it towards You.',
                'reference' => 'Romans 10:2',
                'verse' => 'For I can testify about them that they are zealous for God, but their zeal is not based on knowledge.',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Lord, increase spiritual dissatisfaction among those in '.$stack['location']['name'].' who follow '.$stack['location']['primary_religion'] . ', so that they would begin to seek You.',
                'reference' => 'Romans 10:2',
                'verse' => 'For I can testify about them that they are zealous for God, but their zeal is not based on knowledge.',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Even though the primary religion is '.$stack['location']['primary_religion'] . ' in '.$stack['location']['name'].', Lord, call to yourself persons of peace among the faith community, those who fear you with the best knowledge they have.',
                'reference' => 'Acts 10:1,2',
                'verse' => 'At Caesarea there was a man named Cornelius, a centurion in what was known as the Italian Regiment. He and all his family were devout and God-fearing; he gave generously to those in need and prayed to God regularly.',
            ],
        ];

        if ( $all ) {
            return array_merge( $templates, $lists );
        }

        $lists = array_merge( [ $templates[array_rand( $templates ) ] ], $lists );
        return $lists;
    }

    // https://www.missionfrontiers.org/issue/article/the-ten-universal-elements

    /**
     * 1. Prayer
     * 2. Abundant Gospel Sowing
     * 3. Intentional Church Planting
     * 4. Scriptural Authority
     * 5. Local Leadership
     * 6. Lay Leadership
     * 7. Cell or House Churches
     * 8. Churches Planting Churches
     * 9. Rapid Reproduction
     * 10. Healthy Churches
     */

    public static function _for_prayer_movement( &$lists, $stack, $all = false ) {
        $templates = [
            [
                'section_label' => 'Prayer Movement',
                'prayer' => 'Father, we cry out for a prayer movement ' . $stack['location']['full_name'] . '. Please, stir the  ' . $stack['location']['believers'] . ' believers here to pray for awakening.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => 'Prayer Movement',
                'prayer' => 'Lord, cause a passion for prayer among the people of ' . $stack['location']['full_name'] . '.',
                'reference' => 'Matthew 17:20',
                'verse' => 'I (Jesus) pray also for those who will believe in me through their message, that all of them may be one, Father, just aas you are in me and I am in you. May they also be in us so that the world my believe that you have sent me.',
            ],
            [
                'section_label' => 'Prayer Movement',
                'prayer' => 'Lord, stir the hearts of Your people in ' . $stack['location']['name'] . ' to agree with You and with one another in love.',
                'reference' => 'Matthew 17:20',
                'verse' => 'I (Jesus) pray also for those who will believe in me through their message, that all of them may be one, Father, just aas you are in me and I am in you. May they also be in us so that the world my believe that you have sent me.',
            ],
            [
                'section_label' => 'Prayer Movement',
                'prayer' => 'Spirit, teach the church in '.$stack['location']['name'].' to increase their prayer for your kingdom to come.',
                'reference' => 'Daniel 6:10',
                'verse' => 'Now when Daniel learned that the decree had been published, he went home to his upstairs room where the windows opened toward Jerusalem. Three times a day he got down on his knees and prayed, giving thanks to his God...',
            ],
            [
                'section_label' => 'Prayer Movement',
                'prayer' => 'Spirit, teach the believing children in '.$stack['location']['name'].' to pray with passion and pleading for your presence.',
                'reference' => '',
                'verse' => '',
            ],
        ];

        if ( $all ) {
            return array_merge( $templates, $lists );
        }
        $lists = array_merge( [ $templates[array_rand( $templates ) ] ], $lists );
        return $lists;
    }

    public static function _for_abundant_gospel_sowing( &$lists, $stack, $all = false ) {
        $templates = [
            [
                'section_label' => 'Obedience',
                'prayer' => 'Father, move the ' . $stack['location']['believers'] . ' believers in ' . $stack['location']['name'] . ' to say "Not our will, but yours be done".',
                'reference' => 'Luke 22:41-42',
                'verse' => 'He withdrew about a stone’s throw beyond them, knelt down and prayed, "Father, if you are willing, take this cup from me; yet not my will, but yours be done."',
            ],
            [
                'section_label' => 'Abundant Gospel Sowing',
                'prayer' => 'Lord, make the ' . $stack['location']['believers'] . ' believers in ' . $stack['location']['name'] . ' to be brave and clear with the gospel to their ' . $stack['location']['all_lost'] . ' neighbors.',
                'reference' => 'Acts 14:3',
                'verse' => 'So Paul and Barnabas spent considerable time there, speaking boldly for the Lord, who confirmed the message of his grace by enabling them to perform signs and wonders.',
            ],
            [
                'section_label' => 'Abundant Gospel Sowing',
                'prayer' => 'Lord, make the ' . $stack['location']['believers'] . ' believers in ' . $stack['location']['name'] . ' to be brave and clear with the gospel to their ' . $stack['location']['all_lost'] . ' neighbors.',
                'reference' => 'Acts 14:3',
                'verse' => 'So Paul and Barnabas spent considerable time there, speaking boldly for the Lord, who confirmed the message of his grace by enabling them to perform signs and wonders.',
            ],
            [
                'section_label' => 'Abundant Gospel Sowing',
                'prayer' => 'Father, please raise up apostles, evangelists and preachers in '.$stack['location']['name'].' who can speak your gospel boldly and clearly in ' . $stack['location']['primary_language'] . '.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => 'Abundant Gospel Sowing',
                'prayer' => 'Father, please send into the harvest new evangelists who can speak the ' . $stack['location']['primary_language'] . ' language in '.$stack['location']['name'].'.',
                'reference' => 'Matthew 9:38',
                'verse' => 'Ask the Lord of the harvest, therefore, to send out workers into his harvest field.',
            ],
            [
                'section_label' => 'Abundant Gospel Sowing',
                'prayer' => 'Father, please send into the harvest new apostles who can speak the ' . $stack['location']['primary_language'] . ' language and open new communities for the gospel.',
                'reference' => 'Matthew 9:38',
                'verse' => 'Ask the Lord of the harvest, therefore, to send out workers into his harvest field.',
            ],
            [
                'section_label' => 'Abundant Gospel Sowing',
                'prayer' => 'Father, please send into the harvest new teachers who can speak the ' . $stack['location']['primary_language'] . ' language and can combat the lies of our enemy in '.$stack['location']['full_name'].'.',
                'reference' => 'Matthew 9:38',
                'verse' => 'Ask the Lord of the harvest, therefore, to send out workers into his harvest field.',
            ],

        ];

        if ( $all ) {
            return array_merge( $templates, $lists );
        }
        $lists = array_merge( [ $templates[array_rand( $templates ) ] ], $lists );
        return $lists;
    }

    public static function _for_new_churches( &$lists, $stack, $all = false ) {
        $templates = [
            [
                'section_label' => 'Church Planting',
                'prayer' => 'Father, help '.$stack['location']['new_churches_needed'].' new house churches start in '.$stack['location']['full_name'].'.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => 'Church Planting',
                'prayer' => 'Spirit, please start new house churches in every neighborhood of '.$stack['location']['full_name'].'.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => 'Church Planting',
                'prayer' => 'Spirit, please give every house church in '.$stack['location']['name'].' a passion to plant another house church.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => 'Church Planting',
                'prayer' => 'Father, show your mercy on the '.$stack['location']['all_lost'].' people in '.$stack['location']['name'].' who are far from you. Please add '.$stack['location']['new_churches_needed'].' new house churches this year.',
                'reference' => '',
                'verse' => '',
            ],
        ];

        if ( $all ) {
            return array_merge( $templates, $lists );
        }
        $lists = array_merge( [ $templates[array_rand( $templates ) ] ], $lists );
        return $lists;
    }

    public static function _for_obedience( &$lists, $stack, $all = false ) {
        $templates = [
            [
                'section_label' => 'Obedience',
                'prayer' => 'Father, move the ' . $stack['location']['believers'] . ' believers in ' . $stack['location']['name'] . ' to say "Not our will, but yours be done" today.',
                'reference' => 'Luke 22:41-42',
                'verse' => 'He withdrew about a stone’s throw beyond them, knelt down and prayed, "Father, if you are willing, take this cup from me; yet not my will, but yours be done."',
            ],
            [
                'section_label' => 'Obedience',
                'prayer' => 'Lord, stir the hearts of Your people in ' . $stack['location']['name'] . ' to agree with You and with one another in love.',
                'reference' => 'John 17:21',
                'verse' => 'that all of them may be one, Father, just as you are in me and I am in you. May they also be in us so that the world may believe that you have sent me.',
            ],
            [
                'section_label' => 'Obedience',
                'prayer' => 'Spirit, cause the ' . $stack['location']['believers'] . ' believers in ' . $stack['location']['name'] . ' to obey with immediate, radical, costly obedience like Abraham.',
                'reference' => 'Genesis 22:2-3',
                'verse' => 'Then God said, “Take your son, your only son, whom you love — Isaac — and go to the region of Moriah. Sacrifice him there as a burnt offering on a mountain I will show you.” Early the next morning Abraham got up and loaded his donkey. He took with him two of his servants and his son Isaac. When he had cut enough wood for the burnt offering, he set out for the place God had told him about.',
            ],
        ];

        if ( $all ) {
            return array_merge( $templates, $lists );
        }
        $lists = array_merge( [ $templates[array_rand( $templates ) ] ], $lists );
        return $lists;
    }

    public static function _for_biblical_authority( &$lists, $stack, $all = false ) {
        $templates = [
            [
                'section_label' => 'Biblical Authority',
                'prayer' => 'Spirit, make the Word of God a delight to the people of '.$stack['location']['name'].', like it was to David.',
                'reference' => 'Psalm 119:16',
                'verse' => 'I delight in your decrees, I will not neglect your word.',
            ],
            [
                'section_label' => 'Biblical Authority',
                'prayer' => 'Spirit, give the people of '.$stack['location']['name'].' a desire to hide your word in their heart.',
                'reference' => 'Psalm 119:11',
                'verse' => 'I have hidden your word in my heart that I might not sin against you.',
            ],
            [
                'section_label' => 'Biblical Authority',
                'prayer' => 'Spirit, help the people of '.$stack['location']['name'].' to be consumed with longing for your Word at all times, like David.',
                'reference' => 'Psalm 119:20',
                'verse' => 'My soul is consumed with longing for your laws at all times.',
            ],
            [
                'section_label' => 'Biblical Authority',
                'prayer' => 'Lord, teach the people of '.$stack['location']['name'].' your Word, so that they can follow your ways all their life.',
                'reference' => 'Psalm 119:33',
                'verse' => 'Teach me, Lord, the way of your decrees, that I may follow it to the end.',
            ],
        ];

        if ( $all ) {
            return array_merge( $templates, $lists );
        }
        $lists = array_merge( [ $templates[array_rand( $templates ) ] ], $lists );
        return $lists;
    }

    public static function _for_leadership( &$lists, $stack, $all = false ) { // local leadership and lay leadership
        $templates = [
            [
                'section_label' => 'Local Leadership',
                'prayer' => 'Father, please raise up apostles, evangelists and preachers in '.$stack['location']['name'].' who can speak your gospel boldly and clearly in ' . $stack['location']['primary_language'] . '.',
                'reference' => '',
                'verse' => '',
            ],
        ];

        if ( $all ) {
            return array_merge( $templates, $lists );
        }
        $lists = array_merge( [ $templates[array_rand( $templates ) ] ], $lists );
        return $lists;
    }

    public static function _for_house_churches( &$lists, $stack, $all = false ) {
        $templates = [
            [
                'section_label' => 'Simple Churches',
                'prayer' => 'God, we ask you to raise up elders and deacons from the '.$stack['location']['believers'].' believers in '.$stack['location']['name'].', who will serve the church and equip it to do your work.',
                'reference' => 'Ephesians 4:11',
                'verse' => 'So Christ himself gave the apostles, the prophets, the evangelists, the pastors and teachers, to equip his people for works of service, so that the body of Christ may be built up until we all reach unity in the faith and in the knowledge of the Son of God and become mature, attaining to the whole measure of the fullness of Christ.',
            ],
            [
                'section_label' => 'Simple Churches',
                'prayer' => 'God, guide the  '.$stack['location']['believers'].' believers in '.$stack['location']['name'].' to see their homes as spiritual families that love You, love each other, and make disciples.',
                'reference' => '',
                'verse' => '',
            ],
        ];

        if ( $all ) {
            return array_merge( $templates, $lists );
        }
        $lists = array_merge( [ $templates[array_rand( $templates ) ] ], $lists );
        return $lists;
    }

    public static function _for_multiplication( &$lists, $stack, $all = false ) { // churches planting churches & disciple making disciples
        $section_label = [
            'm' => 'Movement',
            'd' => 'Disciples Making Disciples',
            'c' => 'Churches Planting Churches'
        ];

        $templates = [
            [
                'section_label' => $section_label['m'],
                'prayer' => 'Jesus, all authority was given to you, and you commanded all disciples in '. $stack['location']['full_name'] . ' to make more disciples, and you promised to be with them. May your power and their obedience make more disciples today.',
                'reference' => 'Matthew 28:18',
                'verse' => 'All authority in heaven and on earth has been given to me. Therefore go and make disciples of all nations, baptizing them in the name of the Father and of the Son and of the Holy Spirit, and teaching them to obey everything I have commanded you. And surely I am with you always, to the very end of the age.',
            ],
            [
                'section_label' =>  $section_label['m'],
                'prayer' => 'Father, help the '.$stack['location']['believers'].' believers in '.$stack['location']['name'].' to know that You can make a big impact through their simple obedience today.',
                'reference' => 'Exodus 19:6',
                'verse' => '... you will be for me a kingdom of priests ...',
            ],
            [
                'section_label' =>  $section_label['m'],
                'prayer' => 'Father, please raise up pastors and teachers in '.$stack['location']['name'].' who can speak your gospel boldly and clearly in the ' . $stack['location']['primary_language'] . ' language.',
                'reference' => 'Romans 10:14',
                'verse' => 'How, then, can they call on the one they have not believed in? And how can they believe in the one of whom they have not heard? And how can they hear without someone preaching to them? And how can anyone preach unless they are sent?',
            ],
            [
                'section_label' =>  $section_label['m'],
                'prayer' => 'Please, teach the '.$stack['location']['believers'].' believers in '.$stack['location']['full_name'].' how to pray to you and how to listen for your voice. That they might follow you into the good works you have prepared for them.',
                'reference' => 'John 10:27',
                'verse' => 'My sheep listen to my voice; I know them, and they follow me.',
            ],
            [
                'section_label' =>  $section_label['m'],
                'prayer' => 'Please, convict the '.$stack['location']['believers'].' believers in '.$stack['location']['full_name'].' to look to You as their only hope for strength and fruitfulness and life.',
                'reference' => 'John 15:5',
                'verse' => 'I am the vine; you are the branches. If you remain in me and I in you, you will bear much fruit; apart from me you can do nothing.',
            ],
            [
                'section_label' =>  $section_label['m'],
                'prayer' => 'Father, convict the '.$stack['location']['believers'].' believers in '.$stack['location']['name'].' to be holy and righteous. Inspire them to gather in small groups for accountability and spiritual growth.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' =>  $section_label['m'],
                'prayer' => 'Father, encourage the '.$stack['location']['believers'].' believers in '.$stack['location']['name'].' to not just be consumers of knowledge but be producers of love, mercy, kindness, and justice.',
                'reference' => '1 John 3:18',
                'verse' => '...let us not love with words or speech but with actions and in truth.',
            ],
            [
                'section_label' =>  $section_label['m'],
                'prayer' => 'Father, help the '.$stack['location']['believers'].' believers in '.$stack['location']['name'].' to be spiritually intentional with their relationships among their '.$stack['location']['all_lost'].' lost friends and neighbors.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' =>  $section_label['m'],
                'prayer' => 'Lord, we know that you invest more in those who have been faithful with what they have been given. Please, richly bless each faithful believer in '.$stack['location']['name'].' with more spiritual insight, wisdom, courage and vision.',
                'reference' => 'Matthew 25:28',
                'verse' => 'So take the bag of gold from him and give it to the one who has ten bags. For whoever has will be given more, and they will have an abundance. Whoever does not have, even what they have will be taken from them.',
            ],
            [
                'section_label' =>  $section_label['m'],
                'prayer' => 'Father, multiply brothers, sisters, and mothers to our spiritual family in '.$stack['location']['full_name'].'.',
                'reference' => 'Matthew 12:50',
                'verse' => 'He replied to him, “Who is my mother, and who are my brothers?” Pointing to his disciples, he said, “Here are my mother and my brothers. For whoever does the will of my Father in heaven is my brother and sister and mother.”',
            ],
        ];


        if ( $all ) {
            return array_merge( $templates, $lists );
        }
        $lists = array_merge( [ $templates[array_rand( $templates ) ] ], $lists );
        return $lists;
    }

    public static function _for_urgency( &$lists, $stack, $all = false ) { // for rapid reproduction
        $templates = [
            [
                'section_label' => 'Urgency',
                'prayer' => 'Spirit, encourage the church in '.$stack['location']['full_name'].' to live with urgency and a passion for making disciples.',
                'reference' => 'James 4:14',
                'verse' => 'Yet you do not know what your life will be like tomorrow. You are just a vapor that appears for a little while and then vanishes away.',
            ],
            [
                'section_label' => 'Urgency',
                'prayer' => 'Spirit, encourage the church in '.$stack['location']['full_name'].' to make the most of every opportunity.',
                'reference' => 'Ephesians 5:15',
                'verse' => 'Be very careful, then, how you live—not as unwise but as wise, making the most of every opportunity, because the days are evil.',
            ],
        ];

        if ( $all ) {
            return array_merge( $templates, $lists );
        }
        $lists = array_merge( [ $templates[array_rand( $templates ) ] ], $lists );
        return $lists;
    }


    public static function _for_church_health( &$lists, $stack, $all = false ) {
        $templates = [
            [
                'section_label' => 'The Church',
                'prayer' => 'Lord, stir the hearts of Your people in ' . $stack['location']['name'] . ' to agree with You and with one another.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => 'The Church',
                'prayer' => 'Father, please raise up apostles, evangelists and preachers in '.$stack['location']['name'].' who can speak your gospel boldly and clearly in ' . $stack['location']['primary_language'] . '.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => 'The Church',
                'prayer' => 'Father, please provide access to your Word in ' . $stack['location']['primary_language'] . '. Provide translators, printers, books sellers, and app developers the resources and skill to get your Word to '.$stack['location']['full_name'].'.',
                'reference' => 'Matthew 24:14',
                'verse' => 'And this gospel of the kingdom will be preached in the whole world as a testimony to all nations, and then the end will come.',
            ],
            [
                'section_label' => 'The Church',
                'prayer' => 'Lord we pray you unite the '.$stack['location']['believers'].' believers to pray at all times in the Spirit, with all prayer and supplication, for spiritual breakthrough in ' . $stack['location']['name'] . '.',
                'reference' => 'Philippians 4:6',
                'verse' => '... in every situation, by prayer and petition, with thanksgiving, present your requests to God.',
            ],
            [
                'section_label' => 'The Church',
                'prayer' => 'Father, we pray that the people of ' . $stack['location']['full_name'] . ' will learn to study the Bible, understand it, obey it, and share it.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => 'The Church',
                'prayer' => 'God, we pray both the men and women of ' . $stack['location']['full_name'] . ' will find ways to meet in groups of two or three to encourage and correct one another from your Word.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => 'The Church',
                'prayer' => 'Lord, we pray for the believers in ' . $stack['location']['full_name'] . ' to be more like Jesus in their love for friends and enemies.',
                'reference' => 'Matthew 5:44',
                'verse' => 'But I tell you, love your enemies and pray for those who persecute you.',
            ],
            [
                'section_label' => 'The Church',
                'prayer' => 'God, we pray for the believers in ' . $stack['location']['full_name'] . ' that they will know how easy it is to spend an hour in prayer with you, and will do it.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => 'The Church',
                'prayer' => 'Father, we pray for the believers in ' . $stack['location']['full_name'] . ' to be good spiritual stewards of their everyday relationships.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => 'The Church',
                'prayer' => 'God, we pray for the believers in ' . $stack['location']['full_name'] . ' to be generous so that they would be worthy of greater investment by you.',
                'reference' => '',
                'verse' => '',
            ],
        ];

        if ( $all ) {
            return array_merge( $templates, $lists );
        }
        $lists = array_merge( [ $templates[array_rand( $templates ) ] ], $lists );
        return $lists;
    }

    public static function _cities( &$lists, $stack, $all = false ) {
        if ( empty( $stack['location']['cities_list_w_pop'] ) ) {
            return $lists;
        }
        $templates = [
            [
                'section_label' => 'Cities in '.$stack['location']['name'],
                'prayer' => $stack['location']['cities_list_w_pop'],
                'reference' => '',
                'verse' => '',
            ],
        ];

        if ( $all ) {
            return array_merge( $templates, $lists );
        }
        $lists = array_merge( [ $templates[array_rand( $templates ) ] ], $lists );
        return $lists;
    }

    public static function _non_christian_deaths( &$lists, $stack, $all = false ) {
        $section_label = 'Non-Christians';
        $templates = [
            [
                'section_label' => $section_label,
                'prayer' => 'Over '.$stack['location']['percent_non_christians'].' percent of the people of '.$stack['location']['name'].' are far from Jesus. Lord, please send your gospel to them through the internet or radio or television today!',
                'reference' => '',
                'verse' => '',
            ],
        ];

        if ( $all ) {
            return array_merge( $templates, $lists );
        }
        $lists = array_merge( [ $templates[array_rand( $templates ) ] ], $lists );
        return $lists;
    }

    public static function _christian_adherents_deaths( &$lists, $stack, $all = false ) {
        $section_label = 'Cultural Christians';
        $templates = [
            [
                'section_label' => $section_label,
                'prayer' => "Spirit, consider the ".$stack['location']['christian_adherents']." cultural Christians in ".$stack['location']['name'].". You promised to convict of sin, righteousness and judgement. Please show mercy and don't leave them idle and distant from Jesus.",
                'reference' => '',
                'verse' => '',
            ],
        ];

        if ( $all ) {
            return array_merge( $templates, $lists );
        }
        $lists = array_merge( [ $templates[array_rand( $templates ) ] ], $lists );
        return $lists;
    }

    public static function _believers_births( &$lists, $stack, $all = false ) {
        $section_label = 'Believer Families';
        $templates = [
            [
                'section_label' => $section_label,
                'prayer' => "Spirit, consider the ".$stack['location']['believers']." believers in ".$stack['location']['name'].". You promised to convict of sin, righteousness and judgement. Please show mercy and don't leave them idle and distant from Jesus.",
                'verse' => '',
                'reference' => '',
            ],
        ];

        if ( $all ) {
            return array_merge( $templates, $lists );
        }
        $lists = array_merge( [ $templates[array_rand( $templates ) ] ], $lists );
        return $lists;
    }

}


/**
 * (
[location] => Array
        (
        [grid_id] => 100219785
        [name] => Saiha
        [admin0_name] => India
        [full_name] => Saiha, Mizoram, India
        [population] => 22,700
        [latitude] => 22.3794
        [longitude] => 93.0146
        [country_code] => IN
        [admin0_code] => IND
        [parent_id] => 100219370
        [parent_name] => Mizoram
        [admin0_grid_id] => 100219347
        [admin1_grid_id] => 100219370
        [admin1_name] => Mizoram
        [admin2_grid_id] => 100219785
        [admin2_name] => Saiha
        [admin3_grid_id] =>
        [admin3_name] =>
        [admin4_grid_id] =>
        [admin4_name] =>
        [admin5_grid_id] =>
        [admin5_name] =>
        [level] => 2
        [level_name] => admin2
        [north_latitude] => 22.8106
        [south_latitude] => 21.9462
        [east_longitude] => 93.2093
        [west_longitude] => 92.827
        [p_longitude] => 92.8362
        [p_latitude] => 23.3068
        [p_north_latitude] => 24.5208
        [p_south_latitude] => 21.9462
        [p_east_longitude] => 93.4447
        [p_west_longitude] => 92.2594
        [c_longitude] => 82.8007
        [c_latitude] => 21.1278
        [c_north_latitude] => 35.5013
        [c_south_latitude] => 6.75426
        [c_east_longitude] => 97.4152
        [c_west_longitude] => 68.1862
        [peer_locations] => 8
        [birth_rate] => 18.7
        [death_rate] => 7.2
        [growth_rate] => 1.115
        [believers] => 250
        [christian_adherents] => 275
        [non_christians] => 22,175
        [primary_language] => Hindi
        [primary_religion] => Hinduism
        [percent_believers] => 1.1
        [percent_christian_adherents] => 1.21
        [percent_non_christians] => 97.69
        [admin_level_name] => county
        [admin_level_name_plural] => counties
        [population_int] => 22700
        [believers_int] => 250
        [christian_adherents_int] => 275
        [non_christians_int] => 22175
        [percent_believers_full] => 1.1
        [percent_christian_adherents_full] => 1.21333
        [percent_non_christians_full] => 97.6867
        [all_lost_int] => 22450
        [all_lost] => 22,450
        [lost_per_believer_int] => 90
        [lost_per_believer] => 90
        [population_growth_status] => Significant Growth
        [deaths_non_christians_next_hour] => 0
        [deaths_non_christians_next_100] => 1
        [deaths_non_christians_next_week] => 3
        [deaths_non_christians_next_month] => 13
        [deaths_non_christians_next_year] => 161
        [births_non_christians_last_hour] => 0
        [births_non_christians_last_100] => 4
        [births_non_christians_last_week] => 8
        [births_non_christians_last_month] => 34
        [births_non_christians_last_year] => 419
        [deaths_christian_adherents_next_hour] => 0
        [deaths_christian_adherents_next_100] => 0
        [deaths_christian_adherents_next_week] => 0
        [deaths_christian_adherents_next_month] => 0
        [deaths_christian_adherents_next_year] => 1
        [births_christian_adherents_last_hour] => 0
        [births_christian_adherents_last_100] => 0
        [births_christian_adherents_last_week] => 0
        [births_christian_adherents_last_month] => 0
        [births_christian_adherents_last_year] => 5
        [favor] => non_christians
        [icon_color] => orange
)

[cities] => Array
(
    [0] => Array
        (
            [id] => 28641
            [geonameid] => 1257771
            [name] => Saiha
            [full_name] => Saiha, Mizoram, India
            [admin0_name] => India
            [latitude] => 22.4918
            [longitude] => 92.9814
            [timezone] => Asia/Kolkata
            [population_int] => 22654
            [population] => 22,654
        )

    )

[people_groups] => Array
(
    [1] => Array
    (
        [id] => 6301
        [name] => Halam Rupini
        [longitude] => 92.7058
        [latitude] => 23.724
        [lg_name] => Aizawl
        [lg_full_name] => Aizawl, Aizawl, Mizoram, India
        [admin0_name] => India
        [admin0_grid_id] => 100219347
        [admin1_grid_id] => 100219370
        [admin2_grid_id] => 100219779
        [admin3_grid_id] => 100221497
        [admin4_grid_id] =>
        [admin5_grid_id] =>
        [population] => 4,500
        [JPScale] => 2
        [LeastReached] => N
        [PrimaryLanguageName] => Kok Borok
        [PrimaryReligion] => Hinduism
        [PercentAdherents] => 44.854
        [PercentEvangelical] => 0
        [PeopleCluster] => South Asia Tribal - other
        [AffinityBloc] => South Asian Peoples
        [PeopleID3] => 19763
        [ROP3] => 115791
        [ROG3] => IN
        [pg_unique_key] => IN_19763_115791
        [query_level] => parent
    )

)

[least_reached] => Array
    (
        [id] => 5939
        [name] => Chakma
        [longitude] => 92.7688
        [latitude] => 23.7962
        [lg_name] => Aizawl
        [lg_full_name] => Aizawl, Aizawl, Mizoram, India
        [admin0_name] => India
        [admin0_grid_id] => 100219347
        [admin1_grid_id] => 100219370
        [admin2_grid_id] => 100219779
        [admin3_grid_id] => 100221497
        [admin4_grid_id] =>
        [admin5_grid_id] =>
        [population] => 217,000
        [JPScale] => 1
        [LeastReached] => Y
        [PrimaryLanguageName] => Chakma
        [PrimaryReligion] => Buddhism
        [PercentAdherents] => 4.914
        [PercentEvangelical] => 0
        [PeopleCluster] => South Asia Tribal - other
        [AffinityBloc] => South Asian Peoples
        [PeopleID3] => 11293
        [ROP3] => 101976
        [ROG3] => IN
        [pg_unique_key] => IN_11293_101976
        [query_level] => parent
    )

)
 */
