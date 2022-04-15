<?php
if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

/**
 * Class Prayer_Global_Menu
 */
class Prayer_Global_Menu {

    public $token = 'prayer_global_porch';
    public $page_title = 'Prayer Global Porch';

    private static $_instance = null;

    /**
     * Prayer_Global_Menu Instance
     *
     * Ensures only one instance of Prayer_Global_Menu is loaded or can be loaded.
     *
     * @since 0.1.0
     * @static
     * @return Prayer_Global_Menu instance
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    } // End instance()


    /**
     * Constructor function.
     * @access  public
     * @since   0.1.0
     */
    public function __construct() {

        add_action( "admin_menu", array( $this, "register_menu" ) );

    } // End __construct()


    /**
     * Loads the subnav page
     * @since 0.1
     */
    public function register_menu() {
        add_submenu_page( 'dt_extensions', $this->page_title, $this->page_title, 'manage_dt', $this->token, [ $this, 'content' ] );
    }

    /**
     * Menu stub. Replaced when Disciple.Tools Theme fully loads.
     */
    public function extensions_menu() {}

    /**
     * Builds page contents
     * @since 0.1
     */
    public function content() {

        if ( !current_user_can( 'manage_dt' ) ) { // manage dt is a permission that is specific to Disciple.Tools and allows admins, strategists and dispatchers into the wp-admin
            wp_die( 'You do not have sufficient permissions to access this page.' );
        }

        if ( isset( $_GET["tab"] ) ) {
            $tab = sanitize_key( wp_unslash( $_GET["tab"] ) );
        } else {
            $tab = 'general';
        }

        $link = 'admin.php?page='.$this->token.'&tab=';

        ?>
        <div class="wrap">
            <h2><?php echo esc_html( $this->page_title ) ?></h2>
            <h2 class="nav-tab-wrapper">
                <a href="<?php echo esc_attr( $link ) . 'general' ?>"
                   class="nav-tab <?php echo esc_html( ( $tab == 'general' || !isset( $tab ) ) ? 'nav-tab-active' : '' ); ?>">General</a>
                <a href="<?php echo esc_attr( $link ) . 'second' ?>" class="nav-tab <?php echo esc_html( ( $tab == 'second' ) ? 'nav-tab-active' : '' ); ?>">Second</a>
            </h2>

            <?php
            switch ( $tab ) {
                case "general":
                    $object = new Prayer_Global_Tab_General();
                    $object->content();
                    break;
                case "second":
                    $object = new Prayer_Global_Tab_Second();
                    $object->content();
                    break;
                default:
                    break;
            }
            ?>

        </div><!-- End wrap -->

        <?php
    }
}
Prayer_Global_Menu::instance();

/**
 * Class Prayer_Global_Tab_General
 */
class Prayer_Global_Tab_General {
    public function content() {
        ?>
        <div class="wrap">
            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-1">
                    <div id="post-body-content">
                        <!-- Main Column -->

                        <?php $this->main_column() ?>
                        <?php $this->meta_box_build(); ?>

                        <!-- End Main Column -->
                    </div><!-- end post-body-content -->
                    <div id="postbox-container-1" class="postbox-container">
                        <!-- Right Column -->


                        <!-- End Right Column -->
                    </div><!-- postbox-container 1 -->
                    <div id="postbox-container-2" class="postbox-container">
                    </div><!-- postbox-container 2 -->
                </div><!-- post-body meta box container -->
            </div><!--poststuff end -->
        </div><!-- wrap end -->
        <?php
    }

