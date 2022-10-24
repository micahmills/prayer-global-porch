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
                    'prayer' => '',
                ],
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
                'prayer' => 'There are '.$stack['location']['population'].' people living in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].'. Only about '.$stack['location']['believers'].' might be believers.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'The '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' has (about) '.$stack['location']['believers'].' people who know Jesus, '.$stack['location']['christian_adherents'].' people who know about him culturally, and '.$stack['location']['non_christians'].' people who are far from Jesus.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Pour Your Spirit out on the '.$stack['location']['population'].' citizens of '.$stack['location']['full_name'].', so that they might know Your name and the name of Your Son.',
                'reference' => 'Joel 2:28',
                'verse' => 'And afterward, I will pour out my Spirit on all people. Your sons and daughters will prophesy, Your old men will dream dreams, Your young men will see visions.',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Father, we suspect there is 1 believer for every '.$stack['location']['lost_per_believer'] . ' neighbors who are far from you in '.$stack['location']['name'].'. Please, give courage and opportunity to Your children to speak boldly.',
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
                'prayer' => 'Father, you know every soul, and you know who are Yours and who are yet to be Yours out of the '.$stack['location']['population'] .' people living in '. $stack['location']['full_name'] . '. Please, call Your lost sheep to Yourself.',
                'reference' => 'Ezekiel 36:24',
                'verse' => 'For I will take you out of the nations; I will gather you from all the countries and bring you back into Your own land.',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Father, bring Yourself glory in '.$stack['location']['name'].'. Through Your servants plant '.$stack['location']['new_churches_needed'].' new churches that love you, love one another, and make disciples this year.',
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

    public static function _for_movement( &$lists, $stack, $all = false ) {
        $section_label = 'Movement Health';
        $templates = [
            [
                'section_label' => $section_label,
                'prayer' => 'Father, we pray for every movement leader and disciple in the that they would have deepening intimacy with You.',
                'reference' => 'John 14:20',
                'verse' => 'On that day you will realize that I am in my Father, and you are in me, and I am in you.',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Spirit, raise up prayer leaders in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['full_name'].'.',
                'reference' => 'Colossians 4:2',
                'verse' => 'Devote yourselves to prayer, being watchful and thankful.',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Father, please call new believers full of yearning to see You praised in '.$stack['location']['full_name'].'.',
                'reference' => 'Psalm 96:3',
                'verse' => 'Declare his glory among the nations, his marvelous deeds among all peoples.',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Spirit, guide the '.$stack['location']['believers'].' believers in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' into all truth as they interpret Scriptures.',
                'reference' => 'Hebrews 4:12',
                'verse' => 'For the Word of God is alive and active. Sharper than any two-edged sword, it penetrates even to dividing soul and spirit, joints and marrow.',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Spirit, give the believers of the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' unity and humility as they work to bring the kingdom to new people and places.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Father, give the disciples of the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' an urgency of seeing every people and place reached for the gospel.',
                'reference' => 'John 9:4',
                'verse' => 'As long as it is day, we must do the works of him who sent me. Night is coming, when no one can work.',
            ],
            [
                'section_label' => $section_label,
                'prayer' => "Spirit, teach the ".$stack['location']['believers']." believers in ".$stack['location']['name']." that when they seek first God's Kingdom and His righteousness, He will abundantly provide all they need.",
                'reference' => '2 Corinthians 9:8',
                'verse' => 'And God is able to bless you abundantly, so that in all things at all times, having all that you need, you will abound in every good work.',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Spirit, give the disciples of the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' words, actions, signs and wonders, to proclaim the coming of the Kingdom with boldness and power.',
                'reference' => 'Matthew 10:7',
                'verse' => 'As you go, proclaim this message: "The kingdom of heaven has come near."',
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
                'prayer' => 'Father, please provide access to Your written Word in the ' . $stack['location']['primary_language'] . ' language.',
                'reference' => 'Isaiah 55:11',
                'verse' => 'So will My word be which goes forth from My mouth; It will not return to Me empty, Without accomplishing what I desire, And without succeeding in the matter for which I sent it.',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Father, please send out workers who can create videos and podcasts for the ' . $stack['location']['primary_language'] . ' language for the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].'.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Father, please provide digital and printed Bibles in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' specifically in the ' . $stack['location']['primary_language'] . ' language. Give success to those who distribute them.',
                'reference' => 'Isaiah 55:11',
                'verse' => 'So will My word be which goes forth from My mouth; It will not return to Me empty, Without accomplishing what I desire, And without succeeding in the matter for which I sent it.',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Father, please provide a translation of the Bible in the ' . $stack['location']['primary_language'] . ' language to every seeker in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].'.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Spirit, please, send out the truth about Jesus through YouTube, Tiktok, and Instagram in the ' . $stack['location']['primary_language'] . ' language for the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].'.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Lord, raise up workers in the ' . $stack['location']['primary_language'] . ' language, who can communicate accurately the Word of truth.',
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
                'prayer' => 'The primary religion in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['full_name'].' is '.$stack['location']['primary_religion'].'.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Father, give the '.$stack['location']['believers'].' believers in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' the skill to communicate Your gospel to those who follow '.$stack['location']['primary_religion'].' around them.',
                'reference' => 'Ephesians 6:19',
                'verse' => 'Pray also for me, that whenever I speak, words may be given me so that I will fearlessly make known the mystery of the gospel.',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Father, many people in '.$stack['location']['full_name'].' follow '.$stack['location']['primary_religion'].'. Please give them accurate knowledge of Jesus.',
                'reference' => 'Romans 10:2',
                'verse' => 'For I can testify about them that they are zealous for God, but their zeal is not based on knowledge.',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Lord, increase spiritual dissatisfaction among those in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' who follow '.$stack['location']['primary_religion'] . ', so that they would begin to seek You.',
                'reference' => 'Romans 10:2',
                'verse' => 'For I can testify about them that they are zealous for God, but their zeal is not based on knowledge.',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Even though the primary religion is '.$stack['location']['primary_religion'] . ' in '.$stack['location']['name'].', Lord, call to Yourself persons of peace among the community...those who fear you with the best knowledge they have.',
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
                'prayer' => 'Father, we cry out for a prayer movement in the '.$stack['location']['admin_level_name'].' of ' . $stack['location']['full_name'] . '. Please, stir the ' . $stack['location']['believers'] . ' believers here to pray for awakening.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => 'Prayer Movement',
                'prayer' => 'Lord, cause a passion for prayer among the people of ' . $stack['location']['full_name'] . '.',
                'reference' => 'John 17:20-21',
                'verse' => 'I (Jesus) pray also for those who will believe in me through their message, that all of them may be one, Father, just as you are in me and I am in you. May they also be in us so that the world my believe that you have sent me.',
            ],
            [
                'section_label' => 'Prayer Movement',
                'prayer' => 'Lord, stir the hearts of Your people in the '.$stack['location']['admin_level_name'].' of ' . $stack['location']['name'] . ' to agree with You and agree with one another in love.',
                'reference' => 'John 17:20-21',
                'verse' => 'I (Jesus) pray also for those who will believe in me through their message, that all of them may be one, Father, just as you are in me and I am in you. May they also be in us so that the world my believe that you have sent me.',
            ],
            [
                'section_label' => 'Prayer Movement',
                'prayer' => 'Spirit, teach the church in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' to increase their prayer for Your kingdom to come.',
                'reference' => 'Daniel 6:10',
                'verse' => 'Now when Daniel learned that the decree had been published, he went home to his upstairs room where the windows opened toward Jerusalem. Three times a day he got down on his knees and prayed, giving thanks to his God...',
            ],
            [
                'section_label' => 'Prayer Movement',
                'prayer' => 'Spirit, teach the children in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' to pray with passion and pleading for Your presence.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => 'Prayer Movement',
                'prayer' => 'Spirit, awaken a burning desire for Your presence and intimacy among the '.$stack['location']['population'].' people living in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].'.',
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
                'section_label' => 'Abundant Gospel Sowing',
                'prayer' => 'Lord, make the ' . $stack['location']['believers'] . ' believers in ' . $stack['location']['name'] . ' to be brave and clear with the gospel to their ' . $stack['location']['all_lost'] . ' neighbors.',
                'reference' => 'Acts 14:3',
                'verse' => 'So Paul and Barnabas spent considerable time there, speaking boldly for the Lord, who confirmed the message of his grace by enabling them to perform signs and wonders.',
            ],
            [
                'section_label' => 'Abundant Gospel Sowing',
                'prayer' => 'Father, please send new teachers into the harvest, who can correct the lies of our enemy in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['full_name'].'.',
                'reference' => 'Matthew 9:38',
                'verse' => 'Ask the Lord of the harvest, therefore, to send out workers into his harvest field.',
            ],
            [
                'section_label' => 'Abundant Gospel Sowing',
                'prayer' => 'Father, please send new apostles into the harvest, who can open up new communities for the gospel in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['full_name'].'.',
                'reference' => 'Matthew 9:38',
                'verse' => 'Ask the Lord of the harvest, therefore, to send out workers into his harvest field.',
            ],
            [
                'section_label' => 'Abundant Gospel Sowing',
                'prayer' => 'Father, please send new evangelists into the harvest in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['full_name'].'.',
                'reference' => 'Matthew 9:38',
                'verse' => 'Ask the Lord of the harvest, therefore, to send out workers into his harvest field.',
            ],
            [
                'section_label' => 'Abundant Gospel Sowing',
                'prayer' => 'Father, please raise up apostles, evangelists and preachers in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].'.',
                'reference' => 'Matthew 9:38',
                'verse' => 'Ask the Lord of the harvest, therefore, to send out workers into his harvest field.',
            ],
        ];

        // language
        if ( 'English' !== $stack['location']['primary_language'] ) {
            $templates = array_merge( $templates, [
                [
                    'section_label' => 'Abundant Gospel Sowing',
                    'prayer' => 'Father, please send new teachers into the harvest, who can teach in the ' . $stack['location']['primary_language'] . ' language and can combat the lies of our enemy.',
                    'reference' => 'Matthew 9:38',
                    'verse' => 'Ask the Lord of the harvest, therefore, to send out workers into his harvest field.',
                ],
                [
                    'section_label' => 'Abundant Gospel Sowing',
                    'prayer' => 'Father, please send new apostles into the harvest, who can speak the ' . $stack['location']['primary_language'] . ' language and open new communities for the gospel.',
                    'reference' => 'Matthew 9:38',
                    'verse' => 'Ask the Lord of the harvest, therefore, to send out workers into his harvest field.',
                ],
                [
                    'section_label' => 'Abundant Gospel Sowing',
                    'prayer' => 'Father, please send new evangelists into the harvest, who can speak the ' . $stack['location']['primary_language'] . ' language in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['full_name'].'.',
                    'reference' => 'Matthew 9:38',
                    'verse' => 'Ask the Lord of the harvest, therefore, to send out workers into his harvest field.',
                ],
                [
                    'section_label' => 'Abundant Gospel Sowing',
                    'prayer' => 'Father, please raise up apostles, evangelists and preachers in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].', who can speak Your gospel boldly and clearly in the ' . $stack['location']['primary_language'] . ' language.',
                    'reference' => '',
                    'verse' => '',
                ],
            ]);
        }

        // large christian adherent population
        if ( 20 < $stack['location']['percent_christian_adherents'] ) {
            $templates = array_merge( $templates, [
                [
                    'section_label' => 'Abundant Gospel Sowing',
                    'prayer' => 'Spirit, in mercy, convict the '.$stack['location']['christian_adherents'].' cultural christians in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' to look freshly into the gospel with curiosity like the angels.',
                    'reference' => '1 Peter 1:12',
                    'verse' => '... they spoke of the things that have now been told you by those who have preached the gospel to you by the Holy Spirit sent from heaven. Even angels long to look into these things.',
                ],
                [
                    'section_label' => 'Abundant Gospel Sowing',
                    'prayer' => 'Father, help the '.$stack['location']['believers'].' disciples in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' to challenge their '.$stack['location']['christian_adherents'].' culturally christian neighbors to make You first and to love You with all their heart, soul, strength, and mind.',
                    'reference' => 'Jeremiah 31:34',
                    'verse' => 'No longer will they teach their neighbor, or say to one another, ‘Know the LORD,’ because they will all know me, from the least of them to the greatest,” declares the LORD. “For I will forgive their wickedness and will remember their sins no more.”',
                ],
            ]);
        }

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
                'prayer' => 'Father, help '.$stack['location']['new_churches_needed'].' new simple churches start among the '.$stack['location']['population'].' people in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['full_name'].'. One within reach of everyone living here.',
                'reference' => 'Habakkuk 2:14',
                'verse' => 'For the earth will be filled with the knowledge of the glory of the Lord as the waters cover the sea.',
            ],
            [
                'section_label' => 'Church Planting',
                'prayer' => 'Spirit, please start new house churches in every neighborhood of the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].'.',
                'reference' => 'Habakkuk 2:14',
                'verse' => 'For the earth will be filled with the knowledge of the glory of the Lord as the waters cover the sea.',
            ],
            [
                'section_label' => 'Church Planting',
                'prayer' => 'Spirit, please give every church in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' a passion to plant another simple church.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => 'Church Planting',
                'prayer' => 'Father, show Your mercy on the '.$stack['location']['all_lost'].' people in '.$stack['location']['name'].' who are far from you. Please add '.$stack['location']['new_churches_needed'].' new house churches this year.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => 'Church Planting',
                'prayer' => 'Father, we agree with Your desire that the people in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' hear about You.',
                'reference' => 'Romans 15:21',
                'verse' => 'Those who were not told about him will see, and those who have not heard will understand.',
            ],
            [
                'section_label' => 'Church Planting',
                'prayer' => 'Father, let every disciple be a disciple maker, every home a training center, and every church a church planting movement in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['full_name'].'.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => 'Church Planting',
                'prayer' => 'Father, we ask for networks of simple churches in every city in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].', like Paul planted in Corinth and Ephesus.',
                'reference' => '1 Corinthians 16:19',
                'verse' => 'The churches in the province of Asia send you greetings. Aquila and Priscilla greet you warmly in the Lord, and so does the church that meets at their house.',
            ],
            [
                'section_label' => 'Church Planting',
                'prayer' => 'Jesus, '.$stack['location']['population'].' people live in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].'. Please, give them '.$stack['location']['new_churches_needed'].' new simple churches this year.',
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
                'prayer' => 'Father, move the ' . $stack['location']['believers'] . ' believers in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' to say "Not our will, but Yours be done", like Jesus.',
                'reference' => 'Luke 22:41-42',
                'verse' => 'He withdrew about a stone’s throw beyond them, knelt down and prayed, "Father, if you are willing, take this cup from me; yet not my will, but Yours be done."',
            ],
            [
                'section_label' => 'Obedience',
                'prayer' => 'Lord, stir the hearts of Your people in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' to agree with You and with one another in love.',
                'reference' => 'John 17:21',
                'verse' => 'that all of them may be one, Father, just as you are in me and I am in you. May they also be in us so that the world may believe that you have sent me.',
            ],
            [
                'section_label' => 'Obedience',
                'prayer' => 'Spirit, cause the '.$stack['location']['believers'].' believers in ' . $stack['location']['name'] . ' to obey with immediate, radical, costly obedience, like Abraham.',
                'reference' => 'Genesis 22:2-3',
                'verse' => 'Then God said, “Take Your son, Your only son, whom you love — Isaac — and go to the region of Moriah. Sacrifice him there as a burnt offering on a mountain I will show you.” Early the next morning Abraham got up and loaded his donkey. He took with him two of his servants and his son Isaac. When he had cut enough wood for the burnt offering, he set out for the place God had told him about.',
            ],
            [
                'section_label' => 'Obedience',
                'prayer' => 'Jesus, teach the believers in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' that love for You and obedience to You are connected.',
                'reference' => 'John 14:15',
                'verse' => 'If you love me, keep my commands.',
            ],
            [
                'section_label' => 'Obedience',
                'prayer' => 'Jesus, remind the disciples in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' to train each other to obey all that You commanded, and that You will be with them as they do it.',
                'reference' => 'Matthew 28:20',
                'verse' => '...teaching them to obey everything I have commanded you. And surely I am with you always, to the very end of the age.',
            ],
            [
                'section_label' => 'Obedience',
                'prayer' => 'Jesus, please help the '.$stack['location']['believers'].' believers in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' to be filled with joyful obedience at all times, as you modeled for us all.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => 'Obedience',
                'prayer' => 'Lord, please give the '.$stack['location']['believers'].' believers in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' the Spirit of wisdom and revelation, so that they might know you better.',
                'reference' => 'Ephesians 1:17',
                'verse' => 'I keep asking that the God of our Lord Jesus Christ, the glorious Father, may give you the Spirit of wisdom and revelation, so that you may know him better.',
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
                'verse' => 'I delight in Your decrees, I will not neglect Your word.',
            ],
            [
                'section_label' => 'Biblical Authority',
                'prayer' => 'Spirit, instill a desire within the people of the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' to hide Your word in their heart.',
                'reference' => 'Psalm 119:11',
                'verse' => 'I have hidden Your word in my heart that I might not sin against you.',
            ],
            [
                'section_label' => 'Biblical Authority',
                'prayer' => 'Spirit, help the people of the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' to be consumed with longing for Your Word at all times, like David.',
                'reference' => 'Psalm 119:20',
                'verse' => 'My soul is consumed with longing for Your laws at all times.',
            ],
            [
                'section_label' => 'Biblical Authority',
                'prayer' => 'Lord, teach Your Word to the people of the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].', so that they can follow Your ways all their life.',
                'reference' => 'Psalm 119:33',
                'verse' => 'Teach me, Lord, the way of Your decrees, that I may follow it to the end.',
            ],
            [
                'section_label' => 'Biblical Authority',
                'prayer' => 'Lord, teach the disciples in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' to trust Your Word, like a lamp, in the darkness around them.',
                'reference' => 'Psalm 119:105',
                'verse' => 'Your word is a lamp for my feet, a light on my path.',
            ],
            [
                'section_label' => 'Biblical Authority',
                'prayer' => 'Lord, teach the young people in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' to trust your Word and find the path of purity.',
                'reference' => 'Psalm 119:9',
                'verse' => 'How can a young person stay on the path of purity? By living according to your word.',
            ],
            [
                'section_label' => 'Biblical Authority',
                'prayer' => 'Spirit, we know that cultures come and go, even in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].', but the truth of Your Word endures generations.',
                'reference' => 'Psalm 119:89',
                'verse' => 'Your word, Lord, is eternal; it stands firm in the heavens.',
            ],
            [
                'section_label' => 'Biblical Authority',
                'prayer' => 'Father, defend those who are loyal to your Word in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].', even against fierce enemies.',
                'reference' => 'Psalm 119:61',
                'verse' => 'Though the wicked bind me with ropes, I will not forget your law.',
            ],
            [
                'section_label' => 'Biblical Authority',
                'prayer' => 'Spirit, fill the '.$stack['location']['population'].' souls living in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' with a taste for your Word. Make it sweet as honey in their mouth.',
                'reference' => 'Psalm 119:103',
                'verse' => 'How sweet are your words to my taste, sweeter than honey to my mouth!',
            ],
            [
                'section_label' => 'Biblical Authority',
                'prayer' => 'Spirit, fill the '.$stack['location']['believers'].' believers in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' with tears, because God is not obeyed by those around them.',
                'reference' => 'Psalm 119:136',
                'verse' => 'Streams of tears flow from my eyes, for your law is not obeyed.',
            ],
            [
                'section_label' => 'Biblical Authority',
                'prayer' => 'Father, see the suffering of Your people in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].'. Deliver all those who have not forgotten your Word.',
                'reference' => 'Psalm 119:153',
                'verse' => 'Look on my suffering and deliver me, for I have not forgotten your law.',
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
                'prayer' => 'Father, please raise up apostles, evangelists and preachers in '.$stack['location']['name'].' who can speak Your gospel boldly and clearly in the ' . $stack['location']['primary_language'] . ' language.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => 'Local Leadership',
                'prayer' => 'Father, send workers into the harvest in '.$stack['location']['full_name'].'.',
                'reference' => 'Matthew 9:38',
                'verse' => 'Ask the Lord of the harvest, therefore, to send out workers into his harvest field.',
            ],
            [
                'section_label' => 'Local Leadership',
                'prayer' => 'Spirit, build the strength and maturity of the local leaders in '.$stack['location']['full_name'].'. Show them that faithfulness is more important than knowledge. Show them that the Spirit, the Word, and prayer is enough in order to grow and lead. ',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => 'Local Leadership',
                'prayer' => 'God, we ask you to raise up elders and deacons from the '.$stack['location']['believers'].' believers in '.$stack['location']['name'].', who will serve the church and equip it to do Your work.',
                'reference' => 'Ephesians 4:11',
                'verse' => 'So Christ himself gave the apostles, the prophets, the evangelists, the pastors and teachers, to equip his people for works of service, so that the body of Christ may be built up until we all reach unity in the faith and in the knowledge of the Son of God and become mature, attaining to the whole measure of the fullness of Christ.',
            ],
            [
                'section_label' => 'Local Leadership',
                'prayer' => 'Lord, give grace to the local leaders to shepherd the '.$stack['location']['believers'].' believers in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].'.',
                'reference' => 'John 10:11',
                'verse' => 'I am the good shepherd. The good shepherd lays down his life for the sheep.',
            ],
            [
                'section_label' => 'Local Leadership',
                'prayer' => 'Lord, raise up local leaders in every town in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].'.',
                'reference' => 'Titus 1:5',
                'verse' => 'The reason I left you in Crete was that you might put in order what was left unfinished and appoint elders in every town, as I directed you.',
            ],
            [
                'section_label' => 'Local Leadership',
                'prayer' => 'Lord, for the leaders in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].', let the eyes of their hearts be enlightened in order that they may know the hope to which they are called.',
                'reference' => 'Ephesians 1:18',
                'verse' => 'I pray that the eyes of your heart may be enlightened in order that you may know the hope to which he has called you, the riches of his glorious inheritance in his holy people, and his incomparably great power for us who believe. ',
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
                'prayer' => 'God, guide the '.$stack['location']['believers'].' believers in '.$stack['location']['name'].' to multiply spiritual families that love You, love each other, and make disciples.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => 'Simple Churches',
                'prayer' => 'Spirit, help the '.$stack['location']['believers'].' believers in '.$stack['location']['name'].' to start simple multiplying churches in their homes.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => 'Simple Churches',
                'prayer' => 'Spirit, help the '.$stack['location']['believers'].' believers find persons of peace today among the '.$stack['location']['all_lost'].' lost neighbors around them. And help them start discovery bible studies in these unbelieving homes.',
                'reference' => 'Acts 10:30-33',
                'verse' => 'Suddenly a man in shining clothes stood before me and said, ‘Cornelius, God has heard Your prayer and remembered Your gifts to the poor. Send to Joppa for Simon who is called Peter. He is a guest in the home of Simon the tanner, who lives by the sea.’ So I sent for you immediately, and it was good of you to come. Now we are all here in the presence of God to listen to everything the Lord has commanded you to tell us.”',
            ],
            [
                'section_label' => 'Simple Churches',
                'prayer' => 'Father, we pray that the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' be filled with simple churches in every neighborhood.',
                'reference' => 'Isaiah 11:9',
                'verse' => 'For the earth will be full of the knowledge of the Lord, as the waters cover the sea.',
            ],
            [
                'section_label' => 'Simple Churches',
                'prayer' => 'Father, we ask for '.$stack['location']['new_churches_needed'].' new simple churches in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['full_name'].'. Place a simple church in every community of the '.$stack['location']['population'].' people living here.',
                'reference' => 'Psalm 72:19',
                'verse' => 'And blessed be His glorious name forever; And may the whole earth be filled with His glory. Amen, and Amen.',
            ],
            [
                'section_label' => 'Simple Churches',
                'prayer' => 'Spirit, teach the '.$stack['location']['believers'].' believers in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' the wisdom of how to form simple, reproducible churches of 12-30 in every neighborhood.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => 'Simple Churches',
                'prayer' => 'Father, bless the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' with a multiplying movement of house churches.',
                'reference' => 'Numbers 14:21',
                'verse' => '...but indeed, as I live, all the earth will be filled with the glory of the Lord.',
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
                'prayer' => 'Jesus, all authority was given to you, and you commanded all disciples in '. $stack['location']['full_name'] . ' to make more disciples, and you promised to be with them. May Your power and their obedience make more disciples today.',
                'reference' => 'Matthew 28:18',
                'verse' => 'All authority in heaven and on earth has been given to me. Therefore go and make disciples of all nations, baptizing them in the name of the Father and of the Son and of the Holy Spirit, and teaching them to obey everything I have commanded you. And surely I am with you always, to the very end of the age.',
            ],
            [
                'section_label' => $section_label['m'],
                'prayer' => 'Father, help the '.$stack['location']['believers'].' believers in '.$stack['location']['name'].' to know that You can make a big impact through their simple obedience today.',
                'reference' => 'Exodus 19:6',
                'verse' => '... you will be for me a kingdom of priests ...',
            ],
            [
                'section_label' => $section_label['m'],
                'prayer' => 'Spirit, please defend the '.$stack['location']['believers'].' believers in '.$stack['location']['name'].' against self-centered spirituality. Open their eyes to the fields white for harvest around them.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => $section_label['m'],
                'prayer' => 'Spirit, please defend the '.$stack['location']['believers'].' believers in '.$stack['location']['name'].' against an unwillingness to suffer. Give them courage to face social rejection.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => $section_label['m'],
                'prayer' => 'Father, please raise up pastors and teachers in '.$stack['location']['name'].' who can speak Your gospel boldly and clearly in the ' . $stack['location']['primary_language'] . ' language.',
                'reference' => 'Romans 10:14',
                'verse' => 'How, then, can they call on the one they have not believed in? And how can they believe in the one of whom they have not heard? And how can they hear without someone preaching to them? And how can anyone preach unless they are sent?',
            ],
            [
                'section_label' => $section_label['m'],
                'prayer' => 'Please, teach the '.$stack['location']['believers'].' believers in '.$stack['location']['full_name'].' how to pray to you and how to listen for Your voice. That they might follow you into the good works you have prepared for them.',
                'reference' => 'John 10:27',
                'verse' => 'My sheep listen to my voice; I know them, and they follow me.',
            ],
            [
                'section_label' => $section_label['m'],
                'prayer' => 'Please, convict the '.$stack['location']['believers'].' believers in '.$stack['location']['full_name'].' to look to You as their only hope for strength and fruitfulness and life.',
                'reference' => 'John 15:5',
                'verse' => 'I am the vine; you are the branches. If you remain in me and I in you, you will bear much fruit; apart from me you can do nothing.',
            ],
            [
                'section_label' => $section_label['m'],
                'prayer' => 'Father, convict the '.$stack['location']['believers'].' believers in '.$stack['location']['name'].' to be holy and righteous. Inspire them to gather in small groups for accountability and spiritual growth.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => $section_label['m'],
                'prayer' => 'Father, encourage the '.$stack['location']['believers'].' believers in '.$stack['location']['name'].' to not just be consumers of knowledge but be producers of love, mercy, kindness, and justice.',
                'reference' => '1 John 3:18',
                'verse' => '...let us not love with words or speech but with actions and in truth.',
            ],
            [
                'section_label' => $section_label['m'],
                'prayer' => 'Father, help the '.$stack['location']['believers'].' believers in '.$stack['location']['name'].' to be spiritually intentional with their relationships among their '.$stack['location']['all_lost'].' lost friends and neighbors.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => $section_label['m'],
                'prayer' => 'Lord, we know that you invest more in those who have been faithful with what they have been given. Please, richly bless each faithful believer in '.$stack['location']['name'].' with more spiritual insight, wisdom, courage and vision.',
                'reference' => 'Matthew 25:28',
                'verse' => 'So take the bag of gold from him and give it to the one who has ten bags. For whoever has will be given more, and they will have an abundance. Whoever does not have, even what they have will be taken from them.',
            ],
            [
                'section_label' => $section_label['m'],
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
                'prayer' => 'Spirit, encourage the disciples in '.$stack['location']['full_name'].' to live with urgency and a passion for making more disciples.',
                'reference' => 'James 4:14',
                'verse' => 'Yet you do not know what Your life will be like tomorrow. You are just a vapor that appears for a little while and then vanishes away.',
            ],
            [
                'section_label' => 'Urgency',
                'prayer' => 'Spirit, defend the church in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' against being inward-focused.',
                'reference' => 'Isaiah 61:1',
                'verse' => 'The Spirit of the Sovereign LORD is on me, because the LORD has anointed me to proclaim good news to the poor. He has sent me to bind up the brokenhearted, to proclaim freedom for the captives and release from darkness for the prisoners.',
            ],
            [
                'section_label' => 'Urgency',
                'prayer' => 'Spirit, encourage the church in '.$stack['location']['full_name'].' to make the most of every opportunity.',
                'reference' => 'Ephesians 5:15',
                'verse' => 'Be very careful, then, how you live—not as unwise but as wise, making the most of every opportunity, because the days are evil.',
            ],
            [
                'section_label' => 'Urgency',
                'prayer' => 'Father, you desire the people in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' to see about you and hear about you.',
                'reference' => 'Romans 15:21',
                'verse' => 'Those who were not told about him will see, and those who have not heard will understand.',
            ],
            [
                'section_label' => 'Urgency',
                'prayer' => 'Jesus, your return is closer than when we first believed. Please, set urgency in the hearts of the people living in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].'.',
                'reference' => 'Romans 13:11',
                'verse' => 'Besides this you know the time, that the hour has come for you to wake from sleep. For salvation is nearer to us now than when we first believed.',
            ],
            [
                'section_label' => 'Urgency',
                'prayer' => 'Spirit, disrupt complacency in the '.$stack['location']['believers'].' believers living in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].'. Remind them you are coming soon.',
                'reference' => 'Matthew 24:42',
                'verse' => 'Therefore, stay awake, for you do not know on what day your Lord is coming.',
            ],
            [
                'section_label' => 'Urgency',
                'prayer' => 'Spirit, give faith and responsive hearts to the '.$stack['location']['population'].' citizens of the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].'.',
                'reference' => 'Isaiah 55:6',
                'verse' => 'Seek the Lord while he may be found; call upon him while he is near;',
            ],
            [
                'section_label' => 'Urgency',
                'prayer' => 'Spirit, renew the call of John the Baptist in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].'. Send out bold servants who will call all to repent.',
                'reference' => 'Matthew 3:2',
                'verse' => 'Repent, for the kingdom of heaven is at hand.',
            ],
            [
                'section_label' => 'Urgency',
                'prayer' => 'Father, set on fire the hearts and passion of your church in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].'.',
                'reference' => 'Romans 12:11',
                'verse' => 'Do not be slothful in zeal, be fervent in spirit, serve the Lord.',
            ],
            [
                'section_label' => 'Urgency',
                'prayer' => 'Spirit, awaken the sleepers and call them to repent and be baptized in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].'. Set an urgency in their hearts.',
                'reference' => 'Acts 22:16',
                'verse' => 'And now why do you wait? Rise and be baptized and wash away your sins, calling on his name.’',
            ],
            [
                'section_label' => 'Urgency',
                'prayer' => 'Jesus, defend your people in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' against the difficulty of these last days.',
                'reference' => '2 Timothy 3:1-4',
                'verse' => 'But understand this, that in the last days there will come times of difficulty. For people will be lovers of self, lovers of money, proud, arrogant, abusive, disobedient to their parents, ungrateful, unholy, heartless, unappeasable, slanderous, without self-control, brutal, not loving good, treacherous, reckless, swollen with conceit, lovers of pleasure rather than lovers of God, having the appearance of godliness, but denying its power.',
            ],
            [
                'section_label' => 'Urgency',
                'prayer' => 'Jesus, purify the '.$stack['location']['believers'].' believers in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].' to not just be hearers, but doers of your Word.',
                'reference' => 'Revelation 1:3',
                'verse' => 'Blessed is the one who reads aloud the words of this prophecy, and blessed are those who hear, and who keep what is written in it, for the time is near.',
            ],
            [
                'section_label' => 'Urgency',
                'prayer' => 'Holy Spirit, have mercy on the simple who turned from you in the '.$stack['location']['admin_level_name'].' of '.$stack['location']['name'].'. Warn them again against their complacency.',
                'reference' => 'Proverbs 1:32',
                'verse' => 'For the simple are killed by their turning away, and the complacency of fools destroys them;',
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
                'prayer' => 'Father, please raise up apostles, evangelists and preachers in '.$stack['location']['name'].' who can speak Your gospel boldly and clearly in ' . $stack['location']['primary_language'] . '.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => 'The Church',
                'prayer' => 'Father, please provide access to Your Word. Please provide the translators, printers, books sellers, and app developers the resources and skill to get Your Word to '.$stack['location']['full_name'].'.',
                'reference' => 'Matthew 24:14',
                'verse' => 'And this gospel of the kingdom will be preached in the whole world as a testimony to all nations, and then the end will come.',
            ],
            [
                'section_label' => 'The Church',
                'prayer' => 'Lord we pray you unite the '.$stack['location']['believers'].' believers to pray at all times in the Spirit, with all prayer and supplication, for spiritual breakthrough in ' . $stack['location']['name'] . '.',
                'reference' => 'Philippians 4:6',
                'verse' => '... in every situation, by prayer and petition, with thanksgiving, present Your requests to God.',
            ],
            [
                'section_label' => 'The Church',
                'prayer' => 'Father, we pray that the people of ' . $stack['location']['full_name'] . ' will learn to study the Bible, understand it, obey it, and share it.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => 'The Church',
                'prayer' => 'God, we pray both the men and women of ' . $stack['location']['full_name'] . ' will find ways to meet in groups of two or three to encourage and correct one another from Your Word.',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => 'The Church',
                'prayer' => 'Lord, we pray for the believers in ' . $stack['location']['full_name'] . ' to be more like Jesus in their love for friends and enemies.',
                'reference' => 'Matthew 5:44',
                'verse' => 'But I tell you, love Your enemies and pray for those who persecute you.',
            ],
            [
                'section_label' => 'The Church',
                'prayer' => 'God, we pray for the believers in ' . $stack['location']['full_name'] . ' that they will know how to spend an hour in prayer with you.',
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
            [
                'section_label' => 'The Church',
                'prayer' => 'Father, remind your church in ' . $stack['location']['full_name'] . ' that you have set your Son over all rule and authority, power and dominion, and every name that is invoked.',
                'reference' => 'Ephesians 1:21',
                'verse' => '...he raised Christ from the dead and seated him at his right hand in the heavenly realms, far above all rule and authority, power and dominion, and every name that is invoked, not only in the present age but also in the one to come.',
            ],
            [
                'section_label' => 'The Church',
                'prayer' => 'Father, we rejoice that You who began a good work in the church of ' . $stack['location']['full_name'] . ' will carry it on to completion until the day of Jesus Christ!',
                'reference' => 'Philippians 1:6',
                'verse' => '...being confident of this, that he who began a good work in you will carry it on to completion until the day of Christ Jesus.',
            ],
            [
                'section_label' => 'The Church',
                'prayer' => 'Father, let love abound more and more in the church of ' . $stack['location']['full_name'] . '.',
                'reference' => 'Philippians 1:9',
                'verse' => 'And this is my prayer: that your love may abound more and more in knowledge and depth of insight',
            ],
            [
                'section_label' => 'The Church',
                'prayer' => 'Father, let knowledge and depth of insight abound more and more in the church of ' . $stack['location']['full_name'] . '.',
                'reference' => 'Philippians 1:9',
                'verse' => 'And this is my prayer: that your love may abound more and more in knowledge and depth of insight',
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

    public static function _non_christians( &$lists, $stack, $all = false ) {
        $section_label = 'Non-Christians';
        $templates = [
            [
                'section_label' => $section_label,
                'prayer' => 'Over '.$stack['location']['percent_non_christians'].' percent of the people of '.$stack['location']['name'].' are far from Jesus. Lord, please send Your gospel to them through the internet or radio or television today!',
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => $section_label,
                'prayer' => 'Over '.$stack['location']['percent_non_christians'].' percent of the people of '.$stack['location']['name'].' are far from Jesus.',
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

    public static function _christian_adherents( &$lists, $stack, $all = false ) {
        $section_label = 'Cultural Christians';
        $templates = [
            [
                'section_label' => $section_label,
                'prayer' => "Spirit, consider the ".$stack['location']['christian_adherents']." cultural Christians in ".$stack['location']['name'].". You promised to convict of sin, righteousness and judgement. Please show mercy and don't leave them idle and distant from Jesus.",
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => $section_label,
                'prayer' => "Spirit, ".$stack['location']['christian_adherents']." cultural Christians in ".$stack['location']['full_name']." likely have a Bible in their home. Please, send conviction for them to open it and read it for themselves.",
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => $section_label,
                'prayer' => "Spirit, teach the ".$stack['location']['christian_adherents']." cultural Christians in ".$stack['location']['full_name']." to pray from the heart and not with scripts or formulas only.",
                'reference' => '',
                'verse' => '',
            ],
            [
                'section_label' => $section_label,
                'prayer' => "Spirit, bless the ".$stack['location']['christian_adherents']." cultural Christians in ".$stack['location']['name']." with more knowlege and curiosity about your beautiful gospel, that they might claim it for themselves personally and intimately.",
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

    public static function _believers( &$lists, $stack, $all = false ) {
        $section_label = 'Believer Families';
        $templates = [
            [
                'section_label' => $section_label,
                'prayer' => "Spirit, consider the ".$stack['location']['believers']." believers in ".$stack['location']['name'].". You promised to convict of sin, righteousness and judgement. Please show mercy and don't leave them idle and distant from Jesus.",
                'verse' => '',
                'reference' => '',
            ],
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
