<?php
/**
 * Post Type Template
 */

if ( !defined( 'ABSPATH' ) ){
    exit;
} // Exit if accessed directly.


/**
 * Prayer_Global_Verses Class
 * All functionality pertaining to project update post types in Prayer_Global_Verses.
 *
 * @package  Disciple_Tools
 * @since    0.1.0
 */
class Prayer_Global_Verses
{

    public $post_type;
    public $singular;
    public $plural;
    public $args;
    public $taxonomies;
    private static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Disciple_Tools_Prayer_Post_Type constructor.
     *
     * @param array $args
     * @param array $taxonomies
     */
    public function __construct( $args = [], $taxonomies = [] ){
        $this->post_type = 'verses';
        $this->singular = 'verse';
        $this->plural = 'verses';
        $this->args = $args;
        $this->taxonomies = $taxonomies;

        add_action( 'init', [ $this, 'register_post_type' ] );
        add_action( 'init', [ $this, 'create_tag_taxonomies' ], 0 );
        add_action( 'transition_post_status', [ $this, 'transition_post' ], 10, 3 );
        add_action( 'add_meta_boxes', [ $this, 'add_meta_box' ] );
        add_action( 'save_post', [ $this, 'save_post' ] );

        if ( is_admin() && isset( $_GET['post_type'] ) && $this->post_type === $_GET['post_type'] ){
            add_action( 'pre_get_posts', [ $this, 'dt_landing_order_by_date' ] );
            add_filter( 'manage_'.$this->post_type.'_posts_columns', [ $this, 'set_custom_edit_columns' ] );
            add_action( 'manage_'.$this->post_type.'_posts_custom_column', [ $this, 'custom_column' ], 10, 2 );
        }

    } // End __construct()

    public function add_meta_box( $post_type ) {
        if ( $this->post_type === $post_type ) {
            add_meta_box( $this->post_type . '_page_language', 'Post Language', [ $this, 'meta_box_page_language' ], $this->post_type, 'side', 'default' );
        }
    }

    public function meta_box_page_language( $post ) {
        $lang = get_post_meta( $post->ID, 'post_language', true );
        $langs = prayer_global_list_languages();
        if ( empty( $lang ) ){
            $lang = 'en_US';
        }
        ?>
        <select class="dt-magic-link-language-selector" name="dt-landing-language-selector">
            <?php foreach ( $langs as $code => $language ) : ?>
                <option value="<?php echo esc_html( $code ); ?>" <?php selected( $lang === $code ) ?>>
                    <?php echo esc_html( $language["flag"] ); ?> <?php echo esc_html( $language["native_name"] ); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php
    }

    public function save_post( $id ){
        if ( isset( $_POST["dt-landing-language-selector"] ) ){
            $post_submission = dt_recursive_sanitize_array( $_POST );
            update_post_meta( $post_submission["ID"], 'post_language', $post_submission["dt-landing-language-selector"] );
        }
    }


