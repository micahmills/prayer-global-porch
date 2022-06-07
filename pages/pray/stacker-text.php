<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.

class PG_Stacker_Text {
    public static function photos_text( $stack ) : array {
        /**
         * Photos Block
         */
        return [
            [
                'section_summary' => 'What people, places, activities or culture do you see? <br>Do you see conditions of education, economy, religion, or environment?',
                'prayer' => 'Spirit, what do you want prayed for '.$stack['location']['full_name'].'?',
            ],
            [
                'section_summary' => 'What people, places, activities or culture do you see? <br>Do you see conditions of education, economy, religion, or environment? <br>How could you pray for that?',
                'prayer' => 'Spirit, what do you want prayed for '.$stack['location']['full_name'].'?',
            ],
            [
                'section_summary' => 'What people, places, activities or culture do you see? <br>Do you see conditions of education, economy, religion, or environment?',
                'prayer' => '',
            ],
            [
                'section_summary' => 'What people, places, activities or culture do you see? <br>Do you see conditions of education, economy, religion, or environment?',
                'prayer' => 'Spirit, what do you want prayed for the people of '.$stack['location']['full_name'].'?',
            ],
            [
                'section_summary' => 'What people, places, activities or culture do you see? <br>Do you see conditions of education, economy, religion, or environment?',
                'prayer' => '',
            ],
            [
                'section_summary' => 'What people, places, activities or culture do you see? <br>Do you see conditions of education, economy, religion, or environment? <br>How could you pray for that?',
                'prayer' => 'Spirit, what do you want prayed for '.$stack['location']['full_name'].'?',
            ],
            [
                'section_summary' => 'What people, places, activities or culture do you see? <br>Do you see conditions of education, economy, religion, or environment? <br>How could you pray for that?',
                'prayer' => '',
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

    public static function verse_text( $stack ) : array {

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
                    'prayer' => 'Open the hearts and lips of Your '.$stack['location']['believers'].' people in '.$stack['location']['name'].' to humbly and boldly and broadly share Your Good News for the glory of Your name.',
                ],
                [
                    'verse' => '"All Scripture is breathed out by God and profitable for teaching, for reproof, for correction, and for training in righteousness, that the man of God may be complete, equipped for every good work"',
                    'reference' => '2 Timothy 3:16-17',
                    'prayer' => 'May Your Word, O God, be the foundational source of truth and discernment for all matters of faith and practice and shepherding among the people of '.$stack['location']['full_name'].'.',
                ],
                [
                    'verse' => '"All Scripture is breathed out by God and profitable for teaching, for reproof, for correction, and for training in righteousness, that the man of God may be complete, equipped for every good work"',
                    'reference' => '2 Timothy 3:16-17',
                    'prayer' => 'We pray against competing spiritual authorities '.$stack['location']['name'].' and ask that as biblical understanding increases that love, dependence upon, and obedience to You would correspondingly increase.',
                ],
                [
                    'verse' => '"And when they had appointed elders for them in every church, with prayer and fasting they committed them to the Lord in whom they had believed."',
                    'reference' => 'Acts 14:23',
                    'prayer' => 'Father, just as the earliest church-planting efforts included the appointment of local leaders over those young congregations, we pray for qualified locals to humbly serve and lead Your Church in '.$stack['location']['full_name'].'.',
                ],
                [
                    'verse' => '"Now when they saw the boldness of Peter and John, and perceived that they were uneducated, common men, they were astonished. And they recognized that they had been with Jesus"',
                    'reference' => 'Acts 4:13',
                    'prayer' => 'Lord, while we acknowledge the value of education and training, we affirm the superior value of abiding in and being with You. We pray for the '.$stack['location']['believers'].' believers in '.$stack['location']['name'].' that they abide in You.',
                ],
                [
                    'verse' => '"Now when they saw the boldness of Peter and John, and perceived that they were uneducated, common men, they were astonished. And they recognized that they had been with Jesus"',
                    'reference' => 'Acts 4:13',
                    'prayer' => 'Lord, we ask You to raise up men and women of godly character as lay leaders to serve and shepherd Your people. Let not formal training, diplomas, or titles be the ultimate criteria for influence or a bottleneck to spiritual maturity or church growth.',
                ],
                [
                    'verse' => '"And the Lord added to their number day by day those who were being saved. ... And more than ever believers were added to the Lord, multitudes of both men and women, ... And the word of God continued to increase, and the number of disciples multiplied greatly ..."',
                    'reference' => 'Acts 2:47b; 5:14; 6:7',
                    'prayer' => 'O Lord, in Jesus’ name, we pray for this kind of rapid reproduction in '.$stack['location']['full_name'].'. May Your word increase and may disciples multiply.',
                ],
                [
                    'verse' => '"And the Lord added to their number day by day those who were being saved. ... And the word of God continued to increase, and the number of disciples multiplied greatly ..."',
                    'reference' => 'Acts 2:47b; 5:14; 6:7',
                    'prayer' => 'Bless Your church in '.$stack['location']['full_name'].' with spiritual gifts, godly leaders, unity in the faith and in the knowledge of Your Son, integrity, and an interdependence that nurtures the church in love.',
                ],
                [
                    'verse' => '"And the Lord added to their number day by day those who were being saved. ... And the word of God continued to increase, and the number of disciples multiplied greatly ..."',
                    'reference' => 'Acts 2:47b; 5:14; 6:7',
                    'prayer' => 'For the good of '.$stack['location']['full_name'].' and the glory of Your name, we pray for healthy churches here that are characterized by worship in spirit and truth, love-motivated gospel-sharing, intentional discipleship, and genuine life-on-life community.',
                ],
                [
                    'verse' => '"Pray also for me, that whenever I speak, words may be given to me so that I will fearlessly make known the mystery of the gospel."',
                    'reference' => 'Ephesians 6:18',
                    'prayer' => 'Father, we pray every disciple in '.$stack['location']['name'].' boldly proclaim the mystery of the gospel.',
                ],
                [
                    'verse' => '"By this everyone will know that you are my disciples, if you love one another."',
                    'reference' => 'John 13:35',
                    'prayer' => 'Lord, stir the hearts of Your people in '.$stack['location']['full_name'].' to agree with You and with one another in strong love that their '. number_format( $stack['location']['non_christians_int'] + $stack['location']['christian_adherents_int'] ) .' neighbors might know that they are yours.',
                ],
            ],
            /**
             * PRAYERS TARGETING CULTURAL CHRISTIANS
             */
            'christian_adherents' => [
                [
                    'verse' => '"Day after day, in the temple courts and from house to house, they never stopped teaching and proclaiming the good news"',
                    'reference' => 'Acts 5:42',
                    'prayer' => 'Father, give to the '.$stack['location']['believers'].' believers in '.$stack['location']['name'].' the same fire and passion for the truth of your Son, as the early church. May the message jump from one house to another house throughout the entire '.$stack['location']['admin_level_name'].'.',
                ],
                [
                    'verse' => '"And there arose on that day a great persecution against the church in Jerusalem, and they were all scattered throughout the regions of Judea and Samaria, except the apostles. ... Now those who were scattered went about preaching the word"',
                    'reference' => 'Acts 8:1b,4',
                    'prayer' => 'Father, we ask You to give the '.$stack['location']['believers'].' believers in '.$stack['location']['full_name'].' a collective vision to preach Your word and plant Your church, even in the face of persecution.',
                ],
            ],
            /**
             * PRAYERS TARGETING NON CHRISTIANS
             */
            'non_christians' => [
                [
                    'verse' => '"And there arose on that day a great persecution against the church in Jerusalem, and they were all scattered throughout the regions of Judea and Samaria, except the apostles. ... Now those who were scattered went about preaching the word"',
                    'reference' => 'Acts 8:1b,4',
                    'prayer' => 'Father, we know the expansion of your church is not a job reserved for foreign missionaries or paid staff or specifically gifted individuals. We affirm that you gave the Great Commission to your Bride and we pray that the church of '.$stack['location']['full_name'].' powerfully proclaim you even in persecution.',
                ],
                [
                    'verse' => '"And the Lord added to their number day by day those who were being saved. ... And more than ever believers were added to the Lord, multitudes of both men and women, ... And the word of God continued to increase, and the number of disciples multiplied greatly ..."',
                    'reference' => 'Acts 2:47b; 5:14; 6:7',
                    'prayer' => 'O Lord, in Jesus’ name, we pray for this kind of rapid reproduction in '.$stack['location']['full_name'].'. May Your word increase and may disciples multiply.',
                ],
            ]
        ];

    }

