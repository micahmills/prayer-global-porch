<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.

/**
 * Test that DT_Module_Base has loaded
 */
if ( ! class_exists( 'DT_Module_Base' ) ) {
    dt_write_log( 'Disciple.Tools System not loaded. Cannot load custom post type.' );
    return;
}

/**
 * Add any modules required or added for the post type
 */
add_filter( 'dt_post_type_modules', function( $modules ){
    $modules["laps_base"] = [
        "name" => "Laps",
        "enabled" => true,
        "locked" => true,
        "prerequisites" => [ 'contacts_base' ],
        "post_type" => "laps",
        "description" => "Prayer Laps"
    ];
    $modules["feedback_base"] = [
        "name" => "Feedback",
        "enabled" => true,
        "locked" => true,
        "prerequisites" => [ 'contacts_base' ],
        "post_type" => "feedback",
        "description" => "Feedback"
    ];


    return $modules;
}, 20, 1 );

require_once 'laps.php';
Prayer_Global_Laps_Post_Type::instance();

require_once 'feedback.php';
Prayer_Global_Feedback_Post_Type::instance();


function prayer_global_list_languages(){
    $available_language_codes = get_available_languages( plugin_dir_path( __DIR__ ) .'/support/languages' );
    array_unshift( $available_language_codes, 'en_US' );

    $available_translations = [];

    //flags from https://www.alt-codes.net/flags
    $translations = [
        'en_US' => [
            'language' => 'en_US',
            'english_name' => 'English (United States)',
            'native_name' => 'English',
            'flag' => '🇺🇸',
            'prayer_fuel' => true
        ],
        'es_ES' => [
            'language' => 'es_ES',
            'english_name' => 'Spanish (Spain)',
            'native_name' => 'Español',
            'flag' => '🇪🇸',
            'prayer_fuel' => true
        ],
        'fr_FR' => [
            'language' => 'fr_FR',
            'english_name' => 'French (France)',
            'native_name' => 'Français',
            'flag' => '🇫🇷',
            'prayer_fuel' => true
        ],
        'pt_PT' => [
            'language' => 'pt_PT',
            'english_name' => 'Portuguese',
            'native_name' => 'Português',
            'flag' => '🇵🇹',
            'prayer_fuel' => true
        ],
        'id_ID' => [
            'language' => "id_ID",
            'english_name' => 'Indonesian',
            'native_name' => 'Bahasa Indonesia',
            'flag' => '🇮🇩',
            'prayer_fuel' => true
        ],
        'nl_NL' => [
            'language' => "nl_NL",
            'english_name' => 'Dutch',
            'native_name' => 'Nederlands',
            'flag' => '🇳🇱',
        ],
        'ar_EG' => [
            'language' => 'ar_EG',
            'english_name' => 'Arabic',
            'native_name' => 'العربية',
            'flag' => '🇪🇬',
            'prayer_fuel' => true,
            'dir' => 'rtl'
        ],
        'ru_RU' => [
            'language' => 'ru_RU',
            'english_name' => 'Russian',
            'native_name' => 'русский',
            'flag' => '🇷🇺',
            'prayer_fuel' => true,
        ],
//        'bn_BD' => [
//            'language' => 'bn_BD',
//            'english_name' => 'Bengali',
//            'native_name' => 'বাংলা',
//            'flag' => '🇧🇩',
//            'prayer_fuel' => true,
//        ],
    ];

    foreach ( $available_language_codes as $code ){
        $code = str_replace( "prayer-global-", "", $code );
        if ( isset( $translations[$code] ) ){
            $available_translations[$code] = $translations[$code];
        }
    }
    return apply_filters( 'prayer_global_list_languages', $available_translations );
}
