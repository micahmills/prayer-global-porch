<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.

/**
 * Class Prayer_Global_Feedback_Post_Type
 * Load the core post type hooks into the Disciple.Tools system
 */
class Prayer_Global_Feedback_Post_Type extends DT_Module_Base {

    public $post_type = "feedback";
    public $module = "feedback_base";
    public $single_name = 'Feedback';
    public $plural_name = 'Feedback';
    public static function post_type(){
        return 'feedback';
    }

    private static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        parent::__construct();
        if ( !self::check_enabled_and_prerequisites() ){
            return;
        }

        //setup post type
        add_action( 'after_setup_theme', [ $this, 'after_setup_theme' ], 100 );
        add_filter( 'dt_set_roles_and_permissions', [ $this, 'dt_set_roles_and_permissions' ], 20, 1 ); //after contacts

        //setup tiles and fields
        add_filter( 'dt_custom_fields_settings', [ $this, 'dt_custom_fields_settings' ], 10, 2 );
        add_filter( 'dt_details_additional_tiles', [ $this, 'dt_details_additional_tiles' ], 10, 2 );
        add_action( 'dt_details_additional_section', [ $this, 'dt_details_additional_section' ], 20, 2 );
        add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ], 99 );

        // hooks
        add_action( "post_connection_removed", [ $this, "post_connection_removed" ], 10, 4 );
        add_action( "post_connection_added", [ $this, "post_connection_added" ], 10, 4 );
        add_filter( "dt_post_update_fields", [ $this, "dt_post_update_fields" ], 10, 3 );
        add_filter( "dt_post_create_fields", [ $this, "dt_post_create_fields" ], 10, 2 );
        add_action( "dt_post_created", [ $this, "dt_post_created" ], 10, 3 );
        add_action( "dt_comment_created", [ $this, "dt_comment_created" ], 10, 4 );

        //list
        add_filter( "dt_user_list_filters", [ $this, "dt_user_list_filters" ], 10, 2 );
        add_filter( "dt_filter_access_permissions", [ $this, "dt_filter_access_permissions" ], 20, 2 );

    }

    public function after_setup_theme(){
        if ( class_exists( 'Disciple_Tools_Post_Type_Template' ) ) {
            new Disciple_Tools_Post_Type_Template( $this->post_type, $this->single_name, $this->plural_name );
        }
    }

    public function dt_set_roles_and_permissions( $expected_roles ){

        if ( !isset( $expected_roles["multiplier"] ) ){
            $expected_roles["multiplier"] = [

                "label" => __( 'Multiplier', 'prayer-global' ),
                "description" => "Interacts with Contacts and Groups",
                "permissions" => []
            ];
        }

        // if the user can access contact they also can access this post type
        foreach ( $expected_roles as $role => $role_value ){
            if ( isset( $expected_roles[$role]["permissions"]['access_contacts'] ) && $expected_roles[$role]["permissions"]['access_contacts'] ){
                $expected_roles[$role]["permissions"]['access_' . $this->post_type ] = true;
                $expected_roles[$role]["permissions"]['create_' . $this->post_type] = true;
                $expected_roles[$role]["permissions"]['update_' . $this->post_type] = true;
            }
        }

        if ( isset( $expected_roles["administrator"] ) ){
            $expected_roles["administrator"]["permissions"]['view_any_'.$this->post_type ] = true;
            $expected_roles["administrator"]["permissions"]['update_any_'.$this->post_type ] = true;
        }
        if ( isset( $expected_roles["dt_admin"] ) ){
            $expected_roles["dt_admin"]["permissions"]['view_any_'.$this->post_type ] = true;
            $expected_roles["dt_admin"]["permissions"]['update_any_'.$this->post_type ] = true;
        }

        return $expected_roles;
    }

    public function dt_custom_fields_settings( $fields, $post_type ){
        if ( $post_type === $this->post_type ){

            $fields['type'] = [
                'name'        => __( 'Type', 'prayer-global' ),
                'description' => __( 'Type of Lap', 'prayer-global' ),
                'type'        => 'key_select',
                'default'     => [
                    'location' => [
                        'label' => __( 'Location', 'prayer-global' ),
                        'description' => __( 'Custom laps', 'prayer-global' ),
                    ],
                    'general'   => [
                        'label' => __( 'General', 'prayer-global' ),
                        'description' => __( 'Do not manually create! System creates new laps when ready.', 'prayer-global' ),
                    ]
                ],
                'tile'     => 'status',
                'icon' => get_template_directory_uri() . '/dt-assets/images/nametag.svg',
                "default_color" => "#366184",
                "show_in_table" => 1,
            ];
            $fields['status'] = [
                'name'        => __( 'Status', 'prayer-global' ),
                'description' => __( 'Set the current status.', 'prayer-global' ),
                'type'        => 'key_select',
                'default'     => [
                    'new'   => [
                        'label' => __( 'New', 'prayer-global' ),
                        'description' => __( 'Is new', 'prayer-global' ),
                        'color' => "orange"
                    ],
                    'accepted'   => [
                        'label' => __( 'Accepted', 'prayer-global' ),
                        'description' => __( 'Accepted as valid feedback', 'prayer-global' ),
                        'color' => "#366184"
                    ],
                    'in_progress'   => [
                        'label' => __( 'In Progress', 'prayer-global' ),
                        'description' => __( 'Active and assigned', 'prayer-global' ),
                        'color' => "#366184"
                    ],
                    'closed_fixed' => [
                        'label' => __( 'Closed (Fixed)', 'prayer-global' ),
                        'description' => __( 'Closed and feedback implemented.', 'prayer-global' ),
                        'color' => "gray"
                    ],
                    'closed_ignore' => [
                        'label' => __( 'Closed (Denied)', 'prayer-global' ),
                        'description' => __( 'Closed and feedback not implemented.', 'prayer-global' ),
                        'color' => "gray"
                    ],
                ],
                'tile'     => 'status',
                'icon' => get_template_directory_uri() . '/dt-assets/images/status.svg',
                "default_color" => "#366184",
                "show_in_table" => 2,
                "in_create_form" => true,
            ];
            $fields['assigned_to'] = [
                'name'        => __( 'Assigned To', 'prayer-global' ),
                'description' => __( "Select the main person who is responsible for reporting on this record.", 'prayer-global' ),
                'type'        => 'user_select',
                'default'     => '',
                'tile' => 'status',
                'icon' => get_template_directory_uri() . '/dt-assets/images/assigned-to.svg',
                "show_in_table" => 16,
            ];
            $fields['contacts'] = [
                "name" => __( 'Reporter', 'prayer-global' ),
                "description" => '',
                "type" => "connection",
                "post_type" => "contacts",
                "p2p_direction" => "to",
                "p2p_key" => $this->post_type."_to_contacts",
                "tile" => "status",
                'icon' => get_template_directory_uri() . "/dt-assets/images/group-type.svg",
                'create-icon' => get_template_directory_uri() . "/dt-assets/images/add-contact.svg",
                "show_in_table" => 35
            ];

            $fields['payload'] = [
                'name'        => __( 'Payload', 'disciple_tools' ),
                'description' => _x( 'Raw response from reporter', 'Optional Documentation', 'disciple_tools' ),
                'type'        => 'textarea',
                'default' => '',
                'hidden' => false,
            ];

            $fields['user_hash'] = [
                'name'        => __( 'User Hash', 'disciple_tools' ),
                'description' => _x( 'User identifier', 'Optional Documentation', 'disciple_tools' ),
                'type'        => 'text',
                'default' => '',
                'hidden' => false,
            ];
            $fields['response'] = [
                'name'        => __( 'Response', 'disciple_tools' ),
                'description' => _x( 'Response', 'Optional Documentation', 'disciple_tools' ),
                'type'        => 'textarea',
                'default' => '',
                'tile' => 'status',
                'icon' => get_template_directory_uri() . '/dt-assets/images/assigned-to.svg',
                "show_in_table" => 16,
            ];

            // add location fields
            $fields['location_grid'] = [
                'name'        => __( 'Locations', 'disciple_tools' ),
                'description' => _x( 'The general location where this contact is located.', 'Optional Documentation', 'disciple_tools' ),
                'type'        => 'location',
                'mapbox'    => false,
                "in_create_form" => true,
                "tile" => "details",
                "icon" => get_template_directory_uri() . "/dt-assets/images/location.svg?v=2",
            ];
            $fields['location_grid_meta'] = [
                'name'        => __( 'Locations or Address', 'disciple_tools' ),
                'type'        => 'location_meta',
                "tile"      => "details",
                'mapbox'    => false,
                'hidden' => true,
                "in_create_form" => true,
                "icon" => get_template_directory_uri() . "/dt-assets/images/map-marker-multiple.svg?v=2",
            ];
            $fields["contact_address"] = [
                "name" => __( 'Address', 'disciple_tools' ),
                "icon" => get_template_directory_uri() . "/dt-assets/images/house.svg?v=2",
                "type" => "communication_channel",
                "tile" => "details",
                'mapbox'    => false,
                "customizable" => false
            ];
            if ( DT_Mapbox_API::get_key() ){
                $fields["contact_address"]["custom_display"] = true;
                $fields["contact_address"]["mapbox"] = true;
                $fields["contact_address"]["hidden"] = true;
                unset( $fields["contact_address"]["tile"] );
                $fields["location_grid"]["mapbox"] = true;
                $fields["location_grid"]["hidden"] = true;
                $fields["location_grid_meta"]["mapbox"] = true;
                $fields["location_grid_meta"]["hidden"] = false;
            }
        }

        if ( $post_type === "contacts" ){
            $fields[$this->post_type] = [
                "name" => $this->plural_name,
                "description" => '',
                "type" => "connection",
                "post_type" => $this->post_type,
                "p2p_direction" => "from",
                "p2p_key" => $this->post_type."_to_contacts",
                "tile" => "other",
                'icon' => get_template_directory_uri() . "/dt-assets/images/group-type.svg",
                'create-icon' => get_template_directory_uri() . "/dt-assets/images/add-group.svg",
                "show_in_table" => 35
            ];
        }

        return $fields;
    }

    public function dt_details_additional_tiles( $tiles, $post_type = "" ){
        if ( $post_type === $this->post_type ){
            $tiles["other"] = [ "label" => __( "Other", 'prayer-global' ) ];
        }
        return $tiles;
    }

    public function dt_details_additional_section( $section, $post_type ){
        if ( $post_type === $this->post_type && $section === "other" ) {
            // hide opposite key app
            $post = DT_Posts::get_post( $this->post_type, get_the_ID() );
            dt_write_log( $post );
            if ( isset( $post['type']['key'] ) && $post['type']['key'] === 'global' ) {
                ?>
                <script>
                    jQuery(document).ready(function(){
                        jQuery('.section-app-links.prayer_app_custom_magic_key').hide().prev().hide()
                        console.log('test')
                    })
                </script>
                <?php
            } else if ( isset( $post['type']['key'] ) && $post['type']['key'] === 'custom' ) {
                ?>
                <script>
                    jQuery(document).ready(function(){
                        jQuery('.section-app-links.prayer_app_global_magic_key').hide().prev().hide()
                        console.log('test')
                    })
                </script>
                <?php
            }
        }
    }

    public function post_connection_added( $post_type, $post_id, $field_key, $value ){
//        if ( $post_type === $this->post_type ){
//            if ( $field_key === "members" ){
//                // @todo change 'members'
//                // execute your code here, if field key match
//            }
//            if ( $field_key === "coaches" ){
//                // @todo change 'coaches'
//                // execute your code here, if field key match
//            }
//        }
//        if ( $post_type === "contacts" && $field_key === $this->post_type ){
//            // execute your code here, if a change is made in contacts and a field key is matched
//        }
    }

    //action when a post connection is removed during create or update
    public function post_connection_removed( $post_type, $post_id, $field_key, $value ){
//        if ( $post_type === $this->post_type ){
//            // execute your code here, if connection removed
//        }
    }

    //filter at the start of post update
    public function dt_post_update_fields( $fields, $post_type, $post_id ){
        if ( $post_type === $this->post_type ){
            $post = DT_Posts::get_post( $this->post_type, $post_id, true, false );
            if ( isset( $post['type']['key'] ) && 'custom' === $post['type']['key'] ) {
                if ( isset( $fields["start_date"] ) ){
                    $fields["start_time"] = $fields["start_date"];
                }
                if ( isset( $fields["end_date"] ) ){
                    $fields["end_time"] = $fields["end_date"];
                }
            }
        }
        return $fields;
    }


    //filter when a comment is created
    public function dt_comment_created( $post_type, $post_id, $comment_id, $type ){
    }

    // filter at the start of post creation
    public function dt_post_create_fields( $fields, $post_type ){
//        if ( $post_type === $this->post_type ){
//
//        }
        return $fields;
    }

    //action when a post has been created
    public function dt_post_created( $post_type, $post_id, $initial_fields ){

        // creates initial global lap
//        if ( $post_type === $this->post_type && isset( $initial_fields['type'] ) && 'global' === $initial_fields['type'] ){
//
//        }
    }

    private static function get_my_status(){
        global $wpdb;
        $post_type = self::post_type();
        $current_user = get_current_user_id();

        $results = $wpdb->get_results( $wpdb->prepare( "
            SELECT status.meta_value as status, count(pm.post_id) as count, count(un.post_id) as update_needed
            FROM $wpdb->postmeta pm
            INNER JOIN $wpdb->posts a ON( a.ID = pm.post_id AND a.post_type = %s and a.post_status = 'publish' )
            INNER JOIN $wpdb->postmeta status ON ( status.post_id = pm.post_id AND status.meta_key = 'status' )
            INNER JOIN $wpdb->postmeta as assigned_to ON a.ID=assigned_to.post_id
              AND assigned_to.meta_key = 'assigned_to'
              AND assigned_to.meta_value = CONCAT( 'user-', %s )
            LEFT JOIN $wpdb->postmeta un ON ( un.post_id = pm.post_id AND un.meta_key = 'requires_update' AND un.meta_value = '1' )
            GROUP BY status.meta_value, pm.meta_value
        ", $post_type, $current_user ), ARRAY_A);

        return $results;
    }

    //list page filters function
    private static function get_all_status_types(){
        /**
         * @todo adjust query to return count for update needed
         */
        global $wpdb;
        if ( current_user_can( 'view_any_'.self::post_type() ) ){
            $results = $wpdb->get_results($wpdb->prepare( "
                SELECT status.meta_value as status, count(status.post_id) as count, count(un.post_id) as update_needed
                FROM $wpdb->postmeta status
                INNER JOIN $wpdb->posts a ON( a.ID = status.post_id AND a.post_type = %s and a.post_status = 'publish' )
                LEFT JOIN $wpdb->postmeta un ON ( un.post_id = status.post_id AND un.meta_key = 'requires_update' AND un.meta_value = '1' )
                WHERE status.meta_key = 'status'
                GROUP BY status.meta_value
            ", self::post_type() ), ARRAY_A );
        } else {
            $results = $wpdb->get_results($wpdb->prepare("
                SELECT status.meta_value as status, count(pm.post_id) as count, count(un.post_id) as update_needed
                FROM $wpdb->postmeta pm
                INNER JOIN $wpdb->postmeta status ON( status.post_id = pm.post_id AND status.meta_key = 'status' )
                INNER JOIN $wpdb->posts a ON( a.ID = pm.post_id AND a.post_type = %s and a.post_status = 'publish' )
                LEFT JOIN $wpdb->dt_share AS shares ON ( shares.post_id = a.ID AND shares.user_id = %s )
                LEFT JOIN $wpdb->postmeta assigned_to ON ( assigned_to.post_id = pm.post_id AND assigned_to.meta_key = 'assigned_to' && assigned_to.meta_value = %s )
                LEFT JOIN $wpdb->postmeta un ON ( un.post_id = pm.post_id AND un.meta_key = 'requires_update' AND un.meta_value = '1' )
                WHERE ( shares.user_id IS NOT NULL OR assigned_to.meta_value IS NOT NULL )
                GROUP BY status.meta_value, pm.meta_value
            ", self::post_type(), get_current_user_id(), 'user-' . get_current_user_id() ), ARRAY_A);
        }

        return $results;
    }

    //build list page filters
    public static function dt_user_list_filters( $filters, $post_type ){
        /**
         * @todo process and build filter lists
         */
        if ( $post_type === self::post_type() ){
            $counts = self::get_my_status();
            $fields = DT_Posts::get_post_field_settings( $post_type );
            /**
             * Setup my filters
             */
            $active_counts = [];
            $update_needed = 0;
            $status_counts = [];
            $total_my = 0;
            foreach ( $counts as $count ){
                $total_my += $count["count"];
                dt_increment( $status_counts[$count["status"]], $count["count"] );
                if ( $count["status"] === "active" ){
                    if ( isset( $count["update_needed"] ) ) {
                        $update_needed += (int) $count["update_needed"];
                    }
                    dt_increment( $active_counts[$count["status"]], $count["count"] );
                }
            }

            $filters["tabs"][] = [
                "key" => "assigned_to_me",
                "label" => __( "Assigned to me", 'prayer-global' ),
                "count" => $total_my,
                "order" => 20
            ];
            // add assigned to me filters
            $filters["filters"][] = [
                'ID' => 'my_all',
                'tab' => 'assigned_to_me',
                'name' => __( "All", 'prayer-global' ),
                'query' => [
                    'assigned_to' => [ 'me' ],
                    'sort' => 'status'
                ],
                "count" => $total_my,
            ];
            foreach ( $fields["status"]["default"] as $status_key => $status_value ) {
                if ( isset( $status_counts[$status_key] ) ){
                    $filters["filters"][] = [
                        "ID" => 'my_' . $status_key,
                        "tab" => 'assigned_to_me',
                        "name" => $status_value["label"],
                        "query" => [
                            'assigned_to' => [ 'me' ],
                            'status' => [ $status_key ],
                            'sort' => '-post_date'
                        ],
                        "count" => $status_counts[$status_key]
                    ];
                    if ( $status_key === "active" ){
                        if ( $update_needed > 0 ){
                            $filters["filters"][] = [
                                "ID" => 'my_update_needed',
                                "tab" => 'assigned_to_me',
                                "name" => $fields["requires_update"]["name"],
                                "query" => [
                                    'assigned_to' => [ 'me' ],
                                    'status' => [ 'active' ],
                                    'requires_update' => [ true ],
                                ],
                                "count" => $update_needed,
                                'subfilter' => true
                            ];
                        }
                    }
                }
            }

            if ( current_user_can( 'view_any_' . self::post_type() ) ){
                $counts = self::get_all_status_types();
                $active_counts = [];
                $update_needed = 0;
                $status_counts = [];
                $total_all = 0;
                foreach ( $counts as $count ){
                    $total_all += $count["count"];
                    dt_increment( $status_counts[$count["status"]], $count["count"] );
                    if ( $count["status"] === "active" ){
                        if ( isset( $count["update_needed"] ) ) {
                            $update_needed += (int) $count["update_needed"];
                        }
                        dt_increment( $active_counts[$count["status"]], $count["count"] );
                    }
                }
                $filters["tabs"][] = [
                    "key" => "all",
                    "label" => __( "All", 'prayer-global' ),
                    "count" => $total_all,
                    "order" => 10
                ];
                // add assigned to me filters
                $filters["filters"][] = [
                    'ID' => 'all',
                    'tab' => 'all',
                    'name' => __( "All", 'prayer-global' ),
                    'query' => [
                        'sort' => '-post_date'
                    ],
                    "count" => $total_all
                ];

                foreach ( $fields["status"]["default"] as $status_key => $status_value ) {
                    if ( isset( $status_counts[$status_key] ) ){
                        $filters["filters"][] = [
                            "ID" => 'all_' . $status_key,
                            "tab" => 'all',
                            "name" => $status_value["label"],
                            "query" => [
                                'status' => [ $status_key ],
                                'sort' => '-post_date'
                            ],
                            "count" => $status_counts[$status_key]
                        ];
                        if ( $status_key === "active" ){
                            if ( $update_needed > 0 ){
                                $filters["filters"][] = [
                                    "ID" => 'all_update_needed',
                                    "tab" => 'all',
                                    "name" => $fields["requires_update"]["name"],
                                    "query" => [
                                        'status' => [ 'active' ],
                                        'requires_update' => [ true ],
                                    ],
                                    "count" => $update_needed,
                                    'subfilter' => true
                                ];
                            }
//                        foreach ( $fields["type"]["default"] as $type_key => $type_value ) {
//                            if ( isset( $active_counts[$type_key] ) ) {
//                                $filters["filters"][] = [
//                                    "ID" => 'all_' . $type_key,
//                                    "tab" => 'all',
//                                    "name" => $type_value["label"],
//                                    "query" => [
//                                        'status' => [ 'active' ],
//                                        'sort' => 'name'
//                                    ],
//                                    "count" => $active_counts[$type_key],
//                                    'subfilter' => true
//                                ];
//                            }
//                        }
                        }
                    }
                }
            }
        }
        return $filters;
    }

    // access permission
    public static function dt_filter_access_permissions( $permissions, $post_type ){
        if ( $post_type === self::post_type() ){
            if ( DT_Posts::can_view_all( $post_type ) ){
                $permissions = [];
            }
        }
        return $permissions;
    }

    // scripts
    public function scripts(){
        if ( is_singular( $this->post_type ) && get_the_ID() && DT_Posts::can_view( $this->post_type, get_the_ID() ) ){
            $test = "";
            // @todo add enqueue scripts
        }
    }
}