    public static function demogrphics_content_text( $stack ) : array {

        return [
            /**
             * PRAYERS TARGETING BELIEVERS
             */
            'believers' => [
                [
                    'section_summary' => 'The ' . $stack['location']['admin_level_name'] . ' of <strong>' . $stack['location']['full_name'] . '</strong> has a population of <strong>' . $stack['location']['population'] . '</strong>.<br><br> We estimate ' . $stack['location']['name'] . ' has <strong>' . $stack['location']['believers'] . '</strong> people who might know Jesus, <strong>' . $stack['location']['christian_adherents'] . '</strong> people who might know about Jesus culturally, and <strong>' . $stack['location']['non_christians'] . '</strong> people who do not know Jesus.<br><br>This is <strong>1</strong> believer for every <strong>' .  $stack['location']['lost_per_believer'] . '</strong> neighbors who need Jesus.',
                ],
            ],
            /**
             * PRAYERS TARGETING CULTURAL CHRISTIANS
             */
            'christian_adherents' => [
                [
                    'section_summary' => 'The '.$stack['location']['admin_level_name'].' of <strong>'.$stack['location']['full_name'].'</strong> has a population of <strong>'.$stack['location']['population'].'</strong>.<br><br> We estimate '.$stack['location']['name'].' has <strong>'.$stack['location']['believers'].'</strong> people who might know Jesus, <strong>'.$stack['location']['christian_adherents'].'</strong> people who might know about Jesus culturally, and <strong>'.$stack['location']['non_christians'].'</strong> people who do not know Jesus.<br><br>This is <strong>1</strong> believer for every <strong>'. $stack['location']['lost_per_believer'] .'</strong> neighbors who need Jesus.',
                ],
            ],
            /**
             * PRAYERS TARGETING NON CHRISTIANS
             */
            'non_christians' => [
                [
                    'section_summary' => 'The '.$stack['location']['admin_level_name'].' of <strong>'.$stack['location']['full_name'].'</strong> has a population of <strong>'.$stack['location']['population'].'</strong>.<br><br> We estimate '.$stack['location']['name'].' has <strong>'.$stack['location']['believers'].'</strong> people who might know Jesus, <strong>'.$stack['location']['christian_adherents'].'</strong> people who might know about Jesus culturally, and <strong>'.$stack['location']['non_christians'].'</strong> people who do not know Jesus.<br><br>This is <strong>1</strong> believer for every <strong>'. $stack['location']['lost_per_believer'] .'</strong> neighbors who need Jesus.',
                ],
            ]
        ];

    }

    public static function demogrphics_4_fact_text( $stack ) : array {

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