    /**
     * Register the post type.
     *
     * @access public
     * @return void
     */
    public function register_post_type() {
        register_post_type( $this->post_type, /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
            // let's now add all the options for this post type
            array(
                'labels' => array(
                    'name' => 'Verses', /* This is the Title of the Group */
                    'singular_name' => 'Verse', /* This is the individual type */
                    'all_items' => 'All Verses', /* the all items menu item */
                    'add_new' => 'Add New', /* The add new menu item */
                    'add_new_item' => 'Add New Verse', /* Add New Display Title */
                    'edit' => 'Edit', /* Edit Dialog */
                    'edit_item' => 'Edit Verse', /* Edit Display Title */
                    'new_item' => 'New Verse', /* New Display Title */
                    'view_item' => 'View Verse', /* View Display Title */
                    'search_items' => 'Search Verses', /* Search Custom Type Title */
                    'not_found' => 'Nothing found in the Database.', /* This displays if there are no entries yet */
                    'not_found_in_trash' => 'Nothing found in Trash', /* This displays if there is nothing in the trash */
                    'parent_item_colon' => ''
                ), /* end of arrays */
                'description' => 'Verses', /* Custom Type Description */
                'public' => false,
                'publicly_queryable' => false,
                'exclude_from_search' => true,
                'show_ui' => true,
                'query_var' => true,
                'menu_position' => 8, /* this is what order you want it to appear in on the left hand side menu */
                'menu_icon' => 'dashicons-book', /* the icon for the custom post type menu. uses built-in dashicons (CSS class name) */
                'rewrite' => array(
                    'slug' => 'verses',
                    'with_front' => false
                ), /* you can specify its url slug */
                'has_archive' => 'verses', /* you can rename the slug here */
                'capability_type' => 'post',
                'hierarchical' => false,
                /* the next one is important, it tells what's enabled in the post editor */
                'supports' => array( 'title', 'editor' )
            ) /* end of options */
        ); /* end of register post type */
    } // End register_post_type()



//create two taxonomies, genres and tags for the post type "tag"
    function create_tag_taxonomies()
    {
        // Add new taxonomy, NOT hierarchical (like tags)
        $labels = array(
            'name' => _x( 'Tags', 'taxonomy general name' ),
            'singular_name' => _x( 'Tag', 'taxonomy singular name' ),
            'search_items' =>  __( 'Search Tags' ),
            'popular_items' => __( 'Popular Tags' ),
            'all_items' => __( 'All Tags' ),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __( 'Edit Tag' ),
            'update_item' => __( 'Update Tag' ),
            'add_new_item' => __( 'Add New Tag' ),
            'new_item_name' => __( 'New Tag Name' ),
            'separate_items_with_commas' => __( 'Separate tags with commas' ),
            'add_or_remove_items' => __( 'Add or remove tags' ),
            'choose_from_most_used' => __( 'Choose from the most used tags' ),
            'menu_name' => __( 'Tags' ),
        );

        register_taxonomy('post_tag','verses',array(
            'hierarchical' => false,
            'labels' => $labels,
            'show_ui' => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var' => true,
            'rewrite' => array( 'slug' => 'post_tag' ),
        ));
    }

    /**
     * Order post list by date by default
     * @param $query
     * @return void
     */
    public function dt_landing_order_by_date( $query ){
        if ( !is_admin() ){
            return;
        }

        $screen = get_current_screen();
        if ( 'edit' == $screen->base
            && 'landing' == $screen->post_type
            && !isset( $_GET['orderby'] ) ){
            $query->set( 'orderby', 'date' );
            $query->set( 'order', 'ASC' );
        }
    }

    public function transition_post( $new_status, $old_status, $post ) {
        if ( ( 'publish' == $new_status || 'future' == $new_status ) && $post->post_type == $this->post_type ) {
            $post_id = $post->ID;
        }
    }

    // Add the custom columns to the book post type:
    public function set_custom_edit_columns( $columns ){
        unset( $columns['author'] );
        unset( $columns['date'] );
        $columns['verse'] = 'Verse';
        $columns['tags'] = 'Tags';
        $columns['language'] = 'Language';

        return $columns;
    }

    // Add the data to the custom columns for the book post type:
    public function custom_column( $column, $post_id ) {
        switch ( $column ) {
            case 'verse' :
                $content = get_post_field('post_content', $post_id);
                if (strlen($content) > 300) {
                    $stringCut = substr($content, 0, 300);
                    $endPoint = strrpos($stringCut, ' ');

                    $content = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                    $content .= '...';
                }
                echo $content;
                break;
            case 'language' :
                $language = get_post_meta( $post_id, 'post_language', true );
                if ( empty( $language ) ){
                    $language = "en_US";
                }
                $languages = prayer_global_list_languages();
                if ( !isset( $languages[$language]["flag"] ) ){
                    echo esc_html( $language );
                } else {
                    echo esc_html( $languages[$language]["flag"] );
                }
                break;
            case 'last_modified':
                $post = get_post( $post_id, ARRAY_A );
                if ( $post['post_modified'] !== $post['post_date'] ){
                    echo esc_html( $post['post_modified'] );
                }
                break;
        }
    }
} // End Class
Prayer_Global_Verses::instance();