    public function main_column() {
        global $allowed_tags;
        $allowed_tags['script'] = array(
            'async' => array(),
            'src' => array()
        );
        $fields = prayer_global_fields();
        if ( isset( $_POST['pg_settings_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['pg_settings_nonce'] ) ), 'pg_settings' ) ) {
            $saved_fields = $fields;

            $post_list = dt_recursive_sanitize_array( $_POST['list'] );
            foreach ( $post_list as $field_key => $value ){
                if ( isset( $saved_fields[$field_key]["type"], $_POST['list'][$field_key] ) && $saved_fields[$field_key]["type"] === "textarea" ){ // if textarea
                    $post_list[$field_key] = wp_kses( wp_unslash( $_POST['list'][$field_key] ), $allowed_tags );
                }
            }

            foreach ( $post_list as $key => $value ) {
                if ( ! isset( $saved_fields[$key] ) ) {
                    $saved_fields[$key] = [];
                }
                $saved_fields[$key]['value'] = $value;
            }

            $fields = prayer_global_recursive_parse_args( $saved_fields, $fields );

            update_option( 'prayer_global_fields', $fields );
        }
        ?>
        <form method="post" class="metabox-table">
            <?php wp_nonce_field( 'pg_settings', 'pg_settings_nonce' ) ?>
            <!-- Box -->
            <table class="widefat striped">
                <thead>
                    <tr>
                        <th style="width:10%;">Images Mirror</th>
                        <th style="width:60%;"></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ( $fields as $key => $field ) :
                    if ( isset( $field["enabled"] ) && $field["enabled"] === false ){
                        continue;
                    }
                    if ( !isset( $field['type'] ) || 'text' === $field['type'] ) : ?>
                        <tr>
                            <td>
                                <?php echo esc_html( $field['label'] ); ?>
                            </td>
                            <td>
                                <input type="text" style="width:100%;" name="list[<?php echo esc_html( $key ); ?>]" id="<?php echo esc_html( $key ); ?>" value="<?php echo esc_html( $field['value'] ); ?>" placeholder="<?php echo esc_html( $field['label'] ); ?>"/>
                            </td>
                            <td style="vertical-align: middle;">
                                <?php echo esc_html( $field['description'] ); ?>
                            </td>
                        </tr>
                    <?php elseif ( 'textarea' === $field['type'] ) : ?>
                        <tr>
                            <td>
                                <?php echo esc_html( $field['label'] ); ?>
                            </td>
                            <td>
                                <textarea name="list[<?php echo esc_html( $key ); ?>]" style="width:100%;" id="<?php echo esc_html( $key ); ?>" placeholder="<?php echo esc_html( $field['label'] ); ?>"><?php echo wp_kses( $field['value'], $allowed_tags ); ?></textarea>
                            </td>
                            <td style="vertical-align: middle;">
                                <?php echo esc_html( $field['description'] ); ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
                <tr>
                    <td colspan="2">
                        <button class="button" type="submit">Update</button>
                    </td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </form>
        <br>
        <!-- End Box -->
        <?php
    }

    public function meta_box_build() {
        $json_url = prayer_global_image_json_url();
        $json = json_decode( wp_remote_retrieve_body( wp_remote_get($json_url) ), true  );
        $location_grid_images_version = get_option('location_grid_images_version');

        if ( isset( $_POST['pg_build_settings_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['pg_build_settings_nonce'] ) ), 'pg_build_settings' ) ) {
            update_option('location_grid_images_json', $json, true );
            update_option('location_grid_images_version', $json['version']);
            $location_grid_images_version = get_option('location_grid_images_version');
        }
        $update_needed = ( $location_grid_images_version == $json['version'] );
        ?>
        <form method="post" class="metabox-table">
            <?php wp_nonce_field( 'pg_build_settings', 'pg_build_settings_nonce' ) ?>
            <!-- Box -->
            <table class="widefat striped">
                <thead>
                    <tr>
                        <th>Build</th>
                    </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        Your Version: <?php echo $location_grid_images_version ?><br>
                        Live Version : <?php echo $json['version'] ?? 'Unknown' ?><br>
                         Update: <?php echo  ($update_needed) ? 'No' : '<strong>YES. PLEASE REBUILD THE DATABASE</strong>' ?><br><br>
                    </td>
                </tr>
                <tr>
                    <td>
                        <button type="submit" class="button">Rebuild images Database</button>
                    </td>
                </tr>
                </tbody>
            </table>
            <br>
            <!-- End Box -->
        </form>
        <?php
    }

    public function build_location_grid_photos_table(){

    }
}


/**
 * Class Prayer_Global_Tab_Second
 */
class Prayer_Global_Tab_Second {
    public function content() {
        ?>
        <div class="wrap">
            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">
                    <div id="post-body-content">
                        <!-- Main Column -->

                        <?php $this->main_column() ?>

                        <!-- End Main Column -->
                    </div><!-- end post-body-content -->
                    <div id="postbox-container-1" class="postbox-container">
                        <!-- Right Column -->

                        <?php $this->right_column() ?>

                        <!-- End Right Column -->
                    </div><!-- postbox-container 1 -->
                    <div id="postbox-container-2" class="postbox-container">
                    </div><!-- postbox-container 2 -->
                </div><!-- post-body meta box container -->
            </div><!--poststuff end -->
        </div><!-- wrap end -->
        <?php
    }

    public function main_column() {
        ?>
        <!-- Box -->
        <table class="widefat striped">
            <thead>
                <tr>
                    <th>Header</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        Content
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        <!-- End Box -->
        <?php
    }

    public function right_column() {
        ?>
        <!-- Box -->
        <table class="widefat striped">
            <thead>
                <tr>
                    <th>Information</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    Content
                </td>
            </tr>
            </tbody>
        </table>
        <br>
        <!-- End Box -->
        <?php
    }
}

