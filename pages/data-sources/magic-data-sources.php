<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.

class Prayer_Global_Porch_Data_Source extends DT_Magic_Url_Base
{
    public $magic = false;
    public $parts = false;
    public $page_title = 'Global Prayer - Data Sources';
    public $root = 'content_app';
    public $type = 'data_sources';
    public $type_name = 'Global Prayer - Data Sources';
    public static $token = 'content_app_data_sources';
    public $post_type = 'laps';

    private static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    } // End instance()

    public function __construct() {
        parent::__construct();

        $url = dt_get_url_path();
        if ( ( $this->root . '/' . $this->type ) === $url ) {

            $this->magic = new DT_Magic_URL( $this->root );
            $this->parts = $this->magic->parse_url_parts();


            // register url and access
            add_action( "template_redirect", [ $this, 'theme_redirect' ] );
            add_filter( 'dt_blank_access', function (){ return true;
            }, 100, 1 );
            add_filter( 'dt_allow_non_login_access', function (){ return true;
            }, 100, 1 );
            add_filter( 'dt_override_header_meta', function (){ return true;
            }, 100, 1 );

            // header content
            add_filter( "dt_blank_title", [ $this, "page_tab_title" ] ); // adds basic title to browser tab
            add_action( 'wp_print_scripts', [ $this, 'print_scripts' ], 1500 ); // authorizes scripts
            add_action( 'wp_print_styles', [ $this, 'print_styles' ], 1500 ); // authorizes styles


            // page content
            add_action( 'dt_blank_head', [ $this, '_header' ] );
            add_action( 'dt_blank_footer', [ $this, '_footer' ] );
            add_action( 'dt_blank_body', [ $this, 'body' ] ); // body for no post key

            add_filter( 'dt_magic_url_base_allowed_css', [ $this, 'dt_magic_url_base_allowed_css' ], 10, 1 );
            add_filter( 'dt_magic_url_base_allowed_js', [ $this, 'dt_magic_url_base_allowed_js' ], 10, 1 );
        }

    }

    public function dt_magic_url_base_allowed_js( $allowed_js ) {
        return [];
    }

    public function dt_magic_url_base_allowed_css( $allowed_css ) {
        return [];
    }

    public function header_javascript(){
        require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . 'assets/header.php' );
        ?>
        <link href="https://fonts.googleapis.com/css?family=Crimson+Text:400,400i,600|Montserrat:200,300,400" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ) ?>assets/fonts/ionicons/css/ionicons.min.css">
        <link rel="stylesheet" href="<?php echo esc_url( trailingslashit( plugin_dir_url( __DIR__ ) ) ) ?>assets/basic.css?ver=<?php echo esc_attr( fileatime( trailingslashit( plugin_dir_path( __DIR__ ) ) . 'assets/basic.css' ) ) ?>" type="text/css" media="all">
        <?php
    }

    public function footer_javascript(){
        require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . 'assets/footer.php' );
    }

    public function body(){
        require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . '/assets/nav.php' ) ?>

        <section class="pb_section" >
            <div class="container">
                <div class="row justify-content-md-center text-center mb-5">
                    <div class="col-lg-7">
                        <h2 class="mt-0 heading-border-top font-weight-normal">Data Sources</h2>
                    </div>
                </div>
                <div class="grid-x grid-margin-x grid-padding-y">

                    <!-- Item -->
                    <div class="cell">
                        <hr>
                        <p>
                            We acknowledge that there is no way to possess 100% accurate knowledge of the faith status or location status of
                            every person in the world.
                        </p>
                        <p>
                            No government has this exact number, no business, ... nobody but God has the facts of a person's true faith or whereabouts. Therefore, every demographic fact
                            is a mathematical deduction or extrapolation. (Sorry friends who like exact numbers.)
                        </p>
                        <p>
                            But leveraging the best data sources we can access, we have created a prayer tool to offer
                            informative prayer guidance featuring a unique, close-up location breakdown of the world.
                        </p>
                    </div>


                    <!-- Item -->
                    <div class="cell">
                        <hr>
                        <h3 class="secondary">Population Data</h3>
                        <p>The population data was acquired and cross-checked through multiple sources; country level data
                            is easy to find; most state level data is relatively easy to find; but county level data for non-western countries
                            often required significant research to compile recent census data.
                        </p>

                        <u>Country Level Population</u>
                        <ul>
                            <li><a href="https://data.un.org/">UN Data</a> - United Nations is a recognized source of
                                reliable population data and provided highest level country population.</li>
                        </ul>
                        <u>State Level Population</u><br>
                        <ul>
                            <li><a href="http://www.citypopulation.de/">City Population</a></li>
                            <li><a href="https://worldpopulationreview.com/">World Population Review</a></li>
                            <li>Various census reports published by governments.</li>
                        </ul>
                        <u>County Level Population</u><br>
                        Many western countries have organized, open census websites and population for county (administrative level 2) locations
                        are readily available. There are a number of governments that either deliberately do not easily publish their population data
                        or are not organized to distribute it easily. These countries required significant research to get population data.
                        <ul>
                            <li><a href="http://www.citypopulation.de/">City Population</a></li>
                            <li><a href="https://geonames.org">GeoNames</a></li>
                            <li>Various census reports published by governments</li>
                            <li>Various population reports published by humanitarian agencies</li>
                            <li>Wikipedia</li>
                            <li>State websites</li>
                        </ul>
                    </div>

                    <!-- Item -->
                    <div class="cell">
                        <hr>
                        <h3 class="secondary">Faith Status Data</h3>
                        <p>
                            <u>Categories</u><br>
                        </p>
                        <ul>
                            <li>Non-Christian<br><i>Someone who is far from a true knowledge Jesus Christ and far from a commitment to Jesus Christ.</i></li>
                            <li>Cultural Christian <br><i>(aka. Christian Adherent) Someone who has access to the knowledge of Jesus Christ through a cultural presence of the Church, but may or may not have been encouraged to seek Jesus in a personal, intimate way.</i></li>
                            <li>Believer<br><i>(aka. Disciple, Christian) Someone who has accepted in faith the knowledge of Jesus Christ and who has surrendered their life to Jesus as a personal savior.</i></li>
                        </ul>
                        <p>
                            <u><strong>Source and Method of Calculation</strong></u><br>
                        </p>
                        <ul>
                            <li>
                                <u>Source</u><br>
                                <a href="https://prayer.global/content_app/data_sources/">Joshua Project</a> - Values for "Christian Adherent" and "Evangelical"
                                (which determine unreached status) are often informed estimates, some more accurate than others at the country level.
                            </li>
                            <li>
                                <u>Extrapolation</u><br>
                                Prayer.Global has used the population data at the county and state levels from the Location Grid project and
                                divided percentages by the location population to arrive at the faith status population estimates.
                            </li>
                            <li>
                                <u>Acknowledged Weakness</u><br>
                                <p>
                                    At best, this methodology can offer a general, estimated target for the faith status of various locations. Actual faith status for each location
                                    could vary enormously above or below our estimates.
                                </p>
                                <p>
                                    All calculations of faith status data anywhere suffer the same limitations ... no matter the source ... they are all generalizations.
                                </p>
                                <p>
                                    Our belief is that the estimated numbers for non-christian, cultural christian, and disciple help the person praying to better understand the challenge facing the church
                                    in that location. It helps answer questions like: Is this a weak and small church surrounded by a dominant religion? Is this church holding out against an atheist culture?
                                    Is this a church trying to be faithful while surrounded by a lukewarm historic cultural church?
                                </p>
                                <p>
                                    More than the exact number, it is the distribution of knowledge of Jesus that is informative to the praying person.
                                </p>
                            </li>
                        </ul>
                    </div>

                    <!-- Item -->
                    <div class="cell">
                        <hr>
                        <h3 class="secondary">People Group Data</h3>
                        <ul>
                            <li><a href="https://joshuaproject.net/">Joshua Project</a> - </li>
                            <li><a href="https://grd.imb.org/">International Mission Board - Global Research</a></li>
                        </ul>
                    </div>

                    <!-- Item -->
                    <div class="cell">
                        <hr>
                        <h3 class="secondary">Language and Religion Data</h3>
                        <ul>
                            <li><a href="https://joshuaproject.net/resources/datasets">Joshua Project</a> - Two columns from Joshua Project resources were used to identify Primary Language and Primary Religion. These were identified at the country level, and future versions of Prayer Global intend to push deeper and more accurately into the spoken languages per location.</li>
                        </ul>
                    </div>


                    <!-- Item -->
                    <div class="cell">
                        <hr>
                        <h3 class="secondary">Administrative Divisions and Polygon Resources</h3>
                        <p>
                            <u>Location Grid Project</u><br>
                            The Location Grid Project is an open source project born out of the <a href="https://disciple.tools">Disciple Tools</a> open source project. It was built to support a standardized geolocation grid for disciple making movements activity.  (<a href="https://locationgrid.app">Location Grid Project Website</a>) (<a href="https://github.com/DiscipleTools/location-grid-project">Github Project</a>)
                        </p>
                        <p>
                            <u>Location Grid - Data Sources</u><br>
                            The Location Grid database and polygon set are derived these data sources, although significant processing of original polygon files were implemented for lightweight web distribution:
                        </p>
                        <ul>
                            <li><a href="https://gadm.org/">GADM</a> - GADM is an academic project that provides administrative polygon boundaries. These boundaries provided the seed for the grid system.</li>
                            <li><a href="https://geonames.org">GeoNames</a> - GeoNames is an open source location database of over 11 million places. It has a redistributable polygon set and associated location name database that also served as a seed for the location grid system.</li>
                            <li><a href="https://www.openstreetmap.org/">Open Street maps</a> - Open Street Maps is the best open alternative to closed mapping systems like Google Maps or Apple Maps. Intentional coordination and polygon preparation was designed for use with this tiling system.</li>
                            <li><a href="https://www.mapbox.com/">Mapbox</a> - Mapbox is a service that uses Open Street Maps system but adds a developer friendly programming and hosting layer to mapping source.</li>
                            <li><a href="https://locationgrid.app/">Location Grid - Database and Public Mirror</a> - An enormous amount of custom processing, configuration, and web hosting is provided as a result of the Location Grid Project.</li>
                        </ul>
                    </div>

                    <!-- Item -->
                    <div class="cell">
                        <hr>
                        <h3 class="secondary">City Data</h3>
                        <ul>
                            <li><a href="https://geonames.org">GeoNames</a> - Geonames provided the original 200k city names, population, longitude, and latitude.</li>
                            <li><a href="https://locationgrid.app">Location Grid - Geocoding</a> - Populated cities from geonames were coded against the Location Grid using the longitude, latitude. Then they were crafted into a new resource table.</li>
                        </ul>
                    </div>

                    <!-- Item -->
                    <div class="cell">
                        <hr>
                        <h3 class="secondary">Bible Citations</h3>
                        <ul>
                            <li>English - The <a href="https://www.zondervan.com/about-us/permissions/">NIV</a> is the primary translation used in the prayers. ESV used occasionally.</li>
                        </ul>
                    </div>

                </div>
            </div>
        </section>
        <!-- END section -->

        <?php require_once( trailingslashit( plugin_dir_path( __DIR__ ) ) . '/assets/working-footer.php' ) ?>
        <?php
    }

}
Prayer_Global_Porch_Data_Source::instance();
